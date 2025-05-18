import axiosInstance from '../config/axios';
import { User } from './authService';

// Interface cho thông tin cập nhật người dùng
export interface UpdateUserData {
  name?: string;
  email?: string;
  password?: string;
  password_confirmation?: string;
  avatar?: File;
}

const userService = {
  // Lấy danh sách người dùng (chỉ admin)
  getUsers: async (page = 1, limit = 10): Promise<{ data: User[], total: number }> => {
    const response = await axiosInstance.get('/users', {
      params: { page, limit }
    });
    return response.data;
  },

  // Lấy thông tin chi tiết người dùng
  getUserById: async (id: number): Promise<User> => {
    const response = await axiosInstance.get(`/users/${id}`);
    return response.data;
  },

  // Cập nhật thông tin người dùng
  updateUser: async (id: number, data: UpdateUserData): Promise<User> => {
    // Nếu có file, phải sử dụng FormData
    if (data.avatar) {
      const formData = new FormData();
      Object.entries(data).forEach(([key, value]) => {
        if (value !== undefined) {
          formData.append(key, value);
        }
      });
      
      const response = await axiosInstance.post(`/users/${id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });
      return response.data;
    }
    
    // Nếu không có file, sử dụng JSON
    const response = await axiosInstance.put(`/users/${id}`, data);
    return response.data;
  },

  // Xóa người dùng (chỉ admin)
  deleteUser: async (id: number): Promise<void> => {
    await axiosInstance.delete(`/users/${id}`);
  },
};

export default userService;
