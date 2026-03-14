<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserListRequest extends FormRequest
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
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'in:active,inactive,suspended'],
            'role_id' => ['nullable', 'exists:roles,id'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'page' => ['nullable', 'integer', 'min:1'],
            'sort_by' => ['nullable', 'in:id,name,email,created_at,updated_at'],
            'sort_direction' => ['nullable', 'in:asc,desc'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'search.max' => 'Kata kunci pencarian tidak boleh lebih dari 255 karakter',
            'status.in' => 'Status harus salah satu dari: active, inactive, suspended',
            'role_id.exists' => 'Role tidak ditemukan',
            'per_page.integer' => 'Jumlah per halaman harus berupa angka',
            'per_page.min' => 'Jumlah per halaman minimal 1',
            'per_page.max' => 'Jumlah per halaman maksimal 100',
            'page.integer' => 'Halaman harus berupa angka',
            'page.min' => 'Halaman minimal 1',
            'sort_by.in' => 'Sortir berdasarkan salah satu dari: id, name, email, created_at, updated_at',
            'sort_direction.in' => 'Arah sortir harus salah satu dari: asc, desc',
        ];
    }

    /**
     * Get pagination parameters with default values.
     *
     * @return array
     */
    public function getPaginationParams(): array
    {
        return [
            'per_page' => $this->input('per_page', 15),
            'page' => $this->input('page', 1),
        ];
    }
}
