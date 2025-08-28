<?php

namespace App\Services;

use App\Models\CompensationRequest;
use App\Models\BookingReschedule;
use App\Contracts\SpecialRequestInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpecialRequestService
{
    /**
     * Get all special requests from different tables
     */
    public function getAllSpecialRequests(array $filters = []): Collection
    {
        try {
            Log::info('=== SpecialRequestService@getAllSpecialRequests START ===', $filters);

            $requests = collect();

            // Get compensation requests
            $compensationRequests = $this->getCompensationRequests($filters);
            $requests = $requests->merge($compensationRequests);

            // Get reschedule requests
            $rescheduleRequests = $this->getRescheduleRequests($filters);
            $requests = $requests->merge($rescheduleRequests);

            // TODO: Add other request types here
            // $checkinRequests = $this->getCheckinRequests($filters);
            // $requests = $requests->merge($checkinRequests);

            // Sort by created_at desc (newest first)
            $requests = $requests->sortByDesc(function ($request) {
                return $request->getCreatedAt();
            });

            // Apply additional filters
            if (!empty($filters['status'])) {
                $requests = $requests->filter(function ($request) use ($filters) {
                    return $request->getStatus() === $filters['status'];
                });
            }

            if (!empty($filters['request_type'])) {
                $requests = $requests->filter(function ($request) use ($filters) {
                    return $request->getRequestType() === $filters['request_type'];
                });
            }

            if (!empty($filters['booking_code'])) {
                $requests = $requests->filter(function ($request) use ($filters) {
                    $booking = $request->getBooking();
                    return $booking && stripos($booking->booking_code, $filters['booking_code']) !== false;
                });
            }

            if (!empty($filters['date_from'])) {
                $requests = $requests->filter(function ($request) use ($filters) {
                    return $request->getCreatedAt()->format('Y-m-d') >= $filters['date_from'];
                });
            }

            if (!empty($filters['date_to'])) {
                $requests = $requests->filter(function ($request) use ($filters) {
                    return $request->getCreatedAt()->format('Y-m-d') <= $filters['date_to'];
                });
            }

            Log::info('=== SpecialRequestService@getAllSpecialRequests SUCCESS ===', [
                'total_requests' => $requests->count()
            ]);

            return $requests;

        } catch (\Exception $e) {
            Log::error('=== ERROR in getAllSpecialRequests ===', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return collect();
        }
    }

    /**
     * Get compensation requests
     */
    private function getCompensationRequests(array $filters = []): Collection
    {
        $query = CompensationRequest::with([
            'booking:booking_id,booking_code,guest_name,guest_email,check_in_date,check_out_date,status,total_price_vnd',
            'policy:compensation_policy_id,name,condition_type,discount_type,discount_value',
            'requestedBy:id,name,email',
            'approvedBy:id,name,email'
        ]);

        // Apply basic filters at query level for performance
        if (!empty($filters['status']) && in_array($filters['status'], ['pending', 'approved', 'rejected', 'applied'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->get();
    }

    /**
     * Get reschedule requests
     */
    private function getRescheduleRequests(array $filters = []): Collection
    {
        // Check if reschedule requests table exists
        try {
            $query = BookingReschedule::with([
                'booking:booking_id,booking_code,guest_name,guest_email,check_in_date,check_out_date,status,total_price_vnd',
                'requestedBy:id,name,email',
                'approvedBy:id,name,email'
            ]);

            // Apply basic filters at query level for performance
            if (!empty($filters['status']) && in_array($filters['status'], ['pending', 'approved', 'rejected'])) {
                $query->where('status', $filters['status']);
            }

            if (!empty($filters['date_from'])) {
                $query->whereDate('created_at', '>=', $filters['date_from']);
            }

            if (!empty($filters['date_to'])) {
                $query->whereDate('created_at', '<=', $filters['date_to']);
            }

            return $query->get();

        } catch (\Exception $e) {
            Log::warning('BookingReschedule table not found or error: ' . $e->getMessage());
            return collect();
        }
    }

    /**
     * Get statistics for dashboard
     */
    public function getStatistics(): array
    {
        try {
            $allRequests = $this->getAllSpecialRequests();

            $stats = [
                'total_requests' => $allRequests->count(),
                'pending_requests' => $allRequests->where('status', 'pending')->count(),
                'approved_requests' => $allRequests->where('status', 'approved')->count(),
                'rejected_requests' => $allRequests->where('status', 'rejected')->count(),
                'today_requests' => $allRequests->filter(function ($request) {
                    return $request->getCreatedAt()->isToday();
                })->count(),
                'this_week_requests' => $allRequests->filter(function ($request) {
                    return $request->getCreatedAt()->isCurrentWeek();
                })->count(),
            ];

            // Request type breakdown
            $requestTypes = $allRequests->groupBy(function ($request) {
                return $request->getRequestType();
            });

            $stats['request_types'] = $requestTypes->map(function ($requests, $type) {
                return [
                    'type' => $type,
                    'count' => $requests->count(),
                    'pending' => $requests->where('status', 'pending')->count(),
                ];
            })->values()->toArray();

            // Total compensation amounts
            $compensationRequests = $allRequests->filter(function ($request) {
                return $request->getRequestType() === 'checkout_compensation';
            });

            $stats['total_requested_amount'] = $compensationRequests->sum(function ($request) {
                return $request->getRequestedAmount() ?? 0;
            });

            $stats['total_approved_amount'] = $compensationRequests->filter(function ($request) {
                return $request->getStatus() === 'approved';
            })->sum(function ($request) {
                return $request->getApprovedAmount() ?? 0;
            });

            return $stats;

        } catch (\Exception $e) {
            Log::error('Error getting special request statistics: ' . $e->getMessage());
            return [
                'total_requests' => 0,
                'pending_requests' => 0,
                'approved_requests' => 0,
                'rejected_requests' => 0,
                'today_requests' => 0,
                'this_week_requests' => 0,
                'request_types' => [],
                'total_requested_amount' => 0,
                'total_approved_amount' => 0,
            ];
        }
    }

    /**
     * Get available request types
     */
    public function getAvailableRequestTypes(): array
    {
        return [
            'checkout_compensation' => 'Bồi thường checkout',
            'booking_reschedule' => 'Thay đổi lịch đặt',
            'checkin_special' => 'Check-in đặc biệt',
            'cancellation_special' => 'Hủy đặc biệt',
            'extension_special' => 'Gia hạn đặc biệt',
            'room_transfer' => 'Chuyển phòng',
            'other' => 'Khác',
        ];
    }

    /**
     * Get available statuses
     */
    public function getAvailableStatuses(): array
    {
        return [
            'pending' => 'Chờ duyệt',
            'approved' => 'Đã duyệt',
            'rejected' => 'Từ chối',
            'applied' => 'Đã áp dụng',
        ];
    }

    /**
     * Find a specific request by type and ID
     */
    public function findRequest(string $type, int $id): ?SpecialRequestInterface
    {
        try {
            switch ($type) {
                case 'checkout_compensation':
                    return CompensationRequest::with([
                        'booking',
                        'policy',
                        'requestedBy',
                        'approvedBy'
                    ])->find($id);

                case 'booking_reschedule':
                    return BookingReschedule::with([
                        'booking',
                        'requestedBy',
                        'approvedBy'
                    ])->find($id);

                // TODO: Add other request types
                default:
                    return null;
            }
        } catch (\Exception $e) {
            Log::error("Error finding request {$type}:{$id} - " . $e->getMessage());
            return null;
        }
    }

    /**
     * Approve a request
     */
    public function approveRequest(string $type, int $id, array $data): bool
    {
        try {
            $request = $this->findRequest($type, $id);
            if (!$request || !$request->canBeApproved()) {
                return false;
            }

            // Handle approval based on request type
            switch ($type) {
                case 'checkout_compensation':
                    return $this->approveCompensationRequest($request, $data);

                case 'booking_reschedule':
                    return $this->approveRescheduleRequest($request, $data);

                // TODO: Add other request types
                default:
                    return false;
            }

        } catch (\Exception $e) {
            Log::error("Error approving request {$type}:{$id} - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Reject a request
     */
    public function rejectRequest(string $type, int $id, array $data): bool
    {
        try {
            $request = $this->findRequest($type, $id);
            if (!$request || !$request->canBeRejected()) {
                return false;
            }

            // Handle rejection based on request type
            switch ($type) {
                case 'checkout_compensation':
                    return $this->rejectCompensationRequest($request, $data);

                case 'booking_reschedule':
                    return $this->rejectRescheduleRequest($request, $data);

                // TODO: Add other request types
                default:
                    return false;
            }

        } catch (\Exception $e) {
            Log::error("Error rejecting request {$type}:{$id} - " . $e->getMessage());
            return false;
        }
    }

    /**
     * Private methods for handling specific request types
     */
    private function approveCompensationRequest($request, array $data): bool
    {
        return DB::transaction(function () use ($request, $data) {
            $request->update([
                'status' => 'approved',
                'approved_amount' => $data['approved_amount'] ?? $request->requested_amount,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'admin_note' => $data['admin_note'] ?? null,
            ]);

            // Create audit log
            DB::table('audit_logs')->insert([
                'user_id' => auth()->id(),
                // action must match enum in audit_logs table; use 'update' for approve
                'action' => 'update',
                'model' => 'compensation_requests',
                'model_id' => $request->request_id,
                'changes_summary' => 'Approved compensation request',
                'description' => "Approved compensation request for booking {$request->booking->booking_code}",
                'created_at' => now(),
            ]);

            return true;
        });
    }

    private function rejectCompensationRequest($request, array $data): bool
    {
        return DB::transaction(function () use ($request, $data) {
            $request->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'admin_note' => $data['admin_note'] ?? null,
            ]);

            // Create audit log
            DB::table('audit_logs')->insert([
                'user_id' => auth()->id(),
                // action must match enum in audit_logs table; use 'update' for reject
                'action' => 'update',
                'model' => 'compensation_requests',
                'model_id' => $request->request_id,
                'changes_summary' => 'Rejected compensation request',
                'description' => "Rejected compensation request for booking {$request->booking->booking_code}",
                'created_at' => now(),
            ]);

            return true;
        });
    }

    private function approveRescheduleRequest($request, array $data): bool
    {
        // Implementation for reschedule request approval
        return true;
    }

    private function rejectRescheduleRequest($request, array $data): bool
    {
        // Implementation for reschedule request rejection
        return true;
    }
}