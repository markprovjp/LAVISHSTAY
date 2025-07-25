// src/utils/api.ts
import axios from 'axios';
import { BookingQuotePayload } from '../hooks/useReception'; // Import the type
import { CreateMultiRoomBookingRequest } from '../types/booking';

// Create a base axios instance
const api = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8888/api',
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Add request interceptor to add auth token
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('accessToken');

  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }

  return config;
});

// Add response interceptor to handle common errors
api.interceptors.response.use(
  (response) => response,
  async (error) => {
    const originalRequest = error.config;

    // Handle token expiration
    if (error.response?.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true;

      try {
        // Try to refresh token
        const refreshToken = localStorage.getItem('refreshToken');

        if (refreshToken) {
          const response = await axios.post(`${api.defaults.baseURL}/auth/refresh`, {
            refresh_token: refreshToken,
          });

          if (response.data.access_token) {
            localStorage.setItem('accessToken', response.data.access_token);
            api.defaults.headers.common.Authorization = `Bearer ${response.data.access_token}`;

            return api(originalRequest);
          }
        }
      } catch (err) {
        // If refresh fails, redirect to login
        localStorage.removeItem('accessToken');
        localStorage.removeItem('refreshToken');
        // window.location.href = '/login';
      }
    }

    return Promise.reject(error);
  }
);

// Auth API Functions
export const authAPI = {
  login: async (email: string, password: string) => {
    return api.post('/auth/login', { email, password });
  },

  register: async (name: string, email: string, password: string) => {
    return api.post('/auth/register', { name, email, password });
  },

  logout: async () => {
    return api.post('/auth/logout');
  },

  getProfile: async () => {
    return api.get('/auth/profile');
  },
};

// Properties API Functions
export const propertiesAPI = {
  getAll: async (params?: any) => {
    return api.get('/properties', { params });
  },

  getFeatured: async () => {
    return api.get('/properties/featured');
  },

  getById: async (id: number) => {
    return api.get(`/properties/${id}`);
  },

  search: async (searchParams: any) => {
    return api.get('/properties/search', { params: searchParams });
  },
};

// Bookings API Functions
export const bookingsAPI = {
  create: async (bookingData: any) => {
    return api.post('/bookings', bookingData);
  },

  getByUser: async () => {
    return api.get('/bookings/user');
  },

  getById: async (id: number) => {
    return api.get(`/bookings/${id}`);
  },

  cancel: async (id: number) => {
    return api.post(`/bookings/${id}/cancel`);
  },
};

// Room Types API Functions
export const roomTypesAPI = {
  getAll: async (params?: any) => {
    const response = await api.get('/room-types', { params });
    return response.data;
  },

  getById: async (id: number) => {
    const response = await api.get(`/room-types/${id}`);
    return response.data;
  },

  create: async (data: any) => {
    const response = await api.post('/room-types', data);
    return response.data;
  },

  update: async (id: number, data: any) => {
    const response = await api.put(`/room-types/${id}`, data);
    return response.data;
  },

  delete: async (id: number) => {
    const response = await api.delete(`/room-types/${id}`);
    return response.data;
  }
};

// Rooms API Functions
export const roomsAPI = {
  getAll: async (params?: any) => {
    const response = await api.get('/rooms', { params });
    return response.data;
  },
  getByType: async (roomTypeId: number, params?: any) => {
    const response = await api.get(`/rooms/type/${roomTypeId}`, { params });
    return response.data;
  },

  getByTypeSlug: async (slug: string, params?: any) => {
    const response = await api.get(`/rooms/type-slug/${slug}`, { params });
    return response.data;
  },

  getById: async (id: number) => {
    const response = await api.get(`/rooms/${id}`);
    return response.data;
  },

  create: async (data: any) => {
    const response = await api.post('/rooms', data);
    return response.data;
  },

  update: async (id: number, data: any) => {
    const response = await api.put(`/rooms/${id}`, data);
    return response.data;
  },

  delete: async (id: number) => {
    const response = await api.delete(`/rooms/${id}`);
    return response.data;
  },

  // Get room calendar data
  getCalendarData: async (roomId: number, params?: any) => {
    const response = await api.get(`/rooms/${roomId}/calendar`, { params });
    return response.data;
  },

  // Get room options/packages (kept for compatibility)
  getRoomOptions: async (roomId: string | number) => {
    const response = await api.get(`/room-options?room_id=${roomId}`);
    return response.data;
  },

  // Get room reviews (kept for compatibility)
  getRoomReviews: async (roomId: string | number) => {
    const response = await api.get(`/rooms/${roomId}/reviews`);
    return response.data;
  },

  // Get similar rooms (kept for compatibility)
  getSimilarRooms: async (roomId: string | number) => {
    const response = await api.get(`/rooms/${roomId}/similar`);
    return response.data;
  },

  // Get service packages (kept for compatibility)
  getServicePackages: async () => {
    const response = await api.get('/service-packages');
    return response.data;
  }
};

