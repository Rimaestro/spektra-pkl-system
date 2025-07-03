<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

/**
 * Form Request for creating new user
 * 
 * Handles validation for user creation with role-based authorization
 * and business rule validation.
 */
class CreateUserRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admin and koordinator can create users
        return $this->hasRole(['admin', 'koordinator']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => [
                'required',
                'string',
                Rule::in(['admin', 'koordinator', 'dosen', 'siswa', 'pembimbing_lapangan'])
            ],
            'nis' => 'nullable|string|max:20|unique:users,nis',
            'nip' => 'nullable|string|max:20|unique:users,nip',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'status' => [
                'sometimes',
                'string',
                Rule::in(['active', 'inactive', 'pending'])
            ]
        ];
    }

    /**
     * Get custom validation messages
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
            'nis.unique' => 'NIS sudah digunakan.',
            'nip.unique' => 'NIP sudah digunakan.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $role = $this->input('role');
            
            // Validate role-specific fields
            if ($role === 'siswa' && !$this->input('nis')) {
                $validator->errors()->add('nis', 'NIS wajib diisi untuk siswa.');
            }
            
            if (in_array($role, ['dosen', 'koordinator', 'admin']) && !$this->input('nip')) {
                $validator->errors()->add('nip', 'NIP wajib diisi untuk dosen/koordinator/admin.');
            }
        });
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default status if not provided
        if (!$this->has('status')) {
            $this->merge(['status' => 'active']);
        }
    }
}
