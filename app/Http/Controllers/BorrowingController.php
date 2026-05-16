<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Borrowing;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::with(['item.category', 'borrower', 'approver']);

        if (!auth()->user()->isAdmin()) {
            $query->where('borrower_id', auth()->id());
        }

        if ($request->search) {
            $query->whereHas('item', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
                  ->orWhereHas('borrower', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
                  ->orWhere('reference_no', 'like', '%' . $request->search . '%');
        }
        if ($request->status) $query->where('status', $request->status);
        if ($request->date_from) $query->whereDate('borrow_date', '>=', $request->date_from);
        if ($request->date_to)   $query->whereDate('borrow_date', '<=', $request->date_to);

        $borrowings = $query->latest()->paginate(10)->withQueryString();

        return view('borrowings.index', compact('borrowings'));
    }

    public function create()
    {
        $items = Item::where('is_active', true)->where('stock', '>', 0)->with('category')->orderBy('name')->get();
        return view('borrowings.create', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id'              => 'required|exists:items,id',
            'borrower_name'        => 'required|string|max:255',
            'quantity'             => 'required|integer|min:1',
            'borrow_date'          => 'required|string',
            'expected_return_date' => 'required|string',
            'purpose'              => 'required|string|max:500',
            'notes'                => 'nullable|string',
        ]);

        // Parse d/m/Y from Flatpickr → Y-m-d
        $validated['borrow_date']          = \Carbon\Carbon::createFromFormat('d/m/Y', $validated['borrow_date'])->format('Y-m-d');
        $validated['expected_return_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $validated['expected_return_date'])->format('Y-m-d');

        if ($validated['expected_return_date'] < $validated['borrow_date']) {
            return back()->withErrors(['expected_return_date' => 'Tanggal kembali harus setelah tanggal pinjam.'])->withInput();
        }

        $item = Item::findOrFail($validated['item_id']);
        if ($item->stock < $validated['quantity']) {
            return back()->withErrors(['quantity' => "Stok tidak mencukupi. Stok tersedia: {$item->stock}"])->withInput();
        }

        $validated['borrower_id']  = auth()->id();
        $validated['status']       = 'pending';
        $validated['reference_no'] = 'BR-' . date('Ymd') . '-' . str_pad(Borrowing::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

        $borrowing = Borrowing::create($validated);
        ActivityLog::record('borrow_request', "Permintaan pinjam: {$item->name} x{$borrowing->quantity}", $borrowing);

        return redirect()->route('borrowings.index')->with('success', 'Permintaan peminjaman berhasil dikirim.');
    }

    public function show(Borrowing $borrowing)
    {
        $this->authorizeAccess($borrowing);
        $borrowing->load('item.category', 'borrower', 'approver');
        return view('borrowings.show', compact('borrowing'));
    }

    public function approve(Borrowing $borrowing)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Status peminjaman tidak dapat diubah.');
        }

        if ($borrowing->item->stock < $borrowing->quantity) {
            return back()->with('error', 'Stok tidak mencukupi untuk disetujui.');
        }

        DB::transaction(function () use ($borrowing) {
            $borrowing->update([
                'status'      => 'approved',
                'approved_by' => auth()->id(),
            ]);
            $borrowing->item->decrement('stock', $borrowing->quantity);
            ActivityLog::record('approve_borrowing', "Peminjaman {$borrowing->reference_no} disetujui", $borrowing);
        });

        return back()->with('success', 'Peminjaman berhasil disetujui.');
    }

    public function reject(Request $request, Borrowing $borrowing)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $request->validate(['reject_reason' => 'required|string|max:500']);

        if ($borrowing->status !== 'pending') {
            return back()->with('error', 'Status peminjaman tidak dapat diubah.');
        }

        $borrowing->update([
            'status'        => 'rejected',
            'approved_by'   => auth()->id(),
            'reject_reason' => $request->reject_reason,
        ]);
        ActivityLog::record('reject_borrowing', "Peminjaman {$borrowing->reference_no} ditolak", $borrowing);

        return back()->with('success', 'Peminjaman berhasil ditolak.');
    }

    public function return(Borrowing $borrowing)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        if ($borrowing->status !== 'approved') {
            return back()->with('error', 'Hanya peminjaman yang disetujui yang dapat dikembalikan.');
        }

        DB::transaction(function () use ($borrowing) {
            $borrowing->update([
                'status'             => 'returned',
                'actual_return_date' => today(),
            ]);
            $borrowing->item->increment('stock', $borrowing->quantity);
            ActivityLog::record('return_borrowing', "Barang {$borrowing->item->name} telah dikembalikan", $borrowing);
        });

        return back()->with('success', 'Barang berhasil dicatat sebagai dikembalikan.');
    }

    private function authorizeAccess(Borrowing $borrowing): void
    {
        if (!auth()->user()->isAdmin() && $borrowing->borrower_id !== auth()->id()) {
            abort(403);
        }
    }
}
