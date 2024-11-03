<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Project;
use App\Repositories\ProjectRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    protected ProjectRepository $projectRepository;

    /**
     * Constructor to inject ProjectRepository.
     *
     * @param ProjectRepository $projectRepository
     */
    public function __construct(ProjectRepository $projectRepository)
    {
        $this->projectRepository = $projectRepository;
    }

    /**
     * @throws AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Project::class);

        $perPage = $request->get('per_page', 10);

        return $this->projectRepository->getProjectsForUser(auth()->user(), $perPage);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(ProjectRequest $request)
    {
        $this->authorize('create', Project::class);

        $project = $this->projectRepository->store($request->validated());

        return response()->json($project, 201);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Project $project): Project
    {
        $this->authorize('view', $project);

        return $this->projectRepository->findById($project->id);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(ProjectRequest $request, Project $project): JsonResponse
    {
        $this->authorize('update', $project);

        $updatedProject = $this->projectRepository->update($project, $request->validated());

        return response()->json($updatedProject);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Project $project): JsonResponse
    {
        $this->authorize('delete', $project);

        $this->projectRepository->delete($project);

        return response()->json(['message' => 'Project deleted successfully.']);
    }
}
