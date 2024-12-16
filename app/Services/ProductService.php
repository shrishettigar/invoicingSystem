<?php

namespace App\Services;

use App\Models\Product;

class ProductService
{
    /**
     * Create a new product.
     *
     * @param array $data
     * @return Product
     * @throws ValidationException
     */
    public function create(array $data): Product
    {
        Product::validate($data);
        return Product::create($data);
    }

    /**
     * Get all products.
     *
     * @return \Illuminate\Database\Eloquent\Collection|Product[]
     */
    public function getAll()
    {
        return Product::all();
    }

    /**
     * Get a product by ID.
     *
     * @param int $id
     * @return Product
     * @throws ModelNotFoundException
     */
    public function getById(int $id): Product
    {
        return Product::findOrFailWithValidation($id);
    }

    /**
     * Update an existing product
     *
     * @param int $id
     * @param array $data
     * @return Product
     * @throws ModelNotFoundException
     * @throws ValidationException
     */
    public function update(int $id, array $data): Product
    {
        $product = Product::findOrFailWithValidation($id);
        Product::validate($data);
        $product->update($data);
        return $product;
    }

    /**
     * Delete a product by ID.
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function delete(int $id): bool
    {
        $product = Product::findOrFailWithValidation($id);
        $product->delete();
        return true;
    }
}
