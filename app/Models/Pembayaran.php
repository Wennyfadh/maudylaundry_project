<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'pesanan_id',
        'tgl_bayar',
        'metode_bayar',
        'jumlah_bayar',
        'status_bayar',
        'bukti_bayar',
        'pegawai_id',
    ];

    protected function casts(): array
    {
        return [
            'jumlah_bayar' => 'float',
            'tgl_bayar' => 'date',
        ];
    }

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    public static function generateNextId(): string
    {
        $last = self::orderBy('id', 'desc')->first();
        if (!$last) {
            return 'PAY001';
        }
        $num = (int) filter_var($last->id, FILTER_SANITIZE_NUMBER_INT);
        $next = $num + 1;
        return sprintf('PAY%03d', $next);
    }
}
