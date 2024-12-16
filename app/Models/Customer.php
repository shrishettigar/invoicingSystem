<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'email', 
        'address', 
        'contact_number'
    ];

    /**
     * Validate customer data.
     *
     * @param array $data
     * @param int $id 
     * @return void
     * @throws ValidationException
     */
    public static function validate(array $data, $id = null): void
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers,email'.($id ? ",{$id}" : ''),
            'address' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
        ];
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }

    /**
     * Find a customer by its ID or throw an exception if not found.
     *
     * @param int $id
     * @return Customer
     * @throws ModelNotFoundException
     */
    public static function findOrFailWithValidation(int $id): Customer
    {
        $customer = Customer::find($id);
        if (!$customer) {
            throw new ModelNotFoundException("Customer with ID {$id} not found.");
        }
        return $customer;
    }
}
