<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AddOn extends Model
{
    /**
     * Explicit table name to match migration (addons).
     */
    protected $table = 'addons';

    protected $fillable = ['name', 'price', 'image', 'is_active'];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the products that have this addon.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_addons', 'addon_id', 'product_id')
            ->withPivot('is_required', 'min_selection', 'max_selection')
            ->withTimestamps();
    }
}