// Dashboard API Functions
export const dashboardAPI = {
  getRoomStatistics: async () => {
    const response = await api.get('/dashboard/room-statistics');
    return response.data;
  },

  getFilterOptions: async () => {
    const response = await api.get('/dashboard/filter-options');
    return response.data;
  }
};
// Reception Management API
export const receptionAPI = {
  // Legacy booking methods (keep for compatibility)
  createLegacyBooking: async (data: any) => api.post('/reception/book', data),
  getBookingDetail: async (bookingId: string) => api.get(`/reception/booking/${bookingId}`),
  getPaymentStatus: async (bookingId: string) => api.get(`/reception/payment-status/${bookingId}`),
  listBookings: async (params?: any) => api.get('/reception/bookings-legacy', { params }),

  // Booking Management methods
  getBookings: async (params?: any) => {
    const response = await api.get('/reception/bookings', { params });
    return response.data;
  },

  getBookingStatistics: async () => {
    const response = await api.get('/reception/bookings/statistics');
    return response.data;
  },

  getAssignmentPreview: async (bookingId: number) => {
    const response = await api.get(`/reception/bookings/${bookingId}/assignment-preview`);
    return response.data;
  },

  createBooking: async (data: any) => {
    const response = await api.post('/reception/bookings/create', data);
    return response.data;
  },
  createBookingReception: async (data: any) => {
    const response = await api.post('/reception/bookings/createBookingReception', data);
    return response.data;
  },

 
 
  confirmBooking: async (data: any) => {
    const response = await api.post('/reception/bookings/confirm', data);
    return response.data;
  },

  updateBookingStatus: async (bookingId: number, status: string) => {
    const response = await api.put(`/reception/bookings/${bookingId}/status`, { status });
    return response.data;
  },

  cancelBooking: async (bookingId: number) => {
    const response = await api.put(`/reception/bookings/${bookingId}/cancel`);
    return response.data;
  },
 assignRoomToBooking: async (params: { booking_room_id: number; room_id: number }) => {
   const response = await api.post('/reception/bookings/assign-room', params);
   return response.data;
 },
  assignMultipleRooms: async (assignments: { booking_room_id: number; room_id: number }[]) => {
    const response = await api.post('/reception/bookings/assign-multiple-rooms', { assignments });
    return response.data;
  },
  getAvailableRooms: async (checkIn: string, checkOut: string) => {
    const response = await api.get('/rooms/available', {
      params: { check_in: checkIn, check_out: checkOut }
    });
    return response.data;
  },

  // New Room Management methods
  getRooms: async (params?: any) => {
    const response = await api.get('/reception/rooms', { params });
    return response.data;
  },

  getRoomStatistics: async (params?: any) => {
    const response = await api.get('/reception/rooms/statistics', { params });
    return response.data;
  },

  getRoomDetails: async (roomId: number) => {
    const response = await api.get(`/reception/rooms/${roomId}/details`);
    return response.data;
  },

  updateRoomStatus: async (roomId: number, status: string) => {
    const response = await api.put(`/reception/rooms/${roomId}/status`, { status });
    return response.data;
  },

  getRoomBookings: async (params?: any) => {
    const response = await api.get('/reception/bookings', { params });
    return response.data;
  },

  transferBooking: async (params: {
    booking_id: number;
    old_room_id: number;
    new_room_id: number;
    reason?: string;
  }) => {
    const response = await api.post('/reception/bookings/transfer', params);
    return response.data;
  },

  checkIn: async (params: {
    booking_id: number;
    room_id: number;
    actual_check_in_time?: string;
  }) => {
    const response = await api.post('/reception/bookings/check-in', params);
    return response.data;
  },

  checkOut: async (params: {
    booking_id: number;
    room_id: number;
    actual_check_out_time?: string;
  }) => {
    const response = await api.post('/reception/bookings/check-out', params);
    return response.data;
  },

  getFloors: async () => {
    const response = await api.get('/reception/floors');
    return response.data;
  },

  getRoomTypes: async () => {
    const response = await api.get('/reception/room-types');
    return response.data;
  },

  // --- NEW: Function for calculating booking price ---
  calculatePrice: async (payload: BookingQuotePayload) => {
    const response = await api.post('/reception/bookings/calculate-price', payload);
    return response.data;
  },
};

// Payment API for Reception
export const paymentAPI = {
  createBooking: async (data: any) => {
    const response = await api.post('/payment/create-booking', data);
    return response.data;
  },
  getBookingInfo: async (bookingId: string) => {
    const response = await api.get(`/payment/booking-info/${bookingId}`);
    return response.data;
  },
  getPaymentStatus: async (bookingId: string) => {
    const response = await api.get(`/payment/status/${bookingId}`);
    return response.data;
  },
  checkCPayPayment: async (bookingCode: string, amount: number) => {
    return api.post('/payment/check-cpay', { booking_code: bookingCode, amount: amount });
  },
};

export default api;
