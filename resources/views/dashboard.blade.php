@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Ringkasan')

@section('content')
<div class="space-y-6">

    <!-- WELCOME BANNER (Sesuai Referensi PDF 2 Slide 1) -->
    <div class="bg-gradient-to-r from-[#0288d1] via-[#039be5] to-[#29b6f6] rounded-3xl p-6 md:p-8 text-white shadow-lg shadow-sky-500/15 relative overflow-hidden flex items-center justify-between">
        <div class="relative z-10 max-w-xl">
            <h2 class="text-2xl md:text-3xl font-extrabold tracking-tight">Selamat Datang di Maudy Laundry! 🧺</h2>
            <p class="text-sky-100 text-xs md:text-sm mt-1.5 font-medium">
                Sistem Informasi Manajemen Laundry — {{ $tanggalFormat }}
            </p>
            <div class="mt-4 flex flex-wrap gap-2 text-xs">
                <span class="bg-white/20 backdrop-blur px-3 py-1 rounded-full font-medium">
                    👤 Logged in as: <strong>{{ Auth::user()->name }}</strong> ({{ strtoupper(Auth::user()->role) }})
                </span>
                <span class="bg-white/20 backdrop-blur px-3 py-1 rounded-full font-medium">
                    🏷️ Jabatan: <strong>{{ Auth::user()->pegawai?->jabatan ?? 'Owner/Administrator' }}</strong>
                </span>
            </div>
        </div>
        <div class="hidden sm:block text-7xl opacity-90 pr-4 select-none animate-pulse">
            🧺
        </div>
    </div>

    <!-- 4 METRIC STAT CARDS (Sesuai Referensi PDF 2 Slide 1) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Card 1: Total Pelanggan -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">TOTAL PELANGGAN</span>
                    <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalPelanggan }}</h3>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Pelanggan terdaftar</p>
                </div>
                <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                    👥
                </div>
            </div>
        </div>

        <!-- Card 2: Pesanan Aktif -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">PESANAN AKTIF</span>
                    <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $pesananAktif }}</h3>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Menunggu & Proses</p>
                </div>
                <div class="w-12 h-12 bg-cyan-50 text-cyan-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                    📋
                </div>
            </div>
        </div>

        <!-- Card 3: Pendapatan Bulan Ini -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">PENDAPATAN BULAN INI</span>
                    <h3 class="text-2xl font-extrabold text-emerald-600 mt-1">Rp {{ number_format($pendapatanBulanIni, 0, ',', '.') }}</h3>
                    <p class="text-xs text-slate-400 mt-1 font-medium">{{ $bulanNama }}</p>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                    💰
                </div>
            </div>
        </div>

        <!-- Card 4: Total Pegawai -->
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">TOTAL PEGAWAI</span>
                    <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalPegawai }}</h3>
                    <p class="text-xs text-slate-400 mt-1 font-medium">Tim Aktif</p>
                </div>
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                    👔
                </div>
            </div>
        </div>
    </div>

    <!-- 2 COLUMNS: PESANAN TERBARU & STOK RENDAH (PDF 2 Slide 1) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left: Pesanan Terbaru (Span 2) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-[#0288d1]"></span>
                        <h3 class="font-bold text-slate-800 text-sm">Pesanan Terbaru</h3>
                    </div>
                    <a href="{{ route('pesanan.index') }}" 
                       class="text-xs font-semibold text-sky-600 hover:text-sky-700 bg-sky-50 hover:bg-sky-100 px-3 py-1.5 rounded-xl transition">
                        Lihat Semua
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50/75 text-slate-500 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-4">ID</th>
                                <th class="py-3 px-4">PELANGGAN</th>
                                <th class="py-3 px-4">TGL MASUK</th>
                                <th class="py-3 px-4">STATUS</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($pesananTerbaru as $p)
                            <tr class="hover:bg-sky-50/40 transition">
                                <td class="py-3 px-4 font-mono font-bold text-sky-700">
                                    <a href="{{ route('pesanan.nota', $p->id) }}" class="hover:underline">
                                        {{ $p->id }}
                                    </a>
                                </td>
                                <td class="py-3 px-4 font-medium text-slate-800">
                                    {{ $p->pelanggan?->nama_lengkap ?? '-' }}
                                    <span class="block text-[10px] text-slate-400 font-normal">{{ $p->layanan?->nama_layanan }} ({{ $p->berat_kg }} kg)</span>
                                </td>
                                <td class="py-3 px-4 text-slate-500">
                                    {{ \Carbon\Carbon::parse($p->tgl_masuk)->translatedFormat('d M Y') }}
                                </td>
                                <td class="py-3 px-4">
                                    @if($p->status_pesanan === 'Selesai')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                        Selesai
                                    </span>
                                    @elseif($p->status_pesanan === 'Diproses')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-700 border border-amber-200">
                                        Diproses
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-cyan-100 text-cyan-700 border border-cyan-200">
                                        Menunggu
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400 text-xs">
                                    Belum ada data pesanan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="p-3 bg-slate-50 border-t border-slate-100 text-right">
                <a href="{{ route('pesanan.index') }}" class="text-xs text-sky-600 hover:text-sky-700 font-medium">
                    + Buat Pesanan Baru →
                </a>
            </div>
        </div>

        <!-- Right: Stok Rendah (Span 1) -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between">
            <div>
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="text-amber-500">⚠️</span>
                        <h3 class="font-bold text-slate-800 text-sm">Stok Rendah</h3>
                    </div>
                    <a href="{{ route('inventaris.index') }}" 
                       class="text-xs font-semibold text-sky-600 hover:text-sky-700 bg-sky-50 hover:bg-sky-100 px-3 py-1.5 rounded-xl transition">
                        Kelola Stok
                    </a>
                </div>

                @if($stokRendah->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50/75 text-slate-500 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="py-3 px-4">BARANG</th>
                                <th class="py-3 px-4">STOK</th>
                                <th class="py-3 px-4">SATUAN</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($stokRendah as $inv)
                            <tr class="hover:bg-amber-50/50 transition">
                                <td class="py-3 px-4 font-semibold text-slate-800">
                                    {{ $inv->nama_barang }}
                                    <span class="block text-[10px] text-slate-400">Min: {{ $inv->minimum_stok }}</span>
                                </td>
                                <td class="py-3 px-4 font-bold text-rose-600">
                                    {{ $inv->stok }}
                                </td>
                                <td class="py-3 px-4 text-slate-500">
                                    {{ $inv->satuan }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="p-8 text-center flex flex-col items-center justify-center">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-3xl shadow-inner mb-3">
                        ✓
                    </div>
                    <p class="font-bold text-slate-700 text-sm">Stok aman semua!</p>
                    <p class="text-xs text-slate-400 mt-1">Semua bahan baku dan perlengkapan masih di atas batas minimum.</p>
                </div>
                @endif
            </div>

            <div class="p-3 bg-slate-50 border-t border-slate-100 text-right">
                <a href="{{ route('purchases.create') }}" class="text-xs text-sky-600 hover:text-sky-700 font-medium">
                    + Restok Barang (Pembelian) →
                </a>
            </div>
        </div>

    </div>

</div>
@endsection
