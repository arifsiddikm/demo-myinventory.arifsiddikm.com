@extends('layouts.app')
@section('title','Ajukan Peminjaman')
@section('page-title','Ajukan Peminjaman')
@section('page-sub','Form permintaan pinjam barang')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Select2 custom theme - override default */
    .select2-container--default .select2-selection--single {
        height: 42px !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        display: flex !important;
        align-items: center !important;
        padding: 0 12px !important;
        background: white !important;
        outline: none !important;
    }
    .select2-container--default.select2-container--open .select2-selection--single,
    .select2-container--default.select2-container--focus .select2-selection--single {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 3px rgba(99,102,241,0.12) !important;
    }
    .select2-container--default .select2-selection__rendered {
        color: #334155 !important;
        font-size: 14px !important;
        line-height: normal !important;
        padding: 0 !important;
        margin-top: 0 !important;
    }
    .select2-container--default .select2-selection__placeholder {
        color: #94a3b8 !important;
    }
    .select2-container--default .select2-selection__arrow {
        height: 42px !important;
        right: 10px !important;
        top: 0 !important;
    }
    .select2-container--default .select2-selection__arrow b {
        border-color: #94a3b8 transparent transparent !important;
        margin-top: -3px !important;
    }
    .select2-container--default.select2-container--open .select2-selection__arrow b {
        border-color: transparent transparent #94a3b8 !important;
    }
    .select2-container--default .select2-selection__clear {
        margin-top: 0 !important;
        font-size: 16px !important;
        color: #94a3b8 !important;
        margin-right: 6px !important;
    }
    .select2-dropdown {
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        box-shadow: 0 8px 24px rgba(0,0,0,0.10) !important;
        overflow: hidden !important;
        margin-top: 4px !important;
    }
    .select2-container--default .select2-search--dropdown {
        padding: 8px !important;
        border-bottom: 1px solid #f1f5f9 !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field {
        border: 1px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 6px 10px !important;
        font-size: 13px !important;
        outline: none !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field:focus {
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 2px rgba(99,102,241,0.1) !important;
    }
    .select2-results__option {
        padding: 9px 14px !important;
        font-size: 13.5px !important;
        color: #334155 !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #eef2ff !important;
        color: #4338ca !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #4f46e5 !important;
        color: #fff !important;
    }
</style>
@endpush

@section('content')
<div class="max-w-xl">
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
<form action="{{ route('borrowings.store') }}" method="POST" class="space-y-4">
    @csrf

    {{-- Pilih Barang --}}
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
            Barang yang Dipinjam <span class="text-red-500">*</span>
        </label>
        <select name="item_id" id="itemSelect" style="width:100%" required>
            <option value=""></option>
            @foreach($items as $item)
            <option value="{{ $item->id }}"
                    data-stock="{{ $item->stock }}"
                    data-unit="{{ $item->unit }}"
                    {{ old('item_id') == $item->id ? 'selected' : '' }}>
                {{ $item->name }} ({{ $item->code }}) — Stok: {{ $item->stock }} {{ $item->unit }}
            </option>
            @endforeach
        </select>
        @error('item_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Info stok --}}
    <div id="stockInfo" class="hidden px-4 py-3 bg-indigo-50 border border-indigo-100 rounded-xl text-sm text-indigo-700 flex items-center gap-2">
        <i class="fa-solid fa-circle-info text-indigo-400"></i>
        Stok tersedia: <strong id="stockVal">-</strong>
    </div>

    @if($errors->has('quantity'))
    <div class="px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
        <i class="fa-solid fa-circle-exclamation mr-1.5"></i>{{ $errors->first('quantity') }}
    </div>
    @endif

    {{-- Nama Peminjam --}}
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
            Nama Peminjam <span class="text-red-500">*</span>
        </label>
        <input type="text" name="borrower_name"
               value="{{ old('borrower_name', auth()->user()->name) }}"
               placeholder="Nama lengkap peminjam..."
               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('borrower_name') border-red-400 bg-red-50 @enderror"
               required>
        <p class="text-xs text-slate-400 mt-1">Bisa berbeda dengan akun login, mis. meminjam atas nama orang lain</p>
        @error('borrower_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Jumlah --}}
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Jumlah <span class="text-red-500">*</span></label>
        <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" id="qtyInput"
               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none"
               required>
    </div>

    {{-- Tanggal — Flatpickr --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                Tanggal Pinjam <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input type="text" id="borrowDate" name="borrow_date"
                       value="{{ old('borrow_date', date('d/m/Y')) }}"
                       placeholder="Pilih tanggal..."
                       class="w-full pl-3.5 pr-9 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none cursor-pointer"
                       readonly required>
                <i class="fa-regular fa-calendar absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-sm"></i>
            </div>
            @error('borrow_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                Rencana Kembali <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <input type="text" id="returnDate" name="expected_return_date"
                       value="{{ old('expected_return_date') }}"
                       placeholder="Pilih tanggal..."
                       class="w-full pl-3.5 pr-9 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none cursor-pointer"
                       readonly required>
                <i class="fa-regular fa-calendar absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-sm"></i>
            </div>
            @error('expected_return_date')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Tujuan --}}
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">
            Tujuan / Keperluan <span class="text-red-500">*</span>
        </label>
        <textarea name="purpose" rows="3" placeholder="Jelaskan keperluan peminjaman..."
                  class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none resize-none @error('purpose') border-red-400 @enderror"
                  required>{{ old('purpose') }}</textarea>
        @error('purpose')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Catatan --}}
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Catatan Tambahan</label>
        <textarea name="notes" rows="2" placeholder="Opsional..."
                  class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none resize-none">{{ old('notes') }}</textarea>
    </div>

    <div class="bg-amber-50 border border-amber-100 rounded-xl px-4 py-3 text-xs text-amber-700">
        <i class="fa-solid fa-circle-info mr-1.5"></i>
        Permintaan peminjaman akan diproses setelah disetujui oleh Admin.
    </div>

    <div class="flex gap-3 pt-2">
        <button type="submit"
                class="px-6 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors">
            <i class="fa-solid fa-paper-plane mr-1.5"></i> Kirim Permintaan
        </button>
        <a href="{{ route('borrowings.index') }}"
           class="px-6 py-2.5 bg-slate-100 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-200 transition-colors">
            Batal
        </a>
    </div>
</form>
</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
// Jalankan setelah semua script selesai load
(function() {
    // Select2 init
    $('#itemSelect').select2({
        placeholder: 'Cari & pilih barang...',
        allowClear: true,
        width: '100%',
    });

    // Stok info on change
    $('#itemSelect').on('change', function() {
        var opt = this.options[this.selectedIndex];
        var box = document.getElementById('stockInfo');
        if (opt && opt.value) {
            document.getElementById('stockVal').textContent = (opt.dataset.stock || '0') + ' ' + (opt.dataset.unit || '');
            document.getElementById('qtyInput').max = opt.dataset.stock;
            box.classList.remove('hidden');
        } else {
            box.classList.add('hidden');
        }
    });

    // Trigger jika ada old value
    if ($('#itemSelect').val()) {
        $('#itemSelect').trigger('change');
    }

    // Flatpickr tanggal pinjam
    var borrowPicker = flatpickr('#borrowDate', {
        dateFormat: 'd/m/Y',
        minDate: 'today',
        onChange: function(selectedDates) {
            if (selectedDates[0]) {
                returnPicker.set('minDate', selectedDates[0]);
            }
        }
    });

    // Flatpickr tanggal kembali
    var returnPicker = flatpickr('#returnDate', {
        dateFormat: 'd/m/Y',
        minDate: 'today',
    });
})();
</script>
@endpush
