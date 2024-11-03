<?php
use App\Models\Company;
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

it('allows admin to create a company', function () {
    actingAs($this->admin)
        ->postJson('/api/companies', [
            'name' => 'Test Company',
            'address' => '123 Test Street',
        ])
        ->assertStatus(201)
        ->assertJson(['name' => 'Test Company']);
});

it('prevents non-admin from creating a company', function () {
    actingAs($this->user)
        ->postJson('/api/companies', [
            'name' => 'Test Company',
            'address' => '123 Test Street',
        ])
        ->assertStatus(403);
});

it('allows admin to update a company', function () {
    $company = Company::factory()->create();

    actingAs($this->admin)
        ->putJson("/api/companies/{$company->id}", [
            'name' => 'Updated Company Name',
            'address' => '456 Updated Street',
        ])
        ->assertStatus(200)
        ->assertJson(['name' => 'Updated Company Name']);
});

it('prevents non-admin from deleting a company', function () {
    $company = Company::factory()->create();

    actingAs($this->user)
        ->deleteJson("/api/companies/{$company->id}")
        ->assertStatus(403);
});
