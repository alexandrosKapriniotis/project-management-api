<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\{actingAs};

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'Admin']);
    Role::firstOrCreate(['name' => 'User']);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('Admin');

    $this->user = User::factory()->create();
    $this->user->assignRole('User');
});

it('allows admin to create a user', function () {
    actingAs($this->admin)
        ->postJson('/api/users', [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->assertStatus(201)
        ->assertJson(['name' => 'New User']);
});

it('prevents non-admin from accessing user list', function () {
    actingAs($this->user)
        ->getJson('/api/users')
        ->assertStatus(403);
});

it('allows admin to delete a user', function () {
    $user = User::factory()->create();

    actingAs($this->admin)
        ->deleteJson("/api/users/{$user->id}")
        ->assertStatus(200)
        ->assertJson(['message' => 'User deleted successfully.']);
});

it('allows admin to update a user', function () {
    $user = User::factory()->create();

    actingAs($this->admin)
        ->putJson("/api/users/{$user->id}", [
            'name' => 'Updated User Name',
            'email' => $user->email,
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ])
        ->assertStatus(200)
        ->assertJson(['name' => 'Updated User Name']);
});

it('prevents non-admin from updating another user', function () {
    $user = User::factory()->create();

    actingAs($this->user)
        ->putJson("/api/users/{$user->id}", [
            'name' => 'Unauthorized Update',
            'email' => $user->email,
        ])
        ->assertStatus(403);
});

it('allows admin to view a single user', function () {
    $user = User::factory()->create();

    actingAs($this->admin)
        ->getJson("/api/users/{$user->id}")
        ->assertStatus(200)
        ->assertJson(['id' => $user->id]);
});

it('prevents non-admin from viewing another user', function () {
    $user = User::factory()->create();

    actingAs($this->user)
        ->getJson("/api/users/{$user->id}")
        ->assertStatus(403);
});

