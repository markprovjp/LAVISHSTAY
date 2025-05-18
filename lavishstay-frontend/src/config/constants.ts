/**
 * Các constant cho toàn bộ ứng dụng
 */

// Routes
export const ROUTES = {
  HOME: '/',
  ABOUT: '/about',
  LOGIN: '/login',
  REGISTER: '/register',
  DASHBOARD: '/dashboard',
  PROFILE: '/profile',
  BOOKINGS: '/bookings',
  NOT_FOUND: '/404',
};

// Local Storage Keys
export const STORAGE_KEYS = {
  AUTH_TOKEN: 'authToken',
  DARK_MODE: 'darkMode',
  USER_PREFERENCES: 'userPreferences',
  RECENT_SEARCHES: 'recentSearches',
};

// API Endpoints
export const API_ENDPOINTS = {
  AUTH: {
    LOGIN: '/auth/login',
    REGISTER: '/auth/register',
    LOGOUT: '/auth/logout',
    REFRESH: '/auth/refresh',
    USER: '/auth/user',
  },
  USERS: {
    BASE: '/users',
    PROFILE: '/users/profile',
    AVATAR: '/users/avatar',
  },
  HOTELS: {
    BASE: '/hotels',
    FEATURED: '/hotels/featured',
    SEARCH: '/hotels/search',
    RECOMMENDATIONS: '/hotels/recommendations',
  },
  BOOKINGS: {
    BASE: '/bookings',
    HISTORY: '/bookings/history',
  },
};

// Media Breakpoints
export const BREAKPOINTS = {
  xs: 480,
  sm: 576,
  md: 768,
  lg: 992,
  xl: 1200,
  xxl: 1600,
};

// Default pagination
export const DEFAULT_PAGE_SIZE = 10;

// Maximum file upload size (in bytes)
export const MAX_UPLOAD_SIZE = 5 * 1024 * 1024; // 5MB
