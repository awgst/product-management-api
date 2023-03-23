<?php

namespace Tests\Unit\Service;

use App\Exceptions\CustomException;
use App\Models\Category;
use App\Models\Image;
use App\Models\Product;
use App\Repository\Product\ProductRepositoryInterface;
use App\Service\CategoryService;
use App\Service\ImageService;
use App\Service\ProductService;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class ProductServiceTest extends TestCase
{
    /**
     * @var ProductService
     */
    private $service;

    /**
     * @var MockObject
     */
    private $categoryServiceMock;

    /**
     * @var MockObject
     */
    private $imageServiceMock;

    /**
     * @var MockObject
     */
    private $productRepositoryMock;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->productRepositoryMock = $this->createMock(ProductRepositoryInterface::class);
        $this->categoryServiceMock = $this->createMock(CategoryService::class);
        $this->imageServiceMock = $this->createMock(ImageService::class);
        $this->service = new ProductService($this->productRepositoryMock, $this->categoryServiceMock, $this->imageServiceMock);
    }

    /**
     * Test get all products success.
     * 
     * @return void
     */
    public function testGetAllProductsSuccess()
    {
        $this->productRepositoryMock->method('getAll')->willReturn($this->createMock(LengthAwarePaginator::class));
        $products = $this->service->getAll([]);

        $this->assertInstanceOf(LengthAwarePaginator::class, $products);
    }

    /**
     * Test get all products will thrown exception.
     * 
     * @return void
     */
    public function testGetAllProductsWillThrownException()
    {
        $this->productRepositoryMock->method('getAll')->willThrowException(new Exception('Error'));
        Log::shouldReceive('channel->error')->once();

        $products = $this->service->getAll([]);
        $this->assertNull($products);
    }

    /**
     * Test get product by id success.
     * 
     * @return void
     */
    public function testGetProductByIdSuccess()
    {
        $this->productRepositoryMock->method('getById')->willReturn($this->createMock(Product::class));
        $product = $this->service->getById(1);

        $this->assertInstanceOf(Product::class, $product);
    }

    /**
     * Test get product by id will thrown exception.
     * 
     * @return void
     */
    public function testGetProductByIdWillThrownException()
    {
        $this->productRepositoryMock->method('getById')->willThrowException(new Exception('Error'));
        Log::shouldReceive('channel->error')->once();

        $product = $this->service->getById(1);
        $this->assertNull($product);
    }

    /**
     * Test get product by id will return custom exception.
     * 
     * @return void
     */
    public function testGetProductByIdWillReturnCustomException()
    {
        $this->productRepositoryMock->method('getById')->willReturn(null);

        $product = $this->service->getById(1);
        $this->assertInstanceOf(CustomException::class, $product);
    }

    /**
     * Test create product success.
     * 
     * @return void
     */
    public function testCreateProductSuccess()
    {
        $category = (new Category())->forceFill(['id' => 1]);
        $categories = new Collection([$category]);
        $this->categoryServiceMock->method('getByIds')->willReturn($categories);
        $this->imageServiceMock->method('create')->willReturn($this->createMock(Image::class));
        $this->productRepositoryMock->method('create')->willReturn($this->createMock(Product::class));
        $product = $this->service->create([
            'name' => 'Product 1',
            'description' => 'Description 1',
            'category_ids' => [$category->id],
            'files' => []
        ]);

        $this->assertInstanceOf(Product::class, $product);
    }

    /**
     * Test create product will thrown exception.
     * 
     * @return void
     */
    public function testCreateProductWillThrownException()
    {
        $category = (new Category())->forceFill(['id' => 1]);
        $categories = new Collection([$category]);
        $this->categoryServiceMock->method('getByIds')->willReturn($categories);
        $this->imageServiceMock->method('create')->willReturn($this->createMock(Image::class));
        $this->productRepositoryMock->method('create')->willThrowException(new Exception('Error'));
        $product = $this->service->create([
            'name' => 'Product 1',
            'description' => 'Description 1',
            'category_ids' => [$category->id],
            'files' => []
        ]);

        $product = $this->service->create([]);
        $this->assertNull($product);
    }

    /**
     * Test create product will return custom exception.
     * 
     * @return void
     */
    public function testCreateProductWillReturnCustomException()
    {
        $this->categoryServiceMock->method('getByIds')->willReturn(null);

        $product = $this->service->create([
            'name' => 'Product 1',
            'description' => 'Description 1',
            'category_ids' => [1],
            'files' => []
        ]);
        $this->assertInstanceOf(CustomException::class, $product);
    }

    /**
     * Test update product success.
     * 
     * @return void
     */
    public function testUpdateProductSuccess()
    {
        $category = (new Category())->forceFill(['id' => 1]);
        $categories = new Collection([$category]);
        $this->productRepositoryMock->method('getById')->willReturn($this->createMock(Product::class));
        $this->categoryServiceMock->method('getByIds')->willReturn($categories);
        $this->imageServiceMock->method('create')->willReturn($this->createMock(Image::class));
        $this->productRepositoryMock->method('update')->willReturn($this->createMock(Product::class));
        $product = $this->service->update(1, [
            'name' => 'Product 1',
            'description' => 'Description 1',
            'category_ids' => [$category->id],
            'files' => []
        ]);

        $this->assertInstanceOf(Product::class, $product);
    }

    /**
     * Test update product will thrown exception.
     * 
     * @return void
     */
    public function testUpdateProductWillThrownException()
    {
        $category = (new Category())->forceFill(['id' => 1]);
        $categories = new Collection([$category]);
        $this->productRepositoryMock->method('getById')->willReturn($this->createMock(Product::class));
        $this->categoryServiceMock->method('getByIds')->willReturn($categories);
        $this->imageServiceMock->method('create')->willReturn($this->createMock(Image::class));
        $this->productRepositoryMock->method('update')->willThrowException(new Exception('Error'));
        $product = $this->service->update(1, [
            'name' => 'Product 1',
            'description' => 'Description 1',
            'category_ids' => [$category->id],
            'files' => []
        ]);

        $product = $this->service->update(1, []);
        $this->assertNull($product);
    }

    /**
     * Test update product will return custom exception.
     * 
     * @return void
     */
    public function testUpdateProductWillReturnCustomException()
    {
        $this->productRepositoryMock->method('getById')->willReturn(null);

        $product = $this->service->update(1, [
            'name' => 'Product 1',
            'description' => 'Description 1',
            'category_ids' => [1],
            'files' => []
        ]);
        $this->assertInstanceOf(CustomException::class, $product);
    }

    /**
     * Test delete product success.
     * 
     * @return void
     */
    public function testDeleteProductSuccess()
    {
        $this->productRepositoryMock->method('getById')->willReturn($this->createMock(Product::class));
        $this->productRepositoryMock->method('delete')->willReturn(true);
        $result = $this->service->delete(1);

        $this->assertTrue($result);
    }

    /**
     * Test delete product will thrown exception.
     * 
     * @return void
     */
    public function testDeleteProductWillThrownException()
    {
        $this->productRepositoryMock->method('getById')->willReturn($this->createMock(Product::class));
        $this->productRepositoryMock->method('delete')->willThrowException(new Exception('Error'));
        Log::shouldReceive('channel->error')->once();

        $result = $this->service->delete(1);
        $this->assertNull($result);
    }

    /**
     * Test delete product will return custom exception.
     * 
     * @return void
     */
    public function testDeleteProductWillReturnCustomException()
    {
        $this->productRepositoryMock->method('getById')->willReturn(null);
        $this->productRepositoryMock->method('delete')->willReturn(false);

        $result = $this->service->delete(1);
        $this->assertInstanceOf(CustomException::class, $result);
    }
}
