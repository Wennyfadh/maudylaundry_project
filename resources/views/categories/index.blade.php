@extends('layouts.app')

@section('title', 'Kategori Barang')
@section('page_title', 'Master Data Kategori')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-sky-100 text-[#0288d1] flex items-center justify-center text-xl font-bold shadow-inner">
            🏷️
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">Kategori Inventaris</h2>
            <p class="text-xs text-slate-500">Klasifikasi jenis barang operasional laundry (PDF 1 Requirement)</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Form Tambah Kategori -->
        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm h-fit">
            <h3 class="font-bold text-slate-800 text-sm mb-4 pb-2 border-b border-slate-100">Tambah Kategori Baru</h3>
            <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Kategori *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           placeholder="Contoh: Pewangi & Pelembut"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:border-[#0288d1]">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Deskripsi Singkat</label>
                    <textarea name="description" rows="3" placeholder="Keterangan kategori..."
                              class="w-full px-3.5 py-2 rounded-xl border border-slate-200 text-xs focus:border-[#0288d1]"></textarea>
                </div>
                <button type="submit" class="w-full py-2.5 rounded-xl bg-[#0288d1] hover:bg-[#0277bd] text-white text-xs font-semibold shadow-md shadow-sky-500/20 transition">
                    Simpan Kategori
                </button>
            </form>
        </div>

        <!-- Table Kategori -->
        <div class="md:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100">
                <h3 class="font-bold text-slate-800 text-sm">Daftar Kategori</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 text-slate-500 font-semibold uppercase text-[11px]">
                        <tr>
                            <th class="py-3 px-4">NAMA KATEGORI</th>
                            <th class="py-3 px-4">DESKRIPSI</th>
                            <th class="py-3 px-4 text-center">JUMLAH BARANG</th>
                            <th class="py-3 px-4 text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($categories as $cat)
                        <tr class="hover:bg-sky-50/30 transition">
                            <td class="py-3.5 px-4 font-bold text-slate-800">{{ $cat->name }}</td>
                            <td class="py-3.5 px-4 text-slate-500 max-w-xs">{{ $cat->description ?? '-' }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-2 py-0.5 rounded text-[11px] font-mono bg-sky-50 text-sky-700 font-bold">
                                    {{ $cat->inventaris_count }} barang
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Hapus kategori {{ $cat->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 rounded-lg bg-rose-500 hover:bg-rose-600 text-white font-medium transition text-[11px]">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400 text-xs">Belum ada kategori.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
