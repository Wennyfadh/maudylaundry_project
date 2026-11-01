<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('layanan') && !Schema::hasColumn('layanan', 'maksimum_berat')) {
            Schema::table('layanan', function (Blueprint $table) {
                $table->decimal('maksimum_berat', 8, 2)->default(20.00)->after('estimasi_waktu_hari');
            });

            // Set batas maksimal realistis untuk layanan bawaan
            DB::table('layanan')->where('id', 'LY01')->update(['maksimum_berat' => 25.00]); // Cuci Reguler
            DB::table('layanan')->where('id', 'LY02')->update(['maksimum_berat' => 15.00]); // Cuci Express
            DB::table('layanan')->where('id', 'LY03')->update(['maksimum_berat' => 20.00]); // Cuci + Setrika
            DB::table('layanan')->where('id', 'LY04')->update(['maksimum_berat' => 10.00]); // Dry Cleaning
            DB::table('layanan')->where('id', 'LY05')->update(['maksimum_berat' => 12.00]); // Selimut / Bedcover
        }

        if (Schema::hasTable('pesanan') && !Schema::hasColumn('pesanan', 'metode_pembayaran')) {
            Schema::table('pesanan', function (Blueprint $table) {
                $table->string('metode_pembayaran', 20)->default('COD')->after('sumber_order');
            });
        }

        if (Schema::hasTable('pembayaran') && !Schema::hasColumn('pembayaran', 'bukti_bayar')) {
            Schema::table('pembayaran', function (Blueprint $table) {
                $table->string('bukti_bayar')->nullable()->after('status_bayar');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('layanan') && Schema::hasColumn('layanan', 'maksimum_berat')) {
            Schema::table('layanan', function (Blueprint $table) {
                $table->dropColumn('maksimum_berat');
            });
        }

        if (Schema::hasTable('pesanan') && Schema::hasColumn('pesanan', 'metode_pembayaran')) {
            Schema::table('pesanan', function (Blueprint $table) {
                $table->dropColumn('metode_pembayaran');
            });
        }

        if (Schema::hasTable('pembayaran') && Schema::hasColumn('pembayaran', 'bukti_bayar')) {
            Schema::table('pembayaran', function (Blueprint $table) {
                $table->dropColumn('bukti_bayar');
            });
        }
    }
};
