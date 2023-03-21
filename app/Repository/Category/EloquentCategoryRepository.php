<?php

namespace App\Repository\Category;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @var \App\Models\Category
     */
    private $model;

    /**
     * Create a new repository instance.
     * @param \App\Models\Category $model
     */
    public function __construct(Category $model)
    {
        $this->model = $model;
    }

    /**
     * Get all categories.
     * @param array<string, mixed> $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|null
     */
    public function getAll(array $filters): ?LengthAwarePaginator
    {
        try {
            $orderBy = $filters['order_by'] ?? 'id';
            $order = $filters['order'] ?? 'asc';

            return $this->model
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
     * Get category by id.
     * @param int $id
     * @return \App\Models\Category|null
     */
    public function getById(int $id): ?Category
    {
        try {
            return $this->model->find($id);
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] getById : ", __CLASS__).$e->getMessage());
            return null;
        }
    }

    /**
     * Create category.
     * @param array<string, mixed> $data
     * @return \App\Models\Category|null
     */
    public function create(array $data): ?Category
    {
        try {
            $category = new Category();
            $category->name = $data['name'];
            $category->enable = 1;

            $category->save();

            return $category;
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] create : ", __CLASS__).$e->getMessage());
            return null;
        }
    }

    /**
     * Update category.
     * @param Category $category
     * @param array<string, mixed> $data
     * @return \App\Models\Category|null
     */
    public function update(Category $category, array $data): ?Category
    {
        try {
            if (isset($data['name'])) {
                $category->name = $data['name'];
            }
            if (isset($data['enable'])) {
                $category->enable = $data['enable'];
            }

            $category->save();

            return $category;
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] update : ", __CLASS__).$e->getMessage());
            return null;
        }
    }
}