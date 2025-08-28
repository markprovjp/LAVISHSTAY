# Test API Endpoints với Postman

## 1. Contact Form API

### Endpoint: POST `/api/contact`

**Headers:**

```
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**

```json
{
  "name": "Nguyễn Văn A",
  "email": "test@example.com",
  "phone": "0123456789",
  "subject": "Văn phòng Hà Nội",
  "message": "Tôi muốn biết thông tin về dịch vụ khách sạn và giá cả cho tháng 12."
}
```

**Response Success (200):**

```json
{
  "success": true,
  "message": "Đã nhận yêu cầu. Kiểm tra email để xác nhận."
}
```

**Response Error (422):**

```json
{
  "success": false,
  "message": "Dữ liệu không hợp lệ",
  "errors": {
    "email": ["Email không hợp lệ"]
  }
}
```

---

## 2. Newsletter Subscription API

### Endpoint: POST `/api/newsletter`

**Headers:**

```
Content-Type: application/json
Accept: application/json
```

**Body (JSON):**

```json
{
  "email": "subscriber@example.com"
}
```

**Response Success (200):**

```json
{
  "success": true,
  "message": "Đăng ký thành công! Kiểm tra email để nhận mã giảm giá.",
  "discount_code": "LAVISH20-123456"
}
```

**Response Error (422):**

```json
{
  "success": false,
  "message": "Email không hợp lệ",
  "errors": {
    "email": ["Email không hợp lệ"]
  }
}
```

---

## 3. Newsletter Unsubscribe API

### Endpoint: GET `/api/newsletter/unsubscribe`

**Query Parameters:**

```
email=subscriber@example.com&token=abc123token
```

**Response Success (200):**

```json
{
  "success": true,
  "message": "Đã hủy đăng ký newsletter thành công"
}
```

---

## 4. Test Commands

### Chạy unit tests:

```bash
cd /c/Users/ADMIN/DEV2/LAVISHSTAY/lavishstay-backend
php artisan test tests/Feature/ContactTest.php
php artisan test tests/Feature/NewsletterTest.php
```

### Kiểm tra queue jobs:

```bash
php artisan queue:work --timeout=60
```

### Fake email testing với Mailtrap/Log:

```bash
# Trong .env thêm:
MAIL_MAILER=log
# Hoặc
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
```

---

## 5. Frontend Integration

### ContactForm.tsx - Update handleSubmit:

```typescript
const handleSubmit = async (values: any) => {
  setLoading(true);

  try {
    const response = await fetch("/api/contact", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(values),
    });

    const data = await response.json();

    if (data.success) {
      message.success(data.message);
      form.resetFields();
    } else {
      message.error(data.message || "Có lỗi xảy ra");
    }
  } catch (error) {
    message.error("Không thể gửi yêu cầu. Vui lòng thử lại.");
  } finally {
    setLoading(false);
  }
};
```

### Newsletter.tsx - Update handleSubmit:

```typescript
const handleSubmit = async (values: any) => {
  setLoading(true);

  try {
    const response = await fetch("/api/newsletter", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(values),
    });

    const data = await response.json();

    if (data.success) {
      message.success(`${data.message} Mã giảm giá: ${data.discount_code}`);
      form.resetFields();
    } else {
      message.error(data.message || "Có lỗi xảy ra");
    }
  } catch (error) {
    message.error("Không thể đăng ký. Vui lòng thử lại.");
  } finally {
    setLoading(false);
  }
};
```

---

## 6. Cấu hình Mail Environment

### Development (.env):

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@lavishstay.com
MAIL_FROM_NAME="LavishStay Thanh Hóa"
```

### Production (.env):

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=quyenjpn@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=quyenjpn@gmail.com
MAIL_FROM_NAME="LavishStay Thanh Hóa"

# Queue Configuration
QUEUE_CONNECTION=database
```

### Queue Setup:

```bash
php artisan queue:table
php artisan migrate
php artisan queue:work
```
