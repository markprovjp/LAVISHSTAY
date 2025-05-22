// src/types/index.ts
export interface User {
  id: number;
  name: string;
  email: string;
  avatar?: string;
  role?: string;
  phone?: string;
}

export interface AuthState {
  user: User | null;
  isAuthenticated: boolean;
  isLoading: boolean;
  setUser: (user: User | null) => void;
  startLoading: () => void;
  stopLoading: () => void;
  logout: () => void;
}

// Export all models
export * from './models';
