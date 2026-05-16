@extends('layouts.app')
@section('title','Barang Masuk') @section('page-title','Barang Masuk') @section('page-sub','Pencatatan penerimaan barang')

@section('content')
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="text-xs font-semibold text-slate-500 block mb-1.5">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama barang / referensi..."
                   class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
        </div>
        <div class="w-36">
            <label class="text-xs font-semibold text-slate-500 block mb-1.5">Dari</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
        </div>
        <div class="w-36">
            <label class="text-xs font-semibold text-slate-500 block mb-1.5">Sampai</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-xl hover:bg-primary-700 transition-colors">Cari</button>
            <a href="{{ route('stock-in.index') }}" class="px-4 py-2.5 bg-slate-100 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-200 transition-colors">Reset</a>
        </div>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('stock-in.create') }}" class="ml-auto px-4 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition-colors flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Catat Masuk
        </a>
        @endif
    </form>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-slate-50 border-b border-slate-100 text-xs text-slate-500 uppercase tracking-wider">
                <th class="px-5 py-3.5 text-left">Referensi</th>
                <th class="px-4 py-3.5 text-left">Barang</th>
                <th class="px-4 py-3.5 text-center">Qty</th>
                <th class="px-4 py-3.5 text-left hidden md:table-cell">Supplier</th>
                <th class="px-4 py-3.5 text-left">Tanggal</th>
                <th class="px-4 py-3.5 text-left hidden lg:table-cell">Dicatat</th>
                @if(auth()->user()->isAdmin())<th class="px-4 py-3.5 text-center">Aksi</th>@endif
            </tr></thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($stockIns as $si)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-4 font-mono text-xs text-slate-500">{{ $si->reference_no }}</td>
                    <td class="px-4 py-4">
                        <p class="font-semibold text-slate-800">{{ $si->item->name }}</p>
                        <p class="text-xs text-slate-400">{{ $si->item->category->name }}</p>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="font-bold text-emerald-600">+{{ $si->quantity }}</span>
                        <span class="text-xs text-slate-400">{{ $si->item->unit }}</span>
                    </td>
                    <td class="px-4 py-4 text-slate-500 hidden md:table-cell">{{ $si->supplier ?? '-' }}</td>
                    <td class="px-4 py-4 text-slate-600">{{ $si->received_date->format('d M Y') }}</td>
                    <td class="px-4 py-4 text-slate-500 hidden lg:table-cell">{{ $si->user->name }}</td>
                    @if(auth()->user()->isAdmin())
                    <td class="px-4 py-4 text-center">
                        <form action="{{ route('stock-in.destroy', $si) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="button" data-confirm-delete="Hapus data barang masuk ini? Stok akan berkurang kembali."
                                    class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-12 text-center text-slate-400">
                    <i class="fa-solid fa-inbox text-3xl mb-3 block text-slate-200"></i>Belum ada data barang masuk
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($stockIns->hasPages())<div class="px-5 py-4 border-t border-slate-100">{{ $stockIns->links() }}</div>@endif
</div>
@endsection
