<?php

namespace Tests\Feature\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the category validation passes with valid data.
     *
     * @return void
     */
    public function testCategoryValidationPasses()
    {
        $data = [
            'name' => 'Electronics',
            'description' => 'All kinds of electronic gadgets',
        ];

        Category::validate($data);
        $this->assertTrue(true);
    }

    /**
     * Test that the category validation fails with invalid data.
     *
     * @return void
     */
    public function testCategoryValidationFails()
    {
        $data = [
            'name' => '',
            'description' => 'A description.',
        ];

        $this->expectException(ValidationException::class);
        Category::validate($data);
    }

    /**
     * Test that the category is found or throws ModelNotFoundException.
     *
     * @return void
     */
    public function testFindOrFailWithValidation()
    {
        $category = Category::create([
            'name' => 'Electronics',
            'description' => 'Gadgets and devices',
        ]);

        $foundCategory = Category::findOrFailWithValidation($category->id);
        $this->assertEquals($category->id, $foundCategory->id);

        $this->expectException(ModelNotFoundException::class);
        Category::findOrFailWithValidation(999); 
    }

    /**
     * Test category creation with valid data.
     *
     * @return void
     */
    public function testCategoryCreation()
    {
        $data = [
            'name' => 'Books',
            'description' => 'All types of books',
        ];

        $category = Category::create($data);
        $this->assertDatabaseHas('categories', [
            'name' => 'Books',
            'description' => 'All types of books',
        ]);
    }

    /**
     * Test category creation with missing required fields.
     *
     * @return void
     */
    public function testCategoryCreationWithMissingFields()
    {
        $data = [
            'name' => '', 
            'description' => 'Some description',
        ];

        $this->expectException(ValidationException::class);
        Category::validate($data);
    }
}
