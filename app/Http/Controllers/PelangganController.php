<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Pelanggan::query();
        if ($search) {
            $query->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('no_telp', 'like', "%{$search}%")
                ->orWhere('id', 'like', "%{$search}%");
        }

        $pelanggans = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();
        $totalPelanggan = Pelanggan::count();
        $nextId = Pelanggan::generateNextId();

        return view('pelanggan.index', compact('pelanggans', 'totalPelanggan', 'nextId', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => ['nullable', 'string', 'max:20', 'unique:pelanggan,id'],
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'no_telp' => ['required', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap pelanggan wajib diisi.',
            'no_telp.required' => 'Nomor telepon wajib diisi.',
        ]);

        if (empty($validated['id'] ?? null)) {
            $validated['id'] = Pelanggan::generateNextId();
        }

        Pelanggan::create($validated);

        return redirect()->route('pelanggan.index')
            ->with('success', "Pelanggan {$validated['nama_lengkap']} ({$validated['id']}) berhasil ditambahkan!");
    }

    public function update(Request $request, string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'no_telp' => ['required', 'string', 'max:30'],
            'alamat' => ['nullable', 'string'],
        ]);

        $pelanggan->update($validated);

        return redirect()->route('pelanggan.index')
            ->with('success', "Data pelanggan {$pelanggan->id} berhasil diperbarui!");
    }

    public function destroy(string $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        if ($pelanggan->pesanan()->exists()) {
            return redirect()->route('pelanggan.index')
                ->with('error', "Pelanggan {$pelanggan->nama_lengkap} tidak dapat dihapus karena memiliki riwayat pesanan.");
        }

        $pelanggan->delete();

        return redirect()->route('pelanggan.index')
            ->with('success', "Pelanggan {$pelanggan->nama_lengkap} berhasil dihapus.");
    }
}
