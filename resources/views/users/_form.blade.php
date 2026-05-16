@php $user = $user ?? null; @endphp
<div class="grid grid-cols-2 gap-4">
    <div class="col-span-2">
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $user?->name) }}" placeholder="Nama lengkap"
               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none @error('name') border-red-400 @enderror" required>
        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="col-span-2">
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
        <input type="email" name="email" value="{{ old('email', $user?->email) }}" placeholder="email@domain.com"
               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none @error('email') border-red-400 @enderror" required>
        @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Role <span class="text-red-500">*</span></label>
        <select name="role" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
            <option value="karyawan" {{ old('role', $user?->role) === 'karyawan' ? 'selected' : '' }}>Karyawan</option>
            <option value="admin"    {{ old('role', $user?->role) === 'admin'    ? 'selected' : '' }}>Admin</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Telepon</label>
        <input type="text" name="phone" value="{{ old('phone', $user?->phone) }}" placeholder="08xxxxxxxxxx"
               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
    </div>
    <div class="col-span-2">
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Departemen</label>
        <input type="text" name="department" value="{{ old('department', $user?->department) }}" placeholder="IT / Finance / HR..."
               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
    </div>
</div>
@if($user)
<div class="flex items-center gap-3 mt-1">
    <label class="relative inline-flex items-center cursor-pointer">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }} class="sr-only peer">
        <div class="w-11 h-6 bg-slate-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-primary-100 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
    </label>
    <span class="text-sm font-medium text-slate-700">Akun Aktif</span>
</div>
@endif
