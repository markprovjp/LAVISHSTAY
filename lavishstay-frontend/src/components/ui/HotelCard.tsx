import React from "react";
import { Card, Rate, Typography, Tag, Space } from "antd";
import {
  HeartOutlined,
  EnvironmentOutlined,
  HomeOutlined,
} from "@ant-design/icons";

const { Meta } = Card;
const { Text } = Typography;

interface HotelCardProps {
  id: number;
  title: string;
  location: string;
  price: number;
  rating: number;
  image: string;
  category?: string;
  features?: string[];
  onClick?: () => void;
}

const HotelCard: React.FC<HotelCardProps> = ({
  title,
  location,
  price,
  rating,
  image,
  category = "Khách sạn",
  features = [],
  onClick,
}) => {
  return (
    <Card
      hoverable
      className="overflow-hidden hotel-card transition-all duration-300 hover:shadow-xl"
      cover={
        <div className="relative" style={{ height: "220px" }}>
          <img
            alt={title}
            src={image}
            className="w-full h-full object-cover transition-transform duration-500 hover:scale-105"
          />
          <div className="absolute top-3 left-3">
            <Tag color="blue" className="font-bevietnam px-2 py-1">
              {category}
            </Tag>
          </div>
          <button className="absolute top-3 right-3 bg-white rounded-full p-2 hover:bg-gray-100">
            <HeartOutlined style={{ fontSize: "18px", color: "#888" }} />
          </button>
        </div>
      }
      onClick={onClick}
      styles={{ body: { padding: "16px" } }}
    >
      <div className="mb-2 flex justify-between items-center">
        <Rate disabled defaultValue={rating} className="text-sm" />
        <Text className="text-gray-500">{rating}/5</Text>
      </div>

      <Meta
        title={
          <Text className="text-lg font-bevietnam font-semibold">{title}</Text>
        }
        description={
          <div className="space-y-2">
            <div className="flex items-center font-bevietnam text-gray-500">
              <EnvironmentOutlined className="mr-1" /> {location}
            </div>

            {features.length > 0 && (
              <Space wrap className="pt-2">
                {features.map((feature, index) => (
                  <Tag key={index} className="font-bevietnam m-0">
                    {feature}
                  </Tag>
                ))}
              </Space>
            )}

            <div className="flex justify-between items-center pt-2">
              <div className="flex items-center">
                <HomeOutlined className="text-blue-500 mr-1" />
                <Text className="font-bevietnam font-bold text-gray-700">
                  LavishStay
                </Text>
              </div>
              <Text className="font-bevietnam font-bold text-blue-600 text-lg">
                ${price}
                <Text className="text-gray-500 text-sm">/đêm</Text>
              </Text>
            </div>
          </div>
        }
      />
    </Card>
  );
};

export default HotelCard;
