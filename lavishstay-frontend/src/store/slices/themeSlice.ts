import { createSlice, PayloadAction } from '@reduxjs/toolkit';

// Interface cho state của theme
interface ThemeState {
  isDarkMode: boolean;
}

// Kiểm tra localStorage hoặc prefers-color-scheme để lấy giá trị mặc định
const getDefaultDarkMode = (): boolean => {
  // Kiểm tra localStorage trước
  const savedMode = localStorage.getItem('darkMode');
  if (savedMode !== null) {
    return savedMode === 'true';
  }
  
  // Nếu không có localStorage, kiểm tra prefers-color-scheme
  return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
};

// State ban đầu
const initialState: ThemeState = {
  isDarkMode: getDefaultDarkMode(),
};

// Tạo slice cho theme
const themeSlice = createSlice({
  name: 'theme',
  initialState,
  reducers: {
    toggleDarkMode: (state) => {
      state.isDarkMode = !state.isDarkMode;
      // Lưu vào localStorage
      localStorage.setItem('darkMode', String(state.isDarkMode));
    },
    setDarkMode: (state, action: PayloadAction<boolean>) => {
      state.isDarkMode = action.payload;
      // Lưu vào localStorage
      localStorage.setItem('darkMode', String(state.isDarkMode));
    },
  },
});

// Export actions và reducer
export const { toggleDarkMode, setDarkMode } = themeSlice.actions;
export default themeSlice.reducer;
