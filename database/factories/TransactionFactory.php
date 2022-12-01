<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id_ticket' => fake()->numberBetween(1, 10),
            'id_user' => fake()->numberBetween(1, 10),
            'jumlah_ticket' => fake()->numberBetween(1, 10),
            'total' => fake()->numberBetween(10000, 100000),
            'nama_pembeli' => fake()->name(),
            'tanggal_bayar' => fake()->date(),
        ];
    }
}
