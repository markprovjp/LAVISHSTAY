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

// Interface cho user - phù hợp với Laravel
export interface User {
  id: number;
  name: string;
  email: string;
  email_verified_at?: string | null;
  created_at: string;
  updated_at: string;
  role?: string;
  phone?: string;
  avatar?: string;
}

// Interface cho response đăng nhập từ Laravel
export interface AuthResponse {
  success: boolean;
  message: string;
  data: {
    user: User;
    token: string;
    token_type: string;
  };
}

// Interface cho register response
export interface RegisterResponse {
  success: boolean;
  message: string;
  data: {
    user: User;
    token: string;
    token_type: string;
  };
}

const authService = {
  // Đăng nhập
  login: async (credentials: LoginCredentials): Promise<{ user: User; token: string }> => {
    try {
      const response = await axiosInstance.post('/auth/login', credentials);

      // Xử lý response từ Laravel
      if (response.data.success) {
        const { user, token } = response.data.data;

        // Lưu token ngay lập tức
        authService.setToken(token);

        return { user, token };
      } else {
        throw new Error(response.data.message || 'Đăng nhập thất bại');
      }
    } catch (error: any) {
      // Xử lý lỗi từ backend
      if (error.response?.data?.message) {
        throw new Error(error.response.data.message);
      } else if (error.response?.data?.error) {
        throw new Error(error.response.data.error);
      } else if (error.message) {
        throw new Error(error.message);
      } else {
        throw new Error('Không thể kết nối đến server. Vui lòng thử lại.');
      }
    }
  },

  // Đăng ký
  register: async (data: RegisterData): Promise<{ user: User; token: string }> => {
    try {
      const response = await axiosInstance.post('/auth/register', data);

      if (response.data.success) {
        const { user, token } = response.data.data;

        // Lưu token ngay lập tức
        authService.setToken(token);

        return { user, token };
      } else {
        throw new Error(response.data.message || 'Đăng ký thất bại');
      }
    } catch (error: any) {
      // Xử lý validation errors từ Laravel
      if (error.response?.data?.errors) {
        const validationErrors = error.response.data.errors;
        const firstError = Object.values(validationErrors)[0] as string[];
        throw new Error(firstError[0]);
      } else if (error.response?.data?.message) {
        throw new Error(error.response.data.message);
      } else if (error.message) {
        throw new Error(error.message);
      } else {
        throw new Error('Không thể kết nối đến server. Vui lòng thử lại.');
      }
    }
  },

  // Đăng xuất
  logout: async (): Promise<void> => {
    try {
      await axiosInstance.post('/auth/logout');
    } catch (error) {
      console.error('Logout error:', error);
      // Vẫn clear token dù API có lỗi
    } finally {
      localStorage.removeItem('authToken');
      localStorage.removeItem('authUser');
    }
  },

  // Lấy thông tin người dùng hiện tại
  getCurrentUser: async (): Promise<User> => {
    try {
      const response = await axiosInstance.get('/auth/me');

      if (response.data.success) {
        return response.data.data.user;
      } else {
        throw new Error('Không thể lấy thông tin người dùng');
      }
    } catch (error: any) {
      if (error.response?.status === 401) {
        // Token không hợp lệ
        authService.clearAuth();
        throw new Error('Phiên đăng nhập đã hết hạn');
      }
      throw error;
    }
  },

  // Quên mật khẩu
  forgotPassword: async (email: string): Promise<{ message: string }> => {
    try {
      const response = await axiosInstance.post('/auth/forgot-password', { email });
      return { message: response.data.message };
    } catch (error: any) {
      if (error.response?.data?.message) {
        throw new Error(error.response.data.message);
      } else {
        throw new Error('Không thể gửi email reset mật khẩu');
      }
    }
  },

  // Reset mật khẩu
  resetPassword: async (data: {
    token: string;
    email: string;
    password: string;
    password_confirmation: string;
  }): Promise<{ message: string }> => {
    try {
      const response = await axiosInstance.post('/auth/reset-password', data);
      return { message: response.data.message };
    } catch (error: any) {
      if (error.response?.data?.errors) {
        const validationErrors = error.response.data.errors;
        const firstError = Object.values(validationErrors)[0] as string[];
        throw new Error(firstError[0]);
      } else if (error.response?.data?.message) {
        throw new Error(error.response.data.message);
      } else {
        throw new Error('Không thể reset mật khẩu');
      }
    }
  },

  // Đăng nhập với Google
  googleLogin: async (tokenOrCode: string, isCode: boolean = false): Promise<{ user: User; token: string }> => {
    try {
      const payload = isCode ? { code: tokenOrCode } : { access_token: tokenOrCode };

      const response = await axiosInstance.post('/auth/google', payload);

      if (response.data.success) {
        const { user, token } = response.data.data;

        // Lưu token ngay lập tức
        authService.setToken(token);

        return { user, token };
      } else {
        throw new Error(response.data.message || 'Đăng nhập Google thất bại');
      }
    } catch (error: any) {
      console.error('❌ Google Login API Error:', {
        status: error.response?.status,
        url: error.config?.url,
        message: error.response?.data?.message || error.message,
        data: error.response?.data
      });

      // Xử lý lỗi từ backend
      if (error.response?.data?.message) {
        throw new Error(error.response.data.message);
      } else if (error.response?.data?.error) {
        throw new Error(error.response.data.error);
      } else if (error.message) {
        throw new Error(error.message);
      } else {
        throw new Error('Không thể kết nối đến server. Vui lòng thử lại.');
      }
    }
  },

  // Utility functions
  isAuthenticated: (): boolean => {
    return !!localStorage.getItem('authToken');
  },

  setToken: (token: string): void => {
    localStorage.setItem('authToken', token);
  },

  getToken: (): string | null => {
    return localStorage.getItem('authToken');
  },

  clearAuth: (): void => {
    localStorage.removeItem('authToken');
    localStorage.removeItem('authUser');
  },
};

export default authService;
