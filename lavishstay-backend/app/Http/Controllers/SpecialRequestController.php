<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\SpecialRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SpecialRequestController extends Controller
{
    protected $specialRequestService;

    public function __construct(SpecialRequestService $specialRequestService)
    {
        $this->specialRequestService = $specialRequestService;
    }

    /**
     * Display the special requests dashboard
     */
    public function index(Request $request)
    {
        try {
            Log::info('=== SpecialRequestController@index START ===');
            Log::info('Request URL: ' . $request->fullUrl());
            Log::info('Query parameters: ', $request->query());

            // Validate filters
            $validated = $request->validate([
                'request_type' => 'nullable|string|in:checkout_compensation,booking_reschedule,checkin_special,cancellation_special,extension_special,room_transfer,other',
                'status' => 'nullable|string|in:pending,approved,rejected,applied',
                'booking_code' => 'nullable|string|max:50',
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date|after_or_equal:date_from',
                'per_page' => 'nullable|integer|min:1|max:100',
                'page' => 'nullable|integer|min:1',
            ]);

            // Get special requests with filters
            $allRequests = $this->specialRequestService->getAllSpecialRequests($validated);

            // Paginate results manually since we're combining multiple sources
            $perPage = $validated['per_page'] ?? 20;
            $currentPage = $validated['page'] ?? 1;
            $offset = ($currentPage - 1) * $perPage;
            
            $paginatedRequests = $allRequests->slice($offset, $perPage);
            $totalRequests = $allRequests->count();

            // Get statistics
            $statistics = $this->specialRequestService->getStatistics();

            // Get available options for filters
            $requestTypes = $this->specialRequestService->getAvailableRequestTypes();
            $statuses = $this->specialRequestService->getAvailableStatuses();

            // Prepare pagination info
            $pagination = [
                'current_page' => $currentPage,
                'per_page' => $perPage,
                'total' => $totalRequests,
                'last_page' => ceil($totalRequests / $perPage),
                'from' => $offset + 1,
                'to' => min($offset + $perPage, $totalRequests),
                'has_pages' => $totalRequests > $perPage,
            ];

            Log::info('=== SpecialRequestController@index SUCCESS ===', [
                'total_requests' => $totalRequests,
                'current_page' => $currentPage,
                'per_page' => $perPage
            ]);

            return view('admin.bookings.special-requests.index', compact(
                'paginatedRequests',
                'statistics',
                'requestTypes',
                'statuses',
                'pagination',
                'validated'
            ));

        } catch (\Exception $e) {
            Log::error('=== ERROR in SpecialRequestController@index ===');
            Log::error('Error message: ' . $e->getMessage());
            Log::error('File: ' . $e->getFile());
            Log::error('Line: ' . $e->getLine());

            return back()->with('error', 'Có lỗi xảy ra khi tải danh sách yêu cầu đặc biệt: ' . $e->getMessage());
        }
    }

    /**
     * Get request details for modal
     */
    public function show(Request $request, string $type, int $id)
    {
        try {
            Log::info('=== SpecialRequestController@show START ===', [
                'type' => $type,
                'id' => $id
            ]);

            $specialRequest = $this->specialRequestService->findRequest($type, $id);

            if (!$specialRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy yêu cầu đặc biệt'
                ], 404);
            }

            $detailedData = $specialRequest->getDetailedData();

            Log::info('=== SpecialRequestController@show SUCCESS ===');

            return response()->json([
                'success' => true,
                'message' => 'Lấy chi tiết yêu cầu thành công',
                'data' => $detailedData
            ]);

        } catch (\Exception $e) {
            Log::error('=== ERROR in SpecialRequestController@show ===');
            Log::error('Error message: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy chi tiết yêu cầu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Approve a special request
     */
    public function approve(Request $request, string $type, int $id)
    {
        try {
            Log::info('=== SpecialRequestController@approve START ===', [
                'type' => $type,
                'id' => $id,
                'data' => $request->all()
            ]);

            // Validate based on request type
            $rules = [
                'admin_note' => 'nullable|string|max:1000'
            ];

            if ($type === 'checkout_compensation') {
                $rules['approved_amount'] = 'required|numeric|min:0';
            }

            $validated = $request->validate($rules);

            $success = $this->specialRequestService->approveRequest($type, $id, $validated);

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể duyệt yêu cầu này'
                ], 400);
            }

            // TODO: Send real-time notification
            // TODO: Send email notification

            Log::info('=== SpecialRequestController@approve SUCCESS ===');

            return response()->json([
                'success' => true,
                'message' => 'Đã duyệt yêu cầu thành công',
                'data' => [
                    'approved_at' => now(),
                    'approved_by' => Auth::user()->name
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('=== ERROR in SpecialRequestController@approve ===');
            Log::error('Error message: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi duyệt yêu cầu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a special request
     */
    public function reject(Request $request, string $type, int $id)
    {
        try {
            Log::info('=== SpecialRequestController@reject START ===', [
                'type' => $type,
                'id' => $id,
                'data' => $request->all()
            ]);

            $validated = $request->validate([
                'admin_note' => 'required|string|max:1000'
            ]);

            $success = $this->specialRequestService->rejectRequest($type, $id, $validated);

            if (!$success) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể từ chối yêu cầu này'
                ], 400);
            }

            // TODO: Send real-time notification
            // TODO: Send email notification

            Log::info('=== SpecialRequestController@reject SUCCESS ===');

            return response()->json([
                'success' => true,
                'message' => 'Đã từ chối yêu cầu thành công',
                'data' => [
                    'rejected_at' => now(),
                    'rejected_by' => Auth::user()->name
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ', $e->errors());
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('=== ERROR in SpecialRequestController@reject ===');
            Log::error('Error message: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi từ chối yêu cầu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics for AJAX refresh
     */
    public function getStatistics(Request $request)
    {
        try {
            $statistics = $this->specialRequestService->getStatistics();

            return response()->json([
                'success' => true,
                'data' => $statistics
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting special request statistics: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy thống kê',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}