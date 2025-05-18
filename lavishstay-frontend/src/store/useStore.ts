// src/store/useStore.ts
import { create } from "zustand";
import { User, AuthState } from '../types';

const useStore = create<AuthState>((set) => ({
  user: null,
  isAuthenticated: false,
  isLoading: false,

  setUser: (user: User | null) => set({ user, isAuthenticated: !!user }),
  startLoading: () => set({ isLoading: true }),
  stopLoading: () => set({ isLoading: false }),
  logout: () => set({ user: null, isAuthenticated: false }),
}));

export default useStore;
