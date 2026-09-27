<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\Inspection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<File>
 */
class FileFactory extends Factory
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
            'path' => fake()->filePath(),
            'mime_type' => fake()->mimeType(),
            'inspection_id' => Inspection::factory()
        ];
    }
}
