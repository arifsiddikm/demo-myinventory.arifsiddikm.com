@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-sub', 'Ringkasan sistem inventaris')

@section('content')
{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                <i class="fa-solid fa-boxes-stacked text-indigo-600"></i>
            </div>
            <span class="text-xs font-medium text-slate-400 bg-slate-50 px-2 py-1 rounded-lg">Total</span>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ number_format($totalItems) }}</p>
        <p class="text-sm text-slate-500 mt-1">Jenis Barang</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                <i class="fa-solid fa-download text-emerald-600"></i>
            </div>
            <span class="text-xs font-medium text-emerald-600 bg-emerald-50 px-2 py-1 rounded-lg">Bulan ini</span>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ number_format($totalStockIn) }}</p>
        <p class="text-sm text-slate-500 mt-1">Barang Masuk</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center">
                <i class="fa-solid fa-upload text-orange-600"></i>
            </div>
            <span class="text-xs font-medium text-orange-600 bg-orange-50 px-2 py-1 rounded-lg">Bulan ini</span>
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ number_format($totalStockOut) }}</p>
        <p class="text-sm text-slate-500 mt-1">Barang Keluar</p>
    </div>

    <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center">
                <i class="fa-solid fa-hand-holding-hand text-blue-600"></i>
            </div>
            @if($pendingBorrows > 0)
            <span class="text-xs font-medium text-amber-600 bg-amber-50 px-2 py-1 rounded-lg">{{ $pendingBorrows }} pending</span>
            @else
            <span class="text-xs font-medium text-slate-400 bg-slate-50 px-2 py-1 rounded-lg">Aktif</span>
            @endif
        </div>
        <p class="text-2xl font-bold text-slate-800">{{ number_format($activeBorrows) }}</p>
        <p class="text-sm text-slate-500 mt-1">Sedang Dipinjam</p>
    </div>
</div>

{{-- Alerts --}}
@if($lowStockItems > 0)
<div class="mb-5 flex items-center gap-3 px-4 py-3 bg-amber-50 border border-amber-200 rounded-xl text-sm text-amber-800">
    <i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
    <span><strong>{{ $lowStockItems }} barang</strong> mendekati atau mencapai stok minimum.</span>
    <a href="{{ route('items.index', ['stock_status' => 'low']) }}" class="ml-auto text-amber-700 font-semibold hover:underline">Lihat →</a>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Recent Activity --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-semibold text-slate-800 text-sm">Aktivitas Terbaru</h3>
            <span class="text-xs text-slate-400">{{ now()->format('d M Y') }}</span>
        </div>
        <div class="divide-y divide-slate-50">
            @forelse($recentActivities as $log)
            <div class="px-5 py-3 flex items-start gap-3">
                <div class="w-7 h-7 rounded-full bg-primary-100 flex items-center justify-center shrink-0 mt-0.5">
                    <i class="fa-solid fa-bolt text-primary-600 text-xs"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-slate-700 truncate">{{ $log->description }}</p>
                    <p class="text-xs text-slate-400 mt-0.5">
                        {{ $log->user->name ?? 'System' }} · {{ $log->created_at->diffForHumans() }}
                    </p>
                </div>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-sm text-slate-400">
                <i class="fa-solid fa-clock-rotate-left text-2xl mb-2 block text-slate-200"></i>
                Belum ada aktivitas
            </div>
            @endforelse
        </div>
    </div>

    <div class="space-y-6">
        {{-- Pending Borrowings --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-semibold text-slate-800 text-sm">Peminjaman Terbaru</h3>
                <a href="{{ route('borrowings.index') }}" class="text-xs text-primary-600 hover:underline font-medium">Lihat semua</a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($recentBorrowings as $b)
                <div class="px-5 py-3 flex items-center gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 truncate">{{ $b->item->name }}</p>
                        <p class="text-xs text-slate-400">{{ $b->borrower->name }} · {{ $b->borrow_date->format('d M Y') }}</p>
                    </div>
                    {!! $b->status_badge !!}
                </div>
                @empty
                <div class="px-5 py-6 text-center text-sm text-slate-400">Belum ada peminjaman</div>
                @endforelse
            </div>
        </div>

        {{-- Low Stock --}}
        @if($lowStockList->isNotEmpty())
        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-amber-100 flex items-center justify-between">
                <h3 class="font-semibold text-amber-700 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation"></i> Stok Menipis
                </h3>
                <a href="{{ route('items.index', ['stock_status' => 'low']) }}" class="text-xs text-amber-600 hover:underline font-medium">Lihat semua</a>
            </div>
            <div class="divide-y divide-amber-50">
                @foreach($lowStockList as $item)
                <div class="px-5 py-3 flex items-center gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 truncate">{{ $item->name }}</p>
                        <p class="text-xs text-slate-400">{{ $item->category->name }}</p>
                    </div>
                    <span class="text-sm font-bold text-amber-600">{{ $item->stock }} <span class="font-normal text-xs text-slate-400">{{ $item->unit }}</span></span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
