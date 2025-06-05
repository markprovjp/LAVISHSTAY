import React, { useState } from "react";

import {
  Card,
  Avatar,
  Typography,
  Rate,
  Carousel,
  Button,
  ConfigProvider,
  theme,
} from "antd";
import {
  UserOutlined,
  CommentOutlined,
  LeftOutlined,
  RightOutlined,
} from "@ant-design/icons";

const { Title, Paragraph, Text } = Typography;

interface TestimonialItemProps {
  name: string;
  avatar?: string;
  rating: number;
  comment: string;
  date: string;
  location?: string;
  tour?: string;
  position?: string;
}

export interface TestimonialsProps {
  testimonials: TestimonialItemProps[];
  className?: string;
  style?: React.CSSProperties;
}

const TestimonialItem: React.FC<TestimonialItemProps> = ({
  name,
  avatar,
  rating,
  comment,
  date,
  location,
  tour,
  position,
}) => {
  const { token } = theme.useToken();

  return (
    <Card
      className="testimonial-card overflow-hidden transition-all duration-300"
      variant="borderless"
      style={{
        borderRadius: token.borderRadius * 2,
        height: "100%",
        boxShadow: token.boxShadowTertiary,
        background: token.colorBgContainer,
      }}

      styles={{ body: { padding: 24 } }}
    >
      <div className="absolute top-4 right-4 opacity-10">
        <CommentOutlined
          style={{ fontSize: "64px", color: token.colorPrimary }}
        />
      </div>

      <div className="flex items-center mb-6">
        <Avatar
          size={64}
          src={avatar}
          icon={!avatar ? <UserOutlined /> : undefined}
          style={{
            marginRight: "16px",
            border: `4px solid ${token.colorPrimaryBg}`,
          }}
        />
        <div>
          <Title
            level={5}
            style={{
              margin: 0,
              color: token.colorTextBase,
              fontFamily: token.fontFamily,
              fontWeight: 600,
            }}
          >
            {name}
          </Title>

          {position && (
            <Text style={{ color: token.colorTextSecondary }}>{position}</Text>
          )}

          {location && !position && (
            <Text style={{ color: token.colorTextSecondary }}>{location}</Text>
          )}

          {tour && (
            <Text
              style={{
                color: token.colorPrimary,
                fontSize: "0.875rem",
                display: "block",
                marginTop: "4px",
              }}
            >
              {tour}
            </Text>
          )}

          <div className="mt-2">
            <Rate
              disabled
              defaultValue={rating}
              style={{ fontSize: "0.875rem" }}
            />
          </div>
        </div>
      </div>

      <Paragraph
        style={{
          color: token.colorTextBase,
          fontStyle: "italic",
          position: "relative",
          zIndex: 1,
          marginBottom: "16px",
          minHeight: "80px",
        }}
      >
        "{comment}"
      </Paragraph>

      {date && (
        <Text style={{ color: token.colorTextSecondary, fontSize: "0.875rem" }}>
          {date}
        </Text>
      )}
    </Card>
  );
};

const Testimonial: React.FC<TestimonialsProps> = ({
  testimonials,
  className = "",
  style = {},
}) => {
  const { token } = theme.useToken();
  const carouselRef = React.useRef<any>(null);
  const [activeIndex, setActiveIndex] = useState(0);

  const handleNext = () => {
    carouselRef.current?.next();
    setActiveIndex((prev) => (prev === testimonials.length - 1 ? 0 : prev + 1));
  };

  const handlePrev = () => {
    carouselRef.current?.prev();
    setActiveIndex((prev) => (prev === 0 ? testimonials.length - 1 : prev - 1));
  };

  const goToSlide = (index: number) => {
    carouselRef.current?.goTo(index);
    setActiveIndex(index);
  };

  return (
    <ConfigProvider theme={{ token }}>
      <div
        className={`testimonials-container relative ${className}`}
        style={style}
      >
        <div className="carousel-container relative">
          <Carousel
            ref={carouselRef}
            dots={false}
            autoplay
            autoplaySpeed={5000}
            effect="fade"
            beforeChange={(_, next) => setActiveIndex(next)}
          >
            {testimonials.map((testimonial, index) => (
              <div key={index} className="px-6 py-4">
                <div className="max-w-3xl mx-auto">
                  <TestimonialItem {...testimonial} />
                </div>
              </div>
            ))}
          </Carousel>

          {/* Navigation buttons */}
          <Button
            shape="circle"
            icon={<LeftOutlined />}
            onClick={handlePrev}
            className="absolute left-4 top-1/2 -translate-y-1/2 z-10"
            style={{
              boxShadow: token.boxShadowSecondary,
              background: token.colorBgContainer,
              borderColor: token.colorBorderSecondary,
            }}
          />

          <Button
            shape="circle"
            icon={<RightOutlined />}
            onClick={handleNext}
            className="absolute right-4 top-1/2 -translate-y-1/2 z-10"
            style={{
              boxShadow: token.boxShadowSecondary,
              background: token.colorBgContainer,
              borderColor: token.colorBorderSecondary,
            }}
          />
        </div>

        {/* Indicators */}
        <div className="flex justify-center mt-6 space-x-2">
          {testimonials.map((_, index) => (
            <Button
              key={index}
              type="text"
              size="small"
              onClick={() => goToSlide(index)}
              style={{
                width: "12px",
                height: "12px",
                borderRadius: "50%",
                minWidth: "unset",
                padding: 0,
                background:
                  index === activeIndex
                    ? token.colorPrimary
                    : token.colorBorderSecondary,
              }}
              aria-label={`Go to testimonial ${index + 1}`}
            />
          ))}
        </div>
      </div>
    </ConfigProvider>
  );
};

export default Testimonial;
