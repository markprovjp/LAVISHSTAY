# LavishStay Frontend - Ứng Dụng Đặt Phòng Cao Cấp

## Tổng Quan Dự Án

**LavishStay** là nền tảng đặt phòng cao cấp được phát triển với mục tiêu mang đến trải nghiệm người dùng tuyệt vời và giao diện hiện đại. Dự án này là phần frontend của hệ thống LavishStay, được xây dựng bằng các công nghệ tiên tiến nhất.

![Logo LavishStay](./public/logo192.png)

## Công Nghệ Sử Dụng

### Các Công Nghệ Chính
- **Frontend Framework**: React 18 - Thư viện JavaScript phổ biến nhất để xây dựng giao diện người dùng
- **Ngôn Ngữ**: TypeScript - Mang lại tính an toàn về kiểu dữ liệu và hỗ trợ phát triển tốt hơn
- **Build Tool**: Vite - Công cụ build nhanh và hiệu quả, giúp thời gian phát triển nhanh hơn nhiều lần
- **UI Component Library**: Ant Design - Thư viện UI components đẹp và đa dạng
- **CSS Framework**: Tailwind CSS - Framework CSS tiện lợi cho phép styling nhanh chóng

### Quản Lý State và Data
- **State Management**: 
  - Redux Toolkit - Quản lý state toàn cục của ứng dụng
  - Zustand - Thư viện state management nhẹ và dễ sử dụng
- **API Client**: Axios - Thư viện HTTP client mạnh mẽ để giao tiếp với backend
- **Data Fetching**: React Query - Quản lý fetching, caching và đồng bộ hóa dữ liệu server
- **Routing**: React Router v7 - Thư viện routing mới nhất cho React

### Công Cụ Phát Triển
- **Linting**: ESLint - Kiểm tra lỗi code và đảm bảo chất lượng
- **Testing**: Vitest và React Testing Library - Công cụ test hiện đại
- **Type Checking**: TypeScript - Kiểm tra kiểu dữ liệu tĩnh

## Cấu Trúc Dự Án Chi Tiết

