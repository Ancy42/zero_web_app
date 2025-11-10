<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shop>
 */
class ShopFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'user_id' => User::factory()->create(),
            'delivery_charge' => $this->faker->randomFloat(2, 10, 100),
            'description' => $this->faker->sentence(5),
            'min_order_amount' => $this->faker->randomFloat(2, 10, 100),
            'opening_time' => $this->faker->time(),
            'closing_time' => $this->faker->time(),
            'estimated_delivery_time' => '3-4 days',
            'status' => $this->faker->boolean(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'address' => $this->faker->address(),
        ];
    }
}
