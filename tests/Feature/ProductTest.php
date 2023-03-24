<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test get all products success.
     *
     * @return void
     */
    public function testGetAllProductsSuccess()
    {
        $response = $this->get('/api/v1/product');

        $response->assertStatus(200);
    }

    /**
     * Test get product by id success.
     *
     * @return void
     */
    public function testGetProductByIdSuccess()
    {
        $product = Product::factory()->create(['enable' => true]);
        $response = $this->get('/api/v1/product/'.$product->id);

        $response->assertStatus(200);
    }

    /**
     * Test create product success.
     *
     * @return void
     */
    public function testCreateProductSuccess()
    {
        $categories = Category::factory()->count(2)->create(['enable' => true]);
        $data = [
            'name' => 'Product 1',
            'description' => 'Product 1 description',
            'files' => [
                UploadedFile::fake()->image('image.jpg'),
            ],
            'category_ids' => $categories->pluck('id')->toArray()
        ];
        $response = $this->post('/api/v1/product', $data);

        $response->assertStatus(200);
    }

    /**
     * Test create will fail if category ids is not found
     * 
     * @return void
     */
    public function testCreateProductFailIfCategoryIdsNotFound()
    {
        $data = [
            'name' => 'Product 1',
            'description' => 'Product 1 description',
            'files' => [
                UploadedFile::fake()->image('image.jpg'),
            ],
            'category_ids' => [1, 2]
        ];
        $response = $this->post('/api/v1/product', $data);

        $response->assertStatus(400);
    }

    /**
     * Test update product success.
     * 
     * @return void
     */
    public function testUpdateProductSuccess()
    {
        $product = Product::factory()->create();
        $categories = Category::factory()->count(2)->create(['enable' => true]);
        $data = [
            'name' => 'Product 1',
            'description' => 'Product 1 description',
            'files' => [
                UploadedFile::fake()->image('image.jpg'),
            ],
            'category_ids' => $categories->pluck('id')->toArray()
        ];
        $response = $this->put('/api/v1/product/'.$product->id, $data);

        $response->assertStatus(200);
    }

    /**
     * Test update will fail if category ids is not found
     * 
     * @return void
     */
    public function testUpdateProductFailIfCategoryIdsNotFound()
    {
        $product = Product::factory()->create(['enable' => true]);
        $data = [
            'name' => 'Product 1',
            'description' => 'Product 1 description',
            'files' => [
                UploadedFile::fake()->image('image.jpg'),
            ],
            'category_ids' => [1, 2]
        ];
        $response = $this->put('/api/v1/product/'.$product->id, $data);

        $response->assertStatus(400);
    }

    /**
     * Test update will fail if product not found
     * 
     * @return void
     */
    public function testUpdateProductFailIfProductNotFound()
    {
        $categories = Category::factory()->count(2)->create(['enable' => true]);
        $data = [
            'name' => 'Product 1',
            'description' => 'Product 1 description',
            'files' => [
                UploadedFile::fake()->image('image.jpg'),
            ],
            'category_ids' => $categories->pluck('id')->toArray()
        ];
        $response = $this->put('/api/v1/product/1', $data);

        $response->assertStatus(404);
    }

    /**
     * Test delete product success.
     * 
     * @return void
     */
    public function testDeleteProductSuccess()
    {
        $product = Product::factory()->create(['enable' => true]);
        $response = $this->delete('/api/v1/product/'.$product->id);

        $response->assertStatus(200);
    }

    /**
     * Test delete product fail if product not found.
     * 
     * @return void
     */
    public function testDeleteProductFailIfProductNotFound()
    {
        $response = $this->delete('/api/v1/product/1');

        $response->assertStatus(404);
    }
}
