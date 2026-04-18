<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    public const TYPE_MAIN = 'main';

    public const TYPE_ADDON = 'addon';

    protected $fillable = [
        'order_id',
        'product_id',
        'addon_product_id',
        'item_type',
        'product_name_snapshot',
        'price_snapshot',
        'quantity',
        'total_price',
    ];

    protected $casts = [
        'price_snapshot' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function addonProduct(): BelongsTo
    {
        return $this->belongsTo(AddOn::class, 'addon_product_id');
    }
}
