@extends('layouts.app')
@section('title', $item->name)
@section('page-title', $item->name)
@section('page-sub', 'Detail barang · ' . $item->code)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            @if($item->image)
            <img src="{{ asset('storage/'.$item->image) }}" alt="{{ $item->name }}" class="w-full h-48 object-cover">
            @else
            <div class="w-full h-48 bg-slate-100 flex items-center justify-center text-slate-300">
                <i class="fa-solid fa-box text-5xl"></i>
            </div>
            @endif
            <div class="p-5 space-y-4">
                <div>
                    <p class="text-xs text-slate-400 font-mono mb-1">{{ $item->code }}</p>
                    <h2 class="text-lg font-bold text-slate-800">{{ $item->name }}</h2>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-50 rounded-xl p-3 text-center">
                        <p class="text-2xl font-bold {{ $item->isLowStock() ? 'text-amber-600' : 'text-slate-800' }}">{{ $item->stock }}</p>
                        <p class="text-xs text-slate-400">{{ $item->unit }} tersedia</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3 text-center">
                        <p class="text-2xl font-bold text-slate-500">{{ $item->min_stock }}</p>
                        <p class="text-xs text-slate-400">Stok minimum</p>
                    </div>
                </div>
                <div class="space-y-2.5 text-sm">
                    <div class="flex justify-between"><span class="text-slate-400">Kategori</span><span class="font-medium text-slate-700">{{ $item->category->name }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Lokasi</span><span class="font-medium text-slate-700">{{ $item->location ?? '-' }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Kondisi</span><span class="font-medium text-slate-700">{{ $item->condition_label }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Status</span>
                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $item->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
                @if($item->description)
                <div class="border-t border-slate-100 pt-3">
                    <p class="text-xs text-slate-400 mb-1">Deskripsi</p>
                    <p class="text-sm text-slate-600">{{ $item->description }}</p>
                </div>
                @endif
                @if(auth()->user()->isAdmin())
                <a href="{{ route('items.edit', $item) }}" class="flex items-center justify-center gap-2 w-full py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 transition-colors mt-2">
                    <i class="fa-solid fa-pen"></i> Edit Barang
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-5">
        {{-- Recent Stock In --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-arrow-down-to-bracket text-emerald-500"></i> Riwayat Barang Masuk
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="bg-slate-50 text-xs text-slate-500 uppercase">
                        <th class="px-5 py-3 text-left">Ref</th>
                        <th class="px-4 py-3 text-center">Qty</th>
                        <th class="px-4 py-3 text-left">Supplier</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($item->stockIns->take(5) as $si)
                        <tr><td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $si->reference_no }}</td>
                        <td class="px-4 py-3 text-center font-semibold text-emerald-600">+{{ $si->quantity }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $si->supplier ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $si->received_date->format('d M Y') }}</td></tr>
                        @empty
                        <tr><td colspan="4" class="px-5 py-6 text-center text-slate-400 text-xs">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Stock Out --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h3 class="font-semibold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-arrow-up-from-bracket text-orange-500"></i> Riwayat Barang Keluar
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="bg-slate-50 text-xs text-slate-500 uppercase">
                        <th class="px-5 py-3 text-left">Ref</th>
                        <th class="px-4 py-3 text-center">Qty</th>
                        <th class="px-4 py-3 text-left">Tujuan</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($item->stockOuts->take(5) as $so)
                        <tr><td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $so->reference_no }}</td>
                        <td class="px-4 py-3 text-center font-semibold text-red-500">-{{ $so->quantity }}</td>
                        <td class="px-4 py-3 text-slate-600">{{ $so->purpose ?? '-' }}</td>
                        <td class="px-4 py-3 text-slate-500">{{ $so->issued_date->format('d M Y') }}</td></tr>
                        @empty
                        <tr><td colspan="4" class="px-5 py-6 text-center text-slate-400 text-xs">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
