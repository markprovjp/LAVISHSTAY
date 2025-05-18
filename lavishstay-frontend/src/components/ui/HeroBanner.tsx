import React from 'react';
import { Typography, Button } from 'antd';
import { ArrowRightOutlined } from '@ant-design/icons';
import { Link } from 'react-router-dom';

const { Title, Paragraph } = Typography;

interface HeroBannerProps {
  title: string;
  subtitle: string;
  ctaText?: string;
  ctaLink?: string;
  backgroundImage: string;
  height?: string;
}

const HeroBanner: React.FC<HeroBannerProps> = ({
  title,
  subtitle,
  ctaText,
  ctaLink = '/',
  backgroundImage,
  height = '70vh',
}) => {
  return (
    <div
      className="relative bg-cover bg-center flex items-center justify-center"
      style={{
        backgroundImage: `url(${backgroundImage})`,
        minHeight: height,
      }}
    >
      <div className="absolute inset-0 bg-black opacity-50"></div>
      <div className="relative z-10 text-center text-white p-8 max-w-3xl mx-auto">
        <Title level={1} className="!text-white font-bevietnam font-bold text-4xl md:text-5xl lg:text-6xl mb-4 animate__animated animate__fadeInDown">
          {title}
        </Title>
        <Paragraph className="text-lg md:text-xl font-bevietnam mb-8 animate__animated animate__fadeInUp animate__delay-0.5s">
          {subtitle}
        </Paragraph>
        {ctaText && (
          <Link to={ctaLink}>
            <Button
              type="primary"
              size="large"
              icon={<ArrowRightOutlined />}
              className="font-bevietnam font-semibold animate__animated animate__fadeInUp animate__delay-1s"
            >
              {ctaText}
            </Button>
          </Link>
        )}
      </div>
    </div>
  );
};

export default HeroBanner;
