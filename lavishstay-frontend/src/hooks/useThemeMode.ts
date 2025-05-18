import { useEffect } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { setDarkMode } from '../store/slices/themeSlice';
import { RootState } from '../store';

/**
 * Custom hook để quản lý dark/light mode
 * - Tự động phát hiện chế độ ưa thích của hệ thống
 * - Lưu chế độ đã chọn vào localStorage
 * - Đồng bộ với theme của hệ thống khi thay đổi
 */
const useThemeMode = () => {
  const dispatch = useDispatch();
  const { isDarkMode } = useSelector((state: RootState) => state.theme);

  // Theo dõi cài đặt chế độ màu của hệ thống
  useEffect(() => {
    // Kiểm tra nếu đã có lưu trong localStorage
    const savedMode = localStorage.getItem('darkMode');
    if (savedMode !== null) {
      dispatch(setDarkMode(savedMode === 'true'));
    } else {
      // Nếu chưa có, dùng cài đặt của hệ thống
      const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
      dispatch(setDarkMode(prefersDarkMode));
    }

    // Theo dõi khi cài đặt hệ thống thay đổi
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    const handleChange = (e: MediaQueryListEvent) => {
      // Chỉ thay đổi nếu người dùng chưa tự chọn (không có trong localStorage)
      if (localStorage.getItem('darkMode') === null) {
        dispatch(setDarkMode(e.matches));
      }
    };

    mediaQuery.addEventListener('change', handleChange);
    return () => mediaQuery.removeEventListener('change', handleChange);
  }, [dispatch]);

  // Thay đổi chế độ màu
  const toggleTheme = () => {
    const newMode = !isDarkMode;
    dispatch(setDarkMode(newMode));
    localStorage.setItem('darkMode', String(newMode));
  };

  return {
    isDarkMode,
    toggleTheme,
  };
};

export default useThemeMode;
