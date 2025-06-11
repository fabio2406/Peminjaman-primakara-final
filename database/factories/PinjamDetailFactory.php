<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PinjamDetailFactory extends Factory
{
    protected $model = \App\Models\PinjamDetail::class;

    public function definition()
    {
        return [
            'pinjam_id' => \App\Models\Pinjam::factory(), // Creates a related pinjam if not provided
            'item_id' => \App\Models\Item::factory(), // Assumes you have an Item model factory
            'qty' => $this->faker->numberBetween(1, 10),
        ];
    }
}
