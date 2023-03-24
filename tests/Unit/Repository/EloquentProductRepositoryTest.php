<?php

namespace Tests\Unit\Repository;

use App\Models\Product;
use App\Repository\Product\EloquentProductRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentProductRepositoryTest extends TestCase
{
    use RefreshDatabase;
    
    /**
     * @var EloquentProductRepository
     */
    private $repository;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentProductRepository(new Product());
    }

    /**
     * Test get all products success.
     * 
     * @return void
     */
    public function testGetAllProductsSuccess()
    {
        Product::factory()->count(10)->create();
        $products = $this->repository->getAll([]);

        $this->assertInstanceOf(LengthAwarePaginator::class, $products);
    }

    /**
     * Test get all products with filter success.
     * 
     * @return void
     */
    public function testGetAllProductsWithFilterSuccess()
    {
        Product::factory()->count(10)->create();
        $products = $this->repository->getAll(['name' => 'Product 1']);

        $this->assertInstanceOf(LengthAwarePaginator::class, $products);
    }

    /**
     * Test get product by id success.
     * 
     * @return void
     */
    public function testGetProductByIdSuccess()
    {
        $product = Product::factory()->create();
        $product = $this->repository->getById($product->id);

        $this->assertInstanceOf(Product::class, $product);
    }

    /**
     * Test create product success.
     * 
     * @return void
     */
    public function testCreateProductSuccess()
    {
        $product = Product::factory()->make();
        $product = $this->repository->create($product->toArray());

        $this->assertInstanceOf(Product::class, $product);
    }
    
    /**
     * Test update product success.
     * 
     * @return void
     */
    public function testUpdateProductSuccess()
    {
        $product = Product::factory()->create();
        $product = $this->repository->update($product, ['name' => 'Product 1']);

        $this->assertInstanceOf(Product::class, $product);
    }

    /**
     * Test delete product success.
     * 
     * @return void
     */
    public function testDeleteProductSuccess()
    {
        $product = Product::factory()->create();
        $product = $this->repository->delete($product);

        $this->assertTrue($product);
    }

    /**
     * Test get products by ids success.
     * 
     * @return void
     */
    public function testGetProductsByIdsSuccess()
    {
        $products = Product::factory()->count(10)->create();
        $products = $this->repository->getByIds($products->pluck('id')->toArray());

        $this->assertInstanceOf(Collection::class, $products);
    }
}