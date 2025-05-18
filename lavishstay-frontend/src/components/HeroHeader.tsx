// import React from "react";
// import { Typography, Button, Space } from "antd";
// import { ArrowRightOutlined } from "@ant-design/icons";

// const { Title, Paragraph } = Typography;

// interface HeroHeaderProps {
//   title: string;
//   subtitle: string;
//   ctaText?: string;
//   onCtaClick?: () => void;
//   backgroundImage?: string;
// }

// const HeroHeader: React.FC<HeroHeaderProps> = ({
//   title,
//   subtitle,
//   ctaText = "Khám phá ngay",
//   onCtaClick,
//   backgroundImage = "https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1760&q=80",
// }) => {
//   return (
//     <div
//       className="relative overflow-hidden rounded-xl mb-10"
//       style={{
//         height: "500px",
//         backgroundImage: `url(${backgroundImage})`,
//         backgroundSize: "cover",
//         backgroundPosition: "center",
//       }}
//     >
//       <div
//         className="absolute inset-0"
//         style={{ backgroundColor: "rgba(0, 0, 0, 0.5)" }}
//       ></div>
//       <div className="absolute inset-0 flex flex-col justify-center items-start px-10 md:px-20">
//         <div className="max-w-xl">
//           <Title level={1} className="text-white mb-6 font-bevietnam font-bold">
//             {title}
//           </Title>
//           <Paragraph className="text-white text-lg mb-8 font-bevietnam">
//             {subtitle}
//           </Paragraph>
//           <Space>
//             <Button
//               type="primary"
//               size="large"
//               className="font-bevietnam font-medium"
//               onClick={onCtaClick}
//               icon={<ArrowRightOutlined />}
//             >
//               {ctaText}
//             </Button>
//           </Space>
//         </div>
//       </div>
//     </div>
//   );
// };

// export default HeroHeader;
