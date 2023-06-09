<?php

namespace App\Service;

use App\Exceptions\CustomException;
use App\Models\Product;
use App\Repository\Product\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ProductService
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * @var ImageService
     */
    private $imageService;

    public function __construct(ProductRepositoryInterface $productRepository, CategoryService $categoryService, ImageService $imageService)
    {
        $this->productRepository = $productRepository;
        $this->categoryService = $categoryService;
        $this->imageService = $imageService;
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
            return $this->productRepository->getAll($filters);
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] getAll : ", __CLASS__).$e->getMessage());
            return null;
        }
    }

    /**
     * Get product by id
     * @param int $id
     * 
     * @return Product|CustomException|null
     */
    public function getById(int $id): Product|CustomException|null
    {
        try {
            $category = $this->productRepository->getById($id);
            if (is_null($category)) {
                return new CustomException('Product not found', 404);
            }

            return $category;
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] getById : ", __CLASS__).$e->getMessage());
            return null;
        }
    }

    /**
     * Create product
     * @param array<string, mixed> $data
     * 
     * @return Product|CustomException|null
     */
    public function create(array $data): Product|CustomException|null
    {
        try {
            // Check category
            $categories = $this->categoryService->getByIds($data['category_ids']);
            if (is_null($categories)) {
                return new CustomException('Category not found', 404);
            }
            $categoryIds = $categories->pluck('id')->toArray();
            $diff = array_diff($data['category_ids'], $categoryIds);
            if (count($diff) > 0) {
                return new CustomException(sprintf('Category with id %s not found', implode(", ", $diff)), 400);
            }

            // Create image
            $imageIds = [];
            foreach ($data['files'] as $key => $file) {
                $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $imageData = [
                    'name' => $data['file_name'][$key] ?? $originalFileName,
                    'file' => $file,
                ];
                $image = $this->imageService->create($imageData);
                if ($image instanceof CustomException || is_null($image)) {
                    return $image;
                }
                $imageIds[] = $image->id;
            }
            $data['image_ids'] = $imageIds;

            // Create product
            return $this->productRepository->create($data);
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] create : ", __CLASS__).$e->getMessage());
            return null;
        }
    }

    /**
     * Update product
     * @param int $id
     * @param array<string, mixed> $data
     * 
     * @return Product|CustomException|null
     */
    public function update(int $id, array $data): Product|CustomException|null
    {
        try {
            $product = $this->productRepository->getById($id);
            if (is_null($product)) {
                return new CustomException('Product not found', 404);
            }

            if (isset($data['category_ids'])) {
                // Check category
                $categories = $this->categoryService->getByIds($data['category_ids']);
                if (is_null($categories)) {
                    return new CustomException('Category not found', 404);
                }
                $categoryIds = $categories->pluck('id')->toArray();
                $diff = array_diff($data['category_ids'], $categoryIds);
                if (count($diff) > 0) {
                    return new CustomException(sprintf('Category with id %s not found', implode(", ", $diff)), 400);
                }
            }

            if (isset($data['files'])) {
                // Create image
                $imageIds = [];
                foreach ($data['files'] as $key => $file) {
                    $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $imageData = [
                        'name' => $data['file_name'][$key] ?? $originalFileName,
                        'file' => $file,
                    ];
                    $image = $this->imageService->create($imageData);
                    if ($image instanceof CustomException || is_null($image)) {
                        return $image;
                    }
                    $imageIds[] = $image->id;
                }
                $data['image_ids'] = $imageIds;
            }

            return $this->productRepository->update($product, $data);
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] update : ", __CLASS__).$e->getMessage());
            return null;
        }
    }

    /**
     * Delete product
     * @param int $id
     * 
     * @return bool|CustomException|null
     */
    public function delete(int $id): bool|CustomException|null
    {
        try {
            $product = $this->productRepository->getById($id);
            if (is_null($product)) {
                return new CustomException('Product not found', 404);
            }

            return $this->productRepository->delete($product);
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] delete : ", __CLASS__).$e->getMessage());
            return null;
        }
    }
}