@extends('layouts.public')

@section('title', 'Layanan Laundry Bersih, Cepat & Terpercaya')

@push('styles')
<style>
    .hero-bubble {
        position: absolute;
        border-radius: 50%;
        background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.98) 0%, rgba(255, 255, 255, 0.45) 25%, rgba(186, 230, 253, 0.35) 60%, rgba(244, 114, 182, 0.28) 85%, rgba(255, 255, 255, 0.8) 100%);
        border: 1.5px solid rgba(255, 255, 255, 0.9);
        box-shadow: inset 0 0 10px rgba(255, 255, 255, 0.95), 0 0 16px rgba(255, 255, 255, 0.5);
        pointer-events: auto !important;
        cursor: pointer !important;
        user-select: none;
        touch-action: manipulation;
        z-index: 50 !important;
        transition: transform 0.15s ease, filter 0.15s ease;
    }
    .hero-bubble::before {
        content: '';
        position: absolute;
        top: 14%;
        left: 18%;
        width: 28%;
        height: 20%;
        border-radius: 50%;
        background: radial-gradient(ellipse at center, rgba(255, 255, 255, 0.98) 0%, rgba(255, 255, 255, 0.5) 60%, transparent 100%);
        transform: rotate(-35deg);
    }
    .hero-bubble:hover {
        transform: scale(1.22) !important;
        filter: brightness(1.25) drop-shadow(0 0 10px rgba(255, 255, 255, 0.9));
    }
    .hero-bubble:active {
        transform: scale(0.9) !important;
    }
    .hero-bubble-1 {
        width: 38px; height: 38px;
        top: -15px; left: 15px;
        animation: heroBubbleFloat1 4s infinite ease-in-out;
    }
    .hero-bubble-2 {
        width: 26px; height: 26px;
        top: 25px; right: -10px;
        animation: heroBubbleFloat2 3.5s infinite ease-in-out 1s;
    }
    .hero-bubble-3 {
        width: 48px; height: 48px;
        bottom: 20px; left: -20px;
        animation: heroBubbleFloat1 5s infinite ease-in-out 1.5s;
    }
    .hero-bubble-4 {
        width: 22px; height: 22px;
        top: -30px; right: 40px;
        animation: heroBubbleFloat2 4.2s infinite ease-in-out 0.5s;
    }
    .hero-bubble-5 {
        width: 32px; height: 32px;
        bottom: -15px; right: 20px;
        animation: heroBubbleFloat1 4.8s infinite ease-in-out 2s;
    }

    @keyframes heroBubbleFloat1 {
        0%, 100% { transform: translateY(0) translateX(0) scale(1); }
        50% { transform: translateY(-25px) translateX(12px) scale(1.1); }
    }
    @keyframes heroBubbleFloat2 {
        0%, 100% { transform: translateY(0) translateX(0) scale(1); }
        50% { transform: translateY(-30px) translateX(-14px) scale(1.15); }
    }
</style>
@endpush

