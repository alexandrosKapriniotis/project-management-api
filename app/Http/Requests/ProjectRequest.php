<?php

namespace App\Http\Requests;

use App\Enums\ProjectType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ProjectRequest extends FormRequest
{
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
