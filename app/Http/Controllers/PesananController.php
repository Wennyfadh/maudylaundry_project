<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Layanan;
use App\Models\Pegawai;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\PesananItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $statusFilter = $request->query('status');

        $query = Pesanan::with(['pelanggan', 'pegawai', 'layanan', 'pembayaran']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('sale_number', 'like', "%{$search}%")
                    ->orWhereHas('pelanggan', function ($sub) use ($search) {
                        $sub->where('nama_lengkap', 'like', "%{$search}%");
                    })
                    ->orWhereHas('pegawai', function ($sub) use ($search) {
                        $sub->where('nama_lengkap', 'like', "%{$search}%");
                    });
            });
        }

        if ($statusFilter) {
            $query->where('status_pesanan', $statusFilter);
        }

        $pesanans = $query->orderBy('tgl_masuk', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        $pelanggans = Pelanggan::orderBy('nama_lengkap')->get();
        $pegawais = Pegawai::orderBy('nama_lengkap')->get();
        $layanans = Layanan::orderBy('nama_layanan')->get();
        $nextId = Pesanan::generateNextId();

        // Default pegawai dari staf yang login
        $currentPegawaiId = Auth::user()->pegawai?->id ?? ($pegawais->first()?->id ?? null);

        return view('pesanan.index', compact(
            'pesanans',
            'pelanggans',
            'pegawais',
            'layanans',
            'nextId',
            'currentPegawaiId',
            'search',
            'statusFilter'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => ['nullable', 'string', 'max:20', 'unique:pesanan,id'],
            'pelanggan_id' => ['required', 'exists:pelanggan,id'],
            'pegawai_id' => ['nullable', 'exists:pegawai,id'],
            'jenis_layanan_id' => ['required', 'exists:layanan,id'],
            'berat_kg' => ['required', 'numeric', 'min:0.1'],
            'status_pesanan' => ['required', 'in:Menunggu,Diproses,Selesai'],
            'tgl_masuk' => ['required', 'date'],
            'tgl_selesai_estimasi' => ['nullable', 'date'],
            'catatan' => ['nullable', 'string'],
        ], [
            'pelanggan_id.required' => 'Pilih pelanggan yang memesan.',
            'jenis_layanan_id.required' => 'Pilih jenis layanan laundry.',
            'berat_kg.required' => 'Berat cucian wajib diisi.',
            'berat_kg.min' => 'Berat cucian minimal 0.1 kg.',
            'tgl_masuk.required' => 'Tanggal masuk wajib diisi.',
        ]);

        $pesananId = $validated['id'] ?? Pesanan::generateNextId();

        // Jika pegawai_id tidak dipilih, default ke pegawai dari user login
        $pegawaiId = $validated['pegawai_id'] ?? Auth::user()->pegawai?->id;

        // Eksekusi atomik menggunakan DB::transaction dan lockForUpdate
        DB::transaction(function () use ($validated, $pesananId, $pegawaiId) {
            // Ambil data layanan langsung dari database (Server-Side Calculation)
            $layanan = Layanan::findOrFail($validated['jenis_layanan_id']);

            // Estimasi selesai
            $tglMasuk = Carbon::parse($validated['tgl_masuk']);
            $tglSelesai = !empty($validated['tgl_selesai_estimasi'])
                ? Carbon::parse($validated['tgl_selesai_estimasi'])
                : $tglMasuk->copy()->addDays($layanan->estimasi_waktu_hari);

            $totalHarga = $validated['berat_kg'] * $layanan->harga_per_kg;

            // KONSISTENSI STOK OPERASIONAL (PDF 1 Requirement):
            // Kebutuhan bahan baku per pesanan:
            // 1. Deterjen: 0.05 liter per kg
            // 2. Plastik Kemasan: 1 lembar per pesanan
            $deterjenNeeded = round($validated['berat_kg'] * 0.05, 2);
            $plastikNeeded = 1;

            $deterjen = Inventaris::where('id', 'INV01')->lockForUpdate()->first();
            $plastik = Inventaris::where('id', 'INV03')->lockForUpdate()->first();

            if ($deterjen && $deterjen->stok < $deterjenNeeded) {
                throw ValidationException::withMessages([
                    'berat_kg' => "Stok deterjen ({$deterjen->stok} {$deterjen->satuan}) tidak mencukupi untuk mencuci {$validated['berat_kg']} kg (dibutuhkan {$deterjenNeeded} {$deterjen->satuan}). Transaksi dibatalkan secara atomik.",
                ]);
            }

            if ($plastik && $plastik->stok < $plastikNeeded) {
                throw ValidationException::withMessages([
                    'berat_kg' => "Stok plastik kemasan habis. Transaksi dibatalkan secara atomik.",
                ]);
            }

            // Kurangi stok dan update status stok
            if ($deterjen) {
                $deterjen->stok -= $deterjenNeeded;
                $deterjen->updateStatusStok();
            }
            if ($plastik) {
                $plastik->stok -= $plastikNeeded;
                $plastik->updateStatusStok();
            }

            // Simpan header pesanan
            $pesanan = Pesanan::create([
                'id' => $pesananId,
                'sale_number' => $pesananId,
                'pelanggan_id' => $validated['pelanggan_id'],
                'pegawai_id' => $pegawaiId,
                'jenis_layanan_id' => $layanan->id,
                'berat_kg' => $validated['berat_kg'],
                'status_pesanan' => $validated['status_pesanan'],
                'tgl_masuk' => $tglMasuk->toDateString(),
                'tgl_selesai_estimasi' => $tglSelesai->toDateString(),
                'tgl_selesai_aktual' => ($validated['status_pesanan'] === 'Selesai') ? now()->toDateString() : null,
                'total_harga' => $totalHarga,
                'sumber_order' => 'offline',
                'catatan' => $validated['catatan'] ?? null,
            ]);

            // Simpan detail item pesanan
            PesananItem::create([
                'pesanan_id' => $pesanan->id,
                'inventaris_id' => 'INV01',
                'nama_item' => "Layanan {$layanan->nama_layanan} ({$validated['berat_kg']} kg)",
                'quantity' => $validated['berat_kg'],
                'unit_price' => $layanan->harga_per_kg,
                'subtotal' => $totalHarga,
            ]);
        });

        return redirect()->route('pesanan.index')
            ->with('success', "Pesanan baru {$pesananId} berhasil dibuat dan stok bahan operasional berhasil disesuaikan secara konsisten!");
    }

    public function update(Request $request, string $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $validated = $request->validate([
            'pelanggan_id' => ['required', 'exists:pelanggan,id'],
            'pegawai_id' => ['nullable', 'exists:pegawai,id'],
            'jenis_layanan_id' => ['required', 'exists:layanan,id'],
            'berat_kg' => ['required', 'numeric', 'min:0.1'],
            'status_pesanan' => ['required', 'in:Menunggu,Diproses,Selesai'],
            'tgl_masuk' => ['required', 'date'],
            'tgl_selesai_estimasi' => ['nullable', 'date'],
            'catatan' => ['nullable', 'string'],
        ]);

        $layanan = Layanan::findOrFail($validated['jenis_layanan_id']);
        $totalHarga = $validated['berat_kg'] * $layanan->harga_per_kg;

        $pesanan->update([
            'pelanggan_id' => $validated['pelanggan_id'],
            'pegawai_id' => $validated['pegawai_id'] ?? $pesanan->pegawai_id,
            'jenis_layanan_id' => $layanan->id,
            'berat_kg' => $validated['berat_kg'],
            'status_pesanan' => $validated['status_pesanan'],
            'tgl_masuk' => $validated['tgl_masuk'],
            'tgl_selesai_estimasi' => $validated['tgl_selesai_estimasi'],
            'tgl_selesai_aktual' => ($validated['status_pesanan'] === 'Selesai') ? ($pesanan->tgl_selesai_aktual ?? now()->toDateString()) : null,
            'total_harga' => $totalHarga,
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('pesanan.index')
            ->with('success', "Pesanan {$pesanan->id} berhasil diperbarui!");
    }

    public function updateStatus(Request $request, string $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $validated = $request->validate([
            'status_pesanan' => ['required', 'in:Menunggu,Diproses,Selesai'],
        ]);

        $updates = ['status_pesanan' => $validated['status_pesanan']];
        if ($validated['status_pesanan'] === 'Selesai' && !$pesanan->tgl_selesai_aktual) {
            $updates['tgl_selesai_aktual'] = now()->toDateString();
        }

        // Catat staf penangan jika belum ada
        if (!$pesanan->pegawai_id && Auth::user()->pegawai) {
            $updates['pegawai_id'] = Auth::user()->pegawai->id;
        }

        $pesanan->update($updates);

        return back()->with('success', "Status pesanan {$pesanan->id} berhasil diubah menjadi: {$validated['status_pesanan']}.");
    }

    public function destroy(string $id)
    {
        $pesanan = Pesanan::findOrFail($id);

        // Hanya owner yang dapat membatalkan/menghapus transaksi sesuai ketentuan UTS
        if (!Auth::user()->isOwner()) {
            abort(403, 'Hanya Owner/Pimpinan yang memiliki wewenang membatalkan transaksi.');
        }

        // Batalkan transaksi, hapus riwayat pembayaran terkait, dan kembalikan stok operasional (Stock Restoration)
        DB::transaction(function () use ($pesanan) {
            $deterjenNeeded = round($pesanan->berat_kg * 0.05, 2);
            $plastikNeeded = 1;

            $deterjen = Inventaris::where('id', 'INV01')->lockForUpdate()->first();
            $plastik = Inventaris::where('id', 'INV03')->lockForUpdate()->first();

            if ($deterjen) {
                $deterjen->stok += $deterjenNeeded;
                $deterjen->updateStatusStok();
            }
            if ($plastik) {
                $plastik->stok += $plastikNeeded;
                $plastik->updateStatusStok();
            }

            // Hapus data pembayaran terkait pesanan ini (jika ada) agar tidak terjadi konflik relasi
            $pesanan->pembayarans()->delete();

            $pesanan->items()->delete();
            $pesanan->delete();
        });

        return redirect()->route('pesanan.index')
            ->with('success', "Pesanan {$pesanan->id} berhasil dihapus/dibatalkan dan stok bahan operasional berhasil dikembalikan.");
    }

    public function nota(string $id)
    {
        $pesanan = Pesanan::with(['pelanggan', 'pegawai', 'layanan', 'items', 'pembayaran'])->findOrFail($id);
        return view('pesanan.nota', compact('pesanan'));
    }
}
