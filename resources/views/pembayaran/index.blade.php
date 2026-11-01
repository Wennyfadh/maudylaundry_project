@extends('layouts.app')

@section('title', 'Data Pembayaran')
@section('page_title', 'Transaksi Pembayaran Laundry')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-sky-100 text-[#0288d1] flex items-center justify-center text-xl font-bold shadow-inner">
            💳
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">Data Pembayaran</h2>
            <p class="text-xs text-slate-500">Kelola informasi pembayaran laundry</p>
        </div>
    </div>

    <!-- Stat Cards (PDF 2 Slide 5) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">TOTAL LUNAS</span>
                <h3 class="text-2xl font-extrabold text-emerald-600 mt-1">Rp {{ number_format($totalLunas, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-400 mt-1 font-medium">Pendapatan diterima</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                💰
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">BELUM LUNAS</span>
                <h3 class="text-2xl font-extrabold text-amber-600 mt-1">{{ $pesananBelumLunasCount }}</h3>
                <p class="text-xs text-slate-400 mt-1 font-medium">Pesanan pending</p>
            </div>
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                ⏳
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">ID SELANJUTNYA</span>
                <h3 class="text-2xl font-extrabold text-[#0288d1] font-mono mt-1">{{ $nextId }}</h3>
                <p class="text-xs text-slate-400 mt-1 font-medium">Otomatis generate</p>
            </div>
            <div class="w-12 h-12 bg-cyan-50 text-cyan-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                🆔
            </div>
        </div>
    </div>

    <!-- Form Catat Pembayaran Baru (PDF 2 Slide 5) -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <div class="flex items-center gap-2 mb-5 pb-3 border-b border-slate-100">
            <span class="w-7 h-7 rounded-xl bg-[#0288d1] text-white flex items-center justify-center text-xs font-bold">💳</span>
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Catat Pembayaran Baru</h3>
                <p class="text-[11px] text-slate-400">Input transaksi pembayaran pelanggan</p>
            </div>
        </div>

        <form action="{{ route('pembayaran.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ID Pembayaran *</label>
                    <input type="text" name="id" value="{{ old('id', $nextId) }}" readonly
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono font-bold text-sky-700 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ID Pesanan *</label>
                    <select name="pesanan_id" id="pesanan_select" required onchange="updateBayarAmount(this)"
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs">
                        <option value="">-- Pilih Pesanan --</option>
                        @foreach($unpaidPesanan as $up)
                        <option value="{{ $up->id }}" data-total="{{ $up->total_harga }}">
                            {{ $up->id }} - {{ $up->pelanggan?->nama_lengkap }} (Rp {{ number_format($up->total_harga, 0, ',', '.') }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Bayar *</label>
                    <input type="date" name="tgl_bayar" value="{{ old('tgl_bayar', now()->toDateString()) }}" required
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs font-mono">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Metode Bayar *</label>
                    <select name="metode_bayar" required
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs">
                        <option value="CASH">CASH (Tunai)</option>
                        <option value="QRIS">QRIS</option>
                        <option value="TRANSFER">TRANSFER Bank</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah Bayar (Rp) *</label>
                    <input type="number" id="jumlah_bayar_input" name="jumlah_bayar" value="{{ old('jumlah_bayar', 0) }}" required min="1" step="any"
                           placeholder="0"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status Bayar *</label>
                    <select name="status_bayar" required
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs">
                        <option value="Lunas">Lunas</option>
                        <option value="Belum Lunas">Belum Lunas (DP)</option>
                    </select>
                </div>
            </div>

            <div class="mt-5 flex items-center gap-3">
                <button type="reset" class="px-5 py-2 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 text-xs font-medium transition">
                    Reset
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#0288d1] hover:bg-[#0277bd] text-white text-xs font-semibold shadow-md shadow-sky-500/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span>Simpan Pembayaran</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Table Riwayat Pembayaran (PDF 2 Slide 6) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#0288d1]"></span>
                <h3 class="font-bold text-slate-800 text-sm">Riwayat Pembayaran</h3>
                <span class="text-xs text-slate-400 font-medium ml-2">({{ $pembayarans->total() }} Transaksi)</span>
            </div>

            <form action="{{ route('pembayaran.index') }}" method="GET" class="flex items-center gap-2">
                <select name="metode" class="px-3 py-1.5 text-xs rounded-xl border border-slate-200">
                    <option value="">Semua Metode</option>
                    <option value="CASH" {{ $metodeFilter == 'CASH' ? 'selected' : '' }}>CASH</option>
                    <option value="QRIS" {{ $metodeFilter == 'QRIS' ? 'selected' : '' }}>QRIS</option>
                    <option value="TRANSFER" {{ $metodeFilter == 'TRANSFER' ? 'selected' : '' }}>TRANSFER</option>
                </select>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari ID/Pelanggan..."
                       class="px-3.5 py-1.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-[#0288d1]">
                <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-medium text-slate-700">
                    Cari
                </button>
                @if($search || $metodeFilter)
                <a href="{{ route('pembayaran.index') }}" class="text-xs text-rose-500 hover:underline">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/75 text-slate-500 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">ID BAYAR</th>
                        <th class="py-3 px-4">ID PESANAN</th>
                        <th class="py-3 px-4">PELANGGAN</th>
                        <th class="py-3 px-4">TGL BAYAR</th>
                        <th class="py-3 px-4">METODE</th>
                        <th class="py-3 px-4">JUMLAH</th>
                        <th class="py-3 px-4">STATUS</th>
                        <th class="py-3 px-4 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pembayarans as $pb)
                    <tr class="hover:bg-sky-50/30 transition">
                        <td class="py-3.5 px-4 font-mono font-bold text-sky-700">{{ $pb->id }}</td>
                        <td class="py-3.5 px-4 font-mono font-semibold text-slate-700">
                            <a href="{{ route('pesanan.nota', $pb->pesanan_id) }}" class="hover:underline text-[#0288d1]">
                                {{ $pb->pesanan_id }}
                            </a>
                        </td>
                        <td class="py-3.5 px-4 font-medium text-slate-800">
                            {{ $pb->pesanan?->pelanggan?->nama_lengkap ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-600">
                            {{ \Carbon\Carbon::parse($pb->tgl_bayar)->translatedFormat('d M Y') }}
                        </td>
                        <td class="py-3.5 px-4 font-mono text-[11px] font-semibold text-slate-700">
                            {{ $pb->metode_bayar }}
                        </td>
                        <td class="py-3.5 px-4 font-bold font-mono text-slate-800">
                            Rp {{ number_format($pb->jumlah_bayar, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-4">
                            @if($pb->status_bayar === 'Lunas')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                Lunas
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-700 border border-amber-200">
                                Belum Lunas
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('pembayaran.struk', $pb->id) }}" target="_blank"
                                   class="px-2.5 py-1 rounded-lg border border-sky-400 text-sky-600 hover:bg-sky-50 font-medium transition inline-flex items-center gap-1 text-[11px]" title="Cetak Struk">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    <span>Struk</span>
                                </a>

                                @if(Auth::user()->isOwner())
                                <form action="{{ route('pembayaran.destroy', $pb->id) }}" method="POST" onsubmit="return confirm('Hapus catatan pembayaran {{ $pb->id }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-1 rounded-lg text-rose-500 hover:bg-rose-50 transition" title="Hapus Pembayaran">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-8 text-center text-slate-400 text-xs">
                            Tidak ada data transaksi pembayaran.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pembayarans->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $pembayarans->links() }}
        </div>
        @endif
    </div>

</div>

<script>
    function updateBayarAmount(select) {
        const option = select.options[select.selectedIndex];
        const total = option.getAttribute('data-total');
        if (total) {
            document.getElementById('jumlah_bayar_input').value = total;
        }
    }
</script>
@endsection
