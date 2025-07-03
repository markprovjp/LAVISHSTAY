import { create } from 'zustand';
import { ViewMode, RoomFilters } from '../types/room';

interface RoomManagementState {
    viewMode: ViewMode;
    filters: RoomFilters;
    setViewMode: (mode: ViewMode) => void;
    setFilters: (filters: RoomFilters) => void;
    resetFilters: () => void;
}

const initialFilters: RoomFilters = {
    customerName: '',
    dateRange: undefined,
    roomNumber: '',
    roomType: undefined,
    roomStatus: undefined,
};

export const useRoomManagementStore = create<RoomManagementState>((set) => ({
    viewMode: 'grid',
    filters: initialFilters,
    setViewMode: (mode) => set({ viewMode: mode }),
    setFilters: (filters) => set({ filters }),
    resetFilters: () => set({ filters: initialFilters }),
}));
