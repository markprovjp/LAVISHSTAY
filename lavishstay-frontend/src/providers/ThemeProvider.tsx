// src/providers/ThemeProvider.tsx
import React from "react";
import { ThemeProvider as StyledThemeProvider } from "styled-components";
import { useSelector } from "react-redux";
import { RootState } from "../store";
import { lightColors, darkColors } from "../styles/theme-utils";

interface ThemeProviderProps {
  children: React.ReactNode;
}

const ThemeProvider: React.FC<ThemeProviderProps> = ({ children }) => {
  const { isDarkMode } = useSelector((state: RootState) => state.theme);

  // Tạo đối tượng chủ đề cho các thành phần kiểu dáng
  const theme = {
    isDark: isDarkMode,
    colors: isDarkMode ? darkColors : lightColors,
  };

  return <StyledThemeProvider theme={theme}>{children}</StyledThemeProvider>;
};

export default ThemeProvider;