@section('content')
<div class="space-y-16 py-6" id="beranda">

    <!-- HERO BANNER -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-[#0288d1] via-[#0277bd] to-[#01579b] rounded-3xl p-8 sm:p-12 lg:p-16 text-white shadow-xl shadow-sky-600/15 relative overflow-hidden flex flex-col lg:flex-row items-center justify-between gap-10">
            <div class="max-w-xl z-10">
                <span class="inline-block px-3.5 py-1 rounded-full bg-white/20 backdrop-blur text-xs font-semibold text-sky-100 mb-4">
                    ✨ Solusi Laundry Paling Bersih & Wangi di Aceh Tamiang
                </span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
                    Pakaian Bersih, Segar, & Higienis Tanpa Ribet
                </h1>
                <p class="mt-4 text-sky-100 text-sm sm:text-base leading-relaxed">
                    Pesan laundry mandiri langsung melalui website. Layanan antar-jemput cepat, deterjen premium, dan pemantauan status cucian real-time.
                </p>
                <div class="mt-8 flex flex-wrap items-center gap-4">
                    <a href="#pesan" class="px-6 py-3 bg-white text-[#0288d1] hover:bg-sky-50 font-bold rounded-xl shadow-lg transition text-sm">
                        🧺 Pesan Laundry Online
                    </a>
                    <a href="#lacak" class="px-6 py-3 bg-white/15 hover:bg-white/25 text-white font-semibold rounded-xl backdrop-blur transition text-sm">
                        🔍 Cek Resi Pesanan
                    </a>
                </div>
            </div>

            <!-- Visual Mascot / Basket Graphic with Floating Soap Bubbles -->
            <div class="relative z-10 flex-shrink-0 text-center">
                <div class="w-48 h-48 sm:w-60 sm:h-60 bg-white/15 backdrop-blur-md rounded-3xl border border-white/20 flex flex-col items-center justify-center p-6 shadow-2xl relative">
                    <span class="text-7xl sm:text-8xl select-none animate-bounce" style="animation-duration: 3s;">🧺</span>
                    <span class="mt-3 text-xs font-bold tracking-widest text-sky-100 uppercase">Maudy Laundry</span>
                </div>

                <!-- Floating Sparkling Laundry Foam Bubbles (Interaktif - Tekan untuk meletuskan) -->
                <div class="hero-bubble hero-bubble-1" title="Tekan untuk meletuskan gelembung!"></div>
                <div class="hero-bubble hero-bubble-2" title="Tekan untuk meletuskan gelembung!"></div>
                <div class="hero-bubble hero-bubble-3" title="Tekan untuk meletuskan gelembung!"></div>
                <div class="hero-bubble hero-bubble-4" title="Tekan untuk meletuskan gelembung!"></div>
                <div class="hero-bubble hero-bubble-5" title="Tekan untuk meletuskan gelembung!"></div>
            </div>

            <!-- Background blobs -->
            <div class="absolute -bottom-16 -right-16 w-80 h-80 bg-sky-400/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -top-16 -left-16 w-80 h-80 bg-blue-300/20 rounded-full blur-3xl pointer-events-none"></div>
        </div>
    </section>

    <!-- QUICK TRACKING WIDGET (CEK RESI INSTAN) -->
    <section class="max-w-4xl mx-auto px-4 sm:px-6" id="lacak">
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-xl border border-slate-100 -mt-8 relative z-20">
            <div class="text-center max-w-md mx-auto mb-6">
                <span class="w-10 h-10 rounded-2xl bg-sky-50 text-[#0288d1] inline-flex items-center justify-center text-xl mb-2">🔍</span>
                <h2 class="text-lg font-bold text-slate-800">Lacak Status Laundry Anda</h2>
                <p class="text-xs text-slate-500">Masukkan ID Pesanan untuk memantau progress pengerjaan laundry secara real-time</p>
            </div>

            <form action="{{ route('public.tracking') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
                <div class="relative flex-1">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 font-mono text-sm font-bold">
                        #
                    </span>
                    <input type="text" name="code" required placeholder="Masukkan ID Pesanan (contoh: PS001, PS008...)"
                           class="w-full pl-8 pr-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] uppercase font-mono tracking-wider">
                </div>
                <button type="submit" class="px-8 py-3 bg-[#0288d1] hover:bg-[#0277bd] text-white font-bold text-sm rounded-xl shadow-md shadow-sky-500/20 transition whitespace-nowrap">
                    Cek Status
                </button>
            </form>
        </div>
    </section>

    <!-- DAFTAR LAYANAN & TARIF -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6" id="layanan">
        <div class="text-center max-w-xl mx-auto mb-10">
            <span class="text-xs font-bold text-[#0288d1] uppercase tracking-wider">PILIHAN PAKET</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 tracking-tight mt-1">Daftar Layanan & Tarif</h2>
            <p class="text-xs sm:text-sm text-slate-500 mt-2">Tarif terjangkau dengan jaminan cucian bersih wangi dan higienis</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($layanans as $layanan)
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-md transition flex flex-col justify-between">
                <div>
                    <div class="w-12 h-12 rounded-2xl bg-sky-50 text-[#0288d1] flex items-center justify-center text-2xl mb-4 shadow-inner">
                        ✨
                    </div>
                    <h3 class="text-base font-bold text-slate-800">{{ $layanan->nama_layanan }}</h3>
                    <p class="text-xs text-slate-500 mt-1">Estimasi: <strong>{{ $layanan->estimasi_waktu_hari }} Hari</strong></p>
                    <div class="mt-2 flex items-center gap-1.5">
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                            ⚖️ Maks. {{ $layanan->maksimum_berat }} kg / order
                        </span>
                    </div>
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <span class="text-2xl font-extrabold text-[#0288d1] font-mono">Rp {{ number_format($layanan->harga_per_kg, 0, ',', '.') }}</span>
                        <span class="text-xs text-slate-400 font-medium">/ kg</span>
                    </div>
                </div>

                <a href="#pesan" onclick="selectService('{{ $layanan->id }}')" 
                   class="mt-6 w-full py-2.5 rounded-xl border border-sky-200 text-sky-600 hover:bg-sky-50 font-semibold text-xs text-center transition block">
                    Pilih Layanan Ini
                </a>
            </div>
            @endforeach
        </div>
    </section>

    <!-- FORM PEMESANAN ONLINE MANDIRI (PDF 1 & 2 Requirement) -->
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-8" id="pesan">
        <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-100 shadow-xl relative overflow-hidden">
            <div class="mb-8 pb-4 border-b border-slate-100">
                <span class="text-xs font-bold text-[#0288d1] uppercase tracking-wider">FORMULIR PESANAN</span>
                <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight mt-1">Pemesanan Online Mandiri</h2>
                <p class="text-xs text-slate-500 mt-1">Isi formulir di bawah ini. Tim kami akan menjemput cucian ke lokasi Anda.</p>
            </div>

            @if ($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800">
                <div class="flex items-start gap-3">
                    <span class="text-xl">⚠️</span>
                    <div>
                        <h4 class="text-xs font-bold text-rose-900 uppercase tracking-wider">Perhatian / Formulir Belum Sesuai</h4>
                        <ul class="mt-1 list-disc list-inside text-xs space-y-0.5">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('public.order.store') }}" method="POST" class="space-y-5" id="pub_order_form">
                @csrf

                <!-- WARNING BATAS MAKSIMAL LAYANAN (DITAMPILKAN REAL-TIME OLEH JS) -->
                <div id="weight_warning_box" class="hidden p-4 rounded-2xl bg-amber-50 border-2 border-amber-300 text-amber-900 transition-all duration-300">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-xl bg-amber-200 text-amber-800 flex items-center justify-center font-bold text-base flex-shrink-0">
                            ⚠️
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xs font-bold text-amber-950 uppercase tracking-wider">Peringatan: Berat Melebihi Batas Maksimal!</h4>
                            <p id="weight_warning_msg" class="text-xs text-amber-900 mt-0.5 leading-relaxed font-semibold"></p>
                            <p class="text-[11px] text-amber-700 mt-1">
                                Demi menjaga kualitas cuci dan kebersihan optimal, kapasitas mesin kami dibatasi. Silakan kurangi berat estimasi atau buat pesanan terpisah.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap Anda *</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                               placeholder="Nama lengkap"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp / HP *</label>
                        <input type="text" name="no_telp" value="{{ old('no_telp') }}" required
                               placeholder="08xxxxxxxxxx"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1]">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Penjemputan Lengkap *</label>
                    <textarea name="alamat" required rows="2" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, patokan lokasi..."
                              class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1]">{{ old('alamat') }}</textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Layanan *</label>
                        <select name="jenis_layanan_id" id="pub_layanan_select" required onchange="calculatePubTotal()"
                                class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1]">
                            <option value="">-- Pilih Layanan --</option>
                            @foreach($layanans as $layanan)
                            <option value="{{ $layanan->id }}" 
                                    data-price="{{ $layanan->harga_per_kg }}" 
                                    data-max="{{ $layanan->maksimum_berat }}"
                                    data-name="{{ $layanan->nama_layanan }}"
                                    {{ old('jenis_layanan_id') == $layanan->id ? 'selected' : '' }}>
                                {{ $layanan->nama_layanan }} (Rp {{ number_format($layanan->harga_per_kg, 0, ',', '.') }}/kg - Maks. {{ $layanan->maksimum_berat }} kg)
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="block text-xs font-semibold text-slate-700">Estimasi Berat (kg) *</label>
                            <span id="pub_max_badge" class="text-[10px] font-bold text-slate-400">Maks: -</span>
                        </div>
                        <input type="number" id="pub_berat_input" name="estimasi_berat_kg" value="{{ old('estimasi_berat_kg', '3.0') }}" required min="0.5" step="0.5" oninput="calculatePubTotal()"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1]">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Penjemputan *</label>
                        <input type="date" name="tgl_penjemputan" value="{{ old('tgl_penjemputan', now()->toDateString()) }}" required
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1]">
                    </div>
                </div>

                <!-- PILIHAN METODE PEMBAYARAN: QRIS ATAU COD -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Metode Pembayaran *</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <!-- COD (Bayar di Tempat) -->
                        <label class="relative flex items-start gap-3 p-3.5 rounded-2xl border-2 cursor-pointer transition select-none bg-white hover:border-sky-300 has-[:checked]:border-[#0288d1] has-[:checked]:bg-sky-50/50">
                            <input type="radio" name="metode_pembayaran" value="COD" {{ old('metode_pembayaran', 'COD') === 'COD' ? 'checked' : '' }} class="mt-1 text-[#0288d1] focus:ring-[#0288d1]">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                        <span>💵</span> COD (Bayar di Tempat)
                                    </span>
                                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                        Tunai
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1">Bayar tunai kepada staf kurir saat cucian dijemput atau diantar ke lokasi Anda.</p>
                            </div>
                        </label>

                        <!-- QRIS (Online Scan) -->
                        <label class="relative flex items-start gap-3 p-3.5 rounded-2xl border-2 cursor-pointer transition select-none bg-white hover:border-sky-300 has-[:checked]:border-[#0288d1] has-[:checked]:bg-sky-50/50">
                            <input type="radio" name="metode_pembayaran" value="QRIS" {{ old('metode_pembayaran') === 'QRIS' ? 'checked' : '' }} class="mt-1 text-[#0288d1] focus:ring-[#0288d1]">
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                        <span>📱</span> QRIS (Scan Online)
                                    </span>
                                    <span class="text-[10px] font-bold text-sky-700 bg-sky-50 px-2 py-0.5 rounded-full border border-sky-200">
                                        Scan Instan
                                    </span>
                                </div>
                                <p class="text-[11px] text-slate-500 mt-1">Scan kode QR resmi Maudy Laundry via GoPay, OVO, Dana, ShopeePay, BCA Mobile, dll.</p>
                            </div>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Khusus (Opsional)</label>
                    <input type="text" name="catatan" value="{{ old('catatan') }}" placeholder="Contoh: Pakaian putih harap dipisah, jangan pewangi terlalu menyengat..."
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-xs focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1]">
                </div>

                <!-- Live Estimasi Harga -->
                <div class="p-4 rounded-2xl bg-sky-50 border border-sky-100 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-sky-900 block">Estimasi Tagihan Sementara</span>
                        <span class="text-[11px] text-sky-600">Berat aktual akan ditimbang ulang di tempat saat penjemputan</span>
                    </div>
                    <div class="text-right">
                        <span id="pub_total_preview" class="text-2xl font-extrabold text-[#0288d1] font-mono">Rp 0</span>
                    </div>
                </div>

                <button type="submit" id="pub_submit_btn"
                        class="w-full py-3.5 bg-[#0288d1] hover:bg-[#0277bd] text-white font-bold text-sm rounded-xl shadow-lg shadow-sky-500/25 transition active:scale-[0.99] flex items-center justify-center gap-2">
                    <span id="pub_submit_text">🚀 Kirim Pesanan Laundry Sekarang</span>
                </button>
            </form>
        </div>
    </section>

