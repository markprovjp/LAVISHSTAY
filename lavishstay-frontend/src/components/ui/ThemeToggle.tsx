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
  align-items: center;
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
  padding: 3px;  transition: background 0.3s ease, border-color 0.3s ease;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  will-change: background, border-color;
  transform: translateZ(0); /* Force hardware acceleration */

  &:hover {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
  }

  &:active {
    transform: translateZ(0) scale(0.98);
    transition: transform 0.1s ease;
  }
`;

const ToggleThumb = styled.div<{ $isDarkMode: boolean; $size: string }>`
  position: absolute;
  top: 50%;
  left: ${(props) => {
    const thumbSize =
      props.$size === "large" ? 30 : props.$size === "middle" ? 26 : 22;
    const containerWidth =
      props.$size === "large" ? 80 : props.$size === "middle" ? 64 : 56;
    const padding = 3;
    return props.$isDarkMode
      ? `${containerWidth - thumbSize - padding}px`
      : `${padding}px`;
  }};
  width: ${(props) =>
    props.$size === "large"
      ? "30px"
      : props.$size === "middle"
        ? "26px"
        : "22px"};
  height: ${(props) =>
    props.$size === "large"
      ? "30px"
      : props.$size === "middle"
        ? "26px"
        : "22px"};
  border-radius: 50%;
  background: ${(props) => (props.$isDarkMode ? "#3b82f6" : "#152C5B")};
  display: flex;
  align-items: center;
  justify-content: center;  transition: left 0.3s cubic-bezier(0.4, 0, 0.2, 1), background 0.3s ease;
  color: #fff;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
  z-index: 2;
  transform: translateY(-50%) translateZ(0);
  will-change: left;
`;

const IconsContainer = styled.div`
  display: flex;
  width: 100%;
  justify-content: space-between;
  align-items: center;
  padding: 0 8px;
  position: relative;
  z-index: 1;
  height: 100%;
`;

const IconWrapper = styled.div<{ $active: boolean; $isDarkMode: boolean }>`
  color: ${(props) =>
    props.$active ? "transparent" : props.$isDarkMode ? "#64748b" : "#94a3b8"};
  display: flex;
  align-items: center;
  justify-content: center;
  transition: color 0.2s ease;
  font-size: 12px;
  opacity: ${(props) => (props.$active ? 0.3 : 0.7)};
`;

/**
 * Component nút chuyển đổi giữa dark/light mode với thiết kế styled-components
 */
const ThemeToggle: React.FC<ThemeToggleProps> = ({
  className = "",
  tooltipPlacement = "bottom",
  size = "small",
}) => {
  const { isDarkMode, toggle } = useThemeMode(); const handleToggle = (event: React.MouseEvent<HTMLDivElement>) => {
    event.preventDefault();
    event.stopPropagation();

    // Prevent any potential side effects that might trigger routing
    try {
      toggle();
    } catch (error) {
      console.error('Error toggling theme:', error);
    }
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
      title={isDarkMode ? "Chuyển chế độ sáng" : "Chuyển chế độ tối"}
      placement={tooltipPlacement}
    >      <ToggleContainer
      $isDarkMode={isDarkMode}
      $size={getSizeValue()}
      className={className}
      onClick={handleToggle}
      data-testid="theme-toggle"
      role="switch"
      aria-checked={isDarkMode}
      aria-label={isDarkMode ? "Switch to light mode" : "Switch to dark mode"}
      tabIndex={0} onKeyDown={(e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          e.stopPropagation();

          // Create a synthetic mouse event for consistency
          const syntheticEvent = {
            preventDefault: () => { },
            stopPropagation: () => { },
          } as React.MouseEvent<HTMLDivElement>;

          handleToggle(syntheticEvent);
        }
      }}
    ><ToggleThumb $isDarkMode={isDarkMode} $size={getSizeValue()}>
          {isDarkMode ? (
            <MoonOutlined style={{ fontSize: "12px" }} />
          ) : (
            <SunOutlined style={{ fontSize: "12px" }} />
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
