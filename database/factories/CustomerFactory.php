<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'John',
            'email' => 'John@email.com',
            'phone' => '4356247',
            'address' => 'Address',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
