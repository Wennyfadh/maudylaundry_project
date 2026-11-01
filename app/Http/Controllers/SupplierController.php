<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Supplier::withCount('purchases');
        if ($search) {
            $query->where('nama_supplier', 'like', "%{$search}%")
                ->orWhere('no_telp', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        $suppliers = $query->orderBy('nama_supplier')->paginate(10)->withQueryString();
        $totalSuppliers = Supplier::count();

        return view('suppliers.index', compact('suppliers', 'totalSuppliers', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_supplier' => ['required', 'string', 'max:150'],
            'no_telp' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'alamat' => ['nullable', 'string'],
        ], [
            'nama_supplier.required' => 'Nama supplier wajib diisi.',
            'no_telp.required' => 'Nomor kontak telepon supplier wajib diisi.',
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')
            ->with('success', "Supplier {$validated['nama_supplier']} berhasil ditambahkan!");
    }

    public function update(Request $request, int $id)
    {
        $supplier = Supplier::findOrFail($id);

        $validated = $request->validate([
            'nama_supplier' => ['required', 'string', 'max:150'],
            'no_telp' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'alamat' => ['nullable', 'string'],
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')
            ->with('success', "Data supplier {$supplier->nama_supplier} berhasil diperbarui!");
    }

    public function destroy(int $id)
    {
        $supplier = Supplier::findOrFail($id);

        if ($supplier->purchases()->exists()) {
            return redirect()->route('suppliers.index')
                ->with('error', "Supplier {$supplier->nama_supplier} tidak dapat dihapus karena memiliki riwayat transaksi pengadaan stok.");
        }

        $supplier->delete();

        return redirect()->route('suppliers.index')
            ->with('success', "Supplier {$supplier->nama_supplier} berhasil dihapus.");
    }
}
