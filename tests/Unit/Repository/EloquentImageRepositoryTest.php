<?php

namespace Tests\Unit\Repository;

use App\Models\Image;
use App\Repository\Image\EloquentImageRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentImageRepositoryTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * @var EloquentImageRepository
     */
    private $repository;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentImageRepository(new Image());
    }

    /**
     * Test get all images success.
     * 
     * @return void
     */
    public function testGetAllImagesSuccess()
    {
        Image::factory()->count(10)->create();
        $images = $this->repository->getAll([]);

        $this->assertInstanceOf(LengthAwarePaginator::class, $images);
    }

    /**
     * Test get all images with filter success.
     * 
     * @return void
     */
    public function testGetAllImagesWithFilterSuccess()
    {
        Image::factory()->count(10)->create();
        $images = $this->repository->getAll(['name' => 'Image 1']);

        $this->assertInstanceOf(LengthAwarePaginator::class, $images);
    }

    /**
     * Test get image by id success.
     * 
     * @return void
     */
    public function testGetImageByIdSuccess()
    {
        $image = Image::factory()->create();
        $image = $this->repository->getById($image->id);

        $this->assertInstanceOf(Image::class, $image);
    }

    /**
     * Test create image success.
     * 
     * @return void
     */
    public function testCreateImageSuccess()
    {
        $image = Image::factory()->make();
        $image = $this->repository->create($image->toArray());

        $this->assertInstanceOf(Image::class, $image);
    }

    /**
     * Test update image success.
     * 
     * @return void
     */
    public function testUpdateImageSuccess()
    {
        $image = Image::factory()->create();
        $image = $this->repository->update($image, ['name' => 'Image 1']);

        $this->assertInstanceOf(Image::class, $image);
    }

    /**
     * Test delete image success.
     * 
     * @return void
     */
    public function testDeleteImageSuccess()
    {
        $image = Image::factory()->create();
        $this->repository->delete($image);

        $this->assertDatabaseMissing('images', ['id' => $image->id]);
    }
}