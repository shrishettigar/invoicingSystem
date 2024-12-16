<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    /**
     * Get all products.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'products' => $this->productService->getAll(),
            'status' => 'success'
        ], 200);
    }

    /**
     * Store a new product.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $productData = $request->all();
            $product = $this->productService->create($productData);
            return response()->json([
                'product' => $product,
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
     * Show a single product
     *
     * @param int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function show(int $id): JsonResponse
    {
        try {
            $product = $this->productService->getById($id);
            return response()->json([
                'product' => $product,
                'status' => 'success'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 404);
        }
    }

    /**
     * Update a product
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws ValidationException
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $productData = $request->all();
            $product = $this->productService->update($id, $productData);
            return response()->json([
                'product' => $product,
                'status' => 'success'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 404);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 422);
        }
    }

    /**
     * Delete a product
     *
     * @param int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $result = $this->productService->delete($id);
            return response()->json([
                'deleted' => $result,
                'status' => 'success'
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 404);
        }
    }
}
