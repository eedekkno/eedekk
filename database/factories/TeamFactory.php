<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\TeamStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'address' => fake()->streetAddress(),
            'zipcode' => fake()->postcode(),
            'city' => fake()->city(),
            'org_number' => fake()->randomElement([8, 9]).fake()->numerify('########'),
            'phone' => fake()->randomElement([4, 9]).fake()->numerify('#######'),
            'email' => fake()->safeEmail(),
            'price' => fake()->randomElement([4000, 5000, 6000, 7000]),
            'status' => fake()->randomElement(TeamStatus::cases()),
            'trial_ends_at' => null,
            'subscription_ends_at' => null,
        ];
    }
}
