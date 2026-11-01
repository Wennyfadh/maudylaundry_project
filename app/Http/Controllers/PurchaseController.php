<?php

namespace App\Http\Controllers;

use App\Models\Inventaris;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Purchase::with(['supplier', 'user', 'items.inventaris']);
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                    ->orWhere('purchase_number', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($s) use ($search) {
                        $s->where('nama_supplier', 'like', "%{$search}%");
                    });
            });
        }

        $purchases = $query->orderBy('purchased_at', 'desc')->paginate(10)->withQueryString();
        $totalPurchases = Purchase::count();
        $totalPengeluaran = Purchase::where('status', 'completed')->sum('total');

        return view('purchases.index', compact('purchases', 'totalPurchases', 'totalPengeluaran', 'search'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('nama_supplier')->get();
        $inventaris = Inventaris::orderBy('nama_barang')->get();
        $nextId = Purchase::generateNextId();
        $purchaseNumber = 'PO-' . date('Ym') . '-' . sprintf('%03d', Purchase::count() + 1);

        return view('purchases.create', compact('suppliers', 'inventaris', 'nextId', 'purchaseNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'purchased_at' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.inventaris_id' => ['required', 'exists:inventaris,id'],
            'items.*.quantity' => ['required', 'numeric', 'min:0.01'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0'],
            'catatan' => ['nullable', 'string'],
        ], [
            'supplier_id.required' => 'Pilih supplier pemasok.',
            'items.required' => 'Tambahkan minimal satu barang pembelian.',
            'items.*.quantity.min' => 'Kuantitas barang harus lebih dari 0.',
            'items.*.unit_cost.min' => 'Harga satuan tidak boleh negatif.',
        ]);

        $purchaseId = Purchase::generateNextId();
        $purchaseNumber = 'PO-' . date('Ym') . '-' . sprintf('%03d', Purchase::count() + 1);

        DB::transaction(function () use ($request, $purchaseId, $purchaseNumber) {
            $total = 0;
            $itemsToInsert = [];

            foreach ($request->items as $itemData) {
                $qty = (float) $itemData['quantity'];
                $cost = (float) $itemData['unit_cost'];
                $subtotal = $qty * $cost;
                $total += $subtotal;

                // Lock inventaris record for update
                $inventaris = Inventaris::where('id', $itemData['inventaris_id'])->lockForUpdate()->firstOrFail();

                // Increment stock atomically
                $inventaris->stok += $qty;
                $inventaris->purchase_price = $cost; // update harga beli terkini
                $inventaris->updateStatusStok();

                $itemsToInsert[] = [
                    'inventaris_id' => $inventaris->id,
                    'quantity' => $qty,
                    'unit_cost' => $cost,
                    'subtotal' => $subtotal,
                ];
            }

            // Create purchase header
            $purchase = Purchase::create([
                'id' => $purchaseId,
                'supplier_id' => $request->supplier_id,
                'user_id' => Auth::id(),
                'purchase_number' => $purchaseNumber,
                'total' => $total,
                'status' => 'completed',
                'purchased_at' => $request->purchased_at,
                'catatan' => $request->catatan,
            ]);

            // Create items
            foreach ($itemsToInsert as $item) {
                $item['purchase_id'] = $purchase->id;
                PurchaseItem::create($item);
            }
        });

        return redirect()->route('purchases.index')
            ->with('success', "Transaksi pengadaan stok {$purchaseNumber} berhasil disimpan dan stok barang bertambah secara otomatis!");
    }

    public function show(string $id)
    {
        $purchase = Purchase::with(['supplier', 'user', 'items.inventaris'])->findOrFail($id);
        return view('purchases.show', compact('purchase'));
    }
}
