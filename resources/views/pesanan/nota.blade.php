<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Laundry {{ $pesanan->id }} - Maudy Laundry</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #222;
            margin: 0;
            padding: 30px;
            background: #f8fafc;
        }
        .invoice-card {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #0288d1;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .brand h1 {
            font-size: 20px;
            color: #0288d1;
            margin: 0 0 4px;
        }
        .brand p {
            font-size: 11px;
            color: #64748b;
            margin: 0;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h2 {
            font-size: 18px;
            margin: 0 0 4px;
            color: #1e293b;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-success { background: #dcfce7; color: #166534; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-info { background: #e0f2fe; color: #0369a1; }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 25px;
        }
        .info-box h4 {
            font-size: 11px;
            text-transform: uppercase;
            color: #94a3b8;
            margin: 0 0 6px;
        }
        .info-box p {
            margin: 2px 0;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px 12px;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
        }
        th {
            background: #f8fafc;
            color: #475569;
            font-size: 11px;
            text-transform: uppercase;
        }
        .total-box {
            text-align: right;
            padding: 12px;
            background: #f8fafc;
            border-radius: 8px;
            margin-bottom: 25px;
        }
        .total-box .grand-total {
            font-size: 18px;
            font-weight: bold;
            color: #0288d1;
        }
        .terms {
            font-size: 11px;
            color: #64748b;
            border-top: 1px dashed #cbd5e1;
            padding-top: 15px;
        }
        .btn-print {
            display: inline-block;
            padding: 8px 18px;
            background: #0288d1;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 15px;
            text-decoration: none;
        }
        @media print {
            body { background: #fff; padding: 0; }
            .invoice-card { border: none; box-shadow: none; padding: 0; }
            .btn-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="invoice-card">
        <div class="header">
            <div class="brand">
                <h1>🧺 MAUDY LAUNDRY</h1>
                <p>Sistem Informasi Manajemen Laundry Terpadu</p>
                <p>Dusun Maju, Desa Durian, Kecamatan Rantau, Kabupaten Aceh Tamiang • Telp/WA: 0812-0000-1111</p>
            </div>
            <div class="invoice-title">
                <h2>NOTA PESANAN</h2>
                <p style="font-family: monospace; font-size: 14px; font-weight: bold; color: #0288d1;">#{{ $pesanan->id }}</p>
                @if($pesanan->status_pesanan === 'Selesai')
                    <span class="badge badge-success">SELESAI</span>
                @elseif($pesanan->status_pesanan === 'Diproses')
                    <span class="badge badge-warning">DIPROSES</span>
                @else
                    <span class="badge badge-info">MENUNGGU</span>
                @endif
            </div>
        </div>

        <div class="info-grid">
            <div class="info-box">
                <h4>Data Pelanggan</h4>
                <p><strong>{{ $pesanan->pelanggan?->nama_lengkap }}</strong></p>
                <p>{{ $pesanan->pelanggan?->no_telp }}</p>
                <p>{{ $pesanan->pelanggan?->alamat ?? '-' }}</p>
            </div>
            <div class="info-box">
                <h4>Informasi Transaksi</h4>
                <p>Tgl Masuk: <strong>{{ \Carbon\Carbon::parse($pesanan->tgl_masuk)->translatedFormat('d F Y') }}</strong></p>
                <p>Estimasi Selesai: <strong>{{ $pesanan->tgl_selesai_estimasi ? \Carbon\Carbon::parse($pesanan->tgl_selesai_estimasi)->translatedFormat('d F Y') : '-' }}</strong></p>
                <p>Petugas: <strong>{{ $pesanan->pegawai?->nama_lengkap ?? 'Kasir Utama' }}</strong></p>
                <p>Sumber: <strong>{{ strtoupper($pesanan->sumber_order) }}</strong></p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Item Layanan</th>
                    <th style="text-align: center;">Berat / Qty</th>
                    <th style="text-align: right;">Tarif/Kg</th>
                    <th style="text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>{{ $pesanan->layanan?->nama_layanan }}</strong>
                        @if($pesanan->catatan)
                        <div style="font-size: 11px; color: #64748b; margin-top: 3px;">Catatan: {{ $pesanan->catatan }}</div>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $pesanan->berat_kg }} kg</td>
                    <td style="text-align: right;">Rp {{ number_format($pesanan->layanan?->harga_per_kg, 0, ',', '.') }}</td>
                    <td style="text-align: right; font-weight: bold;">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total-box">
            <div>TOTAL TAGIHAN</div>
            <div class="grand-total">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</div>
            <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                Status Pembayaran: 
                <strong>{{ $pesanan->isLunas() ? 'LUNAS' : 'BELUM LUNAS' }}</strong>
            </div>
        </div>

        <div class="terms">
            <strong>Ketentuan Pelayanan:</strong>
            <ol style="margin: 4px 0 0; padding-left: 18px;">
                <li>Pengambilan cucian wajib membawa nota ini atau menunjukkan kode pesanan resmi.</li>
                <li>Pakaian yang tidak diambil lebih dari 30 hari di luar tanggung jawab Maudy Laundry.</li>
                <li>Klaim keluhan maksimal 1x24 jam setelah cucian diambil.</li>
            </ol>
        </div>

        <div style="text-align: center; margin-top: 20px;">
            <button onclick="window.print()" class="btn-print">🖨️ Cetak Nota Transaksi</button>
        </div>
    </div>

</body>
</html>
