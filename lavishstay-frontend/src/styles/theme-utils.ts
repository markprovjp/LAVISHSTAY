// src/styles/theme-utils.ts
import { css } from 'styled-components';

// Light theme colors
export const lightColors = {
  colorPrimary: '#152C5B',
  colorPrimaryHover: '#1e40af',
  colorSecondary: '#64748b',
  colorSuccess: '#2ca01c',
  colorWarning: '#d97706',
  colorError: '#b91c1c',
  colorInfo: '#0284c7',
  colorBgBase: '#f8fafc',
  colorTextBase: '#1e293b',
  colorTextSecondary: '#64748b',
  colorBorder: '#e2e8f0',
  colorBgContainer: '#ffffff',
  boxShadow: '0 4px 12px rgba(0, 0, 0, 0.06)',
};

// Dark theme colors
export const darkColors = {
  colorPrimary: '#3b82f6',
  colorPrimaryHover: '#60a5fa',
  colorSecondary: '#cbd5e1',
  colorSuccess: '#4ade80',
  colorWarning: '#f59e0b',
  colorError: '#ef4444',
  colorInfo: '#60a5fa',
  colorBgBase: '#0f172a',
  colorTextBase: '#f1f5f9',
  colorTextSecondary: '#cbd5e1',
  colorBorder: '#334155',
  colorBgContainer: '#1e293b',
  boxShadow: '0 4px 12px rgba(0, 0, 0, 0.3)',
};

// Common transitions
export const transitions = {
  fast: '0.2s ease',
  medium: '0.3s ease',
  slow: '0.5s ease',
  bounce: '0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55)',
};

// Breakpoints for responsive design
export const breakpoints = {
  xs: '480px',
  sm: '576px', 
  md: '768px',
  lg: '992px',
  xl: '1200px',
  xxl: '1600px',
};

// Media query helpers
export const media = {
  xs: (literals: TemplateStringsArray, ...placeholders: any[]) => css`
    @media (max-width: ${breakpoints.xs}) {
      ${css(literals, ...placeholders)}
    }
  `,
  sm: (literals: TemplateStringsArray, ...placeholders: any[]) => css`
    @media (min-width: ${breakpoints.sm}) {
      ${css(literals, ...placeholders)}
    }
  `,
  md: (literals: TemplateStringsArray, ...placeholders: any[]) => css`
    @media (min-width: ${breakpoints.md}) {
      ${css(literals, ...placeholders)}
    }
  `,
  lg: (literals: TemplateStringsArray, ...placeholders: any[]) => css`
    @media (min-width: ${breakpoints.lg}) {
      ${css(literals, ...placeholders)}
    }
  `,
  xl: (literals: TemplateStringsArray, ...placeholders: any[]) => css`
    @media (min-width: ${breakpoints.xl}) {
      ${css(literals, ...placeholders)}
    }
  `,
};

// Helper for theme-based styles in styled-components
export const getThemeValue = (
  lightValue: string | number,
  darkValue: string | number
) => (props: any) => (props.theme.isDark ? darkValue : lightValue);

// Common component styles
export const cardStyle = css`
  background-color: ${(props) => (props.theme.isDark ? darkColors.colorBgContainer : lightColors.colorBgContainer)};
  border-radius: 12px;
  box-shadow: ${(props) => (props.theme.isDark ? darkColors.boxShadow : lightColors.boxShadow)};
  transition: all ${transitions.medium};
  
  &:hover {
    box-shadow: ${(props) => (props.theme.isDark ? '0 8px 24px rgba(0, 0, 0, 0.4)' : '0 8px 24px rgba(0, 0, 0, 0.1)')};
    transform: translateY(-4px);
  }
`;

export const buttonStyle = css`
  background-color: ${(props) => (props.theme.isDark ? darkColors.colorPrimary : lightColors.colorPrimary)};
  color: white;
  border: none;
  border-radius: 8px;
  padding: 10px 20px;
  font-weight: 500;
  transition: all ${transitions.medium};
  cursor: pointer;
  
  &:hover {
    background-color: ${(props) => (props.theme.isDark ? darkColors.colorPrimaryHover : lightColors.colorPrimaryHover)};
  }
  
  &:disabled {
    opacity: 0.7;
    cursor: not-allowed;
  }
`;

export const inputStyle = css`
  background-color: ${(props) => (props.theme.isDark ? darkColors.colorBgContainer : lightColors.colorBgContainer)};
  border: 1px solid ${(props) => (props.theme.isDark ? darkColors.colorBorder : lightColors.colorBorder)};
  color: ${(props) => (props.theme.isDark ? darkColors.colorTextBase : lightColors.colorTextBase)};
  border-radius: 8px;
  padding: 10px 16px;
  transition: all ${transitions.fast};
  
  &:focus {
    border-color: ${(props) => (props.theme.isDark ? darkColors.colorPrimary : lightColors.colorPrimary)};
    box-shadow: 0 0 0 2px ${(props) => (props.theme.isDark ? 'rgba(59, 130, 246, 0.2)' : 'rgba(21, 44, 91, 0.2)')};
    outline: none;
  }
`;
