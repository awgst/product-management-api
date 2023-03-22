<?php

namespace App\Repository\Image;

use App\Models\Image;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class EloquentImageRepository implements ImageRepositoryInterface
{
    /**
     * @var Image
     */
    private $model;

    /**
     * Create a new repository instance.
     * @param Image $model
     */
    public function __construct(Image $model)
    {
        $this->model = $model;
    }

    /**
     * Get all images
     * @param array $filters
     * 
     * @return LengthAwarePaginator|null
     */
    public function getAll(array $filters): LengthAwarePaginator|null
    {
        try {
            return $this->model
                ->filter($filters)
                ->orderBy('id', 'desc')
                ->paginate($filters['limit'] ?? 10);
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] getAll : ", __CLASS__).$e->getMessage());
            return null;
        }
    }

    /**
     * Get image by id
     * @param int $id
     * 
     * @return Image|null
     */
    public function getById(int $id): Image|null
    {
        try {
            return $this->model->find($id);
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] getById : ", __CLASS__).$e->getMessage());
            return null;
        }
    }
}