# HƯỚNG DẪN SỬ DỤNG GIT & GITHUB CHO DỰ ÁN LAVISHSTAY

## PHẦN 1: CÀI ĐẶT & THIẾT LẬP BAN ĐẦU

### 1. Cài đặt Git
- Windows: Tải và cài đặt từ [git-scm.com](https://git-scm.com/download/win)
- macOS: Chạy `brew install git` (nếu đã cài Homebrew) hoặc tải từ [git-scm.com](https://git-scm.com/download/mac)
- Linux: Chạy `sudo apt-get install git` (Ubuntu/Debian) hoặc `sudo yum install git` (CentOS/Fedora)

### 2. Cấu hình Git
```bash
git config --global user.name "Tên của bạn"
git config --global user.email "email@example.com"
```

### 3. Tạo tài khoản GitHub
- Đăng ký tại [github.com](https://github.com)

## PHẦN 2: LÀM VIỆC VỚI REPOSITORY CÓ SẴN

### 1. Clone repository (tải code về máy lần đầu tiên)
```bash
git clone https://github.com/markprovjp/LAVISHSTAY.git
cd LAVISHSTAY
```

### 2. Kiểm tra các nhánh hiện có và chuyển nhánh
```bash
# Xem tất cả các nhánh
git branch -a

# Chuyển sang nhánh QUYEN (frontend)
git checkout QUYEN
```

### 3. Cập nhật code mới nhất từ GitHub về máy
```bash
# Lấy code mới nhất từ nhánh QUYEN
git pull origin QUYEN
```

## PHẦN 3: PUSH CODE LÊN GITHUB

### 1. Quy trình push code cơ bản
```bash
# Kiểm tra những thay đổi của bạn
git status

# Thêm tất cả các file đã thay đổi vào staging
git add .

# Hoặc chỉ thêm các file cụ thể
git add src/components/tên-file-của-bạn.tsx

# Tạo commit với mô tả
git commit -m "Mô tả ngắn gọn về những thay đổi của bạn"

# Đẩy code lên GitHub (nhánh QUYEN)
git push origin QUYEN
```

### 2. Xử lý khi có xung đột
```bash
# Nếu không push được vì có người khác đã cập nhật code
git pull origin QUYEN
# Giải quyết xung đột nếu có rồi commit lại
git add .
git commit -m "Fix conflicts"
git push origin QUYEN
```

## PHẦN 4: CÁC LỆNH THƯỜNG DÙNG

### 1. Kiểm tra trạng thái
```bash
git status
```

### 2. Xem lịch sử commit
```bash
git log
git log --oneline  # Rút gọn
```

### 3. Quay lại commit trước
```bash
git reset --soft HEAD~1  # Giữ thay đổi nhưng hủy commit
git reset --hard HEAD~1  # Xóa cả thay đổi và commit (NGUY HIỂM)
```

### 4. Hủy bỏ thay đổi trên một file
```bash
git checkout -- tên-file.txt
```

### 5. Tạo và chuyển sang nhánh mới
```bash
git checkout -b tên-nhánh-mới
```

## PHẦN 5: MỘT SỐ TÌNH HUỐNG THƯỜNG GẶP

### 1. Code bị mất sau khi push
- Kiểm tra xem bạn đang ở nhánh nào: `git branch`
- Chuyển về nhánh QUYEN: `git checkout QUYEN`
- Kiểm tra code có thay đổi không: `git status`
- Khôi phục code từ GitHub: `git pull origin QUYEN`

### 2. Quên commit
- Nếu đã đẩy code lên GitHub nhưng quên commit một số file:
```bash
git add tên-file-bị-quên
git commit -m "Thêm file bị quên"
git push origin QUYEN
```

### 3. Tạo Pull Request (Merge vào main)
- Truy cập repository trên GitHub
- Chọn "Pull requests" > "New pull request"
- Chọn "base: main" và "compare: QUYEN"
- Nhấn "Create pull request"
- Điền tiêu đề và mô tả, nhấn "Create pull request"
- Chọn "Merge pull request" (nếu không có xung đột)

## LƯU Ý QUAN TRỌNG
1. **LUÔN PULL TRƯỚC KHI PUSH**: Đảm bảo bạn có code mới nhất trước khi đẩy thay đổi
2. **ĐẶT TÊN COMMIT CÓ Ý NGHĨA**: Mô tả rõ những gì bạn đã làm
3. **THƯỜNG XUYÊN COMMIT**: Commit nhỏ, thường xuyên thay vì một commit lớn
4. **KIỂM TRA NHÁNH**: Luôn đảm bảo bạn đang làm việc trên đúng nhánh
5. **SAO LƯU CODE**: Sao chép code quan trọng ra nơi khác trước khi thực hiện các thao tác nguy hiểm

## TRÌNH TỰ LÀM VIỆC HÀNG NGÀY

1. Kiểm tra nhánh: `git branch`
2. Chuyển sang nhánh QUYEN: `git checkout QUYEN`
3. Cập nhật code: `git pull origin QUYEN`
4. Làm việc, thay đổi code...
5. Kiểm tra thay đổi: `git status`
6. Thêm, commit và push:
```bash
git add .
git commit -m "Mô tả thay đổi"
git push origin QUYEN
```

## PHỤ LỤC: CÁC LỆNH GIT THÔNG DỤNG
![Git Cheat Sheet](https://education.github.com/git-cheat-sheet-education.pdf)

---
Nếu còn thắc mắc, hãy liên hệ [Tên giảng viên/người quản lý dự án]
