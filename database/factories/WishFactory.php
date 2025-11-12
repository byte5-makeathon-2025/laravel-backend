<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Wish>
 */
class WishFactory extends Factory
{
    public function definition(): array
    {
        $wishes = [
            ['title' => 'Red Bicycle', 'description' => 'I would love a shiny red bicycle with training wheels'],
            ['title' => 'Teddy Bear', 'description' => 'A soft and cuddly teddy bear to hug at night'],
            ['title' => 'Art Supplies', 'description' => 'Watercolors, brushes, and canvas for painting'],
            ['title' => 'Book Collection', 'description' => 'A set of adventure novels to read before bed'],
            ['title' => 'Video Game', 'description' => 'The latest adventure game for my console'],
            ['title' => 'Musical Instrument', 'description' => 'A guitar to learn how to play music'],
            ['title' => 'Science Kit', 'description' => 'Chemistry set to conduct fun experiments'],
            ['title' => 'Board Games', 'description' => 'Family board games to play together'],
            ['title' => 'Sports Equipment', 'description' => 'Soccer ball and goalie gloves'],
            ['title' => 'World Peace', 'description' => 'Peace and happiness for everyone in the world'],
        ];

        $wish = fake()->randomElement($wishes);

        return [
            'user_id' => User::factory(),
            'title' => $wish['title'],
            'description' => $wish['description'],
            'priority' => fake()->randomElement(['high', 'medium', 'low']),
            'status' => fake()->randomElement(['pending', 'pending', 'pending', 'in_progress']),
        ];
    }
}
