<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MyInventory - Sistem Informasi Manajemen Inventaris Barang Perusahaan">
    <meta property="og:title" content="MyInventory - Inventory Management System">
    <meta property="og:description" content="Kelola inventaris barang perusahaan Anda dengan mudah dan efisien.">
    <meta property="og:type" content="website">
    <title>@yield('title', 'Dashboard') — MyInventory</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50:'#eef2ff',100:'#e0e7ff',200:'#c7d2fe',300:'#a5b4fc',400:'#818cf8',500:'#6366f1',600:'#4f46e5',700:'#4338ca',800:'#3730a3',900:'#312e81' },
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .sidebar-link { display:flex; align-items:center; gap:10px; padding:9px 12px; border-radius:10px; color:#475569; font-size:13.5px; font-weight:500; transition:all 0.15s; white-space:nowrap; }
        .sidebar-link:hover { background:#eef2ff; color:#4338ca; }
        .sidebar-link.active { background:linear-gradient(135deg,#4f46e5,#6366f1); color:#fff; box-shadow:0 4px 12px rgba(99,102,241,0.3); }
        .sidebar-link.active i { color:#fff !important; }
        .badge-status { @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold; }
        .badge-pending  { @apply bg-amber-100 text-amber-700; }
        .badge-approved { @apply bg-emerald-100 text-emerald-700; }
        .badge-rejected { @apply bg-red-100 text-red-700; }
        .badge-returned { @apply bg-blue-100 text-blue-700; }
        .badge-overdue  { @apply bg-rose-100 text-rose-800; }
        [x-cloak] { display: none !important; }
    </style>
    {{-- Select2 CSS global (dipakai di form-form create) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    @stack('styles')
</head>
<body class="h-full flex" x-data="{ sidebarOpen: false }">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col fixed inset-y-0 left-0 z-30 shadow-sm hidden lg:flex">
        @include('layouts.sidebar')
    </aside>

    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen=false" class="fixed inset-0 bg-black/40 z-20 lg:hidden"></div>
    <aside x-show="sidebarOpen" x-cloak class="w-64 bg-white border-r border-slate-200 flex flex-col fixed inset-y-0 left-0 z-30 shadow-lg lg:hidden">
        @include('layouts.sidebar')
    </aside>

    <!-- Main content -->
    <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
        <!-- Top navbar -->
        <header class="bg-white border-b border-slate-200 px-4 lg:px-6 py-3 flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen=!sidebarOpen" class="lg:hidden p-2 rounded-lg hover:bg-slate-100 text-slate-500">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div>
                    <h1 class="text-base font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-slate-400">@yield('page-sub', 'MyInventory System')</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if(auth()->user()->isAdmin() && \App\Models\Borrowing::where('status','pending')->count() > 0)
                <a href="{{ route('borrowings.index') }}" class="relative p-2 text-slate-500 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors">
                    <i class="fa-solid fa-bell"></i>
                    <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                </a>
                @endif
                <div class="relative" x-data="{ open: false }">
                    <button @click="open=!open" class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-slate-100 transition-colors">
                        <div class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-white text-sm font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-sm font-medium text-slate-800 leading-none">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400 mt-0.5 capitalize">{{ auth()->user()->role }}</p>
                        </div>
                        <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                    </button>
                    <div x-show="open" x-cloak @click.outside="open=false"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-200 py-1 z-50">
                        <div class="px-4 py-2 border-b border-slate-100">
                            <p class="text-xs font-medium text-slate-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-slate-400">{{ auth()->user()->email }}</p>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" class="p-1">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                <i class="fa-solid fa-right-from-bracket"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page content -->
        <main class="flex-1 p-4 lg:p-6">
            {{-- Flash messages --}}
            @if(session('success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
                 class="mb-4 flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                {{ session('success') }}
                <button @click="show=false" class="ml-auto text-emerald-400 hover:text-emerald-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
            @endif
            @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                 class="mb-4 flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm">
                <i class="fa-solid fa-circle-exclamation text-red-500"></i>
                {{ session('error') }}
                <button @click="show=false" class="ml-auto text-red-400 hover:text-red-600"><i class="fa-solid fa-xmark"></i></button>
            </div>
            @endif

            @yield('content')
        </main>

        <footer class="border-t border-slate-200 px-6 py-3 text-center text-xs text-slate-400">
            © {{ date('Y') }} MyInventory. All rights reserved.
        </footer>
    </div>

    {{-- jQuery + Select2 JS global (wajib sebelum Alpine agar $ ready) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global delete confirm
        document.querySelectorAll('[data-confirm-delete]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form') || document.querySelector(this.dataset.form);
                Swal.fire({
                    title: 'Hapus Data?',
                    text: this.dataset.confirmDelete || 'Data yang dihapus tidak dapat dikembalikan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    borderRadius: '12px',
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });

        // Logout confirm dengan SweetAlert
        function confirmLogout() {
            Swal.fire({
                title: 'Keluar dari Sistem?',
                text: 'Anda akan logout dari MyInventory.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: '<i class="fa-solid fa-right-from-bracket mr-1"></i> Ya, Logout',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>