# 🎓 HƯỚNG DẪN HỆ THỐNG THANH TOÁN - ĐỒNG ÁN TỐT NGHIỆP

## 📋 **MỤC TIÊU DOCUMENT**

- ✅ Hiểu **LOGIC** và **QUY TRÌNH** thanh toán
- ✅ Hiểu **CẤU TRÚC CODE** Frontend & Backend
- ✅ Hiểu **API FLOW** và **DATABASE**
- ✅ Có thể **DEMO** và **GIẢI THÍCH** cho giảng viên
- ✅ Có thể **MỞ RỘNG** tính năng sau này

---

## 🏗️ **TỔNG QUAN KIẾN TRÚC SYSTEM**

```
┌─────────────────┐    API CALLS    ┌─────────────────┐
│   FRONTEND      │ ◄─────────────► │    BACKEND      │
│   (React/TS)    │                 │   (Laravel/PHP) │
├─────────────────┤                 ├─────────────────┤
│ • Payment.tsx   │                 │ • PaymentController│
│ • VietQR        │                 │ • Routes API    │
│ • Auto Polling  │                 │ • Database      │
└─────────────────┘                 └─────────────────┘
         ▲                                   ▲
         │                                   │
┌─────────────────┐                 ┌─────────────────┐
│    CUSTOMER     │                 │    ADMIN        │
│                 │                 │                 │
│ • Scan QR       │                 │ • Check Bank    │
│ • Transfer $    │                 │ • Confirm       │
└─────────────────┘                 └─────────────────┘
```

---

## 🔄 **QUY TRÌNH THANH TOÁN CHI TIẾT**

### **BƯỚC 1: Khách hàng đặt phòng**

```typescript
// File: src/pages/Payment.tsx
const handleSubmit = async (values: any) => {
  // 1. Gọi API tạo booking
  await createBooking(values);

  // 2. Chuyển sang bước thanh toán
  setCurrentStep(1);
};
```

**🔍 Chi tiết:**

- Form validation (họ tên, email, phone)
- Tạo booking code: `LAVISH12345678`
- Gửi data sang Backend qua API

---

### **BƯỚC 2: Generate QR Code**

```typescript
// File: src/pages/Payment.tsx
const generateVietQRUrl = (amount: number, content: string) => {
  const baseUrl = "https://img.vietqr.io/image";
  const imagePath = `MB-0335920306-compact2.png`;

  const params = new URLSearchParams({
    amount: amount.toString(),
    addInfo: encodeURIComponent(content),
    accountName: encodeURIComponent("NGUYEN VAN QUYEN"),
  });

  return `${baseUrl}/${imagePath}?${params.toString()}`;
};
```

**🔍 Chi tiết:**

- Sử dụng VietQR API (miễn phí)
- Tạo QR với thông tin: STK, số tiền, nội dung
- Nội dung: `LAVISH + BookingCode`

---

### **BƯỚC 3: Auto Check Payment**

```typescript
// File: src/pages/Payment.tsx
useEffect(() => {
  if (currentStep === 1 && selectedPaymentMethod === "vietqr") {
    // Check ngay lập tức
    checkPaymentStatus();

    // Sau đó check mỗi 10 giây
    const interval = setInterval(checkPaymentStatus, 10000);

    return () => clearInterval(interval);
  }
}, [currentStep, selectedPaymentMethod, bookingCode]);
```

**🔍 Chi tiết:**

- Frontend **POLLING** API mỗi 10 giây
- Kiểm tra `payment_status` trong database
- Nếu status = `confirmed` → Chuyển sang bước hoàn thành

---

### **BƯỚC 4: Admin Confirm**

```php
// File: app/Http/Controllers/PaymentController.php
public function adminConfirmPayment($bookingCode) {
    $updated = DB::table('bookings')
        ->where('booking_code', $bookingCode)
        ->where('payment_status', 'pending')
        ->update([
            'payment_status' => 'confirmed',
            'payment_confirmed_at' => now()
        ]);

    return response()->json(['success' => true]);
}
```

**🔍 Chi tiết:**

- Admin check Internet Banking
- Thấy tiền về với nội dung đúng
- Click "Xác nhận" → Update database
- Frontend tự động nhận được thông báo

---

## 📁 **CẤU TRÚC FILE VÀ CHỨC NĂNG**

### **🎯 FRONTEND (React/TypeScript)**

#### **1. Payment.tsx - Trang thanh toán chính**

```typescript
const Payment: React.FC = () => {
  // ===== STATE MANAGEMENT =====
  const [currentStep, setCurrentStep] = useState(0);
  const [bookingCode, setBookingCode] = useState("");
  const [paymentStatus, setPaymentStatus] = useState("pending");

  // ===== API CALLS =====
  const createBooking = async (customerData) => {
    // Tạo booking mới
  };

  const checkPaymentStatus = async () => {
    // Check trạng thái thanh toán
  };

  // ===== UI COMPONENTS =====
  const renderPaymentStep = () => {
    // Hiển thị QR code và thông tin chuyển khoản
  };
};
```

