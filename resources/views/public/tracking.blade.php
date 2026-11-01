@extends('layouts.public')

@section('title', 'Cek Status Resi Laundry')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12 space-y-8">

    <!-- Search Header -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-sm text-center">
        <div class="w-12 h-12 rounded-2xl bg-sky-50 text-[#0288d1] flex items-center justify-center text-2xl mx-auto mb-3 shadow-inner">
            🔍
        </div>
        <h1 class="text-xl font-bold text-slate-800">Cek Status & Resi Laundry</h1>
        <p class="text-xs text-slate-500 mt-1">Pantau progress pencucian pakaian Anda secara langsung</p>

        <form action="{{ route('public.tracking') }}" method="GET" class="mt-6 flex flex-col sm:flex-row gap-2 max-w-lg mx-auto">
            <input type="text" name="code" value="{{ $code }}" required placeholder="Masukkan ID Pesanan (contoh: PS001)"
                   class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-mono uppercase tracking-wider focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1]">
            <button type="submit" class="px-6 py-2.5 bg-[#0288d1] hover:bg-[#0277bd] text-white font-bold text-xs rounded-xl shadow-md shadow-sky-500/20 transition">
                Cari Resi
            </button>
        </form>
    </div>

    @if(session('success'))
    <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center gap-3 shadow-sm">
        <span class="text-xl">✓</span>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if($pesanan)
    <!-- Order Found: Tracking Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-100 shadow-xl space-y-8">
        
        <!-- Header Info -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-6 border-b border-slate-100 gap-4">
            <div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">ID PESANAN</span>
                <h2 class="text-2xl font-extrabold text-[#0288d1] font-mono mt-0.5">#{{ $pesanan->id }}</h2>
                <p class="text-xs text-slate-600 mt-1">Pelanggan: <strong>{{ $pesanan->pelanggan?->nama_lengkap }}</strong></p>
            </div>
            <div class="text-left sm:text-right">
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">STATUS TERKINI</span>
                @if($pesanan->status_pesanan === 'Selesai')
                <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                    ✓ Selesai / Siap Diambil
                </span>
                @elseif($pesanan->status_pesanan === 'Diproses')
                <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 border border-amber-200">
                    ⏳ Sedang Diproses
                </span>
                @else
                <span class="inline-block mt-1 px-3 py-1 rounded-full text-xs font-bold bg-cyan-100 text-cyan-800 border border-cyan-200">
                    📥 Menunggu Verifikasi
                </span>
                @endif
            </div>
        </div>

        <!-- PROGRESS TIMELINE STEPPER -->
        <div>
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-6 text-center">Progress Pengerjaan</h3>
            
            <div class="relative flex items-center justify-between max-w-xl mx-auto px-4">
                <!-- Line Background -->
                <div class="absolute left-10 right-10 top-1/2 -translate-y-1/2 h-1 bg-slate-200 z-0"></div>
                <!-- Line Active -->
                <div class="absolute left-10 top-1/2 -translate-y-1/2 h-1 bg-[#0288d1] transition-all z-0"
                     style="width: {{ $pesanan->status_pesanan === 'Selesai' ? '80%' : ($pesanan->status_pesanan === 'Diproses' ? '40%' : '0%') }};"></div>

                <!-- Step 1: Menunggu -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shadow-md {{ in_array($pesanan->status_pesanan, ['Menunggu', 'Diproses', 'Selesai']) ? 'bg-[#0288d1] text-white' : 'bg-slate-200 text-slate-500' }}">
                        1
                    </div>
                    <span class="text-[11px] font-semibold text-slate-700 mt-2 text-center">Menunggu</span>
                    <span class="text-[9px] text-slate-400">Verifikasi</span>
                </div>

                <!-- Step 2: Diproses -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shadow-md {{ in_array($pesanan->status_pesanan, ['Diproses', 'Selesai']) ? 'bg-[#0288d1] text-white' : 'bg-slate-200 text-slate-500' }}">
                        2
                    </div>
                    <span class="text-[11px] font-semibold text-slate-700 mt-2 text-center">Diproses</span>
                    <span class="text-[9px] text-slate-400">Cuci & Setrika</span>
                </div>

                <!-- Step 3: Selesai -->
                <div class="relative z-10 flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shadow-md {{ $pesanan->status_pesanan === 'Selesai' ? 'bg-emerald-500 text-white' : 'bg-slate-200 text-slate-500' }}">
                        3
                    </div>
                    <span class="text-[11px] font-semibold text-slate-700 mt-2 text-center">Selesai</span>
                    <span class="text-[9px] text-slate-400">Siap Diambil</span>
                </div>
            </div>
        </div>

        <!-- Detail Breakdown -->
        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
            <div>
                <span class="text-slate-400 block text-[11px]">Jenis Layanan:</span>
                <span class="font-bold text-slate-800 text-sm">{{ $pesanan->layanan?->nama_layanan }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[11px]">Berat Cucian:</span>
                <span class="font-bold text-slate-800 text-sm font-mono">{{ $pesanan->berat_kg }} kg</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[11px]">Tanggal Masuk:</span>
                <span class="font-medium text-slate-700">{{ \Carbon\Carbon::parse($pesanan->tgl_masuk)->translatedFormat('d F Y') }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[11px]">Estimasi Selesai:</span>
                <span class="font-medium text-slate-700">{{ $pesanan->tgl_selesai_estimasi ? \Carbon\Carbon::parse($pesanan->tgl_selesai_estimasi)->translatedFormat('d F Y') : '-' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[11px]">Staf Penangan:</span>
                <span class="font-medium text-slate-700">{{ $pesanan->pegawai?->nama_lengkap ?? 'Tim Maudy Laundry' }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[11px]">Metode Pembayaran:</span>
                <span class="font-bold {{ $pesanan->metode_pembayaran === 'QRIS' ? 'text-sky-700' : 'text-emerald-700' }}">
                    {{ $pesanan->metode_pembayaran === 'QRIS' ? '📱 QRIS (Scan Online)' : '💵 COD (Bayar di Tempat)' }}
                </span>
            </div>
            <div>
                <span class="text-slate-400 block text-[11px]">Status Pembayaran:</span>
                <span class="font-bold {{ $pesanan->isLunas() ? 'text-emerald-700' : 'text-amber-700' }}">
                    {{ $pesanan->isLunas() ? '✓ Lunas' : 'Belum Lunas' }}
                </span>
            </div>
        </div>

        <!-- Tagihan Total -->
        <div class="flex items-center justify-between p-4 rounded-xl bg-sky-50 border border-sky-100">
            <div>
                <span class="text-xs text-sky-800 font-semibold block">Total Biaya Laundry</span>
                <span class="text-[11px] text-sky-600">Tarif resmi berdasarkan berat cucian</span>
            </div>
            <span class="text-xl font-extrabold text-[#0288d1] font-mono">
                Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
            </span>
        </div>

        <!-- AREA PEMBAYARAN ONLINE QRIS (JIKA BELUM LUNAS) -->
        @if(!$pesanan->isLunas())
        <div class="p-6 rounded-3xl bg-slate-50 border-2 border-slate-200 text-center space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-3 border-b border-slate-200 gap-2">
                <div class="text-left">
                    <span class="text-[10px] font-extrabold tracking-widest text-rose-600 uppercase block">QRIS STANDAR NASIONAL</span>
                    <span class="text-sm font-extrabold text-slate-900 block">MAUDY LAUNDRY</span>
                </div>
                <div class="text-left sm:text-right">
                    <span class="text-[10px] text-slate-500 font-mono">NMID: ID102026889901</span>
                </div>
            </div>

            <div class="bg-white p-4 rounded-2xl border border-slate-200 inline-block shadow-sm">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=00020101021226670016ID.CO.MAUDYLAUNDRY.WWW01189360091100000000000215ID1020268899015204581253033605405{{ $pesanan->total_harga }}5802ID5913MAUDY%20LAUNDRY6011ACEHTAMIANG62070103{{ $pesanan->id }}6304"
                     alt="Scan QRIS Maudy Laundry"
                     class="w-48 h-48 mx-auto rounded-lg">
                <span class="block text-[10px] text-slate-500 font-mono mt-2">Pindai dengan GoPay / OVO / Dana / ShopeePay / BCA</span>
            </div>

            <div class="bg-white p-3 rounded-xl border border-slate-200 max-w-sm mx-auto">
                <span class="text-[11px] text-slate-500 block">Nominal Tagihan:</span>
                <span class="text-xl font-black text-[#0288d1] font-mono block">
                    Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}
                </span>
            </div>

            <form action="{{ route('public.qris.confirm', $pesanan->id) }}" method="POST" class="max-w-sm mx-auto">
                @csrf
                <button type="submit" 
                        class="w-full py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-600/20 transition flex items-center justify-center gap-2">
                    <span>✓ Saya Sudah Scan & Bayar via QRIS</span>
                </button>
            </form>

            @if($pesanan->metode_pembayaran === 'COD')
            <p class="text-[11px] text-slate-500 mt-1">
                Atau Anda juga dapat membayar tunai (COD) langsung kepada kurir saat cucian dijemput/diantar.
            </p>
            @endif
        </div>
        @else
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold flex items-center justify-center gap-2">
            <span>✓ Pembayaran untuk pesanan ini telah LUNAS</span>
        </div>
        @endif

    </div>
    @elseif($code)
    <!-- Order Not Found -->
    <div class="bg-white rounded-3xl p-8 border border-rose-100 shadow-md text-center space-y-3">
        <div class="w-12 h-12 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center text-2xl mx-auto">
            ✕
        </div>
        <h3 class="text-base font-bold text-slate-800">Pesanan Tidak Ditemukan</h3>
        <p class="text-xs text-slate-500 max-w-md mx-auto">
            Tidak ada pesanan dengan kode <strong>{{ $code }}</strong>. Mohon periksa kembali kode resi Anda atau hubungi layanan pelanggan kami.
        </p>
    </div>
    @endif

</div>
@endsection
