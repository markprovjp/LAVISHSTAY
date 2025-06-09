import { useEffect } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { setDarkMode } from '../store/slices/themeSlice';
import { RootState } from '../store';
import { createCSSVariables } from '../styles/theme';
import { scrollManager } from '../utils/scrollManager';

/**
 * Custom hook để quản lý dark/light mode
 * - Tự động phát hiện chế độ ưa thích của hệ thống
 * - Lưu chế độ đã chọn vào localStorage
 * - Đồng bộ với theme của hệ thống khi thay đổi
 * - Áp dụng CSS variables cho toàn bộ ứng dụng
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

    // Thêm event listener để theo dõi thay đổi
    if (mediaQuery.addEventListener) {
      mediaQuery.addEventListener('change', handleChange);
    } else {
      // Cho các trình duyệt cũ hơn
      mediaQuery.addListener(handleChange);
    }

    // Cleanup
    return () => {
      if (mediaQuery.removeEventListener) {
        mediaQuery.removeEventListener('change', handleChange);
      } else {
        mediaQuery.removeListener(handleChange);
      }
    };
  }, [dispatch]);  // Cập nhật localStorage khi thay đổi
  useEffect(() => {
    // Temporarily disable scroll to prevent conflicts during theme change
    scrollManager.disableScrollTemporarily(300);

    // Batch DOM updates to prevent layout thrashing
    requestAnimationFrame(() => {
      localStorage.setItem('darkMode', String(isDarkMode));

      // Cập nhật thuộc tính data cho body để sử dụng trong Tailwind
      document.documentElement.setAttribute('data-theme', isDarkMode ? 'dark' : 'light');

      // Đồng thời cập nhật CSS variables
      createCSSVariables(isDarkMode);

      // Thêm hoặc xóa class 'dark' cho Tailwind
      if (isDarkMode) {
        document.documentElement.classList.add('dark');
      } else {
        document.documentElement.classList.remove('dark');
      }
    });
  }, [isDarkMode]);
  // Trả về state và các method cần thiết
  return {
    isDarkMode,
    toggle: () => {
      // Disable scroll briefly to prevent conflicts
      scrollManager.disableScrollTemporarily(200);
      dispatch(setDarkMode(!isDarkMode));
    },
    enableDarkMode: () => {
      scrollManager.disableScrollTemporarily(200);
      dispatch(setDarkMode(true));
    },
    disableDarkMode: () => {
      scrollManager.disableScrollTemporarily(200);
      dispatch(setDarkMode(false));
    },
  };
};

export default useThemeMode;
