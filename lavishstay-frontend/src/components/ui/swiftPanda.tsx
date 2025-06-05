import React, { useState } from 'react';
import styled from 'styled-components';
import { Modal, Image } from 'antd';
import { getImageUrlWithFallback } from '../../utils/imageUtils';

interface HotelImage {
  id: number;
  src: string;
  alt: string;
  color: string; // RGB values for border color
}

interface SwiftPandeProps {
  images?: HotelImage[];
}

const SwiftPande: React.FC<SwiftPandeProps> = ({ images }) => {
  const [isHovered, setIsHovered] = useState(false);
  const [selectedImage, setSelectedImage] = useState<HotelImage | null>(null);
  const [isModalVisible, setIsModalVisible] = useState(false);
  // Default hotel images - 18 images with varied colors
  const defaultImages: HotelImage[] = [
    { id: 1, src: '/images/hotels/1.jpg', alt: 'Hotel LavishStay Image 1', color: '142, 249, 252' },
    { id: 2, src: '/images/hotels/2.jpg', alt: 'Hotel LavishStay Image 2', color: '142, 252, 204' },
    { id: 3, src: '/images/hotels/3.jpg', alt: 'Hotel LavishStay Image 3', color: '142, 252, 157' },
    { id: 4, src: '/images/hotels/4.jpg', alt: 'Hotel LavishStay Image 4', color: '215, 252, 142' },
    { id: 5, src: '/images/hotels/5.jpg', alt: 'Hotel LavishStay Image 5', color: '252, 252, 142' },
    { id: 6, src: '/images/hotels/6.jpg', alt: 'Hotel LavishStay Image 6', color: '252, 208, 142' },
    { id: 7, src: '/images/hotels/7.jpg', alt: 'Hotel LavishStay Image 7', color: '252, 142, 142' },
    { id: 8, src: '/images/hotels/8.jpg', alt: 'Hotel LavishStay Image 8', color: '252, 142, 239' },
    { id: 9, src: '/images/hotels/9.jpg', alt: 'Hotel LavishStay Image 9', color: '204, 142, 252' },
    { id: 10, src: '/images/hotels/10.jpg', alt: 'Hotel LavishStay Image 10', color: '142, 202, 252' },
    { id: 11, src: '/images/hotels/11.jpg', alt: 'Hotel LavishStay Image 11', color: '255, 105, 180' },
    { id: 12, src: '/images/hotels/12.jpg', alt: 'Hotel LavishStay Image 12', color: '255, 165, 0' },
    { id: 13, src: '/images/hotels/13.jpg', alt: 'Hotel LavishStay Image 13', color: '50, 205, 50' },
    { id: 14, src: '/images/hotels/14.jpg', alt: 'Hotel LavishStay Image 14', color: '255, 20, 147' },
    { id: 15, src: '/images/hotels/15.jpg', alt: 'Hotel LavishStay Image 15', color: '0, 191, 255' },
    { id: 16, src: '/images/hotels/16.jpg', alt: 'Hotel LavishStay Image 16', color: '138, 43, 226' },
    { id: 17, src: '/images/hotels/17.jpg', alt: 'Hotel LavishStay Image 17', color: '255, 69, 0' },
    { id: 18, src: '/images/hotels/18.jpg', alt: 'Hotel LavishStay Image 18', color: '34, 139, 34' }
  ]; const hotelImages = images || defaultImages;
  const quantity = hotelImages.length;

  // Handlers for image interactions
  const handleImageClick = (image: HotelImage) => {
    setSelectedImage(image);
    setIsModalVisible(true);
  };

  const handleModalClose = () => {
    setIsModalVisible(false);
    setSelectedImage(null);
  };

  const handleContainerMouseEnter = () => {
    setIsHovered(true);
  };

  const handleContainerMouseLeave = () => {
    setIsHovered(false);
  }; return (
    <StyledWrapper>
      <div className="wrapper">
        <div
          className={`inner ${isHovered ? 'paused' : ''}`}
          style={{ '--quantity': quantity } as any}
          onMouseEnter={handleContainerMouseEnter}
          onMouseLeave={handleContainerMouseLeave}
        >          {hotelImages.map((image, index) => (
          <img
            key={image.id}
            className="cardSwift"
            style={{
              '--index': index,
              '--color-cardSwift': image.color
            } as any}
            src={getImageUrlWithFallback(image.src)}
            alt={image.alt}
            onClick={() => handleImageClick(image)}
          />
        ))}
        </div>
      </div>

      {/* Modal for image preview */}
      <Modal
        open={isModalVisible}
        onCancel={handleModalClose}
        footer={null}
        width="50%"
        centered
      >        {selectedImage && (
        <Image
          src={getImageUrlWithFallback(selectedImage.src)}
          alt={selectedImage.alt}
          preview={false}
        />
      )}
      </Modal>
    </StyledWrapper>
  );
};

const StyledWrapper = styled.div`
  .wrapper {
    width: 99vw;
    height: 79vh;
    position: relative;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    margin-left: calc(-50vw + 50%);
    margin-right: calc(-50vw + 50%);
  }  .inner {
    --w: 280px;
    --h: 205px;
    --translateZ: calc((var(--w) + var(--h)) * 1.7);
    --rotateX: -15deg;
    --perspective: 2000px;
    position: absolute;
    width: var(--w);
    height: var(--h);
    top: 30%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 2;
    transform-style: preserve-3d;
    animation: rotating 30s linear infinite;
  }
  @keyframes rotating {
    from {
      transform: translate(-50%, -50%) perspective(var(--perspective)) rotateX(var(--rotateX)) rotateY(0);
    }
    to {
      transform: translate(-50%, -50%) perspective(var(--perspective)) rotateX(var(--rotateX)) rotateY(1turn);
    }
  }

  .inner.paused {
    animation-play-state: paused;
  }.cardSwift {
    position: absolute;
    border: 2px solid rgba(var(--color-cardSwift), 0.8);
    border-radius: 16px;
    overflow: hidden;
    inset: 0;
    
    transform: rotateY(calc((360deg / var(--quantity)) * var(--index)))
      translateZ(var(--translateZ));
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    transition: all 0.4s ease;
    backdrop-filter: blur(8px);
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }

  .cardSwift:hover {
    border-color: rgba(var(--color-cardSwift), 1);
    box-shadow: 0 15px 60px rgba(var(--color-cardSwift), 0.4);
    transform: rotateY(calc((360deg / var(--quantity)) * var(--index)))
      translateZ(calc(var(--translateZ) + 30px)) scale(1.05);
  }  /* Responsive Design */
  @media (max-width: 1200px) {
    .inner {
      --w: 240px;
      --h: 150px;
      --translateZ: calc((var(--w) + var(--h)) * 1.6);
    }
  }

  @media (max-width: 768px) {
    .wrapper {
      height: 80vh;
    }
    
    .inner {
      --w: 200px;
      --h: 125px;
      --translateZ: calc((var(--w) + var(--h)) * 1.4);
    }
    
    .cardSwift {
      border-width: 2px;
    }
  }

  @media (max-width: 480px) {
    .wrapper {
      height: 70vh;
    }
    
    .inner {
      --w: 160px;
      --h: 100px;
      --translateZ: calc((var(--w) + var(--h)) * 1.2);
    }
    
    .cardSwift {
      border-width: 1px;
      border-radius: 12px;
    }
  }

  @media (max-width: 360px) {
    .wrapper {
      height: 60vh;
    }
    
    .inner {
      --w: 120px;
      --h: 75px;
      --translateZ: calc((var(--w) + var(--h)) * 1.0);
    }
  }
`;

export default SwiftPande;