<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
use Exception;

class CartService
{
    /**
     * Add product to cart
     *
     * @param array $data
     * @return JsonResponse
     * @throws ValidationException
     */
    public function addProductToCart(array $data): CartItem
    {

        $rules = [
            'customer_id' => 'required|integer|min:1|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ];


        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $customerId = $data['customer_id'];
        $productId = $data['product_id'];
        $quantity = $data['quantity'];
 
        $cart = Cart::firstOrCreate(['customer_id' => $customerId]);
        $product = Product::findOrFail($productId);

        if ($product->available_quantity < $quantity) {
            $validator = \Validator::make([], []);
            $validator->errors()->add('quantity', 'Insufficient stock!!!. Available stock: ' . $product->available_quantity);
            throw new ValidationException($validator);
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $cartItem->update(['quantity' => $cartItem->quantity + $quantity]);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => $quantity,
            ]);
        }

        $product->decrement('available_quantity', $quantity);

        return $cartItem ?? CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->first();
    }

    /**
     * Get customers cart items
     *
     * @param int $cartItemId
     * @return bool
     * @throws ValidationException
     */
    public function deleteCartItem(int $cartItemId): bool
    {
        $cartItem = CartItem::findOrFailWithValidation($cartItemId);
        $product = $cartItem->product;

        // Restore the product's available quantity when an item is removed from the cart
        $product->increment('available_quantity', $cartItem->quantity);

        $cartItem->delete();
        return true;
    }

    /**
     * Get customers cart items
     *
     * @param int $customerId
     * @return \Illuminate\Database\Eloquent\Collection|CartItem[]
     * @throws ValidationException
     */
    public function getCartItemsByCustomerId(int $customerId)
    {
         $cart = Cart::where('customer_id', $customerId)->first();
         if (!$cart) {
            throw new ModelNotFoundException("Cart not found for this customer");
         }
         return $cart->items;
    }
}
