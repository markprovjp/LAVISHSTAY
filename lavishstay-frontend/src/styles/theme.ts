// src/styles/theme.ts
import { theme } from 'antd';
import { lightColors, darkColors } from './theme-utils';

/**
 * Tạo CSS Variables cho toàn bộ ứng dụng dựa trên chế độ dark/light
 * @param isDarkMode - Boolean xác định dark mode có được bật hay không
 */
export const createCSSVariables = (isDarkMode: boolean) => {
  const baseColors = isDarkMode ? darkColors : lightColors;
  
  // Cập nhật CSS variables trên root
  document.documentElement.style.setProperty('--color-primary', baseColors.colorPrimary);
  document.documentElement.style.setProperty('--color-primary-hover', baseColors.colorPrimaryHover);
  document.documentElement.style.setProperty('--color-secondary', baseColors.colorSecondary);
  document.documentElement.style.setProperty('--color-success', baseColors.colorSuccess);
  document.documentElement.style.setProperty('--color-warning', baseColors.colorWarning);
  document.documentElement.style.setProperty('--color-error', baseColors.colorError);
  document.documentElement.style.setProperty('--color-info', baseColors.colorInfo);
  document.documentElement.style.setProperty('--color-bg-base', baseColors.colorBgBase);
  document.documentElement.style.setProperty('--color-text-base', baseColors.colorTextBase);
  document.documentElement.style.setProperty('--color-text-secondary', baseColors.colorTextSecondary);
  document.documentElement.style.setProperty('--color-border', baseColors.colorBorder);
  document.documentElement.style.setProperty('--color-bg-container', baseColors.colorBgContainer);
  document.documentElement.style.setProperty('--box-shadow', baseColors.boxShadow);
};

/**
 * Tạo ra theme Ant Design dựa trên chế độ hiện tại (Light/Dark)
 * @param isDarkMode - Boolean để xác định dark mode có được bật hay không
 * @returns Object theme cho Ant Design ConfigProvider
 */
export const createAntdTheme = (isDarkMode: boolean) => {
  const baseColors = isDarkMode ? darkColors : lightColors;

  // Tự động cập nhật CSS Variables mỗi khi theme thay đổi
  createCSSVariables(isDarkMode);

  return {
    token: {
      colorPrimary: baseColors.colorPrimary,
      colorLink: baseColors.colorPrimary,
      colorSuccess: baseColors.colorSuccess,
      colorWarning: baseColors.colorWarning,
      colorError: baseColors.colorError,
      colorInfo: baseColors.colorInfo,
      colorBgBase: baseColors.colorBgBase,
      colorTextBase: baseColors.colorTextBase,
      colorBgContainer: baseColors.colorBgContainer,
      fontFamily: "'Be Vietnam Pro', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif, 'Apple Color Emoji'",
      borderRadius: 8,
      controlHeight: 42,
    },
    algorithm: isDarkMode
      ? theme.darkAlgorithm
      : theme.defaultAlgorithm,
  };
};