<?php

namespace App\Repository\Category;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface CategoryRepositoryInterface
{
    public function getAll(array $filters): LengthAwarePaginator|null;
}