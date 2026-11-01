@extends('layouts.app')

@section('title', 'Detail Pembelian ' . $purchase->purchase_number)
@section('page_title', 'Detail Pengadaan Stok')

@section('content')
<div class="space-y-6 max-w-4xl">

    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-sky-100 text-[#0288d1] flex items-center justify-center text-xl font-bold shadow-inner">
                📥
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight">Faktur Pembelian: {{ $purchase->purchase_number }}</h2>
                <p class="text-xs text-slate-500">Dicatat pada {{ \Carbon\Carbon::parse($purchase->purchased_at)->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition">
                🖨️ Cetak Faktur
            </button>
            <a href="{{ route('purchases.index') }}" class="px-3.5 py-2 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 text-xs font-medium">
                Kembali
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pb-6 border-b border-slate-100">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">SUPPLIER</span>
                <h4 class="font-bold text-slate-800 text-base mt-1">{{ $purchase->supplier?->nama_supplier }}</h4>
                <p class="text-xs text-slate-500">{{ $purchase->supplier?->no_telp }}</p>
                <p class="text-xs text-slate-500">{{ $purchase->supplier?->alamat ?? '-' }}</p>
            </div>
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">INFORMASI TRANSAKSI</span>
                <p class="text-xs text-slate-700 mt-1">Petugas: <strong>{{ $purchase->user?->name }}</strong></p>
                <p class="text-xs text-slate-700">Status: <span class="text-emerald-700 font-bold bg-emerald-100 px-2 py-0.5 rounded text-[10px]">SELESAI</span></p>
                <p class="text-xs text-slate-700">Catatan: {{ $purchase->catatan ?? '-' }}</p>
            </div>
            <div class="text-right">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">TOTAL NILAI PEMBELIAN</span>
                <h3 class="text-2xl font-extrabold text-[#0288d1] font-mono mt-1">Rp {{ number_format($purchase->total, 0, ',', '.') }}</h3>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[11px]">
                    <tr>
                        <th class="py-3 px-4">KODE</th>
                        <th class="py-3 px-4">NAMA BARANG</th>
                        <th class="py-3 px-4 text-center">KUANTITAS</th>
                        <th class="py-3 px-4 text-right">HARGA BELI SATUAN</th>
                        <th class="py-3 px-4 text-right">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($purchase->items as $item)
                    <tr>
                        <td class="py-3 px-4 font-mono font-bold text-sky-700">{{ $item->inventaris_id }}</td>
                        <td class="py-3 px-4 font-semibold text-slate-800">{{ $item->inventaris?->nama_barang }}</td>
                        <td class="py-3 px-4 text-center font-mono font-bold">{{ $item->quantity }} {{ $item->inventaris?->satuan }}</td>
                        <td class="py-3 px-4 text-right font-mono">Rp {{ number_format($item->unit_cost, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-right font-mono font-bold text-slate-800">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
