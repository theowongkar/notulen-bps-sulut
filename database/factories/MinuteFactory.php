<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Minute>
 */
class MinuteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $problem = fake()->sentence();

        return [
            'followed_up_by' => User::inRandomOrder()->first()?->id,
            'problem' => $problem,
            'slug' => Str::slug($problem),
            'solution' => fake()->paragraph(),
            'follow_up_plan' => fake()->paragraph(),
            'follow_up_limits' => fake()->date(),
            'data_source' => fake()->sentence(),
            'evidence' => null,
        ];
    }
}
