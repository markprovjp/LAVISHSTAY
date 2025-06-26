// src/utils/api.ts
import axios from 'axios';

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

export default api;
