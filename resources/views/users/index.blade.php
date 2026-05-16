@extends('layouts.app')
@section('title','Manajemen User') @section('page-title','Manajemen User') @section('page-sub','Kelola akun pengguna sistem')

@section('content')
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="text-xs font-semibold text-slate-500 block mb-1.5">Cari User</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama / email / departemen..."
                   class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
        </div>
        <div class="w-36">
            <label class="text-xs font-semibold text-slate-500 block mb-1.5">Role</label>
            <select name="role" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
                <option value="">Semua</option>
                <option value="admin"    {{ request('role') === 'admin'    ? 'selected' : '' }}>Admin</option>
                <option value="karyawan" {{ request('role') === 'karyawan' ? 'selected' : '' }}>Karyawan</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-xl hover:bg-primary-700 transition-colors">Cari</button>
            <a href="{{ route('users.index') }}" class="px-4 py-2.5 bg-slate-100 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-200 transition-colors">Reset</a>
        </div>
        <a href="{{ route('users.create') }}" class="ml-auto px-4 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 transition-colors flex items-center gap-2">
            <i class="fa-solid fa-user-plus"></i> Tambah User
        </a>
    </form>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-slate-50 border-b border-slate-100 text-xs text-slate-500 uppercase tracking-wider">
                <th class="px-5 py-3.5 text-left">User</th>
                <th class="px-4 py-3.5 text-left hidden md:table-cell">Departemen</th>
                <th class="px-4 py-3.5 text-left hidden md:table-cell">Telepon</th>
                <th class="px-4 py-3.5 text-center">Role</th>
                <th class="px-4 py-3.5 text-center">Status</th>
                <th class="px-4 py-3.5 text-center">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-primary-600 flex items-center justify-center text-white text-sm font-bold shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-slate-800">{{ $user->name }}</p>
                                <p class="text-xs text-slate-400">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4 text-slate-500 hidden md:table-cell">{{ $user->department ?? '-' }}</td>
                    <td class="px-4 py-4 text-slate-500 hidden md:table-cell">{{ $user->phone ?? '-' }}</td>
                    <td class="px-4 py-4 text-center">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                            {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-4 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('users.edit', $user) }}" class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit">
                                <i class="fa-solid fa-pen text-sm"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="button" data-confirm-delete="Hapus user '{{ $user->name }}'?"
                                        class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                    <i class="fa-solid fa-trash text-sm"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-12 text-center text-slate-400">
                    <i class="fa-solid fa-users text-3xl mb-3 block text-slate-200"></i>Tidak ada user ditemukan
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())<div class="px-5 py-4 border-t border-slate-100">{{ $users->links() }}</div>@endif
</div>
@endsection
