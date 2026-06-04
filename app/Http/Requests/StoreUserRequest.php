<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'min:8',
                'confirmed',
            ],

            'role' => [
                'required',
                'in:admin,vendor,staff,customer',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'họ tên',
            'email' => 'email',
            'password' => 'mật khẩu',
            'role' => 'vai trò',
        ];
    }
}
