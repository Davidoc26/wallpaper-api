<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use function implode;

final class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $name = implode(" ", $this->faker->unique()->words($this->faker->numberBetween(1, 2)));

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