**📌 Các chức năng chính:**

- ✅ **Step 0:** Form thông tin khách hàng
- ✅ **Step 1:** Hiển thị QR + auto check payment
- ✅ **Step 2:** Thông báo hoàn thành

#### **2. VietQR Integration**

```typescript
// Cấu hình VietQR
const VIETQR_CONFIG = {
  bankId: "MB", // MB Bank
  accountNo: "0335920306", // Số tài khoản
  accountName: "NGUYEN VAN QUYEN",
};

// Generate QR URL
const qrUrl = generateVietQRUrl(totalAmount, `LAVISH ${bookingCode}`);
```

---

### **🎯 BACKEND (Laravel/PHP)**

#### **1. PaymentController.php - API Controller**

```php
class PaymentController extends Controller {
    // 🔹 Tạo booking mới
    public function createBooking(Request $request) {
        // Validate input → Insert vào database
    }

    // 🔹 Check payment status (cho Frontend polling)
    public function checkPaymentStatus($bookingCode) {
        // Query database → Return status
    }

    // 🔹 Admin confirm payment
    public function adminConfirmPayment($bookingCode) {
        // Update status từ pending → confirmed
    }

    // 🔹 Lấy danh sách booking chờ thanh toán
    public function getPendingPayments() {
        // Return list booking có status = pending
    }
}
```

#### **2. Database Schema**

```sql
-- Table: bookings
CREATE TABLE bookings (
    id BIGINT PRIMARY KEY,
    booking_code VARCHAR(255) UNIQUE,     -- LAVISH12345678
    customer_name VARCHAR(255),
    customer_email VARCHAR(255),
    customer_phone VARCHAR(255),
    rooms_data JSON,                      -- Thông tin phòng đã chọn
    total_amount DECIMAL(15,2),
    payment_method ENUM('vietqr', 'pay_at_hotel'),
    payment_status ENUM('pending', 'confirmed', 'failed'),
    payment_confirmed_at TIMESTAMP NULL,
    check_in DATE,
    check_out DATE,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### **3. API Routes**

```php
// File: routes/api.php
Route::prefix('payment')->group(function () {
    Route::post('/create-booking', [PaymentController::class, 'createBooking']);
    Route::get('/status/{bookingCode}', [PaymentController::class, 'checkPaymentStatus']);
    Route::post('/admin/confirm/{bookingCode}', [PaymentController::class, 'adminConfirmPayment']);
    Route::get('/admin/pending', [PaymentController::class, 'getPendingPayments']);
});
```

---

### **🎯 ADMIN PANEL (Laravel Blade)**

#### **1. payment.blade.php - Giao diện admin**

```php
<!-- File: resources/views/admin/payment.blade.php -->
<script>
function adminPanel() {
    return {
        bookings: [],

        // Lấy danh sách booking
        async fetchBookings() {
            const response = await fetch('/api/payment/admin/pending');
            this.bookings = response.data;
        },

        // Xác nhận thanh toán
        async confirmPayment(bookingCode) {
            await fetch(`/api/payment/admin/confirm/${bookingCode}`, {
                method: 'POST'
            });
            this.fetchBookings(); // Refresh
        }
    }
}
</script>
```

---

## 🔄 **API FLOW DIAGRAM**

```
🏠 CUSTOMER SIDE                    🖥️  BACKEND API                   👨‍💼 ADMIN SIDE

┌─────────────────┐                ┌─────────────────┐               ┌─────────────────┐
│ 1. Fill Form    │───POST────────►│ /create-booking │               │                 │
│    Submit       │                │                 │               │                 │
└─────────────────┘                └─────────────────┘               │                 │
                                            │                         │                 │
                                            ▼                         │                 │
┌─────────────────┐                ┌─────────────────┐               │                 │
│ 2. Show QR      │                │ INSERT into     │               │                 │
│    Display Info │                │ bookings table  │               │                 │
└─────────────────┘                └─────────────────┘               │                 │
         │                                                            │                 │
         ▼                                                            │                 │
