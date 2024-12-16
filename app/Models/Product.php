<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'available_quantity',
        'category_id',
    ];

    /**
     * Get the category associated with the product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Validate product data.
     *
     * @param array $data
     * @return void
     * @throws ValidationException
     */
    public static function validate(array $data): void
    {
        $rules =[
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'available_quantity' => 'required|integer|min:0', 
            'category_id' => 'required|exists:categories,id',
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Find a product by its ID or throw an exception if not found.
     *
     * @param int $id
     * @return Product
     * @throws ModelNotFoundException
     */
    public static function findOrFailWithValidation(int $id): Product
    {
        $product = Product::find($id);
        if (!$product) {
            throw new ModelNotFoundException("Product with ID {$id} not found.");
        }
        return $product;
    }

}
