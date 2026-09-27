<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\FileReport;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FileReport>
 */
class FileReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hasFeedback = fake()->boolean();
        return [
            'file_id' => File::factory(),
            'irregularity' => fake()->randomElement(['open_water_tank', 'abandoned_pool']),
            'status' => $hasFeedback ? 'created' : 'provided_feedback',
            'agent_report' => fake()->sentence(),
            'user_feedback' => $hasFeedback ? fake()->randomElement(['correct', 'incorrect']) : null,
            'feedback_by' => $hasFeedback ? User::factory() : null
        ];
    }
}