┌─────────────────┐                ┌─────────────────┐               │                 │
│ 3. Auto Poll    │◄──GET 10s─────│ /status/{code}  │               │                 │
│    Check Status │                │                 │               │                 │
└─────────────────┘                └─────────────────┘               │                 │
         │                                   │                       │                 │
         │            ┌─────────────────────────────────────────────┐ │                 │
         │            │         WAITING...                          │ │                 │
         │            │    Status = 'pending'                       │ │                 │
         │            └─────────────────────────────────────────────┘ │                 │
         │                                   │                       │                 │
         │                                   ▼                       │                 │
         │                          ┌─────────────────┐               │ 4. Check Bank   │
         │                          │ Admin checks    │◄──────────────│    See Money    │
         │                          │ Internet Banking│               │    Transfer     │
         │                          └─────────────────┘               │                 │
         │                                   │                       │                 │
         │                                   ▼                       │                 │
         │                          ┌─────────────────┐               │ 5. Click        │
         │                          │ Admin clicks    │◄──────────────│   "Confirm"     │
         │                          │ "Confirm"       │               │                 │
         │                          └─────────────────┘               └─────────────────┘
         │                                   │
         │                                   ▼
         │                          ┌─────────────────┐
         │                          │ POST /admin/    │
         │                          │ confirm/{code}  │
         │                          └─────────────────┘
         │                                   │
         │                                   ▼
         │                          ┌─────────────────┐
         │                          │ UPDATE status   │
         │                          │ = 'confirmed'   │
         │                          └─────────────────┘
         │                                   │
         ▼                                   ▼
┌─────────────────┐                ┌─────────────────┐
│ 6. Auto Detect  │◄──GET─────────│ /status/{code}  │
│    Status =     │                │ Return:         │
│   'confirmed'   │                │ 'confirmed'     │
└─────────────────┘                └─────────────────┘
         │
         ▼
┌─────────────────┐
│ 7. Show Success │
│    "Completed!" │
└─────────────────┘
```

---

## 📊 **DATABASE INTERACTIONS**

### **1. Tạo Booking**

```sql
-- Khi khách submit form
INSERT INTO bookings (
    booking_code,
    customer_name,
    customer_email,
    customer_phone,
    rooms_data,
    total_amount,
    payment_method,
    payment_status,    -- 'pending'
    check_in,
    check_out,
    created_at
) VALUES (...);
```

### **2. Check Status**

```sql
-- Frontend polling mỗi 10s
SELECT payment_status, payment_confirmed_at
FROM bookings
WHERE booking_code = 'LAVISH12345678';
```

### **3. Admin Confirm**

```sql
-- Admin click xác nhận
UPDATE bookings
SET payment_status = 'confirmed',
    payment_confirmed_at = NOW()
WHERE booking_code = 'LAVISH12345678'
AND payment_status = 'pending';
```

### **4. Admin View Pending**

```sql
-- Admin xem danh sách chờ
SELECT * FROM bookings
WHERE payment_status = 'pending'
AND payment_method = 'vietqr'
ORDER BY created_at DESC;
```

---

## 🎯 **DEMO SCENARIO CHO GIẢNG VIÊN**

### **📱 Phần 1: Customer Journey**

1. **Mở trang payment:** `http://localhost:3000/payment`
2. **Nhập thông tin:** Họ tên, email, phone
3. **Submit form:** → Chuyển sang bước 2
4. **Hiển thị QR:** Khách quét và chuyển khoản
5. **Auto polling:** Màn hình hiển thị "Đang chờ xác nhận..."

### **💻 Phần 2: Admin Process**

1. **Mở admin panel:** `http://localhost:8000/admin/payment`
2. **Xem booking list:** Hiển thị booking vừa tạo
3. **Check internet banking:** Thấy tiền về với nội dung `LAVISH XXXXX`
4. **Click "Xác nhận":** Status chuyển từ pending → confirmed

### **✅ Phần 3: Auto Complete**

1. **Frontend tự động detect:** Status = confirmed
2. **Chuyển sang step 3:** Hiển thị "Đặt phòng thành công!"
3. **Show booking info:** Mã booking, thông tin chi tiết

---

## 💡 **ĐIỂM MẠNH CỦA GIẢI PHÁP**

### **🎓 Phù hợp đồ án tốt nghiệp:**

- ✅ **Hoàn toàn miễn phí** - Không tốn API key
- ✅ **Logic đơn giản** - Dễ hiểu, dễ giải thích
- ✅ **Demo thực tế** - Có thể chuyển tiền thật
- ✅ **Bảo mật cao** - Admin manual confirm

### **🔧 Kỹ thuật sử dụng:**

- ✅ **Frontend:** React, TypeScript, Ant Design
- ✅ **Backend:** Laravel, PHP, MySQL
- ✅ **API:** RESTful, JSON response
- ✅ **Real-time:** Polling mechanism
- ✅ **QR Code:** VietQR integration

### **📈 Có thể mở rộng:**

- 🔮 **Webhook** từ ngân hàng (tự động hóa)
- 🔮 **Bank API** integration (MBBank, VCB...)
- 🔮 **WebSocket** cho real-time notification
- 🔮 **Email/SMS** notification

---

### **3. Access Points:**

- **Customer Payment:** `http://localhost:3000/payment`
- **Admin Panel:** `http://localhost:8000/admin/payment`
- **API Testing:** `http://localhost:8000/api/payment/admin/pending`

---
