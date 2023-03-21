<?php

namespace App\Service;

use App\Exceptions\CustomException;
use App\Models\Category;
use App\Repository\Category\CategoryRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class CategoryService
{
    /**
     * @var CategoryRepositoryInterface
     */
    private $categoryRepository;
    
    /**
     * Create a new service instance.
     * 
     * @param CategoryRepositoryInterface $categoryRepository
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
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