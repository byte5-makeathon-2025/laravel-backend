<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wish>
 */
class WishFactory extends Factory
{
    public function definition(): array
    {
        $wishes = [
            ['title' => 'Red Bicycle', 'product_name' => 'Kids Bicycle with Training Wheels', 'product_price' => 149.99, 'product_weight' => 12.5],
            ['title' => 'Teddy Bear', 'product_name' => 'Giant Plush Teddy Bear', 'product_price' => 39.99, 'product_weight' => 1.2],
            ['title' => 'Art Supplies', 'product_name' => 'Professional Watercolor Paint Set', 'product_price' => 59.99, 'product_weight' => 2.3],
            ['title' => 'Book Collection', 'product_name' => 'Adventure Novels Box Set', 'product_price' => 49.99, 'product_weight' => 3.5],
            ['title' => 'Video Game', 'product_name' => 'Adventure Quest Game', 'product_price' => 59.99, 'product_weight' => 0.2],
            ['title' => 'Musical Instrument', 'product_name' => 'Beginner Acoustic Guitar', 'product_price' => 129.99, 'product_weight' => 2.8],
            ['title' => 'Science Kit', 'product_name' => 'Chemistry Experiment Set', 'product_price' => 44.99, 'product_weight' => 1.8],
            ['title' => 'Board Games', 'product_name' => 'Family Board Game Collection', 'product_price' => 34.99, 'product_weight' => 2.1],
            ['title' => 'Sports Equipment', 'product_name' => 'Soccer Ball and Goalie Gloves Set', 'product_price' => 54.99, 'product_weight' => 1.5],
            ['title' => 'LEGO Set', 'product_name' => 'LEGO Creator Expert Set', 'product_price' => 199.99, 'product_weight' => 4.2],
        ];

        $wish = fake()->randomElement($wishes);

        return [
            'name' => fake()->name(),
            'street' => fake()->streetName(),
            'house_number' => fake()->buildingNumber(),
            'postal_code' => fake()->postcode(),
            'city' => fake()->city(),
            'country' => fake()->country(),
            'title' => $wish['title'],
            'description' => fake()->optional(0.3)->sentence(),
            'priority' => fake()->randomElement(['high', 'medium', 'low']),
            'status' => fake()->randomElement(['pending', 'pending', 'pending', 'in_progress', 'granted']),
            'product_name' => $wish['product_name'],
            'product_sku' => (string) fake()->numberBetween(1, 200),
            'product_image' => 'https://cdn.dummyjson.com/products/images/beauty/Essence%20Mascara%20Lash%20Princess/1.png',
            'product_weight' => $wish['product_weight'],
            'product_price' => $wish['product_price'],
        ];
    }
}
