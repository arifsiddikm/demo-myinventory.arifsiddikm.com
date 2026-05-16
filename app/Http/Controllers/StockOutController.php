<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockOut;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOutController extends Controller
{
    public function index(Request $request)
    {
        $query = StockOut::with(['item.category', 'user']);

        if ($request->search) {
            $query->whereHas('item', fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
                  ->orWhere('reference_no', 'like', '%' . $request->search . '%');
        }
        if ($request->date_from) $query->whereDate('issued_date', '>=', $request->date_from);
        if ($request->date_to)   $query->whereDate('issued_date', '<=', $request->date_to);

        $stockOuts = $query->latest()->paginate(10)->withQueryString();
        $items     = Item::where('is_active', true)->orderBy('name')->get();

        return view('stock-out.index', compact('stockOuts', 'items'));
    }

    public function create()
    {
        $items = Item::where('is_active', true)->where('stock', '>', 0)->with('category')->orderBy('name')->get();
        return view('stock-out.create', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id'    => 'required|exists:items,id',
            'quantity'   => 'required|integer|min:1',
            'purpose'    => 'nullable|string|max:255',
            'recipient'  => 'nullable|string|max:255',
            'issued_date'=> 'required|string',
            'notes'      => 'nullable|string',
        ]);

        $item = Item::findOrFail($validated['item_id']);

        if ($item->stock < $validated['quantity']) {
            return back()->withErrors(['quantity' => "Stok tidak mencukupi. Stok tersedia: {$item->stock} {$item->unit}"])->withInput();
        }

        $validated['user_id']      = auth()->id();
        $validated['issued_date']  = \Carbon\Carbon::createFromFormat('d/m/Y', $validated['issued_date'])->format('Y-m-d');
        $validated['reference_no'] = 'SO-' . date('Ymd') . '-' . str_pad(StockOut::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

        DB::transaction(function () use ($validated, $item) {
            $stockOut = StockOut::create($validated);
            $item->decrement('stock', $validated['quantity']);
            ActivityLog::record('stock_out', "Barang keluar: {$item->name} -{$stockOut->quantity}", $stockOut);
        });

        return redirect()->route('stock-out.index')->with('success', 'Barang keluar berhasil dicatat.');
    }

    public function show(StockOut $stockOut)
    {
        $stockOut->load('item.category', 'user');
        return view('stock-out.show', compact('stockOut'));
    }

    public function destroy(StockOut $stockOut)
    {
        DB::transaction(function () use ($stockOut) {
            $stockOut->item->increment('stock', $stockOut->quantity);
            ActivityLog::record('delete_stock_out', "Data barang keluar {$stockOut->reference_no} dihapus");
            $stockOut->delete();
        });

        return redirect()->route('stock-out.index')->with('success', 'Data barang keluar berhasil dihapus.');
    }
}
