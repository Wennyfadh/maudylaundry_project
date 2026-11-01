@extends('layouts.app')

@section('title', 'Divisi Pesanan')
@section('page_title', 'Transaksi Pesanan Laundry')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-sky-100 text-[#0288d1] flex items-center justify-center text-xl font-bold shadow-inner">
            🧺
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">Divisi Pesanan</h2>
            <p class="text-xs text-slate-500">Kelola dan input transaksi pesanan laundry pelanggan</p>
        </div>
    </div>

    <!-- Form Buat Pesanan Baru (PDF 2 Slide 6) -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <div class="flex items-center gap-2 mb-5 pb-3 border-b border-slate-100">
            <span class="w-7 h-7 rounded-xl bg-[#0288d1] text-white flex items-center justify-center text-xs font-bold">📋</span>
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Buat Pesanan Baru</h3>
                <p class="text-[11px] text-slate-400">Input pesanan laundry masuk</p>
            </div>
        </div>

        <form action="{{ route('pesanan.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ID Pesanan *</label>
                    <input type="text" name="id" value="{{ old('id', $nextId) }}" readonly
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono font-bold text-sky-700 text-xs">
                </div>
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label class="block text-xs font-semibold text-slate-700">Pelanggan *</label>
                        <a href="{{ route('pelanggan.index') }}" class="text-[10px] text-sky-600 hover:underline">+ Pelanggan Baru</a>
                    </div>
                    <select name="pelanggan_id" required
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs">
                        <option value="">-- Pilih Pelanggan --</option>
                        @foreach($pelanggans as $pl)
                        <option value="{{ $pl->id }}" {{ old('pelanggan_id') == $pl->id ? 'selected' : '' }}>
                            {{ $pl->id }} - {{ $pl->nama_lengkap }} ({{ $pl->no_telp }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Pegawai Penanganan *</label>
                    <select name="pegawai_id" required
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs">
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach($pegawais as $pg)
                        <option value="{{ $pg->id }}" {{ (old('pegawai_id', $currentPegawaiId) == $pg->id) ? 'selected' : '' }}>
                            {{ $pg->nama_lengkap }} ({{ $pg->jabatan }})
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jenis Layanan *</label>
                    <select name="jenis_layanan_id" id="layanan_select" required onchange="calculatePriceAndEstimate()"
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs">
                        <option value="">-- Pilih Layanan --</option>
                        @foreach($layanans as $ly)
                        <option value="{{ $ly->id }}" data-price="{{ $ly->harga_per_kg }}" data-days="{{ $ly->estimasi_waktu_hari }}" {{ old('jenis_layanan_id') == $ly->id ? 'selected' : '' }}>
                            {{ $ly->nama_layanan }} (Rp {{ number_format($ly->harga_per_kg, 0, ',', '.') }}/kg - {{ $ly->estimasi_waktu_hari }} hari)
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Berat (kg) *</label>
                    <input type="number" id="berat_input" name="berat_kg" value="{{ old('berat_kg', '5.0') }}" required min="0.1" step="0.1" oninput="calculatePriceAndEstimate()"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Status Pesanan *</label>
                    <select name="status_pesanan" required
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs">
                        <option value="Menunggu">Menunggu</option>
                        <option value="Diproses">Diproses</option>
                        <option value="Selesai">Selesai</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Masuk *</label>
                    <input type="date" id="tgl_masuk_input" name="tgl_masuk" value="{{ old('tgl_masuk', now()->toDateString()) }}" required onchange="calculatePriceAndEstimate()"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Selesai (Estimasi)</label>
                    <input type="date" id="tgl_selesai_input" name="tgl_selesai_estimasi" value="{{ old('tgl_selesai_estimasi') }}"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 focus:outline-none focus:border-[#0288d1] text-xs font-mono">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Khusus</label>
                <input type="text" name="catatan" value="{{ old('catatan') }}" placeholder="Contoh: Jangan disetrika terlalu panas, wangi lavender..."
                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs">
            </div>

            <!-- Total Preview Banner -->
            <div class="mt-4 p-4 rounded-xl bg-sky-50 border border-sky-100 flex items-center justify-between">
                <div>
                    <span class="text-xs text-sky-800 font-medium">Perhitungan Otomatis Server (Server-Side Calculation):</span>
                    <p class="text-[11px] text-sky-600 mt-0.5">Stok operasional deterjen & plastik diperiksa dan dikunci secara atomik (lockForUpdate).</p>
                </div>
                <div class="text-right">
                    <span class="text-[11px] text-slate-400 block font-semibold uppercase tracking-wider">Estimasi Total</span>
                    <span id="preview_total" class="text-xl font-extrabold text-[#0288d1] font-mono">Rp 0</span>
                </div>
            </div>

            <div class="mt-5 flex items-center gap-3">
                <button type="reset" class="px-5 py-2 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 text-xs font-medium transition">
                    Reset
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#0288d1] hover:bg-[#0277bd] text-white text-xs font-semibold shadow-md shadow-sky-500/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span>Buat Pesanan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Table Daftar Pesanan (PDF 2 Slide 7) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#0288d1]"></span>
                <h3 class="font-bold text-slate-800 text-sm">Daftar Pesanan</h3>
                <span class="text-xs text-slate-400 font-medium ml-2">({{ $pesanans->total() }} Pesanan)</span>
            </div>

            <form action="{{ route('pesanan.index') }}" method="GET" class="flex items-center gap-2">
                <select name="status" class="px-3 py-1.5 text-xs rounded-xl border border-slate-200">
                    <option value="">Semua Status</option>
                    <option value="Menunggu" {{ $statusFilter == 'Menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="Diproses" {{ $statusFilter == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                    <option value="Selesai" {{ $statusFilter == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari ID/Pelanggan/Pegawai..."
                       class="px-3.5 py-1.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-[#0288d1]">
                <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-medium text-slate-700">
                    Cari
                </button>
                @if($search || $statusFilter)
                <a href="{{ route('pesanan.index') }}" class="text-xs text-rose-500 hover:underline">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/75 text-slate-500 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">ID</th>
                        <th class="py-3 px-4">PELANGGAN</th>
                        <th class="py-3 px-4">PEGAWAI</th>
                        <th class="py-3 px-4">TGL MASUK</th>
                        <th class="py-3 px-4">TGL SELESAI</th>
                        <th class="py-3 px-4">TOTAL</th>
                        <th class="py-3 px-4">STATUS</th>
                        <th class="py-3 px-4 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pesanans as $ps)
                    <tr class="hover:bg-sky-50/30 transition">
                        <td class="py-3.5 px-4 font-mono font-bold text-sky-700">
                            <a href="{{ route('pesanan.nota', $ps->id) }}" class="hover:underline" title="Lihat & Cetak Nota">
                                {{ $ps->id }}
                            </a>
                            <div class="flex items-center gap-1 mt-0.5">
                                @if($ps->sumber_order === 'online')
                                <span class="text-[9px] bg-purple-100 text-purple-800 font-bold px-1.5 py-0.2 rounded">Online</span>
                                @endif
                                <span class="text-[9px] {{ $ps->metode_pembayaran === 'QRIS' ? 'bg-sky-100 text-sky-800' : 'bg-emerald-100 text-emerald-800' }} font-bold px-1.5 py-0.2 rounded">
                                    {{ $ps->metode_pembayaran === 'QRIS' ? 'QRIS' : 'COD' }}
                                </span>
                            </div>
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-slate-800">
                            {{ $ps->pelanggan?->nama_lengkap ?? '-' }}
                            <span class="block text-[10px] text-slate-400 font-normal">{{ $ps->layanan?->nama_layanan }} ({{ $ps->berat_kg }} kg)</span>
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 font-medium">
                            {{ $ps->pegawai?->nama_lengkap ?? '— (Belum Diambil)' }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 font-mono text-[11px]">
                            {{ \Carbon\Carbon::parse($ps->tgl_masuk)->translatedFormat('d M Y') }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-500 font-mono text-[11px]">
                            {{ $ps->tgl_selesai_aktual ? \Carbon\Carbon::parse($ps->tgl_selesai_aktual)->translatedFormat('d M Y') : ($ps->tgl_selesai_estimasi ? \Carbon\Carbon::parse($ps->tgl_selesai_estimasi)->translatedFormat('d M Y') : '—') }}
                        </td>
                        <td class="py-3.5 px-4 font-mono text-slate-800">
                            <span class="font-bold">Rp {{ number_format($ps->total_harga, 0, ',', '.') }}</span>
                            <span class="block text-[10px] {{ $ps->isLunas() ? 'text-emerald-600 font-semibold' : 'text-amber-600 font-medium' }}">
                                {{ $ps->isLunas() ? '✓ Lunas' : 'Belum Lunas' }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4">
                            @if($ps->status_pesanan === 'Selesai')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                Selesai
                            </span>
                            @elseif($ps->status_pesanan === 'Diproses')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-700 border border-amber-200">
                                Diproses
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-cyan-100 text-cyan-700 border border-cyan-200">
                                Menunggu
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-1.5">
                                <!-- Cetak Nota -->
                                <a href="{{ route('pesanan.nota', $ps->id) }}" target="_blank"
                                   class="p-1 text-sky-600 hover:bg-sky-50 rounded-lg transition" title="Cetak Nota">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                </a>

                                <!-- Quick Status Switch -->
                                @if($ps->status_pesanan === 'Menunggu')
                                <form action="{{ route('pesanan.status', $ps->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status_pesanan" value="Diproses">
                                    <button type="submit" class="px-2 py-0.5 text-[10px] font-bold rounded bg-amber-100 text-amber-800 hover:bg-amber-200" title="Ubah status ke Diproses">
                                        Proses
                                    </button>
                                </form>
                                @elseif($ps->status_pesanan === 'Diproses')
                                <form action="{{ route('pesanan.status', $ps->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status_pesanan" value="Selesai">
                                    <button type="submit" class="px-2 py-0.5 text-[10px] font-bold rounded bg-emerald-100 text-emerald-800 hover:bg-emerald-200" title="Tandai Selesai">
                                        Selesai
                                    </button>
                                </form>
                                @endif

                                <!-- Hapus / Batalkan (Owner only) -->
                                @if(Auth::user()->isOwner())
                                <form action="{{ route('pesanan.destroy', $ps->id) }}" method="POST" onsubmit="return confirm('Batalkan pesanan {{ $ps->id }} dan pulihkan stok bahan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-1 rounded-lg text-rose-500 hover:bg-rose-50 transition" title="Batalkan & Pulihkan Stok">
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
                            Belum ada transaksi pesanan laundry.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pesanans->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $pesanans->links() }}
        </div>
        @endif
    </div>

</div>

<script>
    function calculatePriceAndEstimate() {
        const layananSelect = document.getElementById('layanan_select');
        const beratInput = document.getElementById('berat_input');
        const tglMasukInput = document.getElementById('tgl_masuk_input');
        const tglSelesaiInput = document.getElementById('tgl_selesai_input');
        const previewTotal = document.getElementById('preview_total');

        if (layananSelect.selectedIndex <= 0) {
            previewTotal.innerText = 'Rp 0';
            return;
        }

        const selectedOption = layananSelect.options[layananSelect.selectedIndex];
        const price = parseFloat(selectedOption.getAttribute('data-price') || 0);
        const days = parseInt(selectedOption.getAttribute('data-days') || 1);
        const weight = parseFloat(beratInput.value || 0);

        const total = price * weight;
        previewTotal.innerText = 'Rp ' + total.toLocaleString('id-ID');

        // Auto calculate estimated completion date
        if (tglMasukInput.value) {
            const date = new Date(tglMasukInput.value);
            date.setDate(date.getDate() + days);
            tglSelesaiInput.value = date.toISOString().split('T')[0];
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        calculatePriceAndEstimate();
    });
</script>
@endsection
