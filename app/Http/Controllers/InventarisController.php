<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Inventaris;
use Illuminate\Http\Request;

class InventarisController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $statusFilter = $request->query('status');

        $query = Inventaris::with('category');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%")
                    ->orWhere('satuan', 'like', "%{$search}%");
            });
        }

        if ($statusFilter) {
            $query->where('status_stok', $statusFilter);
        }

        $inventarisList = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();
        $totalJenisBarang = Inventaris::count();
        $stokRendahCount = Inventaris::whereColumn('stok', '<=', 'minimum_stok')->count();
        $nextId = Inventaris::generateNextId();
        $categories = Category::all();

        return view('inventaris.index', compact(
            'inventarisList',
            'totalJenisBarang',
            'stokRendahCount',
            'nextId',
            'categories',
            'search',
            'statusFilter'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => ['nullable', 'string', 'max:20', 'unique:inventaris,id'],
            'nama_barang' => ['required', 'string', 'max:150'],
            'satuan' => ['required', 'string', 'max:50'],
            'stok' => ['required', 'numeric', 'min:0'],
            'minimum_stok' => ['required', 'numeric', 'min:0'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'satuan.required' => 'Satuan barang wajib diisi.',
            'stok.required' => 'Jumlah stok awal wajib diisi.',
            'stok.min' => 'Stok tidak boleh bernilai negatif.',
            'minimum_stok.required' => 'Minimum stok wajib diisi.',
        ]);

        $id = ($validated['id'] ?? null) ?: Inventaris::generateNextId();
        $validated['id'] = $id;
        $validated['sku'] = $id;
        $validated['purchase_price'] = $validated['purchase_price'] ?? 0;
        $validated['selling_price'] = $validated['selling_price'] ?? 0;

        // Auto compute status_stok
        if ($validated['stok'] <= 0) {
            $validated['status_stok'] = 'Kritis';
        } elseif ($validated['stok'] <= $validated['minimum_stok']) {
            $validated['status_stok'] = 'Perhatian';
        } else {
            $validated['status_stok'] = 'Aman';
        }

        Inventaris::create($validated);

        return redirect()->route('inventaris.index')
            ->with('success', "Barang {$validated['nama_barang']} ({$id}) berhasil ditambahkan ke inventaris!");
    }

    public function update(Request $request, string $id)
    {
        $item = Inventaris::findOrFail($id);

        $validated = $request->validate([
            'nama_barang' => ['required', 'string', 'max:150'],
            'satuan' => ['required', 'string', 'max:50'],
            'stok' => ['required', 'numeric', 'min:0'],
            'minimum_stok' => ['required', 'numeric', 'min:0'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'selling_price' => ['nullable', 'numeric', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
        ]);

        $validated['purchase_price'] = $validated['purchase_price'] ?? 0;
        $validated['selling_price'] = $validated['selling_price'] ?? 0;

        if ($validated['stok'] <= 0) {
            $validated['status_stok'] = 'Kritis';
        } elseif ($validated['stok'] <= $validated['minimum_stok']) {
            $validated['status_stok'] = 'Perhatian';
        } else {
            $validated['status_stok'] = 'Aman';
        }

        $item->update($validated);

        return redirect()->route('inventaris.index')
            ->with('success', "Barang {$item->nama_barang} berhasil diperbarui!");
    }

    public function destroy(string $id)
    {
        $item = Inventaris::findOrFail($id);

        // Periksa apakah sudah pernah dipakai di purchase_items atau pesanan_items
        if ($item->purchaseItems()->exists() || $item->pesananItems()->exists()) {
            // Gunakan soft delete sesuai petunjuk PDF 1
            $item->delete();
            return redirect()->route('inventaris.index')
                ->with('success', "Barang {$item->nama_barang} diarsipkan (soft delete) karena memiliki riwayat transaksi.");
        }

        $item->forceDelete();

        return redirect()->route('inventaris.index')
            ->with('success', "Barang {$item->nama_barang} berhasil dihapus permanen.");
    }
}
