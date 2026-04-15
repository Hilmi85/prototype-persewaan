<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

class ItemFactory extends Factory
{
    public function definition(): array
    {
        $imagePath = public_path('img_item_upload');
        $images = [];

        if (File::exists($imagePath)) {
            $images = collect(File::files($imagePath))
                ->filter(function ($file) {
                    return in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'webp']);
                })
                ->map(function ($file) {
                    return $file->getFilename();
                })
                ->values()
                ->toArray();
        }

        return [
            'name' => $this->faker->name(),
            'category_id' => $this->faker->numberBetween(1, 2),
            'price' => $this->faker->randomFloat(2, 1000, 100000),
            'description' => $this->faker->text(),
            'img' => !empty($images)
                ? $this->faker->randomElement($images)
                : 'default.jpg',
            'is_active' => $this->faker->boolean(),
        ];
    }
}
