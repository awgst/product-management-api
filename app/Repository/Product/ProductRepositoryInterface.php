<?php

namespace App\Repository\Product;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function getAll(array $filters): LengthAwarePaginator|null;
    public function getById(int $id): Product|null;
    public function create(array $data): Product|null;
    public function update(Product $product, array $data): Product|null;
    public function delete(Product $product): bool;
}