import { createSlice, PayloadAction } from '@reduxjs/toolkit';
import { User } from '../../mirage/users';

// Interface cho state của auth
interface AuthState {
  user: User | null;
  token: string | null;
  isAuthenticated: boolean;
  isLoading: boolean;
  error: string | null;
}

// Hàm để khôi phục user từ localStorage
const getUserFromStorage = (): User | null => {
  try {
    const userStr = localStorage.getItem('authUser');
    return userStr ? JSON.parse(userStr) : null;
  } catch {
    return null;
  }
};

// State ban đầu
const initialState: AuthState = {
  user: getUserFromStorage(),
  token: localStorage.getItem('authToken'),
  isAuthenticated: !!localStorage.getItem('authToken'),
  isLoading: false,
  error: null,
};

// Tạo slice cho auth
const authSlice = createSlice({
  name: 'auth',
  initialState,
  reducers: {
    loginStart: (state) => {
      state.isLoading = true;
      state.error = null;
    }, loginSuccess: (state, action: PayloadAction<{ user: User, token: string }>) => {
      state.isLoading = false;
      state.user = action.payload.user;
      state.token = action.payload.token;
      state.isAuthenticated = true;
      // Lưu token và user vào localStorage
      localStorage.setItem('authToken', action.payload.token);
      localStorage.setItem('authUser', JSON.stringify(action.payload.user));
    },
    loginFailure: (state, action: PayloadAction<string>) => {
      state.isLoading = false;
      state.error = action.payload;
      state.isAuthenticated = false;
    }, logout: (state) => {
      state.user = null;
      state.token = null;
      state.isAuthenticated = false;
      // Xóa token và user từ localStorage
      localStorage.removeItem('authToken');
      localStorage.removeItem('authUser');
    },
    updateUser: (state, action: PayloadAction<User>) => {
      state.user = action.payload;
    },
  },
});

// Export actions và reducer
export const { loginStart, loginSuccess, loginFailure, logout, updateUser } = authSlice.actions;
export default authSlice.reducer;
