@extends('layouts.app')
@section('title','Barang Keluar') @section('page-title','Barang Keluar') @section('page-sub','Pencatatan pengeluaran barang')

@section('content')
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="text-xs font-semibold text-slate-500 block mb-1.5">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama barang / referensi..."
                   class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
        </div>
        <div class="w-36"><label class="text-xs font-semibold text-slate-500 block mb-1.5">Dari</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none"></div>
        <div class="w-36"><label class="text-xs font-semibold text-slate-500 block mb-1.5">Sampai</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none"></div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-xl hover:bg-primary-700 transition-colors">Cari</button>
            <a href="{{ route('stock-out.index') }}" class="px-4 py-2.5 bg-slate-100 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-200 transition-colors">Reset</a>
        </div>
        @if(auth()->user()->isAdmin())
        <a href="{{ route('stock-out.create') }}" class="ml-auto px-4 py-2.5 bg-orange-600 text-white text-sm font-semibold rounded-xl hover:bg-orange-700 transition-colors flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Catat Keluar
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
                <th class="px-4 py-3.5 text-left hidden md:table-cell">Tujuan</th>
                <th class="px-4 py-3.5 text-left hidden md:table-cell">Penerima</th>
                <th class="px-4 py-3.5 text-left">Tanggal</th>
                @if(auth()->user()->isAdmin())<th class="px-4 py-3.5 text-center">Aksi</th>@endif
            </tr></thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($stockOuts as $so)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-4 font-mono text-xs text-slate-500">{{ $so->reference_no }}</td>
                    <td class="px-4 py-4">
                        <p class="font-semibold text-slate-800">{{ $so->item->name }}</p>
                        <p class="text-xs text-slate-400">{{ $so->item->category->name }}</p>
                    </td>
                    <td class="px-4 py-4 text-center font-bold text-orange-600">-{{ $so->quantity }} <span class="font-normal text-xs text-slate-400">{{ $so->item->unit }}</span></td>
                    <td class="px-4 py-4 text-slate-500 hidden md:table-cell">{{ $so->purpose ?? '-' }}</td>
                    <td class="px-4 py-4 text-slate-500 hidden md:table-cell">{{ $so->recipient ?? '-' }}</td>
                    <td class="px-4 py-4 text-slate-600">{{ $so->issued_date->format('d M Y') }}</td>
                    @if(auth()->user()->isAdmin())
                    <td class="px-4 py-4 text-center">
                        <form action="{{ route('stock-out.destroy', $so) }}" method="POST" class="inline">
                            @csrf @method('DELETE')
                            <button type="button" data-confirm-delete="Hapus data ini? Stok akan bertambah kembali."
                                    class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-12 text-center text-slate-400">
                    <i class="fa-solid fa-inbox text-3xl mb-3 block text-slate-200"></i>Belum ada data barang keluar
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($stockOuts->hasPages())<div class="px-5 py-4 border-t border-slate-100">{{ $stockOuts->links() }}</div>@endif
</div>
@endsection
