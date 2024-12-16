<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class InvoiceService
{
    /**
     * CGenerate Invoice
     *
     * @param  Request $request 
     * @return Invoice
     * @throws ModelNotFoundException
     */
    public function generateInvoice(array $data): Invoice
    {
        $rules = [
            'customer_id' => 'required|exists:customers,id',
            'flat_discount' => 'nullable|numeric',
            'item_wise_discounts' => 'nullable|array',
            'payment_method' => 'required|in:cash,credit,paypal',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $customerId = $data['customer_id'];
        $flatDiscount = $data['flat_discount'] ?? 0;
        $itemWiseDiscounts = $data['item_wise_discounts'] ?? [];
        $paymentMethod = $data['payment_method'];

        $cart = Cart::where('customer_id', $customerId)->first();

        if (!$cart) {
            throw new ModelNotFoundException("Cart not found for this customer");
        }

       
        $cartItems = CartItem::where('cart_id', $cart->id)->get();

        $subTotal = 0;
        $taxAmount = 0;
        $items = [];

        foreach ($cartItems as $cartItem) {
            $product = $cartItem->product;
            $itemDiscount = isset($itemWiseDiscounts[$product->id]) ? $itemWiseDiscounts[$product->id] : 0;

            if ($product->available_quantity < $cartItem->quantity) {
                $validator = \Validator::make([], []);
                $validator->errors()->add('quantity', "Not enough stock for " . $product->name);
                throw new ValidationException($validator);
            }

            $priceAfterDiscount = $product->price - $itemDiscount;
            $totalPrice = $priceAfterDiscount * $cartItem->quantity;
            $subTotal += $totalPrice;
            $items[] = [
                'product_id' => $product->id,
                'quantity' => $cartItem->quantity,
                'price' => $product->price,
                'discount' => $itemDiscount,
                'total_price' => $totalPrice
            ];
        }

        // Apply flat discount to the subtotal
        $subTotalAfterFlatDiscount = $subTotal - $flatDiscount;

        // Calculate tax (e.g., 10% of subtotal after flat discount)
        $taxAmount = $subTotalAfterFlatDiscount * 0.1;

        // Calculate final total (Subtotal + Tax - Flat Discount)
        $totalAmount = $subTotalAfterFlatDiscount + $taxAmount;

        $customer = Customer::findOrFail($customerId);
        $invoice = Invoice::create([
            'customer_id' => $customer->id,
            'sub_total' => $subTotal,
            'flat_discount' => $flatDiscount,
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'payment_method' => $paymentMethod
        ]);

        foreach ($items as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'discount' => $item['discount'],
                'total_price' => $item['total_price']
            ]);
        }

        // Clear the cart after generating the invoice
        $cart->items()->delete();
        
        return $invoice->load('items');
    }
}
