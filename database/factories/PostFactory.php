<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? user::factory(),
            'category_id' => Category::inRandomorder()->first()->id ?? Category::factory(),
            'title' => fake()->sentence(),
            'body' => fake()->paragraph(2),
            'likes_count' => fake()->numberBetween(0, 50),
        ];
    }
}
