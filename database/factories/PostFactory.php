<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Church;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
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
    public function definition(): array{
        $title=$this->faker->unique()->sentence();
        return [
            'title'=>$title,
            'slug'=>Str::slug($title),
            'body'=>$this->faker->text(250),
            'published'=>$this->faker->randomElement([0,1]),
            'category_id'=>Category::all()->random()->id,
            'user_id'=>User::all()->random()->id,
            'church_id'=>Church::all()->random()->id,
        ];
    }
}
