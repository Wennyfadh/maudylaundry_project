@extends('layouts.app')

@section('title', 'Data Supplier')
@section('page_title', 'Master Data Pemasok (Supplier)')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-sky-100 text-[#0288d1] flex items-center justify-center text-xl font-bold shadow-inner">
            🏢
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">Data Pemasok / Supplier</h2>
            <p class="text-xs text-slate-500">Kelola informasi mitra distributor bahan laundry (PDF 1 Requirement)</p>
        </div>
    </div>

    <!-- Form Tambah Supplier -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <div class="flex items-center gap-2 mb-5 pb-3 border-b border-slate-100">
            <span class="w-7 h-7 rounded-xl bg-[#0288d1] text-white flex items-center justify-center text-xs font-bold">🏢</span>
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Tambah Supplier Baru</h3>
                <p class="text-[11px] text-slate-400">Daftarkan supplier bahan baku laundry</p>
            </div>
        </div>

        <form action="{{ route('suppliers.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Supplier *</label>
                    <input type="text" name="nama_supplier" value="{{ old('nama_supplier') }}" required
                           placeholder="Contoh: CV Berkah Kimia Laundry"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">No. Kontak / Telp *</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp') }}" required
                           placeholder="08123456789"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           placeholder="supplier@email.com"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat Supplier</label>
                <input type="text" name="alamat" value="{{ old('alamat') }}" placeholder="Alamat kantor / gudang supplier"
                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs">
            </div>

            <div class="mt-5 flex items-center gap-3">
                <button type="reset" class="px-5 py-2 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 text-xs font-medium transition">
                    Reset
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#0288d1] hover:bg-[#0277bd] text-white text-xs font-semibold shadow-md shadow-sky-500/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span>Simpan Supplier</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Table Daftar Supplier -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#0288d1]"></span>
                <h3 class="font-bold text-slate-800 text-sm">Daftar Supplier</h3>
                <span class="text-xs text-slate-400 font-medium ml-2">({{ $suppliers->total() }} Supplier)</span>
            </div>

            <form action="{{ route('suppliers.index') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari supplier..."
                       class="px-3.5 py-1.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-[#0288d1]">
                <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-medium text-slate-700">
                    Cari
                </button>
                @if($search)
                <a href="{{ route('suppliers.index') }}" class="text-xs text-rose-500 hover:underline">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/75 text-slate-500 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">NAMA SUPPLIER</th>
                        <th class="py-3 px-4">NO KONTAK</th>
                        <th class="py-3 px-4">EMAIL</th>
                        <th class="py-3 px-4">ALAMAT</th>
                        <th class="py-3 px-4">TRANSAKSI</th>
                        <th class="py-3 px-4 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($suppliers as $sup)
                    <tr class="hover:bg-sky-50/30 transition">
                        <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $sup->nama_supplier }}</td>
                        <td class="py-3.5 px-4 font-mono text-slate-600">{{ $sup->no_telp }}</td>
                        <td class="py-3.5 px-4 text-slate-500">{{ $sup->email ?? '-' }}</td>
                        <td class="py-3.5 px-4 text-slate-500 max-w-xs truncate">{{ $sup->alamat ?? '-' }}</td>
                        <td class="py-3.5 px-4">
                            <span class="inline-block px-2 py-0.5 rounded text-[11px] font-mono bg-sky-50 text-sky-700 font-bold">
                                {{ $sup->purchases_count }} faktur
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <form action="{{ route('suppliers.destroy', $sup->id) }}" method="POST" onsubmit="return confirm('Hapus supplier {{ $sup->nama_supplier }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-500 hover:bg-rose-600 text-white font-medium transition text-[11px]">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                            Belum ada data supplier pemasok.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($suppliers->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $suppliers->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
