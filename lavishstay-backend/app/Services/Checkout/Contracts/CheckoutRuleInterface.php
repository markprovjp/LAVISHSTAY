<?php

namespace App\Services\Checkout\Contracts;

use App\Models\Booking;

interface CheckoutRuleInterface
{
    /**
     * Check if the rule passes for the given booking
     *
     * @param Booking $booking
     * @param array $context Additional context data
     * @return bool
     */
    public function passes(Booking $booking, array $context = []): bool;

    /**
     * Get the error message when rule fails
     *
     * @return string
     */
    public function getMessage(): string;

    /**
     * Get the rule priority (lower number = higher priority)
     *
     * @return int
     */
    public function getPriority(): int;

    /**
     * Get rule identifier
     *
     * @return string
     */
    public function getRuleId(): string;

    /**
     * Check if this rule is blocking (prevents checkout) or warning only
     *
     * @return bool
     */
    public function isBlocking(): bool;
}