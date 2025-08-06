/**
 * @deprecated Không sử dụng file này nữa. Thay vào đó, sử dụng src/styles/theme.ts để đảm bảo tính nhất quán
 */
import { ThemeConfig } from 'antd';
import { createAntdTheme } from '../styles/theme';

// Re-export từ file theme chính
export { createAntdTheme } from '../styles/theme';
export { createCSSVariables } from '../styles/theme';

// Tạo theme mặc định để duy trì tương thích ngược
const theme = createAntdTheme(false);

export default theme;