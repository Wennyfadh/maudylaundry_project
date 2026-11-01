<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan - Maudy Laundry</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #111;
            margin: 0;
            padding: 25px;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 28px;
            margin-bottom: 2px;
        }
        .title {
            font-size: 18px;
            font-weight: 800;
            letter-spacing: 0.5px;
            margin: 0 0 3px;
            color: #000;
        }
        .subtitle {
            font-size: 11px;
            color: #444;
            margin: 2px 0;
        }
        .meta-line {
            font-size: 10px;
            color: #666;
            margin-top: 4px;
        }
        .hr-double {
            border: 0;
            border-top: 2px solid #000;
            border-bottom: 1px solid #000;
            height: 3px;
            margin: 12px 0 16px;
        }
        .summary-bar {
            display: flex;
            justify-content: space-between;
            border: 1px solid #bbb;
            background: #fbfbfb;
            padding: 10px 15px;
            margin-bottom: 18px;
            border-radius: 4px;
        }
        .summary-item {
            text-align: center;
        }
        .summary-item .label {
            font-size: 9px;
            text-transform: uppercase;
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 2px;
        }
        .summary-item .value {
            font-size: 13px;
            font-weight: bold;
            color: #111;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        th, td {
            border: 1px solid #333;
            padding: 8px 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 45px;
            padding: 0 40px;
        }
        .sign-box {
            text-align: center;
            width: 200px;
        }
        .sign-space {
            height: 65px;
        }
        .sign-line {
            border-top: 1px solid #222;
            padding-top: 4px;
            font-weight: bold;
            font-size: 11px;
        }
        .btn-print {
            position: fixed;
            top: 15px;
            right: 15px;
            padding: 8px 16px;
            background: #0288d1;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        @media print {
            .btn-print { display: none; }
            body { padding: 0; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="btn-print">🖨️ Cetak Dokumen</button>

    <!-- Header Laporan Resmi Maudy Laundry (PDF 2 Slides 8-12) -->
    <div class="header">
        <div class="logo">🧺</div>
        <h1 class="title">MAUDY LAUNDRY</h1>
        <p class="subtitle">Sistem Informasi Manajemen Laundry Terpadu</p>
        <p class="subtitle">
            <strong>
                Laporan: 
                @if($tab === 'harian') Laporan Harian
                @elseif($tab === 'mingguan') Laporan Mingguan
                @elseif($tab === 'bulanan') Laporan Bulanan
                @elseif($tab === 'tahunan') Laporan Tahunan
                @else Laporan Detail Transaksi
                @endif
            </strong>
        </p>
        <p class="subtitle">Periode: {{ $startDate->translatedFormat('d M Y') }} s/d {{ $endDate->translatedFormat('d M Y') }}</p>
        <p class="meta-line">Dicetak oleh sistem pada: {{ $printedAt }}</p>
    </div>

    <div class="hr-double"></div>

    <!-- Summary Box (Sesuai Referensi Slide 8-12) -->
    <div class="summary-bar">
        <div class="summary-item">
            <span class="label">Total Pendapatan</span>
            <span class="value" style="color: #059669;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <span class="label">Total Pesanan</span>
            <span class="value">{{ $totalPesanan }}</span>
        </div>
        <div class="summary-item">
            <span class="label">Pesanan Selesai</span>
            <span class="value">{{ $pesananSelesai }}</span>
        </div>
        <div class="summary-item">
            <span class="label">Rata-Rata / Transaksi</span>
            <span class="value">Rp {{ number_format($rataRata, 0, ',', '.') }}</span>
        </div>
    </div>

    <!-- Data Tables By Tab (Sesuai Tampilan Slide 8, 9, 10, 11, 12) -->

    @if($tab === 'harian')
    <!-- Tampilan Laporan Harian (Slide 8) -->
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 40px;">No.</th>
                <th>Tanggal</th>
                <th class="text-center">Total Transaksi</th>
                <th class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporanHarian as $idx => $lh)
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($lh->tanggal)->translatedFormat('d M Y') }}</td>
                <td class="text-center">{{ $lh->total_transaksi }} transaksi</td>
                <td class="text-right font-mono">Rp {{ number_format($lh->total_pendapatan, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="bold" style="background: #f9f9f9;">
                <td colspan="3" class="text-right">TOTAL PENDAPATAN:</td>
                <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    @elseif($tab === 'mingguan')
    <!-- Tampilan Laporan Mingguan (Slide 9) -->
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 40px;">No.</th>
                <th>Minggu Ke-</th>
                <th>Rentang Tanggal</th>
                <th class="text-center">Total Transaksi</th>
                <th class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($laporanMingguan as $lm)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="bold">{{ $lm['label'] }}</td>
                <td>{{ $lm['rentang'] }}</td>
                <td class="text-center">{{ $lm['total_transaksi'] }} transaksi</td>
                <td class="text-right font-mono">Rp {{ number_format($lm['total_pendapatan'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="bold" style="background: #f9f9f9;">
                <td colspan="4" class="text-right">TOTAL PENDAPATAN:</td>
                <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    @elseif($tab === 'bulanan')
    <!-- Tampilan Laporan Bulanan (Slide 10) -->
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 40px;">No.</th>
                <th>Bulan</th>
                <th class="text-center">Tahun</th>
                <th class="text-center">Total Transaksi</th>
                <th class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($laporanBulanan as $lb)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="bold">{{ $lb['bulan'] }}</td>
                <td class="text-center">{{ $lb['tahun'] }}</td>
                <td class="text-center">{{ $lb['total_transaksi'] }} transaksi</td>
                <td class="text-right font-mono">Rp {{ number_format($lb['total_pendapatan'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="bold" style="background: #f9f9f9;">
                <td colspan="4" class="text-right">TOTAL PENDAPATAN:</td>
                <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    @elseif($tab === 'tahunan')
    <!-- Tampilan Laporan Tahunan (Slide 11) -->
    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 40px;">No.</th>
                <th>Tahun</th>
                <th class="text-center">Total Transaksi</th>
                <th class="text-center">Total Pesanan</th>
                <th class="text-right">Total Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($laporanTahunan as $lt)
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td class="bold">{{ $lt['tahun'] }}</td>
                <td class="text-center">{{ $lt['total_transaksi'] }} transaksi</td>
                <td class="text-center">{{ $lt['total_pesanan'] }} pesanan</td>
                <td class="text-right font-mono">Rp {{ number_format($lt['total_pendapatan'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="bold" style="background: #f9f9f9;">
                <td colspan="4" class="text-right">GRAND TOTAL PENDAPATAN:</td>
                <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    @else
    <!-- Tampilan Laporan Detail Transaksi (Slide 12) -->
    <table>
        <thead>
            <tr>
                <th>ID Bayar</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Metode</th>
                <th>Status</th>
                <th class="text-right">Jumlah Bayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detailTransaksi as $dt)
            <tr>
                <td class="bold font-mono">{{ $dt->id }}</td>
                <td>{{ \Carbon\Carbon::parse($dt->tgl_bayar)->translatedFormat('d M Y') }}</td>
                <td>{{ $dt->pesanan?->pelanggan?->nama_lengkap ?? '-' }}</td>
                <td>{{ $dt->metode_bayar }}</td>
                <td>{{ $dt->status_bayar }}</td>
                <td class="text-right font-mono">Rp {{ number_format($dt->jumlah_bayar, 0, ',', '.') }}</td>
            </tr>
            @endforeach
            <tr class="bold" style="background: #f9f9f9;">
                <td colspan="5" class="text-right">TOTAL PENDAPATAN:</td>
                <td class="text-right">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
    @endif

    <!-- Tanda Tangan Resmi (Slide 8-12) -->
    <div class="signatures">
        <div class="sign-box">
            <p>Mengetahui,</p>
            <div class="sign-space"></div>
            <div class="sign-line">Pimpinan</div>
        </div>

        <div class="sign-box">
            <p>Dibuat oleh,</p>
            <div class="sign-space"></div>
            <div class="sign-line">Admin / Kasir</div>
        </div>
    </div>

</body>
</html>
