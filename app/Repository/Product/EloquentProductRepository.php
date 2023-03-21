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

    /**
     * Get product by id
     * @param int $id
     * 
     * @return Product|null
     */
    public function getById(int $id): Product|null
    {
        try {
            return $this->product->find($id);
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] getById : ", __CLASS__).$e->getMessage());
            return null;
        }
    }

    /**
     * Create product
     * @param array<string, mixed> $data
     * 
     * @return Product|null
     */
    public function create(array $data): Product|null
    {
        try {
            $product = $this->product;

            $product->name = $data['name'];
            $product->description = $data['description'];
            $product->enable = 1;

            $product->save();

            return $product;
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] create : ", __CLASS__).$e->getMessage());
            return null;
        }
    }

    /**
     * Update product
     * @param Product $product
     * @param array<string, mixed> $data
     * 
     * @return Product|null
     */
    public function update(Product $product, array $data): Product|null
    {
        try {
            if (isset($data['name'])) {
                $product->name = $data['name'];
            }
            if (isset($data['description'])) {
                $product->description = $data['description'];
            }
            if (isset($data['enable'])) {
                $product->enable = $data['enable'];
            }

            $product->save();

            return $product;
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] update : ", __CLASS__).$e->getMessage());
            return null;
        }
    }
}