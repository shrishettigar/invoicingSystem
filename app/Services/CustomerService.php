<?php

namespace App\Services;

use App\Models\Customer;

class CustomerService
{
    /**
     * Create a new customer.
     *
     * @param array $data
     * @return Customer
     * @throws ValidationException
     */
    public function create(array $data): Customer
    {
        Customer::validate($data);
        return Customer::create($data);
    }

    /**
     * Get all customers.
     *
     * @return \Illuminate\Database\Eloquent\Collection|Customer[]
     */
    public function getAll()
    {
        return Customer::all();
    }

    /**
     * Get a customer by ID.
     *
     * @param int $id
     * @return Customer
     * @throws ModelNotFoundException
     */
    public function getById(int $id): Customer
    {
        return Customer::findOrFailWithValidation($id);
    }

    /**
     * Update an existing customer
     *
     * @param int $id
     * @param array $data
     * @return Customer
     * @throws ModelNotFoundException
     * @throws ValidationException
     */
    public function update(int $id, array $data): Customer
    {
        $customer = Customer::findOrFailWithValidation($id);
        Customer::validate($data, $id);
        $customer->update($data);
        return $customer;
    }

    /**
     * Delete a customer by ID.
     *
     * @param int $id
     * @return bool
     * @throws ModelNotFoundException
     */
    public function delete(int $id): bool
    {
        $customer = Customer::findOrFailWithValidation($id);
        $customer->delete();
        return true;
    }
}
