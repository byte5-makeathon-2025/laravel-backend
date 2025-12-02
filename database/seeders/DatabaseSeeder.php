<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Wish;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
        ]);
        $adminUser->assignRole('admin');

        $santaUser = User::factory()->create([
            'name' => 'Santa Claus',
            'email' => 'santa@example.com',
        ]);
        $santaUser->assignRole('santa_claus');

        $elfUser = User::factory()->create([
            'name' => 'Helper Elf',
            'email' => 'elf@example.com',
        ]);
        $elfUser->assignRole('elf');

        Wish::factory()->create([
            'name' => 'Tommy Anderson',
            'title' => 'Red Bicycle',
            'description' => 'I would love a shiny red bicycle with training wheels',
            'priority' => 'high',
            'status' => 'pending',
            "coordinates" => [40.7127837, -74.0059413]
        ]);

        Wish::factory()->create([
            'name' => 'Sarah Johnson',
            'title' => 'Teddy Bear',
            'description' => 'A soft and cuddly teddy bear to hug at night',
            'priority' => 'medium',
            'status' => 'pending',
            "coordinates" => [32.7127837, -42.0059413]
        ]);

        Wish::factory()->create([
            'name' => 'Michael Smith',
            'title' => 'World Peace',
            'description' => 'Peace and happiness for everyone in the world',
            'priority' => 'high',
            'status' => 'in_progress',
            "coordinates" => [89.7127837, -89.0059413]
        ]);
    }
}
