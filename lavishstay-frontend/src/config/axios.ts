import axios from 'axios';
import env from './env';

// Tạo một instance axios với cấu hình mặc định
const axiosInstance = axios.create({
  baseURL: env.API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
  timeout: 10000, // 10 giây
});

// Interceptor cho request
axiosInstance.interceptors.request.use(
  (config) => {
    // Lấy token từ localStorage
    const token = localStorage.getItem('authToken');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Interceptor cho response
axiosInstance.interceptors.response.use(
  (response) => {
    return response;
  },
  (error) => {
    // Xử lý lỗi toàn cục (đăng nhập lại, thông báo lỗi, ...)
    if (error.response?.status === 401) {
      // Chưa đăng nhập hoặc hết hạn token
      localStorage.removeItem('authToken');
      // Có thể điều hướng đến trang đăng nhập ở đây
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

export default axiosInstance;
