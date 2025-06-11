<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition()
    {
        return [
            'kode_item' => $this->faker->unique()->bothify('ITEM-####'), // Kode unik
            'nama_item' => $this->faker->word(), // Nama acak
            'stok' => $this->faker->numberBetween(1, 100), // Stok antara 1 hingga 100
            'category_id' => $this->faker->randomElement([1, 2]), // 1 untuk barang, 2 untuk ruangan
            'pengelola' => $this->faker->randomElement(['dpt', 'dala','sdm','warek'])
        ];
    }
}
