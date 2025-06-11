<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PinjamFactory extends Factory
{
    protected $model = \App\Models\Pinjam::class;

    public function definition()
    {
        return [
            'user_id' => \App\Models\User::factory(), // Creates a related user if not provided
            'loan_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'return_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'actual_return_date' => $this->faker->optional()->dateTimeBetween('now', '+1 month'),
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected', 'returned']),
            'keterangan_peminjam' => $this->faker->sentence(),
            'keterangan_penyetuju' => $this->faker->optional()->sentence(),
        ];
    }
}

