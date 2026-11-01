@extends('layouts.app')

@section('title', 'Laporan Keuangan & Operasional')
@section('page_title', 'Laporan Terpadu')

@section('content')
<div class="space-y-6">

    <!-- Header Section (PDF 2 Slide 7) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-sky-100 text-[#0288d1] flex items-center justify-center text-xl font-bold shadow-inner">
                📊
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight">Laporan Keuangan & Operasional</h2>
                <p class="text-xs text-slate-500">Ringkasan transaksi dan analitik bisnis Maudy Laundry</p>
            </div>
        </div>

        <a href="{{ route('laporan.print', ['tab' => $tab, 'start_date' => $startDate->toDateString(), 'end_date' => $endDate->toDateString()]) }}" 
           target="_blank"
           class="px-4 py-2.5 rounded-xl bg-[#0288d1] hover:bg-[#0277bd] text-white text-xs font-semibold shadow-md shadow-sky-500/20 transition inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Cetak Laporan Resmi</span>
        </a>
    </div>

    <!-- Navigation Tabs (PDF 2 Slide 7) -->
    <div class="bg-white rounded-2xl p-1.5 border border-slate-100 shadow-sm flex flex-wrap gap-1">
        <a href="{{ route('laporan.index', ['tab' => 'detail', 'start_date' => $startDate->toDateString(), 'end_date' => $endDate->toDateString()]) }}"
           class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ $tab === 'detail' ? 'bg-[#0288d1] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
            📋 Detail Transaksi
        </a>
        <a href="{{ route('laporan.index', ['tab' => 'harian', 'start_date' => $startDate->toDateString(), 'end_date' => $endDate->toDateString()]) }}"
           class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ $tab === 'harian' ? 'bg-[#0288d1] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
            📅 Laporan Harian
        </a>
        <a href="{{ route('laporan.index', ['tab' => 'mingguan', 'start_date' => $startDate->toDateString(), 'end_date' => $endDate->toDateString()]) }}"
           class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ $tab === 'mingguan' ? 'bg-[#0288d1] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
            📆 Laporan Mingguan
        </a>
        <a href="{{ route('laporan.index', ['tab' => 'bulanan', 'start_date' => $startDate->toDateString(), 'end_date' => $endDate->toDateString()]) }}"
           class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ $tab === 'bulanan' ? 'bg-[#0288d1] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
            🗓️ Laporan Bulanan
        </a>
        <a href="{{ route('laporan.index', ['tab' => 'tahunan', 'start_date' => $startDate->toDateString(), 'end_date' => $endDate->toDateString()]) }}"
           class="px-4 py-2 rounded-xl text-xs font-semibold transition {{ $tab === 'tahunan' ? 'bg-[#0288d1] text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50' }}">
            📊 Laporan Tahunan
        </a>
    </div>

    <!-- Filter Form (PDF 2 Slide 7) -->
    <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
        <form action="{{ route('laporan.index') }}" method="GET" class="flex flex-col sm:flex-row items-end gap-4">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="flex-1 w-full sm:w-auto">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Mulai Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate->toDateString() }}" 
                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-mono focus:border-[#0288d1]">
            </div>
            <div class="flex-1 w-full sm:w-auto">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate->toDateString() }}" 
                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-mono focus:border-[#0288d1]">
            </div>
            <div class="flex items-center gap-2">
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#0288d1] hover:bg-[#0277bd] text-white text-xs font-semibold shadow-md shadow-sky-500/20 transition">
                    Tampilkan
                </button>
            </div>
        </form>
    </div>

    <!-- 4 Summary Metric Cards (PDF 2 Slide 7) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">TOTAL PENDAPATAN</span>
                <h3 class="text-2xl font-extrabold text-emerald-600 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-400 mt-1 font-medium">Periode terpilih</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                💰
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">TOTAL PESANAN</span>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalPesanan }}</h3>
                <p class="text-xs text-slate-400 mt-1 font-medium">Order masuk</p>
            </div>
            <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                🧺
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">PESANAN SELESAI</span>
                <h3 class="text-3xl font-extrabold text-emerald-600 mt-1">{{ $pesananSelesai }}</h3>
                <p class="text-xs text-slate-400 mt-1 font-medium">Siap / telah diambil</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                ✓
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">RATA-RATA / TRANSAKSI</span>
                <h3 class="text-2xl font-extrabold text-slate-800 mt-1">Rp {{ number_format($rataRata, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-400 mt-1 font-medium">Nilai per pesanan</p>
            </div>
            <div class="w-12 h-12 bg-cyan-50 text-cyan-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                📈
            </div>
        </div>
    </div>

    <!-- 2 Columns: Main Report Table & Layanan Terpopuler (PDF 2 Slide 7) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Left Table (Span 2) -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-[#0288d1]"></span>
                    <h3 class="font-bold text-slate-800 text-sm">
                        @if($tab === 'detail') Detail Transaksi Pembayaran
                        @elseif($tab === 'harian') Laporan Rekap Harian
                        @elseif($tab === 'mingguan') Laporan Rekap Mingguan
                        @elseif($tab === 'bulanan') Laporan Rekap Bulanan
                        @else Laporan Rekap Tahunan
                        @endif
                    </h3>
                </div>
                <span class="text-xs font-mono text-slate-400">
                    {{ $startDate->translatedFormat('d M Y') }} — {{ $endDate->translatedFormat('d M Y') }}
                </span>
            </div>

            <div class="overflow-x-auto">
                @if($tab === 'detail')
                <!-- Detail Transaksi Table -->
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/75 text-slate-500 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">ID BAYAR</th>
                            <th class="py-3 px-4">TANGGAL</th>
                            <th class="py-3 px-4">PELANGGAN</th>
                            <th class="py-3 px-4">JUMLAH</th>
                            <th class="py-3 px-4">METODE</th>
                            <th class="py-3 px-4">STATUS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($detailTransaksi as $dt)
                        <tr class="hover:bg-sky-50/30 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-sky-700">{{ $dt->id }}</td>
                            <td class="py-3.5 px-4 text-slate-600 font-mono">{{ \Carbon\Carbon::parse($dt->tgl_bayar)->translatedFormat('d M Y') }}</td>
                            <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $dt->pesanan?->pelanggan?->nama_lengkap ?? '-' }}</td>
                            <td class="py-3.5 px-4 font-bold font-mono text-slate-800">Rp {{ number_format($dt->jumlah_bayar, 0, ',', '.') }}</td>
                            <td class="py-3.5 px-4 font-mono font-semibold text-slate-700">{{ $dt->metode_bayar }}</td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                    {{ $dt->status_bayar }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400 text-xs">Tidak ada transaksi pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @elseif($tab === 'harian')
                <!-- Laporan Harian Table -->
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/75 text-slate-500 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">TANGGAL</th>
                            <th class="py-3 px-4 text-center">TOTAL TRANSAKSI</th>
                            <th class="py-3 px-4 text-right">TOTAL PENDAPATAN</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($laporanHarian as $lh)
                        <tr class="hover:bg-sky-50/30 transition">
                            <td class="py-3.5 px-4 font-semibold text-slate-800">{{ \Carbon\Carbon::parse($lh->tanggal)->translatedFormat('d F Y') }}</td>
                            <td class="py-3.5 px-4 text-center font-mono">{{ $lh->total_transaksi }} transaksi</td>
                            <td class="py-3.5 px-4 text-right font-bold font-mono text-emerald-600">Rp {{ number_format($lh->total_pendapatan, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-8 text-center text-slate-400 text-xs">Tidak ada data harian pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @elseif($tab === 'mingguan')
                <!-- Laporan Mingguan Table -->
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/75 text-slate-500 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">MINGGU KE-</th>
                            <th class="py-3 px-4">RENTANG TANGGAL</th>
                            <th class="py-3 px-4 text-center">TOTAL TRANSAKSI</th>
                            <th class="py-3 px-4 text-right">TOTAL PENDAPATAN</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($laporanMingguan as $lm)
                        <tr class="hover:bg-sky-50/30 transition">
                            <td class="py-3.5 px-4 font-bold text-sky-700">{{ $lm['label'] }}</td>
                            <td class="py-3.5 px-4 text-slate-600 font-mono">{{ $lm['rentang'] }}</td>
                            <td class="py-3.5 px-4 text-center font-mono">{{ $lm['total_transaksi'] }} transaksi</td>
                            <td class="py-3.5 px-4 text-right font-bold font-mono text-emerald-600">Rp {{ number_format($lm['total_pendapatan'], 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 text-xs">Tidak ada data mingguan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @elseif($tab === 'bulanan')
                <!-- Laporan Bulanan Table -->
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/75 text-slate-500 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">BULAN</th>
                            <th class="py-3 px-4">TAHUN</th>
                            <th class="py-3 px-4 text-center">TOTAL TRANSAKSI</th>
                            <th class="py-3 px-4 text-right">TOTAL PENDAPATAN</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($laporanBulanan as $lb)
                        <tr class="hover:bg-sky-50/30 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-800">{{ $lb['bulan'] }}</td>
                            <td class="py-3.5 px-4 font-mono text-slate-600">{{ $lb['tahun'] }}</td>
                            <td class="py-3.5 px-4 text-center font-mono">{{ $lb['total_transaksi'] }} transaksi</td>
                            <td class="py-3.5 px-4 text-right font-bold font-mono text-emerald-600">Rp {{ number_format($lb['total_pendapatan'], 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 text-xs">Tidak ada data bulanan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                @else
                <!-- Laporan Tahunan Table -->
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50/75 text-slate-500 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-100">
                        <tr>
                            <th class="py-3 px-4">TAHUN</th>
                            <th class="py-3 px-4 text-center">TOTAL TRANSAKSI</th>
                            <th class="py-3 px-4 text-center">TOTAL PESANAN</th>
                            <th class="py-3 px-4 text-right">TOTAL PENDAPATAN</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($laporanTahunan as $lt)
                        <tr class="hover:bg-sky-50/30 transition">
                            <td class="py-3.5 px-4 font-bold text-sky-700 font-mono">{{ $lt['tahun'] }}</td>
                            <td class="py-3.5 px-4 text-center font-mono">{{ $lt['total_transaksi'] }} transaksi</td>
                            <td class="py-3.5 px-4 text-center font-mono">{{ $lt['total_pesanan'] }} pesanan</td>
                            <td class="py-3.5 px-4 text-right font-bold font-mono text-emerald-600">Rp {{ number_format($lt['total_pendapatan'], 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 text-xs">Tidak ada data tahunan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                @endif
            </div>
        </div>

        <!-- Right: Layanan Terpopuler (Span 1) Sesuai PDF 2 Slide 7 -->
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 space-y-4 h-fit">
            <div class="flex items-center gap-2 pb-3 border-b border-slate-100">
                <span class="text-lg">🏆</span>
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Layanan Terpopuler</h3>
                    <p class="text-[11px] text-slate-400">Peringkat kontribusi omset</p>
                </div>
            </div>

            <div class="space-y-3">
                @foreach($layananTerpopuler as $idx => $lt)
                <div class="p-3 rounded-xl bg-slate-50 hover:bg-sky-50/50 transition flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-6 h-6 rounded-full bg-white border border-slate-200 text-slate-600 font-bold flex items-center justify-center text-xs">
                            {{ $idx + 1 }}
                        </span>
                        <div>
                            <h4 class="font-semibold text-xs text-slate-800">{{ $lt->nama_layanan }}</h4>
                            <span class="text-[10px] text-slate-400 font-medium">{{ $lt->pesanan_count }} pesanan</span>
                        </div>
                    </div>
                    <span class="text-xs font-mono font-bold text-[#0288d1]">
                        Rp {{ number_format($lt->pesanan_sum_total_harga ?? 0, 0, ',', '.') }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
