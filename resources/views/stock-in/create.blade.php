@extends('layouts.app')
@section('title','Catat Barang Masuk')
@section('page-title','Catat Barang Masuk')
@section('page-sub','Input penerimaan barang baru')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
<style>
    .select2-container--custom .select2-selection--single {
        height:42px;border:1px solid #e2e8f0;border-radius:12px;
        display:flex;align-items:center;padding:0 12px;background:white;
    }
    .select2-container--custom.select2-container--open .select2-selection--single,
    .select2-container--custom .select2-selection--single:focus {
        border-color:#6366f1;box-shadow:0 0 0 3px rgba(99,102,241,0.12);
    }
    .select2-container--custom .select2-selection__rendered{color:#334155;font-size:14px;padding:0;}
    .select2-container--custom .select2-selection__placeholder{color:#94a3b8;}
    .select2-container--custom .select2-selection__arrow{height:42px;right:10px;}
    .select2-container--custom .select2-dropdown{border:1px solid #e2e8f0;border-radius:12px;box-shadow:0 8px 24px rgba(0,0,0,0.1);overflow:hidden;margin-top:4px;}
    .select2-container--custom .select2-search--dropdown{padding:8px;border-bottom:1px solid #f1f5f9;}
    .select2-container--custom .select2-search--dropdown input{border:1px solid #e2e8f0;border-radius:8px;padding:6px 10px;font-size:13px;outline:none;width:100%;}
    .select2-container--custom .select2-search--dropdown input:focus{border-color:#6366f1;}
    .select2-container--custom .select2-results__option{padding:9px 14px;font-size:13.5px;color:#334155;}
    .select2-container--custom .select2-results__option--highlighted{background:#eef2ff;color:#4338ca;}
    .select2-container--custom .select2-results__option[aria-selected=true]{background:#4f46e5;color:#fff;}
</style>
@endpush

@section('content')
<div class="max-w-xl">
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
<form action="{{ route('stock-in.store') }}" method="POST" class="space-y-4">
    @csrf

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Barang <span class="text-red-500">*</span></label>
        <select name="item_id" id="itemSelect" style="width:100%" data-placeholder="Cari & pilih barang..." required>
            <option value=""></option>
            @foreach($items as $item)
            <option value="{{ $item->id }}" data-stock="{{ $item->stock }}" data-unit="{{ $item->unit }}"
                    {{ old('item_id') == $item->id ? 'selected' : '' }}>
                {{ $item->name }} ({{ $item->code }}) — Stok: {{ $item->stock }} {{ $item->unit }}
            </option>
            @endforeach
        </select>
        @error('item_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div id="currentStock" class="hidden px-4 py-3 bg-blue-50 border border-blue-100 rounded-xl text-sm text-blue-700 flex items-center gap-2">
        <i class="fa-solid fa-circle-info text-blue-400"></i>
        Stok saat ini: <strong id="stockVal">-</strong>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jumlah <span class="text-red-500">*</span></label>
            <input type="number" name="quantity" value="{{ old('quantity') }}" min="1" placeholder="0"
                   class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none @error('quantity') border-red-400 @enderror" required>
            @error('quantity')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Tanggal Terima <span class="text-red-500">*</span></label>
            <div class="relative">
                <input type="text" id="receivedDate" name="received_date"
                       value="{{ old('received_date', date('d/m/Y')) }}"
                       placeholder="Pilih tanggal..."
                       class="w-full pl-3.5 pr-9 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none cursor-pointer"
                       readonly required>
                <i class="fa-regular fa-calendar absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
            </div>
            @error('received_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Supplier</label>
        <input type="text" name="supplier" value="{{ old('supplier') }}" placeholder="Nama supplier..."
               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Harga Satuan (Rp)</label>
        <input type="number" name="price_per_unit" value="{{ old('price_per_unit') }}" min="0" placeholder="0"
               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
    </div>

    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Catatan</label>
        <textarea name="notes" rows="2" placeholder="Catatan tambahan..."
                  class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none resize-none">{{ old('notes') }}</textarea>
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit" class="px-6 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 transition-colors">
            <i class="fa-solid fa-save mr-1.5"></i> Simpan
        </button>
        <a href="{{ route('stock-in.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-200 transition-colors">Batal</a>
    </div>
</form>
</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#itemSelect').select2({
        theme: 'custom',
        placeholder: 'Cari & pilih barang...',
        allowClear: true,
        width: '100%',
    }).on('change', function() {
        const opt = this.options[this.selectedIndex];
        const box = document.getElementById('currentStock');
        if (opt && opt.value) {
            document.getElementById('stockVal').textContent = opt.dataset.stock + ' ' + opt.dataset.unit;
            box.classList.remove('hidden');
        } else { box.classList.add('hidden'); }
    });
    if ($('#itemSelect').val()) $('#itemSelect').trigger('change');

    flatpickr('#receivedDate', {
        dateFormat: 'd/m/Y',
        maxDate: 'today',
        locale: { firstDayOfWeek: 1 },
    });
});
</script>
@endpush
