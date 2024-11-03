<?php

namespace App\Repositories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class ProjectRepository
{
    /**
     * Get paginated projects for the user based on their role.
     *
     * @param User $user
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getProjectsForUser(User $user, int $perPage = 10): LengthAwarePaginator
    {
        // Admins can see all projects; regular users see only projects of companies they belong to
        return $user->hasRole('Admin')
            ? Project::paginate($perPage)
            : Project::whereHas('company', function ($query) use ($user) {
                $query->whereIn('id', $user->companies()->pluck('id'));
            })->paginate($perPage);
    }

    /**
     * Store a new project.
     *
     * @param array $data
     * @return Project
     */
    public function store(array $data): Project
    {
        return Project::create($data);
    }

    /**
     * Update a project.
     *
     * @param Project $project
     * @param array $data
     * @return Project
     */
    public function update(Project $project, array $data): Project
    {
        $project->update($data);

        return $project;
    }

    /**
     * Delete a project.
     *
     * @param Project $project
     * @return void
     */
    public function delete(Project $project): void
    {
        $project->delete();
    }

    /**
     * Find a project by ID.
     *
     * @param int|string $id
     * @return Project|null
     */
    public function findById($id): ?Project
    {
        return Project::find($id);
    }
}
