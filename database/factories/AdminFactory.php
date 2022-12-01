<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class AdminFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'konser_name' => fake()->name(),
            'ticket_category' => 'pop',
            'date' => fake()->date(),
            'price' => fake()->numberBetween(10000, 100000),
        ];
    }
}
