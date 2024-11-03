<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'unique:users,email,' . ($this->user ? $this->user->id : 'NULL'),
            ],
            'password' => $this->isMethod('post')
                ? 'required|string|min:8|confirmed'
                : 'nullable|string|min:8|confirmed',
        ];
    }
}
