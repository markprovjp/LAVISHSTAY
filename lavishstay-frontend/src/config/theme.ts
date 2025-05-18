// Cấu hình theme cho Ant Design với khả năng chuyển đổi light/dark mode
import type { ThemeConfig } from 'antd';

// Màu sắc cho chế độ light
const lightColors = {
  colorPrimary: '#1890ff',
  colorLink: '#1890ff',
  colorSuccess: '#52c41a',
  colorWarning: '#faad14',
  colorError: '#f5222d',
  colorInfo: '#1890ff',
  colorBgBase: '#ffffff',
  colorTextBase: '#000000',
};

// Màu sắc cho chế độ dark
const darkColors = {
  colorPrimary: '#177ddc',
  colorLink: '#177ddc',
  colorSuccess: '#49aa19',
  colorWarning: '#d89614',
  colorError: '#a61d24',
  colorInfo: '#177ddc',
  colorBgBase: '#141414',
  colorTextBase: '#ffffff',
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
        colorBgContainer: isDark ? '#1f1f1f' : '#ffffff',
        boxShadowTertiary: isDark 
          ? '0 4px 12px rgba(0, 0, 0, 0.2)' 
          : '0 4px 12px rgba(0, 0, 0, 0.08)',
        borderRadiusLG: 12,
      },
      Menu: {
        colorPrimary: colorTokens.colorPrimary,
        itemHeight: 50,
        itemHoverColor: isDark ? '#177ddc' : '#40a9ff',
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
        labelColor: isDark ? '#d9d9d9' : '#4b5563',
      },
    },
  };
};

// Theme mặc định (light)
const theme = createTheme(false);

export { createTheme };
export default theme;
