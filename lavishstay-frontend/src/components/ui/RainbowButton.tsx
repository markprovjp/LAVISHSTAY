import React from 'react';
import styled from 'styled-components';

interface RainbowButtonProps {
    children: React.ReactNode;
    onClick?: () => void;
    className?: string;
    size?: 'small' | 'medium' | 'large';
    disabled?: boolean;
}

const RainbowButton: React.FC<RainbowButtonProps> = ({
    children,
    onClick,
    className = '',
    size = 'medium',
    disabled = false
}) => {
    return (
        <StyledWrapper className={className}>
            <button
                data-label={children}
                className={`rainbow-hover ${size} ${disabled ? 'disabled' : ''}`}
                onClick={onClick}
                disabled={disabled}
            >
                <span className="sp">{children}</span>
            </button>
        </StyledWrapper>
    );
};

const StyledWrapper = styled.div`
  .rainbow-hover {
    font-weight: 700;
    color: #ff7576;
    background-color: #2B3044;
    border: none;
    outline: none;
    cursor: pointer;
    position: relative;
    border-radius: 12px;
    box-shadow: 0px 2px 4px rgba(43, 48, 68, 0.3),
      0px 8px 24px rgba(43, 48, 68, 0.2);
    transform-style: preserve-3d;
    transform: scale(var(--s, 1)) perspective(600px)
      rotateX(var(--rx, 0deg)) rotateY(var(--ry, 0deg));
    perspective: 600px;
    transition: all 0.2s ease;
    overflow: hidden;
  }

  &.w-full .rainbow-hover {
    width: 100%;
  }

  .rainbow-hover.small {
    font-size: 14px;
    padding: 8px 16px;
    line-height: 20px;
  }

  .rainbow-hover.medium {
    font-size: 16px;
    padding: 12px 24px;
    line-height: 24px;
  }

  .rainbow-hover.large {
    font-size: 18px;
    padding: 16px 32px;
    line-height: 28px;
  }

  .sp {
    background: linear-gradient(
        90deg,
        #866ee7,
        #ea60da,
        #ed8f57,
        #fbd41d,
        #2cca91,
        #866ee7
      );
    background-size: 200% 100%;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    display: block;
    animation: rainbow-slide 3s ease-in-out infinite;
  }

  .rainbow-hover:hover {
    transform: scale(1.05) perspective(600px);
    box-shadow: 0px 4px 8px rgba(43, 48, 68, 0.4),
      0px 12px 32px rgba(43, 48, 68, 0.3);
  }

  .rainbow-hover:hover .sp {
    animation-duration: 0.8s;
  }

  .rainbow-hover:active {
    transition: 0.1s;
    transform: scale(0.95) perspective(600px);
  }

  .rainbow-hover.disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
  }

  .rainbow-hover.disabled .sp {
    animation: none;
    background: linear-gradient(90deg, #999, #666);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  @keyframes rainbow-slide {
    0%, 100% {
      background-position: 0% 50%;
    }
    50% {
      background-position: 100% 50%;
    }
  }

  /* Dark mode support */
  @media (prefers-color-scheme: dark) {
    .rainbow-hover {
      background-color: #1a1d29;
      box-shadow: 0px 2px 4px rgba(26, 29, 41, 0.4),
        0px 8px 24px rgba(26, 29, 41, 0.3);
    }
    
    .rainbow-hover:hover {
      box-shadow: 0px 4px 8px rgba(26, 29, 41, 0.5),
        0px 12px 32px rgba(26, 29, 41, 0.4);
    }
  }
`;

export default RainbowButton;
