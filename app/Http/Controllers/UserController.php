<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use AuthorizesRequests;
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws AuthorizationException
     */
    public function index(): Collection
    {
        $this->authorize('viewAny', User::class);

        return $this->userRepository->getAll();
    }

    /**
     * @throws AuthorizationException
     */
    public function store(UserRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $user = $this->userRepository->store($request->validated());

        return response()->json($user, 201);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(User $user): User
    {
        $this->authorize('view', $user);

        return $user;
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UserRequest $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $user = $this->userRepository->update($user, $request->validated());

        return response()->json($user);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $this->userRepository->delete($user);

        return response()->json(['message' => 'User deleted successfully.']);
    }
}
