<?php

namespace App\Service;

use App\Exceptions\CustomException;
use App\Models\Image;
use App\Repository\Image\ImageRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ImageService
{
    /**
     * @var ImageRepositoryInterface
     */
    private $imageRepository;

    /**
     * Create a new service instance.
     * 
     * @param ImageRepositoryInterface $imageRepository
     */
    public function __construct(ImageRepositoryInterface $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    /**
     * Get all categories.
     * @param array<string, mixed> $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|null
     */
    public function getAll(array $filters): ?LengthAwarePaginator
    {
        try {
            return $this->imageRepository->getAll($filters);
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] getAll : ", __CLASS__).$e->getMessage());
            return null;
        }
    }

    /**
     * Get image by id.
     * @param int $id
     * @return \App\Models\Image|CustomException|null
     */
    public function getById(int $id): Image|CustomException|null
    {
        try {
            $image = $this->imageRepository->getById($id);
            if (is_null($image)) {
                return new CustomException('Image not found', 404);
            }

            return $image;
        } catch (\Exception $e) {
            Log::channel('exception')->error(sprintf("[%s] getById : ", __CLASS__).$e->getMessage());
            return null;
        }
    }
}