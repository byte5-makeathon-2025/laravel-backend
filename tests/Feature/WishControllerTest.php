<?php

use App\Models\User;
use App\Models\Wish;

test('unauthenticated user can create a wish', function () {
    $response = $this->postJson('/api/wishes', [
        'name' => 'John Doe',
        'title' => 'Red Bicycle',
        'description' => 'I would love a shiny red bicycle',
        'priority' => 'high',
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['message', 'wish' => ['id', 'name', 'title', 'description', 'priority', 'status']])
        ->assertJson(['message' => 'Wish successfully created']);

    $this->assertDatabaseHas('wishes', [
        'name' => 'John Doe',
        'title' => 'Red Bicycle',
    ]);
});

test('user cannot create wish without required fields', function () {
    $response = $this->postJson('/api/wishes', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'title', 'description']);
});

test('user cannot create wish without name field', function () {
    $response = $this->postJson('/api/wishes', [
        'title' => 'Red Bicycle',
        'description' => 'I would love a shiny red bicycle',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name']);
});

test('santa can view a specific wish', function () {
    $santa = User::factory()->create();
    $santa->assignRole('santa_claus');

    $wish = Wish::create([
        'name' => 'Jane Smith',
        'title' => 'My Specific Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $this->actingAs($santa)
        ->getJson("/api/wishes/{$wish->id}")
        ->assertStatus(200)
        ->assertJsonFragment(['title' => 'My Specific Wish', 'name' => 'Jane Smith']);
});

test('elf can view a specific wish', function () {
    $elf = User::factory()->create();
    $elf->assignRole('elf');

    $wish = Wish::create([
        'name' => 'Peter Parker',
        'title' => 'Elf Specific Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $this->actingAs($elf)
        ->getJson("/api/wishes/{$wish->id}")
        ->assertStatus(200)
        ->assertJsonFragment(['title' => 'Elf Specific Wish', 'name' => 'Peter Parker']);
});

test('unauthenticated user cannot view specific wish', function () {
    $wish = Wish::create([
        'name' => 'Alice Wonder',
        'title' => 'Secret Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $response = $this->getJson("/api/wishes/{$wish->id}");

    $response->assertStatus(401);
});

test('regular user cannot view specific wish', function () {
    $user = User::factory()->create();

    $wish = Wish::create([
        'name' => 'Bob Builder',
        'title' => 'Private Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $this->actingAs($user)
        ->getJson("/api/wishes/{$wish->id}")
        ->assertStatus(403);
});

test('santa gets 404 when viewing non-existent wish', function () {
    $santa = User::factory()->create();
    $santa->assignRole('santa_claus');

    $this->actingAs($santa)
        ->getJson('/api/wishes/99999')
        ->assertStatus(404);
});

test('santa can view all wishes', function () {
    $santa = User::factory()->create();
    $santa->assignRole('santa_claus');

    Wish::create([
        'name' => 'Tommy Anderson',
        'title' => 'User Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $this->actingAs($santa)
        ->getJson('/api/wishes/all')
        ->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['title' => 'User Wish'])
        ->assertJsonStructure([
            'data',
            'current_page',
            'per_page',
            'total',
            'links',
        ]);
});

test('elf can view all wishes', function () {
    $elf = User::factory()->create();
    $elf->assignRole('elf');

    Wish::create([
        'name' => 'Sarah Johnson',
        'title' => 'Elf Wish Test',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $this->actingAs($elf)
        ->getJson('/api/wishes/all')
        ->assertStatus(200)
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['title' => 'Elf Wish Test'])
        ->assertJsonStructure([
            'data',
            'current_page',
            'per_page',
            'total',
            'links',
        ]);
});

test('regular user cannot view all wishes without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->getJson('/api/wishes/all')
        ->assertStatus(403);
});

test('unauthenticated user cannot view all wishes', function () {
    $response = $this->getJson('/api/wishes/all');

    $response->assertStatus(401);
});

test('santa can update wish', function () {
    $santa = User::factory()->create();
    $santa->assignRole('santa_claus');

    $wish = Wish::create([
        'name' => 'Michael Smith',
        'title' => 'Original Title',
        'description' => 'Original description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $this->actingAs($santa)
        ->putJson("/api/wishes/{$wish->id}", [
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'status' => 'granted',
        ])
        ->assertStatus(200)
        ->assertJsonFragment(['title' => 'Updated Title', 'status' => 'granted']);

    $this->assertDatabaseHas('wishes', [
        'id' => $wish->id,
        'title' => 'Updated Title',
        'status' => 'granted',
    ]);
});

test('elf can update wish', function () {
    $elf = User::factory()->create();
    $elf->assignRole('elf');

    $wish = Wish::create([
        'name' => 'Emily Brown',
        'title' => 'Original Title',
        'description' => 'Original description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $this->actingAs($elf)
        ->putJson("/api/wishes/{$wish->id}", [
            'title' => 'Elf Updated Title',
            'description' => 'Elf updated description',
            'status' => 'in_progress',
        ])
        ->assertStatus(200)
        ->assertJsonFragment(['title' => 'Elf Updated Title', 'status' => 'in_progress']);

    $this->assertDatabaseHas('wishes', [
        'id' => $wish->id,
        'title' => 'Elf Updated Title',
        'status' => 'in_progress',
    ]);
});

test('regular user cannot update wish without permission', function () {
    $user = User::factory()->create();

    $wish = Wish::create([
        'name' => 'Robert Wilson',
        'title' => 'My Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $this->actingAs($user)
        ->putJson("/api/wishes/{$wish->id}", [
            'status' => 'granted',
        ])
        ->assertStatus(403);
});

test('unauthenticated user cannot update wish', function () {
    $wish = Wish::create([
        'name' => 'Lisa Martinez',
        'title' => 'My Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $response = $this->putJson("/api/wishes/{$wish->id}", [
        'status' => 'granted',
    ]);

    $response->assertStatus(401);
});

test('user gets 404 when updating non-existent wish', function () {
    $santa = User::factory()->create();
    $santa->assignRole('santa_claus');

    $this->actingAs($santa)
        ->putJson('/api/wishes/99999', [
            'title' => 'Updated Title',
        ])
        ->assertStatus(404);
});

test('santa can delete wish', function () {
    $santa = User::factory()->create();
    $santa->assignRole('santa_claus');

    $wish = Wish::create([
        'name' => 'David Taylor',
        'title' => 'My Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $this->actingAs($santa)
        ->deleteJson("/api/wishes/{$wish->id}")
        ->assertStatus(200)
        ->assertJson(['message' => 'Wish deleted successfully']);

    $this->assertSoftDeleted('wishes', ['id' => $wish->id]);
});

test('elf can delete wish', function () {
    $elf = User::factory()->create();
    $elf->assignRole('elf');

    $wish = Wish::create([
        'name' => 'Anna White',
        'title' => 'My Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $this->actingAs($elf)
        ->deleteJson("/api/wishes/{$wish->id}")
        ->assertStatus(200)
        ->assertJson(['message' => 'Wish deleted successfully']);

    $this->assertSoftDeleted('wishes', ['id' => $wish->id]);
});

test('regular user cannot delete wish without permission', function () {
    $user = User::factory()->create();

    $wish = Wish::create([
        'name' => 'Chris Harris',
        'title' => 'My Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $this->actingAs($user)
        ->deleteJson("/api/wishes/{$wish->id}")
        ->assertStatus(403);
});

test('unauthenticated user cannot delete wish', function () {
    $wish = Wish::create([
        'name' => 'Jessica Lee',
        'title' => 'My Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $response = $this->deleteJson("/api/wishes/{$wish->id}");

    $response->assertStatus(401);
});

test('user gets 404 when deleting non-existent wish', function () {
    $santa = User::factory()->create();
    $santa->assignRole('santa_claus');

    $this->actingAs($santa)
        ->deleteJson('/api/wishes/99999')
        ->assertStatus(404);
});

test('wish validation accepts valid priority values', function () {
    $response = $this->postJson('/api/wishes', [
        'name' => 'Test User',
        'title' => 'Test Wish',
        'description' => 'Test description',
        'priority' => 'low',
    ]);

    $response->assertStatus(201);
});

test('wish validation rejects invalid priority values', function () {
    $response = $this->postJson('/api/wishes', [
        'name' => 'Test User',
        'title' => 'Test Wish',
        'description' => 'Test description',
        'priority' => 'invalid',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['priority']);
});
