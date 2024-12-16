<?php

namespace Tests\Feature\Controllers;

use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $categoryService;

    public function setUp(): void
    {
        parent::setUp();
        // Mocking CategoryService
        $this->categoryService = Mockery::mock(CategoryService::class);
        $this->app->instance(CategoryService::class, $this->categoryService);
    }

    /** @test */
    public function it_can_get_all_categories()
    {
        $categories = Category::factory()->count(3)->make();
        
        // Mocking the return value of categoryService->getAll()
        $this->categoryService
            ->shouldReceive('getAll')
            ->once()
            ->andReturn($categories);

        $response = $this->getJson('/api/categories');

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                     'status' => 'success',
                     'categories' => $categories->toArray(),
                 ]);
    }

    /** @test */
    public function it_can_create_a_category()
    {
        $categoryData = [
            'name' => 'Electronics',
            'description' => 'Tech gadgets and appliances',
        ];

        // Mocking the creation of a category
        $this->categoryService
            ->shouldReceive('create')
            ->once()
            ->with($categoryData)
            ->andReturn(new Category($categoryData));

        $response = $this->postJson('/api/categories', $categoryData);

        $response->assertStatus(Response::HTTP_CREATED)
                 ->assertJson([
                     'status' => 'success',
                     'category' => $categoryData,
                 ]);
    }

    
    /** @test */
    public function it_can_show_a_single_category()
    {
        $category = Category::factory()->create();

        $this->categoryService
            ->shouldReceive('getById')
            ->once()
            ->with($category->id)
            ->andReturn($category);

        $response = $this->getJson('/api/categories/' . $category->id);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                     'status' => 'success',
                     'category' => $category->toArray(),
                 ]);
    }

    /** @test */
    public function it_returns_404_if_category_not_found()
    {
        $this->categoryService
            ->shouldReceive('getById')
            ->once()
            ->with(999)
            ->andThrow(new ModelNotFoundException('Category with ID 999 not found.'));

        $response = $this->getJson('/api/categories/999');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
                 ->assertJson([
                     'status' => 'failed',
                     'message' => 'Category with ID 999 not found.',
                 ]);
    }

    /** @test */
    public function it_can_update_a_category()
    {
        $category = Category::factory()->create();
        $updatedData = [
            'name' => 'Updated Category',
            'description' => 'Updated description',
        ];

        // Mocking the update method
        $this->categoryService
            ->shouldReceive('update')
            ->once()
            ->with($category->id, $updatedData)
            ->andReturn(new Category($updatedData));

        $response = $this->putJson('/api/categories/' . $category->id, $updatedData);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                     'status' => 'success',
                     'category' => $updatedData,
                 ]);
    }

    /** @test */
    public function it_returns_404_if_category_not_found_on_update()
    {
        $this->categoryService
            ->shouldReceive('update')
            ->once()
            ->with(999, [])
            ->andThrow(new ModelNotFoundException('Category with ID 999 not found.'));

        $response = $this->putJson('/api/categories/999', []);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
                 ->assertJson([
                     'status' => 'failed',
                     'message' => 'Category with ID 999 not found.',
                 ]);
    }

    /** @test */
    public function it_can_delete_a_category()
    {
        $category = Category::factory()->create();

        // Mocking the delete method
        $this->categoryService
            ->shouldReceive('delete')
            ->once()
            ->with($category->id)
            ->andReturn(true);

        $response = $this->deleteJson('/api/categories/' . $category->id);

        $response->assertStatus(Response::HTTP_OK)
                 ->assertJson([
                     'status' => 'success',
                     'deleted' => true,
                 ]);
    }

    /** @test */
    public function it_returns_404_if_category_not_found_on_delete()
    {
        $this->categoryService
            ->shouldReceive('delete')
            ->once()
            ->with(999)
            ->andThrow(new ModelNotFoundException('Category with ID 999 not found.'));

        $response = $this->deleteJson('/api/categories/999');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
                 ->assertJson([
                     'status' => 'failed',
                     'message' => 'Category with ID 999 not found.',
                 ]);
    }
}
