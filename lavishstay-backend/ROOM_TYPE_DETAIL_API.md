# Room Type Detail API Documentation

## Overview

API endpoint để lấy thông tin chi tiết của một loại phòng dựa trên slug. API này cung cấp đầy đủ thông tin cần thiết cho trang chi tiết room type bao gồm: thông tin cơ bản, hình ảnh, giá cả, tình trạng sẵn có, tiện ích, chính sách, đánh giá và phòng tương tự.

## Endpoints

### GET /api/room-types/{slug}

Lấy thông tin chi tiết của room type theo slug.

**Parameters:**

-   `slug` (string, required): Slug của room type (VD: deluxe, premium_corner)

**Query Parameters:**

-   `locale` (string, optional): Ngôn ngữ hiển thị (en|vi). Default: vi
-   `currency` (string, optional): Loại tiền tệ (VND|USD). Default: VND
-   `reviews_page` (integer, optional): Trang đánh giá. Default: 1
-   `reviews_per_page` (integer, optional): Số đánh giá mỗi trang (1-50). Default: 10
-   `related_limit` (integer, optional): Số phòng tương tự (1-20). Default: 6
-   `include` (string, optional): Các phần mở rộng (reviews,related,price_by_date). Default: all

**Example Request:**

```bash
GET /api/room-types/deluxe?locale=vi&currency=VND&reviews_page=1&reviews_per_page=5&related_limit=6
```

**Response Structure:**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "slug": "deluxe",
        "code": "deluxe",
        "name": "Deluxe Room",
        "short_description": "Phòng giường đôi rộng rãi này được bố trí máy điều hòa...",
        "description_html": "<p>Mô tả chi tiết phòng...</p>",
        "description_plain": "Mô tả chi tiết phòng...",
        "status": "published",
        "capacity": {
            "adults": 2,
            "children": 2
        },
        "size": 32,
        "max_occupancy": 2,
        "images": {
            "main": "http://localhost:8888/storage/room-types/1/1.jpg",
            "gallery": [
                "http://localhost:8888/storage/room-types/1/1.jpg",
                "http://localhost:8888/storage/room-types/1/2.jpg"
            ]
        },
        "price": {
            "currency": "VND",
            "min_price": 50000,
            "max_price": 65000,
            "base_price": 50000,
            "price_by_date": [
                {
                    "date": "2025-08-22",
                    "price": 60000
                }
            ]
        },
        "availability": {
            "total_rooms": 90,
            "available_rooms": 85,
            "next_available_date": null
        },
        "amenities": [
            {
                "id": 1,
                "name": "Điều hòa không khí tân tiến",
                "slug": "dieu_hoa_khong_khi_tan_tien",
                "icon": "Snowflake",
                "group": "basic"
            }
        ],
        "policies": [
            {
                "type": "cancellation",
                "title": "Chính sách hủy phòng",
                "description": "Khách có thể hủy miễn phí trước 24 giờ check-in..."
            }
        ],
        "ratings": {
            "average": 4.6,
            "count": 123,
            "distribution": {
                "1": 2,
                "2": 3,
                "3": 8,
                "4": 20,
                "5": 90
            }
        },
        "reviews": {
            "total": 123,
            "page": 1,
            "per_page": 5,
            "items": [
                {
                    "id": 1,
                    "user": {
                        "id": 1,
                        "name": "Nguyen Van A",
                        "avatar": "https://example.com/avatar.jpg"
                    },
                    "rating": 5.0,
                    "title": "Chất lượng tuyệt vời",
                    "body": "Phòng rất đẹp và sạch sẽ...",
                    "created_at": "2025-08-22T10:30:00.000000Z"
                }
            ]
        },
        "related_rooms": [
            {
                "id": 2,
                "slug": "premium_corner",
                "name": "Premium Corner",
                "thumbnail": "http://localhost:8888/storage/room-types/2/1.jpg",
                "price": {
                    "min": 1500000,
                    "max": 1950000
                },
                "short_description": "Phòng giường đôi rộng rãi này có máy điều hòa...",
                "tags": ["WiFi", "TV", "Minibar"],
                "score": 85.5
            }
        ],
        "meta": {
            "last_updated": "2025-08-22T10:30:00.000000Z",
            "created_at": "2025-08-22T10:30:00.000000Z"
        },
        "flags": {
            "booking_allowed": false
        },
        "links": {
            "self": "http://localhost:8888/api/room-types/deluxe",
            "images_base": "http://localhost:8888/storage/room-types/1/"
        }
    }
}
```

**Error Responses:**

404 Not Found:

```json
{
    "success": false,
    "message": "Room type not found"
}
```

400 Bad Request:

```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "locale": ["The locale field must be one of: en, vi"],
        "reviews_per_page": ["The reviews per page may not be greater than 50"]
    }
}
```

### POST /api/room-types/clear-cache

Xóa cache cho room type details.

**Parameters:**

-   `slug` (string, optional): Slug của room type cụ thể. Nếu không có sẽ xóa tất cả cache.

**Example Request:**

```bash
POST /api/room-types/clear-cache
Content-Type: application/json

