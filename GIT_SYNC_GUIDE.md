# Git Sync Scripts - Hướng dẫn sử dụng

## 📋 Mô tả

Các script này giúp bạn và bạn team dễ dàng đồng bộ code trên nhánh DEV.

## 🛠️ Các script có sẵn:

### 1. Pull Scripts (Lấy code mới nhất)

- **pull-dev.sh** - Script cho Linux/Mac
- **pull-dev.bat** - Script cho Windows

### 2. Push Scripts (Đẩy code lên)

- **push-dev.sh** - Script cho Linux/Mac
- **push-dev.bat** - Script cho Windows

### 3. Auto-sync Script

- **auto-sync.sh** - Tự động đồng bộ mỗi 30 giây

## 🚀 Cách sử dụng:

### Trên Windows:

```bash
# Lấy code mới nhất
./pull-dev.bat

# Đẩy code lên (với message mặc định)
./push-dev.bat

# Đẩy code lên với message tùy chỉnh
./push-dev.bat "Fix bug login"
```

### Trên Linux/Mac:

```bash
# Cho phép thực thi script
chmod +x *.sh

# Lấy code mới nhất
./pull-dev.sh

# Đẩy code lên (với message mặc định)
./push-dev.sh

# Đẩy code lên với message tùy chỉnh
./push-dev.sh "Add new feature"

# Chạy auto-sync (tự động đồng bộ)
./auto-sync.sh
```

## 💡 Workflow khuyến nghị:

### Khi bắt đầu làm việc:

1. Chạy `pull-dev` để lấy code mới nhất
2. Code tính năng của bạn
3. Chạy `push-dev` để đẩy code lên

### Trong quá trình code:

- Chạy `pull-dev` định kỳ để cập nhật code từ bạn team
- Chạy `push-dev` khi hoàn thành một phần nhỏ

### Nếu muốn tự động:

- Chạy `auto-sync.sh` để tự động pull/push mỗi 30 giây

## ⚠️ Lưu ý quan trọng:

1. **Backup code**: Luôn commit hoặc stash code trước khi pull
2. **Xử lý conflict**: Nếu có conflict, script sẽ dừng để bạn giải quyết
3. **Communication**: Thông báo với team khi push những thay đổi lớn
4. **Branch**: Script tự động chuyển về nhánh DEV

## 🔧 Troubleshooting:

### Nếu gặp permission denied (Linux/Mac):

```bash
chmod +x *.sh
```

### Nếu có conflict:

1. Script sẽ dừng lại
2. Giải quyết conflict bằng tay
3. Chạy lại script

### Nếu push bị từ chối:

1. Chạy `pull-dev` trước
2. Giải quyết conflict (nếu có)
3. Chạy lại `push-dev`

## 📞 Liên hệ:Quyenjpn@gmail.com

Nếu có vấn đề gì, hãy thông báo trong team chat!
