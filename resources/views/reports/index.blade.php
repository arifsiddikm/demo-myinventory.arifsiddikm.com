@extends('layouts.app')
@section('title','Laporan') @section('page-title','Laporan') @section('page-sub','Ekspor & filter data inventaris')

@section('content')
{{-- Filter Form --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-5">
    <form method="GET" class="flex flex-wrap gap-4 items-end">
        <div class="w-44">
            <label class="text-xs font-semibold text-slate-500 block mb-1.5">Jenis Laporan</label>
            <select name="type" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
                @foreach(['stock_in'=>'Barang Masuk','stock_out'=>'Barang Keluar','borrowing'=>'Peminjaman','inventory'=>'Inventaris'] as $val=>$lbl)
                <option value="{{ $val }}" {{ $type === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-36">
            <label class="text-xs font-semibold text-slate-500 block mb-1.5">Dari Tanggal</label>
            <input type="date" name="date_from" value="{{ $dateFrom }}" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
        </div>
        <div class="w-36">
            <label class="text-xs font-semibold text-slate-500 block mb-1.5">Sampai Tanggal</label>
            <input type="date" name="date_to" value="{{ $dateTo }}" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
        </div>
        <div class="w-40">
            <label class="text-xs font-semibold text-slate-500 block mb-1.5">Kategori</label>
            <select name="category_id" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2 flex-wrap">
            <button type="submit" class="px-4 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 transition-colors">
                <i class="fa-solid fa-filter mr-1.5"></i>Filter
            </button>
            <a href="{{ route('reports.pdf', request()->query()) }}" target="_blank"
               class="px-4 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-file-pdf"></i> PDF
            </a>
            <a href="{{ route('reports.excel', request()->query()) }}"
               class="px-4 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition-colors flex items-center gap-2">
                <i class="fa-solid fa-file-excel"></i> Excel
            </a>
        </div>
    </form>
</div>

{{-- Summary Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-5">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4 text-center">
        <p class="text-2xl font-bold text-slate-800">{{ $data->count() }}</p>
        <p class="text-xs text-slate-400 mt-1">Total Record</p>
    </div>
    @if($type !== 'inventory')
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4 text-center">
        <p class="text-2xl font-bold text-slate-800">{{ $data->sum('quantity') }}</p>
        <p class="text-xs text-slate-400 mt-1">Total Qty</p>
    </div>
    @endif
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4 text-center">
        <p class="text-sm font-semibold text-slate-500">{{ \Carbon\Carbon::parse($dateFrom)->format('d M Y') }}</p>
        <p class="text-xs text-slate-400 mt-1">Dari Tanggal</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm px-5 py-4 text-center">
        <p class="text-sm font-semibold text-slate-500">{{ \Carbon\Carbon::parse($dateTo)->format('d M Y') }}</p>
        <p class="text-xs text-slate-400 mt-1">Sampai Tanggal</p>
    </div>
</div>

{{-- Data Table --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-semibold text-slate-800 text-sm">
            @php $typeLabels = ['stock_in'=>'Barang Masuk','stock_out'=>'Barang Keluar','borrowing'=>'Peminjaman','inventory'=>'Inventaris']; @endphp
            Data {{ $typeLabels[$type] ?? 'Laporan' }}
        </h3>
        <span class="text-xs text-slate-400">{{ $data->count() }} record</span>
    </div>
    <div class="overflow-x-auto">
        @if($type === 'stock_in')
        <table class="w-full text-sm">
            <thead><tr class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                <th class="px-5 py-3 text-left">Referensi</th><th class="px-4 py-3 text-left">Barang</th>
                <th class="px-4 py-3 text-center">Qty</th><th class="px-4 py-3 text-left">Supplier</th>
                <th class="px-4 py-3 text-left">Tanggal</th><th class="px-4 py-3 text-left">Dicatat</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($data as $r)
                <tr class="hover:bg-slate-50/50">
                    <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $r->reference_no }}</td>
                    <td class="px-4 py-3"><p class="font-medium text-slate-800">{{ $r->item->name }}</p><p class="text-xs text-slate-400">{{ $r->item->category->name }}</p></td>
                    <td class="px-4 py-3 text-center font-bold text-emerald-600">+{{ $r->quantity }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $r->supplier ?? '-' }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $r->received_date->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $r->user->name }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-slate-400">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>

        @elseif($type === 'stock_out')
        <table class="w-full text-sm">
            <thead><tr class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                <th class="px-5 py-3 text-left">Referensi</th><th class="px-4 py-3 text-left">Barang</th>
                <th class="px-4 py-3 text-center">Qty</th><th class="px-4 py-3 text-left">Tujuan</th>
                <th class="px-4 py-3 text-left">Penerima</th><th class="px-4 py-3 text-left">Tanggal</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($data as $r)
                <tr class="hover:bg-slate-50/50">
                    <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $r->reference_no }}</td>
                    <td class="px-4 py-3"><p class="font-medium text-slate-800">{{ $r->item->name }}</p><p class="text-xs text-slate-400">{{ $r->item->category->name }}</p></td>
                    <td class="px-4 py-3 text-center font-bold text-orange-600">-{{ $r->quantity }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $r->purpose ?? '-' }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $r->recipient ?? '-' }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $r->issued_date->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-10 text-center text-slate-400">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>

        @elseif($type === 'borrowing')
        <table class="w-full text-sm">
            <thead><tr class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                <th class="px-5 py-3 text-left">Referensi</th><th class="px-4 py-3 text-left">Barang</th>
                <th class="px-4 py-3 text-left">Peminjam</th><th class="px-4 py-3 text-center">Qty</th>
                <th class="px-4 py-3 text-left">Tgl Pinjam</th><th class="px-4 py-3 text-left">Tgl Kembali</th>
                <th class="px-4 py-3 text-center">Status</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($data as $r)
                <tr class="hover:bg-slate-50/50">
                    <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $r->reference_no }}</td>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $r->item->name }}</td>
                    <td class="px-4 py-3 text-slate-600">{{ $r->borrower->name }}</td>
                    <td class="px-4 py-3 text-center font-semibold">{{ $r->quantity }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $r->borrow_date->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $r->expected_return_date->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-center">{!! $r->status_badge !!}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-slate-400">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>

        @elseif($type === 'inventory')
        <table class="w-full text-sm">
            <thead><tr class="bg-slate-50 text-xs text-slate-500 uppercase tracking-wider">
                <th class="px-5 py-3 text-left">Kode</th><th class="px-4 py-3 text-left">Nama Barang</th>
                <th class="px-4 py-3 text-left">Kategori</th><th class="px-4 py-3 text-center">Stok</th>
                <th class="px-4 py-3 text-left">Satuan</th><th class="px-4 py-3 text-left">Lokasi</th>
                <th class="px-4 py-3 text-left">Kondisi</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($data as $r)
                <tr class="hover:bg-slate-50/50">
                    <td class="px-5 py-3 font-mono text-xs text-slate-500">{{ $r->code }}</td>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $r->name }}</td>
                    <td class="px-4 py-3"><span class="px-2 py-0.5 bg-primary-50 text-primary-700 text-xs rounded-lg">{{ $r->category->name }}</span></td>
                    <td class="px-4 py-3 text-center font-bold {{ $r->isLowStock() ? 'text-amber-600' : 'text-slate-800' }}">{{ $r->stock }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $r->unit }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $r->location ?? '-' }}</td>
                    <td class="px-4 py-3 text-slate-500">{{ $r->condition_label }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-5 py-10 text-center text-slate-400">Tidak ada data</td></tr>
                @endforelse
            </tbody>
        </table>
        @endif
    </div>
</div>
@endsection
