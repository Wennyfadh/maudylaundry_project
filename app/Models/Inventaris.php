<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventaris extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'inventaris';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'category_id',
        'sku',
        'nama_barang',
        'purchase_price',
        'selling_price',
        'stok',
        'satuan',
        'minimum_stok',
        'status_stok',
    ];

    protected function casts(): array
    {
        return [
            'purchase_price' => 'float',
            'selling_price' => 'float',
            'stok' => 'float',
            'minimum_stok' => 'float',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function purchaseItems(): HasMany
    {
        return $this->hasMany(PurchaseItem::class, 'inventaris_id');
    }

    public function pesananItems(): HasMany
    {
        return $this->hasMany(PesananItem::class, 'inventaris_id');
    }

    public function updateStatusStok(): string
    {
        if ($this->stok <= 0) {
            $status = 'Kritis';
        } elseif ($this->stok <= $this->minimum_stok) {
            $status = 'Perhatian';
        } else {
            $status = 'Aman';
        }
        $this->status_stok = $status;
        $this->save();
        return $status;
    }

    public static function generateNextId(): string
    {
        $last = self::withTrashed()->orderBy('id', 'desc')->first();
        if (!$last) {
            return 'INV01';
        }
        $num = (int) filter_var($last->id, FILTER_SANITIZE_NUMBER_INT);
        $next = $num + 1;
        return sprintf('INV%02d', $next);
    }
}
