@extends('layouts.app')

@section('title', 'Inventaris Barang')
@section('page_title', 'Master Data Inventaris & Stok')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-sky-100 text-[#0288d1] flex items-center justify-center text-xl font-bold shadow-inner">
            📦
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">Inventaris Barang</h2>
            <p class="text-xs text-slate-500">Kelola stok bahan dan perlengkapan laundry</p>
        </div>
    </div>

    <!-- Stat Cards (PDF 2 Slide 4) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">TOTAL JENIS BARANG</span>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalJenisBarang }}</h3>
                <p class="text-xs text-slate-400 mt-1 font-medium">Jenis item barang</p>
            </div>
            <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                📦
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">STOK RENDAH</span>
                <h3 class="text-3xl font-extrabold {{ $stokRendahCount > 0 ? 'text-amber-600' : 'text-emerald-600' }} mt-1">{{ $stokRendahCount }}</h3>
                <p class="text-xs text-slate-400 mt-1 font-medium">Perlu restok segera</p>
            </div>
            <div class="w-12 h-12 {{ $stokRendahCount > 0 ? 'bg-amber-50 text-amber-600' : 'bg-emerald-50 text-emerald-600' }} rounded-2xl flex items-center justify-center text-xl shadow-inner">
                {{ $stokRendahCount > 0 ? '⚠️' : '✓' }}
            </div>
        </div>
    </div>

    <!-- Form Tambah Barang Baru (PDF 2 Slide 4) -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <div class="flex items-center gap-2 mb-5 pb-3 border-b border-slate-100">
            <span class="w-7 h-7 rounded-xl bg-[#0288d1] text-white flex items-center justify-center text-xs font-bold">📦</span>
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Tambah Barang Baru</h3>
                <p class="text-[11px] text-slate-400">Tambahkan barang ke inventaris</p>
            </div>
        </div>

        <form action="{{ route('inventaris.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ID Barang *</label>
                    <input type="text" name="id" value="{{ old('id', $nextId) }}" readonly
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono font-bold text-sky-700 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Barang *</label>
                    <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" required
                           placeholder="Deterjen Cair"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Satuan *</label>
                    <input type="text" name="satuan" value="{{ old('satuan') }}" required
                           placeholder="liter / botol / pcs"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Stok Awal *</label>
                    <input type="number" name="stok" value="{{ old('stok', 0) }}" required min="0" step="any"
                           placeholder="0"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Minimum Stok *</label>
                    <input type="number" name="minimum_stok" value="{{ old('minimum_stok', 10) }}" required min="0" step="any"
                           placeholder="10"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori Barang</label>
                    <select name="category_id" class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-5 flex items-center gap-3">
                <button type="reset" class="px-5 py-2 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 text-xs font-medium transition">
                    Reset
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#0288d1] hover:bg-[#0277bd] text-white text-xs font-semibold shadow-md shadow-sky-500/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span>Simpan Barang</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Table Daftar Inventaris (PDF 2 Slide 5) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#0288d1]"></span>
                <h3 class="font-bold text-slate-800 text-sm">Daftar Inventaris</h3>
                <span class="text-xs text-slate-400 font-medium ml-2">({{ $inventarisList->total() }} Barang)</span>
            </div>

            <form action="{{ route('inventaris.index') }}" method="GET" class="flex items-center gap-2">
                <select name="status" class="px-3 py-1.5 text-xs rounded-xl border border-slate-200">
                    <option value="">Semua Status</option>
                    <option value="Aman" {{ $statusFilter == 'Aman' ? 'selected' : '' }}>Aman</option>
                    <option value="Perhatian" {{ $statusFilter == 'Perhatian' ? 'selected' : '' }}>Perhatian</option>
                    <option value="Kritis" {{ $statusFilter == 'Kritis' ? 'selected' : '' }}>Kritis</option>
                </select>
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari barang..."
                       class="px-3.5 py-1.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-[#0288d1]">
                <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-medium text-slate-700">
                    Filter
                </button>
                @if($search || $statusFilter)
                <a href="{{ route('inventaris.index') }}" class="text-xs text-rose-500 hover:underline">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/75 text-slate-500 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">ID</th>
                        <th class="py-3 px-4">NAMA BARANG</th>
                        <th class="py-3 px-4">STOK</th>
                        <th class="py-3 px-4">SATUAN</th>
                        <th class="py-3 px-4">STATUS STOK</th>
                        <th class="py-3 px-4 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($inventarisList as $item)
                    <tr class="hover:bg-sky-50/30 transition">
                        <td class="py-3.5 px-4 font-mono font-bold text-sky-700">{{ $item->id }}</td>
                        <td class="py-3.5 px-4 font-semibold text-slate-800">
                            {{ $item->nama_barang }}
                            @if($item->category)
                            <span class="block text-[10px] text-slate-400 font-normal">{{ $item->category->name }}</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 font-bold font-mono text-slate-800">{{ $item->stok }}</td>
                        <td class="py-3.5 px-4 text-slate-500">{{ $item->satuan }}</td>
                        <td class="py-3.5 px-4">
                            @if($item->status_stok === 'Aman')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                <span>✓</span> Aman
                            </span>
                            @elseif($item->status_stok === 'Perhatian')
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-700 border border-amber-200">
                                <span>!</span> Perhatian
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-100 text-rose-700 border border-rose-200">
                                <span>✕</span> Kritis
                            </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" 
                                        onclick="openEditInventaris('{{ $item->id }}', '{{ addslashes($item->nama_barang) }}', '{{ $item->satuan }}', '{{ $item->stok }}', '{{ $item->minimum_stok }}', '{{ $item->category_id }}')"
                                        class="px-2.5 py-1 rounded-lg border border-sky-400 text-sky-600 hover:bg-sky-50 font-medium transition inline-flex items-center gap-1 text-[11px]">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    <span>Edit</span>
                                </button>

                                <form action="{{ route('inventaris.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus barang {{ $item->nama_barang }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="px-2.5 py-1 rounded-lg bg-rose-500 hover:bg-rose-600 text-white font-medium transition inline-flex items-center gap-1 text-[11px]">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        <span>Hapus</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-400 text-xs">
                            Tidak ada data inventaris barang.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($inventarisList->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $inventarisList->links() }}
        </div>
        @endif
    </div>

