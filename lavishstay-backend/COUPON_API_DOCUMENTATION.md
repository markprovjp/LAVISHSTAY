# API Documentation - Coupon/Discount System

## Overview

The LavishStay hotel booking system now includes a comprehensive coupon/discount code system with the following features:

-   Percentage and fixed amount discounts
-   Usage limits (global and per-user)
-   Minimum booking amount requirements
-   Room type restrictions
-   Stackable and combinable coupons
-   Time-based validity
-   Admin management interface

## Public API Endpoints

### 1. Validate Coupon Code

**Endpoint:** `POST /api/coupons/validate`
**Description:** Validates a coupon code against a booking preview
**Authentication:** Not required

**Request Body:**

```json
{
    "code": "WELCOME2024",
    "booking_preview": {
        "base_price_vnd": 1000000,
        "taxes_vnd": 100000,
        "fees_vnd": 50000,
        "room_type_id": 1
    }
}
```

**Response (Success):**

```json
{
    "valid": true,
    "coupon": {
        "code": "WELCOME2024",
        "type": "percent",
        "value": 15,
        "description": "Chào mừng khách hàng mới - Giảm 15% cho lần đặt phòng đầu tiên",
        "min_booking_amount_vnd": 500000
    },
    "discount_vnd": 150000,
    "new_total_vnd": 1000000
}
```

**Response (Invalid):**

```json
{
    "valid": false,
    "reason": "expired",
    "message": "Mã giảm giá đã hết hạn"
}
```

### 2. Check Coupon Code Existence

**Endpoint:** `POST /api/coupons/check-code`
**Description:** Quick check if a coupon code exists and is active
**Authentication:** Not required

**Request Body:**

```json
{
    "code": "WELCOME2024"
}
```

**Response (Exists):**

```json
{
    "exists": true,
    "coupon": {
        "code": "WELCOME2024",
        "type": "percent",
        "value": 15,
        "description": "Chào mừng khách hàng mới - Giảm 15% cho lần đặt phòng đầu tiên"
    },
    "message": "Mã giảm giá hợp lệ"
}
```

**Response (Not Found):**

```json
{
    "exists": false,
    "message": "Mã giảm giá không tồn tại hoặc không có hiệu lực"
}
```

### 3. Apply Coupon to Booking

**Endpoint:** `POST /api/bookings/{booking_id}/apply-coupon`
**Description:** Apply a coupon code to an existing booking
**Authentication:** Required (Sanctum)

**Request Body:**

```json
{
    "code": "VIP300K"
}
```

**Response (Success):**

```json
{
    "success": true,
    "message": "Mã giảm giá đã được áp dụng thành công",
    "discount_amount": 300000,
    "new_total": 1700000,
    "coupon": {
        "code": "VIP300K",
        "description": "Ưu đãi VIP - Giảm 300.000đ cho đặt phòng cao cấp"
    }
}
```

**Response (Already Applied):**

```json
{
    "success": true,
    "existing": true,
    "message": "Mã đã được áp dụng trước đó",
    "discount_amount": 300000,
    "new_total": 1700000
}
```

**Response (Error):**

```json
{
    "success": false,
    "reason": "usage_limit",
    "message": "Mã giảm giá đã hết lượt sử dụng"
}
```

### 4. User Redemption History

**Endpoint:** `GET /api/coupons/my-redemptions`
**Description:** Get current user's coupon redemption history
**Authentication:** Required (Sanctum)

**Response:**

```json
{
    "success": true,
    "data": [
        {
            "coupon_code": "WELCOME2024",
            "amount_saved": 150000,
            "booking_code": "BK12345",
            "redeemed_at": "2024-08-23T10:30:00Z"
        },
        {
            "coupon_code": "VIP300K",
            "amount_saved": 300000,
            "booking_code": "BK12346",
            "redeemed_at": "2024-08-22T15:45:00Z"
        }
    ],
    "pagination": {
        "current_page": 1,
        "per_page": 15,
        "total": 2
    }
}
```

## Admin API Endpoints

### 1. List All Coupons

**Endpoint:** `GET /api/admin/coupons`
**Description:** Get paginated list of all coupons with filters
**Authentication:** Required (Admin role)

**Query Parameters:**

