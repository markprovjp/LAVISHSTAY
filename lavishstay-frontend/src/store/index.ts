import { configureStore } from '@reduxjs/toolkit';
import { persistStore, persistReducer } from 'redux-persist';
import storage from 'redux-persist/lib/storage';
import { combineReducers } from '@reduxjs/toolkit';
import authReducer from './slices/authSlice';
import themeReducer from './slices/themeSlice';
import searchReducer from './slices/searchSlice';
import bookingReducer from './slices/bookingSlice';
import ReceptionReducer from './slices/Reception';

// Cấu hình persist cho booking và search data
const persistConfig = {
  key: 'root',
  storage,
  whitelist: ['booking', 'search'], // Chỉ persist booking và search data
};

// Combine reducers
const rootReducer = combineReducers({
  auth: authReducer,
  theme: themeReducer,
  search: searchReducer,
  booking: bookingReducer,
  Reception: ReceptionReducer,
});

// Tạo persisted reducer
const persistedReducer = persistReducer(persistConfig, rootReducer);

// Tạo store
const store = configureStore({
  reducer: persistedReducer,
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

// Tạo persistor
export const persistor = persistStore(store);

// Suy ra các loại `rootstate` và` appdispatch` từ chính cửa hàng
export type RootState = ReturnType<typeof store.getState>;
export type AppDispatch = typeof store.dispatch;

export default store;
