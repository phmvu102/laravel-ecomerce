<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $user = $this->route('user');

        return [

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                Rule::unique('users')
                    ->ignore($user->id),
            ],

            'password' => [
                'nullable',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                'in:admin,vendor,staff,customer',
            ],
        ];
    }
}
