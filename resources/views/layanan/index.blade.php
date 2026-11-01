@extends('layouts.app')

@section('title', 'Data Layanan')
@section('page_title', 'Master Data Layanan & Tarif')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-2xl bg-sky-100 text-[#0288d1] flex items-center justify-center text-xl font-bold shadow-inner">
            ✨
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">Data Layanan</h2>
            <p class="text-xs text-slate-500">Kelola jenis dan tarif harga layanan laundry</p>
        </div>
    </div>

    <!-- Form Tambah Layanan Baru (PDF 2 Slide 3) -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <div class="flex items-center gap-2 mb-5 pb-3 border-b border-slate-100">
            <span class="w-7 h-7 rounded-xl bg-[#0288d1] text-white flex items-center justify-center text-xs font-bold">✨</span>
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Tambah Layanan Baru</h3>
                <p class="text-[11px] text-slate-400">Daftarkan jenis paket layanan laundry</p>
            </div>
        </div>

        <form action="{{ route('layanan.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ID Layanan *</label>
                    <input type="text" name="id" value="{{ old('id', $nextId) }}" readonly
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono font-bold text-sky-700 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Layanan *</label>
                    <input type="text" name="nama_layanan" value="{{ old('nama_layanan') }}" required
                           placeholder="Contoh: Cuci Reguler"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Harga per Kg (Rp) *</label>
                    <input type="number" name="harga_per_kg" value="{{ old('harga_per_kg') }}" required min="0" step="500"
                           placeholder="5000"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs font-mono">
                </div>
            </div>

            <div class="mt-4 max-w-xs">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Estimasi Waktu (Hari) *</label>
                <input type="number" name="estimasi_waktu_hari" value="{{ old('estimasi_waktu_hari', 2) }}" required min="1"
                       placeholder="2"
                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs font-mono">
            </div>

            <div class="mt-5 flex items-center gap-3">
                <button type="reset" class="px-5 py-2 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 text-xs font-medium transition">
                    Reset
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#0288d1] hover:bg-[#0277bd] text-white text-xs font-semibold shadow-md shadow-sky-500/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    <span>Simpan Layanan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Table Daftar Layanan (PDF 2 Slide 4) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#0288d1]"></span>
                <h3 class="font-bold text-slate-800 text-sm">Daftar Layanan</h3>
                <span class="text-xs text-slate-400 font-medium ml-2">({{ $layanans->total() }} Layanan)</span>
            </div>

            <form action="{{ route('layanan.index') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari layanan..."
                       class="px-3.5 py-1.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-[#0288d1]">
                <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-medium text-slate-700">
                    Cari
                </button>
                @if($search)
                <a href="{{ route('layanan.index') }}" class="text-xs text-rose-500 hover:underline">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/75 text-slate-500 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">ID</th>
                        <th class="py-3 px-4">NAMA LAYANAN</th>
                        <th class="py-3 px-4">HARGA/KG</th>
                        <th class="py-3 px-4">ESTIMASI</th>
                        <th class="py-3 px-4 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($layanans as $ly)
                    <tr class="hover:bg-sky-50/30 transition">
                        <td class="py-3.5 px-4 font-mono font-bold text-sky-700">{{ $ly->id }}</td>
                        <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $ly->nama_layanan }}</td>
                        <td class="py-3.5 px-4 font-mono font-medium text-slate-700">Rp {{ number_format($ly->harga_per_kg, 0, ',', '.') }}/kg</td>
                        <td class="py-3.5 px-4 text-slate-600 font-medium">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] bg-slate-100 font-mono text-slate-700">
                                {{ $ly->estimasi_waktu_hari }} hari
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" 
                                        onclick="openEditLayanan('{{ $ly->id }}', '{{ addslashes($ly->nama_layanan) }}', '{{ $ly->harga_per_kg }}', '{{ $ly->estimasi_waktu_hari }}')"
                                        class="px-2.5 py-1 rounded-lg border border-sky-400 text-sky-600 hover:bg-sky-50 font-medium transition inline-flex items-center gap-1 text-[11px]">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    <span>Edit</span>
                                </button>

                                <form action="{{ route('layanan.destroy', $ly->id) }}" method="POST" onsubmit="return confirm('Hapus layanan {{ $ly->nama_layanan }}?');">
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
                        <td colspan="5" class="py-8 text-center text-slate-400 text-xs">
                            Tidak ada data layanan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($layanans->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $layanans->links() }}
        </div>
        @endif
    </div>

</div>

<!-- EDIT LAYANAN MODAL -->
<div id="editLayananModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-sm">Edit Data Layanan</h3>
            <button type="button" onclick="closeEditLayanan()" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
        </div>
        <form id="editLayananForm" method="POST" class="mt-4 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">ID Layanan</label>
                <input type="text" id="edit_ly_id" readonly class="w-full px-3 py-2 rounded-xl bg-slate-100 font-mono text-xs text-slate-600">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Layanan *</label>
                <input type="text" id="edit_ly_nama" name="nama_layanan" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Harga per Kg (Rp) *</label>
                <input type="number" id="edit_ly_harga" name="harga_per_kg" required min="0" step="500" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Estimasi Waktu (Hari) *</label>
                <input type="number" id="edit_ly_estimasi" name="estimasi_waktu_hari" required min="1" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs font-mono">
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeEditLayanan()" class="px-4 py-2 rounded-xl border text-xs text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-[#0288d1] text-white text-xs font-semibold hover:bg-[#0277bd]">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditLayanan(id, nama, harga, estimasi) {
        document.getElementById('edit_ly_id').value = id;
        document.getElementById('edit_ly_nama').value = nama;
        document.getElementById('edit_ly_harga').value = harga;
        document.getElementById('edit_ly_estimasi').value = estimasi;
        document.getElementById('editLayananForm').action = "{{ url('/layanan') }}/" + id;
        document.getElementById('editLayananModal').classList.remove('hidden');
        document.getElementById('editLayananModal').classList.add('flex');
    }

    function closeEditLayanan() {
        document.getElementById('editLayananModal').classList.add('hidden');
        document.getElementById('editLayananModal').classList.remove('flex');
    }
</script>
@endsection
