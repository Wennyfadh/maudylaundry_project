<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use PDO;

class InitLaundryDatabase extends Command
{
    protected $signature = 'laundry:init {--sqlite : Force initialization using SQLite}';
    protected $description = 'Initialize Maudy Laundry database and seed realistic demo data';

    public function handle(): int
    {
        $this->info('🧺 Menyiapkan Database Maudy Laundry...');

        if ($this->option('sqlite')) {
            $this->info('Menggunakan koneksi SQLite...');
            config(['database.default' => 'sqlite']);
            Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
            $this->info(Artisan::output());
            $this->showSuccess();
            return Command::SUCCESS;
        }

        // Try MySQL first
        $host = config('database.connections.mysql.host', '127.0.0.1');
        $port = config('database.connections.mysql.port', '3306');
        $dbName = config('database.connections.mysql.database', 'maudylaundry');
        $username = config('database.connections.mysql.username', 'root');
        $password = config('database.connections.mysql.password', '');

        try {
            $pdo = new PDO("mysql:host={$host};port={$port}", $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            $this->info("✅ Database MySQL '{$dbName}' berhasil diverifikasi/dibuat di Laragon!");

            Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
            $this->line(Artisan::output());
            $this->showSuccess();
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->warn("⚠️ Tidak dapat terhubung ke MySQL ({$e->getMessage()}).");
            $this->info("Mengalihkan ke database SQLite lokal...");

            config(['database.default' => 'sqlite']);
            Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
            $this->line(Artisan::output());
            $this->showSuccess();
            return Command::SUCCESS;
        }
    }

    private function showSuccess(): void
    {
        $this->info('✨ Database & Seeder Maudy Laundry Siap Digunakan!');
        $this->table(
            ['Role', 'Nama', 'Email', 'Password Default'],
            [
                ['Owner', 'Siti Maudy', 'owner@maudylaundry.com', 'password'],
                ['Staff (Kasir)', 'Dewi Lestari', 'staff2@maudylaundry.com', 'password'],
                ['Staff (Operator Cuci)', 'Rahmat Hidayat', 'staff1@maudylaundry.com', 'password'],
                ['Staff (Setrika)', 'Agus Setiawan', 'agus@maudylaundry.com', 'password'],
                ['Staff (Teknisi)', 'dinda', 'dinda@maudylaundry.com', 'password'],
            ]
        );
    }
}
