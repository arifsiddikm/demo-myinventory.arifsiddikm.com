<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\StockIn;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function index(Request $request)
    {
        $query = StockIn::with(['item.category', 'user']);

        if ($request->search) {
            $query->whereHas('item', fn($q) => $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('code', 'like', '%' . $request->search . '%'))
                ->orWhere('reference_no', 'like', '%' . $request->search . '%');
        }
        if ($request->date_from) $query->whereDate('received_date', '>=', $request->date_from);
        if ($request->date_to)   $query->whereDate('received_date', '<=', $request->date_to);

        $stockIns = $query->latest()->paginate(10)->withQueryString();
        $items    = Item::where('is_active', true)->orderBy('name')->get();

        return view('stock-in.index', compact('stockIns', 'items'));
    }

    public function create()
    {
        $items = Item::where('is_active', true)->with('category')->orderBy('name')->get();
        return view('stock-in.create', compact('items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id'        => 'required|exists:items,id',
            'quantity'       => 'required|integer|min:1',
            'supplier'       => 'nullable|string|max:255',
            'price_per_unit' => 'nullable|numeric|min:0',
            'received_date'  => 'required|string',
            'notes'          => 'nullable|string',
        ]);

        $validated['user_id']       = auth()->id();
        $validated['received_date'] = \Carbon\Carbon::createFromFormat('d/m/Y', $validated['received_date'])->format('Y-m-d');
        $validated['reference_no']  = 'SI-' . date('Ymd') . '-' . str_pad(StockIn::whereDate('created_at', today())->count() + 1, 4, '0', STR_PAD_LEFT);

        DB::transaction(function () use ($validated) {
            $stockIn = StockIn::create($validated);
            $stockIn->item->increment('stock', $validated['quantity']);
            ActivityLog::record('stock_in', "Barang masuk: {$stockIn->item->name} +{$stockIn->quantity}", $stockIn);
        });

        return redirect()->route('stock-in.index')->with('success', 'Barang masuk berhasil dicatat.');
    }

    public function show(StockIn $stockIn)
    {
        $stockIn->load('item.category', 'user');
        return view('stock-in.show', compact('stockIn'));
    }

    public function destroy(StockIn $stockIn)
    {
        DB::transaction(function () use ($stockIn) {
            $stockIn->item->decrement('stock', $stockIn->quantity);
            ActivityLog::record('delete_stock_in', "Data barang masuk {$stockIn->reference_no} dihapus");
            $stockIn->delete();
        });

        return redirect()->route('stock-in.index')->with('success', 'Data barang masuk berhasil dihapus.');
    }
}
