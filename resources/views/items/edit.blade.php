@extends('layouts.app')
@section('title', 'Edit Barang')
@section('page-title', 'Edit Barang')
@section('page-sub', $item->name)

@section('content')
<div class="max-w-2xl">
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
        <form action="{{ route('items.update', $item) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf @method('PUT')
            @include('items._form')
            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 transition-colors">
                    <i class="fa-solid fa-save mr-1.5"></i> Simpan Perubahan
                </button>
                <a href="{{ route('items.index') }}" class="px-6 py-2.5 bg-slate-100 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-200 transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
