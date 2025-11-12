<?php

use App\Models\User;
use App\Models\Wish;

test('authenticated user can create a wish', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->postJson('/api/wishes', [
            'title' => 'Red Bicycle',
            'description' => 'I would love a shiny red bicycle',
            'priority' => 'high',
        ]);

    $response->assertStatus(201)
        ->assertJsonStructure(['wish' => ['id', 'title', 'description', 'priority', 'status']]);

    $this->assertDatabaseHas('wishes', [
        'title' => 'Red Bicycle',
        'user_id' => $user->id,
    ]);
});

test('user cannot create wish without required fields', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->postJson('/api/wishes', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['title', 'description']);
});

test('user can view their own wishes', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    Wish::create([
        'user_id' => $user->id,
        'title' => 'My Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->getJson('/api/wishes');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'wishes')
        ->assertJsonFragment(['title' => 'My Wish']);
});

test('user cannot view other users wishes', function () {
    $user1 = User::factory()->create();
    $user1->assignRole('user');

    $user2 = User::factory()->create();
    $user2->assignRole('user');

    $wish = Wish::create([
        'user_id' => $user1->id,
        'title' => 'User 1 Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $token = $user2->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->getJson("/api/wishes/{$wish->id}");

    $response->assertStatus(403);
});

test('user can view a specific wish they own', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $wish = Wish::create([
        'user_id' => $user->id,
        'title' => 'My Specific Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->getJson("/api/wishes/{$wish->id}");

    $response->assertStatus(200)
        ->assertJsonFragment(['title' => 'My Specific Wish']);
});

test('user gets 404 when viewing non-existent wish', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->getJson('/api/wishes/99999');

    $response->assertStatus(404)
        ->assertJson(['message' => 'Wish not found']);
});

test('user can update their own wish', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $wish = Wish::create([
        'user_id' => $user->id,
        'title' => 'Original Title',
        'description' => 'Original description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->putJson("/api/wishes/{$wish->id}", [
            'title' => 'Updated Title',
            'description' => 'Updated description',
        ]);

    $response->assertStatus(200)
        ->assertJsonFragment(['title' => 'Updated Title']);

    $this->assertDatabaseHas('wishes', [
        'id' => $wish->id,
        'title' => 'Updated Title',
    ]);
});

test('user cannot update other users wish', function () {
    $user1 = User::factory()->create();
    $user1->assignRole('user');

    $user2 = User::factory()->create();
    $user2->assignRole('user');

    $wish = Wish::create([
        'user_id' => $user1->id,
        'title' => 'User 1 Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $token = $user2->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->putJson("/api/wishes/{$wish->id}", [
            'title' => 'Hacked Title',
        ]);

    $response->assertStatus(403);
});

test('user cannot update wish status', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $wish = Wish::create([
        'user_id' => $user->id,
        'title' => 'My Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->putJson("/api/wishes/{$wish->id}", [
            'status' => 'granted',
        ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('wishes', [
        'id' => $wish->id,
        'status' => 'pending',
    ]);
});

test('user gets 404 when updating non-existent wish', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->putJson('/api/wishes/99999', [
            'title' => 'Updated Title',
        ]);

    $response->assertStatus(404)
        ->assertJson(['message' => 'Wish not found']);
});

test('user can delete their own wish', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $wish = Wish::create([
        'user_id' => $user->id,
        'title' => 'My Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->deleteJson("/api/wishes/{$wish->id}");

    $response->assertStatus(200)
        ->assertJson(['message' => 'Wish deleted successfully']);

    $this->assertSoftDeleted('wishes', ['id' => $wish->id]);
});

test('user cannot delete other users wish', function () {
    $user1 = User::factory()->create();
    $user1->assignRole('user');

    $user2 = User::factory()->create();
    $user2->assignRole('user');

    $wish = Wish::create([
        'user_id' => $user1->id,
        'title' => 'User 1 Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $token = $user2->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->deleteJson("/api/wishes/{$wish->id}");

    $response->assertStatus(403);
});

test('user gets 404 when deleting non-existent wish', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->deleteJson('/api/wishes/99999');

    $response->assertStatus(404)
        ->assertJson(['message' => 'Wish not found']);
});

test('santa can view all wishes', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $santa = User::factory()->create();
    $santa->assignRole('santa_claus');

    Wish::create([
        'user_id' => $user->id,
        'title' => 'User Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $token = $santa->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->getJson('/api/wishes/all');

    $response->assertStatus(200)
        ->assertJsonCount(1, 'wishes')
        ->assertJsonFragment(['title' => 'User Wish']);
});

test('regular user cannot view all wishes', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->getJson('/api/wishes/all');

    $response->assertStatus(403);
});

test('santa can update wish status', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $santa = User::factory()->create();
    $santa->assignRole('santa_claus');

    $wish = Wish::create([
        'user_id' => $user->id,
        'title' => 'User Wish',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $token = $santa->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->putJson("/api/wishes/{$wish->id}", [
            'status' => 'granted',
        ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('wishes', [
        'id' => $wish->id,
        'status' => 'granted',
    ]);
});

test('santa cannot update wish content', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $santa = User::factory()->create();
    $santa->assignRole('santa_claus');

    $wish = Wish::create([
        'user_id' => $user->id,
        'title' => 'Original Title',
        'description' => 'Test description',
        'priority' => 'high',
        'status' => 'pending',
    ]);

    $token = $santa->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->putJson("/api/wishes/{$wish->id}", [
            'title' => 'Hacked Title',
            'status' => 'granted',
        ]);

    $response->assertStatus(200);

    $this->assertDatabaseHas('wishes', [
        'id' => $wish->id,
        'title' => 'Original Title',
        'status' => 'granted',
    ]);
});

test('unauthenticated user cannot access wishes', function () {
    $response = $this->getJson('/api/wishes');

    $response->assertStatus(401);
});

test('wish validation accepts valid priority values', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->postJson('/api/wishes', [
            'title' => 'Test Wish',
            'description' => 'Test description',
            'priority' => 'low',
        ]);

    $response->assertStatus(201);
});

test('wish validation rejects invalid priority values', function () {
    $user = User::factory()->create();
    $user->assignRole('user');

    $token = $user->createToken('test-token')->plainTextToken;

    $response = $this->withHeader('Authorization', "Bearer {$token}")
        ->postJson('/api/wishes', [
            'title' => 'Test Wish',
            'description' => 'Test description',
            'priority' => 'invalid',
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['priority']);
});
