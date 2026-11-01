<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'detail'); // detail, harian, mingguan, bulanan, tahunan

        // Default range to cover current date or seeded month (July 2026)
        $defaultStart = Carbon::parse('2026-07-01');
        $defaultEnd = Carbon::parse('2026-07-31');

        $startDate = $request->filled('start_date') ? Carbon::parse($request->query('start_date')) : $defaultStart;
        $endDate = $request->filled('end_date') ? Carbon::parse($request->query('end_date')) : $defaultEnd;

        // Base Query Pembayaran
        $pembayaranQuery = Pembayaran::with(['pesanan.pelanggan', 'pesanan.layanan'])
            ->where('status_bayar', 'Lunas')
            ->whereBetween('tgl_bayar', [$startDate->toDateString(), $endDate->toDateString()]);

        // Base Query Pesanan
        $pesananQuery = Pesanan::whereBetween('tgl_masuk', [$startDate->toDateString(), $endDate->toDateString()]);

        $totalPendapatan = (float) (clone $pembayaranQuery)->sum('jumlah_bayar');
        $totalPesanan = (clone $pesananQuery)->count();
        $pesananSelesai = (clone $pesananQuery)->where('status_pesanan', 'Selesai')->count();
        $rataRata = $totalPesanan > 0 ? round($totalPendapatan / $totalPesanan) : 0;

        // Detail Transaksi
        $detailTransaksi = (clone $pembayaranQuery)->orderBy('tgl_bayar', 'desc')->get();

        // Laporan Harian
        $laporanHarian = Pembayaran::select(
            'tgl_bayar as tanggal',
            DB::raw('COUNT(id) as total_transaksi'),
            DB::raw('SUM(jumlah_bayar) as total_pendapatan')
        )
            ->where('status_bayar', 'Lunas')
            ->whereBetween('tgl_bayar', [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy('tgl_bayar')
            ->orderBy('tgl_bayar', 'desc')
            ->get();

        // Laporan Mingguan
        $laporanMingguan = (clone $pembayaranQuery)->get()
            ->groupBy(function ($item) {
                $dt = Carbon::parse($item->tgl_bayar);
                return 'Minggu ' . $dt->weekOfYear . ' (' . $dt->year . ')';
            })
            ->map(function ($items, $key) {
                $minDate = $items->min('tgl_bayar');
                $maxDate = $items->max('tgl_bayar');
                return [
                    'label' => $key,
                    'rentang' => Carbon::parse($minDate)->translatedFormat('d M Y') . ' — ' . Carbon::parse($maxDate)->translatedFormat('d M Y'),
                    'total_transaksi' => $items->count(),
                    'total_pendapatan' => $items->sum('jumlah_bayar'),
                ];
            });

        // Laporan Bulanan
        $laporanBulanan = (clone $pembayaranQuery)->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->tgl_bayar)->translatedFormat('F Y');
            })
            ->map(function ($items, $key) {
                $dt = Carbon::parse($items->first()->tgl_bayar);
                return [
                    'bulan' => $dt->translatedFormat('F'),
                    'tahun' => $dt->year,
                    'total_transaksi' => $items->count(),
                    'total_pendapatan' => $items->sum('jumlah_bayar'),
                ];
            });

        // Laporan Tahunan
        $laporanTahunan = (clone $pembayaranQuery)->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->tgl_bayar)->year;
            })
            ->map(function ($items, $year) {
                return [
                    'tahun' => $year,
                    'total_transaksi' => $items->count(),
                    'total_pesanan' => $items->count(),
                    'total_pendapatan' => $items->sum('jumlah_bayar'),
                ];
            });

        // Layanan Terpopuler
        $layananTerpopuler = Layanan::withCount('pesanan')
            ->withSum('pesanan', 'total_harga')
            ->orderBy('pesanan_sum_total_harga', 'desc')
            ->get();

        return view('laporan.index', compact(
            'tab',
            'startDate',
            'endDate',
            'totalPendapatan',
            'totalPesanan',
            'pesananSelesai',
            'rataRata',
            'detailTransaksi',
            'laporanHarian',
            'laporanMingguan',
            'laporanBulanan',
            'laporanTahunan',
            'layananTerpopuler'
        ));
    }

    public function print(Request $request)
    {
        $tab = $request->query('tab', 'detail');
        $defaultStart = Carbon::parse('2026-07-01');
        $defaultEnd = Carbon::parse('2026-07-31');

        $startDate = $request->filled('start_date') ? Carbon::parse($request->query('start_date')) : $defaultStart;
        $endDate = $request->filled('end_date') ? Carbon::parse($request->query('end_date')) : $defaultEnd;

        $pembayaranQuery = Pembayaran::with(['pesanan.pelanggan', 'pesanan.layanan'])
            ->where('status_bayar', 'Lunas')
            ->whereBetween('tgl_bayar', [$startDate->toDateString(), $endDate->toDateString()]);

        $pesananQuery = Pesanan::whereBetween('tgl_masuk', [$startDate->toDateString(), $endDate->toDateString()]);

        $totalPendapatan = (float) (clone $pembayaranQuery)->sum('jumlah_bayar');
        $totalPesanan = (clone $pesananQuery)->count();
        $pesananSelesai = (clone $pesananQuery)->where('status_pesanan', 'Selesai')->count();
        $rataRata = $totalPesanan > 0 ? round($totalPendapatan / $totalPesanan) : 0;

        $detailTransaksi = (clone $pembayaranQuery)->orderBy('tgl_bayar', 'desc')->get();

        $laporanHarian = Pembayaran::select(
            'tgl_bayar as tanggal',
            DB::raw('COUNT(id) as total_transaksi'),
            DB::raw('SUM(jumlah_bayar) as total_pendapatan')
        )
            ->where('status_bayar', 'Lunas')
            ->whereBetween('tgl_bayar', [$startDate->toDateString(), $endDate->toDateString()])
            ->groupBy('tgl_bayar')
            ->orderBy('tgl_bayar', 'desc')
            ->get();

        $laporanMingguan = (clone $pembayaranQuery)->get()
            ->groupBy(function ($item) {
                $dt = Carbon::parse($item->tgl_bayar);
                return 'Minggu ' . $dt->weekOfYear . ' (' . $dt->year . ')';
            })
            ->map(function ($items, $key) {
                $minDate = $items->min('tgl_bayar');
                $maxDate = $items->max('tgl_bayar');
                return [
                    'label' => $key,
                    'rentang' => Carbon::parse($minDate)->translatedFormat('d M Y') . ' — ' . Carbon::parse($maxDate)->translatedFormat('d M Y'),
                    'total_transaksi' => $items->count(),
                    'total_pendapatan' => $items->sum('jumlah_bayar'),
                ];
            });

        $laporanBulanan = (clone $pembayaranQuery)->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->tgl_bayar)->translatedFormat('F Y');
            })
            ->map(function ($items, $key) {
                $dt = Carbon::parse($items->first()->tgl_bayar);
                return [
                    'bulan' => $dt->translatedFormat('F'),
                    'tahun' => $dt->year,
                    'total_transaksi' => $items->count(),
                    'total_pendapatan' => $items->sum('jumlah_bayar'),
                ];
            });

        $laporanTahunan = (clone $pembayaranQuery)->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->tgl_bayar)->year;
            })
            ->map(function ($items, $year) {
                return [
                    'tahun' => $year,
                    'total_transaksi' => $items->count(),
                    'total_pesanan' => $items->count(),
                    'total_pendapatan' => $items->sum('jumlah_bayar'),
                ];
            });

        $printedAt = Carbon::now()->translatedFormat('d-m-Y H:i') . ' WIB';

        return view('laporan.print', compact(
            'tab',
            'startDate',
            'endDate',
            'totalPendapatan',
            'totalPesanan',
            'pesananSelesai',
            'rataRata',
            'detailTransaksi',
            'laporanHarian',
            'laporanMingguan',
            'laporanBulanan',
            'laporanTahunan',
            'printedAt'
        ));
    }
}
