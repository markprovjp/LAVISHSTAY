import React from "react";
import styled from "styled-components";
import { SearchOutlined } from "@ant-design/icons";

interface ButtonSearchProps {
  text?: string;
  onClick?: () => void;
  className?: string;
  style?: React.CSSProperties;
  icon?: React.ReactNode;
  type?: "submit" | "button" | "reset";
}

const ButtonSearch: React.FC<ButtonSearchProps> = ({
  text = "Tìm kiếm",
  onClick,
  className = "",
  style = {},
  icon = <SearchOutlined className="search-icon text-xl" />,
  type = "submit",
}) => {
  return (
    <StyledWrapper style={style} className={className}>
      <button className="button-home-search" onClick={onClick} type={type}>
        <div className="icon-container">
          <div className="icon-wrapper">{icon}</div>
        </div>
        <span className="button-text">{text}</span>
      </button>
    </StyledWrapper>
  );
};


const StyledWrapper = styled.div`
  .button-home-search {
    position: relative;
    font-size: 18px;
    font-weight: 600;
    background: var(--color-primary, #152C5B);
    color: white;
    padding: 0 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    cursor: pointer;
    height: 50px;
    width: 100%;
    box-shadow: 0 4px 6px rgba(21, 44, 91, 0.1);
  }

  .icon-container {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .icon-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .search-icon {
    font-size: 22px;
  }

  .button-text {
    position: absolute;
    right: 1.5rem;
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
  }

  .button-home-search:hover {
    background: var(--color-primary-hover, #1e3a8a);
    box-shadow: 0 6px 12px rgba(21, 44, 91, 0.15);
  }

  .button-home-search:hover .icon-container {
    left: 1.5rem;
    transform: translateX(0);
    animation: pulse 1.5s infinite;
  }

  .button-home-search:hover .button-text {
    opacity: 1;
    transform: translateX(0);
  }

  .button-home-search:active {
    transform: scale(0.97);
  }

  @keyframes pulse {
    0% {
      transform: translateX(0) scale(1);
    }
    50% {
      transform: translateX(0) scale(1.05);
    }
    100% {
      transform: translateX(0) scale(1);
    }
  }

  @keyframes slide-right {
    0% {
      transform: translateX(0);
      opacity: 0;
    }
    100% {
      transform: translateX(0);
      opacity: 1;
    }
  }

  /* Responsive adjustments */
  @media (max-width: 768px) {
    .button-home-search {
      font-size: 16px;
    }
    
    .search-icon {
      font-size: 20px;
    }
  }
`;


export default ButtonSearch;
