<?php

namespace App\Models;

use App\Contracts\SpecialRequestInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CompensationRequest extends Model implements SpecialRequestInterface
{
    protected $table = 'compensation_requests';
    protected $primaryKey = 'request_id';

    protected $fillable = [
        'booking_id',
        'requested_by',
        'policy_id',
        'custom_reason',
        'status',
        'requested_amount',
        'approved_amount',
        'approved_by',
        'approved_at',
        'attachments',
        'admin_note'
    ];

    protected $casts = [
        'attachments' => 'array',
        'approved_at' => 'datetime',
        'requested_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2'
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function policy()
    {
        return $this->belongsTo(CompensationPolicy::class, 'policy_id', 'compensation_policy_id');
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'rejected');
    }

    // Interface Implementation
    public function getRequestType(): string
    {
        return 'checkout_compensation';
    }

    public function getRequestTypeLabel(): string
    {
        return 'Bồi thường checkout';
    }

    public function getBooking()
    {
        return $this->booking;
    }

    public function getRequestedBy()
    {
        return $this->requestedBy;
    }

    public function getApprovedBy()
    {
        return $this->approvedBy;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getStatusLabel(): string
    {
        $labels = [
            'pending' => 'Chờ duyệt',
            'approved' => 'Đã duyệt',
            'rejected' => 'Từ chối',
            'applied' => 'Đã áp dụng'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getReason(): ?string
    {
        return $this->custom_reason ?? $this->policy?->name;
    }

    public function getAdminNote(): ?string
    {
        return $this->admin_note;
    }

    public function getRequestedAmount(): ?float
    {
        return $this->requested_amount ? (float) $this->requested_amount : null;
    }

    public function getApprovedAmount(): ?float
    {
        return $this->approved_amount ? (float) $this->approved_amount : null;
    }

    public function getAttachments(): ?array
    {
        return $this->attachments;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getApprovedAt()
    {
        return $this->approved_at;
    }

    public function canBeApproved(): bool
    {
        return $this->status === 'pending';
    }

    public function canBeRejected(): bool
    {
        return $this->status === 'pending';
    }

    public function getPriority(): int
    {
        // Higher priority for larger amounts or policy-based requests
        if ($this->policy_id) {
            return 2; // Policy-based requests have medium priority
        }

        if ($this->requested_amount && $this->requested_amount > 1000000) {
            return 1; // High amount requests have high priority
        }

        return 3; // Default priority
    }

    public function getDisplayData(): array
    {
        return [
            'id' => $this->request_id,
            'type' => $this->getRequestType(),
            'type_label' => $this->getRequestTypeLabel(),
            'booking_code' => $this->booking?->booking_code,
            'guest_name' => $this->booking?->guest_name,
            'status' => $this->getStatus(),
            'status_label' => $this->getStatusLabel(),
            'requested_amount' => $this->getRequestedAmount(),
            'approved_amount' => $this->getApprovedAmount(),
            'formatted_requested_amount' => $this->getRequestedAmount() ? 
                number_format($this->getRequestedAmount(), 0, ',', '.') . ' ₫' : null,
            'formatted_approved_amount' => $this->getApprovedAmount() ? 
                number_format($this->getApprovedAmount(), 0, ',', '.') . ' ₫' : null,
            'reason' => $this->getReason(),
            'requested_by' => $this->requestedBy?->name,
            'approved_by' => $this->approvedBy?->name,
            'created_at' => $this->getCreatedAt(),
            'approved_at' => $this->getApprovedAt(),
            'priority' => $this->getPriority(),
            'has_attachments' => !empty($this->getAttachments()),
            'attachments_count' => is_array($this->getAttachments()) ? count($this->getAttachments()) : 0,
        ];
    }

    public function getDetailedData(): array
    {
        $displayData = $this->getDisplayData();
        
        return array_merge($displayData, [
            'booking_details' => [
                'booking_id' => $this->booking?->booking_id,
                'booking_code' => $this->booking?->booking_code,
                'guest_name' => $this->booking?->guest_name,
                'guest_email' => $this->booking?->guest_email,
                'check_in_date' => $this->booking?->check_in_date,
                'check_out_date' => $this->booking?->check_out_date,
                'booking_status' => $this->booking?->status,
                'total_price_vnd' => $this->booking?->total_price_vnd,
                'formatted_total_price' => $this->booking?->total_price_vnd ? 
                    number_format($this->booking->total_price_vnd, 0, ',', '.') . ' ₫' : null,
            ],
            'policy_details' => $this->policy ? [
                'policy_id' => $this->policy->compensation_policy_id,
                'name' => $this->policy->name,
                'description' => $this->policy->description,
                'condition_type' => $this->policy->condition_type,
                'discount_type' => $this->policy->discount_type,
                'discount_value' => $this->policy->discount_value,
                'max_compensation_amount' => $this->policy->max_compensation_amount,
            ] : null,
            'custom_reason' => $this->custom_reason,
            'admin_note' => $this->getAdminNote(),
            'attachments' => $this->getAttachments(),
            'staff_details' => [
                'requested_by' => $this->requestedBy ? [
                    'id' => $this->requestedBy->id,
                    'name' => $this->requestedBy->name,
                    'email' => $this->requestedBy->email,
                ] : null,
                'approved_by' => $this->approvedBy ? [
                    'id' => $this->approvedBy->id,
                    'name' => $this->approvedBy->name,
                    'email' => $this->approvedBy->email,
                ] : null,
            ],
            'timeline' => [
                'requested_at' => $this->getCreatedAt(),
                'approved_at' => $this->getApprovedAt(),
                'processing_time' => $this->getApprovedAt() ? 
                    $this->getCreatedAt()->diffForHumans($this->getApprovedAt()) : null,
            ],
            'can_approve' => $this->canBeApproved(),
            'can_reject' => $this->canBeRejected(),
        ]);
    }

    // Additional helper methods
    public function getFormattedRequestedAmountAttribute(): ?string
    {
        return $this->requested_amount ? number_format($this->requested_amount, 0, ',', '.') . ' ₫' : null;
    }

    public function getFormattedApprovedAmountAttribute(): ?string
    {
        return $this->approved_amount ? number_format($this->approved_amount, 0, ',', '.') . ' ₫' : null;
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->getStatusLabel();
    }
}