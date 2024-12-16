<?php

namespace Tests\Unit\Services;

use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery;
use Tests\TestCase;

class CategoryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $categoryService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->categoryService = new CategoryService();
    }

    /** @test */
    public function it_creates_a_category_with_valid_data()
    {
        // Arrange: Prepare valid category data
        $data = [
            'name' => 'Test New Category',
            'description' => 'Category description'
        ];

        // Act: Call the create method
        $category = $this->categoryService->create($data);

        // Assert: Check if category is created as expected
        $this->assertEquals('Test New Category', $category->name);
        $this->assertEquals('Category description', $category->description);
    }

    /** @test */
    public function it_throws_validation_exception_when_data_is_invalid()
    {
        // Arrange: Invalid data (missing name)
        $data = [
            'description' => 'Category description'
        ];

        // Act & Assert: Check if validation exception is thrown
        $this->expectException(ValidationException::class);
        $this->categoryService->create($data);
    }

    /** @test */
    public function it_gets_all_categories()
    {
        // Arrange: Create categories in the database
        Category::factory()->count(3)->create();

        // Act: Call getAll method
        $categories = $this->categoryService->getAll();

        // Assert: Check if 3 categories are fetched
        $this->assertCount(3, $categories);
    }

    /** @test */
    public function it_gets_category_by_id()
    {
        // Arrange: Create a category
        $category = Category::factory()->create();

        // Act: Call getById method
        $foundCategory = $this->categoryService->getById($category->id);

        // Assert: Check if category is returned
        $this->assertEquals($category->id, $foundCategory->id);
    }

    /** @test */
    public function it_throws_model_not_found_exception_when_category_is_not_found_by_id()
    {
        // Act & Assert: Check if ModelNotFoundException is thrown for non-existing category
        $this->expectException(ModelNotFoundException::class);
        $this->categoryService->getById(999); // Non-existing ID
    }

    /** @test */
    public function it_updates_a_category_with_valid_data()
    {
        // Arrange: Create a category
        $category = Category::factory()->create();

        // New valid data
        $data = [
            'name' => 'Updated Category',
            'description' => 'Updated description'
        ];

        // Act: Call update method
        $updatedCategory = $this->categoryService->update($category->id, $data);

        // Assert: Check if category is updated
        $this->assertEquals('Updated Category', $updatedCategory->name);
        $this->assertEquals('Updated description', $updatedCategory->description);
    }

    /** @test */
    public function it_throws_model_not_found_exception_when_updating_non_existing_category()
    {
        // Arrange: Non-existing ID
        $data = ['name' => 'Non-existing Category', 'description' => 'Description'];

        // Act & Assert: Check if ModelNotFoundException is thrown
        $this->expectException(ModelNotFoundException::class);
        $this->categoryService->update(999, $data); // Non-existing ID
    }

    /** @test */
    public function it_deletes_a_category()
    {
        // Arrange: Create a category
        $category = Category::factory()->create();

        // Act: Call delete method
        $result = $this->categoryService->delete($category->id);

        // Assert: Check if category is deleted
        $this->assertTrue($result);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /** @test */
    public function it_throws_model_not_found_exception_when_deleting_non_existing_category()
    {
        // Act & Assert: Check if ModelNotFoundException is thrown for non-existing category
        $this->expectException(ModelNotFoundException::class);
        $this->categoryService->delete(999); // Non-existing ID
    }
}
