<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Pegawai;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPelanggan = Pelanggan::count();
        $pesananAktif = Pesanan::whereIn('status_pesanan', ['Menunggu', 'Diproses'])->count();

        // Pendapatan bulan berjalan (atau bulan terakhir jika data dummy ada di Juli 2026)
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $pendapatanBulanIni = Pembayaran::where('status_bayar', 'Lunas')
            ->whereMonth('tgl_bayar', $currentMonth)
            ->whereYear('tgl_bayar', $currentYear)
            ->sum('jumlah_bayar');

        // Jika bulan ini 0 tapi ada data di bulan Juli 2026 (seperti data UTS di screenshot), kita tampilkan label bulan dinamis
        $bulanNama = Carbon::now()->translatedFormat('F Y');
        if ($pendapatanBulanIni == 0) {
            $latestPayment = Pembayaran::where('status_bayar', 'Lunas')->latest('tgl_bayar')->first();
            if ($latestPayment) {
                $monthDate = Carbon::parse($latestPayment->tgl_bayar);
                $bulanNama = $monthDate->translatedFormat('F Y');
                $pendapatanBulanIni = Pembayaran::where('status_bayar', 'Lunas')
                    ->whereMonth('tgl_bayar', $monthDate->month)
                    ->whereYear('tgl_bayar', $monthDate->year)
                    ->sum('jumlah_bayar');
            }
        }

        $totalPegawai = Pegawai::count();

        // 5 Pesanan Terbaru
        $pesananTerbaru = Pesanan::with(['pelanggan', 'pegawai', 'layanan'])
            ->latest('tgl_masuk')
            ->take(5)
            ->get();

        // Stok Rendah (stok <= minimum_stok)
        $stokRendah = Inventaris::where('stok', '<=', DB::raw('minimum_stok'))
            ->orderBy('stok', 'asc')
            ->take(5)
            ->get();

        $tanggalFormat = Carbon::now()->translatedFormat('l, d F Y');

        return view('dashboard', compact(
            'totalPelanggan',
            'pesananAktif',
            'pendapatanBulanIni',
            'bulanNama',
            'totalPegawai',
            'pesananTerbaru',
            'stokRendah',
            'tanggalFormat'
        ));
    }
}
