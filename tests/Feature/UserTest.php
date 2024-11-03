<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\{actingAs};

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'api']);
    Role::firstOrCreate(['name' => 'User', 'guard_name' => 'api']);

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

