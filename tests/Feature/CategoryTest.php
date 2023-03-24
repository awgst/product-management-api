<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test get all categories success.
     *
     * @return void
     */
    public function testGetAllCategoriesSuccess()
    {
        $response = $this->get('/api/v1/category');

        $response->assertStatus(200);
    }

    /**
     * Test get category by id success.
     *
     * @return void
     */
    public function testGetCategoryByIdSuccess()
    {
        $category = Category::factory()->create(['enable' => true]);
        $response = $this->get('/api/v1/category/' . $category->id);

        $response->assertStatus(200);
    }

    /**
     * Test create category success.
     *
     * @return void
     */
    public function testCreateCategorySuccess()
    {
        $products = Product::factory()->count(2)->create([
            'enable' => true
        ]);
        $data = [
            'name' => 'Category 1',
            'product_ids' => $products->pluck('id')->toArray()
        ];
        $response = $this->post('/api/v1/category', $data);

        $response->assertStatus(200);
    }

    /**
     * Test create will fail if product ids is not found
     * 
     * @return void
     */
    public function testCreateCategoryFailIfRequestDataIsInvalid()
    {
        $data = [
            'name' => 'Category 1',
            'product_ids' => [1, 2]
        ];
        $response = $this->post('/api/v1/category', $data);

        $response->assertStatus(400);
    }

    /**
     * Test update category success.
     *
     * @return void
     */
    public function testUpdateCategorySuccess()
    {
        $category = Category::factory()->create();
        $products = Product::factory()->count(2)->create([
            'enable' => true
        ]);
        $data = [
            'name' => 'Category 1',
            'product_ids' => $products->pluck('id')->toArray()
        ];
        $response = $this->put('/api/v1/category/' . $category->id, $data);

        $response->assertStatus(200);
    }

    /**
     * Test update category fail if category is not found.
     * 
     * @return void
     */
    public function testUpdateCategoryFailIfCategoryIsNotFound()
    {
        $products = Product::factory()->count(2)->create([
            'enable' => true
        ]);
        $data = [
            'name' => 'Category 1',
            'product_ids' => $products->pluck('id')->toArray()
        ];
        $response = $this->put('/api/v1/category/1', $data);

        $response->assertStatus(404);
    }

    /**
     * Test delete category success.
     *
     * @return void
     */
    public function testDeleteCategorySuccess()
    {
        $category = Category::factory()->create(['enable' => true]);
        $response = $this->delete('/api/v1/category/' . $category->id);

        $response->assertStatus(200);
    }

    /**
     * Test delete category fail if category is not found.
     * 
     * @return void
     */
    public function testDeleteCategoryFailIfCategoryIsNotFound()
    {
        $response = $this->delete('/api/v1/category/1');

        $response->assertStatus(404);
    }
}
