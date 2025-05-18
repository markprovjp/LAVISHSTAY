import { configureStore } from '@reduxjs/toolkit';
import authReducer from './slices/authSlice';
import themeReducer from './slices/themeSlice';

// Tạo store
const store = configureStore({
  reducer: {
    auth: authReducer,
    theme: themeReducer,
    // Thêm các reducer khác ở đây
  },
  // Middleware nếu cần
});

// Infer the `RootState` and `AppDispatch` types from the store itself
export type RootState = ReturnType<typeof store.getState>;
export type AppDispatch = typeof store.dispatch;

export default store;
