@extends('layouts.app')

@section('title', 'Catat Pembelian Stok')
@section('page_title', 'Form Pengadaan Stok (Stock-In)')

@section('content')
<div class="space-y-6 max-w-4xl">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-sky-100 text-[#0288d1] flex items-center justify-center text-xl font-bold shadow-inner">
                📥
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight">Transaksi Pembelian Stok Baru</h2>
                <p class="text-xs text-slate-500">Stok inventaris bertambah otomatis setelah transaksi disimpan (Atomik)</p>
            </div>
        </div>
        <a href="{{ route('purchases.index') }}" class="text-xs text-slate-600 hover:text-slate-800 font-medium">
            ← Kembali ke Riwayat
        </a>
    </div>

    <form action="{{ route('purchases.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Card Header Info -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-4">
            <h3 class="font-bold text-slate-800 text-sm border-b border-slate-100 pb-2">Informasi Pembelian</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Transaksi (PO)</label>
                    <input type="text" value="{{ $purchaseNumber }}" readonly
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono font-bold text-sky-700 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Supplier Pemasok *</label>
                    <select name="supplier_id" required class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:border-[#0288d1]">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $s)
                        <option value="{{ $s->id }}">{{ $s->nama_supplier }} ({{ $s->no_telp }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal Pembelian *</label>
                    <input type="date" name="purchased_at" value="{{ now()->toDateString() }}" required
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs font-mono focus:border-[#0288d1]">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan Tambahan</label>
                <input type="text" name="catatan" placeholder="Contoh: Pengadaan rutin bulanan sabun dan softener"
                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:border-[#0288d1]">
            </div>
        </div>

        <!-- Card Items Pembelian -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-slate-800 text-sm">Daftar Barang yang Dibeli</h3>
                <button type="button" onclick="addItemRow()" class="px-3 py-1.5 rounded-xl bg-sky-50 text-sky-600 hover:bg-sky-100 text-xs font-semibold transition">
                    + Tambah Baris Barang
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs" id="itemsTable">
                    <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[11px]">
                        <tr>
                            <th class="py-2.5 px-3">Pilih Barang Inventaris</th>
                            <th class="py-2.5 px-3 w-28">Kuantitas</th>
                            <th class="py-2.5 px-3 w-36">Harga Beli Satuan (Rp)</th>
                            <th class="py-2.5 px-3 w-36 text-right">Subtotal</th>
                            <th class="py-2.5 px-3 w-12 text-center">Hapus</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody" class="divide-y divide-slate-100">
                        <tr class="item-row">
                            <td class="py-2.5 px-3">
                                <select name="items[0][inventaris_id]" required onchange="handleItemSelect(this, 0)" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs">
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($inventaris as $inv)
                                    <option value="{{ $inv->id }}" data-cost="{{ $inv->purchase_price }}" data-satuan="{{ $inv->satuan }}">
                                        {{ $inv->id }} - {{ $inv->nama_barang }} (Satuan: {{ $inv->satuan }})
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="py-2.5 px-3">
                                <input type="number" name="items[0][quantity]" value="1" required min="0.01" step="any" oninput="calculateTotal()" class="row-qty w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono">
                            </td>
                            <td class="py-2.5 px-3">
                                <input type="number" name="items[0][unit_cost]" value="0" required min="0" step="any" oninput="calculateTotal()" class="row-cost w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono">
                            </td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-800 row-subtotal">
                                Rp 0
                            </td>
                            <td class="py-2.5 px-3 text-center">
                                <button type="button" onclick="removeItemRow(this)" class="text-rose-500 hover:text-rose-700 font-bold text-base">&times;</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Total Bar -->
            <div class="mt-4 p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                <div>
                    <span class="text-xs text-slate-600 font-medium">Total Nilai Pembelian (Server Verified):</span>
                </div>
                <div class="text-right">
                    <span id="grandTotal" class="text-2xl font-extrabold text-[#0288d1] font-mono">Rp 0</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('purchases.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 text-xs font-medium">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-[#0288d1] hover:bg-[#0277bd] text-white text-xs font-semibold shadow-md shadow-sky-500/20 transition">
                Simpan & Tambah Stok Barang
            </button>
        </div>
    </form>

</div>

<script>
    let rowIndex = 1;
    const inventarisOptions = `@foreach($inventaris as $inv)<option value="{{ $inv->id }}" data-cost="{{ $inv->purchase_price }}" data-satuan="{{ $inv->satuan }}">{{ $inv->id }} - {{ $inv->nama_barang }} ({{ $inv->satuan }})</option>@endforeach`;

    function addItemRow() {
        const tbody = document.getElementById('itemsBody');
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.innerHTML = `
            <td class="py-2.5 px-3">
                <select name="items[${rowIndex}][inventaris_id]" required onchange="handleItemSelect(this, ${rowIndex})" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs">
                    <option value="">-- Pilih Barang --</option>
                    ${inventarisOptions}
                </select>
            </td>
            <td class="py-2.5 px-3">
                <input type="number" name="items[${rowIndex}][quantity]" value="1" required min="0.01" step="any" oninput="calculateTotal()" class="row-qty w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono">
            </td>
            <td class="py-2.5 px-3">
                <input type="number" name="items[${rowIndex}][unit_cost]" value="0" required min="0" step="any" oninput="calculateTotal()" class="row-cost w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono">
            </td>
            <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-800 row-subtotal">
                Rp 0
            </td>
            <td class="py-2.5 px-3 text-center">
                <button type="button" onclick="removeItemRow(this)" class="text-rose-500 hover:text-rose-700 font-bold text-base">&times;</button>
            </td>
        `;
        tbody.appendChild(tr);
        rowIndex++;
        calculateTotal();
    }

    function removeItemRow(btn) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length > 1) {
            btn.closest('tr').remove();
            calculateTotal();
        } else {
            alert('Minimal satu barang pembelian harus tersedia.');
        }
    }

    function handleItemSelect(select, idx) {
        const option = select.options[select.selectedIndex];
        const cost = option.getAttribute('data-cost') || 0;
        const row = select.closest('tr');
        row.querySelector('.row-cost').value = cost;
        calculateTotal();
    }

    function calculateTotal() {
        let total = 0;
        document.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(row.querySelector('.row-qty').value || 0);
            const cost = parseFloat(row.querySelector('.row-cost').value || 0);
            const subtotal = qty * cost;
            row.querySelector('.row-subtotal').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
            total += subtotal;
        });
        document.getElementById('grandTotal').innerText = 'Rp ' + total.toLocaleString('id-ID');
    }

    document.addEventListener('DOMContentLoaded', calculateTotal);
</script>
@endsection
