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
    Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'api']);
    Role::firstOrCreate(['name' => 'User', 'guard_name' => 'api']);

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
    $project = Project::factory()->create(['company_id' => $this->company->id]);

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

