<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pesanan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $metodeFilter = $request->query('metode');

        $query = Pembayaran::with(['pesanan.pelanggan', 'pegawai']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('pesanan_id', 'like', "%{$search}%")
                    ->orWhereHas('pesanan.pelanggan', function ($sub) use ($search) {
                        $sub->where('nama_lengkap', 'like', "%{$search}%");
                    });
            });
        }

        if ($metodeFilter) {
            $query->where('metode_bayar', $metodeFilter);
        }

        $pembayarans = $query->orderBy('tgl_bayar', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Stat cards
        $totalLunas = Pembayaran::where('status_bayar', 'Lunas')->sum('jumlah_bayar');

        // Belum Lunas: pesanan yang belum memiliki pembayaran lunas
        $pesananBelumLunasCount = Pesanan::whereDoesntHave('pembayarans', function ($q) {
            $q->where('status_bayar', 'Lunas');
        })->count();

        $nextId = Pembayaran::generateNextId();

        // Daftar pesanan yang belum lunas atau bisa dibayar
        $unpaidPesanan = Pesanan::with('pelanggan')
            ->whereDoesntHave('pembayarans', function ($q) {
                $q->where('status_bayar', 'Lunas');
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('pembayaran.index', compact(
            'pembayarans',
            'totalLunas',
            'pesananBelumLunasCount',
            'nextId',
            'unpaidPesanan',
            'search',
            'metodeFilter'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => ['nullable', 'string', 'max:20', 'unique:pembayaran,id'],
            'pesanan_id' => ['required', 'exists:pesanan,id'],
            'tgl_bayar' => ['required', 'date'],
            'metode_bayar' => ['required', 'in:CASH,QRIS,TRANSFER'],
            'jumlah_bayar' => ['required', 'numeric', 'min:1'],
            'status_bayar' => ['required', 'in:Lunas,Belum Lunas'],
        ], [
            'pesanan_id.required' => 'Pilih pesanan yang akan dibayar.',
            'tgl_bayar.required' => 'Tanggal pembayaran wajib diisi.',
            'metode_bayar.required' => 'Metode pembayaran wajib dipilih.',
            'jumlah_bayar.required' => 'Jumlah bayar wajib diisi.',
            'jumlah_bayar.min' => 'Jumlah bayar minimal Rp 1.',
        ]);

        $payId = ($validated['id'] ?? null) ?: Pembayaran::generateNextId();
        $pegawaiId = Auth::user()->pegawai?->id;

        DB::transaction(function () use ($validated, $payId, $pegawaiId) {
            Pembayaran::create([
                'id' => $payId,
                'pesanan_id' => $validated['pesanan_id'],
                'tgl_bayar' => $validated['tgl_bayar'],
                'metode_bayar' => $validated['metode_bayar'],
                'jumlah_bayar' => $validated['jumlah_bayar'],
                'status_bayar' => $validated['status_bayar'],
                'pegawai_id' => $pegawaiId,
            ]);

            // Jika status bayar lunas, perbarui pesanan jika diperlukan
            if ($validated['status_bayar'] === 'Lunas') {
                $pesanan = Pesanan::find($validated['pesanan_id']);
                if ($pesanan && $pesanan->status_pesanan === 'Menunggu') {
                    $pesanan->update(['status_pesanan' => 'Diproses']);
                }
            }
        });

        return redirect()->route('pembayaran.index')
            ->with('success', "Pembayaran {$payId} untuk pesanan {$validated['pesanan_id']} berhasil dicatat!");
    }

    public function struk(string $id)
    {
        $pembayaran = Pembayaran::with(['pesanan.pelanggan', 'pesanan.layanan', 'pegawai'])->findOrFail($id);
        return view('pembayaran.struk', compact('pembayaran'));
    }

    public function destroy(string $id)
    {
        if (!Auth::user()->isOwner()) {
            abort(403, 'Hanya Owner yang dapat menghapus catatan pembayaran.');
        }

        $pembayaran = Pembayaran::findOrFail($id);
        $pembayaran->delete();

        return redirect()->route('pembayaran.index')
            ->with('success', "Catatan pembayaran {$pembayaran->id} berhasil dihapus.");
    }
}
