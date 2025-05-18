import React from "react";
import { Card, Avatar, Typography, Rate } from "antd";
import { UserOutlined, CommentOutlined } from "@ant-design/icons";

const { Title, Paragraph, Text } = Typography;

interface TestimonialProps {
  name: string;
  avatar?: string;
  rating: number;
  comment: string;
  date: string;
  location?: string;
}

const Testimonial: React.FC<TestimonialProps> = ({
  name,
  avatar,
  rating,
  comment,
  date,
  location,
}) => {
  return (
    <Card
      className="h-full relative testimonial-card overflow-hidden transition-all duration-300 hover:shadow-lg"
      bordered={false}
      bodyStyle={{ padding: "24px" }}
    >
      <div className="absolute top-4 right-4 opacity-10">
        <CommentOutlined style={{ fontSize: "64px", color: "#1890ff" }} />
      </div>

      <div className="flex items-center mb-4">
        <Avatar
          size={50}
          src={avatar}
          icon={!avatar ? <UserOutlined /> : undefined}
          className="mr-4 border-2 border-blue-500"
        />
        <div>
          <Title level={5} className="font-bevietnam font-semibold m-0">
            {name}
          </Title>
          {location && (
            <Text className="font-bevietnam text-gray-500">{location}</Text>
          )}
        </div>
      </div>

      <div className="mb-4">
        <Rate disabled defaultValue={rating} className="text-sm" />
      </div>

      <Paragraph
        className="font-bevietnam text-gray-700 mb-4"
        style={{
          minHeight: "80px",
          position: "relative",
          zIndex: 1,
        }}
      >
        "{comment}"
      </Paragraph>

      <Text className="font-bevietnam text-gray-500 text-sm">{date}</Text>
    </Card>
  );
};

export default Testimonial;
