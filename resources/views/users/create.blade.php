@extends('layouts.app')
@section('title','Tambah User') @section('page-title','Tambah User') @section('page-sub','Buat akun pengguna baru')

@section('content')
<div class="max-w-lg">
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
<form action="{{ route('users.store') }}" method="POST" class="space-y-4">
    @csrf
    @include('users._form')
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Password <span class="text-red-500">*</span></label>
        <input type="password" name="password" placeholder="Minimal 6 karakter"
               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none @error('password') border-red-400 @enderror">
        @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
        <input type="password" name="password_confirmation" placeholder="Ulangi password"
               class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
    </div>
    <div class="flex gap-3 pt-2">
        <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 transition-colors">
            <i class="fa-solid fa-user-plus mr-1.5"></i> Buat User
        </button>
        <a href="{{ route('users.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-200 transition-colors">Batal</a>
    </div>
</form>
</div>
</div>
@endsection
