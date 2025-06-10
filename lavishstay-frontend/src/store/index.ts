import { configureStore } from '@reduxjs/toolkit';
import authReducer from './slices/authSlice';
import themeReducer from './slices/themeSlice';
import searchReducer from './slices/searchSlice';
import bookingReducer from './slices/bookingSlice';

// Tạo store
const store = configureStore({
  reducer: {
    auth: authReducer,
    theme: themeReducer,
    search: searchReducer,
    booking: bookingReducer,
    // Thêm các reducer khác ở đây
  },
  // Middleware nếu cần
});

// Suy ra các loại `rootstate` và` appdispatch` từ chính cửa hàng
export type RootState = ReturnType<typeof store.getState>;
export type AppDispatch = typeof store.dispatch;

export default store;
