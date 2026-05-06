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

        $itemType = $this->faker->randomElement(['baju_adat', 'aksesoris', 'jasa_rias']);

        return [
            'name' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 50000, 500000),
            'category_id' => $this->faker->numberBetween(1, 2),

            'item_type' => $itemType,
            'adat_category' => $itemType === 'baju_adat'
                ? $this->faker->randomElement(['Jawa', 'Sunda', 'Madura', 'Bali'])
                : null,
            'gender' => $this->faker->randomElement(['Laki-laki', 'Perempuan', 'Unisex']),

            'img' => !empty($images)
                ? $this->faker->randomElement($images)
                : 'default.jpg',

            'is_active' => $this->faker->boolean(90),
        ];
    }
}
