<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanan';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama_layanan',
        'harga_per_kg',
        'estimasi_waktu_hari',
        'maksimum_berat',
    ];

    protected function casts(): array
    {
        return [
            'harga_per_kg' => 'float',
            'estimasi_waktu_hari' => 'integer',
            'maksimum_berat' => 'float',
        ];
    }

    public function getMaksimumBeratAttribute($value): float
    {
        if ($value !== null && (float) $value > 0) {
            return (float) $value;
        }

        return match ($this->id) {
            'LY01' => 25.0,
            'LY02' => 15.0,
            'LY03' => 20.0,
            'LY04' => 10.0,
            'LY05' => 12.0,
            default => 20.0,
        };
    }

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'jenis_layanan_id');
    }

    public static function generateNextId(): string
    {
        $last = self::orderBy('id', 'desc')->first();
        if (!$last) {
            return 'LY01';
        }
        $num = (int) filter_var($last->id, FILTER_SANITIZE_NUMBER_INT);
        $next = $num + 1;
        return sprintf('LY%02d', $next);
    }
}
