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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->string('id', 20)->primary(); // e.g. PAY001, PAY002
            $table->string('pesanan_id', 20);
            $table->foreign('pesanan_id')->references('id')->on('pesanan')->restrictOnDelete();
            $table->date('tgl_bayar');
            $table->enum('metode_bayar', ['CASH', 'QRIS', 'TRANSFER'])->default('CASH');
            $table->decimal('jumlah_bayar', 14, 2);
            $table->enum('status_bayar', ['Lunas', 'Belum Lunas'])->default('Lunas');
            $table->string('pegawai_id', 20)->nullable();
            $table->foreign('pegawai_id')->references('id')->on('pegawai')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
