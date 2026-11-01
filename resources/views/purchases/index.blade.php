@extends('layouts.app')

@section('title', 'Pembelian Stok')
@section('page_title', 'Pengadaan Stok / Stock-In')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-sky-100 text-[#0288d1] flex items-center justify-center text-xl font-bold shadow-inner">
                📥
            </div>
            <div>
                <h2 class="text-xl font-bold text-slate-800 tracking-tight">Pembelian Stok Bahan (Stock-In)</h2>
                <p class="text-xs text-slate-500">Pencatatan barang masuk dari pemasok/supplier (PDF 1 Requirement)</p>
            </div>
        </div>

        <a href="{{ route('purchases.create') }}" 
           class="px-4 py-2.5 rounded-xl bg-[#0288d1] hover:bg-[#0277bd] text-white text-xs font-semibold shadow-md shadow-sky-500/20 transition inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            <span>+ Catat Transaksi Pembelian</span>
        </a>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">TOTAL TRANSAKSI PEMBELIAN</span>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalPurchases }}</h3>
                <p class="text-xs text-slate-400 mt-1 font-medium">Faktur stok-in</p>
            </div>
            <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                📋
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">TOTAL PENGELUARAN PENGADAAN</span>
                <h3 class="text-2xl font-extrabold text-[#0288d1] mt-1">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                <p class="text-xs text-slate-400 mt-1 font-medium">Bahan operasional</p>
            </div>
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                💸
            </div>
        </div>
    </div>

    <!-- Table Purchases -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#0288d1]"></span>
                <h3 class="font-bold text-slate-800 text-sm">Riwayat Pembelian Stok</h3>
            </div>

            <form action="{{ route('purchases.index') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nomor/supplier..."
                       class="px-3.5 py-1.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-[#0288d1]">
                <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-medium text-slate-700">
                    Cari
                </button>
                @if($search)
                <a href="{{ route('purchases.index') }}" class="text-xs text-rose-500 hover:underline">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/75 text-slate-500 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">NO. TRANSAKSI</th>
                        <th class="py-3 px-4">SUPPLIER</th>
                        <th class="py-3 px-4">TGL BELI</th>
                        <th class="py-3 px-4">ITEM / BARANG</th>
                        <th class="py-3 px-4">TOTAL</th>
                        <th class="py-3 px-4">PETUGAS</th>
                        <th class="py-3 px-4 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($purchases as $pur)
                    <tr class="hover:bg-sky-50/30 transition">
                        <td class="py-3.5 px-4 font-mono font-bold text-sky-700">
                            {{ $pur->purchase_number }}
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-slate-800">
                            {{ $pur->supplier?->nama_supplier ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 font-mono">
                            {{ \Carbon\Carbon::parse($pur->purchased_at)->translatedFormat('d M Y') }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-600">
                            <span class="inline-block text-[11px] bg-slate-100 px-2 py-0.5 rounded font-mono">
                                {{ $pur->items->count() }} jenis barang
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-bold font-mono text-slate-800">
                            Rp {{ number_format($pur->total, 0, ',', '.') }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-500">
                            {{ $pur->user?->name ?? 'Staff' }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <a href="{{ route('purchases.show', $pur->id) }}"
                               class="px-2.5 py-1 rounded-lg border border-sky-400 text-sky-600 hover:bg-sky-50 font-medium transition inline-flex items-center gap-1 text-[11px]">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>Detail</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400 text-xs">
                            Belum ada riwayat transaksi pembelian stok.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($purchases->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $purchases->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
