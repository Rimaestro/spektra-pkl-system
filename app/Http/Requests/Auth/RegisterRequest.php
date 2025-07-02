<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s]+$/'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email'
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).*$/'
            ],
            'role' => [
                'required',
                'in:mahasiswa,dosen,pembimbing_lapangan'
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^[\+]?[0-9\-\(\)\s]+$/'
            ],
            'address' => [
                'nullable',
                'string',
                'max:500'
            ]
        ];

        // Role-specific validation
        if ($this->input('role') === 'mahasiswa') {
            $rules['nim'] = [
                'required',
                'string',
                'size:10',
                'unique:users,nim',
                'regex:/^[0-9]+$/'
            ];
        }

        if (in_array($this->input('role'), ['dosen', 'koordinator'])) {
            $rules['nip'] = [
                'required',
                'string',
                'size:18',
                'unique:users,nip',
                'regex:/^[0-9]+$/'
            ];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.regex' => 'Nama hanya boleh berisi huruf dan spasi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
            'nim.required' => 'NIM wajib diisi untuk mahasiswa.',
            'nim.size' => 'NIM harus 10 digit.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'nim.regex' => 'NIM hanya boleh berisi angka.',
            'nip.required' => 'NIP wajib diisi untuk dosen.',
            'nip.size' => 'NIP harus 18 digit.',
            'nip.unique' => 'NIP sudah terdaftar.',
            'nip.regex' => 'NIP hanya boleh berisi angka.',
            'phone.regex' => 'Format nomor telepon tidak valid.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama lengkap',
            'email' => 'email',
            'password' => 'password',
            'role' => 'role',
            'nim' => 'NIM',
            'nip' => 'NIP',
            'phone' => 'nomor telepon',
            'address' => 'alamat',
        ];
    }
}
