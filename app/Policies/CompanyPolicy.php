<?php

namespace App\Policies;

use App\Models\Company;
use App\Models\User;

class CompanyPolicy
{
    /**
     * Global check for Admin role
     */
    public function before(User $user, $ability): ?true
    {
        if ($user->hasRole('Admin')) {
            return true;
        }
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->companies()->exists();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Company $company): bool
    {
        return $user->companies->contains($company->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Company $company): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Company $company): bool
    {
        return false;
    }
}
