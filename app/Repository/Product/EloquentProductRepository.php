<?php

namespace App\Repository\Product;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
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
                ->with(['categories' => function ($q) {
                    $q->where('enable', true);
                }])
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
            return $this->product
                ->with(['categories' => function ($q) {
                    $q->where('enable', true);
                }])
                ->find($id);
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
            DB::beginTransaction();
            $product = $this->product;

            $product->name = $data['name'];
            $product->description = $data['description'];
            $product->enable = 1;

            $product->save();

            if (isset($data['category_ids'])) {
                $product->categories()->sync($data['category_ids']);
            }

            DB::commit();

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
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
            DB::beginTransaction();
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

            if (isset($data['category_ids'])) {
                $product->categories()->sync($data['category_ids']);
            }

            $product->load('categories');

            DB::commit();

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::channel('exception')->error(sprintf("[%s] update : ", __CLASS__).$e->getMessage());
            return null;
        }
    }

    /**
     * Delete product
     * @param Product $product
     * 
     * @return bool
     */
    public function delete(Product $product): bool
    {
        try {
            return $product->delete();
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] delete : ", __CLASS__).$e->getMessage());
            return false;
        }
    }

    /**
     * Get products by ids
     * @param array<int> $ids
     * 
     * @return Collection|null
     */
    public function getByIds(array $ids): Collection|null
    {
        try {
            return $this->product
                ->whereIn('id', $ids)
                ->where('enable', true)
                ->get();
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] getByIds : ", __CLASS__).$e->getMessage());
            return null;
        }
    }
}