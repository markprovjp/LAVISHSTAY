// src/hooks/useApi.ts
import { useQuery, useMutation, QueryClient } from '@tanstack/react-query';
import { message } from 'antd';
import { propertiesAPI, authAPI, bookingsAPI, roomsAPI, roomTypesAPI } from '../utils/api';
import axios from 'axios';

// Create a client to use for invalidation
const queryClient = new QueryClient();


// Rooms related hooks
export const useGetAllRooms = () => {
  return useQuery({
    queryKey: ['rooms'],
    queryFn: async () => {
      const response = await axios.get('/api/rooms');
      return response.data;
    },
  });
};

// Room Types related hooks
export const useGetAllRoomTypes = () => {
  return useQuery({
    queryKey: ['roomTypes'],
    queryFn: roomTypesAPI.getAll,
  });
};

export const useGetRoomTypeById = (roomTypeId: string | number) => {
  return useQuery({
    queryKey: ['roomType', roomTypeId],
    queryFn: () => roomTypesAPI.getById(Number(roomTypeId)),
    enabled: !!roomTypeId,
  });
};

export const useGetRoomsByType = (roomType: string) => {
  return useQuery({
    queryKey: ['rooms', roomType],
    queryFn: async () => {
      const response = await axios.get(`/api/rooms-type/${roomType}`);
      return response.data;
    },
    enabled: !!roomType,
  });
};

// Room details hooks
export const useGetRoomById = (roomId: string | number) => {
  return useQuery({
    queryKey: ['room', roomId],
    queryFn: async () => {
      const response = await axios.get(`/api/rooms/${roomId}`);
      return response.data;
    },
    enabled: !!roomId,
  });
};

export const useGetRoomOptions = (roomId: string | number) => {
  return useQuery({
    queryKey: ['roomOptions', roomId],
    queryFn: async () => {
      const response = await axios.get(`/api/rooms/${roomId}/options`);
      return response.data;
    },
    enabled: !!roomId,
  });
};

export const useGetRoomReviews = (roomId: string | number) => {
  return useQuery({
    queryKey: ['roomReviews', roomId],
    queryFn: async () => {
      const response = await axios.get(`/api/rooms/${roomId}/reviews`);
      return response.data;
    },
    enabled: !!roomId,
  });
};

export const useGetSimilarRooms = (roomId: string | number) => {
  return useQuery({
    queryKey: ['similarRooms', roomId],
    queryFn: async () => {
      const response = await axios.get(`/api/rooms/${roomId}/similar`);
      return response.data;
    },
    enabled: !!roomId,
  });
};

export const useGetServicePackages = () => {
  return useQuery({
    queryKey: ['servicePackages'],
    queryFn: async () => {
      const response = await axios.get('/api/service-packages');
      return response.data;
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

