# 🔧 Port Configuration Update - BE sử dụng port 8888

## ✅ Đã cập nhật các file sau:

### Backend (Laravel):

- `lavishstay-backend/.env` - APP_URL=http://localhost:8888
- `lavishstay-backend/config/cors.php` - Thêm localhost:5173 cho FE
- `lavishstay-backend/config/sanctum.php` - Cập nhật stateful domains
- `start_backend.bat` - Sử dụng port 8888
- `start-dev.sh` - Cập nhật tất cả reference tới port 8888
- `start-dev.ps1` - Cập nhật tất cả reference tới port 8888

### Frontend (React/Vite):

- `lavishstay-frontend/.env` - VITE_API_URL=http://localhost:8888/api
- `lavishstay-frontend/src/utils/api.ts` - Default baseURL port 8888
- `lavishstay-frontend/src/services/bookingAntiSpamService.ts` - API calls port 8888
- `lavishstay-frontend/src/pages/AdminPayment.tsx` - API_BASE_URL port 8888
- `lavishstay-frontend/src/pages/Payment.tsx` - API_BASE_URL port 8888
- `lavishstay-frontend/src/mirage/server.ts` - Passthrough port 8888

### Documentation:

- `API_GUIDE.md` - Cập nhật environment variables

## 🚀 Cách chạy với port mới:

### Chạy Backend (Port 8888):

```bash
cd lavishstay-backend
php artisan serve --port=8888
```

### Chạy Frontend (Port 3000 hoặc 5173):

```bash
cd lavishstay-frontend
npm run dev
```

### Hoặc sử dụng scripts có sẵn:

```bash
# Windows
./start_backend.bat
./start_frontend.bat

# Cross-platform
./start-dev.sh
./start-dev.ps1
```

## 🔗 URL mới:

- **Backend API**: http://localhost:8888/api
- **Frontend**: http://localhost:3000 hoặc http://localhost:5173
- **Laravel Admin**: http://localhost:8888

## ⚡ Quick Commands:

### Pull code mới nhất từ team:

```bash
./pull-dev.bat    # Windows
./pull-dev.sh     # Linux/Mac
```

### Push code sau khi dev:

```bash
./push-dev.bat "Feature description"    # Windows
./push-dev.sh "Feature description"     # Linux/Mac
```

### Auto-sync liên tục:

```bash
./auto-sync.sh    # Tự động pull/push mỗi 30 giây
```

## 🎯 Team Workflow:

1. **Sáng**: `pull-dev` để lấy code mới từ bạn team
2. **Coding**: BE port 8888, FE port 3000/5173
3. **Trong ngày**: `push-dev` thường xuyên để đồng bộ
4. **Cuối ngày**: `push-dev` trước khi tắt máy

Tất cả đã được cấu hình sẵn! 🎉
