// src/components/ui/StyledCard.tsx
import React from "react";
import styled from "styled-components";
import { Card } from "antd";
import type { CardProps } from "antd";

interface StyledCardProps extends CardProps {
  $elevated?: boolean;
  $interactive?: boolean;
  $variant?: "default" | "outline" | "transparent";
  $padding?: "small" | "medium" | "large" | "none";
}

// Get padding based on size
const getPadding = (padding: string | undefined) => {
  switch (padding) {
    case "small":
      return "12px";
    case "medium":
      return "20px";
    case "large":
      return "32px";
    case "none":
      return "0px";
    default:
      return "16px";
  }
};

// Base styled card
const BaseCard = styled(Card)<StyledCardProps>`
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;

  .ant-card-body {
    padding: ${(props) => getPadding(props.$padding)};
  }

  ${(props) =>
    props.$interactive &&
    `
    cursor: pointer;
    
    &:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, ${
        props.theme.isDark ? "0.3" : "0.1"
      });
    }
    
    &:active {
      transform: translateY(-2px);
    }
  `}
`;

// Default variant
const DefaultCard = styled(BaseCard)`
  background: ${(props) => (props.theme.isDark ? "#1e293b" : "#ffffff")};
  border: none;
  box-shadow: ${(props) =>
    props.$elevated
      ? props.theme.isDark
        ? "0 10px 25px rgba(0, 0, 0, 0.3)"
        : "0 10px 25px rgba(0, 0, 0, 0.08)"
      : props.theme.isDark
      ? "0 4px 12px rgba(0, 0, 0, 0.2)"
      : "0 4px 12px rgba(0, 0, 0, 0.05)"};
`;

// Outline variant
const OutlineCard = styled(BaseCard)`
  background: ${(props) => (props.theme.isDark ? "#1e293b" : "#ffffff")};
  border: 2px solid ${(props) => (props.theme.isDark ? "#334155" : "#e2e8f0")};
  box-shadow: none;

  ${(props) =>
    props.$interactive &&
    `
    &:hover {
      border-color: ${props.theme.isDark ? "#3b82f6" : "#152C5B"};
    }
  `}
`;

// Transparent variant
const TransparentCard = styled(BaseCard)`
  background: ${(props) =>
    props.theme.isDark ? "rgba(30, 41, 59, 0.6)" : "rgba(255, 255, 255, 0.6)"};
  backdrop-filter: blur(8px);
  border: 1px solid
    ${(props) =>
      props.theme.isDark
        ? "rgba(51, 65, 85, 0.6)"
        : "rgba(226, 232, 240, 0.6)"};
  box-shadow: ${(props) =>
    props.$elevated
      ? props.theme.isDark
        ? "0 10px 25px rgba(0, 0, 0, 0.2)"
        : "0 10px 25px rgba(0, 0, 0, 0.05)"
      : "none"};
`;

const StyledCard: React.FC<StyledCardProps> = ({
  $elevated = false,
  $interactive = false,
  $variant = "default",
  $padding = "medium",
  children,
  ...props
}) => {
  switch ($variant) {
    case "outline":
      return (
        <OutlineCard
          $elevated={$elevated}
          $interactive={$interactive}
          $padding={$padding}
          {...props}
        >
          {children}
        </OutlineCard>
      );
    case "transparent":
      return (
        <TransparentCard
          $elevated={$elevated}
          $interactive={$interactive}
          $padding={$padding}
          {...props}
        >
          {children}
        </TransparentCard>
      );
    case "default":
    default:
      return (
        <DefaultCard
          $elevated={$elevated}
          $interactive={$interactive}
          $padding={$padding}
          {...props}
        >
          {children}
        </DefaultCard>
      );
  }
};

export default StyledCard;
