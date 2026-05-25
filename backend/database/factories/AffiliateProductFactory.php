<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AffiliateProduct>
 */
class AffiliateProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amazon_asin' => $this->faker->unique()->regexify('[A-Z0-9]{10}'),
            'product_name_snapshot' => $this->faker->sentence(5),
            'amazon_url' => $this->faker->url(),
            'your_notes' => $this->faker->paragraph(),
            'main_image_url_snapshot' => $this->faker->imageUrl(),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'status' => 'active',
            'is_featured' => $this->faker->boolean(20), // 20% chance of being featured
            'item_type' => $this->faker->randomElement(['ring', 'necklace', 'bracelet', 'earring']),
        ];
    }
}
