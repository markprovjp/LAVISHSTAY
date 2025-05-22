<p align="center"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></p>

# LavishStay - Backend

LavishStay là một ứng dụng đặt phòng khách sạn được xây dựng bằng Laravel (Backend) và React (Frontend). Dự án này được phát triển cho khóa học PRO224 - SUMMER2025.

## Yêu Cầu Hệ Thống

Để chạy ứng dụng, bạn cần cài đặt các phần mềm sau:

- PHP >= 8.2
- Composer
- XAMPP 


## Cài Đặt

### Phương pháp 1: Sử dụng script tự động

Dự án đã cung cấp một script PowerShell để thiết lập và chạy cả Backend và Frontend:

1. Mở PowerShell và điều hướng đến thư mục gốc của dự án
2. Chạy lệnh:
```powershell
./start-dev.ps1
```
3. Chọn tùy chọn 2 (Backend) hoặc 3 (Cả hai) từ menu

### Phương pháp 2: Cài đặt thủ công

#### Bước 1: Clone dự án (nếu chưa có)

```bash
git clone <repository-url> LavishStay
cd LavishStay/lavishstay-backend
```

#### Bước 2: Cài đặt các dependency

```bash
composer install
```

#### Bước 3: Thiết lập môi trường

```bash
# Tạo file .env từ mẫu
cp .env.example .env

# Tạo khóa ứng dụng
php artisan key:generate
```

#### Bước 4: Cấu hình cơ sở dữ liệu

Mặc định, dự án sử dụng SQLite. File database.sqlite đã được tạo sẵn trong thư mục `/database`. Nếu bạn muốn sử dụng MySQL, hãy chỉnh sửa các thông số sau trong file `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lavishstay
DB_USERNAME=root
DB_PASSWORD=
```

#### Bước 5: Chạy migration và seeder

```bash
# Chạy migration để tạo các bảng trong database
php artisan migrate

# Chạy seeder để tạo dữ liệu mẫu (nếu cần)
php artisan db:seed
```

## Chạy Ứng Dụng

### Khởi động server Laravel

```bash
php artisan serve
```

Server sẽ chạy tại địa chỉ: [http://localhost:8000](http://localhost:8000)

### Chạy Queue Worker (nếu cần)

```bash
php artisan queue:work
```

### Chạy Scheduler (nếu cần)

```bash
php artisan schedule:work
```

## Cấu Trúc Thư Mục

- `app/` - Chứa các models, controllers và các thành phần core của ứng dụng
- `bootstrap/` - Chứa các files khởi động ứng dụng
- `config/` - Chứa tất cả các file cấu hình của ứng dụng
- `database/` - Chứa migrations, seeders và factories
- `public/` - Thư mục gốc web, chứa index.php và các assets
- `resources/` - Chứa views, assets chưa biên dịch
- `routes/` - Chứa các định nghĩa route
- `storage/` - Chứa logs, sesions, cache
- `tests/` - Chứa các files test

## Các Lệnh Hữu Ích

```bash
# Xóa cache
php artisan cache:clear

# Xóa cache route
php artisan route:clear

# Xóa cache config
php artisan config:clear

# Liệt kê tất cả routes
php artisan route:list

# Tạo controller mới
php artisan make:controller TenController

# Tạo model mới với migration
php artisan make:model TenModel -m

# Chạy test
php artisan test
```

## Liên Kết Frontend

Frontend của dự án được xây dựng bằng React và có thể được khởi động riêng biệt:

```bash
cd ../lavishstay-frontend
npm install
npm run dev
```

Frontend sẽ chạy tại địa chỉ: [http://localhost:5173](http://localhost:5173)

## Liên Hệ

Nếu bạn có bất kỳ câu hỏi hay góp ý nào, vui lòng liên hệ:

- Email: [email@example.com](mailto:email@example.com)
- GitHub: [GitHub Repository](https://github.com/yourusername/lavishstay)

## License

Dự án này được cấp phép theo [MIT license](https://opensource.org/licenses/MIT).
