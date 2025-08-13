<?php

namespace Database\Seeders;

use App\Models\Minute;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@bpssulut.com',
            'role' => 'Admin',
        ]);

        User::factory(10)->create();
        Minute::factory(20)->create();
    }
}
