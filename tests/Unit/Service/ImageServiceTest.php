<?php

namespace Tests\Unit\Service;

use App\Exceptions\CustomException;
use App\Facade\Upload;
use App\Models\Image;
use App\Repository\Image\ImageRepositoryInterface;
use App\Service\ImageService;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class ImageServiceTest extends TestCase
{
    /**
     * @var ImageService
     */
    private $service;

    /**
     * @var MockObject
     */
    private $imageRepositoryMock;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->imageRepositoryMock = $this->createMock(ImageRepositoryInterface::class);
        $this->service = new ImageService($this->imageRepositoryMock);
    }

    /**
     * Test get all images success.
     * 
     * @return void
     */
    public function testGetAllImagesSuccess()
    {
        $this->imageRepositoryMock->method('getAll')->willReturn($this->createMock(LengthAwarePaginator::class));
        $images = $this->service->getAll([]);

        $this->assertInstanceOf(LengthAwarePaginator::class, $images);
    }

    /**
     * Test get all images will throw exception.
     * 
     * @return void
     */
    public function testGetAllImagesWillThrownException()
    {
        $this->imageRepositoryMock->method('getAll')->willThrowException(new Exception('Error'));
        Log::shouldReceive('channel->error')->once();

        $images = $this->service->getAll([]);
        $this->assertNull($images);
    }

    /**
     * Test get image by id success.
     * 
     * @return void
     */
    public function testGetImageByIdSuccess()
    {
        $this->imageRepositoryMock->method('getById')->willReturn($this->createMock(Image::class));
        $image = $this->service->getById(1);

        $this->assertInstanceOf(Image::class, $image);
    }

    /**
     * Test get image by id will thrown exception.
     * 
     * @return void
     */
    public function testGetImageByIdWillThrownException()
    {
        $this->imageRepositoryMock->method('getById')->willThrowException(new Exception('Error'));
        Log::shouldReceive('channel->error')->once();

        $image = $this->service->getById(1);
        $this->assertNull($image);
    }

    /**
     * Test get image by id will return custom exception.
     * 
     * @return void
     */
    public function testGetImageByIdWillReturnCustomException()
    {
        $this->imageRepositoryMock->method('getById')->willReturn(null);

        $image = $this->service->getById(1);
        $this->assertInstanceOf(CustomException::class, $image);
    }

    /**
     * Test create image success.
     * 
     * @return void
     */
    public function testCreateImageSuccess()
    {
        $uploadedImageMock = $this->createMock(\Illuminate\Http\UploadedFile::class);
        $uploadedImageMock->method('getClientOriginalName')->willReturn('image.jpg');
        Upload::shouldReceive('as->upload')->once()
            ->andReturn([
                'file'=>'image.jpg', 
                'url'=>'http://localhost/image.jpg'
            ]);
        $this->imageRepositoryMock->method('create')->willReturn($this->createMock(Image::class));
        $image = $this->service->create([
            'name' => '',
            'file' => $uploadedImageMock
        ]);

        $this->assertInstanceOf(Image::class, $image);
    }

    /**
     * Test create image will throw exception.
     * 
     * @return void
     */
    public function testCreateImageWillThrownException()
    {
        $this->imageRepositoryMock->method('create')->willThrowException(new Exception('Error'));
        Log::shouldReceive('channel->error')->once();

        $image = $this->service->create([]);
        $this->assertNull($image);
    }

    /**
     * Test update image success.
     * 
     * @return void
     */
    public function testUpdateImageSuccess()
    {
        $uploadedImageMock = $this->createMock(\Illuminate\Http\UploadedFile::class);
        $uploadedImageMock->method('getClientOriginalName')->willReturn('image.jpg');
        Upload::shouldReceive('as->upload')->once()
            ->andReturn([
                'file'=>'image.jpg', 
                'url'=>'http://localhost/image.jpg'
            ]);
        Upload::shouldReceive('delete')->once()
            ->andReturn(true);
        $this->imageRepositoryMock->method('getById')->willReturn($this->createMock(Image::class));
        $this->imageRepositoryMock->method('update')->willReturn($this->createMock(Image::class));
        $image = $this->service->update(1, [
            'name' => '',
            'file' => $uploadedImageMock
        ]);

        $this->assertInstanceOf(Image::class, $image);
    }

    /**
     * Test update image will throw exception.
     * 
     * @return void
     */
    public function testUpdateImageWillThrownException()
    {
        $this->imageRepositoryMock->method('getById')->willReturn($this->createMock(Image::class));
        $this->imageRepositoryMock->method('update')->willThrowException(new Exception('Error'));
        Log::shouldReceive('channel->error')->once();

        $image = $this->service->update(1, ['file' => '']);
        $this->assertNull($image);
    }

    /**
     * Test update image will return custom exception.
     * 
     * @return void
     */
    public function testUpdateImageWillReturnCustomException()
    {
        $this->imageRepositoryMock->method('getById')->willReturn(null);

        $image = $this->service->update(1, ['file' => '']);
        $this->assertInstanceOf(CustomException::class, $image);
    }

    /**
     * Test delete image success.
     * 
     * @return void
     */
    public function testDeleteImageSuccess()
    {
        $this->imageRepositoryMock->method('getById')->willReturn($this->createMock(Image::class));
        $this->imageRepositoryMock->method('delete')->willReturn(true);
        $image = $this->service->delete(1);

        $this->assertTrue($image);
    }

    /**
     * Test delete image will throw exception.
     * 
     * @return void
     */
    public function testDeleteImageWillThrownException()
    {
        $this->imageRepositoryMock->method('getById')->willReturn($this->createMock(Image::class));
        $this->imageRepositoryMock->method('delete')->willThrowException(new Exception('Error'));
        Log::shouldReceive('channel->error')->once();

        $image = $this->service->delete(1);
        $this->assertNull($image);
    }

    /**
     * Test delete image will return custom exception.
     * 
     * @return void
     */
    public function testDeleteImageWillReturnCustomException()
    {
        $this->imageRepositoryMock->method('getById')->willReturn(null);

        $image = $this->service->delete(1);
        $this->assertInstanceOf(CustomException::class, $image);
    }
}
