<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pesanan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'pesanan';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'sale_number',
        'pelanggan_id',
        'pegawai_id',
        'jenis_layanan_id',
        'berat_kg',
        'status_pesanan',
        'tgl_masuk',
        'tgl_selesai_estimasi',
        'tgl_selesai_aktual',
        'total_harga',
        'sumber_order',
        'metode_pembayaran',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'berat_kg' => 'float',
            'total_harga' => 'float',
            'tgl_masuk' => 'date',
            'tgl_selesai_estimasi' => 'date',
            'tgl_selesai_aktual' => 'date',
        ];
    }

    public function pelanggan(): BelongsTo
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'jenis_layanan_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PesananItem::class, 'pesanan_id');
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'pesanan_id')->latestOfMany();
    }

    public function pembayarans(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'pesanan_id');
    }

    public function scopeMenunggu($query)
    {
        return $query->where('status_pesanan', 'Menunggu');
    }

    public function scopeDiproses($query)
    {
        return $query->where('status_pesanan', 'Diproses');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status_pesanan', 'Selesai');
    }

    public function isLunas(): bool
    {
        return $this->pembayarans()->where('status_bayar', 'Lunas')->exists();
    }

    public static function generateNextId(): string
    {
        $last = self::withTrashed()->orderBy('id', 'desc')->first();
        if (!$last) {
            return 'PS001';
        }
        $num = (int) filter_var($last->id, FILTER_SANITIZE_NUMBER_INT);
        $next = $num + 1;
        return sprintf('PS%03d', $next);
    }
}
