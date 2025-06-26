# LavishStay Frontend Technology Guide

This guide provides an overview of the key technologies used in the LavishStay frontend application and how to use them in your development workflow.

## Core Technologies

### React with TypeScript

Our frontend is built with React 18 and TypeScript, providing a robust type-safe development experience.

```tsx
// Ví dụ về thành phần gõ
interface ButtonProps {
  text: string;
  onClick: () => void;
  disabled?: boolean;
}

const Button: React.FC<ButtonProps> = ({ text, onClick, disabled }) => {
  return (
    <button onClick={onClick} disabled={disabled}>
      {text}
    </button>
  );
};
```

### Vite

Chúng tôi sử dụng Vite làm công cụ xây dựng để phát triển nhanh và các bản dựng sản xuất được tối ưu hóa.

- Start development server: `npm run dev`
- Build for production: `npm run build`
- Preview production build: `npm run preview`

## UI Framework

### Ant Design

We use Ant Design for our UI components. It provides a comprehensive set of high-quality React components.

```tsx
import { Button, DatePicker, Space } from 'antd';

const App: React.FC = () => (
  <Space direction="vertical">
    <Button type="primary">Primary Button</Button>
    <DatePicker />
  </Space>
);
```

### Tailwind CSS

Tailwind CSS is used for utility-first styling.

```tsx
<div className="flex justify-between items-center p-4 bg-white shadow rounded-lg">
  <h2 className="text-xl font-bold text-gray-800">Hotel Name</h2>
  <span className="text-indigo-600 font-semibold">$120/night</span>
</div>
```

## State Management

### Redux Toolkit

We use Redux Toolkit for global state management, particularly for authentication and theme state.

```tsx
// Example of using Redux state and actions
import { useSelector, useDispatch } from 'react-redux';
import { login, logout } from './slices/authSlice';
import { RootState } from './store';

const LoginButton: React.FC = () => {
  const dispatch = useDispatch();
  const { isAuthenticated } = useSelector((state: RootState) => state.auth);

  const handleLogin = () => {
    dispatch(login({ user: { id: 1, name: 'User', email: 'user@example.com' } }));
  };

  const handleLogout = () => {
    dispatch(logout());
  };

  return (
    <button onClick={isAuthenticated ? handleLogout : handleLogin}>
      {isAuthenticated ? 'Logout' : 'Login'}
    </button>
  );
};
```

### Zustand

Zustand is used for simpler state management needs, like shopping cart functionality.

```tsx
// Example of using Zustand store
import useCartStore from '../store/useCartStore';

const AddToCartButton: React.FC<{ product: Product }> = ({ product }) => {
  const addItem = useCartStore(state => state.addItem);
  
  return (
    <button onClick={() => addItem(product)}>
      Add to Cart
    </button>
  );
};
```

## Data Fetching

### Axios

We use Axios for API requests with an interceptor setup for authentication.

```tsx
// Example of using the API client
import api from '../utils/api';

const fetchData = async () => {
  try {
    const response = await api.get('/endpoint');
    return response.data;
  } catch (error) {
    console.error('Error fetching data:', error);
    throw error;
  }
};
```

### React Query (TanStack Query)

React Query is used for data fetching, caching, and state management.

```tsx
// Example of using React Query hooks
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import api from '../utils/api';

// Fetch data
export const useUsers = () => {
  return useQuery({
    queryKey: ['users'],
    queryFn: async () => {
      const response = await api.get('/users');
      return response.data;
    },
  });
};

// Create data with mutation
export const useCreateUser = () => {
  const queryClient = useQueryClient();
  
  return useMutation({
    mutationFn: async (newUser) => {
      const response = await api.post('/users', newUser);
      return response.data;
    },
    onSuccess: () => {
      // Invalidate and refetch
      queryClient.invalidateQueries({ queryKey: ['users'] });
    },
  });
};
```

## Routing

### React Router

We use React Router v7 for application routing.

```tsx
// Example of basic routing
import { BrowserRouter, Routes, Route } from 'react-router-dom';

const AppRoutes: React.FC = () => (
  <BrowserRouter>
    <Routes>
      <Route path="/" element={<Home />} />
      <Route path="/about" element={<About />} />
      <Route path="/contact" element={<Contact />} />
      <Route path="/products/:id" element={<ProductDetail />} />
      <Route path="*" element={<NotFound />} />
    </Routes>
  </BrowserRouter>
);
```

## Internationalization

### i18next

We use i18next for internationalization.

```tsx
// Example of using i18n
import { useTranslation } from 'react-i18next';

const WelcomeMessage: React.FC = () => {
  const { t } = useTranslation();
  
  return <h1>{t('welcome.message')}</h1>;
};
```

## Development Tools

### ESLint

ESLint is configured for code quality and consistency. Run linting with:

```
npm run lint
```

### Vitest

We use Vitest for unit testing. Run tests with:

```
npm test
```

## Best Practices

1. **Component Organization**: Keep components small and focused on a single responsibility.
2. **State Management**: Use Redux for global state, Zustand for simpler state, and React Query for server state.
3. **TypeScript**: Define interfaces for all props and state to ensure type safety.
4. **Styling**: Use Tailwind for most styling needs, with Ant Design for complex components.
5. **Performance**: Use React Query's caching capabilities and memoization for optimizing performance.
6. **Testing**: Write tests for all critical user flows and business logic.

## Example Workflow

For a typical feature implementation:

1. Define types/interfaces in `src/types/`
2. Set up API calls in a service file in `src/services/`
3. Create React Query hooks in `src/hooks/`
4. Implement UI components in `src/components/`
5. Add the feature to the appropriate page in `src/pages/`
6. Update routing in `App.tsx` if needed
7. Add tests for the feature

Happy coding!
