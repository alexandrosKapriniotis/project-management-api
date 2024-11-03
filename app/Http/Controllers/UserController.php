<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index(): Collection
    {
        $this->authorize('viewAny', User::class);

        return User::all();
    }

    public function store(UserRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $user = User::create($request->validated());
        $user->assignRole('User');

        return response()->json($user, 201);
    }

    public function show(User $user): User
    {
        $this->authorize('view', $user);

        return $user;
    }

    public function update(UserRequest $request, User $user): JsonResponse
    {
        $this->authorize('update', $user);

        $user->update($request->validated());

        return response()->json($user);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete', $user);

        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);
    }
}