```
src/
  ├── assets/           # Tài nguyên tĩnh (hình ảnh, fonts, css)
  │   ├── css/          # Global CSS và fonts
  │   ├── fonts/        # Font files
  │   └── images/       # Hình ảnh
  │
  ├── components/       # Components React
  │   ├── layouts/      # Các layout khác nhau của ứng dụng
  │   │   ├── AuthLayout.tsx       # Layout cho trang đăng nhập/đăng ký
  │   │   ├── DashboardLayout.tsx  # Layout cho trang dashboard
  │   │   ├── DefaultLayout.tsx    # Layout mặc định
  │   │   ├── Footer.tsx           # Component footer chung
  │   │   ├── Header.tsx           # Component header với navigation
  │   │   └── MainLayout.tsx       # Layout chính
  │   │
  │   ├── ui/           # UI components tái sử dụng
  │   │   ├── Breadcrumb.tsx       # Component điều hướng breadcrumb
  │   │   ├── FeatureCard.tsx      # Card hiển thị tính năng
  │   │   ├── HeroBanner.tsx       # Banner hero section
  │   │   ├── HotelCard.tsx        # Card hiển thị khách sạn/phòng
  │   │   ├── LazyLoad.tsx         # Component lazy loading
  │   │   ├── StyledButton.tsx     # Button tùy chỉnh
  │   │   ├── StyledCard.tsx       # Card tùy chỉnh
  │   │   ├── StyledHeroBanner.tsx # Banner hero tùy chỉnh
  │   │   ├── StyledTitle.tsx      # Tiêu đề tùy chỉnh
  │   │   └── ThemeToggle.tsx      # Toggle chuyển đổi theme
  │   │
  │   ├── ContactForm.tsx    # Form liên hệ
  │   ├── CustomFooter.tsx   # Footer tùy chỉnh
  │   ├── FeatureCard.tsx    # Card hiển thị tính năng
  │   ├── HeroHeader.tsx     # Header hero section
  │   ├── HotelCard.tsx      # Card hiển thị khách sạn
  │   ├── Navbar.tsx         # Thanh điều hướng
  │   ├── Newsletter.tsx     # Form đăng ký newsletter
  │   ├── PageHeader.tsx     # Header của trang
  │   ├── SearchForm.tsx     # Form tìm kiếm
  │   ├── SectionHeader.tsx  # Header của section
  │   ├── Stats.tsx          # Hiển thị thống kê
  │   ├── Testimonial.tsx    # Hiển thị đánh giá khách hàng
  │   └── UserAvatar.tsx     # Avatar người dùng
  │
  ├── config/           # Cấu hình
  │   ├── axios.ts      # Cấu hình Axios cho API calls
  │   ├── constants.ts  # Các hằng số trong dự án
  │   ├── env.ts        # Quản lý biến môi trường
  │   ├── index.ts      # Export cấu hình
  │   └── theme.ts      # Cấu hình theme của ứng dụng
  │
  ├── hooks/            # Custom hooks
  │   ├── useApi.ts         # Hook quản lý API calls
  │   ├── useErrorRedirect.ts # Hook xử lý lỗi và chuyển hướng
  │   ├── useFormValidation.ts # Hook xác thực form
  │   ├── useThemeMode.ts   # Hook quản lý theme
  │   └── index.ts          # Export hooks
  │
  ├── pages/            # Các trang của ứng dụng
  │   ├── dashboard/    # Các trang dashboard
  │   ├── About.tsx     # Trang giới thiệu
  │   ├── ErrorPage.tsx # Trang hiển thị lỗi
  │   ├── Forbidden.tsx # Trang cấm truy cập (403)
  │   ├── Home.tsx      # Trang chủ
  │   ├── Login.tsx     # Trang đăng nhập
  │   ├── NotFound.tsx  # Trang không tìm thấy (404)
  │   ├── Register.tsx  # Trang đăng ký
  │   └── ServerError.tsx # Trang lỗi máy chủ (500)
  │
  ├── routes/           # Định nghĩa routes
  │   ├── PrivateRoute.tsx  # Route yêu cầu xác thực
  │   ├── PublicRoute.tsx   # Route công khai
  │   └── index.tsx         # Cấu hình routes chính
  │
  ├── services/         # Services API
  │   ├── authService.ts # Dịch vụ xác thực (login, register)
  │   ├── index.ts      # Export services
  │   └── userService.ts # Dịch vụ quản lý người dùng
  │
  ├── store/            # Quản lý state
  │   ├── slices/       # Redux slices
  │   │   ├── authSlice.ts  # State quản lý xác thực
  │   │   └── themeSlice.ts # State quản lý theme
  │   ├── index.ts      # Cấu hình Redux store
  │   └── useStore.ts   # Hook Zustand
  │
  ├── types/            # TypeScript types
  │   ├── index.ts      # Export types
  │   └── models.ts     # Định nghĩa models (User, Property, Booking...)
  │
  ├── utils/            # Các hàm tiện ích
  │   ├── api.ts        # Tiện ích API
  │   ├── helpers.ts    # Các hàm hỗ trợ
  │   ├── index.ts      # Export utilities
  │   └── SEO.tsx       # Component quản lý SEO
  │
  ├── App.tsx           # Component chính của ứng dụng
  ├── index.tsx         # Điểm vào ứng dụng React
  ├── styles.ts         # Global styles
  └── theme.ts          # Cấu hình theme tổng thể
```

## Chức Năng Chính Của Ứng Dụng

### Trang Công Khai
1. **Trang Chủ**
   - Hero banner với hình ảnh thu hút
   - Form tìm kiếm phòng/khách sạn
   - Hiển thị các khách sạn được đề xuất
   - Hiển thị tính năng nổi bật của nền tảng
   - Phần đánh giá từ khách hàng
   - Form đăng ký nhận bản tin

2. **Trang Giới Thiệu**
   - Thông tin về LavishStay
   - Lịch sử và sứ mệnh
   - Thống kê về hệ thống (số lượng khách sạn, khách hàng...)
   - Đội ngũ phát triển

3. **Trang Tìm Kiếm & Danh Sách Khách Sạn**
   - Bộ lọc tìm kiếm nâng cao
   - Hiển thị danh sách khách sạn theo tiêu chí
   - Sắp xếp theo giá, đánh giá, phổ biến
   - Hiển thị trên bản đồ

4. **Trang Chi Tiết Khách Sạn**
   - Thông tin chi tiết về khách sạn
   - Hình ảnh phòng và tiện nghi
   - Bản đồ vị trí
   - Đánh giá từ khách hàng
   - Hệ thống đặt phòng

