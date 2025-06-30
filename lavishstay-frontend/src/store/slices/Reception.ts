import { createSlice, PayloadAction } from '@reduxjs/toolkit';
import dayjs, { Dayjs } from 'dayjs';

export interface FiltersState {
  dateRange: [Dayjs, Dayjs] | null;
  roomTypes: string[];
  occupancy: number;
  cleanedOnly: boolean;
  selectedRooms: any[]; // Thêm selectedRooms vào state
}

const initialState: FiltersState = {
  dateRange: [dayjs(), dayjs().add(1, 'day')],
  roomTypes: [],
  occupancy: 1,
  cleanedOnly: false,
  selectedRooms: [], // Khởi tạo selectedRooms rỗng
};

const filtersSlice = createSlice({
  name: 'filters',
  initialState,
  reducers: {
    setDateRange: (state, action: PayloadAction<[Dayjs, Dayjs] | null>) => {
      state.dateRange = action.payload;
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
      state.dateRange = [dayjs(), dayjs().add(1, 'day')];
      state.roomTypes = [];
      state.occupancy = 1;
      state.cleanedOnly = false;
      state.selectedRooms = [];
    },
  },
});

export const { setDateRange, setRoomTypes, setOccupancy, setCleanedOnly, resetFilters, setSelectedRooms } = filtersSlice.actions;
export default filtersSlice.reducer;