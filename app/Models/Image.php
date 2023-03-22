<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    public $timestamps = false; 

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

        return $query;
    }

    /**
     * Accessor for url attribute
     * @return string
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/images/'.$this->file);
    }

    /**
     * Product relationship
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_images');
    }
}
