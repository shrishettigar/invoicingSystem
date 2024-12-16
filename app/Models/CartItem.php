<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['cart_id', 'product_id', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Find a item by its ID or throw an exception if not found.
     *
     * @param int $id
     * @return CartItem
     * @throws ModelNotFoundException
     */
    public static function findOrFailWithValidation(int $id): CartItem
    {
        $cartItem = CartItem::find($id);
        if (!$cartItem) {
            throw new ModelNotFoundException("Item with ID {$id} not found.");
        }
        return $cartItem;
    }

}
