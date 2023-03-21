<?php

namespace App\Service;

use App\Exceptions\CustomException;
use App\Models\Category;
use App\Repository\Category\CategoryRepositoryInterface;
use App\Repository\Product\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class CategoryService
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * Create a new service instance.
     * 
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository, ProductRepositoryInterface $productRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Get all categories.
     * @param array<string, mixed> $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|null
     */
    public function getAll(array $filters): ?LengthAwarePaginator
    {
        try {
            return $this->categoryRepository->getAll($filters);
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] getAll : ", __CLASS__).$e->getMessage());
            return null;
        }
    }

    /**
     * Get category by id.
     * @param int $id
     * @return \App\Models\Category|CustomException|null
     */
    public function getById(int $id): Category|CustomException|null
    {
        try {
            $category = $this->categoryRepository->getById($id);
            if (is_null($category)) {
                return new CustomException('Category not found', 404);
            }

            return $category;
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] getById : ", __CLASS__).$e->getMessage());
            return null;
        }
    }

    /**
     * Create category.
     * @param array<string, mixed> $data
     * @return \App\Models\Category|CustomException|null
     */
    public function create(array $data): Category|CustomException|null
    {
        try {
            // Check product
            $products = $this->productRepository->getByIds($data['product_ids']);
            if (is_null($products)) {
                return new CustomException('Product not found', 404);
            }

            $productIds = $products->pluck('id')->toArray();
            $diff = array_diff($data['product_ids'], $productIds);
            if (count($diff) > 0) {
                return new CustomException(sprintf('Product with id %s not found', implode(', ', $diff)), 400);
            }
            // Create category
            $category = $this->categoryRepository->create($data);

            return $category;
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] create : ", __CLASS__).$e->getMessage());
            return null;
        }
    }

    /**
     * Update category.
     * @param int $id
     * @param array<string, mixed> $data
     * @return \App\Models\Category|CustomException|null
     */
    public function update(int $id, array $data): Category|CustomException|null
    {
        try {
            $category = $this->categoryRepository->getById($id);
            if (is_null($category)) {
                return new CustomException('Category not found', 404);
            }

            // Check product
            if (isset($data['product_ids'])) {
                $products = $this->productRepository->getByIds($data['product_ids']);
                if (is_null($products)) {
                    return new CustomException('Product not found', 404);
                }

                $productIds = $products->pluck('id')->toArray();
                $diff = array_diff($data['product_ids'], $productIds);
                if (count($diff) > 0) {
                    return new CustomException(sprintf('Product with id %s not found', implode(', ', $diff)), 400);
                }
            }

            $updated = $this->categoryRepository->update($category, $data);

            return $updated;
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] update : ", __CLASS__).$e->getMessage());
            return null;
        }
    }

    /**
     * Delete category.
     * @param int $id
     * @return bool|CustomException|null
     */
    public function delete(int $id): bool|CustomException|null
    {
        try {
            $category = $this->categoryRepository->getById($id);
            if (is_null($category)) {
                return new CustomException('Category not found', 404);
            }
            $deleted = $this->categoryRepository->delete($category);

            return $deleted;
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] delete : ", __CLASS__).$e->getMessage());
            return null;
        }
    }

    /**
     * Get by ids
     * @param array<int> $ids
     * @return \Illuminate\Database\Eloquent\Collection|null
     */
    public function getByIds(array $ids): ?\Illuminate\Database\Eloquent\Collection
    {
        try {
            return $this->categoryRepository->getByIds($ids);
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] getByIds : ", __CLASS__).$e->getMessage());
            return null;
        }
    }
}