<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Pricegroup;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Price>
 */
class PriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'price' => fake()->numberBetween(1000, 50000),
            'team_id' => Team::factory(),
            'pricegroup_id' => Pricegroup::factory(),
        ];
    }
}
