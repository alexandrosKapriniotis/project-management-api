<?php

namespace App\Repositories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CompanyRepository
{
    /**
     * Get paginated companies for the user based on their role.
     *
     * @param User $user
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getCompaniesForUser(User $user, int $perPage = 10): LengthAwarePaginator
    {
        return $user->hasRole('Admin')
            ? Company::paginate($perPage)
            : $user->companies()->paginate($perPage);
    }

    /**
     * Store a new company.
     *
     * @param array $data
     * @return Company
     */
    public function store(array $data): Company
    {
        return Company::create($data);
    }

    /**
     * Update a company.
     *
     * @param Company $company
     * @param array $data
     * @return Company
     */
    public function update(Company $company, array $data): Company
    {
        $company->update($data);

        return $company;
    }

    /**
     * Delete a company.
     *
     * @param Company $company
     * @return void
     */
    public function delete(Company $company): void
    {
        $company->delete();
    }

    /**
     * Find a company by ID.
     *
     * @param int|string $id
     * @return Company|null
     */
    public function findById($id): ?Company
    {
        return Company::find($id);
    }
}
