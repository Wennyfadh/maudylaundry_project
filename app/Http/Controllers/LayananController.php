<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Layanan::query();
        if ($search) {
            $query->where('nama_layanan', 'like', "%{$search}%")
                ->orWhere('id', 'like', "%{$search}%");
        }

        $layanans = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();
        $nextId = Layanan::generateNextId();

        return view('layanan.index', compact('layanans', 'nextId', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => ['nullable', 'string', 'max:20', 'unique:layanan,id'],
            'nama_layanan' => ['required', 'string', 'max:150'],
            'harga_per_kg' => ['required', 'numeric', 'min:0'],
            'estimasi_waktu_hari' => ['required', 'integer', 'min:1'],
        ], [
            'nama_layanan.required' => 'Nama layanan laundry wajib diisi.',
            'harga_per_kg.required' => 'Harga per kg wajib diisi.',
            'harga_per_kg.min' => 'Harga tidak boleh bernilai negatif.',
            'estimasi_waktu_hari.required' => 'Estimasi waktu hari wajib diisi.',
        ]);

        if (empty($validated['id'] ?? null)) {
            $validated['id'] = Layanan::generateNextId();
        }

        Layanan::create($validated);

        return redirect()->route('layanan.index')
            ->with('success', "Layanan {$validated['nama_layanan']} ({$validated['id']}) berhasil ditambahkan!");
    }

    public function update(Request $request, string $id)
    {
        $layanan = Layanan::findOrFail($id);

        $validated = $request->validate([
            'nama_layanan' => ['required', 'string', 'max:150'],
            'harga_per_kg' => ['required', 'numeric', 'min:0'],
            'estimasi_waktu_hari' => ['required', 'integer', 'min:1'],
        ]);

        $layanan->update($validated);

        return redirect()->route('layanan.index')
            ->with('success', "Layanan {$layanan->nama_layanan} berhasil diperbarui!");
    }

    public function destroy(string $id)
    {
        $layanan = Layanan::findOrFail($id);

        if ($layanan->pesanan()->exists()) {
            return redirect()->route('layanan.index')
                ->with('error', "Layanan {$layanan->nama_layanan} tidak dapat dihapus karena sudah digunakan dalam transaksi pesanan.");
        }

        $layanan->delete();

        return redirect()->route('layanan.index')
            ->with('success', "Layanan {$layanan->nama_layanan} berhasil dihapus.");
    }
}
