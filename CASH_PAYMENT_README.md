# Cash Payment Collection Implementation

## Overview

Implementation of cash payment collection system for hotel booking management. This allows receptionists and cashiers to mark cash payments as collected directly from the admin interface.

## Features

- Display payment method (Online/Tại khách sạn) in booking table
- Cash collection button for at_hotel payments
- Modal form for entering cash collection details
- Backend validation and security
- Audit logging

## Frontend Changes

### BookingManagement.tsx

- Added "Thanh toán" column showing payment type with colored tags
- Added "Thu tiền" column with collection button for at_hotel payments
- Added cash collection modal with form fields:
  - Amount (default to booking total)
  - Receipt number (optional)
  - Notes (optional)
- Confirmation dialog before submitting

## Backend Changes

### Migration

File: `database/migrations/2025_08_26_000001_add_cash_collection_fields_to_payment_table.php`

Adds fields to `payment` table:

- `collected_by` - Id của người dùng đã thu tiền mặt
- `collected_at` - Dấu thời gian khi tiền mặt được thu thập
- `cash_amount_vnd` - Số tiền được thu thập trong VND
- `cash_receipt_number` - Số biên lai (tùy chọn)
- `collector_notes` - Notes about collection (optional)

### Controller Method

File: `app/Http/Controllers/Api/ReceptionController.php`

Added `markCashPaid()` method:

- Validates user role (receptionist/cashier only)
- Validates request data
- Updates payment record with collection info
- Handles partial payments
- Marks payment as completed when full amount collected
- Updates booking status if needed
- Audit logging

### Route

Added to `routes/api.php`:

```php
Route::post('/reception/mark-cash-paid', [ReceptionController::class, 'markCashPaid'])->middleware('auth:api');
```

## API Usage

### Request

```http
POST /api/reception/mark-cash-paid
Content-Type: application/json
Authorization: Bearer {token}

{
  "booking_id": 123,
  "payment_id": 456,  // optional
  "amount_vnd": 1500000,
  "receipt_number": "RC001",  // optional
  "notes": "Thu tiền mặt từ khách"  // optional
}
```

### Response - Success

```json
{
  "success": true,
  "message": "Recorded cash collection",
  "data": {
    "booking": {
      "booking_id": 123,
      "booking_code": "LVS123456",
      "status": "confirmed",
      "total_price_vnd": 1500000
    },
    "payment": {
      "payment_id": 456,
      "payment_type": "at_hotel",
      "status": "completed",
      "cash_amount_vnd": 1500000,
      "collected_by": 1,
      "collected_at": "2025-08-26T10:30:00.000000Z",
      "transaction_id": "cash_LVS123456_20250826103000"
    }
  }
}
```

### Response - Error

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "amount_vnd": ["The amount vnd field is required."]
  }
}
```

## Deployment Steps

### Backend

1. Run migration:

   ```bash
   php artisan migrate
   ```

2. Clear route cache:

   ```bash
   php artisan route:clear
   php artisan route:cache
   ```

3. Test API endpoint:
   ```bash
   php artisan test
   ```

### Frontend

1. Build and check for errors:

   ```bash
   npm run build
   npm run lint
   ```

2. Test UI functionality in development:
   ```bash
   npm run dev
   ```

## Testing

### Manual Testing

1. Create a booking with payment_type = 'at_hotel'
2. Open booking management page
3. Verify "Thu tiền" button appears for at_hotel bookings
4. Click button and fill modal form
5. Confirm payment collection
6. Verify payment status changes to "Đã thu"

### Backend Testing

Basic integration test:

```php
// Test case: Mark cash as paid
$booking = Booking::factory()->create(['total_price_vnd' => 1500000]);
$payment = Payment::factory()->create([
    'booking_id' => $booking->booking_id,
    'payment_type' => 'at_hotel',
    'status' => 'pending'
]);

$response = $this->postJson('/api/reception/mark-cash-paid', [
    'booking_id' => $booking->booking_id,
    'amount_vnd' => 1500000,
    'receipt_number' => 'RC001'
]);

$response->assertStatus(200);
$this->assertEquals('completed', $payment->fresh()->status);
```

## Security Notes

- Only users with role 'receptionist' or 'cashier' can mark cash as paid
- All actions are logged with user ID and timestamp
- Payment validation prevents duplicate collections
- Database transactions ensure data consistency

## UI/UX Features

- Confirmation dialog: "Bạn chắc chắn đã nhận tiền mặt?"
- Form validation with clear error messages
- Optimistic UI updates after successful submission
- Disabled states during form submission
- Colored tags for easy visual identification of payment types
