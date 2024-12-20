<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstname' => $this->faker->firstName(),
            'lastname' => fake()->lastName() . ' ' . fake()->lastName(),
            'document' => $this->faker->numerify('########'),
            'address' => $this->faker->address(),
            'cellphone' => fake()->numerify('###') . ' ' . fake()->numerify('###') . ' ' . fake()->numerify('###'),
            'email' => $this->faker->email(),
            'birthdate' => $this->faker->date(),
            'baptism' => $this->faker->date(),
            'group_id' => Group::all()->random()->id,
        ];
    }
}
