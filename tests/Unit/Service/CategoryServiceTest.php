<?php

namespace Tests\Unit\Service;

use App\Exceptions\CustomException;
use App\Models\Category;
use App\Models\Product;
use App\Repository\Category\CategoryRepositoryInterface;
use App\Repository\Product\ProductRepositoryInterface;
use App\Service\CategoryService;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    /**
     * @var CategoryService
     */
    private $service;

    /**
     * @var MockObject
     */
    private $categoryRepositoryMock;

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
        $this->categoryRepositoryMock = $this->createMock(CategoryRepositoryInterface::class);
        $this->service = new CategoryService($this->categoryRepositoryMock, $this->productRepositoryMock);
    }
    /**
     * Test get all categories success.
     * 
     * @return void
     */
    public function testGetAllCategoriesSuccess()
    {
        $this->categoryRepositoryMock->method('getAll')->willReturn($this->createMock(LengthAwarePaginator::class));
        $categories = $this->service->getAll([]);

        $this->assertInstanceOf(LengthAwarePaginator::class, $categories);
    }

    /**
     * Test get all categories will throw exception.
     * 
     * @return void
     */
    public function testGetAllCategoriesWillThrownException()
    {
        $this->categoryRepositoryMock->method('getAll')->willThrowException(new Exception('Error'));
        Log::shouldReceive('channel->error')->once();

        $categories = $this->service->getAll([]);
        $this->assertNull($categories);
    }

    /**
     * Test get category by id success.
     * 
     * @return void
     */
    public function testGetCategoryByIdSuccess()
    {
        $this->categoryRepositoryMock->method('getById')->willReturn($this->createMock(Category::class));
        $category = $this->service->getById(1);

        $this->assertInstanceOf(Category::class, $category);
    }

    /**
     * Test get category by id will thrown exception.
     * 
     * @return void
     */
    public function testGetCategoryByIdWillThrownException()
    {
        $this->categoryRepositoryMock->method('getById')->willThrowException(new Exception('Error'));
        Log::shouldReceive('channel->error')->once();

        $category = $this->service->getById(1);
        $this->assertNull($category);
    }

    /**
     * Test get category by id will return custom exception.
     * 
     * @return void
     */
    public function testGetCategoryByIdWillReturnCustomException()
    {
        $this->categoryRepositoryMock->method('getById')->willReturn(null);
        $category = $this->service->getById(1);

        $this->assertInstanceOf(CustomException::class, $category);
    }

    /**
     * Test create category success.
     * 
     * @return void
     */
    public function testCreateCategorySuccess()
    {
        $product = (new Product())->forceFill(['id' => 1]);
        $products = Collection::make([$product]);
        $this->productRepositoryMock->method('getByIds')->willReturn($products);
        $this->categoryRepositoryMock->method('create')->willReturn($this->createMock(Category::class));
        $category = $this->service->create([
            'name' => 'Category 1',
            'product_ids' => [$product->id],
        ]);

        $this->assertInstanceOf(Category::class, $category);
    }

    /**
     * Test create category will return custom exception.
     * 
     * @return void
     */
    public function testCreateCategoryWillReturnCustomException()
    {
        $this->productRepositoryMock->method('getByIds')->willReturn(null);
        $category = $this->service->create([
            'name' => 'Category 1',
            'product_ids' => [1],
        ]);

        $this->assertInstanceOf(CustomException::class, $category);
    }

    /**
     * Test create category will thrown exception.
     * 
     * @return void
     */
    public function testCreateCategoryWillThrownException()
    {
        $product = (new Product())->forceFill(['id' => 1]);
        $products = Collection::make([$product]);
        $this->productRepositoryMock->method('getByIds')->willReturn($products);
        $this->categoryRepositoryMock->method('create')->willThrowException(new Exception('Error'));
        Log::shouldReceive('channel->error')->once();

        $category = $this->service->create([
            'name' => 'Category 1',
            'product_ids' => [$product->id],
        ]);
        $this->assertNull($category);
    }

    /**
     * Test update category success.
     * 
     * @return void
     */
    public function testUpdateCategorySuccess()
    {
        $product = (new Product())->forceFill(['id' => 1]);
        $products = Collection::make([$product]);
        $this->productRepositoryMock->method('getByIds')->willReturn($products);
        $this->categoryRepositoryMock->method('getById')->willReturn($this->createMock(Category::class));
        $this->categoryRepositoryMock->method('update')->willReturn($this->createMock(Category::class));
        $category = $this->service->update(1, [
            'name' => 'Category 1',
            'product_ids' => [$product->id],
        ]);

        $this->assertInstanceOf(Category::class, $category);
    }

    /**
     * Test update category will return custom exception.
     * 
     * @return void
     */
    public function testUpdateCategoryWillReturnCustomException()
    {
        $this->productRepositoryMock->method('getByIds')->willReturn(null);
        $category = $this->service->update(1, [
            'name' => 'Category 1',
            'product_ids' => [1],
        ]);

        $this->assertInstanceOf(CustomException::class, $category);
    }

    /**
     * Test update category will thrown exception.
     * 
     * @return void
     */
    public function testUpdateCategoryWillThrownException()
    {
        $product = (new Product())->forceFill(['id' => 1]);
        $products = Collection::make([$product]);
        $this->productRepositoryMock->method('getByIds')->willReturn($products);
        $this->categoryRepositoryMock->method('getById')->willReturn($this->createMock(Category::class));
        $this->categoryRepositoryMock->method('update')->willThrowException(new Exception('Error'));
        Log::shouldReceive('channel->error')->once();

        $category = $this->service->update(1, [
            'name' => 'Category 1',
            'product_ids' => [$product->id],
        ]);
        $this->assertNull($category);
    }

    /**
     * Test delete category success.
     * 
     * @return void
     */
    public function testDeleteCategorySuccess()
    {
        $this->categoryRepositoryMock->method('getById')->willReturn($this->createMock(Category::class));
        $this->categoryRepositoryMock->method('delete')->willReturn(true);
        $result = $this->service->delete(1);

        $this->assertTrue($result);
    }

    /**
     * Test delete category will return custom exception.
     * 
     * @return void
     */
    public function testDeleteCategoryWillReturnCustomException()
    {
        $this->categoryRepositoryMock->method('getById')->willReturn(null);
        $result = $this->service->delete(1);

        $this->assertInstanceOf(CustomException::class, $result);
    }

    /**
     * Test delete category will thrown exception.
     * 
     * @return void
     */
    public function testDeleteCategoryWillThrownException()
    {
        $this->categoryRepositoryMock->method('getById')->willReturn($this->createMock(Category::class));
        $this->categoryRepositoryMock->method('delete')->willThrowException(new Exception('Error'));
        Log::shouldReceive('channel->error')->once();

        $result = $this->service->delete(1);
        $this->assertNull($result);
    }
}