-   `page` - Page number (default: 1)
-   `per_page` - Items per page (default: 15)
-   `status` - Filter by status (active, inactive, expired)
-   `type` - Filter by type (percent, fixed)
-   `search` - Search in code or description

**Example:** `GET /api/admin/coupons?status=active&type=percent&search=welcome`

**Response:**

```json
{
    "data": [
        {
            "id": 1,
            "code": "WELCOME2024",
            "type": "percent",
            "value": 15,
            "currency": "VND",
            "description": "Chào mừng khách hàng mới",
            "start_at": "2024-08-23T00:00:00Z",
            "end_at": "2025-02-23T23:59:59Z",
            "usage_limit": 1000,
            "per_user_limit": 1,
            "min_booking_amount_vnd": 500000,
            "applicable_room_type_ids": null,
            "stackable": false,
            "active": true,
            "used_count": 25,
            "remaining_uses": 975,
            "created_at": "2024-08-23T10:00:00Z",
            "updated_at": "2024-08-23T10:00:00Z"
        }
    ],
    "current_page": 1,
    "per_page": 15,
    "total": 10,
    "last_page": 1
}
```

### 2. Create New Coupon

**Endpoint:** `POST /api/admin/coupons`
**Description:** Create a new coupon
**Authentication:** Required (Admin role)

**Request Body:**

```json
{
    "code": "NEWYEAR2025",
    "type": "percent",
    "value": 20,
    "currency": "VND",
    "description": "New Year Special - 20% off all bookings",
    "start_at": "2024-12-31T00:00:00",
    "end_at": "2025-01-15T23:59:59",
    "usage_limit": 500,
    "per_user_limit": 1,
    "min_booking_amount_vnd": 800000,
    "applicable_room_type_ids": [1, 2, 3],
    "stackable": false,
    "combinable_with": null,
    "active": true
}
```

**Response:**

```json
{
    "success": true,
    "message": "Mã giảm giá đã được tạo thành công",
    "data": {
        "id": 11,
        "code": "NEWYEAR2025",
        "type": "percent",
        "value": 20,
        "description": "New Year Special - 20% off all bookings",
        "created_by": 1,
        "created_at": "2024-08-23T11:00:00Z"
    }
}
```

### 3. Update Coupon

**Endpoint:** `PUT /api/admin/coupons/{id}`
**Description:** Update an existing coupon
**Authentication:** Required (Admin role)

**Request Body:**

```json
{
    "description": "Updated description",
    "value": 25,
    "active": false
}
```

### 4. Delete Coupon (Soft Delete)

**Endpoint:** `DELETE /api/admin/coupons/{id}`
**Description:** Soft delete a coupon
**Authentication:** Required (Admin role)

**Response:**

```json
{
    "success": true,
    "message": "Mã giảm giá đã được xóa thành công"
}
```

### 5. Restore Deleted Coupon

**Endpoint:** `POST /api/admin/coupons/{id}/restore`
**Description:** Restore a soft-deleted coupon
**Authentication:** Required (Admin role)

### 6. Coupon Statistics

**Endpoint:** `GET /api/admin/coupons/statistics`
**Description:** Get overall coupon system statistics
**Authentication:** Required (Admin role)

**Response:**

```json
{
    "total_coupons": 10,
    "active_coupons": 8,
    "total_redemptions": 150,
    "total_savings_vnd": 45000000,
    "average_discount_vnd": 300000,
    "most_used_coupons": [
        {
            "code": "WELCOME2024",
            "usage_count": 45,
            "total_savings": 6750000
        }
    ],
    "recent_activity": [
        {
            "type": "redemption",
            "coupon_code": "VIP300K",
            "user_email": "customer@example.com",
            "amount_saved": 300000,
            "timestamp": "2024-08-23T11:30:00Z"
        }
    ]
}
```

### 7. Coupon Redemption History

**Endpoint:** `GET /api/admin/coupons/{id}/redemptions`
**Description:** Get redemption history for a specific coupon
**Authentication:** Required (Admin role)

**Response:**

```json
{
    "data": [
        {
            "id": 1,
            "user": {
                "id": 123,
                "name": "John Doe",
                "email": "john@example.com"
            },
            "booking": {
                "booking_id": 456,
                "booking_code": "BK12345"
            },
            "amount_saved_vnd": 150000,
            "redeemed_at": "2024-08-23T10:30:00Z"
        }
    ],
    "current_page": 1,
    "per_page": 15,
    "total": 25
}
```

