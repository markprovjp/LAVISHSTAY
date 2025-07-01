import { createSlice, PayloadAction } from '@reduxjs/toolkit';
import dayjs, { Dayjs } from 'dayjs';

export interface FiltersState {
  dateRange: [string, string] | null; // Lưu string thay vì Dayjs
  roomTypes: string[];
  occupancy: number;
  cleanedOnly: boolean;
  selectedRooms: any[]; // Thêm selectedRooms vào state
}

const initialState: FiltersState = {
  dateRange: [dayjs().toISOString(), dayjs().add(1, 'day').toISOString()], // Lưu string
  roomTypes: [],
  occupancy: 1,
  cleanedOnly: false,
  selectedRooms: [], // Khởi tạo selectedRooms rỗng
};

const filtersSlice = createSlice({
  name: 'filters',
  initialState,
  reducers: {
    setDateRange: (state, action: PayloadAction<[Dayjs, Dayjs] | [string, string] | null>) => {
      if (action.payload) {
        // Convert Dayjs to string if needed
        if (typeof action.payload[0] === 'string') {
          state.dateRange = action.payload as [string, string];
        } else {
          state.dateRange = [
            (action.payload[0] as Dayjs).toISOString(),
            (action.payload[1] as Dayjs).toISOString()
          ];
        }
      } else {
        state.dateRange = null;
      }
    },
    setRoomTypes: (state, action: PayloadAction<string[]>) => {
      state.roomTypes = action.payload;
    },
    setOccupancy: (state, action: PayloadAction<number>) => {
      state.occupancy = action.payload;
    },
    setCleanedOnly: (state, action: PayloadAction<boolean>) => {
      state.cleanedOnly = action.payload;
    },
    setSelectedRooms: (state, action: PayloadAction<any[]>) => {
      state.selectedRooms = action.payload;
    },
    resetFilters: (state) => {
      state.dateRange = [dayjs().toISOString(), dayjs().add(1, 'day').toISOString()];
      state.roomTypes = [];
      state.occupancy = 1;
      state.cleanedOnly = false;
      state.selectedRooms = [];
    },
  },
});

export const { setDateRange, setRoomTypes, setOccupancy, setCleanedOnly, resetFilters, setSelectedRooms } = filtersSlice.actions;
export default filtersSlice.reducer;