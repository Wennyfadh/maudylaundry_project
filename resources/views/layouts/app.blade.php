<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Maudy Laundry</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0288d1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        maudy: {
                            blue: '#0288d1',
                            dark: '#01579b',
                            light: '#e1f5fe',
                            accent: '#29b6f6',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'Segoe UI', 'Roboto', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(2, 136, 209, 0.2); border-radius: 999px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(2, 136, 209, 0.4); }

        /* Aesthetic Sidebar Laundry Bubbles */
        .sidebar-bubble {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 35%, rgba(255, 255, 255, 0.45) 0%, rgba(255, 255, 255, 0.1) 35%, transparent 70%);
            border: 1px solid rgba(255, 255, 255, 0.35);
            box-shadow: inset 0 0 8px rgba(255, 255, 255, 0.3);
            pointer-events: none;
            z-index: 1;
        }
        .sidebar-bubble-1 {
            width: 75px; height: 75px;
            bottom: 25px; right: -20px;
            animation: sidebarFloat 7s infinite ease-in-out;
        }
        .sidebar-bubble-2 {
            width: 36px; height: 36px;
            bottom: 110px; left: 15px;
            animation: sidebarFloat 5.5s infinite ease-in-out 1.5s;
        }
        .sidebar-bubble-3 {
            width: 24px; height: 24px;
            bottom: 175px; right: 25px;
            animation: sidebarFloat 6.2s infinite ease-in-out 3s;
        }
        @keyframes sidebarFloat {
            0%, 100% { transform: translateY(0) scale(1); opacity: 0.45; }
            50% { transform: translateY(-16px) scale(1.08); opacity: 0.75; }
        }
    </style>
