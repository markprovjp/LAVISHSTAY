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
  middleware: (getDefaultMiddleware) =>
    getDefaultMiddleware({
      serializableCheck: {
        // Ignore these action types
        ignoredActions: ['persist/PERSIST', 'persist/REHYDRATE'],
        // Ignore these field paths in all actions
        ignoredActionsPaths: ['meta.arg', 'payload.timestamp'],
        // Ignore these paths in the state
        ignoredPaths: ['search.dateRange'],
      },
    }),
});

// Suy ra các loại `rootstate` và` appdispatch` từ chính cửa hàng
export type RootState = ReturnType<typeof store.getState>;
export type AppDispatch = typeof store.dispatch;

export default store;
