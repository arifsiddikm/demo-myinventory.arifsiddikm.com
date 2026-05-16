@php $item = $item ?? null; @endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kode Barang <span class="text-red-500">*</span></label>
        <input type="text" name="code" value="{{ old('code', $item?->code) }}" placeholder="ITM-001"
               class="w-full px-3.5 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('code') border-red-400 bg-red-50 @else border-slate-200 @enderror">
        @error('code')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Barang <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $item?->name) }}" placeholder="Nama barang..."
               class="w-full px-3.5 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('name') border-red-400 bg-red-50 @else border-slate-200 @enderror">
        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kategori <span class="text-red-500">*</span></label>
        <select name="category_id" class="w-full px-3.5 py-2.5 text-sm border rounded-xl focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none @error('category_id') border-red-400 @else border-slate-200 @enderror">
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ old('category_id', $item?->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
        @error('category_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Satuan <span class="text-red-500">*</span></label>
        <input type="text" name="unit" value="{{ old('unit', $item?->unit ?? 'pcs') }}" placeholder="pcs / rim / unit"
               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
        @error('unit')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
    @if(!$item)
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Stok Awal <span class="text-red-500">*</span></label>
        <input type="number" name="stock" value="{{ old('stock', 0) }}" min="0"
               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
        @error('stock')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
    @endif
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Stok Minimum <span class="text-red-500">*</span></label>
        <input type="number" name="min_stock" value="{{ old('min_stock', $item?->min_stock ?? 5) }}" min="0"
               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
        @error('min_stock')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lokasi</label>
        <input type="text" name="location" value="{{ old('location', $item?->location) }}" placeholder="Rak A-1 / Gudang B"
               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kondisi <span class="text-red-500">*</span></label>
        <select name="condition" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
            <option value="baik"         {{ old('condition', $item?->condition) === 'baik'         ? 'selected' : '' }}>Baik</option>
            <option value="rusak_ringan" {{ old('condition', $item?->condition) === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan</option>
            <option value="rusak_berat"  {{ old('condition', $item?->condition) === 'rusak_berat'  ? 'selected' : '' }}>Rusak Berat</option>
        </select>
    </div>
</div>

<div>
    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Deskripsi</label>
    <textarea name="description" rows="3" placeholder="Deskripsi barang..."
              class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none resize-none">{{ old('description', $item?->description) }}</textarea>
</div>

<div>
    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Foto Barang</label>
    @if($item?->image)
    <div class="mb-2 flex items-center gap-3">
        <img src="{{ asset('storage/'.$item->image) }}" class="w-16 h-16 rounded-xl object-cover border border-slate-200" alt="Preview">
        <p class="text-xs text-slate-500">Upload baru untuk mengganti gambar</p>
    </div>
    @endif
    <input type="file" name="image" accept="image/*" id="imageInput"
           class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
    <div id="imagePreview" class="mt-2 hidden">
        <img id="previewImg" src="" class="w-20 h-20 rounded-xl object-cover border border-slate-200" alt="Preview">
    </div>
    <p class="text-xs text-slate-400 mt-1">Maks. 2MB. Format: JPG, PNG, WEBP</p>
</div>

@if($item)
<div class="flex items-center gap-3">
    <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $item?->is_active ?? true) ? 'checked' : '' }} class="sr-only peer">
        <div class="w-11 h-6 bg-slate-200 peer-focus:ring-4 peer-focus:ring-primary-100 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
    </label>
    <span class="text-sm font-medium text-slate-700">Barang Aktif</span>
</div>
@endif

@push('scripts')
<script>
    document.getElementById('imageInput')?.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('imagePreview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
