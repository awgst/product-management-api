<?php

namespace App\Repository\Product;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class EloquentProductRepository implements ProductRepositoryInterface
{
    /**
     * @var Product
     */
    private Product $product;

    /**
     * Create a new EloquentProductRepository instance.
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get all products
     * @param array<string, mixed> $filters
     * 
     * @return LengthAwarePaginator|null
     */
    public function getAll(array $filters): LengthAwarePaginator|null
    {
        try {
            $orderBy = $filters['order_by'] ?? 'id';
            $order = $filters['order'] ?? 'asc';

            return $this->product
                ->filter($filters)
                ->where('enable', true)
                ->orderBy($orderBy, $order)
                ->paginate($filters['limit'] ?? 10);
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] getAll : ", __CLASS__).$e->getMessage());
            return null;
        }
    }
}