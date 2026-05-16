@extends('layouts.app')
@section('title','Detail Peminjaman') @section('page-title','Detail Peminjaman') @section('page-sub', $borrowing->reference_no)

@section('content')
<div class="max-w-2xl">
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
        <div>
            <p class="font-mono text-xs text-slate-400">{{ $borrowing->reference_no }}</p>
            <h2 class="font-bold text-slate-800 text-lg mt-0.5">{{ $borrowing->item->name }}</h2>
        </div>
        {!! $borrowing->status_badge !!}
    </div>

    <div class="p-6 grid grid-cols-2 gap-5">
        <div>
            <p class="text-xs text-slate-400 mb-1">Peminjam (Akun)</p>
            <p class="font-semibold text-slate-800">{{ $borrowing->borrower->name }}</p>
            <p class="text-xs text-slate-500">{{ $borrowing->borrower->department ?? '-' }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-400 mb-1">Nama Peminjam</p>
            <p class="font-semibold text-indigo-700">{{ $borrowing->borrower_name ?? $borrowing->borrower->name }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-400 mb-1">Jumlah</p>
            <p class="font-semibold text-slate-800">{{ $borrowing->quantity }} {{ $borrowing->item->unit }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-400 mb-1">Tanggal Pinjam</p>
            <p class="font-semibold text-slate-800">{{ $borrowing->borrow_date->format('d M Y') }}</p>
        </div>
        <div>
            <p class="text-xs text-slate-400 mb-1">Rencana Kembali</p>
            <p class="font-semibold {{ $borrowing->expected_return_date->isPast() && $borrowing->status === 'approved' ? 'text-red-600' : 'text-slate-800' }}">
                {{ $borrowing->expected_return_date->format('d M Y') }}
                @if($borrowing->expected_return_date->isPast() && $borrowing->status === 'approved')
                <span class="text-xs text-red-500 font-normal ml-1">(Terlambat)</span>
                @endif
            </p>
        </div>
        @if($borrowing->actual_return_date)
        <div>
            <p class="text-xs text-slate-400 mb-1">Tanggal Dikembalikan</p>
            <p class="font-semibold text-emerald-700">{{ $borrowing->actual_return_date->format('d M Y') }}</p>
        </div>
        @endif
        @if($borrowing->approver)
        <div>
            <p class="text-xs text-slate-400 mb-1">Diproses Oleh</p>
            <p class="font-semibold text-slate-800">{{ $borrowing->approver->name }}</p>
        </div>
        @endif
        <div class="col-span-2">
            <p class="text-xs text-slate-400 mb-1">Tujuan</p>
            <p class="text-slate-700">{{ $borrowing->purpose }}</p>
        </div>
        @if($borrowing->notes)
        <div class="col-span-2">
            <p class="text-xs text-slate-400 mb-1">Catatan</p>
            <p class="text-slate-600 text-sm">{{ $borrowing->notes }}</p>
        </div>
        @endif
        @if($borrowing->reject_reason)
        <div class="col-span-2 px-4 py-3 bg-red-50 border border-red-100 rounded-xl">
            <p class="text-xs text-red-500 font-semibold mb-1">Alasan Penolakan</p>
            <p class="text-red-700 text-sm">{{ $borrowing->reject_reason }}</p>
        </div>
        @endif
    </div>

    @if(auth()->user()->isAdmin())
    <div class="px-6 py-4 border-t border-slate-100 flex gap-3">
        @if($borrowing->status === 'pending')
        <form action="{{ route('borrowings.approve', $borrowing) }}" method="POST">
            @csrf
            <button type="submit" class="px-5 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition-colors">
                <i class="fa-solid fa-check mr-1.5"></i>Setujui
            </button>
        </form>
        @endif
        @if($borrowing->status === 'approved')
        <form action="{{ route('borrowings.return', $borrowing) }}" method="POST">
            @csrf
            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors">
                <i class="fa-solid fa-rotate-left mr-1.5"></i>Tandai Dikembalikan
            </button>
        </form>
        @endif
        <a href="{{ route('borrowings.index') }}" class="px-5 py-2.5 bg-slate-100 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-200 transition-colors">Kembali</a>
    </div>
    @endif
</div>
</div>
@endsection
