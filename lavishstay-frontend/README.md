# LavishStay Frontend - React + TypeScript + Vite

Dự án LavishStay Frontend là một nền tảng đặt phòng cao cấp được xây dựng với các công nghệ hiện đại:
- React 18 + TypeScript
- Vite cho phát triển và build siêu nhanh
- Ant Design + Tailwind CSS cho UI/UX
- Redux Toolkit + Zustand cho quản lý state
- Tanstack React Query cho quản lý data fetching
- React Router v7 cho routing
- Be Vietnam Pro font cho giao diện sang trọng

## Cấu trúc dự án

```
src/
  ├── assets/           # Tài nguyên tĩnh (hình ảnh, fonts, css)
  │   ├── css/          # Global CSS và fonts
  │   ├── fonts/        # Font files
  │   └── images/       # Hình ảnh
  │
  ├── components/       # Components React
  │   ├── layouts/      # Layouts (Default, Dashboard, Auth)
  │   ├── ui/           # UI components (buttons, cards, etc.)
  │   ├── ContactForm.tsx
  │   ├── CustomFooter.tsx
  │   ├── FeatureCard.tsx
  │   ├── HeroHeader.tsx
  │   ├── HotelCard.tsx
  │   ├── Navbar.tsx
  │   ├── Newsletter.tsx
  │   ├── PageHeader.tsx
  │   ├── SearchForm.tsx
  │   ├── SectionHeader.tsx
  │   ├── Stats.tsx
  │   ├── Testimonial.tsx
  │   └── UserAvatar.tsx
  │
  ├── config/           # Cấu hình
  │   ├── axios.ts      # Cấu hình Axios
  │   ├── constants.ts  # Các hằng số
  │   ├── env.ts        # Biến môi trường
  │   ├── index.ts      # Export cấu hình
  │   └── theme.ts      # Cấu hình theme
  │
  ├── hooks/            # Custom hooks
  │
  ├── pages/            # Các trang
  │   ├── dashboard/    # Các trang dashboard
  │   ├── About.tsx     # Trang giới thiệu
  │   ├── ErrorPage.tsx # Trang lỗi
  │   ├── Forbidden.tsx # Trang cấm truy cập
  │   ├── Home.tsx      # Trang chủ
  │   ├── Login.tsx     # Trang đăng nhập
  │   ├── NotFound.tsx  # Trang không tìm thấy
  │   ├── Register.tsx  # Trang đăng ký
  │   └── ServerError.tsx # Trang lỗi máy chủ
  │
  ├── routes/           # Định nghĩa routes
  │
  ├── services/         # Services API
  │   ├── authService.ts # Dịch vụ xác thực
  │   ├── index.ts      # Export services
  │   └── userService.ts # Dịch vụ người dùng
  │
  ├── store/            # Quản lý state
  │   ├── slices/       # Redux slices
  │   ├── index.ts      # Cấu hình Redux store
  │   └── useStore.ts   # Hooks Zustand
  │
  ├── types/            # TypeScript types
  │   ├── index.ts      # Export types
  │   └── models.ts     # Định nghĩa models
  │
  ├── utils/            # Các hàm tiện ích
  │
  ├── App.tsx           # Component chính
  ├── index.tsx         # Điểm vào ứng dụng
  ├── styles.ts         # Global styles
  └── theme.ts          # Theme configuration
```

## Scripts

- `npm start` / `npm run dev` - Khởi động môi trường phát triển với Vite
- `npm run build` - Tạo phiên bản production
- `npm run preview` - Xem trước bản build production
- `npm test` - Chạy tests với Vitest
- `npm run lint` - Kiểm tra lỗi code với ESLint

## Công nghệ sử dụng

- **Frontend Framework**: React 18
- **Language**: TypeScript
- **Build Tool**: Vite
- **UI Component Library**: Ant Design
- **Styling**: Tailwind CSS
- **State Management**: Redux Toolkit & Zustand
- **API Client**: Axios
- **Data Fetching**: Tanstack React Query
- **Routing**: React Router v7
- **Form Handling**: Native React forms
- **Testing**: Vitest, React Testing Library
- **Type Checking**: TypeScript

## Cấu trúc cấp cao của dự án

- `index.html` - File HTML điểm vào
- `public/` - Chứa các tài nguyên tĩnh được phục vụ trực tiếp
- `src/` - Mã nguồn React
  - `App.tsx` - Component React chính
  - `index.tsx` - Điểm khởi đầu React
  - `components/` - Các component có thể tái sử dụng
  - `pages/` - Các trang/routes của ứng dụng
  - `assets/` - Tài nguyên tĩnh như hình ảnh
  - `hooks/` - Custom React hooks
  - `utils/` - Các hàm tiện ích

## Tài liệu tham khảo

- [Vite Documentation](https://vitejs.dev/)
- [React Documentation](https://react.dev/)
- [TypeScript Documentation](https://www.typescriptlang.org/docs/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Ant Design Documentation](https://ant.design/docs/react/introduce)
- [Redux Toolkit Documentation](https://redux-toolkit.js.org/)
- [React Query Documentation](https://tanstack.com/query/latest)
- [React Router Documentation](https://reactrouter.com/)