## Integration with Payment Flow

The coupon system is integrated into the existing booking creation process in `PaymentController@createBooking`. To apply a coupon during booking creation, include the `coupon_code` parameter:

**Request to `/api/payments/create-booking`:**

```json
{
    "room_type_id": 1,
    "check_in_date": "2024-09-01",
    "check_out_date": "2024-09-05",
    "guest_count": 2,
    "guest_name": "John Doe",
    "guest_email": "john@example.com",
    "guest_phone": "+84123456789",
    "coupon_code": "WELCOME2024"
}
```

The system will automatically:

1. Validate the coupon code
2. Apply the discount if valid
3. Store the redemption record
4. Return the booking with applied discount

## Error Codes and Messages

| Error Code        | Reason                         | Message                                    |
| ----------------- | ------------------------------ | ------------------------------------------ |
| `not_found`       | Coupon doesn't exist           | Mã giảm giá không tồn tại                  |
| `expired`         | Coupon has expired             | Mã giảm giá đã hết hạn                     |
| `not_started`     | Coupon not yet active          | Mã giảm giá chưa có hiệu lực               |
| `inactive`        | Coupon is disabled             | Mã giảm giá không hoạt động                |
| `usage_limit`     | Global usage limit reached     | Mã giảm giá đã hết lượt sử dụng            |
| `user_limit`      | User usage limit reached       | Bạn đã sử dụng hết lượt cho mã này         |
| `min_amount`      | Booking below minimum          | Giá trị booking chưa đạt yêu cầu tối thiểu |
| `room_type`       | Room type not applicable       | Mã không áp dụng cho loại phòng này        |
| `already_applied` | Coupon already used on booking | Mã đã được áp dụng cho booking này         |

## Sample Coupons

The system comes with sample coupons for testing:

| Code          | Type    | Value       | Description                    | Min Amount    | Restrictions        |
| ------------- | ------- | ----------- | ------------------------------ | ------------- | ------------------- |
| `WELCOME2024` | Percent | 15%         | Welcome discount for new users | 500,000 VND   | First use only      |
| `VIP300K`     | Fixed   | 300,000 VND | VIP discount for premium rooms | 2,000,000 VND | Premium rooms only  |
| `WEEKEND10`   | Percent | 10%         | Weekend special                | 800,000 VND   | Stackable           |
| `LOYALTY5`    | Percent | 5%          | Loyalty program                | 300,000 VND   | Stackable           |
| `EARLYBIRD`   | Fixed   | 150,000 VND | Early booking discount         | 1,000,000 VND | Stackable           |
| `FLASH50K`    | Fixed   | 50,000 VND  | Limited time flash sale        | 400,000 VND   | Limited uses        |
| `GROUP25`     | Percent | 25%         | Group booking discount         | 5,000,000 VND | Large bookings      |
| `STUDENT20`   | Percent | 20%         | Student discount               | 600,000 VND   | Standard rooms only |

## Security Features

-   **Rate Limiting:** API endpoints are rate-limited to prevent abuse
-   **Authorization:** Admin endpoints require proper authentication and authorization
-   **Input Validation:** All inputs are validated using Laravel Form Requests
-   **SQL Injection Prevention:** Using Eloquent ORM and parameterized queries
-   **Concurrency Control:** Database row locking prevents race conditions during coupon application
-   **Audit Trail:** All coupon creations and redemptions are logged with timestamps and user information

## Performance Considerations

-   **Database Indexing:** Optimized indexes on frequently queried columns
-   **Caching:** Consider implementing Redis caching for frequently accessed coupon data
-   **Pagination:** All list endpoints use pagination to handle large datasets
-   **Soft Deletes:** Coupons are soft deleted to maintain redemption history integrity

## Future Enhancements

1. **Geographic Restrictions:** Limit coupons to specific regions or countries
2. **Dynamic Pricing:** Coupons that adjust based on demand or seasonal factors
3. **Bulk Operations:** Import/export coupon codes from CSV files
4. **A/B Testing:** Create multiple versions of coupons for testing effectiveness
5. **Integration with Marketing Tools:** Connect with email marketing platforms
6. **Mobile Push Notifications:** Send coupon codes via push notifications
7. **Social Media Integration:** Share-to-unlock coupon mechanisms
