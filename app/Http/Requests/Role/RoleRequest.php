<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'nama' => ['required', 'string', 'max:255'],
        ];

        // For update, nama should be unique except current role
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['nama'][] = 'unique:roles,nama,' . $this->route('id');
        } else {
            // For create, nama should be unique
            $rules['nama'][] = 'unique:roles,nama';
        }

        // For list request (GET), add pagination and sort validation
        if ($this->isMethod('GET')) {
            $rules = [
                'search' => ['nullable', 'string', 'max:255'],
                'sort_by' => ['nullable', 'in:nama,created_at,updated_at'],
                'sort_direction' => ['nullable', 'in:asc,desc'],
                'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
                'page' => ['nullable', 'integer', 'min:1'],
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama role wajib diisi',
            'nama.max' => 'Nama role tidak boleh lebih dari 255 karakter',
            'nama.unique' => 'Nama role sudah digunakan',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            // For update, make all fields optional by using 'sometimes'
            $this->merge([
                'nama' => $this->input('nama'),
            ]);
        }
    }
}
