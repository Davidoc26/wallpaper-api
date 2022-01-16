<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use function config;
use function storage_path;

final class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $storageFolder = config('image.wallpapers-path') . '31_11_21/';
        $filePath = storage_path("app/public/$storageFolder");
        if (!File::exists($filePath)) {
            File::makeDirectory($filePath, recursive: true);
        }

        return [
            'user_id' => User::factory()->create()->getKey(),
            'name' => $name = $this->faker->word(),
            'path' => $storageFolder . $this->faker->image($filePath, 200, 200, fullPath: false, word: $name),
        ];
    }
}
