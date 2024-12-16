<?php

namespace App\Services;

use App\Models\Category;


class CategoryService
{
    /**
     * Create a new category.
     *
     * @param array $data
     * @return Category
     * @throws ValidationException
     */
    public function create(array $data): Category
    { 
        Category::validate($data);
        return Category::create($data);
    }

    /**
     * Get all categories.
     *
     * @return \Illuminate\Database\Eloquent\Collection|Category[]
     */
    public function getAll()
    {
        return Category::all();
    }

    /**
     * Get a category by ID.
     *
     * @param int $id
     * @return Category
     * @throws ModelNotFoundException
     */
    public function getById(int $id): Category
    {
        return Category::findOrFailWithValidation($id);;
    }

    /**
     * Update an existing category.
     *
     * @param int $id
     * @param array $data
     * @return Category
     * @throws ModelNotFoundException
     * @throws ValidationException
     */
    public function update(int $id, array $data): Category
    {
        $category = Category::findOrFailWithValidation($id);
        Category::validate($data);
        $category->update($data);
        return $category;
    }

    /**
     * Delete a category by ID.
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function delete(int $id): bool
    {
        $category = Category::findOrFailWithValidation($id);
        $category->delete();
        return true;
    }
}
