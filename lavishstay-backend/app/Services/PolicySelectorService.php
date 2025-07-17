<?php

namespace App\Services;

use App\Models\PolicyApplication;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PolicySelectorService
{
    public function selectPolicies(array $input): array
    {
        $roomTypeId = $input['room_type_id'];
        $date = Carbon::parse($input['date']);
        $occupancyPercent = $input['occupancy_percent'];
        $isHoliday = $input['is_holiday'];


        $types = ['cancellation', 'deposit', 'check_out'];
        $result = [];

        foreach ($types as $type) {
            $query = PolicyApplication::query()
                ->where('policy_type', $type)
                ->where('is_active', true)
                ->where(function ($q) use ($roomTypeId) {
                    $q->whereNull('room_type_id')
                      ->orWhere('room_type_id', $roomTypeId);
                })
                ->where(function ($q) use ($isHoliday) {
                    $q->whereNull('applies_to_holiday')
                      ->orWhere('applies_to_holiday', $isHoliday);
                })
                ->where(function ($q) use ($occupancyPercent) {
                    $q->whereNull('min_occupancy_percent')
                      ->orWhere('min_occupancy_percent', '<=', $occupancyPercent);
                })
                ->where(function ($q) use ($occupancyPercent) {
                    $q->whereNull('max_occupancy_percent')
                      ->orWhere('max_occupancy_percent', '>=', $occupancyPercent);
                })
                ->where(function ($q) use ($date) {
                    $q->whereNull('date_from')
                      ->orWhere('date_from', '<=', $date);
                })
                ->where(function ($q) use ($date) {
                    $q->whereNull('date_to')
                      ->orWhere('date_to', '>=', $date);
                })
                ->orderByDesc('priority')
                ->orderByDesc('id'); // Nếu priority bằng nhau, ưu tiên record mới hơn

            $policyApplication = $query->first();

       

            // Set policy ID
            $result[$type . '_policy_id'] = $policyApplication?->policy_id;

            if ($policyApplication) {
                // Get the actual policy content
                $policyModel = match ($type) {
                    'cancellation' => \App\Models\CancellationPolicy::class,
                    'deposit' => \App\Models\DepositPolicy::class,
                    'check_out' => \App\Models\CheckOutPolicy::class,
                };

                $realPolicy = $policyModel::find($policyApplication->policy_id);
            

                // Set the real policy content
                $result[$type . '_policy_applied'] = $realPolicy;
                
                // Store application details for debugging
                $result[$type . '_policy_application'] = $policyApplication;
            } else {
                $result[$type . '_policy_applied'] = null;
                $result[$type . '_policy_application'] = null;
            }
        }

        return $result;
    }

    private function isHoliday($date): bool
    {
        return DB::table('holiday_events')
                ->where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->exists();
    }
}
