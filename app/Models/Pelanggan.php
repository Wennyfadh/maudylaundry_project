<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggan';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'nama_lengkap',
        'no_telp',
        'alamat',
    ];

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'pelanggan_id');
    }

    public static function generateNextId(): string
    {
        $last = self::orderBy('id', 'desc')->first();
        if (!$last) {
            return 'PL01';
        }
        $num = (int) filter_var($last->id, FILTER_SANITIZE_NUMBER_INT);
        $next = $num + 1;
        return sprintf('PL%02d', $next);
    }
}
