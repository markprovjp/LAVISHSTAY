<?php

namespace App\Services\Checkout;

use App\Models\Booking;
use App\Services\Checkout\Contracts\CheckoutRuleInterface;
use App\Services\Checkout\Rules\BookingStatusRule;
use App\Services\Checkout\Rules\PaymentSufficientRule;
use App\Services\Checkout\Rules\CheckoutTimeRule;
use App\Services\Checkout\Rules\RoomAvailabilityRule;
use App\Services\Checkout\Rules\OutstandingServicesRule;
use App\Services\Checkout\Rules\EarlyCheckoutRule;
use App\Services\Checkout\Rules\GuestInformationRule;
use App\Services\Checkout\Rules\DamageAssessmentRule;
use Illuminate\Support\Facades\Log;

class CheckoutRuleEngine
{
    /**
     * @var CheckoutRuleInterface[]
     */
    private array $rules = [];

    /**
     * @var array
     */
    private array $ruleResults = [];

    public function __construct()
    {
        $this->loadDefaultRules();
    }

    /**
     * Load default checkout rules
     */
    private function loadDefaultRules(): void
    {
        $this->rules = [
            new BookingStatusRule(),
            new PaymentSufficientRule(),
            new CheckoutTimeRule(),
            new RoomAvailabilityRule(),
            new OutstandingServicesRule(),
            new EarlyCheckoutRule(),
            new GuestInformationRule(),
            new DamageAssessmentRule(),
        ];

        // Sort by priority
        usort($this->rules, function($a, $b) {
            return $a->getPriority() <=> $b->getPriority();
        });
    }

    /**
     * Add a custom rule
     */
    public function addRule(CheckoutRuleInterface $rule): self
    {
        $this->rules[] = $rule;
        
        // Re-sort by priority
        usort($this->rules, function($a, $b) {
            return $a->getPriority() <=> $b->getPriority();
        });

        return $this;
    }

    /**
     * Remove a rule by ID
     */
    public function removeRule(string $ruleId): self
    {
        $this->rules = array_filter($this->rules, function($rule) use ($ruleId) {
            return $rule->getRuleId() !== $ruleId;
        });

        return $this;
    }

    /**
     * Validate booking against all rules
     */
    public function validate(Booking $booking, array $context = []): CheckoutValidationResult
    {
        Log::info('=== CheckoutRuleEngine@validate START ===', [
            'booking_id' => $booking->booking_id,
            'booking_code' => $booking->booking_code,
            'total_rules' => count($this->rules)
        ]);

        $this->ruleResults = [];
        $blockingFailures = [];
        $warnings = [];
        $passed = [];

        foreach ($this->rules as $rule) {
            try {
                $ruleId = $rule->getRuleId();
                $passes = $rule->passes($booking, $context);
                
                $this->ruleResults[$ruleId] = [
                    'rule_id' => $ruleId,
                    'passes' => $passes,
                    'message' => $rule->getMessage(),
                    'priority' => $rule->getPriority(),
                    'is_blocking' => $rule->isBlocking(),
                ];

                if ($passes) {
                    $passed[] = $this->ruleResults[$ruleId];
                } else {
                    if ($rule->isBlocking()) {
                        $blockingFailures[] = $this->ruleResults[$ruleId];
                    } else {
                        $warnings[] = $this->ruleResults[$ruleId];
                    }
                }

                Log::debug("Rule {$ruleId}: " . ($passes ? 'PASSED' : 'FAILED'));

            } catch (\Exception $e) {
                Log::error("Error executing rule {$rule->getRuleId()}: " . $e->getMessage());
                
                // Treat rule execution errors as blocking failures
                $blockingFailures[] = [
                    'rule_id' => $rule->getRuleId(),
                    'passes' => false,
                    'message' => 'Lỗi kiểm tra quy tắc: ' . $e->getMessage(),
                    'priority' => $rule->getPriority(),
                    'is_blocking' => true,
                ];
            }
        }

        $canCheckout = empty($blockingFailures);

        $result = new CheckoutValidationResult(
            $canCheckout,
            $blockingFailures,
            $warnings,
            $passed,
            $this->ruleResults
        );

        Log::info('=== CheckoutRuleEngine@validate COMPLETED ===', [
            'can_checkout' => $canCheckout,
            'blocking_failures' => count($blockingFailures),
            'warnings' => count($warnings),
            'passed' => count($passed)
        ]);

        return $result;
    }

    /**
     * Get all rules
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * Get rule results from last validation
     */
    public function getRuleResults(): array
    {
        return $this->ruleResults;
    }
}

/**
 * Checkout validation result
 */
class CheckoutValidationResult
{
    public bool $canCheckout;
    public array $blockingFailures;
    public array $warnings;
    public array $passed;
    public array $allResults;

    public function __construct(
        bool $canCheckout,
        array $blockingFailures,
        array $warnings,
        array $passed,
        array $allResults
    ) {
        $this->canCheckout = $canCheckout;
        $this->blockingFailures = $blockingFailures;
        $this->warnings = $warnings;
        $this->passed = $passed;
        $this->allResults = $allResults;
    }

    /**
     * Get summary of validation
     */
    public function getSummary(): array
    {
        return [
            'can_checkout' => $this->canCheckout,
            'total_rules_checked' => count($this->allResults),
            'blocking_failures_count' => count($this->blockingFailures),
            'warnings_count' => count($this->warnings),
            'passed_count' => count($this->passed),
            'validation_status' => $this->canCheckout ? 'APPROVED' : 'BLOCKED'
        ];
    }

    /**
     * Get detailed results
     */
    public function getDetailedResults(): array
    {
        return [
            'summary' => $this->getSummary(),
            'blocking_failures' => $this->blockingFailures,
            'warnings' => $this->warnings,
            'passed_rules' => $this->passed,
            'all_rule_results' => $this->allResults
        ];
    }

    /**
     * Check if has warnings
     */
    public function hasWarnings(): bool
    {
        return !empty($this->warnings);
    }

    /**
     * Check if has blocking failures
     */
    public function hasBlockingFailures(): bool
    {
        return !empty($this->blockingFailures);
    }
}