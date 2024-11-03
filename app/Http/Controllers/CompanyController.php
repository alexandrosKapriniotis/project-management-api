<?php

namespace App\Http\Controllers;

use App\Http\Requests\CompanyRequest;
use App\Models\Company;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('viewAny', Company::class);

        return Company::all();
    }

    public function store(CompanyRequest $request)
    {
        $this->authorize('create', Company::class);

        $company = Company::create($request->validated());

        return response()->json($company, 201);
    }

    public function show(Company $company)
    {
        $this->authorize('view', $company);

        return $company;
    }

    public function update(CompanyRequest $request, Company $company)
    {
        $this->authorize('update', $company);

        $company->update($request->validated());

        return response()->json($company);
    }

    public function destroy(Company $company): JsonResponse
    {
        $this->authorize('delete', $company);

        $company->delete();

        return response()->json(['message' => 'Company deleted successfully.']);
    }
}
