<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    protected CategoryService $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Get all categories.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'categories' => $this->categoryService->getAll(),
            'status' => 'success'
        ], 200);
    }

    /**
     * Store a new category.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $categoryData = $request->all();
            $category = $this->categoryService->create($categoryData);
            return response()->json([
                'category' => $category,
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
     * Show a single category.
     *
     * @param int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function show(int $id): JsonResponse
    {
        try {
            $category = $this->categoryService->getById($id);
            return response()->json([
                'category' => $category,
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
     * Update a category.
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
            $categoryData = $request->all();
            $category = $this->categoryService->update($id, $categoryData);
            return response()->json([
                'category' => $category,
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
     * Delete a category.
     *
     * @param int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $result = $this->categoryService->delete($id);
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
