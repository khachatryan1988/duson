<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = ['hy' => $this->generateTitel(), 'en' => $this->generateTitel(), 'ru' => $this->generateTitel()];
        $category = Category::inRandomOrder()->first();

        return [
            'title' => $title,
            'item_id' => rand(10000, 50000),
            'price' => rand(1000, 50000),
            'category_id' => $category->id,
            'quantity' => rand(10, 100)
        ];
    }

    public function generateTitel() {
        $postTitle = $this->faker->words(rand(5, 10), true);
        $ucase = ucfirst($postTitle);
        return $ucase;
    }
}
