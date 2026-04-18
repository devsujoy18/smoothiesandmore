<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use Sluggable;

    protected $fillable = ['name', 'description', 'price', 'mrp', 'category_id', 'image', 'is_available', 'sort_no'];

    protected $casts = [
        'price' => 'decimal:2',
        'mrp' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    /**
     * Return the sluggable configuration array for this model.
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the addons for this product.
     */
    public function addons(): BelongsToMany
    {
        return $this->belongsToMany(AddOn::class, 'product_addons', 'product_id', 'addon_id')
            ->withPivot('is_required', 'min_selection', 'max_selection')
            ->withTimestamps();
    }
}
