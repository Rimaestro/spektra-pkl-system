<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'pkl_id',
        'report_type',
        'title',
        'content',
        'file_path',
        'attachments',
        'report_date',
        'status',
        'feedback',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'report_date' => 'date',
            'attachments' => 'array',
        ];
    }

    /**
     * Check if report is draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Check if report is submitted
     */
    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    /**
     * Check if report is reviewed
     */
    public function isReviewed(): bool
    {
        return $this->status === 'reviewed';
    }

    /**
     * Check if report is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if report is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if report is daily type
     */
    public function isDaily(): bool
    {
        return $this->report_type === 'daily';
    }

    /**
     * Check if report is weekly type
     */
    public function isWeekly(): bool
    {
        return $this->report_type === 'weekly';
    }

    /**
     * Check if report is monthly type
     */
    public function isMonthly(): bool
    {
        return $this->report_type === 'monthly';
    }

    /**
     * Check if report is final type
     */
    public function isFinal(): bool
    {
        return $this->report_type === 'final';
    }

    /**
     * Get report type label
     */
    public function getReportTypeLabel(): string
    {
        return match($this->report_type) {
            'daily' => 'Laporan Harian',
            'weekly' => 'Laporan Mingguan',
            'monthly' => 'Laporan Bulanan',
            'final' => 'Laporan Akhir',
            default => 'Unknown'
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabel(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'submitted' => 'Submitted',
            'reviewed' => 'Reviewed',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => 'Unknown'
        };
    }

    /**
     * PKL yang terkait dengan laporan ini
     */
    public function pkl()
    {
        return $this->belongsTo(PKL::class);
    }

    /**
     * Scope untuk laporan berdasarkan tipe
     */
    public function scopeByType($query, $type)
    {
        return $query->where('report_type', $type);
    }

    /**
     * Scope untuk laporan berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk laporan yang sudah disubmit
     */
    public function scopeSubmitted($query)
    {
        return $query->whereIn('status', ['submitted', 'reviewed', 'approved', 'rejected']);
    }

    /**
     * Scope untuk laporan yang perlu direview
     */
    public function scopeNeedsReview($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Get status color class for UI
     */
    public function getStatusColorClass(): string
    {
        return match($this->status) {
            'draft' => 'text-secondary',
            'submitted' => 'text-warning',
            'reviewed' => 'text-info',
            'approved' => 'text-success',
            'rejected' => 'text-danger',
            default => 'text-secondary'
        };
    }

    /**
     * Get report type color class for UI
     */
    public function getReportTypeColorClass(): string
    {
        return match($this->report_type) {
            'daily' => 'text-primary',
            'weekly' => 'text-info',
            'monthly' => 'text-warning',
            'final' => 'text-success',
            default => 'text-secondary'
        };
    }

    /**
     * Check if report can be edited
     */
    public function canBeEdited(): bool
    {
        return in_array($this->status, ['draft', 'rejected']);
    }

    /**
     * Check if report can be submitted
     */
    public function canBeSubmitted(): bool
    {
        return $this->status === 'draft' &&
               !empty($this->title) &&
               !empty($this->content);
    }

    /**
     * Check if report can be reviewed
     */
    public function canBeReviewed(): bool
    {
        return $this->status === 'submitted';
    }

    /**
     * Get attachments count
     */
    public function getAttachmentsCount(): int
    {
        return is_array($this->attachments) ? count($this->attachments) : 0;
    }

    /**
     * Get word count of content
     */
    public function getWordCount(): int
    {
        return str_word_count(strip_tags($this->content ?? ''));
    }

    /**
     * Check if report is late
     */
    public function isLate(): bool
    {
        if (!$this->report_date) {
            return false;
        }

        $expectedDate = match($this->report_type) {
            'daily' => $this->report_date,
            'weekly' => $this->report_date->endOfWeek(),
            'monthly' => $this->report_date->endOfMonth(),
            'final' => $this->pkl->end_date ?? $this->report_date,
            default => $this->report_date
        };

        return $this->created_at->gt($expectedDate);
    }

    /**
     * User yang membuat laporan (melalui PKL)
     */
    public function user()
    {
        return $this->hasOneThrough(User::class, PKL::class, 'id', 'id', 'pkl_id', 'user_id');
    }
}
