<?php

namespace App\Http\Requests;

use App\Enums\ProjectType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ProjectRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => ['required', new Enum(ProjectType::class)],
            'company_id' => 'required|exists:companies,id',
            'budget' => 'nullable|numeric|min:0',
            'timeline' => 'nullable|date',
        ];
    }
}
