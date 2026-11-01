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
        Schema::create('inventaris', function (Blueprint $table) {
            $table->string('id', 20)->primary(); // e.g. INV01, INV02
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('sku', 50)->unique()->nullable();
            $table->string('nama_barang', 150);
            $table->decimal('purchase_price', 12, 2)->default(0);
            $table->decimal('selling_price', 12, 2)->default(0);
            $table->decimal('stok', 10, 2)->default(0);
            $table->string('satuan', 50)->default('pcs'); // liter, botol, lembar, pcs
            $table->decimal('minimum_stok', 10, 2)->default(0);
            $table->enum('status_stok', ['Aman', 'Perhatian', 'Kritis'])->default('Aman');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventaris');
    }
};
