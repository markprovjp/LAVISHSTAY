<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\BookingCleanupService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CleanupController extends Controller
{
    protected $cleanupService;

    public function __construct(BookingCleanupService $cleanupService)
    {
        $this->cleanupService = $cleanupService;
    }

    /**
     * Preview expired pending bookings
     */
    public function previewExpirePending(): JsonResponse
    {
        try {
            $result = $this->cleanupService->expirePendingBookings(true);
            
            return response()->json([
                'success' => true,
                'data' => $result,
                'requiresConfirmation' => $result['deleted'] > 10,
                'message' => "Sẽ xóa {$result['deleted']} booking pending hết hạn"
            ]);
        } catch (\Exception $e) {
            Log::error('Preview expire pending failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể preview xóa booking pending'
            ], 500);
        }
    }

    /**
     * Execute expired pending bookings cleanup
     */
    public function executeExpirePending(Request $request): JsonResponse
    {
        try {
            // Validate confirmation code if required
            $previewResult = $this->cleanupService->expirePendingBookings(true);
            if ($previewResult['deleted'] > 10) {
                $request->validate([
                    'confirmation_code' => 'required|string|min:4'
                ]);
                
                $expectedCode = substr(md5($previewResult['deleted'] . date('Y-m-d')), 0, 6);
                if ($request->confirmation_code !== $expectedCode) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mã xác nhận không đúng'
                    ], 400);
                }
            }

            // Acquire lock
            if (!$this->cleanupService->acquireLock('expire_pending', 30)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hệ thống đang thực hiện cleanup khác, vui lòng thử lại sau'
                ], 423);
            }

            try {
                $result = $this->cleanupService->expirePendingBookings(false);
                
                return response()->json([
                    'success' => true,
                    'data' => $result,
                    'message' => "Đã xóa {$result['deleted']} booking pending hết hạn"
                ]);
            } finally {
                $this->cleanupService->releaseLock('expire_pending');
            }
            
        } catch (\Exception $e) {
            Log::error('Execute expire pending failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể thực hiện xóa booking pending'
            ], 500);
        }
    }

    /**
     * Preview complete past checkouts
     */
    public function previewCompletePastCheckouts(): JsonResponse
    {
        try {
            $result = $this->cleanupService->completePastCheckouts(true);
            
            return response()->json([
                'success' => true,
                'data' => $result,
                'requiresConfirmation' => $result['updated'] > 20,
                'message' => "Sẽ chuyển {$result['updated']} booking sang trạng thái Completed"
            ]);
        } catch (\Exception $e) {
            Log::error('Preview complete checkouts failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể preview hoàn thành checkout'
            ], 500);
        }
    }

    /**
     * Execute complete past checkouts
     */
    public function executeCompletePastCheckouts(Request $request): JsonResponse
    {
        try {
            // Validate confirmation code if required
            $previewResult = $this->cleanupService->completePastCheckouts(true);
            if ($previewResult['updated'] > 20) {
                $request->validate([
                    'confirmation_code' => 'required|string|min:4'
                ]);
                
                $expectedCode = substr(md5($previewResult['updated'] . date('Y-m-d')), 0, 6);
                if ($request->confirmation_code !== $expectedCode) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mã xác nhận không đúng'
                    ], 400);
                }
            }

            // Acquire lock
            if (!$this->cleanupService->acquireLock('complete_checkouts', 30)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hệ thống đang thực hiện cleanup khác, vui lòng thử lại sau'
                ], 423);
            }

            try {
                $result = $this->cleanupService->completePastCheckouts(false);
                
                return response()->json([
                    'success' => true,
                    'data' => $result,
                    'message' => "Đã chuyển {$result['updated']} booking sang trạng thái Completed"
                ]);
            } finally {
                $this->cleanupService->releaseLock('complete_checkouts');
            }
            
        } catch (\Exception $e) {
            Log::error('Execute complete checkouts failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể thực hiện hoàn thành checkout'
            ], 500);
        }
    }

    /**
     * Preview complete cleaning bookings
     */
    public function previewCompleteCleaningBookings(): JsonResponse
    {
        try {
            $result = $this->cleanupService->completeCleaningBookings(true);
            
            return response()->json([
                'success' => true,
                'data' => $result,
                'requiresConfirmation' => $result['cleaningUpdated'] > 15,
                'message' => "Sẽ chuyển {$result['cleaningUpdated']} booking từ Cleaning sang Completed và xóa thông tin dọn dẹp {$result['roomsCleared']} phòng"
            ]);
        } catch (\Exception $e) {
            Log::error('Preview complete cleaning failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể preview hoàn thành dọn dẹp'
            ], 500);
        }
    }

    /**
     * Execute complete cleaning bookings
     */
    public function executeCompleteCleaningBookings(Request $request): JsonResponse
    {
        try {
            // Validate confirmation code if required
            $previewResult = $this->cleanupService->completeCleaningBookings(true);
            if ($previewResult['cleaningUpdated'] > 15) {
                $request->validate([
                    'confirmation_code' => 'required|string|min:4'
                ]);
                
                $expectedCode = substr(md5($previewResult['cleaningUpdated'] . date('Y-m-d')), 0, 6);
                if ($request->confirmation_code !== $expectedCode) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mã xác nhận không đúng'
                    ], 400);
                }
            }

            // Acquire lock
            if (!$this->cleanupService->acquireLock('complete_cleaning', 30)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hệ thống đang thực hiện cleanup khác, vui lòng thử lại sau'
                ], 423);
            }

            try {
                $result = $this->cleanupService->completeCleaningBookings(false);
                
                return response()->json([
                    'success' => true,
                    'data' => $result,
                    'message' => "Đã chuyển {$result['cleaningUpdated']} booking từ Cleaning sang Completed và xóa thông tin dọn dẹp {$result['roomsCleared']} phòng"
                ]);
            } finally {
                $this->cleanupService->releaseLock('complete_cleaning');
            }
            
        } catch (\Exception $e) {
            Log::error('Execute complete cleaning failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể thực hiện hoàn thành dọn dẹp'
            ], 500);
        }
    }

    /**
     * Preview run all cleanup operations
     */
    public function previewRunAll(): JsonResponse
    {
        try {
            $result = $this->cleanupService->runAll(true);
            
            $totalAffected = $result['summary']['total_bookings_affected'];
            
            return response()->json([
                'success' => true,
                'data' => $result,
                'requiresConfirmation' => $totalAffected > 30,
                'message' => "Sẽ thực hiện tất cả cleanup, ảnh hưởng {$totalAffected} booking"
            ]);
        } catch (\Exception $e) {
            Log::error('Preview run all failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể preview tất cả cleanup'
            ], 500);
        }
    }

    /**
     * Execute run all cleanup operations
     */
    public function executeRunAll(Request $request): JsonResponse
    {
        try {
            // Validate confirmation code if required
            $previewResult = $this->cleanupService->runAll(true);
            $totalAffected = $previewResult['summary']['total_bookings_affected'];
            
            if ($totalAffected > 30) {
                $request->validate([
                    'confirmation_code' => 'required|string|min:4'
                ]);
                
                $expectedCode = substr(md5($totalAffected . date('Y-m-d')), 0, 6);
                if ($request->confirmation_code !== $expectedCode) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Mã xác nhận không đúng'
                    ], 400);
                }
            }

            // Acquire global lock
            if (!$this->cleanupService->acquireLock('run_all_cleanup', 60)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hệ thống đang thực hiện cleanup khác, vui lòng thử lại sau'
                ], 423);
            }

            try {
                $result = $this->cleanupService->runAll(false);
                
                return response()->json([
                    'success' => true,
                    'data' => $result,
                    'message' => "Đã thực hiện tất cả cleanup, ảnh hưởng {$result['summary']['total_bookings_affected']} booking"
                ]);
            } finally {
                $this->cleanupService->releaseLock('run_all_cleanup');
            }
            
        } catch (\Exception $e) {
            Log::error('Execute run all failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể thực hiện tất cả cleanup'
            ], 500);
        }
    }

    /**
     * Get confirmation code for destructive operations
     */
    public function getConfirmationCode(Request $request): JsonResponse
    {
        $request->validate([
            'operation' => 'required|string|in:expire_pending,complete_checkouts,complete_cleaning,run_all',
            'count' => 'required|integer|min:1'
        ]);

        try {
            $count = $request->count;
            $code = substr(md5($count . date('Y-m-d')), 0, 6);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'confirmation_code' => $code,
                    'expires_at' => now()->addMinutes(10)->toISOString()
                ],
                'message' => "Mã xác nhận cho thao tác ảnh hưởng {$count} bản ghi"
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get confirmation code failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo mã xác nhận'
            ], 500);
        }
    }
}
