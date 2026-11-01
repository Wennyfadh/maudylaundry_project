<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'nama_lengkap',
        'jabatan',
        'no_telp',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'pegawai_id');
    }

    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'pegawai_id');
    }

    public static function generateNextId(): string
    {
        $last = self::orderBy('id', 'desc')->first();
        if (!$last) {
            return 'PG01';
        }
        $num = (int) filter_var($last->id, FILTER_SANITIZE_NUMBER_INT);
        $next = $num + 1;
        return sprintf('PG%02d', $next);
    }
}
