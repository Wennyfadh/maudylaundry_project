<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran {{ $pembayaran->id }} - Maudy Laundry</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            color: #111;
            margin: 0;
            padding: 20px;
            background: #fff;
        }
        .receipt {
            max-width: 340px;
            margin: 0 auto;
            border: 1px dashed #ccc;
            padding: 16px;
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #444;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }
        .title {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
        }
        .subtitle {
            font-size: 11px;
            margin: 2px 0;
        }
        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .divider {
            border-top: 1px dashed #444;
            margin: 10px 0;
        }
        .bold {
            font-weight: bold;
        }
        .footer {
            text-align: center;
            font-size: 11px;
            margin-top: 15px;
            border-top: 1px dashed #444;
            padding-top: 10px;
        }
        .btn-print {
            display: block;
            margin: 15px auto 0;
            padding: 8px 16px;
            background: #0288d1;
            color: #fff;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-family: sans-serif;
            font-size: 12px;
        }
        @media print {
            .btn-print { display: none; }
            .receipt { border: none; padding: 0; }
        }
    </style>
</head>
<body>

    <div class="receipt">
        <div class="header">
            <h1 class="title">MAUDY LAUNDRY</h1>
            <p class="subtitle">Dusun Maju, Desa Durian, Kecamatan Rantau, Kabupaten Aceh Tamiang</p>
            <p class="subtitle">WhatsApp: 0812-0000-1111</p>
        </div>

        <div class="row">
            <span>No. Struk:</span>
            <span class="bold">{{ $pembayaran->id }}</span>
        </div>
        <div class="row">
            <span>No. Pesanan:</span>
            <span class="bold">{{ $pembayaran->pesanan_id }}</span>
        </div>
        <div class="row">
            <span>Tanggal:</span>
            <span>{{ \Carbon\Carbon::parse($pembayaran->tgl_bayar)->format('d/m/Y') }}</span>
        </div>
        <div class="row">
            <span>Pelanggan:</span>
            <span>{{ $pembayaran->pesanan?->pelanggan?->nama_lengkap ?? '-' }}</span>
        </div>
        <div class="row">
            <span>Kasir / Staff:</span>
            <span>{{ $pembayaran->pegawai?->nama_lengkap ?? Auth::user()->name }}</span>
        </div>

        <div class="divider"></div>

        <div class="row">
            <span>Layanan:</span>
            <span>{{ $pembayaran->pesanan?->layanan?->nama_layanan }}</span>
        </div>
        <div class="row">
            <span>Berat Cucian:</span>
            <span>{{ $pembayaran->pesanan?->berat_kg }} kg</span>
        </div>
        <div class="row">
            <span>Total Tagihan:</span>
            <span>Rp {{ number_format($pembayaran->pesanan?->total_harga, 0, ',', '.') }}</span>
        </div>

        <div class="divider"></div>

        <div class="row bold" style="font-size: 14px;">
            <span>JUMLAH DIBAYAR:</span>
            <span>Rp {{ number_format($pembayaran->jumlah_bayar, 0, ',', '.') }}</span>
        </div>
        <div class="row">
            <span>Metode Bayar:</span>
            <span>{{ $pembayaran->metode_bayar }}</span>
        </div>
        <div class="row bold">
            <span>Status:</span>
            <span>*** {{ strtoupper($pembayaran->status_bayar) }} ***</span>
        </div>

        <div class="footer">
            <p>Terima kasih atas kepercayaan Anda mencuci di Maudy Laundry!</p>
            <p>Pakaian bersih, wangi, dan rapi.</p>
        </div>

        <button onclick="window.print()" class="btn-print">🖨️ Cetak Struk</button>
    </div>

</body>
</html>
