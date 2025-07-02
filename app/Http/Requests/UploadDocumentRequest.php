<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

/**
 * Form Request for uploading PKL documents
 * 
 * Handles validation for document uploads with file type validation
 * and role-based authorization.
 */
class UploadDocumentRequest extends BaseFormRequest
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

        // Admin and koordinator can upload documents for any PKL
        if ($this->hasRole(['admin', 'koordinator'])) {
            return true;
        }

        // Mahasiswa can upload documents for their own PKL
        if ($this->hasRole('mahasiswa') && $pkl->student_id === $this->user()->id) {
            return true;
        }

        // Dosen can upload documents for PKL they supervise
        if ($this->hasRole('dosen') && $pkl->supervisor_id === $this->user()->id) {
            return true;
        }

        // Pembimbing lapangan can upload documents for PKL they supervise
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
        return [
            'document_type' => [
                'required',
                'string',
                Rule::in([
                    'proposal',
                    'surat_permohonan',
                    'surat_balasan',
                    'laporan_harian',
                    'laporan_mingguan',
                    'laporan_akhir',
                    'sertifikat',
                    'evaluasi_pembimbing',
                    'evaluasi_perusahaan',
                    'dokumentasi',
                    'lainnya'
                ])
            ],
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'file' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png,gif',
                'max:10240' // 10MB max
            ],
            'is_required' => 'sometimes|boolean',
            'due_date' => 'nullable|date|after_or_equal:today',
            'notes' => 'nullable|string|max:500',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return array_merge(parent::attributes(), [
            'document_type' => 'jenis dokumen',
            'title' => 'judul dokumen',
            'description' => 'deskripsi',
            'file' => 'file dokumen',
            'is_required' => 'wajib',
            'due_date' => 'batas waktu',
            'notes' => 'catatan',
        ]);
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'document_type.in' => 'Jenis dokumen yang dipilih tidak valid.',
            'file.mimes' => 'File harus berupa: PDF, DOC, DOCX, JPG, JPEG, PNG, atau GIF.',
            'file.max' => 'Ukuran file maksimal 10MB.',
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

                // Validate document type based on PKL status
                $allowedDocumentsByStatus = [
                    'pending' => ['proposal', 'surat_permohonan', 'lainnya'],
                    'approved' => ['surat_balasan', 'laporan_harian', 'laporan_mingguan', 'dokumentasi', 'lainnya'],
                    'ongoing' => ['laporan_harian', 'laporan_mingguan', 'dokumentasi', 'lainnya'],
                    'completed' => ['laporan_akhir', 'sertifikat', 'evaluasi_pembimbing', 'evaluasi_perusahaan', 'lainnya'],
                ];

                $allowedTypes = $allowedDocumentsByStatus[$pkl->status] ?? [];
                
                if (!in_array($this->document_type, $allowedTypes)) {
                    $validator->errors()->add(
                        'document_type',
                        "Jenis dokumen '{$this->document_type}' tidak diizinkan untuk PKL dengan status '{$pkl->status}'."
                    );
                }

                // Validate file type based on document type
                $fileTypeRequirements = [
                    'proposal' => ['pdf', 'doc', 'docx'],
                    'surat_permohonan' => ['pdf', 'doc', 'docx'],
                    'surat_balasan' => ['pdf', 'doc', 'docx'],
                    'laporan_harian' => ['pdf', 'doc', 'docx'],
                    'laporan_mingguan' => ['pdf', 'doc', 'docx'],
                    'laporan_akhir' => ['pdf', 'doc', 'docx'],
                    'sertifikat' => ['pdf', 'jpg', 'jpeg', 'png'],
                    'evaluasi_pembimbing' => ['pdf', 'doc', 'docx'],
                    'evaluasi_perusahaan' => ['pdf', 'doc', 'docx'],
                    'dokumentasi' => ['jpg', 'jpeg', 'png', 'gif', 'pdf'],
                    'lainnya' => ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif'],
                ];

                if ($this->hasFile('file')) {
                    $fileExtension = $this->file('file')->getClientOriginalExtension();
                    $allowedExtensions = $fileTypeRequirements[$this->document_type] ?? [];

                    if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                        $validator->errors()->add(
                            'file',
                            "Jenis file tidak sesuai untuk dokumen '{$this->document_type}'. File yang diizinkan: " . implode(', ', $allowedExtensions)
                        );
                    }
                }

                // Check for duplicate document type (for certain types)
                $uniqueDocumentTypes = ['proposal', 'surat_permohonan', 'surat_balasan', 'laporan_akhir'];
                
                if (in_array($this->document_type, $uniqueDocumentTypes)) {
                    $existingDocument = \App\Models\PKLDocument::where('pkl_id', $pkl->id)
                        ->where('document_type', $this->document_type)
                        ->exists();

                    if ($existingDocument) {
                        $validator->errors()->add(
                            'document_type',
                            "Dokumen jenis '{$this->document_type}' sudah ada. Silakan hapus dokumen lama terlebih dahulu."
                        );
                    }
                }

                // Validate role-specific document upload permissions
                $user = $this->user();
                $restrictedDocuments = [
                    'mahasiswa' => ['evaluasi_pembimbing', 'evaluasi_perusahaan'],
                    'dosen' => ['evaluasi_perusahaan'],
                    'pembimbing_lapangan' => ['evaluasi_pembimbing'],
                ];

                $userRestrictions = $restrictedDocuments[$user->role] ?? [];
                
                if (in_array($this->document_type, $userRestrictions)) {
                    $validator->errors()->add(
                        'document_type',
                        "Anda tidak memiliki izin untuk mengunggah dokumen jenis '{$this->document_type}'."
                    );
                }
            }
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default values
        $this->merge([
            'is_required' => $this->is_required ?? false,
            'uploaded_by' => $this->user()->id,
        ]);

        // Format due date if provided
        if ($this->due_date) {
            $this->merge([
                'due_date' => \Carbon\Carbon::parse($this->due_date)->format('Y-m-d')
            ]);
        }
    }

    /**
     * Handle a passed validation attempt.
     */
    protected function passedValidation(): void
    {
        // Add PKL ID to the validated data
        $pkl = $this->route('pkl');
        $this->merge([
            'pkl_id' => $pkl->id,
            'uploaded_by' => $this->user()->id,
            'uploaded_at' => now(),
        ]);
    }
}
