<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Inventaris;
use App\Models\Layanan;
use App\Models\Pegawai;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\Pesanan;
use App\Models\PesananItem;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. SEED USERS & PEGAWAI
        $usersData = [
            [
                'email' => 'winny@gmail.com',
                'name' => 'winny',
                'role' => 'owner',
                'password' => Hash::make('password'),
                'pegawai_id' => 'PG01',
                'jabatan' => 'Pemilik',
                'no_telp' => '081200001111',
            ],
            [
                'email' => 'jahdam@gmail.com',
                'name' => 'jahdam eka',
                'role' => 'staff',
                'password' => Hash::make('password'),
                'pegawai_id' => 'PG02',
                'jabatan' => 'Kasir',
                'no_telp' => '081200002222',
            ],
            [
                'email' => 'dewi@gmail.com',
                'name' => 'dewi lestari',
                'role' => 'staff',
                'password' => Hash::make('password'),
                'pegawai_id' => 'PG03',
                'jabatan' => 'Operator Cuci',
                'no_telp' => '081200003333',
            ],
            [
                'email' => 'agus@gmail.com',
                'name' => 'agus setiawan',
                'role' => 'staff',
                'password' => Hash::make('password'),
                'pegawai_id' => 'PG04',
                'jabatan' => 'Operator Setrika',
                'no_telp' => '081200004444',
            ],
            [
                'email' => 'dinda@maudylaundry.com',
                'name' => 'dinda',
                'role' => 'staff',
                'password' => Hash::make('password'),
                'pegawai_id' => 'PG05',
                'jabatan' => 'Teknisi Cuci',
                'no_telp' => '08119999',
            ],
        ];

        foreach ($usersData as $u) {
            $user = User::create([
                'name' => $u['name'],
                'email' => $u['email'],
                'password' => $u['password'],
                'role' => $u['role'],
            ]);

            Pegawai::create([
                'id' => $u['pegawai_id'],
                'user_id' => $user->id,
                'nama_lengkap' => $u['name'],
                'jabatan' => $u['jabatan'],
                'no_telp' => $u['no_telp'],
            ]);
        }

        // 2. SEED PELANGGAN (Matches PDF 2 Slide 2)
        $pelangganData = [
            ['id' => 'PL01', 'nama_lengkap' => 'Budi Hartono', 'no_telp' => '082100001111', 'alamat' => 'Jl. Mawar No. 12 Aceh Tamiang'],
            ['id' => 'PL02', 'nama_lengkap' => 'Rina Susanti', 'no_telp' => '082100002222', 'alamat' => 'Jl. Melati No. 5 Aceh Tamiang'],
            ['id' => 'PL03', 'nama_lengkap' => 'Dani Firmansyah', 'no_telp' => '082100003333', 'alamat' => 'Jl. Anggrek No. 7 Aceh Tamiang'],
            ['id' => 'PL04', 'nama_lengkap' => 'Yuni Astuti', 'no_telp' => '082100004444', 'alamat' => 'Jl. Kenanga No. 3 Aceh Tamiang'],
            ['id' => 'PL05', 'nama_lengkap' => 'nisaa', 'no_telp' => '0819999', 'alamat' => 'jln mangga'],
            ['id' => 'PL06', 'nama_lengkap' => 'sucinta', 'no_telp' => '0819999', 'alamat' => 'jln manusia baik'],
            ['id' => 'PL07', 'nama_lengkap' => 'dinda', 'no_telp' => '0819999911919999', 'alamat' => 'jl perintis01 masal'],
        ];

        foreach ($pelangganData as $p) {
            Pelanggan::create($p);
        }

        // 3. SEED LAYANAN (Matches PDF 2 Slide 4)
        $layananData = [
            ['id' => 'LY01', 'nama_layanan' => 'Cuci Reguler', 'harga_per_kg' => 5000, 'estimasi_waktu_hari' => 2],
            ['id' => 'LY02', 'nama_layanan' => 'Cuci Express', 'harga_per_kg' => 10000, 'estimasi_waktu_hari' => 1],
            ['id' => 'LY03', 'nama_layanan' => 'Cuci + Setrika', 'harga_per_kg' => 8000, 'estimasi_waktu_hari' => 3],
            ['id' => 'LY04', 'nama_layanan' => 'Dry Cleaning', 'harga_per_kg' => 25000, 'estimasi_waktu_hari' => 2],
            ['id' => 'LY05', 'nama_layanan' => 'Cuci Selimut/Bedcover', 'harga_per_kg' => 30000, 'estimasi_waktu_hari' => 3],
        ];

        foreach ($layananData as $l) {
            Layanan::create($l);
        }

        // 4. SEED CATEGORIES (PDF 1 requirement)
        $categories = [
            ['name' => 'Bahan Cuci', 'description' => 'Deterjen dan sabun pencuci pakaian'],
            ['name' => 'Pewangi & Pelembut', 'description' => 'Pewangi dan softener konsentrat'],
            ['name' => 'Pengemasan', 'description' => 'Plastik pembungkus dan hanger'],
            ['name' => 'Chemical Khusus', 'description' => 'Cairan pembersih noda khusus dan dry clean'],
        ];

        $catIds = [];
        foreach ($categories as $cat) {
            $created = Category::create($cat);
            $catIds[] = $created->id;
        }

        // 5. SEED INVENTARIS (Matches PDF 2 Slide 5)
        $inventarisData = [
            ['id' => 'INV01', 'category_id' => $catIds[0], 'sku' => 'INV01', 'nama_barang' => 'Deterjen Cair', 'purchase_price' => 15000, 'selling_price' => 20000, 'stok' => 50, 'satuan' => 'liter', 'minimum_stok' => 10, 'status_stok' => 'Aman'],
            ['id' => 'INV02', 'category_id' => $catIds[1], 'sku' => 'INV02', 'nama_barang' => 'Pewangi Pakaian', 'purchase_price' => 18000, 'selling_price' => 25000, 'stok' => 30, 'satuan' => 'botol', 'minimum_stok' => 10, 'status_stok' => 'Aman'],
            ['id' => 'INV03', 'category_id' => $catIds[2], 'sku' => 'INV03', 'nama_barang' => 'Plastik Kemasan', 'purchase_price' => 500, 'selling_price' => 1000, 'stok' => 200, 'satuan' => 'lembar', 'minimum_stok' => 50, 'status_stok' => 'Aman'],
            ['id' => 'INV04', 'category_id' => $catIds[3], 'sku' => 'INV04', 'nama_barang' => 'Cairan Dry Clean', 'purchase_price' => 45000, 'selling_price' => 60000, 'stok' => 10, 'satuan' => 'liter', 'minimum_stok' => 15, 'status_stok' => 'Perhatian'],
            ['id' => 'INV05', 'category_id' => $catIds[1], 'sku' => 'INV05', 'nama_barang' => 'Softener', 'purchase_price' => 12000, 'selling_price' => 18000, 'stok' => 25, 'satuan' => 'botol', 'minimum_stok' => 10, 'status_stok' => 'Aman'],
        ];

        foreach ($inventarisData as $inv) {
            Inventaris::create($inv);
        }

        // 6. SEED SUPPLIERS (PDF 1 requirement)
        $suppliers = [
            ['nama_supplier' => 'CV Berkah Kimia Laundry', 'no_telp' => '08123456789', 'email' => 'supplier@berkahkimia.com', 'alamat' => 'Jl. Raya Tlogomas No. 45 Aceh Tamiang'],
            ['nama_supplier' => 'PT Sentosa Plastik Pack', 'no_telp' => '08198765432', 'email' => 'info@sentosaplastik.com', 'alamat' => 'Kawasan Industri Rungkut No. 12 Surabaya'],
        ];

        $supplierModels = [];
        foreach ($suppliers as $s) {
            $supplierModels[] = Supplier::create($s);
        }

        // 7. SEED PURCHASES & ITEMS (PDF 1 stock-in requirement)
        $purchase = Purchase::create([
            'id' => 'PO001',
            'supplier_id' => $supplierModels[0]->id,
            'user_id' => 1,
            'purchase_number' => 'PO-202607-001',
            'total' => 1500000,
            'status' => 'completed',
            'purchased_at' => '2026-07-01 09:00:00',
            'catatan' => 'Pengadaan stok awal bahan cuci bulan Juli',
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'inventaris_id' => 'INV01',
            'quantity' => 50,
            'unit_cost' => 15000,
            'subtotal' => 750000,
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'inventaris_id' => 'INV02',
            'quantity' => 30,
            'unit_cost' => 18000,
            'subtotal' => 540000,
        ]);

        PurchaseItem::create([
            'purchase_id' => $purchase->id,
            'inventaris_id' => 'INV05',
            'quantity' => 17.5,
            'unit_cost' => 12000,
            'subtotal' => 210000,
        ]);

        // 8. SEED PESANAN (Matches PDF 2 Slide 7)
        $pesananData = [
            [
                'id' => 'PS001',
                'sale_number' => 'PS001',
                'pelanggan_id' => 'PL01',
                'pegawai_id' => 'PG02',
                'jenis_layanan_id' => 'LY01',
                'berat_kg' => 5.0,
                'status_pesanan' => 'Selesai',
                'tgl_masuk' => '2026-07-01',
                'tgl_selesai_estimasi' => '2026-07-03',
                'tgl_selesai_aktual' => '2026-07-03',
                'total_harga' => 25000,
                'sumber_order' => 'offline',
                'catatan' => 'Cuci wangi lavender',
            ],
            [
                'id' => 'PS002',
                'sale_number' => 'PS002',
                'pelanggan_id' => 'PL02',
                'pegawai_id' => 'PG02',
                'jenis_layanan_id' => 'LY03',
                'berat_kg' => 5.0,
                'status_pesanan' => 'Selesai',
                'tgl_masuk' => '2026-07-05',
                'tgl_selesai_estimasi' => '2026-07-06',
                'tgl_selesai_aktual' => '2026-07-06',
                'total_harga' => 40000,
                'sumber_order' => 'offline',
                'catatan' => 'Kemeja kerja harap rapi',
            ],
            [
                'id' => 'PS003',
                'sale_number' => 'PS003',
                'pelanggan_id' => 'PL03',
                'pegawai_id' => 'PG04',
                'jenis_layanan_id' => 'LY03',
                'berat_kg' => 7.0,
                'status_pesanan' => 'Selesai',
                'tgl_masuk' => '2026-07-08',
                'tgl_selesai_estimasi' => '2026-07-11',
                'tgl_selesai_aktual' => '2026-07-11',
                'total_harga' => 56000,
                'sumber_order' => 'offline',
                'catatan' => null,
            ],
            [
                'id' => 'PS004',
                'sale_number' => 'PS004',
                'pelanggan_id' => 'PL01',
                'pegawai_id' => 'PG02',
                'jenis_layanan_id' => 'LY05',
                'berat_kg' => 5.0,
                'status_pesanan' => 'Selesai',
                'tgl_masuk' => '2026-07-10',
                'tgl_selesai_estimasi' => null,
                'tgl_selesai_aktual' => '2026-07-13',
                'total_harga' => 150000,
                'sumber_order' => 'offline',
                'catatan' => 'Bedcover king size 2 buah',
            ],
            [
                'id' => 'PS005',
                'sale_number' => 'PS005',
                'pelanggan_id' => 'PL04',
                'pegawai_id' => 'PG04',
                'jenis_layanan_id' => 'LY02',
                'berat_kg' => 3.0,
                'status_pesanan' => 'Selesai',
                'tgl_masuk' => '2026-07-12',
                'tgl_selesai_estimasi' => '2026-07-13',
                'tgl_selesai_aktual' => '2026-07-13',
                'total_harga' => 30000,
                'sumber_order' => 'offline',
                'catatan' => 'Butuh cepat untuk dinas luar kota',
            ],
            [
                'id' => 'PS006',
                'sale_number' => 'PS006',
                'pelanggan_id' => 'PL05',
                'pegawai_id' => 'PG04',
                'jenis_layanan_id' => 'LY05',
                'berat_kg' => 30.0,
                'status_pesanan' => 'Selesai',
                'tgl_masuk' => '2026-07-25',
                'tgl_selesai_estimasi' => null,
                'tgl_selesai_aktual' => '2026-07-28',
                'total_harga' => 900000,
                'sumber_order' => 'online',
                'catatan' => 'Pesanan sprei asrama via web mandiri',
            ],
            [
                'id' => 'PS007',
                'sale_number' => 'PS007',
                'pelanggan_id' => 'PL05',
                'pegawai_id' => 'PG05',
                'jenis_layanan_id' => 'LY01',
                'berat_kg' => 70.0,
                'status_pesanan' => 'Selesai',
                'tgl_masuk' => '2026-07-29',
                'tgl_selesai_estimasi' => '2026-07-30',
                'tgl_selesai_aktual' => '2026-07-30',
                'total_harga' => 350000,
                'sumber_order' => 'online',
                'catatan' => 'Cuci pakaian bulk order jemput di alamat',
            ],
        ];

        foreach ($pesananData as $ps) {
            $pesanan = Pesanan::create($ps);

            PesananItem::create([
                'pesanan_id' => $pesanan->id,
                'inventaris_id' => 'INV01',
                'nama_item' => 'Layanan ' . Layanan::find($ps['jenis_layanan_id'])->nama_layanan . ' (' . $ps['berat_kg'] . ' kg)',
                'quantity' => $ps['berat_kg'],
                'unit_price' => Layanan::find($ps['jenis_layanan_id'])->harga_per_kg,
                'subtotal' => $ps['total_harga'],
            ]);
        }

        // 9. SEED PEMBAYARAN (Matches PDF 2 Slide 6)
        $pembayaranData = [
            ['id' => 'PAY001', 'pesanan_id' => 'PS001', 'tgl_bayar' => '2026-07-03', 'metode_bayar' => 'CASH', 'jumlah_bayar' => 25000, 'status_bayar' => 'Lunas', 'pegawai_id' => 'PG02'],
            ['id' => 'PAY002', 'pesanan_id' => 'PS002', 'tgl_bayar' => '2026-07-06', 'metode_bayar' => 'QRIS', 'jumlah_bayar' => 40000, 'status_bayar' => 'Lunas', 'pegawai_id' => 'PG02'],
            ['id' => 'PAY003', 'pesanan_id' => 'PS006', 'tgl_bayar' => '2026-07-28', 'metode_bayar' => 'CASH', 'jumlah_bayar' => 900000, 'status_bayar' => 'Lunas', 'pegawai_id' => 'PG03'],
            ['id' => 'PAY004', 'pesanan_id' => 'PS003', 'tgl_bayar' => '2026-07-28', 'metode_bayar' => 'CASH', 'jumlah_bayar' => 56000, 'status_bayar' => 'Lunas', 'pegawai_id' => 'PG03'],
            ['id' => 'PAY005', 'pesanan_id' => 'PS004', 'tgl_bayar' => '2026-07-28', 'metode_bayar' => 'CASH', 'jumlah_bayar' => 150000, 'status_bayar' => 'Lunas', 'pegawai_id' => 'PG03'],
            ['id' => 'PAY006', 'pesanan_id' => 'PS005', 'tgl_bayar' => '2026-07-28', 'metode_bayar' => 'CASH', 'jumlah_bayar' => 30000, 'status_bayar' => 'Lunas', 'pegawai_id' => 'PG03'],
            ['id' => 'PAY007', 'pesanan_id' => 'PS007', 'tgl_bayar' => '2026-07-28', 'metode_bayar' => 'CASH', 'jumlah_bayar' => 350000, 'status_bayar' => 'Lunas', 'pegawai_id' => 'PG03'],
        ];

        foreach ($pembayaranData as $pay) {
            Pembayaran::create($pay);
        }
    }
}
