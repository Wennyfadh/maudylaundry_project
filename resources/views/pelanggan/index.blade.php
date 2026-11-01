@extends('layouts.app')

@section('title', 'Data Pelanggan')
@section('page_title', 'Master Data Pelanggan')

@section('content')
<div class="space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-2xl bg-sky-100 text-[#0288d1] flex items-center justify-center text-xl font-bold shadow-inner">
                    👥
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-800 tracking-tight">Data Pelanggan</h2>
                    <p class="text-xs text-slate-500">Kelola data master pelanggan Maudy Laundry</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Stat Cards (PDF 2 Slide 1) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">TOTAL PELANGGAN</span>
                <h3 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $totalPelanggan }}</h3>
                <p class="text-xs text-slate-400 mt-1 font-medium">Pelanggan terdaftar</p>
            </div>
            <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                👥
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center justify-between">
            <div>
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">ID SELANJUTNYA</span>
                <h3 class="text-3xl font-extrabold text-[#0288d1] font-mono mt-1">{{ $nextId }}</h3>
                <p class="text-xs text-slate-400 mt-1 font-medium">Otomatis generate</p>
            </div>
            <div class="w-12 h-12 bg-cyan-50 text-cyan-600 rounded-2xl flex items-center justify-center text-xl shadow-inner">
                🆔
            </div>
        </div>
    </div>

    <!-- Form Daftarkan Pelanggan Baru (PDF 2 Slide 1) -->
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
        <div class="flex items-center gap-2 mb-5 pb-3 border-b border-slate-100">
            <span class="w-7 h-7 rounded-xl bg-[#0288d1] text-white flex items-center justify-center text-xs font-bold">👤</span>
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Daftarkan Pelanggan Baru</h3>
                <p class="text-[11px] text-slate-400">Masukkan data pelanggan laundry</p>
            </div>
        </div>

        <form action="{{ route('pelanggan.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">ID Pelanggan *</label>
                    <input type="text" name="id" value="{{ old('id', $nextId) }}" readonly
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 bg-slate-50 font-mono font-bold text-sky-700 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                           placeholder="Nama pelanggan"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">No Telp *</label>
                    <input type="text" name="no_telp" value="{{ old('no_telp') }}" required
                           placeholder="08xxxxxxxxxx"
                           class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs">
                </div>
            </div>

            <div class="mt-4">
                <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat</label>
                <input type="text" name="alamat" value="{{ old('alamat') }}"
                       placeholder="Alamat lengkap pelanggan"
                       class="w-full px-3.5 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-sky-500/20 focus:border-[#0288d1] text-xs">
            </div>

            <div class="mt-5 flex items-center gap-3">
                <button type="reset" class="px-5 py-2 rounded-xl border border-slate-300 text-slate-600 hover:bg-slate-50 text-xs font-medium transition">
                    Reset
                </button>
                <button type="submit" class="px-5 py-2 rounded-xl bg-[#0288d1] hover:bg-[#0277bd] text-white text-xs font-semibold shadow-md shadow-sky-500/20 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Simpan Pelanggan</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Table Daftar Pelanggan (PDF 2 Slide 2) -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#0288d1]"></span>
                <h3 class="font-bold text-slate-800 text-sm">Daftar Pelanggan</h3>
                <span class="text-xs text-slate-400 font-medium ml-2">({{ $pelanggans->total() }} Data)</span>
            </div>

            <!-- Search box -->
            <form action="{{ route('pelanggan.index') }}" method="GET" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama / no telp..."
                       class="px-3.5 py-1.5 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-[#0288d1]">
                <button type="submit" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-medium text-slate-700">
                    Cari
                </button>
                @if($search)
                <a href="{{ route('pelanggan.index') }}" class="text-xs text-rose-500 hover:underline">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/75 text-slate-500 font-semibold uppercase text-[11px] tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">ID</th>
                        <th class="py-3 px-4">NAMA</th>
                        <th class="py-3 px-4">NO TELP</th>
                        <th class="py-3 px-4">ALAMAT</th>
                        <th class="py-3 px-4 text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pelanggans as $p)
                    <tr class="hover:bg-sky-50/30 transition">
                        <td class="py-3.5 px-4 font-mono font-bold text-sky-700">{{ $p->id }}</td>
                        <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $p->nama_lengkap }}</td>
                        <td class="py-3.5 px-4 text-slate-600 font-mono">{{ $p->no_telp }}</td>
                        <td class="py-3.5 px-4 text-slate-500 max-w-xs truncate">{{ $p->alamat ?? '-' }}</td>
                        <td class="py-3.5 px-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Button Edit Modal -->
                                <button type="button" 
                                        onclick="openEditModal('{{ $p->id }}', '{{ addslashes($p->nama_lengkap) }}', '{{ $p->no_telp }}', '{{ addslashes($p->alamat) }}')"
                                        class="px-2.5 py-1 rounded-lg border border-sky-400 text-sky-600 hover:bg-sky-50 font-medium transition inline-flex items-center gap-1 text-[11px]">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    <span>Edit</span>
                                </button>

                                <!-- Button Hapus -->
                                <form action="{{ route('pelanggan.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan {{ $p->nama_lengkap }}?');">
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
                            Tidak ada data pelanggan yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pelanggans->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $pelanggans->links() }}
        </div>
        @endif
    </div>

</div>

<!-- EDIT MODAL -->
<div id="editModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-xs hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800 text-sm">Edit Data Pelanggan</h3>
            <button type="button" onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
        </div>
        <form id="editForm" method="POST" class="mt-4 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">ID Pelanggan</label>
                <input type="text" id="edit_id" readonly class="w-full px-3 py-2 rounded-xl bg-slate-100 font-mono text-xs text-slate-600">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                <input type="text" id="edit_nama" name="nama_lengkap" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:border-[#0288d1]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">No Telp *</label>
                <input type="text" id="edit_telp" name="no_telp" required class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:border-[#0288d1]">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">Alamat</label>
                <textarea id="edit_alamat" name="alamat" rows="2" class="w-full px-3 py-2 rounded-xl border border-slate-200 text-xs focus:border-[#0288d1]"></textarea>
            </div>
            <div class="flex justify-end gap-2 pt-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded-xl border text-xs text-slate-600 hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-4 py-2 rounded-xl bg-[#0288d1] text-white text-xs font-semibold hover:bg-[#0277bd]">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditModal(id, nama, telp, alamat) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nama').value = nama;
        document.getElementById('edit_telp').value = telp;
        document.getElementById('edit_alamat').value = alamat;
        document.getElementById('editForm').action = "{{ url('/pelanggan') }}/" + id;
        document.getElementById('editModal').classList.remove('hidden');
        document.getElementById('editModal').classList.add('flex');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }
</script>
@endsection
