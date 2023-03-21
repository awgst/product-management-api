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

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
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
     * @return Product|null
     */
    public function create(array $data): Product|null
    {
        try {
            return $this->productRepository->create($data);
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] create : ", __CLASS__).$e->getMessage());
            return null;
        }
    }
}