</div>

<!-- EDIT INVENTARIS MODAL -->
<div id="editInvModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-sm">Edit Data Inventaris</h3>
            <button type="button" onclick="closeEditInv()" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
        </div>
        <form id="editInvForm" method="POST" class="mt-4 space-y-4">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ID Barang</label>
                    <input type="text" id="edit_inv_id" readonly class="w-full px-3 py-2 rounded-xl bg-slate-100 font-mono text-xs text-slate-600">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Satuan *</label>
                    <input type="text" id="edit_inv_satuan" name="satuan" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Barang *</label>
                <input type="text" id="edit_inv_nama" name="nama_barang" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Stok *</label>
                    <input type="number" id="edit_inv_stok" name="stok" required min="0" step="any" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Minimum Stok *</label>
                    <input type="number" id="edit_inv_min" name="minimum_stok" required min="0" step="any" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Kategori</label>
                <select id="edit_inv_cat" name="category_id" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs">
                    <option value="">-- Tanpa Kategori --</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeEditInv()" class="px-4 py-2 rounded-xl border text-xs text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-[#0288d1] text-white text-xs font-semibold hover:bg-[#0277bd]">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditInventaris(id, nama, satuan, stok, min, catId) {
        document.getElementById('edit_inv_id').value = id;
        document.getElementById('edit_inv_nama').value = nama;
        document.getElementById('edit_inv_satuan').value = satuan;
        document.getElementById('edit_inv_stok').value = stok;
        document.getElementById('edit_inv_min').value = min;
        document.getElementById('edit_inv_cat').value = catId || '';
        document.getElementById('editInvForm').action = "{{ url('/inventaris') }}/" + id;
        document.getElementById('editInvModal').classList.remove('hidden');
        document.getElementById('editInvModal').classList.add('flex');
    }

    function closeEditInv() {
        document.getElementById('editInvModal').classList.add('hidden');
        document.getElementById('editInvModal').classList.remove('flex');
    }
</script>
@endsection
