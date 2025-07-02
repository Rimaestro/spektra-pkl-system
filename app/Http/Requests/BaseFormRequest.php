<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * Base Form Request for SPEKTRA PKL System
 * 
 * Provides common functionality and validation rules for all form requests
 * in the system, including role-based validation and Indonesian error messages.
 */
abstract class BaseFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * 
     * Override this method in child classes for specific authorization logic.
     */
    public function authorize(): bool
    {
        return true; // Default to authorized, override in child classes if needed
    }

    /**
     * Get custom attributes for validator errors.
     * 
     * Provides Indonesian field names for better user experience.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'email' => 'alamat email',
            'password' => 'kata sandi',
            'password_confirmation' => 'konfirmasi kata sandi',
            'phone' => 'nomor telepon',
            'address' => 'alamat',
            'role' => 'peran',
            'nim' => 'NIM',
            'nip' => 'NIP',
            'title' => 'judul',
            'description' => 'deskripsi',
            'content' => 'konten',
            'status' => 'status',
            'start_date' => 'tanggal mulai',
            'end_date' => 'tanggal selesai',
            'company_id' => 'perusahaan',
            'supervisor_id' => 'dosen pembimbing',
            'field_supervisor_id' => 'pembimbing lapangan',
            'file' => 'file',
            'document' => 'dokumen',
            'report_date' => 'tanggal laporan',
            'activities' => 'kegiatan',
            'problems' => 'kendala',
            'solutions' => 'solusi',
            'score' => 'nilai',
            'feedback' => 'umpan balik',
            'message' => 'pesan',
            'subject' => 'subjek',
            'recipient_id' => 'penerima',
            'attachment' => 'lampiran',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     * 
     * Provides Indonesian error messages for better user experience.
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'required_if' => ':attribute wajib diisi ketika :other adalah :value.',
            'required_unless' => ':attribute wajib diisi kecuali :other adalah :values.',
            'required_with' => ':attribute wajib diisi ketika :values ada.',
            'required_with_all' => ':attribute wajib diisi ketika :values ada.',
            'required_without' => ':attribute wajib diisi ketika :values tidak ada.',
            'required_without_all' => ':attribute wajib diisi ketika tidak ada :values.',
            'string' => ':attribute harus berupa teks.',
            'email' => ':attribute harus berupa alamat email yang valid.',
            'unique' => ':attribute sudah digunakan.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'min' => [
                'string' => ':attribute minimal :min karakter.',
                'numeric' => ':attribute minimal :min.',
                'file' => ':attribute minimal :min KB.',
            ],
            'max' => [
                'string' => ':attribute maksimal :max karakter.',
                'numeric' => ':attribute maksimal :max.',
                'file' => ':attribute maksimal :max KB.',
            ],
            'confirmed' => 'Konfirmasi :attribute tidak cocok.',
            'same' => ':attribute dan :other harus sama.',
            'different' => ':attribute dan :other harus berbeda.',
            'in' => ':attribute yang dipilih tidak valid.',
            'not_in' => ':attribute yang dipilih tidak valid.',
            'numeric' => ':attribute harus berupa angka.',
            'integer' => ':attribute harus berupa bilangan bulat.',
            'boolean' => ':attribute harus berupa true atau false.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'date_format' => ':attribute tidak sesuai format :format.',
            'before' => ':attribute harus sebelum :date.',
            'after' => ':attribute harus setelah :date.',
            'before_or_equal' => ':attribute harus sebelum atau sama dengan :date.',
            'after_or_equal' => ':attribute harus setelah atau sama dengan :date.',
            'image' => ':attribute harus berupa gambar.',
            'mimes' => ':attribute harus berupa file dengan tipe: :values.',
            'mimetypes' => ':attribute harus berupa file dengan tipe: :values.',
            'file' => ':attribute harus berupa file.',
            'size' => [
                'string' => ':attribute harus :size karakter.',
                'numeric' => ':attribute harus :size.',
                'file' => ':attribute harus :size KB.',
            ],
            'between' => [
                'string' => ':attribute harus antara :min sampai :max karakter.',
                'numeric' => ':attribute harus antara :min sampai :max.',
                'file' => ':attribute harus antara :min sampai :max KB.',
            ],
            'alpha' => ':attribute hanya boleh berisi huruf.',
            'alpha_dash' => ':attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
            'alpha_num' => ':attribute hanya boleh berisi huruf dan angka.',
            'regex' => 'Format :attribute tidak valid.',
            'url' => ':attribute harus berupa URL yang valid.',
            'json' => ':attribute harus berupa JSON yang valid.',
            'array' => ':attribute harus berupa array.',
            'filled' => ':attribute wajib diisi.',
            'nullable' => ':attribute boleh kosong.',
            'sometimes' => ':attribute opsional.',
        ];
    }

    /**
     * Common validation rules for user-related fields
     */
    protected function getUserValidationRules(bool $isUpdate = false): array
    {
        $emailRule = $isUpdate ? 'sometimes|email|unique:users,email,' . $this->route('user') : 'required|email|unique:users,email';
        
        return [
            'name' => 'required|string|max:255',
            'email' => $emailRule,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ];
    }

    /**
     * Common validation rules for role-specific fields
     */
    protected function getRoleValidationRules(string $role): array
    {
        $rules = [];

        switch ($role) {
            case 'mahasiswa':
                $rules['nim'] = 'required|string|max:20|unique:users,nim';
                $rules['nip'] = 'nullable';
                break;
            
            case 'dosen':
            case 'koordinator':
            case 'admin':
                $rules['nip'] = 'required|string|max:20|unique:users,nip';
                $rules['nim'] = 'nullable';
                break;
            
            case 'pembimbing_lapangan':
                $rules['nim'] = 'nullable';
                $rules['nip'] = 'nullable';
                break;
        }

        return $rules;
    }

    /**
     * Common validation rules for file uploads
     */
    protected function getFileValidationRules(array $allowedTypes = ['pdf', 'doc', 'docx'], int $maxSize = 2048): array
    {
        return [
            'file' => 'required|file|mimes:' . implode(',', $allowedTypes) . '|max:' . $maxSize,
        ];
    }

    /**
     * Common validation rules for image uploads
     */
    protected function getImageValidationRules(int $maxSize = 2048): array
    {
        return [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:' . $maxSize,
        ];
    }

    /**
     * Common validation rules for date fields
     */
    protected function getDateValidationRules(): array
    {
        return [
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ];
    }

    /**
     * Check if user has required role(s)
     */
    protected function hasRole(string|array $roles): bool
    {
        $user = $this->user();
        
        if (!$user) {
            return false;
        }

        $userRole = $user->role;
        $allowedRoles = is_array($roles) ? $roles : [$roles];

        return in_array($userRole, $allowedRoles);
    }

    /**
     * Check if user owns the resource
     */
    protected function ownsResource(mixed $resource, string $userIdField = 'user_id'): bool
    {
        $user = $this->user();
        
        if (!$user || !$resource) {
            return false;
        }

        return $user->id === $resource->{$userIdField};
    }

    /**
     * Check if user can access resource (owns it or has admin/koordinator role)
     */
    protected function canAccessResource(mixed $resource, string $userIdField = 'user_id'): bool
    {
        return $this->hasRole(['admin', 'koordinator']) || $this->ownsResource($resource, $userIdField);
    }

    /**
     * Get the "after" validation callables for the request.
     * 
     * Override this method in child classes for additional validation logic.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                // Additional validation logic can be added here
                // This will be called after all other validation rules pass
            }
        ];
    }

    /**
     * Handle a passed validation attempt.
     * 
     * Override this method in child classes to modify data after validation passes.
     */
    protected function passedValidation(): void
    {
        // Default implementation does nothing
        // Override in child classes if needed
    }

    /**
     * Prepare the data for validation.
     * 
     * Override this method in child classes to modify data before validation.
     */
    protected function prepareForValidation(): void
    {
        // Default implementation does nothing
        // Override in child classes if needed
    }
}
