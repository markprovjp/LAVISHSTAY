<?php

namespace App\Contracts;

interface SpecialRequestInterface
{
    /**
     * Get the request type identifier
     */
    public function getRequestType(): string;

    /**
     * Get the request type label for display
     */
    public function getRequestTypeLabel(): string;

    /**
     * Get the booking associated with this request
     */
    public function getBooking();

    /**
     * Get the user who made the request
     */
    public function getRequestedBy();

    /**
     * Get the admin who approved/rejected the request
     */
    public function getApprovedBy();

    /**
     * Get the request status
     */
    public function getStatus(): string;

    /**
     * Get the request status label for display
     */
    public function getStatusLabel(): string;

    /**
     * Get the request reason/description
     */
    public function getReason(): ?string;

    /**
     * Get the admin note
     */
    public function getAdminNote(): ?string;

    /**
     * Get the requested amount (if applicable)
     */
    public function getRequestedAmount(): ?float;

    /**
     * Get the approved amount (if applicable)
     */
    public function getApprovedAmount(): ?float;

    /**
     * Get attachments (if any)
     */
    public function getAttachments(): ?array;

    /**
     * Get the creation timestamp
     */
    public function getCreatedAt();

    /**
     * Get the approval timestamp
     */
    public function getApprovedAt();

    /**
     * Check if request can be approved
     */
    public function canBeApproved(): bool;

    /**
     * Check if request can be rejected
     */
    public function canBeRejected(): bool;

    /**
     * Get priority level (1-5, 1 being highest)
     */
    public function getPriority(): int;

    /**
     * Get formatted display data for the dashboard
     */
    public function getDisplayData(): array;

    /**
     * Get detailed data for the modal
     */
    public function getDetailedData(): array;
}