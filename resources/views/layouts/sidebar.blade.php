<div class="flex flex-col h-full overflow-hidden">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-slate-100 shrink-0">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 shadow-md"
             style="background: linear-gradient(135deg,#4f46e5,#7c3aed)">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                <rect x="2" y="2" width="7" height="7" rx="1.5" fill="white" opacity="0.95"/>
                <rect x="11" y="2" width="7" height="7" rx="1.5" fill="white" opacity="0.95"/>
                <rect x="2" y="11" width="7" height="7" rx="1.5" fill="white" opacity="0.95"/>
                <rect x="11" y="11" width="7" height="7" rx="1.5" fill="white" opacity="0.95"/>
            </svg>
        </div>
        <div class="min-w-0">
            <p class="text-sm font-bold text-slate-800 leading-none">MyInventory</p>
            <p class="text-xs text-slate-400 mt-0.5">Management System</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto px-3 py-4">

        <p class="px-3 mb-2 text-xs font-semibold text-slate-400 uppercase tracking-widest">Menu</p>

        <a href="{{ route('dashboard') }}"
           class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge-high w-4 text-center text-sm"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('items.index') }}"
           class="sidebar-link {{ request()->routeIs('items.*') ? 'active' : '' }}">
            <i class="fa-solid fa-boxes-stacked w-4 text-center text-sm"></i>
            <span>Inventaris Barang</span>
        </a>

        <p class="px-3 pt-5 pb-2 text-xs font-semibold text-slate-400 uppercase tracking-widest">Transaksi</p>

        <a href="{{ route('stock-in.index') }}"
           class="sidebar-link {{ request()->routeIs('stock-in.*') ? 'active' : '' }}">
            <i class="fa-solid fa-circle-down w-4 text-center text-sm"></i>
            <span>Barang Masuk</span>
        </a>

        <a href="{{ route('stock-out.index') }}"
           class="sidebar-link {{ request()->routeIs('stock-out.*') ? 'active' : '' }}">
            <i class="fa-solid fa-circle-up w-4 text-center text-sm"></i>
            <span>Barang Keluar</span>
        </a>

        <a href="{{ route('borrowings.index') }}"
           class="sidebar-link {{ request()->routeIs('borrowings.*') ? 'active' : '' }}">
            <i class="fa-solid fa-hand-holding w-4 text-center text-sm"></i>
            <span>Peminjaman</span>
            @php $pendingCount = \App\Models\Borrowing::where('status','pending')->count(); @endphp
            @if($pendingCount > 0 && auth()->user()->isAdmin())
            <span class="ml-auto inline-flex items-center justify-center min-w-5 h-5 px-1.5 text-xs font-bold bg-red-500 text-white rounded-full">{{ $pendingCount }}</span>
            @endif
        </a>

        <p class="px-3 pt-5 pb-2 text-xs font-semibold text-slate-400 uppercase tracking-widest">Laporan</p>

        <a href="{{ route('reports.index') }}"
           class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-column w-4 text-center text-sm"></i>
            <span>Laporan</span>
        </a>

        @if(auth()->user()->isAdmin())
        <p class="px-3 pt-5 pb-2 text-xs font-semibold text-slate-400 uppercase tracking-widest">Admin</p>
        <a href="{{ route('users.index') }}"
           class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users w-4 text-center text-sm"></i>
            <span>Manajemen User</span>
        </a>
        @endif

    </nav>

    {{-- Footer user --}}
    <div class="shrink-0 px-3 py-3 border-t border-slate-100">
        <div class="flex items-center gap-3 px-3 py-2.5 bg-slate-50 rounded-xl">
            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0"
                 style="background:linear-gradient(135deg,#4f46e5,#7c3aed)">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-slate-800 truncate leading-none">{{ auth()->user()->name }}</p>
                <p class="text-xs text-slate-400 mt-0.5 capitalize">{{ auth()->user()->role }}</p>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="shrink-0" id="logout-form">
                @csrf
                <button type="button"
                        onclick="confirmLogout()"
                        class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                        title="Logout">
                    <i class="fa-solid fa-right-from-bracket text-sm"></i>
                </button>
            </form>
        </div>
    </div>

</div>
