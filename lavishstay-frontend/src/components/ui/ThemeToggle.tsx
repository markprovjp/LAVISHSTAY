import React from "react";
import { Segmented, Tooltip } from "antd";
import { MoonOutlined, SunOutlined } from "@ant-design/icons";
import { useThemeMode } from "../../hooks";

interface ThemeToggleProps {
  className?: string;
  tooltipPlacement?: "top" | "bottom" | "left" | "right";
  size?: "large" | "middle" | "small";
}

/**
 * Component nút chuyển đổi giữa dark/light mode
 */
const ThemeToggle: React.FC<ThemeToggleProps> = ({
  className = "",
  tooltipPlacement = "bottom",
  size = "small",
}) => {
  const { isDarkMode, toggleTheme } = useThemeMode();
  return (
    <Tooltip
      title={isDarkMode ? "Chuyển sang chế độ sáng" : "Chuyển sang chế độ tối"}
      placement={tooltipPlacement}
    >
      <Segmented
        value={isDarkMode ? "dark" : "light"}
        className={`theme-toggle ${className}` }
        size={size}
        shape="round"
        onChange={(value) => {
          if (
            (value === "dark" && !isDarkMode) ||
            (value === "light" && isDarkMode)
          ) {
            toggleTheme();
          }
        }}
        options={[
          {
            value: "light",
            icon: <SunOutlined />,
            label: "",
          },
          {
            value: "dark",
            icon: <MoonOutlined />,
            label: "",
          },
        ]}
      />
    </Tooltip>
  );
};

export default ThemeToggle;
