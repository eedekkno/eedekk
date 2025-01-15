<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => fake()->uuid(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'address' => fake()->streetAddress(),
            'city' => fake()->city(),
            'zip' => fake()->postcode(),
            'country' => fake()->country(),
            'type' => fake()->randomElement(['private', 'company']),
            'team_id' => Team::factory(),
        ];
    }

    /**
     * Tilpass factory til et spesifikt team basert på brukerens team.
     */
    public function forUser(User $user): Factory|CustomerFactory
    {
        return $this->state(fn () => [
            'team_id' => $user->team->id,
        ]);
    }
}
