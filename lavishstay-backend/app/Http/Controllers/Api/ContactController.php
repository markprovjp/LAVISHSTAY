<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ContactResponse;
use App\Mail\AdminContactNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'subject' => 'required|string|max:255',
                'message' => 'required|string|max:2000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $validator->errors()
                ], 422);
            }

            $data = $validator->validated();

            // Save to contact_messages
            $contactMessage = DB::table('contact_messages')->insertGetId([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'subject' => $data['subject'],
                'message' => $data['message'],
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Queue customer email
            Mail::to($data['email'])->queue(new ContactResponse($data));

            // Queue admin notification
            Mail::to('quyenjpn@gmail.com')
                ->queue(new AdminContactNotification($data));

            Log::info('Contact form submitted', [
                'contact_id' => $contactMessage,
                'email' => $data['email'],
                'subject' => $data['subject']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã nhận yêu cầu. Kiểm tra email để xác nhận.'
            ]);

        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý yêu cầu'
            ], 500);
        }
    }
}
