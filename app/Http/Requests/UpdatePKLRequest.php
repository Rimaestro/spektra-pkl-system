<?php

namespace App\Http\Requests;

use App\Models\PKL;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * Form Request for updating PKL
 * 
 * Handles validation for PKL updates with role-based authorization
 * and status-dependent validation rules.
 */
class UpdatePKLRequest extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $pkl = $this->route('pkl');
        
        if (!$pkl) {
            return false;
        }

        // Admin and koordinator can update any PKL
        if ($this->hasRole(['admin', 'koordinator'])) {
            return true;
        }

        // Dosen can update PKL they supervise
        if ($this->hasRole('dosen') && $pkl->supervisor_id === $this->user()->id) {
            return true;
        }

        // Mahasiswa can update their own PKL (limited fields)
        if ($this->hasRole('mahasiswa') && $pkl->user_id === $this->user()->id) {
            return true;
        }

        // Pembimbing lapangan can update PKL they supervise
        if ($this->hasRole('pembimbing_lapangan') && $pkl->field_supervisor_id === $this->user()->id) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $pkl = $this->route('pkl');
        $user = $this->user();

        $rules = [];

        // Base rules that can be updated by authorized users
        if ($this->hasRole(['admin', 'koordinator'])) {
            $rules = [
                'description' => 'sometimes|string|max:1000',
                'company_id' => [
                    'sometimes',
                    'integer',
                    Rule::exists('companies', 'id')->where('status', 'active')
                ],
                'supervisor_id' => [
                    'sometimes',
                    'integer',
                    Rule::exists('users', 'id')->where(function ($query) {
                        $query->where('role', 'dosen')
                              ->where('status', 'active');
                    })
                ],
                'field_supervisor_id' => [
                    'sometimes',
                    'integer',
                    'nullable',
                    Rule::exists('users', 'id')->where(function ($query) {
                        $query->where('role', 'pembimbing_lapangan')
                              ->where('status', 'active');
                    })
                ],
                'start_date' => 'sometimes|date',
                'end_date' => 'sometimes|date|after:start_date',
                'documents' => 'sometimes|array',
                'documents.*' => 'sometimes|string|max:255',
                'status' => [
                    'sometimes',
                    'string',
                    Rule::in(['pending', 'approved', 'rejected', 'ongoing', 'completed', 'cancelled'])
                ],
                'rejection_reason' => 'sometimes|string|max:500',
                'final_score' => 'sometimes|numeric|min:0|max:100',
                'defense_date' => 'sometimes|date',
            ];
        } elseif ($this->hasRole('dosen') && $pkl->supervisor_id === $user->id) {
            // Dosen can update limited fields
            $rules = [
                'objectives' => 'sometimes|string|max:2000',
                'expected_outcomes' => 'sometimes|string|max:2000',
                'notes' => 'sometimes|string|max:1000',
                'status' => [
                    'sometimes',
                    'string',
                    Rule::in(['approved', 'rejected', 'completed'])
                ],
                'rejection_reason' => 'sometimes|string|max:500',
                'completion_notes' => 'sometimes|string|max:1000',
            ];
        } elseif ($this->hasRole('mahasiswa') && $pkl->user_id === $user->id) {
            // Mahasiswa can only update basic info if PKL is still pending
            if ($pkl->status === 'pending') {
                $rules = [
                    'description' => 'sometimes|string|max:1000',
                    'start_date' => 'sometimes|date',
                    'end_date' => 'sometimes|date|after:start_date',
                    'documents' => 'sometimes|array',
                    'documents.*' => 'sometimes|string|max:255',
                ];
            }
        } elseif ($this->hasRole('pembimbing_lapangan') && $pkl->field_supervisor_id === $user->id) {
            // Pembimbing lapangan can update notes and provide feedback
            $rules = [
                'notes' => 'sometimes|string|max:1000',
                'field_supervisor_feedback' => 'sometimes|string|max:1000',
            ];
        }

        return $rules;
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
            'status' => 'status',
            'rejection_reason' => 'alasan penolakan',
            'completion_notes' => 'catatan penyelesaian',
            'field_supervisor_feedback' => 'umpan balik pembimbing lapangan',
        ]);
    }

    /**
     * Get the "after" validation callables for the request.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $pkl = $this->route('pkl');

                // Validate status transitions
                if ($this->has('status')) {
                    $currentStatus = $pkl->status;
                    $newStatus = $this->status;

                    $validTransitions = [
                        'pending' => ['approved', 'rejected'],
                        'approved' => ['ongoing', 'cancelled'],
                        'ongoing' => ['completed', 'cancelled'],
                        'rejected' => ['pending'], // Can be resubmitted
                        'completed' => [], // Final state
                        'cancelled' => [], // Final state
                    ];

                    if (!in_array($newStatus, $validTransitions[$currentStatus] ?? [])) {
                        $validator->errors()->add(
                            'status',
                            "Tidak dapat mengubah status dari {$currentStatus} ke {$newStatus}."
                        );
                    }

                    // Require rejection reason when rejecting
                    if ($newStatus === 'rejected' && !$this->rejection_reason) {
                        $validator->errors()->add(
                            'rejection_reason',
                            'Alasan penolakan wajib diisi ketika menolak PKL.'
                        );
                    }

                    // Require completion notes when completing
                    if ($newStatus === 'completed' && !$this->completion_notes) {
                        $validator->errors()->add(
                            'completion_notes',
                            'Catatan penyelesaian wajib diisi ketika menyelesaikan PKL.'
                        );
                    }
                }

                // Validate date changes for ongoing PKL
                if ($pkl->status === 'ongoing' && ($this->has('start_date') || $this->has('end_date'))) {
                    $validator->errors()->add(
                        'start_date',
                        'Tanggal tidak dapat diubah untuk PKL yang sedang berlangsung.'
                    );
                }

                // Validate PKL duration if dates are being updated
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

                // Validate company capacity if company is being changed
                if ($this->company_id && $this->company_id !== $pkl->company_id) {
                    $company = \App\Models\Company::find($this->company_id);
                    if ($company) {
                        $currentPKLCount = PKL::where('company_id', $this->company_id)
                            ->whereIn('status', ['approved', 'ongoing'])
                            ->where('id', '!=', $pkl->id)
                            ->count();

                        if ($currentPKLCount >= $company->max_students) {
                            $validator->errors()->add(
                                'company_id',
                                'Perusahaan sudah mencapai kapasitas maksimal mahasiswa PKL.'
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
        // Ensure dates are in proper format if provided
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
}
