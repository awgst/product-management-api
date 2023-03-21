<?php

namespace App\Repository\Category;

use App\Models\Category;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface CategoryRepositoryInterface
{
    public function getAll(array $filters): LengthAwarePaginator|null;
    public function getById(int $id): Category|null;
    public function create(array $data): Category|null;
    public function update(Category $category, array $data): Category|null;
    public function delete(Category $category): bool;

    public function getByIds(array $ids): Collection|null;
}