{
  "slug": "deluxe"
}
```

**Response:**

```json
{
    "success": true,
    "message": "Cache cleared successfully"
}
```

## Implementation Details

### Database Tables Used

-   `room_types`: Thông tin cơ bản của room type
-   `room_type_image`: Hình ảnh của room type
-   `room_type_amenity`: Quan hệ many-to-many với amenities
-   `amenities`: Danh sách tiện ích
-   `booking`: Đặt phòng để tính availability và reviews
-   `reviews`: Đánh giá từ khách hàng
-   `room_price_history`: Lịch sử giá để tính min/max price

### Caching Strategy

-   Cache key: `room_type_detail_{slug}_{md5(filters)}`
-   TTL: 60 seconds
-   Automatic cache invalidation when related models change

### Related Rooms Algorithm

1. Tìm room types có chung amenities nhiều nhất
2. Sắp xếp theo độ tương đồng (shared amenities \* 10 + price proximity score)
3. Trả về top N results theo `related_limit`

### Performance Considerations

-   Eager loading relationships để tránh N+1 queries
-   Sử dụng database indexing trên các fields thường query
-   Pagination cho reviews để tránh load quá nhiều data
-   Cache kết quả để giảm database queries

### Security

-   Validation đầy đủ cho tất cả input parameters
-   Sanitization cho JSON output để tránh malformed UTF-8
-   Không expose sensitive data trong response
-   Rate limiting có thể áp dụng nếu cần

## Usage Examples

### Lấy thông tin cơ bản

```bash
curl -X GET "http://localhost:8888/api/room-types/deluxe"
```

### Lấy với reviews và related rooms

```bash
curl -X GET "http://localhost:8888/api/room-types/deluxe?include=reviews,related&reviews_per_page=20&related_limit=10"
```

### Lấy với price by date

```bash
curl -X GET "http://localhost:8888/api/room-types/deluxe?include=price_by_date&currency=USD"
```

### Xóa cache

```bash
curl -X POST "http://localhost:8888/api/room-types/clear-cache" \
  -H "Content-Type: application/json" \
  -d '{"slug": "deluxe"}'
```

## Testing

Sử dụng PHPUnit tests trong `tests/Feature/Api/RoomTypeDetailTest.php`:

```bash
php artisan test tests/Feature/Api/RoomTypeDetailTest.php
```

## Sample SQL Queries

### Related Rooms Query

```sql
SELECT rt.*,
       COUNT(rta2.amenity_id) as shared_amenities,
       ABS(rt.base_price - ?) as price_diff
FROM room_types rt
LEFT JOIN room_type_amenity rta2 ON rt.room_type_id = rta2.room_type_id
WHERE rt.room_type_id != ?
  AND rt.is_active = 1
  AND rta2.amenity_id IN (SELECT amenity_id FROM room_type_amenity WHERE room_type_id = ?)
GROUP BY rt.room_type_id
ORDER BY shared_amenities DESC, price_diff ASC
LIMIT ?
```

### Price History Query

```sql
SELECT date, adjusted_price
FROM room_price_history
WHERE room_type_id = ?
  AND date BETWEEN ? AND ?
ORDER BY date ASC
```
