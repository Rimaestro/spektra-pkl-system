<?php

namespace App\Http\Requests;

use App\Models\Company;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * Form Request for creating new PKL
 * 
 * Handles validation for PKL creation with role-based authorization
 * and business rule validation.
 */
class CreatePKLRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only admin, koordinator, and mahasiswa can create PKL
        return $this->hasRole(['admin', 'koordinator', 'mahasiswa']);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'company_id' => [
                'required',
                'integer',
                Rule::exists('companies', 'id')->where('status', 'active')
            ],
            'supervisor_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', 'dosen')
                          ->where('status', 'active');
                })
            ],
            'field_supervisor_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', 'pembimbing_lapangan')
                          ->where('status', 'active');
                })
            ],
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
            'description' => 'nullable|string|max:1000',
            'documents' => 'nullable|array',
            'documents.*' => 'nullable|string|max:255',

            // For admin/koordinator creating PKL for mahasiswa
            'student_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', 'mahasiswa')
                          ->where('status', 'active');
                })
            ],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'company_id' => 'perusahaan',
            'supervisor_id' => 'dosen pembimbing',
            'field_supervisor_id' => 'pembimbing lapangan',
            'start_date' => 'tanggal mulai',
            'end_date' => 'tanggal selesai',
            'objectives' => 'tujuan PKL',
            'expected_outcomes' => 'hasil yang diharapkan',
            'requirements' => 'persyaratan',
            'notes' => 'catatan',
            'student_id' => 'mahasiswa',
        ]);
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                // Validate PKL duration (minimum 1 month, maximum 6 months)
                if ($this->start_date && $this->end_date) {
                    $startDate = \Carbon\Carbon::parse($this->start_date);
                    $endDate = \Carbon\Carbon::parse($this->end_date);
                    $diffInMonths = $startDate->diffInMonths($endDate);

                    if ($diffInMonths < 1) {
                        $validator->errors()->add(
                            'end_date',
                            'Durasi PKL minimal 1 bulan.'
                        );
                    }

                    if ($diffInMonths > 6) {
                        $validator->errors()->add(
                            'end_date',
                            'Durasi PKL maksimal 6 bulan.'
                        );
                    }
                }

                // Validate that mahasiswa doesn't have active PKL
                $studentId = $this->student_id ?? $this->user()->id;
                if ($studentId) {
                    $activePKL = \App\Models\PKL::where('student_id', $studentId)
                        ->whereIn('status', ['pending', 'approved', 'ongoing'])
                        ->exists();

                    if ($activePKL) {
                        $validator->errors()->add(
                            'student_id',
                            'Mahasiswa sudah memiliki PKL yang aktif.'
                        );
                    }
                }

                // Validate company capacity
                if ($this->company_id) {
                    $company = Company::find($this->company_id);
                    if ($company) {
                        $currentPKLCount = \App\Models\PKL::where('company_id', $this->company_id)
                            ->whereIn('status', ['approved', 'ongoing'])
                            ->count();

                        if ($currentPKLCount >= $company->max_students) {
                            $validator->errors()->add(
                                'company_id',
                                'Perusahaan sudah mencapai kapasitas maksimal mahasiswa PKL.'
                            );
                        }
                    }
                }

                // Validate supervisor workload
                if ($this->supervisor_id) {
                    $currentSupervisionCount = \App\Models\PKL::where('supervisor_id', $this->supervisor_id)
                        ->whereIn('status', ['approved', 'ongoing'])
                        ->count();

                    if ($currentSupervisionCount >= 10) { // Max 10 students per supervisor
                        $validator->errors()->add(
                            'supervisor_id',
                            'Dosen pembimbing sudah mencapai kapasitas maksimal bimbingan.'
                        );
                    }
                }

                // Validate field supervisor assignment
                if ($this->field_supervisor_id && $this->company_id) {
                    $fieldSupervisor = User::find($this->field_supervisor_id);
                    if ($fieldSupervisor) {
                        $isAssignedToCompany = $fieldSupervisor->companies()
                            ->where('company_id', $this->company_id)
                            ->exists();

                        if (!$isAssignedToCompany) {
                            $validator->errors()->add(
                                'field_supervisor_id',
                                'Pembimbing lapangan tidak terdaftar di perusahaan yang dipilih.'
                            );
                        }
                    }
                }
            }
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // If user is mahasiswa and no student_id provided, use current user
        if ($this->user()->role === 'mahasiswa' && !$this->student_id) {
            $this->merge([
                'student_id' => $this->user()->id
            ]);
        }

        // Ensure dates are in proper format
        if ($this->start_date) {
            $this->merge([
                'start_date' => \Carbon\Carbon::parse($this->start_date)->format('Y-m-d')
            ]);
        }

        if ($this->end_date) {
            $this->merge([
                'end_date' => \Carbon\Carbon::parse($this->end_date)->format('Y-m-d')
            ]);
        }
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        // Set default status based on user role
        if ($this->user()->role === 'mahasiswa') {
            $this->merge(['status' => 'pending']);
        } else {
            $this->merge(['status' => 'approved']);
        }

        // Set student_id if not provided (for mahasiswa)
        if (!$this->student_id && $this->user()->role === 'mahasiswa') {
            $this->merge(['student_id' => $this->user()->id]);
        }
    }
}
