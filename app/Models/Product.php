<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * Get the categories for the product.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_products');
    }

    /**
     * Get the images for the product.
     */
    public function images(): BelongsToMany
    {
        return $this->belongsToMany(Image::class, 'product_images');
    }

    /**
     * Scope filter.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array<string, mixed> $filters
     * 
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, array $filters): \Illuminate\Database\Eloquent\Builder
    {
        if (isset($filters['name'])) {
            $query->where('name', 'like', '%'.$filters['name'].'%');
        }

        if (isset($filters['description'])) {
            $query->where('description', 'like', '%'.$filters['description'].'%');
        }

        return $query;
    }
}
