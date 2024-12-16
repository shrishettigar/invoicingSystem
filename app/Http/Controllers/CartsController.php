<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use App\Services\InvoiceService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class CartsController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService, InvoiceService $invoiceService)
    {
        $this->cartService = $cartService;
        $this->invoiceService = $invoiceService;
    }

    /**
     * Add product to cart
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function addProductToCart(Request $request): JsonResponse
    {
        try {
            $cartData = $request->all();
            $cartItem = $this->cartService->addProductToCart($cartData);
            return response()->json([
                'cart_item' => $cartItem,
                'status' => 'success'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' =>  $e->getMessage(),
                'status' => 'failed'
            ], 422);
        }
    }

    /**
     * Delete  item from the cart
     *
     * @param  int $customer_id 
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function deleteCartItem(int $cartItemId): JsonResponse
    {
        try {
            $result = $this->cartService->deleteCartItem($cartItemId);
            return response()->json([
                'deleted' => $result,
                'status' => 'Success'
            ], 200); 
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status' => 'failed'
            ], 404);
        }
    }

    /**
     * get customer cart Items
     *
     * @param  int $customer_id 
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function getCartItems(int $customerId): JsonResponse
    {     
        try {
            $items = $this->cartService->getCartItemsByCustomerId($customerId);
            return response()->json([
                'items' => $items
            ], 200); 
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status' => 'failed'
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    /**
     * Checkout customer cart Items
     *
     * @param  Request $request 
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function checkout(Request $request): JsonResponse
    {
        try {
            $requestData = $request->all();
            $invoice = $this->invoiceService->generateInvoice($requestData);
            return response()->json([
                'Invoice' =>  $invoice,
                'status' => 'Success'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' =>  $e->getMessage(),
                'status' => 'failed'
            ], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' =>  $e->getMessage(),
                'status' => 'failed'
            ], 404);
        } 
    }
}
