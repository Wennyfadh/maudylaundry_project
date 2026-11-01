<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchases';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'supplier_id',
        'user_id',
        'purchase_number',
        'total',
        'status',
        'purchased_at',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'float',
            'purchased_at' => 'datetime',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseItem::class, 'purchase_id');
    }

    public static function generateNextId(): string
    {
        $last = self::orderBy('id', 'desc')->first();
        if (!$last) {
            return 'PO001';
        }
        $num = (int) filter_var($last->id, FILTER_SANITIZE_NUMBER_INT);
        $next = $num + 1;
        return sprintf('PO%03d', $next);
    }
}
