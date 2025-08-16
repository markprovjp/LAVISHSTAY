<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->enum('type', ['string', 'number', 'boolean', 'json'])->default('string');
            $table->string('group_name', 100);
            $table->text('description')->nullable();
            $table->boolean('is_encrypted')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default settings
        $defaultSettings = [
            // VietQR Settings
            [
                'key' => 'vietqr.bank_id',
                'value' => 'MBBank',
                'type' => 'string',
                'group_name' => 'vietqr',
                'description' => 'Mã ngân hàng cho VietQR',
                'is_active' => true
            ],
            [
                'key' => 'vietqr.account_no',
                'value' => '0335920306',
                'type' => 'string',
                'group_name' => 'vietqr',
                'description' => 'Số tài khoản ngân hàng',
                'is_active' => true
            ],
            [
                'key' => 'vietqr.account_name',
                'value' => 'NGUYEN VAN QUYEN',
                'type' => 'string',
                'group_name' => 'vietqr',
                'description' => 'Tên chủ tài khoản',
                'is_active' => true
            ],
            [
                'key' => 'vietqr.template',
                'value' => 'print',
                'type' => 'string',
                'group_name' => 'vietqr',
                'description' => 'Template QR code',
                'is_active' => true
            ],
            [
                'key' => 'vietqr.enabled',
                'value' => '1',
                'type' => 'boolean',
                'group_name' => 'vietqr',
                'description' => 'Bật/tắt thanh toán VietQR',
                'is_active' => true
            ],

            // CPay/Seepay Settings
            [
                'key' => 'cpay.google_script_url',
                'value' => env('CPAY_GOOGLE_SCRIPT_URL', ''),
                'type' => 'string',
                'group_name' => 'cpay',
                'description' => 'URL Google Apps Script cho CPay',
                'is_encrypted' => true,
                'is_active' => true
            ],
            [
                'key' => 'cpay.timeout',
                'value' => '30',
                'type' => 'number',
                'group_name' => 'cpay',
                'description' => 'Timeout cho API CPay (giây)',
                'is_active' => true
            ],
            [
                'key' => 'cpay.enabled',
                'value' => '1',
                'type' => 'boolean',
                'group_name' => 'cpay',
                'description' => 'Bật/tắt kiểm tra thanh toán CPay',
                'is_active' => true
            ],

            // VNPay Settings
            [
                'key' => 'vnpay.enabled',
                'value' => '0',
                'type' => 'boolean',
                'group_name' => 'vnpay',
                'description' => 'Bật/tắt thanh toán VNPay',
                'is_active' => true
            ],
            [
                'key' => 'vnpay.merchant_id',
                'value' => '',
                'type' => 'string',
                'group_name' => 'vnpay',
                'description' => 'Mã merchant VNPay',
                'is_encrypted' => true,
                'is_active' => true
            ],
            [
                'key' => 'vnpay.hash_secret',
                'value' => '',
                'type' => 'string',
                'group_name' => 'vnpay',
                'description' => 'Hash secret VNPay',
                'is_encrypted' => true,
                'is_active' => true
            ],

            // Pay at Hotel Settings
            [
                'key' => 'pay_at_hotel.enabled',
                'value' => '1',
                'type' => 'boolean',
                'group_name' => 'pay_at_hotel',
                'description' => 'Bật/tắt thanh toán tại khách sạn',
                'is_active' => true
            ],

            // General Settings
            [
                'key' => 'general.default_payment_method',
                'value' => 'vietqr',
                'type' => 'string',
                'group_name' => 'general',
                'description' => 'Phương thức thanh toán mặc định',
                'is_active' => true
            ],
            [
                'key' => 'general.api_base_url',
                'value' => 'http://localhost:8888/api',
                'type' => 'string',
                'group_name' => 'general',
                'description' => 'Base URL cho API',
                'is_active' => true
            ],
            [
                'key' => 'general.payment_timeout',
                'value' => '900',
                'type' => 'number',
                'group_name' => 'general',
                'description' => 'Thời gian timeout thanh toán (giây)',
                'is_active' => true
            ]
        ];

        foreach ($defaultSettings as $setting) {
            DB::table('payment_settings')->insert(array_merge($setting, [
                'created_at' => now(),
                'updated_at' => now()
            ]));
        }
    }

    public function down()
    {
        Schema::dropIfExists('payment_settings');
    }
};