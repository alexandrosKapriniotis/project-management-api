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
    $this->user->companies()->attach($this->company->id);
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

it('allows regular user to create a project for their company', function () {
    actingAs($this->user)
        ->postJson('/api/projects', [
            'name' => 'User Project',
            'description' => 'Project for user’s company',
            'type' => ProjectType::Standard->value,
            'company_id' => $this->company->id,
        ])
        ->assertStatus(201)
        ->assertJson(['name' => 'User Project']);
});

it('prevents regular user from creating a project for a company they do not belong to', function () {
    $otherCompany = Company::factory()->create();

    actingAs($this->user)
        ->postJson('/api/projects', [
            'name' => 'Unauthorized Project',
            'description' => 'Project for another company',
            'type' => ProjectType::Standard->value,
            'company_id' => $otherCompany->id,
        ])
        ->assertStatus(403);
});

it('allows regular user to update a project within their company', function () {
    $project = Project::factory()->create(['company_id' => $this->company->id]);

    actingAs($this->user)
        ->putJson("/api/projects/{$project->id}", [
            'name' => 'Updated User Project',
            'description' => 'Updated description',
            'type' => $project->type,
            'company_id'  => $project->company_id,
        ])
        ->assertStatus(200)
        ->assertJson(['name' => 'Updated User Project']);
});

it('prevents regular user from updating a project in another company', function () {
    $otherCompany = Company::factory()->create();
    $project = Project::factory()->create(['company_id' => $otherCompany->id]);

    actingAs($this->user)
        ->putJson("/api/projects/{$project->id}", [
            'name' => 'Unauthorized Update',
            'description' => 'Attempting to update another company’s project',
        ])
        ->assertStatus(403);
});

it('allows admin to delete any project', function () {
    $project = Project::factory()->create(['company_id' => $this->company->id]);

    actingAs($this->admin)
        ->deleteJson("/api/projects/{$project->id}")
        ->assertStatus(200)
        ->assertJson(['message' => 'Project deleted successfully.']);
});

it('prevents regular user from deleting a project in another company', function () {
    $otherCompany = Company::factory()->create();
    $project = Project::factory()->create(['company_id' => $otherCompany->id]);

    actingAs($this->user)
        ->deleteJson("/api/projects/{$project->id}")
        ->assertStatus(403);
});

it('allows regular user to delete a project within their company', function () {
    $project = Project::factory()->create(['company_id' => $this->company->id]);

    actingAs($this->user)
        ->deleteJson("/api/projects/{$project->id}")
        ->assertStatus(200)
        ->assertJson(['message' => 'Project deleted successfully.']);
});

