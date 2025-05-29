import React from "react";
import { Card, Typography, Space, Button, Tooltip, Badge } from "antd";
import { useNavigate } from "react-router-dom";
import {
  ArrowRightOutlined,
  EnvironmentOutlined,
} from "@ant-design/icons";
import { theme } from "antd";
import { AmenityUtils } from "../../constants/amenities";

const { Title, Text } = Typography;

export interface RoomProps {
  id: number;
  name: string;
  image: string;
  priceVND?: number;
  size?: number;
  view?: string;
  bedType?: string | {
    default: string;
    options?: string[];
  };
  amenities?: string[];
  discount?: number;
  isSale?: boolean;
  roomType?: "deluxe" | "premium" | "suite" | "presidential" | "theLevel";
  className?: string;
  style?: React.CSSProperties;
}

const formatVND = (price: number) => {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  }).format(price);
};

const RoomCard: React.FC<RoomProps> = ({
  id,
  name,
  image,
  priceVND,
  size,
  view,
  bedType,
  amenities = ["wifi", "tv", "ac"],
  discount,
  isSale = false,
  roomType = "standard",
  className = "",
  style = {},
}) => {
  const { token } = theme.useToken();
  const navigate = useNavigate();
  // Navigate to room details
  const handleViewDetails = () => {
    navigate(`/rooms/${id}`);
  };

  // Lấy formatted amenities từ constants
  const formattedAmenities = AmenityUtils.formatAmenitiesForDisplay(amenities);// Tính giá VND (nếu không có thì convert từ USD với tỷ giá 24,000)
  const baseVNDPrice = priceVND || 0;

  // Tính giá sau giảm giá nếu có
  const discountedVNDPrice = discount
    ? baseVNDPrice - (baseVNDPrice * discount) / 100
    : baseVNDPrice;

  // Helper function to get bed type display text
  const getBedTypeText = (bedType: string | { default: string; options?: string[] }): string => {
    if (typeof bedType === 'string') {
      return bedType;
    }
    return bedType.default;
  };

  // Styling based on room type
  let cardStyle = { ...style };
  let additionalClass = "";

  switch (roomType) {
    case "presidential":
      cardStyle = {
        ...style,
        // backgroundColor: "#f8f4e3", // Bối cảnh kem thanh lịch
        border: "1px solid #dcc191", // Biên giới vàng
      };
      additionalClass = "presidential-room";
      break;
    case "theLevel":
      cardStyle = {
        ...style,
        // backgroundColor: "#eff6fb", // Nền màu xanh nhạt
        border: "1px solid #b0d2ed", // Đường viền màu xanh trung bình
      };
      additionalClass = "the-level-room";
      break;
    default:
      break;
  }

  const card = (
    <Card
      hoverable
      className={`room-card overflow-hidden transition-all duration-300 h-full ${className} ${additionalClass}`}
      style={{
        borderRadius: token.borderRadiusLG,
        ...cardStyle,
      }}
      cover={
        <div className="relative overflow-hidden" style={{ height: "240px" }}>
          <img
            alt={name}
            src={image}
            className="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
          />
        </div>
      } bodyStyle={{ padding: "20px" }}
    >
      <div className="space-y-4">
        <Title
          level={5}
          className="mb-0 font-medium leading-tight"
          style={{
            color:
              roomType === "presidential"
                ? "#9c7c38"
                : roomType === "theLevel"
                  ? "#0c5f96"
                  : token.colorTextBase,
          }}
        >
          {name}
        </Title>

        <Space direction="vertical" size="small" className="w-full">
          <div className="flex flex-wrap items-center gap-x-3 text-sm">
            {size && (
              <div className="flex items-center gap-1">
                <span style={{ color: token.colorTextSecondary }}>📐</span>
                <Text style={{ color: token.colorTextSecondary }}>{size} m²</Text>
              </div>
            )}
            {view && (
              <div className="flex items-center gap-1">
                <EnvironmentOutlined style={{ color: token.colorTextSecondary }} />
                <Text style={{ color: token.colorTextSecondary }}>{view}</Text>
              </div>
            )}
          </div>          {bedType && (
            <div className="flex items-center gap-1">
              <span style={{ color: token.colorTextSecondary }}>🛏️</span>
              <Text style={{ color: token.colorTextSecondary }}>{getBedTypeText(bedType)}</Text>
            </div>
          )}
        </Space>        <div className="flex items-center gap-4 py-2">
          {formattedAmenities.slice(0, 4).map((amenity) => (
            <Tooltip key={amenity.key} title={amenity.name}>
              <span
                className="text-lg transition-colors hover:opacity-70"
                style={{ color: token.colorPrimary }}
              >
                {amenity.icon}
              </span>
            </Tooltip>
          ))}
          {amenities.length > 4 && (
            <Tooltip title={`+${amenities.length - 4} tiện nghi khác`}>
              <span
                className="text-sm font-medium px-2 py-1 bg-gray-100 rounded-full"
                style={{ color: token.colorTextSecondary }}
              >
                +{amenities.length - 4}
              </span>
            </Tooltip>
          )}
        </div><div className="flex justify-between items-end mb-3">
          <div className="flex-1">
            <div className="mb-1">
              <Text type="secondary" style={{ fontSize: "12px" }}>
                Giá phòng/đêm
              </Text>
            </div>            {discount && baseVNDPrice > 0 ? (
              <div className="space-y-1">
                <Text
                  style={{
                    fontSize: "14px",
                    color: token.colorTextTertiary,
                    textDecoration: "line-through"
                  }}
                >
                  {formatVND(baseVNDPrice)}
                </Text>
                <div>
                  <Text
                    strong
                    style={{
                      fontSize: "18px",
                      color: token.colorError,
                    }}
                  >
                    {formatVND(discountedVNDPrice)}
                  </Text>
                </div>
              </div>
            ) : baseVNDPrice > 0 ? (
              <Text
                strong
                style={{
                  fontSize: "18px",
                  color:
                    roomType === "presidential"
                      ? "#9c7c38"
                      : roomType === "theLevel"
                        ? "#0c5f96"
                        : token.colorTextBase,
                }}
              >
                {formatVND(baseVNDPrice)}
              </Text>
            ) : (
              <Text
                style={{
                  fontSize: "16px",
                  color: token.colorTextSecondary,
                }}
              >
                Liên hệ
              </Text>
            )}
          </div>        <Button
            type={roomType === "presidential" ? "default" : "primary"}
            icon={<ArrowRightOutlined />}
            onClick={handleViewDetails}
            size="middle"
            className="transition-all duration-200"
            style={{
              borderRadius: token.borderRadiusSM,
              backgroundColor:
                roomType === "presidential"
                  ? "#dcc191"
                  : roomType === "theLevel"
                    ? "#4b9cd3"
                    : undefined,
              borderColor:
                roomType === "presidential"
                  ? "#9c7c38"
                  : roomType === "theLevel"
                    ? "#0c5f96"
                    : undefined,
            }}
          >
            Xem chi tiết
          </Button>
        </div>
      </div>
    </Card>
  );

  // Only show ribbon for sale rooms with discount
  if (isSale && discount) {
    return (
      <Badge.Ribbon text={`${discount}%`} color="pink">
        {card}
      </Badge.Ribbon>
    );
  }

  return card;
};

export default RoomCard;
