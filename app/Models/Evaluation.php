<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pkl_id',
        'evaluator_id',
        'evaluator_type',
        'technical_score',
        'attitude_score',
        'communication_score',
        'final_score',
        'comments',
        'suggestions',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'technical_score' => 'decimal:2',
            'attitude_score' => 'decimal:2',
            'communication_score' => 'decimal:2',
            'final_score' => 'decimal:2',
        ];
    }

    /**
     * Check if evaluation is draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if evaluation is submitted
     */
    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    /**
     * Check if evaluation is final
     */
    public function isFinal(): bool
    {
        return $this->status === 'final';
    }

    /**
     * Check if evaluator is supervisor
     */
    public function isSupervisorEvaluation(): bool
    {
        return $this->evaluator_type === 'supervisor';
    }

    /**
     * Check if evaluator is field supervisor
     */
    public function isFieldSupervisorEvaluation(): bool
    {
        return $this->evaluator_type === 'field_supervisor';
    }

    /**
     * Calculate final score from component scores
     */
    public function calculateFinalScore(): float
    {
        $scores = collect([
            $this->technical_score,
            $this->attitude_score,
            $this->communication_score
        ])->filter()->values();

        if ($scores->isEmpty()) {
            return 0;
        }

        return round($scores->average(), 2);
    }

    /**
     * Auto-calculate and update final score
     */
    public function updateFinalScore(): void
    {
        $this->final_score = $this->calculateFinalScore();
        $this->save();
    }

    /**
     * Get grade letter based on final score
     */
    public function getGradeLetter(): string
    {
        $score = $this->final_score;

        return match(true) {
            $score >= 85 => 'A',
            $score >= 80 => 'A-',
            $score >= 75 => 'B+',
            $score >= 70 => 'B',
            $score >= 65 => 'B-',
            $score >= 60 => 'C+',
            $score >= 55 => 'C',
            $score >= 50 => 'C-',
            $score >= 45 => 'D+',
            $score >= 40 => 'D',
            default => 'E'
        };
    }

    /**
     * Get evaluator type label
     */
    public function getEvaluatorTypeLabel(): string
    {
        return match($this->evaluator_type) {
            'supervisor' => 'Dosen Pembimbing',
            'field_supervisor' => 'Pembimbing Lapangan',
            default => 'Unknown'
        };
    }

    /**
     * PKL yang dievaluasi
     */
    public function pkl()
    {
        return $this->belongsTo(PKL::class, 'pkl_id');
    }

    /**
     * User yang melakukan evaluasi
     */
    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    /**
     * Scope untuk evaluasi berdasarkan tipe evaluator
     */
    public function scopeByEvaluatorType($query, $type)
    {
        return $query->where('evaluator_type', $type);
    }

    /**
     * Scope untuk evaluasi dari dosen pembimbing
     */
    public function scopeSupervisorEvaluations($query)
    {
        return $query->where('evaluator_type', 'supervisor');
    }

    /**
     * Scope untuk evaluasi dari pembimbing lapangan
     */
    public function scopeFieldSupervisorEvaluations($query)
    {
        return $query->where('evaluator_type', 'field_supervisor');
    }

    /**
     * Scope untuk evaluasi yang sudah final
     */
    public function scopeFinal($query)
    {
        return $query->where('status', 'final');
    }

    /**
     * Get status color class for UI
     */
    public function getStatusColorClass(): string
    {
        return match($this->status) {
            'draft' => 'text-secondary',
            'submitted' => 'text-warning',
            'final' => 'text-success',
            default => 'text-secondary'
        };
    }

    /**
     * Check if evaluation can be edited
     */
    public function canBeEdited(): bool
    {
        return in_array($this->status, ['draft', 'submitted']);
    }

    /**
     * Check if evaluation is complete
     */
    public function isComplete(): bool
    {
        return !is_null($this->technical_score) &&
               !is_null($this->attitude_score) &&
               !is_null($this->communication_score) &&
               !is_null($this->final_score);
    }

    /**
     * Get completion percentage
     */
    public function getCompletionPercentage(): float
    {
        $fields = [
            $this->technical_score,
            $this->attitude_score,
            $this->communication_score,
            $this->comments
        ];

        $completed = count(array_filter($fields, fn($field) => !is_null($field)));
        return ($completed / count($fields)) * 100;
    }

    /**
     * Get average score from all components
     */
    public function getAverageScore(): float
    {
        $scores = collect([
            $this->technical_score,
            $this->attitude_score,
            $this->communication_score
        ])->filter()->values();

        return $scores->isEmpty() ? 0 : round($scores->average(), 2);
    }

    /**
     * Check if scores are above threshold
     */
    public function isPassingGrade(float $threshold = 60.0): bool
    {
        return $this->final_score >= $threshold;
    }

    /**
     * Get evaluation weight based on evaluator type
     */
    public function getEvaluationWeight(): float
    {
        return match($this->evaluator_type) {
            'supervisor' => 0.4, // 40% weight for supervisor
            'field_supervisor' => 0.6, // 60% weight for field supervisor
            default => 0.5
        };
    }

    /**
     * User yang dievaluasi (melalui PKL)
     */
    public function student()
    {
        return $this->hasOneThrough(User::class, PKL::class, 'id', 'id', 'pkl_id', 'user_id');
    }

    /**
     * Company tempat PKL (melalui PKL)
     */
    public function company()
    {
        return $this->hasOneThrough(Company::class, PKL::class, 'id', 'id', 'pkl_id', 'company_id');
    }
}
