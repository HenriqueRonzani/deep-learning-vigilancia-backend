<?php

namespace Database\Factories;

use App\Models\Inspection;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inspection>
 */
class InspectionFactory extends Factory
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
            'address' => fake()->address(),
            'type' => fake()->randomElement(['active', 'dengue_breeding_site', 'report']),
            'status' => fake()->randomElement(['draft', 'queued', 'processing', 'completed', 'failed']),
            'dengue_breeding_site_spotted' => fake()->boolean(),
            'requested_at' => fake()->date(),
            'requested_by' => User::factory()
        ];
    }
}
