<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pesanan', function (Blueprint $table) {
            $table->string('id', 20)->primary(); // e.g. PS001, PS002
            $table->string('sale_number', 50)->unique();
            $table->string('pelanggan_id', 20);
            $table->foreign('pelanggan_id')->references('id')->on('pelanggan')->restrictOnDelete();
            $table->string('pegawai_id', 20)->nullable();
            $table->foreign('pegawai_id')->references('id')->on('pegawai')->nullOnDelete();
            $table->string('jenis_layanan_id', 20);
            $table->foreign('jenis_layanan_id')->references('id')->on('layanan')->restrictOnDelete();
            $table->decimal('berat_kg', 8, 2)->default(1);
            $table->enum('status_pesanan', ['Menunggu', 'Diproses', 'Selesai'])->default('Menunggu');
            $table->date('tgl_masuk');
            $table->date('tgl_selesai_estimasi')->nullable();
            $table->date('tgl_selesai_aktual')->nullable();
            $table->decimal('total_harga', 14, 2);
            $table->enum('sumber_order', ['online', 'offline'])->default('offline');
            $table->text('catatan')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('pesanan_items', function (Blueprint $table) {
            $table->id();
            $table->string('pesanan_id', 20);
            $table->foreign('pesanan_id')->references('id')->on('pesanan')->cascadeOnDelete();
            $table->string('inventaris_id', 20)->nullable();
            $table->foreign('inventaris_id')->references('id')->on('inventaris')->nullOnDelete();
            $table->string('nama_item', 150);
            $table->decimal('quantity', 8, 2)->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('subtotal', 14, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan_items');
        Schema::dropIfExists('pesanan');
    }
};
