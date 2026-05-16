@extends('layouts.app')
@section('title', 'Inventaris Barang')
@section('page-title', 'Inventaris Barang')
@section('page-sub', 'Kelola semua data barang')

@section('content')
{{-- Filter --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="text-xs font-semibold text-slate-500 block mb-1.5">Cari Barang</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau kode barang..."
                   class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none">
        </div>
        <div class="w-40">
            <label class="text-xs font-semibold text-slate-500 block mb-1.5">Kategori</label>
            <select name="category_id" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
                <option value="">Semua</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-36">
            <label class="text-xs font-semibold text-slate-500 block mb-1.5">Stok</label>
            <select name="stock_status" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
                <option value="">Semua</option>
                <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Menipis</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-xl hover:bg-primary-700 transition-colors">
                <i class="fa-solid fa-search mr-1.5"></i>Cari
            </button>
            <a href="{{ route('items.index') }}" class="px-4 py-2.5 bg-slate-100 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-200 transition-colors">Reset</a>
        </div>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('items.create') }}" class="ml-auto px-4 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition-colors flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Tambah Barang
        </a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Barang</th>
                    <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori</th>
                    <th class="text-center px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok</th>
                    <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider hidden md:table-cell">Lokasi</th>
                    <th class="text-left px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider hidden lg:table-cell">Kondisi</th>
                    <th class="text-center px-4 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($items as $item)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            @if($item->image)
                            <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}" class="w-10 h-10 rounded-lg object-cover border border-slate-100">
                            @else
                            <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400">
                                <i class="fa-solid fa-box text-sm"></i>
                            </div>
                            @endif
                            <div>
                                <p class="font-semibold text-slate-800">{{ $item->name }}</p>
                                <p class="text-xs text-slate-400 font-mono">{{ $item->code }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <span class="px-2.5 py-1 bg-primary-50 text-primary-700 text-xs font-medium rounded-lg">{{ $item->category->name }}</span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        @if($item->isLowStock())
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-50 text-amber-700 text-sm font-bold rounded-lg">
                            <i class="fa-solid fa-triangle-exclamation text-xs"></i> {{ $item->stock }}
                        </span>
                        @else
                        <span class="text-slate-800 font-semibold">{{ $item->stock }}</span>
                        @endif
                        <span class="text-xs text-slate-400 ml-1">{{ $item->unit }}</span>
                    </td>
                    <td class="px-4 py-4 text-slate-500 text-sm hidden md:table-cell">{{ $item->location ?? '-' }}</td>
                    <td class="px-4 py-4 hidden lg:table-cell">
                        @php $condColors = ['baik'=>'emerald','rusak_ringan'=>'amber','rusak_berat'=>'red']; $c = $condColors[$item->condition] ?? 'slate'; @endphp
                        <span class="px-2.5 py-1 bg-{{ $c }}-50 text-{{ $c }}-700 text-xs font-medium rounded-lg">{{ $item->condition_label }}</span>
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('items.show', $item) }}" class="p-1.5 text-slate-500 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Detail">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <a href="{{ route('items.edit', $item) }}" class="p-1.5 text-slate-500 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                <i class="fa-solid fa-pen text-sm"></i>
                            </a>
                            <form action="{{ route('items.destroy', $item) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="button" data-confirm-delete="Hapus barang '{{ $item->name }}'?" class="p-1.5 text-slate-500 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash text-sm"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-slate-400">
                        <i class="fa-solid fa-box-open text-3xl mb-3 block text-slate-200"></i>
                        Tidak ada barang ditemukan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($items->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">
        {{ $items->links() }}
    </div>
    @endif
</div>
@endsection
