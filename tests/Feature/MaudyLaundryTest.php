<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Inventaris;
use App\Models\Layanan;
use App\Models\Pegawai;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MaudyLaundryTest extends TestCase
{
    use RefreshDatabase;

    protected User $owner;
    protected User $staff;
    protected Layanan $layanan;
    protected Pelanggan $pelanggan;
    protected Inventaris $deterjen;
    protected Inventaris $plastik;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create Owner User & Pegawai
        $this->owner = User::create([
            'name' => 'Siti Maudy',
            'email' => 'owner@maudylaundry.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);
        Pegawai::create([
            'id' => 'PG01',
            'user_id' => $this->owner->id,
            'nama_lengkap' => 'Siti Maudy',
            'jabatan' => 'Pemilik',
            'no_telp' => '081200001111',
        ]);

        // 2. Create Staff User & Pegawai
        $this->staff = User::create([
            'name' => 'Rahmat Hidayat',
            'email' => 'staff1@maudylaundry.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);
        Pegawai::create([
            'id' => 'PG02',
            'user_id' => $this->staff->id,
            'nama_lengkap' => 'Rahmat Hidayat',
            'jabatan' => 'Operator Cuci',
            'no_telp' => '081200002222',
        ]);

        // 3. Create Pelanggan
        $this->pelanggan = Pelanggan::create([
            'id' => 'PL01',
            'nama_lengkap' => 'Budi Hartono',
            'no_telp' => '082100001111',
            'alamat' => 'Jl. Mawar No. 12 Malang',
        ]);

        // 4. Create Layanan
        $this->layanan = Layanan::create([
            'id' => 'LY01',
            'nama_layanan' => 'Cuci Reguler',
            'harga_per_kg' => 5000,
            'estimasi_waktu_hari' => 2,
        ]);

        // 5. Create Inventaris
        $this->deterjen = Inventaris::create([
            'id' => 'INV01',
            'nama_barang' => 'Deterjen Cair',
            'satuan' => 'liter',
            'stok' => 50,
            'minimum_stok' => 10,
            'status_stok' => 'Aman',
            'purchase_price' => 15000,
            'selling_price' => 20000,
        ]);

        $this->plastik = Inventaris::create([
            'id' => 'INV03',
            'nama_barang' => 'Plastik Kemasan',
            'satuan' => 'lembar',
            'stok' => 200,
            'minimum_stok' => 50,
            'status_stok' => 'Aman',
            'purchase_price' => 500,
            'selling_price' => 1000,
        ]);
    }

    /**
     * Test 1: Guest tidak dapat membuka dashboard (Auth Guard)
     */
    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    /**
     * Test 2: Login berhasil dengan kredensial yang valid
     */
    public function test_user_can_login_with_valid_credentials()
    {
        $response = $this->post('/login', [
            'email' => 'owner@maudylaundry.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($this->owner);
    }

    /**
     * Test 3: Password salah ditolak
     */
    public function test_login_with_invalid_password_is_rejected()
    {
        $response = $this->post('/login', [
            'email' => 'owner@maudylaundry.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test 4: RBAC Server-Side - Staff tidak dapat mengakses kelola pegawai (403 Forbidden)
     */
    public function test_staff_cannot_access_pegawai_management()
    {
        $response = $this->actingAs($this->staff)->get('/pegawai');
        $response->assertStatus(403);
    }

    /**
     * Test 5: RBAC Server-Side - Owner dapat mengakses kelola pegawai
     */
    public function test_owner_can_access_pegawai_management()
    {
        $response = $this->actingAs($this->owner)->get('/pegawai');
        $response->assertStatus(200);
    }

    /**
     * Test 6: Owner menambah data pegawai baru otomatis membuat akun User staff
     */
    public function test_owner_can_create_new_pegawai_and_user_account()
    {
        $response = $this->actingAs($this->owner)->post('/pegawai', [
            'id' => 'PG06',
            'nama_lengkap' => 'Dewi Safitri',
            'jabatan' => 'Kasir Sore',
            'no_telp' => '081233445566',
            'email' => 'dewi.safitri@maudylaundry.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/pegawai');

        // Pastikan tersimpan di users dan pegawai
        $this->assertDatabaseHas('users', [
            'email' => 'dewi.safitri@maudylaundry.com',
            'role' => 'staff',
        ]);
        $this->assertDatabaseHas('pegawai', [
            'id' => 'PG06',
            'nama_lengkap' => 'Dewi Safitri',
            'jabatan' => 'Kasir Sore',
        ]);
    }

    /**
     * Test 7: Transaksi Pengadaan Stok (Stock-In) menambah stok inventaris secara konsisten
     */
    public function test_purchase_stock_in_increases_inventory_stock()
    {
        $supplier = Supplier::create([
            'nama_supplier' => 'CV Sumber Rejeki',
            'no_telp' => '081299887766',
        ]);

        $initialStock = $this->deterjen->stok;

        $response = $this->actingAs($this->staff)->post('/purchases', [
            'supplier_id' => $supplier->id,
            'purchased_at' => now()->toDateString(),
            'items' => [
                [
                    'inventaris_id' => $this->deterjen->id,
                    'quantity' => 20,
                    'unit_cost' => 15000,
                ],
            ],
        ]);

        $response->assertRedirect('/purchases');
        $this->deterjen->refresh();
        $this->assertEquals($initialStock + 20, $this->deterjen->stok);
    }

    /**
     * Test 8: Transaksi Pesanan mengurangi stok bahan operasional secara atomik
     */
    public function test_sales_order_reduces_inventory_stock_atomically()
    {
        $initialStock = $this->deterjen->stok;
        $beratKg = 10.0;
        $expectedReduction = round($beratKg * 0.05, 2); // 0.5 liter

        $response = $this->actingAs($this->staff)->post('/pesanan', [
            'pelanggan_id' => $this->pelanggan->id,
            'jenis_layanan_id' => $this->layanan->id,
            'berat_kg' => $beratKg,
            'status_pesanan' => 'Menunggu',
            'tgl_masuk' => now()->toDateString(),
        ]);

        $response->assertRedirect('/pesanan');
        $this->deterjen->refresh();
        $this->assertEquals($initialStock - $expectedReduction, $this->deterjen->stok);
    }

    /**
     * Test 9: Transaksi Pesanan ditolak dan rollback jika stok bahan operasional tidak cukup
     */
    public function test_sales_order_rejected_when_stock_insufficient()
    {
        // Set stok deterjen menjadi sangat sedikit (0.01 liter)
        $this->deterjen->update(['stok' => 0.01]);

        $response = $this->actingAs($this->staff)->post('/pesanan', [
            'pelanggan_id' => $this->pelanggan->id,
            'jenis_layanan_id' => $this->layanan->id,
            'berat_kg' => 20.0, // Butuh 1.0 liter
            'status_pesanan' => 'Menunggu',
            'tgl_masuk' => now()->toDateString(),
        ]);

        $response->assertSessionHasErrors('berat_kg');
        // Pastikan tidak ada pesanan baru yang dibuat
        $this->assertEquals(0, Pesanan::count());
        // Stok tetap utuh tidak berubah
        $this->deterjen->refresh();
        $this->assertEquals(0.01, $this->deterjen->stok);
    }

    /**
     * Test 10: Pemesanan Online Mandiri oleh Publik tanpa login
     */
    public function test_public_can_create_self_order_without_login()
    {
        $response = $this->post('/order', [
            'nama_lengkap' => 'Indah Permata',
            'no_telp' => '081377889900',
            'alamat' => 'Jl. Ijen No. 44 Malang',
            'jenis_layanan_id' => $this->layanan->id,
            'estimasi_berat_kg' => 4.0,
            'metode_pembayaran' => 'QRIS',
            'tgl_penjemputan' => now()->toDateString(),
            'catatan' => 'Jemput jam 10 pagi',
        ]);

        $pesanan = Pesanan::latest('id')->first();
        $this->assertNotNull($pesanan);
        $this->assertEquals('online', $pesanan->sumber_order);
        $this->assertEquals('QRIS', $pesanan->metode_pembayaran);
        $this->assertEquals('Menunggu', $pesanan->status_pesanan);
        $this->assertNull($pesanan->pegawai_id);
        $this->assertEquals(4.0 * 5000, $pesanan->total_harga);

        $response->assertRedirect('/order/success/' . $pesanan->id);
    }

    /**
     * Test 11: Validasi batas maksimal berat per kategori layanan ditolak jika melebihi kuota
     */
    public function test_public_order_rejected_when_exceeding_category_maximum_limit()
    {
        // Layanan Cuci Reguler memiliki batas 25.0 kg
        $this->layanan->update(['maksimum_berat' => 25.0]);

        $response = $this->post('/order', [
            'nama_lengkap' => 'Budi Santoso',
            'no_telp' => '081299887766',
            'alamat' => 'Jl. Soekarno Hatta Malang',
            'jenis_layanan_id' => $this->layanan->id,
            'estimasi_berat_kg' => 30.0, // Melebihi batas 25 kg
            'metode_pembayaran' => 'COD',
            'tgl_penjemputan' => now()->toDateString(),
        ]);

        $response->assertSessionHasErrors('estimasi_berat_kg');
        // Pastikan pesanan tidak dibuat
        $this->assertNull(Pesanan::where('total_harga', 30.0 * 5000)->first());
    }

    /**
     * Test 12: Publik dapat melakukan konfirmasi pembayaran QRIS mandiri hingga Lunas
     */
    public function test_public_can_confirm_qris_payment()
    {
        $pesanan = Pesanan::create([
            'id' => 'PS098',
            'sale_number' => 'PS098',
            'pelanggan_id' => $this->pelanggan->id,
            'pegawai_id' => null,
            'jenis_layanan_id' => $this->layanan->id,
            'berat_kg' => 4.0,
            'status_pesanan' => 'Menunggu',
            'tgl_masuk' => now()->toDateString(),
            'total_harga' => 20000,
            'sumber_order' => 'online',
            'metode_pembayaran' => 'QRIS',
        ]);

        Pembayaran::create([
            'id' => 'PAY098',
            'pesanan_id' => 'PS098',
            'tgl_bayar' => now()->toDateString(),
            'metode_bayar' => 'QRIS',
            'jumlah_bayar' => 20000,
            'status_bayar' => 'Belum Lunas',
            'pegawai_id' => null,
        ]);

        $this->assertFalse($pesanan->isLunas());

        $response = $this->post('/order/' . $pesanan->id . '/confirm-qris');
        $response->assertSessionHas('success');

        $pesanan->refresh();
        $this->assertTrue($pesanan->isLunas());
        $this->assertEquals('Lunas', $pesanan->pembayaran->status_bayar);
    }

    /**
     * Test 11: Publik dapat melacak status pesanan secara instan via resi tracking
     */
    public function test_public_can_track_order_via_resi()
    {
        $pesanan = Pesanan::create([
            'id' => 'PS099',
            'sale_number' => 'PS099',
            'pelanggan_id' => $this->pelanggan->id,
            'pegawai_id' => 'PG02',
            'jenis_layanan_id' => $this->layanan->id,
            'berat_kg' => 5.0,
            'status_pesanan' => 'Diproses',
            'tgl_masuk' => now()->toDateString(),
            'total_harga' => 25000,
            'sumber_order' => 'offline',
        ]);

        $response = $this->get('/tracking?code=PS099');
        $response->assertStatus(200);
        $response->assertSee('PS099');
        $response->assertSee('Diproses');
    }

    /**
     * Test 12: Owner dapat menghapus data pegawai yang memiliki riwayat pesanan (disassociation aman)
     */
    public function test_owner_can_delete_staff_with_order_history()
    {
        $pesanan = Pesanan::create([
            'id' => 'PS100',
            'sale_number' => 'PS100',
            'pelanggan_id' => $this->pelanggan->id,
            'pegawai_id' => 'PG02',
            'jenis_layanan_id' => $this->layanan->id,
            'berat_kg' => 3.0,
            'status_pesanan' => 'Diproses',
            'tgl_masuk' => now()->toDateString(),
            'total_harga' => 15000,
            'sumber_order' => 'offline',
        ]);

        $response = $this->actingAs($this->owner)->delete('/pegawai/PG02');
        $response->assertRedirect('/pegawai');

        // Pastikan pegawai dan user staff terhapus
        $this->assertNull(Pegawai::find('PG02'));
        $this->assertNull(User::where('email', 'staff1@maudylaundry.com')->first());

        // Pastikan pesanan tetap utuh tetapi pegawai_id diset null
        $pesanan->refresh();
        $this->assertNull($pesanan->pegawai_id);
    }

    /**
     * Test 13: Owner dapat menghapus data pesanan yang sudah berstatus lunas (cascade payment + stock restoration)
     */
    public function test_owner_can_delete_order_with_completed_payment()
    {
        $initialStock = $this->deterjen->stok;

        $pesanan = Pesanan::create([
            'id' => 'PS101',
            'sale_number' => 'PS101',
            'pelanggan_id' => $this->pelanggan->id,
            'pegawai_id' => 'PG01',
            'jenis_layanan_id' => $this->layanan->id,
            'berat_kg' => 4.0,
            'status_pesanan' => 'Selesai',
            'tgl_masuk' => now()->toDateString(),
            'total_harga' => 20000,
            'sumber_order' => 'offline',
        ]);

        $pembayaran = Pembayaran::create([
            'id' => 'PAY101',
            'pesanan_id' => 'PS101',
            'tgl_bayar' => now()->toDateString(),
            'metode_bayar' => 'CASH',
            'jumlah_bayar' => 20000,
            'status_bayar' => 'Lunas',
            'pegawai_id' => 'PG01',
        ]);

        $response = $this->actingAs($this->owner)->delete('/pesanan/PS101');
        $response->assertRedirect('/pesanan');

        // Pastikan pesanan terhapus
        $this->assertSoftDeleted('pesanan', ['id' => 'PS101']);
        // Pastikan pembayaran terhapus
        $this->assertNull(Pembayaran::find('PAY101'));
        // Pastikan stok terpulihkan (+0.2 liter)
        $this->deterjen->refresh();
        $this->assertEquals($initialStock + 0.2, $this->deterjen->stok);
    }
}
