// src/components/ui/StyledButton.tsx
import React from "react";
import styled from "styled-components";
import { Button } from "antd";
import type { ButtonProps } from "antd";

interface StyledButtonProps extends ButtonProps {
  variant?: "primary" | "secondary" | "outline" | "text";
  $fullWidth?: boolean;
}

// Base styled button
const StyledButtonBase = styled(Button)<StyledButtonProps>`
  border-radius: 8px;
  font-weight: 500;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
  width: ${(props) => (props.$fullWidth ? "100%" : "auto")};

  &::before {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s ease, height 0.6s ease;
  }

  &:hover::before {
    width: 300%;
    height: 300%;
  }

  &:active {
    transform: translateY(1px);
  }
`;

// Primary variant
const PrimaryButton = styled(StyledButtonBase)`
  background: ${(props) => (props.theme.isDark ? "#3b82f6" : "#152C5B")};
  color: white;
  border: none;

  &:hover {
    background: ${(props) => (props.theme.isDark ? "#60a5fa" : "#1e40af")};
    color: white;
  }

  &:focus {
    background: ${(props) => (props.theme.isDark ? "#3b82f6" : "#152C5B")};
    color: white;
    box-shadow: 0 0 0 3px
      ${(props) =>
        props.theme.isDark
          ? "rgba(59, 130, 246, 0.3)"
          : "rgba(21, 44, 91, 0.3)"};
  }

  &:disabled {
    background: #94a3b8;
    color: #f1f5f9;
  }
`;

// Secondary variant
const SecondaryButton = styled(StyledButtonBase)`
  background: ${(props) => (props.theme.isDark ? "#475569" : "#e2e8f0")};
  color: ${(props) => (props.theme.isDark ? "#f1f5f9" : "#1e293b")};
  border: none;

  &:hover {
    background: ${(props) => (props.theme.isDark ? "#64748b" : "#cbd5e1")};
  }

  &:focus {
    box-shadow: 0 0 0 3px
      ${(props) =>
        props.theme.isDark
          ? "rgba(71, 85, 105, 0.3)"
          : "rgba(226, 232, 240, 0.3)"};
  }
`;

// Outline variant
const OutlineButton = styled(StyledButtonBase)`
  background: transparent;
  color: ${(props) => (props.theme.isDark ? "#3b82f6" : "#152C5B")};
  border: 2px solid ${(props) => (props.theme.isDark ? "#3b82f6" : "#152C5B")};

  &:hover {
    background: ${(props) =>
      props.theme.isDark ? "rgba(59, 130, 246, 0.1)" : "rgba(21, 44, 91, 0.1)"};
  }

  &:focus {
    box-shadow: 0 0 0 3px
      ${(props) =>
        props.theme.isDark
          ? "rgba(59, 130, 246, 0.2)"
          : "rgba(21, 44, 91, 0.2)"};
  }
`;

// Text variant
const TextButton = styled(StyledButtonBase)`
  background: transparent;
  color: ${(props) => (props.theme.isDark ? "#3b82f6" : "#152C5B")};
  border: none;
  padding: 0;
  height: auto;

  &:hover {
    color: ${(props) => (props.theme.isDark ? "#60a5fa" : "#1e40af")};
    background: transparent;
  }

  &::before {
    display: none;
  }
`;

const StyledButton: React.FC<StyledButtonProps> = ({
  variant = "primary",
  $fullWidth = false,
  children,
  ...props
}) => {
  switch (variant) {
    case "secondary":
      return (
        <SecondaryButton $fullWidth={$fullWidth} {...props}>
          {children}
        </SecondaryButton>
      );
    case "outline":
      return (
        <OutlineButton $fullWidth={$fullWidth} {...props}>
          {children}
        </OutlineButton>
      );
    case "text":
      return (
        <TextButton $fullWidth={$fullWidth} {...props}>
          {children}
        </TextButton>
      );
    case "primary":
    default:
      return (
        <PrimaryButton $fullWidth={$fullWidth} {...props}>
          {children}
        </PrimaryButton>
      );
  }
};

export default StyledButton;
