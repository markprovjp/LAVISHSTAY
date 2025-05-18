// src/hooks/useApi.ts
import { useQuery, useMutation, QueryClient } from '@tanstack/react-query';
import { message } from 'antd';
import { propertiesAPI, authAPI, bookingsAPI } from '../utils/api';

// Create a client to use for invalidation
const queryClient = new QueryClient();

// Properties related hooks
export const useGetFeaturedProperties = () => {
  return useQuery({
    queryKey: ['featuredProperties'],
    queryFn: propertiesAPI.getFeatured,
  });
};

export const useGetPropertyById = (id: number) => {
  return useQuery({
    queryKey: ['property', id],
    queryFn: () => propertiesAPI.getById(id),
    enabled: !!id,
  });
};

export const useSearchProperties = (searchParams: any) => {
  return useQuery({
    queryKey: ['properties', searchParams],
    queryFn: () => propertiesAPI.search(searchParams),
    enabled: !!searchParams.destination || !!searchParams.checkIn,
  });
};

// Booking related hooks
export const useCreateBooking = () => {
  return useMutation({
    mutationFn: bookingsAPI.create,
    onSuccess: () => {
      message.success('Đặt phòng thành công!');
      queryClient.invalidateQueries({ queryKey: ['userBookings'] });
    },
    onError: (error: any) => {
      message.error(error.response?.data?.message || 'Có lỗi xảy ra khi đặt phòng');
    },
  });
};

export const useGetUserBookings = () => {
  return useQuery({
    queryKey: ['userBookings'],
    queryFn: bookingsAPI.getByUser,
  });
};

export const useCancelBooking = () => {
  return useMutation({
    mutationFn: bookingsAPI.cancel,
    onSuccess: () => {
      message.success('Hủy đặt phòng thành công!');
      queryClient.invalidateQueries({ queryKey: ['userBookings'] });
    },
    onError: (error: any) => {
      message.error(error.response?.data?.message || 'Có lỗi xảy ra khi hủy đặt phòng');
    },
  });
};

// Auth related hooks
export const useLogin = () => {
  return useMutation({
    mutationFn: (credentials: { email: string; password: string }) => {
      return authAPI.login(credentials.email, credentials.password);
    },
    onSuccess: (response) => {
      localStorage.setItem('accessToken', response.data.access_token);
      localStorage.setItem('refreshToken', response.data.refresh_token);
      message.success('Đăng nhập thành công!');
    },
    onError: (error: any) => {
      message.error(error.response?.data?.message || 'Đăng nhập thất bại');
    },
  });
};

export const useRegister = () => {
  return useMutation({
    mutationFn: (userData: { name: string; email: string; password: string }) => {
      return authAPI.register(userData.name, userData.email, userData.password);
    },
    onSuccess: () => {
      message.success('Đăng ký thành công! Vui lòng đăng nhập.');
    },
    onError: (error: any) => {
      message.error(error.response?.data?.message || 'Đăng ký thất bại');
    },
  });
};

export const useLogout = () => {
  return useMutation({
    mutationFn: authAPI.logout,
    onSuccess: () => {
      localStorage.removeItem('accessToken');
      localStorage.removeItem('refreshToken');
      message.success('Đăng xuất thành công!');
      queryClient.clear();
    },
  });
};

export const useGetUserProfile = () => {
  return useQuery({
    queryKey: ['userProfile'],
    queryFn: authAPI.getProfile,
    retry: false,
    enabled: !!localStorage.getItem('accessToken'),
  });
};
