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

        $santaUser = User::factory()->create([
            'name' => 'Santa Claus',
            'email' => 'santa@byte5.de',
        ]);
        $santaUser->assignRole('santa_claus');

        $elfUser = User::factory()->create([
            'name' => 'Helper Elf',
            'email' => 'elf@byte5.de',
        ]);
        $elfUser->assignRole('elf');

        Wish::factory()->create([
            'name' => 'Tommy Anderson',
            'title' => 'Red Bicycle',
            'description' => 'I would love a shiny red bicycle with training wheels',
            'priority' => 'high',
            'status' => 'pending',
        ]);

        Wish::factory()->create([
            'name' => 'Sarah Johnson',
            'title' => 'Teddy Bear',
            'description' => 'A soft and cuddly teddy bear to hug at night',
            'priority' => 'medium',
            'status' => 'pending',
        ]);

        Wish::factory()->create([
            'name' => 'Michael Smith',
            'title' => 'World Peace',
            'description' => 'Peace and happiness for everyone in the world',
            'priority' => 'high',
            'status' => 'in_progress',
        ]);
    }
}
