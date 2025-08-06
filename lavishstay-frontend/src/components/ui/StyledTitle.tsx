// src/components/ui/StyledTitle.tsx
import React from "react";
import styled, { useTheme } from "styled-components";

interface StyledTitleProps {
  text: string;
  className?: string;
  animationColor?: string;
  fontSize?: string;
  strokeColor?: string;
  autoFill?: boolean;
}

const StyledTitle: React.FC<StyledTitleProps> = ({
  text,
  className = "",
  animationColor,
  fontSize = "2em",
  strokeColor,
  autoFill = false,
}) => {
  const theme = useTheme() as any;
  const isDarkMode = theme?.isDark;

  // Điều chỉnh màu sắc dựa trên light/dark mode
  const defaultAnimationColor = isDarkMode ? "#60a5fa" : "#3b82f6";
  const defaultStrokeColor = isDarkMode
    ? "rgba(255, 255, 255, 0.8)"
    : "rgba(21, 44, 91, 0.7)";

  // Sử dụng giá trị mặc định nếu không có props được truyền vào
  const finalAnimationColor = animationColor || defaultAnimationColor;
  const finalStrokeColor = strokeColor || defaultStrokeColor;

  return (
    <StyledWrapper
      className={`${className} ${autoFill ? "auto-fill" : ""}`}
      $animationColor={finalAnimationColor}
      $fontSize={fontSize}
      $strokeColor={finalStrokeColor}
      $isDarkMode={isDarkMode}
    >
      <span className="title" data-text={text}>
        <span className="actual-text">&nbsp;{text}&nbsp;</span>
        <span aria-hidden="true" className="hover-text">
          &nbsp;{text}&nbsp;
        </span>
      </span>
    </StyledWrapper>
  );
};

const StyledWrapper = styled.div<{
  $animationColor: string;
  $fontSize: string;
  $strokeColor: string;
  $isDarkMode: boolean;
}>`
  .title {
    --border-right: 6px;
    --text-stroke-color: ${(props) => props.$strokeColor};
    --animation-color: ${(props) => props.$animationColor};
    --fs-size: ${(props) => props.$fontSize};
    letter-spacing: 10px;
    text-decoration: none;
    font-size: var(--fs-size);
    font-family: "Be Vietnam Pro", sans-serif;
    position: relative;
    // text-transform: uppercase;
    color: transparent;
    -webkit-text-stroke: 1px var(--text-stroke-color);
    display: inline-block;
    text-shadow: ${(props) =>
      props.$isDarkMode ? "0 0 8px rgba(255, 255, 255, 0.3)" : "none"};
  }

  /* this is the text, when you hover */
  .hover-text {
    position: absolute;
    box-sizing: border-box;
    content: attr(data-text);
    color: var(--animation-color);
    width: 0%;
    inset: 0;
    border-right: var(--border-right) solid var(--animation-color);
    overflow: hidden;
    transition: 0.5s;
    -webkit-text-stroke: 1px var(--animation-color);
  }

  /* hover */
  .title:hover .hover-text {
    width: 100%;
    filter: drop-shadow(0 0 23px var(--animation-color));
  }

  /* Auto fill mode */
  &.auto-fill .hover-text {
    width: 102%;
    filter: drop-shadow(0 0 23px var(--animation-color));
  }

 
`;

export default StyledTitle;
