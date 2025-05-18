# LavishStay Frontend - React + TypeScript + Vite

Dự án LavishStay Frontend là một nền tảng đặt phòng cao cấp được xây dựng với các công nghệ hiện đại:
- React 18 + TypeScript
- Vite cho phát triển và build siêu nhanh
- Ant Design + Tailwind CSS cho UI/UX
- Redux Toolkit cho quản lý state
- React Query cho quản lý data fetching
- React Router v6 cho routing
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
  │   └── ui/           # UI components (buttons, cards, etc.)
  │
  ├── config/           # Cấu hình
  │   ├── constants.ts  # Các hằng số
  │   ├── env.ts        # Biến môi trường
  │   ├── axios.ts      # Cấu hình Axios
  │   └── theme.ts      # Cấu hình theme
  │
  ├── hooks/            # Custom hooks
  │
  ├── pages/            # Các trang
  │   └── dashboard/    # Các trang dashboard
  │
  ├── routes/           # Định nghĩa routes
  │   ├── PrivateRoute.tsx
  │   └── PublicRoute.tsx
  │
  ├── services/         # Services API
  │
  ├── store/            # Redux store
  │   └── slices/       # Redux slices
  │
  ├── types/            # TypeScript types
  │
  └── utils/            # Các hàm tiện ích
```

### `npm run lint`

Runs ESLint to check for code quality issues.

## Project Structure

- `index.html` - The entry point HTML file
- `src/` - Contains all React code
  - `App.jsx` - The main React component
  - `index.jsx` - The React entry point
  - `components/` - Reusable React components (to be created)
  - `pages/` - Pages/routes of the application (to be created)
  - `assets/` - Static assets like images (to be created)
  - `hooks/` - Custom React hooks (to be created)
  - `utils/` - Utility functions (to be created)

## Learn More

- [Vite Documentation](https://vitejs.dev/)
- [React Documentation](https://react.dev/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Vitest Documentation](https://vitest.dev/)

