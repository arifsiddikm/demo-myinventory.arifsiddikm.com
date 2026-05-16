<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::with('category');

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->condition) {
            $query->where('condition', $request->condition);
        }
        if ($request->stock_status === 'low') {
            $query->whereColumn('stock', '<=', 'min_stock');
        }

        $items      = $query->latest()->paginate(10)->withQueryString();
        $categories = Category::all();

        return view('items.index', compact('items', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code'        => 'required|string|max:50|unique:items,code',
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'stock'       => 'required|integer|min:0',
            'min_stock'   => 'required|integer|min:0',
            'unit'        => 'required|string|max:50',
            'location'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'condition'   => 'required|in:baik,rusak_ringan,rusak_berat',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        $item = Item::create($validated);
        ActivityLog::record('create_item', "Barang '{$item->name}' berhasil ditambahkan", $item);

        return redirect()->route('items.index')->with('success', 'Barang berhasil ditambahkan.');
    }

    public function show(Item $item)
    {
        $item->load('category', 'stockIns.user', 'stockOuts.user', 'borrowings.borrower');
        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $categories = Category::all();
        return view('items.edit', compact('item', 'categories'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'code'        => 'required|string|max:50|unique:items,code,' . $item->id,
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'min_stock'   => 'required|integer|min:0',
            'unit'        => 'required|string|max:50',
            'location'    => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'condition'   => 'required|in:baik,rusak_ringan,rusak_berat',
            'is_active'   => 'boolean',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image) Storage::disk('public')->delete($item->image);
            $validated['image'] = $request->file('image')->store('items', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');
        $item->update($validated);
        ActivityLog::record('update_item', "Barang '{$item->name}' diperbarui", $item);

        return redirect()->route('items.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        if ($item->borrowings()->whereIn('status', ['approved'])->exists()) {
            return back()->with('error', 'Barang sedang dipinjam, tidak dapat dihapus.');
        }
        if ($item->image) Storage::disk('public')->delete($item->image);
        $name = $item->name;
        $item->delete();
        ActivityLog::record('delete_item', "Barang '{$name}' dihapus");

        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus.');
    }
}
