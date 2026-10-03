<?php

namespace Database\Seeders;

use App\Models\FileReport;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        // Gonna create a file, an inspection and a user to reach one of these
        FileReport::factory(50)->create();
    }
}
