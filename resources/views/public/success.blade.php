@extends('layouts.public')

@section('title', 'Pesanan Berhasil - ' . $pesanan->id)

@section('content')
<div class="max-w-xl mx-auto px-4 py-12">
    <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-xl text-center space-y-6">
        <!-- Success Icon -->
        <div class="w-20 h-20 bg-emerald-50 text-emerald-600 rounded-3xl flex items-center justify-center text-4xl mx-auto shadow-inner">
            ✓
        </div>

        <div>
            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 mb-2">
                Pesanan Berhasil Diterbitkan!
            </span>
            <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">Terima Kasih, {{ $pesanan->pelanggan?->nama_lengkap }}!</h1>
            <p class="text-xs text-slate-500 mt-1">Pesanan laundry Anda telah masuk ke sistem antrian Maudy Laundry.</p>
        </div>

        <!-- Order ID Highlight -->
        <div class="p-5 rounded-2xl bg-sky-50 border border-sky-100">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest block">KODE / ID RESI ANDA</span>
            <span class="text-3xl font-extrabold text-[#0288d1] font-mono tracking-wider block mt-1">
                {{ $pesanan->id }}
            </span>
            <p class="text-[11px] text-sky-700 mt-1">Simpan kode pesanan ini untuk melacak status laundry Anda kapan saja.</p>
        </div>

        <!-- Summary Details -->
        <div class="text-left text-xs bg-slate-50 rounded-2xl p-4 space-y-2 border border-slate-100">
            <div class="flex justify-between">
                <span class="text-slate-500">Layanan:</span>
                <span class="font-bold text-slate-800">{{ $pesanan->layanan?->nama_layanan }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Estimasi Berat:</span>
                <span class="font-bold text-slate-800">{{ $pesanan->berat_kg }} kg</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Estimasi Tagihan:</span>
                <span class="font-bold font-mono text-[#0288d1]">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Metode Pembayaran:</span>
                <span class="font-bold {{ $pesanan->metode_pembayaran === 'QRIS' ? 'text-sky-700' : 'text-emerald-700' }}">
                    {{ $pesanan->metode_pembayaran === 'QRIS' ? '📱 QRIS (Scan Online)' : '💵 COD (Bayar di Tempat)' }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Status Pembayaran:</span>
                <span class="font-bold {{ $pesanan->isLunas() ? 'text-emerald-700' : 'text-amber-700' }}">
                    {{ $pesanan->isLunas() ? '✓ Lunas' : 'Belum Lunas' }}
                </span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Jadwal Penjemputan:</span>
                <span class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($pesanan->tgl_masuk)->translatedFormat('d F Y') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-slate-500">Alamat Penjemputan:</span>
                <span class="font-semibold text-slate-800 text-right max-w-xs">{{ $pesanan->pelanggan?->alamat }}</span>
            </div>
        </div>

        @if($pesanan->metode_pembayaran === 'QRIS')
        <!-- KARTU PEMBAYARAN QRIS RESMI -->
        <div class="p-6 rounded-3xl bg-white border-2 border-slate-200 shadow-sm text-center space-y-4">
            <!-- QRIS Brand Header -->
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="text-left">
                    <span class="text-[10px] font-extrabold tracking-widest text-rose-600 uppercase block">QRIS STANDAR NASIONAL</span>
                    <span class="text-sm font-extrabold text-slate-900 block">MAUDY LAUNDRY</span>
                </div>
                <div class="text-right">
                    <span class="text-[9px] text-slate-400 font-mono block">NMID: ID102026889901</span>
                    <span class="text-[9px] text-slate-400 font-mono block">A01 - ACEH TAMIANG</span>
                </div>
            </div>

            <!-- QR Code Scanable Image -->
            <div class="relative inline-block p-4 bg-white rounded-2xl border border-slate-200 shadow-inner">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=00020101021226670016ID.CO.MAUDYLAUNDRY.WWW01189360091100000000000215ID1020268899015204581253033605405{{ $pesanan->total_harga }}5802ID5913MAUDY%20LAUNDRY6011ACEHTAMIANG62070103{{ $pesanan->id }}6304"
                     alt="QRIS QR Code Maudy Laundry"
                     class="w-52 h-52 mx-auto rounded-lg">
                <span class="block text-[10px] text-slate-400 font-mono mt-2">Pindai dengan Aplikasi E-Wallet / Mobile Banking</span>
            </div>

            <!-- Tagihan Nominal -->
            <div class="bg-sky-50 rounded-2xl p-3 border border-sky-100">
                <span class="text-[11px] text-slate-500 block">Total Nominal yang Harus Dibayar:</span>
                <span class="text-2xl font-black text-[#0288d1] font-mono block mt-0.5">
                    Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                </span>
            </div>

            <!-- Supported Apps Badges -->
            <div class="flex flex-wrap items-center justify-center gap-1.5 pt-1 text-[10px] text-slate-500 font-semibold">
                <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200">GoPay</span>
                <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200">OVO</span>
                <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200">DANA</span>
                <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200">ShopeePay</span>
                <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200">BCA</span>
                <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200">BRI</span>
                <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200">Mandiri</span>
                <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200">BNI</span>
            </div>

            <!-- Tombol Konfirmasi Pembayaran QRIS -->
            @if(!$pesanan->isLunas())
            <form action="{{ route('public.qris.confirm', $pesanan->id) }}" method="POST" class="pt-2">
                @csrf
                <button type="submit" 
                        class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center justify-center gap-2">
                    <span>✓ Saya Sudah Scan & Bayar via QRIS</span>
                </button>
            </form>
            @else
            <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-center gap-2">
                <span>✓ Pembayaran QRIS Telah Berhasil Dikonfirmasi (Lunas)</span>
            </div>
            @endif
        </div>
        @else
        <!-- KARTU INFORMASI PEMBAYARAN COD -->
        <div class="p-5 rounded-2xl bg-amber-50/70 border border-amber-200 text-left space-y-2">
            <div class="flex items-center gap-2 text-amber-900 font-bold text-xs">
                <span class="text-base">💵</span> Instruksi Pembayaran COD (Bayar di Tempat)
            </div>
            <p class="text-xs text-amber-800 leading-relaxed">
                Silakan siapkan uang tunai pas sebesar <strong class="font-mono text-slate-900">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</strong> saat staf kurir Maudy Laundry tiba untuk menjemput cucian di alamat Anda.
            </p>
        </div>
        @endif

        <div class="pt-2 flex flex-col sm:flex-row gap-3">
            <a href="{{ route('public.tracking', ['code' => $pesanan->id]) }}" 
               class="flex-1 py-3 bg-[#0288d1] hover:bg-[#0277bd] text-white font-bold text-xs rounded-xl shadow-md shadow-sky-500/20 transition">
                🔍 Pantau Status Laundry
            </a>
            <a href="{{ route('home') }}" 
               class="px-5 py-3 border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs rounded-xl transition">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
