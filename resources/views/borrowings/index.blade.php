@extends('layouts.app')
@section('title','Peminjaman') @section('page-title','Peminjaman Barang') @section('page-sub','Kelola permintaan & peminjaman')

@section('content')
{{-- Filter --}}
<div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        <div class="flex-1 min-w-48">
            <label class="text-xs font-semibold text-slate-500 block mb-1.5">Cari</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama barang / peminjam..."
                   class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
        </div>
        <div class="w-36">
            <label class="text-xs font-semibold text-slate-500 block mb-1.5">Status</label>
            <select name="status" class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-500 outline-none">
                <option value="">Semua</option>
                @foreach(['pending'=>'Pending','approved'=>'Disetujui','rejected'=>'Ditolak','returned'=>'Dikembalikan'] as $val=>$lbl)
                <option value="{{ $val }}" {{ request('status') == $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2.5 bg-primary-600 text-white text-sm font-medium rounded-xl hover:bg-primary-700 transition-colors">Cari</button>
            <a href="{{ route('borrowings.index') }}" class="px-4 py-2.5 bg-slate-100 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-200 transition-colors">Reset</a>
        </div>
        <a href="{{ route('borrowings.create') }}" class="ml-auto px-4 py-2.5 bg-blue-600 text-white text-sm font-semibold rounded-xl hover:bg-blue-700 transition-colors flex items-center gap-2">
            <i class="fa-solid fa-plus"></i> Ajukan Pinjam
        </a>
    </form>
</div>

<div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="bg-slate-50 border-b border-slate-100 text-xs text-slate-500 uppercase tracking-wider">
                <th class="px-5 py-3.5 text-left">Referensi</th>
                <th class="px-4 py-3.5 text-left">Barang</th>
                <th class="px-4 py-3.5 text-left hidden md:table-cell">Peminjam</th>
                <th class="px-4 py-3.5 text-center">Qty</th>
                <th class="px-4 py-3.5 text-left hidden lg:table-cell">Tgl Pinjam</th>
                <th class="px-4 py-3.5 text-left hidden lg:table-cell">Tgl Kembali</th>
                <th class="px-4 py-3.5 text-center">Status</th>
                <th class="px-4 py-3.5 text-center">Aksi</th>
            </tr></thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($borrowings as $b)
                <tr class="hover:bg-slate-50/50 transition-colors" x-data="{}">
                    <td class="px-5 py-4 font-mono text-xs text-slate-500">{{ $b->reference_no }}</td>
                    <td class="px-4 py-4">
                        <p class="font-semibold text-slate-800">{{ $b->item->name }}</p>
                        <p class="text-xs text-slate-400">{{ $b->item->category->name }}</p>
                    </td>
                    <td class="px-4 py-4 text-slate-600 hidden md:table-cell">
                        <p class="font-medium text-slate-700">{{ $b->borrower_name ?? $b->borrower->name }}</p>
                        <p class="text-xs text-slate-400">{{ $b->borrower->name }}</p>
                    </td>
                    <td class="px-4 py-4 text-center font-semibold text-slate-700">{{ $b->quantity }}</td>
                    <td class="px-4 py-4 text-slate-500 hidden lg:table-cell">{{ $b->borrow_date->format('d M Y') }}</td>
                    <td class="px-4 py-4 hidden lg:table-cell">
                        <span class="{{ $b->expected_return_date->isPast() && in_array($b->status, ['approved']) ? 'text-red-600 font-semibold' : 'text-slate-500' }}">
                            {{ $b->expected_return_date->format('d M Y') }}
                        </span>
                    </td>
                    <td class="px-4 py-4 text-center">{!! $b->status_badge !!}</td>
                    <td class="px-4 py-4">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('borrowings.show', $b) }}" class="p-1.5 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors" title="Detail">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </a>
                            @if(auth()->user()->isAdmin() && $b->status === 'pending')
                            <form action="{{ route('borrowings.approve', $b) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="p-1.5 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Setujui">
                                    <i class="fa-solid fa-check text-sm"></i>
                                </button>
                            </form>
                            <button type="button" onclick="showReject({{ $b->id }})"
                                    class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Tolak">
                                <i class="fa-solid fa-xmark text-sm"></i>
                            </button>
                            @endif
                            @if(auth()->user()->isAdmin() && $b->status === 'approved')
                            <form action="{{ route('borrowings.return', $b) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-2.5 py-1 text-xs bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg transition-colors font-medium">
                                    Kembalikan
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-5 py-12 text-center text-slate-400">
                    <i class="fa-solid fa-hand-holding-box text-3xl mb-3 block text-slate-200"></i>Belum ada data peminjaman
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($borrowings->hasPages())<div class="px-5 py-4 border-t border-slate-100">{{ $borrowings->links() }}</div>@endif
</div>

{{-- Reject Modal --}}
<div id="rejectModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
        <h3 class="font-semibold text-slate-800 mb-4">Alasan Penolakan</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <textarea name="reject_reason" rows="3" placeholder="Tulis alasan penolakan..." required
                      class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-red-500 outline-none resize-none mb-4"></textarea>
            <div class="flex gap-3">
                <button type="submit" class="px-5 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition-colors">Tolak</button>
                <button type="button" onclick="closeReject()" class="px-5 py-2.5 bg-slate-100 text-slate-600 text-sm font-medium rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showReject(id) {
    document.getElementById('rejectForm').action = `/borrowings/${id}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
}
function closeReject() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectModal').classList.remove('flex');
}
</script>
@endpush
