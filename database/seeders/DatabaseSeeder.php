<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wish;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $adminUser->assignRole('admin');

        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@example.com',
        ]);
        $testUser->assignRole('user');

        $santaUser = User::factory()->create([
            'name' => 'Santa Claus',
            'email' => 'santa@example.com',
        ]);
        $santaUser->assignRole('santa_claus');

        Wish::factory()->create([
            'user_id' => $testUser->id,
            'title' => 'Red Bicycle',
            'description' => 'I would love a shiny red bicycle with training wheels',
            'priority' => 'high',
            'status' => 'pending',
        ]);

        Wish::factory()->create([
            'user_id' => $testUser->id,
            'title' => 'Teddy Bear',
            'description' => 'A soft and cuddly teddy bear to hug at night',
            'priority' => 'medium',
            'status' => 'pending',
        ]);

        Wish::factory()->create([
            'user_id' => $adminUser->id,
            'title' => 'World Peace',
            'description' => 'Peace and happiness for everyone in the world',
            'priority' => 'high',
            'status' => 'in_progress',
        ]);
    }
}
