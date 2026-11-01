<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\PesananItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicOrderController extends Controller
{
    public function index()
    {
        $layanans = Layanan::all();
        return view('public.landing', compact('layanans'));
    }

    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'no_telp' => ['required', 'string', 'max:30'],
            'alamat' => ['required', 'string'],
            'jenis_layanan_id' => ['required', 'exists:layanan,id'],
            'estimasi_berat_kg' => ['required', 'numeric', 'min:0.5'],
            'metode_pembayaran' => ['required', 'in:COD,QRIS'],
            'tgl_penjemputan' => ['required', 'date'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap Anda wajib diisi.',
            'no_telp.required' => 'Nomor WhatsApp/HP wajib diisi agar kurir dapat menghubungi Anda.',
            'alamat.required' => 'Alamat lengkap penjemputan wajib diisi.',
            'jenis_layanan_id.required' => 'Pilih jenis layanan laundry yang Anda butuhkan.',
            'estimasi_berat_kg.required' => 'Masukkan estimasi berat cucian.',
            'estimasi_berat_kg.min' => 'Estimasi berat minimal 0.5 kg.',
            'metode_pembayaran.required' => 'Pilih metode pembayaran (QRIS atau COD).',
            'metode_pembayaran.in' => 'Pilihan metode pembayaran tidak valid.',
            'tgl_penjemputan.required' => 'Tentukan tanggal penjemputan cucian.',
        ]);

        $layanan = Layanan::findOrFail($validated['jenis_layanan_id']);
        $maxLimit = $layanan->maksimum_berat;

        // Validasi batas maksimal per kategori / layanan
        if ($validated['estimasi_berat_kg'] > $maxLimit) {
            return back()->withInput()->withErrors([
                'estimasi_berat_kg' => "Perhatian: Estimasi berat ({$validated['estimasi_berat_kg']} kg) melebihi batas maksimal untuk layanan {$layanan->nama_layanan} (Batas Maksimal: {$maxLimit} kg per pesanan). Silakan kurangi berat cucian atau buat pesanan terpisah demi menjaga kualitas pencucian.",
            ]);
        }

        $pesanan = DB::transaction(function () use ($validated, $layanan) {
            // Cari atau buat pelanggan baru berdasarkan nomor telepon
            $pelanggan = Pelanggan::where('no_telp', $validated['no_telp'])->first();
            if (!$pelanggan) {
                $pelanggan = Pelanggan::create([
                    'id' => Pelanggan::generateNextId(),
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'no_telp' => $validated['no_telp'],
                    'alamat' => $validated['alamat'],
                ]);
            } else {
                // Update nama / alamat jika ada perubahan
                $pelanggan->update([
                    'nama_lengkap' => $validated['nama_lengkap'],
                    'alamat' => $validated['alamat'],
                ]);
            }

            $pesananId = Pesanan::generateNextId();
            $tglMasuk = Carbon::parse($validated['tgl_penjemputan']);
            $tglEstimasi = $tglMasuk->copy()->addDays($layanan->estimasi_waktu_hari);

            // Perhitungan harga di sisi server
            $totalHarga = $validated['estimasi_berat_kg'] * $layanan->harga_per_kg;

            $newPesanan = Pesanan::create([
                'id' => $pesananId,
                'sale_number' => $pesananId,
                'pelanggan_id' => $pelanggan->id,
                'pegawai_id' => null, // Belum ditugaskan, masuk ke antrian verifikasi staf
                'jenis_layanan_id' => $layanan->id,
                'berat_kg' => $validated['estimasi_berat_kg'],
                'status_pesanan' => 'Menunggu',
                'tgl_masuk' => $tglMasuk->toDateString(),
                'tgl_selesai_estimasi' => $tglEstimasi->toDateString(),
                'tgl_selesai_aktual' => null,
                'total_harga' => $totalHarga,
                'sumber_order' => 'online',
                'metode_pembayaran' => $validated['metode_pembayaran'],
                'catatan' => $validated['catatan'] ?? 'Pemesanan online mandiri via website',
            ]);

            PesananItem::create([
                'pesanan_id' => $newPesanan->id,
                'inventaris_id' => null,
                'nama_item' => "Layanan {$layanan->nama_layanan} (Estimasi {$validated['estimasi_berat_kg']} kg)",
                'quantity' => $validated['estimasi_berat_kg'],
                'unit_price' => $layanan->harga_per_kg,
                'subtotal' => $totalHarga,
            ]);

            // Buat pencatatan pembayaran awal
            $metodeBayarEnum = $validated['metode_pembayaran'] === 'QRIS' ? 'QRIS' : 'CASH';
            Pembayaran::create([
                'id' => Pembayaran::generateNextId(),
                'pesanan_id' => $newPesanan->id,
                'tgl_bayar' => $tglMasuk->toDateString(),
                'metode_bayar' => $metodeBayarEnum,
                'jumlah_bayar' => $totalHarga,
                'status_bayar' => 'Belum Lunas',
                'pegawai_id' => null,
            ]);

            return $newPesanan;
        });

        return redirect()->route('public.success', $pesanan->id)
            ->with('success', 'Pesanan laundry Anda berhasil dibuat! Silakan pantau status atau selesaikan pembayaran.');
    }

    public function success(string $id)
    {
        $pesanan = Pesanan::with(['pelanggan', 'layanan', 'pembayaran'])->findOrFail($id);
        return view('public.success', compact('pesanan'));
    }

    public function tracking(Request $request)
    {
        $code = trim($request->query('code', ''));
        $pesanan = null;

        if ($code) {
            $pesanan = Pesanan::with(['pelanggan', 'layanan', 'pegawai', 'pembayaran'])
                ->where('id', $code)
                ->orWhere('sale_number', $code)
                ->first();
        }

        return view('public.tracking', compact('pesanan', 'code'));
    }

    public function confirmQrisPayment(Request $request, string $id)
    {
        $pesanan = Pesanan::with('pembayaran')->findOrFail($id);

        $pembayaran = $pesanan->pembayaran;
        if (!$pembayaran) {
            $pembayaran = Pembayaran::create([
                'id' => Pembayaran::generateNextId(),
                'pesanan_id' => $pesanan->id,
                'tgl_bayar' => now()->toDateString(),
                'metode_bayar' => 'QRIS',
                'jumlah_bayar' => $pesanan->total_harga,
                'status_bayar' => 'Lunas',
                'pegawai_id' => null,
            ]);
        } else {
            $pembayaran->update([
                'metode_bayar' => 'QRIS',
                'status_bayar' => 'Lunas',
                'tgl_bayar' => now()->toDateString(),
            ]);
        }

        // Update preferensi metode pembayaran pesanan
        $pesanan->update(['metode_pembayaran' => 'QRIS']);

        return back()->with('success', "Pembayaran QRIS untuk pesanan #{$pesanan->id} sebesar Rp " . number_format($pesanan->total_harga, 0, ',', '.') . " berhasil dikonfirmasi dan status kini LUNAS!");
    }
}
