// src/components/ui/StyledHeroBanner.tsx
// import React from "react";
// import { Typography } from "antd";
// import { Link } from "react-router-dom";
// import styled from "styled-components";
// import { media } from "../../styles/theme-utils";
// import StyledButton from "./StyledButton";

// const { Title, Paragraph } = Typography;

// interface HeroBannerProps {
//   title?: string;
//   subtitle?: string;
//   ctaText?: string;
//   ctaLink?: string;
//   image?: string;
//   height?: string;
//   reversed?: boolean;
// }

// // Styled components
// const BannerContainer = styled.div`
//   padding: 2rem 0;
//   background-color: ${(props) => (props.theme.isDark ? "#1e293b" : "#ffffff")};
//   transition: background-color 0.3s ease;
// `;

// const ContentContainer = styled.div<{ $reversed?: boolean }>`
//   display: flex;
//   flex-direction: column;
//   ${media.md`
//     flex-direction: ${(props) => (props.$reversed ? "row-reverse" : "row")};
//   `}
//   align-items: center;
//   max-width: 1200px;
//   margin: 0 auto;
//   min-height: ${(props) => props.$height || "80vh"};
//   gap: 2rem;
// `;

// const ContentSide = styled.div`
//   width: 100%;
//   padding: 2rem;
//   display: flex;
//   flex-direction: column;
//   justify-content: center;

//   ${media.md`
//     width: 50%;
//     padding: 3rem;
//   `}
// `;

// const ImageSide = styled.div`
//   width: 100%;
//   ${media.md`
//     width: 50%;
//   `}
//   position: relative;
//   overflow: hidden;
//   border-radius: 1rem;
//   box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
//   transform: translateY(0);
//   transition: transform 0.5s ease, box-shadow 0.5s ease;

//   &:hover {
//     transform: translateY(-8px);
//     box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
//   }
// `;

// const BannerImage = styled.img`
//   width: 100%;
//   height: 100%;
//   min-height: 300px;
//   object-fit: cover;
//   transition: transform 0.7s ease;

//   &:hover {
//     transform: scale(1.05);
//   }
// `;

// const BannerTitle = styled(Title)`
//   && {
//     color: ${(props) => (props.theme.isDark ? "#f1f5f9" : "#1e293b")};
//     font-weight: 700;
//     font-family: "Be Vietnam Pro", sans-serif;
//     margin-bottom: 1.5rem;
//     font-size: 2rem;
//     line-height: 1.2;

//     ${media.md`
//       font-size: 3rem;
//     `}

//     ${media.lg`
//       font-size: 3.5rem;
//     `}
    
//     position: relative;
//     overflow: hidden;

//     &::after {
//       content: "";
//       position: absolute;
//       left: 0;
//       bottom: -5px;
//       height: 4px;
//       width: 40px;
//       background: ${(props) => (props.theme.isDark ? "#3b82f6" : "#152C5B")};
//       transition: width 0.5s ease;
//     }

//     &:hover::after {
//       width: 100px;
//     }
//   }
// `;

// const BannerSubtitle = styled(Paragraph)`
//   && {
//     color: ${(props) => (props.theme.isDark ? "#cbd5e1" : "#4b5563")};
//     font-size: 1rem;
//     font-weight: 300;
//     margin-bottom: 2.5rem;
//     line-height: 1.6;
//     font-family: "Be Vietnam Pro", sans-serif;

//     ${media.md`
//       font-size: 1.125rem;
//     `}
//   }
// `;

// const AnimatedButton = styled(StyledButton)`
//   position: relative;
//   overflow: hidden;
//   z-index: 1;
//   padding: 0.75rem 2.5rem;
//   font-size: 1rem;
//   font-weight: 600;
//   text-transform: uppercase;
//   letter-spacing: 1px;

//   &::before {
//     content: "";
//     position: absolute;
//     top: 0;
//     left: 0;
//     width: 100%;
//     height: 100%;
//     background: linear-gradient(
//       120deg,
//       transparent,
//       ${(props) =>
//         props.theme.isDark
//           ? "rgba(59, 130, 246, 0.2)"
//           : "rgba(21, 44, 91, 0.2)"},
//       transparent
//     );
//     transform: translateX(-100%);
//     transition: 0.6s;
//     z-index: -1;
//   }

//   &:hover::before {
//     transform: translateX(100%);
//   }
// `;

// const StyledHeroBanner: React.FC<HeroBannerProps> = ({
//   // title = "LAVISHSTAY – Trải nghiệm đặt phòng tiện ích, tận hưởng kỳ nghỉ như tại chính ngôi nhà bạn",
//   // subtitle = "Vô vàn lựa chọn từ nhà nghỉ, biệt thự, homestay đến khách sạn sang trọng – Tất cả trong một hệ thống đặt phòng trực tuyến đa tiện ích",
//   // ctaText = "Khám phá ngay",
//   // ctaLink = "/",
//   // image = "/images/luxury-hotel-room-banner.jpg",
//   // height = "80vh",
//   // reversed = false,
// }) => {
//   return (
//     <BannerContainer>
//       <ContentContainer $height={height} $reversed={reversed}>
//         <ContentSide>
//           <BannerTitle
//             level={1}
//             className="animate__animated animate__fadeInLeft"
//           >
//             {title}
//           </BannerTitle>
//           <BannerSubtitle className="animate__animated animate__fadeInLeft animate__delay-0.3s">
//             {subtitle}
//           </BannerSubtitle>
//           {ctaText && (
//             <div className="animate__animated animate__fadeInUp animate__delay-0.5s">
//               <Link to={ctaLink}>
//                 <AnimatedButton type="primary" size="large">
//                   {ctaText}
//                 </AnimatedButton>
//               </Link>
//             </div>
//           )}
//         </ContentSide>
//         <ImageSide>
//           <BannerImage
//             src={image}
//             alt="LavishStay Banner"
//             className="animate__animated animate__fadeIn animate__delay-0.5s"
//           />
//         </ImageSide>
//       </ContentContainer>
//     </BannerContainer>
//   );
// };

// export default StyledHeroBanner;