### Khu Vực Người Dùng
1. **Đăng Nhập / Đăng Ký**
   - Đăng nhập bằng email/password
   - Đăng nhập qua mạng xã hội
   - Đăng ký tài khoản mới
   - Khôi phục mật khẩu

2. **Dashboard Người Dùng**
   - Trang tổng quan
   - Quản lý hồ sơ cá nhân
   - Lịch sử đặt phòng
   - Danh sách yêu thích
   - Thông báo

3. **Quản Lý Đặt Phòng**
   - Xem các đặt phòng hiện tại
   - Lịch sử đặt phòng
   - Hủy hoặc sửa đổi đặt phòng
   - Thanh toán

### Tính Năng Giao Diện
1. **Đa Ngôn Ngữ**
   - Hỗ trợ tiếng Việt và tiếng Anh
   - Dễ dàng mở rộng thêm ngôn ngữ

2. **Theme Sáng/Tối**
   - Chuyển đổi giữa chế độ sáng và tối
   - Tự động nhận diện chế độ hệ thống

3. **Responsive**
   - Tương thích với tất cả thiết bị
   - Tối ưu hóa cho di động, tablet và desktop

4. **Trải Nghiệm Người Dùng**
   - Animations mượt mà
   - Loading states
   - Error handling thân thiện

## Tích Hợp API

Dự án sử dụng REST API từ phần backend Laravel để giao tiếp. Dưới đây là các nhóm API chính:

1. **Authentication API**
   - Đăng nhập, đăng xuất
   - Đăng ký
   - Quên mật khẩu
   - Refresh token

2. **User API**
   - Xem và cập nhật thông tin cá nhân
   - Quản lý mật khẩu
   - Quản lý thông báo

3. **Property API**
   - Tìm kiếm khách sạn/phòng
   - Xem chi tiết khách sạn
   - Lấy danh sách đề xuất
   - Xếp hạng và đánh giá

4. **Booking API**
   - Tạo đặt phòng mới
   - Xem trạng thái đặt phòng
   - Hủy đặt phòng
   - Thanh toán

5. **Wishlist API**
   - Thêm/xóa khỏi danh sách yêu thích
   - Xem danh sách yêu thích

## Scripts

Các lệnh chính để phát triển và triển khai dự án:

```bash
# Khởi động môi trường phát triển
npm start
# hoặc
npm run dev

# Build phiên bản production
npm run build

# Xem trước phiên bản build
npm run preview

# Chạy tests
npm test

# Kiểm tra lỗi code
npm run lint
```

## Công Nghệ Nổi Bật

### State Management
- **Redux Toolkit**: Quản lý global state phức tạp như authentication, theme...
- **Zustand**: Quản lý state đơn giản như filters, UI states...

### Data Fetching
- **React Query**: Quản lý data fetching, caching, và synchronization với server
- **Axios Interceptors**: Xử lý authentication, refresh token và error handling

### UI/UX
- **Ant Design**: Cung cấp các components phức tạp như DatePicker, Table, Modal...
- **Tailwind CSS**: Styling nhanh chóng và responsive
- **Styled Components**: Custom components với CSS-in-JS

### Routing
- **React Router v7**: Quản lý routes và navigation trong ứng dụng
- **Protected Routes**: Bảo vệ các routes cần authentication

## Hướng Dẫn Phát Triển

1. **Clone dự án và cài đặt dependencies**
   ```bash
   git clone <repository-url>
   cd lavishstay-frontend
   npm install
   ```

2. **Cấu hình môi trường**
   - Tạo file `.env.local` từ `.env.example`
   - Cập nhật các biến môi trường cần thiết

3. **Khởi động ứng dụng**
   ```bash
   npm start
   ```

4. **Build cho production**
   ```bash
   npm run build
   ```

## Tài Liệu Tham Khảo

- [Vite Documentation](https://vitejs.dev/)
- [React Documentation](https://react.dev/)
- [TypeScript Documentation](https://www.typescriptlang.org/docs/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Ant Design Documentation](https://ant.design/docs/react/introduce)
- [Redux Toolkit Documentation](https://redux-toolkit.js.org/)
- [React Query Documentation](https://tanstack.com/query/latest)
- [React Router Documentation](https://reactrouter.com/)

## Giấy Phép

Dự án được phát triển cho mục đích học tập và giảng dạy.

---

© 2025 LavishStay - Nền tảng đặt phòng cao cấp

