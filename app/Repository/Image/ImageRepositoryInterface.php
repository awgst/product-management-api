<?php

namespace App\Repository\Image;

use App\Models\Image;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ImageRepositoryInterface
{
    public function getAll(array $filters): LengthAwarePaginator|null;
    public function getById(int $id): Image|null;
}