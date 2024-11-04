<?php

namespace App\Http\Requests;

use App\Enums\ProjectType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ProjectRequest extends FormRequest
{
    public function authorize(): bool
    {    
        if ($this->user()->hasRole('Admin')) {
            return true;
        }

        // For non-admin users, ensure they belong to the company they’re trying to assign the project to
        return $this->user()->companies()->pluck('id')->contains($this->input('company_id'));
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => ['required', new Enum(ProjectType::class)],
            'company_id' => 'required|exists:companies,id',
            'budget' => 'numeric|min:0|required_if:type,' . ProjectType::Complex->value,
            'timeline' => 'date|required_if:type,' . ProjectType::Complex->value,
        ];
    }
}