</div>

<script>
    function selectService(id) {
        const select = document.getElementById('pub_layanan_select');
        select.value = id;
        calculatePubTotal();
    }

    function calculatePubTotal() {
        const select = document.getElementById('pub_layanan_select');
        const beratInput = document.getElementById('pub_berat_input');
        const berat = parseFloat(beratInput.value || 0);
        const preview = document.getElementById('pub_total_preview');
        const maxBadge = document.getElementById('pub_max_badge');
        const warningBox = document.getElementById('weight_warning_box');
        const warningMsg = document.getElementById('weight_warning_msg');
        const submitBtn = document.getElementById('pub_submit_btn');
        const submitText = document.getElementById('pub_submit_text');

        if (select.selectedIndex <= 0) {
            preview.innerText = 'Rp 0';
            maxBadge.innerText = 'Maks: -';
            maxBadge.className = 'text-[10px] font-bold text-slate-400';
            warningBox.classList.add('hidden');
            beratInput.classList.remove('border-red-400', 'bg-red-50', 'text-red-900');
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-slate-400', 'cursor-not-allowed', 'hover:bg-slate-400');
            submitBtn.classList.add('bg-[#0288d1]', 'hover:bg-[#0277bd]');
            submitText.innerText = '🚀 Kirim Pesanan Laundry Sekarang';
            return;
        }

        const selectedOpt = select.options[select.selectedIndex];
        const price = parseFloat(selectedOpt.getAttribute('data-price') || 0);
        const maxLimit = parseFloat(selectedOpt.getAttribute('data-max') || 20);
        const serviceName = selectedOpt.getAttribute('data-name') || 'Layanan ini';

        maxBadge.innerText = 'Maks: ' + maxLimit + ' kg';

        const total = price * berat;
        preview.innerText = 'Rp ' + total.toLocaleString('id-ID');

        // Validasi real-time batas maksimal berat
        if (berat > maxLimit) {
            maxBadge.className = 'text-[10px] font-bold text-red-600 animate-pulse';
            warningBox.classList.remove('hidden');
            warningMsg.innerText = `Layanan "${serviceName}" dibatasi maksimal ${maxLimit} kg per pesanan. Estimasi berat cucian Anda saat ini ${berat} kg (kelebihan ${(berat - maxLimit).toFixed(1)} kg).`;
            beratInput.classList.add('border-red-400', 'bg-red-50', 'text-red-900');
            
            // Nonaktifkan tombol kirim jika melebihi batas
            submitBtn.disabled = true;
            submitBtn.classList.remove('bg-[#0288d1]', 'hover:bg-[#0277bd]');
            submitBtn.classList.add('bg-slate-400', 'cursor-not-allowed', 'hover:bg-slate-400');
            submitText.innerText = `⚠️ Berat Melebihi Batas Maksimal (${maxLimit} kg)`;
        } else {
            maxBadge.className = 'text-[10px] font-bold text-emerald-600';
            warningBox.classList.add('hidden');
            beratInput.classList.remove('border-red-400', 'bg-red-50', 'text-red-900');
            
            submitBtn.disabled = false;
            submitBtn.classList.remove('bg-slate-400', 'cursor-not-allowed', 'hover:bg-slate-400');
            submitBtn.classList.add('bg-[#0288d1]', 'hover:bg-[#0277bd]');
            submitText.innerText = '🚀 Kirim Pesanan Laundry Sekarang';
        }
    }

    document.addEventListener('DOMContentLoaded', calculatePubTotal);
</script>
@endsection
