// File cấu hình môi trường
export const env = {
  API_URL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
  APP_NAME: import.meta.env.VITE_APP_NAME || 'LavishStay',
  APP_ENV: import.meta.env.VITE_APP_ENV || 'development',
  ENABLE_MOCK_API: import.meta.env.VITE_ENABLE_MOCK_API === 'true',
};

export default env;
