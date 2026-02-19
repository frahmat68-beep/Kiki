<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Equipment>
 */
class EquipmentFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        $categoryId = Category::query()->value('id');
        if (! $categoryId) {
            $base = fake()->unique()->word();
            $category = Category::query()->create([
                'name' => ucfirst($base),
                'slug' => Str::slug($base),
                'description' => fake()->sentence(),
            ]);
            $categoryId = $category->id;
        }

        return [
            'category_id' => $categoryId,
            'name' => ucfirst($name),
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 9999),
            'price_per_day' => fake()->numberBetween(100000, 900000),
            'status' => fake()->randomElement(['ready', 'maintenance', 'unavailable']),
            'description' => fake()->sentence(12),
            'specifications' => implode(PHP_EOL, fake()->sentences(2)),
            'stock' => fake()->numberBetween(1, 10),
            'image_path' => null,
            'image' => null,
        ];
    }
}
