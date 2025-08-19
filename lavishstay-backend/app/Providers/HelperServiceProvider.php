<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\SpecialRequestHelper;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register helper functions globally
        if (!function_exists('getRequestTypeIcon')) {
            function getRequestTypeIcon(string $type): string {
                return SpecialRequestHelper::getRequestTypeIcon($type);
            }
        }

        if (!function_exists('getRequestTypeIconBg')) {
            function getRequestTypeIconBg(string $type): string {
                return SpecialRequestHelper::getRequestTypeIconBg($type);
            }
        }

        if (!function_exists('getStatusBadgeClass')) {
            function getStatusBadgeClass(string $status): string {
                return SpecialRequestHelper::getStatusBadgeClass($status);
            }
        }

        if (!function_exists('formatCurrency')) {
            function formatCurrency(?float $amount): string {
                return SpecialRequestHelper::formatCurrency($amount);
            }
        }

        if (!function_exists('getStatusLabel')) {
            function getStatusLabel(string $status): string {
                return SpecialRequestHelper::getStatusLabel($status);
            }
        }

        if (!function_exists('getRequestTypeLabel')) {
            function getRequestTypeLabel(string $type): string {
                return SpecialRequestHelper::getRequestTypeLabel($type);
            }
        }

        if (!function_exists('getPriorityBadge')) {
            function getPriorityBadge(int $priority): array {
                return SpecialRequestHelper::getPriorityBadge($priority);
            }
        }

        if (!function_exists('formatDateTime')) {
            function formatDateTime($dateTime): string {
                return SpecialRequestHelper::formatDateTime($dateTime);
            }
        }

        if (!function_exists('formatDate')) {
            function formatDate($date): string {
                return SpecialRequestHelper::formatDate($date);
            }
        }

        if (!function_exists('timeAgo')) {
            function timeAgo($dateTime): string {
                return SpecialRequestHelper::timeAgo($dateTime);
            }
        }

        if (!function_exists('isHighPriority')) {
            function isHighPriority(int $priority): bool {
                return SpecialRequestHelper::isHighPriority($priority);
            }
        }

        if (!function_exists('getAttachmentIcon')) {
            function getAttachmentIcon(string $filename): string {
                return SpecialRequestHelper::getAttachmentIcon($filename);
            }
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}