</head>
<body class="bg-[#f0f6fc] text-slate-700 antialiased min-h-screen flex flex-col md:flex-row">

    <!-- SIDEBAR KIRI MELENGKUNG (Curved Blue Sidebar) Sesuai Referensi PDF 2 -->
    <aside class="w-full md:w-64 bg-gradient-to-b from-[#0288d1] to-[#0277bd] text-white flex-shrink-0 md:min-h-screen md:rounded-r-[28px] shadow-xl z-30 flex flex-col justify-between relative overflow-hidden">
        <!-- Floating Subtle Soap Bubbles -->
        <div class="sidebar-bubble sidebar-bubble-1"></div>
        <div class="sidebar-bubble sidebar-bubble-2"></div>
        <div class="sidebar-bubble sidebar-bubble-3"></div>

        <div class="p-5 relative z-10">
            <!-- Brand Logo -->
            <div class="flex items-center gap-3 pb-6 border-b border-white/20">
                <div class="w-11 h-11 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                    🧺
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight text-white leading-tight">Maudy Laundry</h1>
                    <p class="text-xs text-sky-100 font-medium">Sistem Informasi</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="mt-6 space-y-6 text-sm">
                <!-- UTAMA -->
                <div>
                    <span class="text-[11px] font-bold text-sky-200 uppercase tracking-wider px-3">UTAMA</span>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('dashboard') ? 'bg-white text-[#0288d1] shadow-md font-semibold' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            <span>Dashboard</span>
                        </a>
                    </div>
                </div>

                <!-- TRANSAKSI -->
                <div>
                    <span class="text-[11px] font-bold text-sky-200 uppercase tracking-wider px-3">TRANSAKSI</span>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('pesanan.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('pesanan.*') ? 'bg-white text-[#0288d1] shadow-md font-semibold' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            <span>Pesanan</span>
                        </a>
                        <a href="{{ route('pembayaran.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('pembayaran.*') ? 'bg-white text-[#0288d1] shadow-md font-semibold' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            <span>Pembayaran</span>
                        </a>
                    </div>
                </div>

                <!-- LOGISTIK / STOK (PDF 1 Requirement) -->
                <div>
                    <span class="text-[11px] font-bold text-sky-200 uppercase tracking-wider px-3">PENGADAAN & STOK</span>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('purchases.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('purchases.*') ? 'bg-white text-[#0288d1] shadow-md font-semibold' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20"/></svg>
                            <span>Pembelian Stok</span>
                        </a>
                        <a href="{{ route('suppliers.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('suppliers.*') ? 'bg-white text-[#0288d1] shadow-md font-semibold' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>Supplier</span>
                        </a>
                        <a href="{{ route('categories.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('categories.*') ? 'bg-white text-[#0288d1] shadow-md font-semibold' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            <span>Kategori</span>
                        </a>
                    </div>
                </div>

                <!-- DATA MASTER -->
                <div>
                    <span class="text-[11px] font-bold text-sky-200 uppercase tracking-wider px-3">DATA MASTER</span>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('pelanggan.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('pelanggan.*') ? 'bg-white text-[#0288d1] shadow-md font-semibold' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <span>Pelanggan</span>
                        </a>

                        @if(Auth::user() && Auth::user()->isOwner())
                        <a href="{{ route('pegawai.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('pegawai.*') ? 'bg-white text-[#0288d1] shadow-md font-semibold' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                            <span>Pegawai</span>
                            <span class="ml-auto text-[10px] bg-amber-400 text-amber-950 font-bold px-1.5 py-0.5 rounded">Owner</span>
                        </a>
                        @endif

                        <a href="{{ route('layanan.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('layanan.*') ? 'bg-white text-[#0288d1] shadow-md font-semibold' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                            <span>Layanan</span>
                        </a>
                        <a href="{{ route('inventaris.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('inventaris.*') ? 'bg-white text-[#0288d1] shadow-md font-semibold' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <span>Inventaris</span>
                        </a>
                    </div>
                </div>

                <!-- LAPORAN -->
                <div>
                    <span class="text-[11px] font-bold text-sky-200 uppercase tracking-wider px-3">LAPORAN</span>
                    <div class="mt-2 space-y-1">
                        <a href="{{ route('laporan.index') }}" 
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-medium transition {{ request()->routeIs('laporan.*') ? 'bg-white text-[#0288d1] shadow-md font-semibold' : 'text-white/90 hover:bg-white/10 hover:text-white' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Laporan Keuangan</span>
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- User Profile & Logout Box -->
        <div class="p-4 m-3 bg-white/10 backdrop-blur rounded-2xl border border-white/15">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5 overflow-hidden">
                    <div class="w-9 h-9 rounded-xl bg-white text-[#0288d1] font-bold flex items-center justify-center text-sm flex-shrink-0 shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="truncate">
                        <p class="text-xs font-semibold text-white truncate">{{ Auth::user()->name ?? 'User' }}</p>
                        <span class="inline-block text-[10px] font-medium px-2 py-0.5 rounded-full {{ Auth::user() && Auth::user()->isOwner() ? 'bg-amber-400 text-amber-950 font-bold' : 'bg-sky-200 text-sky-900' }}">
                            {{ Auth::user() && Auth::user()->isOwner() ? 'Owner' : 'Staff' }}
                        </span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Logout dari sistem" 
                            class="p-2 text-white/80 hover:text-white hover:bg-white/20 rounded-xl transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT WRAPPER -->
    <div class="flex-1 flex flex-col min-w-0">
        <!-- Top Utility Bar -->
        <header class="bg-white border-b border-slate-200/80 px-6 py-3.5 flex items-center justify-between sticky top-0 z-20 shadow-xs">
            <div class="flex items-center gap-3">
                <span class="text-xs text-slate-400 font-medium">Maudy Laundry Management</span>
                <span class="text-slate-300">/</span>
                <span class="text-xs font-semibold text-slate-700">@yield('page_title', 'Dashboard')</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs text-sky-600 hover:text-sky-700 bg-sky-50 hover:bg-sky-100 font-medium px-3 py-1.5 rounded-lg transition">
                    <span>Lihat Web Publik</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                <span class="text-xs text-slate-500 bg-slate-100 px-2.5 py-1 rounded-md font-mono">
                    {{ now()->translatedFormat('d M Y') }}
                </span>
            </div>
        </header>

        <!-- Flash Messages & Toasts -->
        <main class="p-6 md:p-8 flex-1 custom-scrollbar">
            @if(session('success'))
            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start gap-3 shadow-xs">
                <div class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center flex-shrink-0 text-sm font-bold">✓</div>
                <div class="flex-1">
                    <p class="font-semibold text-sm">Berhasil!</p>
                    <p class="text-xs text-emerald-700 mt-0.5">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start gap-3 shadow-xs">
                <div class="w-6 h-6 rounded-full bg-rose-500 text-white flex items-center justify-center flex-shrink-0 text-sm font-bold">✕</div>
                <div class="flex-1">
                    <p class="font-semibold text-sm">Perhatian / Gagal</p>
                    <p class="text-xs text-rose-700 mt-0.5">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 flex items-start gap-3 shadow-xs">
                <div class="w-6 h-6 rounded-full bg-amber-500 text-white flex items-center justify-center flex-shrink-0 text-sm font-bold">!</div>
                <div class="flex-1">
                    <p class="font-semibold text-sm">Ada kesalahan input:</p>
                    <ul class="text-xs text-amber-700 mt-1 list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
