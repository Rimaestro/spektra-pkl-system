<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

/**
 * Form Request for updating user
 * 
 * Handles validation for user updates with role-based authorization
 * and business rule validation.
 */
class UpdateUserRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admin and koordinator can update users
        return $this->hasRole(['admin', 'koordinator']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $userId = $this->route('user');
        
        return [
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'email',
                Rule::unique('users', 'email')->ignore($userId)
            ],
            'role' => [
                'sometimes',
                'string',
                Rule::in(['admin', 'koordinator', 'dosen', 'siswa', 'pembimbing_lapangan'])
            ],
            'nis' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'nis')->ignore($userId)
            ],
            'nip' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'nip')->ignore($userId)
            ],
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
            'name.string' => 'Nama harus berupa teks.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'role.in' => 'Role tidak valid.',
            'nis.unique' => 'NIS sudah digunakan.',
            'nip.unique' => 'NIP sudah digunakan.',
            'status.in' => 'Status tidak valid.',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $role = $this->input('role');
            
            if ($role) {
                // Validate role-specific fields when role is being updated
                if ($role === 'siswa' && !$this->input('nis')) {
                    $validator->errors()->add('nis', 'NIS wajib diisi untuk siswa.');
                }
                
                if (in_array($role, ['dosen', 'koordinator', 'admin']) && !$this->input('nip')) {
                    $validator->errors()->add('nip', 'NIP wajib diisi untuk dosen/koordinator/admin.');
                }
            }
        });
    }
}
