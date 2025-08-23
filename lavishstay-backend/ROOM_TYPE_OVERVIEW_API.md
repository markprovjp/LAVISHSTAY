# Room Types Overview API Documentation

## Endpoint

```
GET /api/room-types/overview
```

## Description

Trả về danh sách các loại phòng với thông tin tổng quan, tối ưu cho UI homepage. API này được thiết kế để hiển thị thông tin loại phòng một cách nhẹ và nhanh chóng.

## Query Parameters

| Parameter    | Type    | Required | Default | Description                                                     |
| ------------ | ------- | -------- | ------- | --------------------------------------------------------------- |
| `featured`   | boolean | No       | false   | Lọc loại phòng nổi bật (rating >= 4 hoặc premium rooms)         |
| `popular`    | boolean | No       | false   | Lọc loại phòng phổ biến (dựa trên số booking trong 30 ngày qua) |
| `min_price`  | number  | No       | -       | Giá tối thiểu (VND)                                             |
| `max_price`  | number  | No       | -       | Giá tối đa (VND)                                                |
| `limit`      | integer | No       | 12      | Số lượng kết quả trên mỗi trang (max: 50)                       |
| `page`       | integer | No       | 1       | Trang hiện tại                                                  |
| `locale`     | string  | No       | vi      | Ngôn ngữ (en, vi)                                               |
| `currency`   | string  | No       | VND     | Đơn vị tiền tệ (VND, USD)                                       |
| `check_date` | string  | No       | today   | Ngày kiểm tra tình trạng phòng (YYYY-MM-DD)                     |

## Response Structure

### Success Response (200)

```json
{
    "success": true,
    "meta": {
        "total": 123,
        "page": 1,
        "per_page": 12,
        "total_pages": 11
    },
    "data": [
        {
            "room_type_id": 12,
            "slug": "deluxe-double",
            "title": "Deluxe Double",
            "short_description": "Phòng rộng 30m², cửa sổ hướng phố",
            "thumbnail": "https://example.com/storage/xxx.jpg",
            "gallery": [
                "https://example.com/storage/1.jpg",
                "https://example.com/storage/2.jpg",
                "https://example.com/storage/3.jpg"
            ],
            "starting_price": 450000,
            "price_unit": "VND",
            "max_adults": 2,
            "max_children": 1,
            "total_rooms": 8,
            "available_rooms": 3,
            "avg_rating": 4.6,
            "review_count": 23,
            "amenities": [
                {
                    "id": 1,
                    "name": "WiFi miễn phí",
                    "icon": "wifi"
                }
            ],
            "tags": ["sea-view", "breakfast-included"],
            "badges": ["featured", "best-seller"],
            "slug_url": "/room-types/deluxe-double"
        }
    ]
}
```

### Error Response (400)

```json
{
    "success": false,
    "message": "Invalid parameters",
    "errors": {
        "currency": ["The selected currency is invalid."]
    }
}
```

### Error Response (500)

```json
{
    "success": false,
    "message": "An error occurred while fetching room types",
    "error": "Internal server error"
}
```

## Data Fields Description

### Room Type Object

| Field               | Type          | Description                                  |
| ------------------- | ------------- | -------------------------------------------- |
| `room_type_id`      | integer       | ID loại phòng                                |
| `slug`              | string        | Slug URL-friendly                            |
| `title`             | string        | Tên loại phòng                               |
| `short_description` | string        | Mô tả ngắn (max ~120 ký tự)                  |
| `thumbnail`         | string        | URL ảnh đại diện                             |
| `gallery`           | array         | Danh sách URL ảnh (tối đa 3)                 |
| `starting_price`    | integer\|null | Giá khởi điểm (có thể null nếu không có giá) |
| `price_unit`        | string        | Đơn vị tiền tệ                               |
| `max_adults`        | integer       | Số người lớn tối đa                          |
| `max_children`      | integer       | Số trẻ em tối đa                             |
| `total_rooms`       | integer       | Tổng số phòng                                |
| `available_rooms`   | integer       | Số phòng có sẵn                              |
| `avg_rating`        | float         | Điểm đánh giá trung bình                     |
| `review_count`      | integer       | Số lượng đánh giá                            |
| `amenities`         | array         | Danh sách tiện ích (tối đa 5)                |
| `tags`              | array         | Các tag đặc trưng                            |
| `badges`            | array         | Các badge đặc biệt                           |
| `slug_url`          | string        | Đường dẫn chi tiết                           |

### Available Badges

-   `featured`: Phòng nổi bật
-   `best-seller`: Bán chạy nhất
-   `new`: Mới thêm
-   `price-unavailable`: Chưa có giá

### Available Tags

-   `sea-view`: Hướng biển
-   `suite`: Loại suite
-   `premium`: Cao cấp
-   `family-friendly`: Thân thiện gia đình

## Cache Management

### Clear Cache Endpoint

```
POST /api/room-types/overview/clear-cache
```

Xóa cache của API overview. Response:

```json
{
    "success": true,
    "message": "Room types overview cache cleared successfully"
}
```

## Examples

### Basic Request

```bash
curl -X GET "http://localhost:8888/api/room-types/overview"
```

### Filtered Request

```bash
curl -X GET "http://localhost:8888/api/room-types/overview?featured=true&limit=6&currency=USD"
```

### Price Range Filter

```bash
curl -X GET "http://localhost:8888/api/room-types/overview?min_price=500000&max_price=2000000"
```

### Pagination

```bash
curl -X GET "http://localhost:8888/api/room-types/overview?page=2&limit=10"
```

## Performance Features

1. **Caching**: API responses được cache trong 60 giây
2. **Eager Loading**: Tối ưu query với eager loading relationships
3. **Pagination**: Hỗ trợ phân trang để giảm tải
4. **Image Optimization**: Chỉ trả về 3 ảnh đầu tiên cho gallery
5. **Selective Fields**: Chỉ select các trường cần thiết

## Database Requirements

### Required Tables

-   `room_types`
-   `room_type_image`
-   `room_type_amenity`
-   `amenities`
-   `room`
-   `reviews`
-   `booking`
-   `room_price_history`

### Required Indexes (Recommended)

```sql
CREATE INDEX idx_room_type_id ON room(room_type_id);
CREATE INDEX idx_room_status ON room(status);
CREATE INDEX idx_room_price_history_room_type_date ON room_price_history(room_type_id, date);
CREATE INDEX idx_room_type_slug ON room_types(room_code);
```

## Error Handling

API sử dụng HTTP status codes chuẩn:

-   `200`: Success
-   `400`: Bad Request (validation errors)
-   `500`: Internal Server Error

Tất cả response đều có trường `success` để kiểm tra kết quả.

## Integration Notes

1. **Frontend**: Dùng cho homepage room type showcase
2. **Mobile**: Response JSON tối ưu cho mobile apps
3. **SEO**: Cung cấp `slug_url` cho SEO-friendly URLs
4. **Internationalization**: Hỗ trợ đa ngôn ngữ qua parameter `locale`
