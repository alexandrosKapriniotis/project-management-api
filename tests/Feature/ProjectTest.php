<?php

use App\Enums\ProjectType;
use App\Models\Project;
use App\Models\User;
use App\Models\Company;
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

    $this->company = Company::factory()->create();
});

it('allows admin to create a project', function () {
    actingAs($this->admin)
        ->postJson('/api/projects', [
            'name' => 'New Project',
            'description' => 'Project Description',
            'type' => ProjectType::Standard->value,
            'company_id' => $this->company->id,
        ])
        ->assertStatus(201)
        ->assertJson(['name' => 'New Project']);
});

it('prevents non-admin from updating a project', function () {
    $project = Project::factory()->create(['company_id' => $this->company->id, 'type' => ProjectType::Standard->value]);

    actingAs($this->user)
        ->putJson("/api/projects/{$project->id}", [
            'name' => 'Updated Project Name',
            'type' => $project->type->value,
            'company_id' => $project->company_id,
        ])
        ->assertStatus(403);
});

it('allows admin to delete a project', function () {
    $project = Project::factory()->create(['company_id' => $this->company->id]);

    actingAs($this->admin)
        ->deleteJson("/api/projects/{$project->id}")
        ->assertStatus(200)
        ->assertJson(['message' => 'Project deleted successfully.']);
});

it('requires budget and timeline for complex projects', function () {
    actingAs($this->admin)
        ->postJson('/api/projects', [
            'name' => 'Complex Project',
            'description' => 'Project Description',
            'type' => ProjectType::Complex->value,
            'company_id' => $this->company->id,
        ])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['budget', 'timeline']);
});

it('does not require budget and timeline for standard projects', function () {
    actingAs($this->admin)
        ->postJson('/api/projects', [
            'name' => 'Standard Project',
            'description' => 'Project Description',
            'type' => ProjectType::Standard->value,
            'company_id' => $this->company->id,
        ])
        ->assertStatus(201)
        ->assertJson(['name' => 'Standard Project']);
});

it('allows admin to view all projects', function () {
    Project::factory()->count(3)->create(['company_id' => $this->company->id]);

    actingAs($this->admin)
        ->getJson('/api/projects')
        ->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

it('prevents regular user from viewing projects of other companies', function () {
    $otherCompany = Company::factory()->create();
    Project::factory()->count(2)->create(['company_id' => $this->company->id]);
    Project::factory()->count(2)->create(['company_id' => $otherCompany->id]);

    $this->user->companies()->attach($this->company->id);

    actingAs($this->user)
        ->getJson('/api/projects')
        ->assertStatus(200)
        ->assertJsonCount(2, 'data');
});

