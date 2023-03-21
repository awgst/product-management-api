<?php

namespace App\Repository\Product;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function getAll(array $filters): LengthAwarePaginator|null;
    public function getById(int $id): Product|null;
    public function create(array $data): Product|null;
    public function update(Product $product, array $data): Product|null;
    public function delete(Product $product): bool;

    public function getByIds(array $ids): Collection|null;
}