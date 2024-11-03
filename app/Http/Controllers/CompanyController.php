<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use App\Repositories\CompanyRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    use AuthorizesRequests;

    protected CompanyRepository $companyRepository;

    /**
     * Constructor to inject CompanyRepository.
     *
     * @param CompanyRepository $companyRepository
     */
    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * @throws AuthorizationException
     */
    public function index(Request $request): LengthAwarePaginator
    {
        $this->authorize('viewAny', Company::class);

        $perPage = $request->get('per_page', 10);

        return $this->companyRepository->getCompaniesForUser(auth()->user(), $perPage);
    }

    /**
     * @throws AuthorizationException
     */
    public function store(CompanyRequest $request): JsonResponse
    {
        $this->authorize('create', Company::class);

        $company = $this->companyRepository->store($request->validated());

        return response()->json($company, 201);
    }

    /**
     * @throws AuthorizationException
     */
    public function show(Company $company): Company
    {
        $this->authorize('view', $company);

        return $this->companyRepository->findById($company->id);
    }

    /**
     * @throws AuthorizationException
     */
    public function update(CompanyRequest $request, Company $company): JsonResponse
    {
        $this->authorize('update', $company);

        $updatedCompany = $this->companyRepository->update($company, $request->validated());

        return response()->json($updatedCompany);
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Company $company): JsonResponse
    {
        $this->authorize('delete', $company);

        $this->companyRepository->delete($company);

        return response()->json(['message' => 'Company deleted successfully.']);
    }
}
