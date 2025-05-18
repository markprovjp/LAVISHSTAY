import axiosInstance from '../config/axios';

// Interface cho dữ liệu đăng nhập
export interface LoginCredentials {
  email: string;
  password: string;
}

// Interface cho dữ liệu đăng ký
export interface RegisterData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

// Interface cho user
export interface User {
  id: number;
  name: string;
  email: string;
  created_at: string;
  updated_at: string;
}

// Interface cho response đăng nhập
export interface AuthResponse {
  user: User;
  token: string;
  token_type: string;
}

const authService = {
  // Đăng nhập
  login: async (credentials: LoginCredentials): Promise<AuthResponse> => {
    const response = await axiosInstance.post('/auth/login', credentials);
    return response.data;
  },

  // Đăng ký
  register: async (data: RegisterData): Promise<AuthResponse> => {
    const response = await axiosInstance.post('/auth/register', data);
    return response.data;
  },

  // Đăng xuất
  logout: async (): Promise<void> => {
    await axiosInstance.post('/auth/logout');
    localStorage.removeItem('authToken');
  },

  // Lấy thông tin người dùng
  getCurrentUser: async (): Promise<User> => {
    const response = await axiosInstance.get('/auth/user');
    return response.data;
  },

  // Kiểm tra đã đăng nhập chưa
  isAuthenticated: (): boolean => {
    return !!localStorage.getItem('authToken');
  },

  // Lưu token vào localStorage
  setToken: (token: string): void => {
    localStorage.setItem('authToken', token);
  },

  // Lấy token
  getToken: (): string | null => {
    return localStorage.getItem('authToken');
  },
};

export default authService;
