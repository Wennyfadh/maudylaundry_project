<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = Pegawai::with('user');
        if ($search) {
            $query->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('jabatan', 'like', "%{$search}%")
                ->orWhere('id', 'like', "%{$search}%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('email', 'like', "%{$search}%");
                });
        }

        $pegawais = $query->orderBy('id', 'asc')->paginate(10)->withQueryString();
        $totalPegawai = Pegawai::count();
        $nextId = Pegawai::generateNextId();

        return view('pegawai.index', compact('pegawais', 'totalPegawai', 'nextId', 'search'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => ['nullable', 'string', 'max:20', 'unique:pegawai,id'],
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'jabatan' => ['required', 'string', 'max:100'],
            'no_telp' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'nama_lengkap.required' => 'Nama lengkap pegawai wajib diisi.',
            'jabatan.required' => 'Jabatan pegawai wajib diisi.',
            'no_telp.required' => 'Nomor telepon pegawai wajib diisi.',
            'email.required' => 'Email login pegawai wajib diisi.',
            'email.unique' => 'Email sudah terdaftar untuk pengguna lain.',
            'password.required' => 'Password awal pegawai wajib diisi.',
            'password.min' => 'Password minimal 6 karakter.',
        ]);

        $pegawaiId = ($validated['id'] ?? null) ?: Pegawai::generateNextId();

        DB::transaction(function () use ($validated, $pegawaiId) {
            $user = User::create([
                'name' => $validated['nama_lengkap'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'staff',
            ]);

            Pegawai::create([
                'id' => $pegawaiId,
                'user_id' => $user->id,
                'nama_lengkap' => $validated['nama_lengkap'],
                'jabatan' => $validated['jabatan'],
                'no_telp' => $validated['no_telp'],
            ]);
        });

        return redirect()->route('pegawai.index')
            ->with('success', "Pegawai {$validated['nama_lengkap']} ({$pegawaiId}) berhasil didaftarkan dan dapat langsung login menggunakan email {$validated['email']}.");
    }

    public function update(Request $request, string $id)
    {
        $pegawai = Pegawai::with('user')->findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'jabatan' => ['required', 'string', 'max:100'],
            'no_telp' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($pegawai->user_id)],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        DB::transaction(function () use ($pegawai, $validated) {
            $pegawai->update([
                'nama_lengkap' => $validated['nama_lengkap'],
                'jabatan' => $validated['jabatan'],
                'no_telp' => $validated['no_telp'],
            ]);

            if ($pegawai->user) {
                $userUpdates = [
                    'name' => $validated['nama_lengkap'],
                    'email' => $validated['email'],
                ];
                if (!empty($validated['password'])) {
                    $userUpdates['password'] = Hash::make($validated['password']);
                }
                $pegawai->user->update($userUpdates);
            }
        });

        return redirect()->route('pegawai.index')
            ->with('success', "Data pegawai {$pegawai->nama_lengkap} berhasil diperbarui.");
    }

    public function destroy(string $id)
    {
        $pegawai = Pegawai::with('user')->findOrFail($id);

        // Jangan hapus akun jika owner sedang login sebagai akun tersebut
        if ($pegawai->user_id === auth()->id()) {
            return redirect()->route('pegawai.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        DB::transaction(function () use ($pegawai) {
            // Lepaskan relasi penanganan pesanan dan pembayaran agar riwayat audit transaksi tetap aman
            Pesanan::where('pegawai_id', $pegawai->id)->update(['pegawai_id' => null]);
            Pembayaran::where('pegawai_id', $pegawai->id)->update(['pegawai_id' => null]);

            $user = $pegawai->user;
            $pegawai->delete();
            if ($user && $user->role === 'staff') {
                $user->delete();
            }
        });

        return redirect()->route('pegawai.index')
            ->with('success', "Pegawai {$pegawai->nama_lengkap} beserta akun loginnya berhasil dihapus.");
    }
}
