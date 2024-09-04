<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id' => User::factory(),
            'title' => $title = fake()->sentence(),
            'slug' => Str::slug($title),
            'body' => fake()->sentence(500),
            'read_time' => random_int(5, 22),
            'is_published' => fake()->boolean(75),
        ];
    }
}
