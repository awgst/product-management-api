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

    /**
     * Create image
     * @param array $data
     * 
     * @return Image|null
     */
    public function create(array $data): Image|null
    {
        try {
            $image = new Image();
            $image->name = $data['name'];
            $image->file = $data['file'];
            $image->enable = 1;

            $image->save();
            
            return $image;
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] create : ", __CLASS__).$e->getMessage());
            return null;
        }
    }

    /**
     * Update image
     * @param Image $image
     * @param array $data
     * 
     * @return Image|null
     */
    public function update(Image $image, array $data): Image|null
    {
        try {
            if (isset($data['name'])) {
                $image->name = $data['name'];
            }

            if (isset($data['file'])) {
                $image->file = $data['file'];
            }

            if (isset($data['enable'])) {
                $image->enable = $data['enable'];
            }

            $image->save();
            
            return $image;
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] update : ", __CLASS__).$e->getMessage());
            return null;
        }
    }

    /**
     * Delete image
     * @param Image $image
     * 
     * @return bool
     */
    public function delete(Image $image): bool
    {
        try {
            return $image->delete();
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] delete : ", __CLASS__).$e->getMessage());
            return false;
        }
    }
}