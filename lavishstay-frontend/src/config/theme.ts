// Cấu hình theme cho Ant Design với khả năng chuyển đổi light/dark mode
import type { ThemeConfig } from 'antd';

// Màu sắc cho chế độ light
const lightColors = {
  colorPrimary: '#152C5B',
  colorLink: '#152C5B',
  colorSuccess: '#2ca01c',
  colorWarning: '#d97706',
  colorError: '#b91c1c',
  colorInfo: '#0284c7',
  colorBgBase: '#f8fafc',
  colorTextBase: '#1e293b',
};

// Màu sắc cho chế độ dark
const darkColors = {
  colorPrimary: '#3b82f6',
  colorLink: '#3b82f6',
  colorSuccess: '#4ade80',
  colorWarning: '#f59e0b',
  colorError: '#ef4444',
  colorInfo: '#60a5fa',
  colorBgBase: '#0f172a',
  colorTextBase: '#f1f5f9',
};

// Tạo theme cơ bản
const createTheme = (isDark = false): ThemeConfig => {
  const colorTokens = isDark ? darkColors : lightColors;
  
  return {
    token: {
      ...colorTokens,
      borderRadius: 8,
      fontFamily: "'Be Vietnam Pro', 'Open Sans', sans-serif",
      fontSize: 14,
      fontWeightStrong: 600,
    },
    components: {
      Button: {
        controlHeight: 40,
        paddingContentHorizontal: 20,
        fontWeight: 500,
        borderRadius: 6,
      },
      Card: {
        colorBgContainer: isDark ? '#1e293b' : '#ffffff',
        boxShadowTertiary: isDark 
          ? '0 4px 12px rgba(0, 0, 0, 0.3)' 
          : '0 4px 12px rgba(0, 0, 0, 0.06)',
        borderRadiusLG: 12,
      },
      Menu: {
        colorPrimary: colorTokens.colorPrimary,
        itemHeight: 50,
        itemHoverColor: isDark ? '#60a5fa' : '#1e40af',
        itemSelectedColor: '#ffffff',
        itemSelectedBg: colorTokens.colorPrimary,
      },
      Typography: {
        fontWeightStrong: 600,
      },
      Input: {
        controlHeight: 40,
        borderRadius: 6,
      },
      Select: {
        controlHeight: 40,
        borderRadius: 6,
      },
      Form: {
        labelColor: isDark ? '#e2e8f0' : '#4b5563',
      },
    },
  };
};

// Theme mặc định (light)
const theme = createTheme(false);

export { createTheme };
export default theme;