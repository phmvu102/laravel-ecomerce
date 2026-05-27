<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $brandId = $this->route('brand'); // Lấy id của brand từ route khi update

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('brands', 'slug')->ignore($brandId)
            ],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:2048'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên thương hiệu không được để trống.',
            'slug.unique' => 'Slug thương hiệu đã tồn tại.',
            'logo.image' => 'Logo phải là định dạng hình ảnh.',
            'logo.max' => 'Dung lượng ảnh không được vượt quá 2MB.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.in' => 'Trạng thái chọn không hợp lệ.',
        ];
    }
}
