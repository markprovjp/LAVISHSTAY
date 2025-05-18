// src/theme.ts
import type { ThemeConfig } from 'antd';

const theme: ThemeConfig = {
  token: {
    colorPrimary: '#1890ff',
    colorLink: '#1890ff',
    colorSuccess: '#52c41a',
    colorWarning: '#faad14',
    colorError: '#f5222d',
    colorInfo: '#1890ff',
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
      colorBgContainer: '#ffffff',
      boxShadowTertiary: '0 4px 12px rgba(0, 0, 0, 0.08)',
      borderRadiusLG: 12,
    },
    Menu: {
      colorPrimary: '#1890ff',
      itemHeight: 50,
      itemHoverColor: '#40a9ff',
      itemSelectedColor: '#ffffff',
      itemSelectedBg: '#1890ff',
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
      labelColor: '#4b5563',
    },  },
};

export default theme;

