<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::role('root')->first();
        $category = $this->faker->randomElement(Category::all());
        $title = $this->faker->sentence;

        return [
            'user_id' => $user->id,
            'title' => $title,
            'slug' => Str::slug($title),
            'category_id' => $category->id,
            'description' => $this->faker->paragraphs(rand(5, 10), true),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
