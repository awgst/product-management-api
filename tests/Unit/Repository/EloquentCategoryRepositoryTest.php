<?php

namespace Tests\Unit\Repository;

use App\Models\Category;
use App\Repository\Category\EloquentCategoryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentCategoryRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var EloquenCategoryRepository
     */
    private $repository;

    /**
     * Setup the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentCategoryRepository(new Category());
    }

    /**
     * Test get all categories success.
     * 
     * @return void
     */
    public function testGetAllCategoriesSuccess()
    {
        Category::factory()->count(10)->create();
        $categories = $this->repository->getAll([]);

        $this->assertInstanceOf(LengthAwarePaginator::class, $categories);
    }

    /**
     * Test get all categories with filter success.
     * 
     * @return void
     */
    public function testGetAllCategoriesWithFilterSuccess()
    {
        Category::factory()->count(10)->create();
        $categories = $this->repository->getAll(['name' => 'Category 1']);

        $this->assertInstanceOf(LengthAwarePaginator::class, $categories);
    }

    /**
     * Test get category by id success.
     * 
     * @return void
     */
    public function testGetCategoryByIdSuccess()
    {
        $category = Category::factory()->create();
        $category = $this->repository->getById($category->id);

        $this->assertInstanceOf(Category::class, $category);
    }

    /**
     * Test create category success.
     * 
     * @return void
     */
    public function testCreateCategorySuccess()
    {
        $category = $this->repository->create([
            'name' => 'Category 1',
        ]);

        $this->assertInstanceOf(Category::class, $category);
    }

    /**
     * Test update category success.
     * 
     * @return void
     */
    public function testUpdateCategorySuccess()
    {
        $category = Category::factory()->create();
        $category = $this->repository->update($category, [
            'name' => 'Category 1',
        ]);

        $this->assertInstanceOf(Category::class, $category);
    }

    /**
     * Test delete category success.
     * 
     * @return void
     */
    public function testDeleteCategorySuccess()
    {
        $category = Category::factory()->create();
        $this->repository->delete($category);

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    /**
     * Test get categories by ids success.
     * 
     * @return void
     */
    public function testGetCategoriesByIdsSuccess()
    {
        $categories = Category::factory()->count(10)->create();
        $categories = $this->repository->getByIds($categories->pluck('id')->toArray());

        $this->assertInstanceOf(Collection::class, $categories);
    }
}
