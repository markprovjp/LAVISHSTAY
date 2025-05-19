import React from "react";
import { Tooltip } from "antd";
import { MoonOutlined, SunOutlined } from "@ant-design/icons";
import { useThemeMode } from "../../hooks";
import styled from "styled-components";

interface ThemeToggleProps {
  className?: string;
  tooltipPlacement?: "top" | "bottom" | "left" | "right";
  size?: "large" | "middle" | "small";
}

// Styled components for the theme toggle
const ToggleContainer = styled.div<{ $isDarkMode: boolean; $size: string }>`
  position: relative;
  display: flex;
  width: ${(props) =>
    props.$size === "large"
      ? "80px"
      : props.$size === "middle"
      ? "64px"
      : "56px"};
  height: ${(props) =>
    props.$size === "large"
      ? "36px"
      : props.$size === "middle"
      ? "32px"
      : "28px"};
  border-radius: 50px;
  background: ${(props) => (props.$isDarkMode ? "#0f172a" : "#f1f5f9")};
  border: 2px solid ${(props) => (props.$isDarkMode ? "#3b82f6" : "#152C5B")};
  cursor: pointer;
  padding: 2px;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
  overflow: hidden;

  &:hover {
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.25);
  }
`;

const ToggleThumb = styled.div<{ $isDarkMode: boolean; $size: string }>`
  position: absolute;
  top: 0px;
  left: ${(props) =>
    props.$isDarkMode
      ? props.$size === "large"
        ? "calc(100% - 32px)"
        : props.$size === "middle"
        ? "calc(100% - 28px)"
        : "calc(100% - 24px)"
      : "0px"};
  width: ${(props) =>
    props.$size === "large"
      ? "32px"
      : props.$size === "middle"
      ? "28px"
      : "24px"};
  height: ${(props) =>
    props.$size === "large"
      ? "32px"
      : props.$size === "middle"
      ? "28px"
      : "24px"};
  border-radius: 50%;
  background: ${(props) => (props.$isDarkMode ? "#3b82f6" : "#152C5B")};
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55);
  color: #fff;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
  z-index: 2;
`;

const IconsContainer = styled.div`
  display: flex;
  width: 100%;
  justify-content: space-between;
  align-items: center;
  padding: 0 6px;
  position: relative;
  z-index: 1;
`;

const IconWrapper = styled.div<{ $active: boolean; $isDarkMode: boolean }>`
  color: ${(props) =>
    props.$active ? (props.$isDarkMode ? "#3b82f6" : "#152C5B") : "#9ca3af"};
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color 0.3s ease;
`;

/**
 * Component nút chuyển đổi giữa dark/light mode với thiết kế styled-components
 */
const ThemeToggle: React.FC<ThemeToggleProps> = ({
  className = "",
  tooltipPlacement = "bottom",
  size = "small",
}) => {
  const { isDarkMode, toggleTheme } = useThemeMode();

  const handleToggle = () => {
    toggleTheme();
  };

  // Map size to CSS value
  const getSizeValue = () => {
    switch (size) {
      case "large":
        return "large";
      case "middle":
        return "middle";
      default:
        return "small";
    }
  };

  return (
    <Tooltip
      title={isDarkMode ? "Chuyển sang chế độ sáng" : "Chuyển sang chế độ tối"}
      placement={tooltipPlacement}
    >
      <ToggleContainer
        $isDarkMode={isDarkMode}
        $size={getSizeValue()}
        className={className}
        onClick={handleToggle}
        data-testid="theme-toggle"
      >
        <ToggleThumb $isDarkMode={isDarkMode} $size={getSizeValue()}>
          {isDarkMode ? (
            <MoonOutlined style={{ fontSize: "14px" }} />
          ) : (
            <SunOutlined style={{ fontSize: "14px" }} />
          )}
        </ToggleThumb>
        <IconsContainer>
          <IconWrapper $active={!isDarkMode} $isDarkMode={isDarkMode}>
            <SunOutlined />
          </IconWrapper>
          <IconWrapper $active={isDarkMode} $isDarkMode={isDarkMode}>
            <MoonOutlined />
          </IconWrapper>
        </IconsContainer>
      </ToggleContainer>
    </Tooltip>
  );
};

export default ThemeToggle;
