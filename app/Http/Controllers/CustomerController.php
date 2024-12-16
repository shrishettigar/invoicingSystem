<?php

namespace App\Http\Controllers;

use App\Services\CustomerService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Get all customers.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'customers' => $this->customerService->getAll(),
            'status' => 'success'
        ], 200);
    }

    /**
     * Create new customer.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $customerData = $request->all();
            $customer = $this->customerService->create($customerData);
            return response()->json([
                'customer' => $customer,
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
     * Show a single customer
     *
     * @param int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function show(int $id): JsonResponse
    {
        try {
            $customer = $this->customerService->getById($id);
            return response()->json([
                'customer' => $customer,
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
     * Update a customer
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
            $customerData = $request->all();
            $customer = $this->customerService->update($id, $customerData);
            return response()->json([
                'customer' => $customer,
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
     * Delete a customer
     *
     * @param int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $result = $this->customerService->delete($id);
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
