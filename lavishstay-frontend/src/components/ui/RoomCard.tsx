import React from "react";
import { Card, Typography, Space, Button } from "antd";
import { useNavigate } from "react-router-dom";
import {
  ArrowRightOutlined,
  EnvironmentOutlined,
  UserOutlined,
  StarFilled,
} from "@ant-design/icons";
import { theme } from "antd";
import RoomMainAmenities from "../room/RoomMainAmenities";
import "./RoomCard.css";

const { Title } = Typography;

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
  mainAmenities?: string[]; // Tiện ích chính hiển thị trên card (4-5 tiện ích)
  discount?: number;
  roomType?: "deluxe" | "premium" | "suite" | "presidential" | "theLevel";
  rating?: number; // Add rating from Room model
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
  amenities = [],
  mainAmenities,
  discount,
  roomType = "standard",
  rating,
  className = "",
  style = {},
}) => {
  const { token } = theme.useToken();
  const navigate = useNavigate();

  // Navigate to room details
  const handleViewDetails = () => {
    navigate(`/rooms/${id}`);
  };// Tính giá VND (nếu không có thì convert từ USD với tỷ giá 24,000)
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
  // Styling based on room type với màu sắc hiện đại và trendy
  let cardStyle = { ...style };
  let additionalClass = "";
  let themeColors = {
    primary: "#1890ff",
    secondary: "#f5f5f5",
    accent: "#52c41a",
    text: token.colorTextBase,
    border: "#f0f0f0"
  };

  switch (roomType) {
    case "deluxe":
      themeColors = {
        primary: "#6366f1", // Indigo modern
        secondary: "#f1f5f9",
        accent: "#8b5cf6",
        text: "#4338ca",
        border: "#c7d2fe"
      };
      cardStyle = {
        ...style,
        border: "1px solid #c7d2fe",
        background: "linear-gradient(135deg, #fefbff 0%, #f8fafc 100%)",
      };
      additionalClass = "deluxe-room";
      break;
    case "premium":
      themeColors = {
        primary: "#059669", // Emerald green
        secondary: "#ecfdf5",
        accent: "#10b981",
        text: "#047857",
        border: "#a7f3d0"
      };
      cardStyle = {
        ...style,
        border: "1px solid #a7f3d0",
        background: "linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 100%)",
      };
      additionalClass = "premium-room";
      break;
    case "suite":
      themeColors = {
        primary: "#dc2626", // Red modern
        secondary: "#fef2f2",
        accent: "#ef4444",
        text: "#b91c1c",
        border: "#fecaca"
      };
      cardStyle = {
        ...style,
        border: "1px solid #fecaca",
        background: "linear-gradient(135deg, #fffbeb 0%, #fef2f2 100%)",
      };
      additionalClass = "suite-room";
      break;
    case "presidential":
      themeColors = {
        primary: "#d97706", // Amber luxury
        secondary: "#fffbeb",
        accent: "#f59e0b",
        text: "#92400e",
        border: "#fed7aa"
      };
      cardStyle = {
        ...style,
        border: "1px solid #fed7aa",
        background: "linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%)",
      };
      additionalClass = "presidential-room";
      break;
    case "theLevel":
      themeColors = {
        primary: "#0ea5e9", // Sky blue
        secondary: "#f0f9ff",
        accent: "#38bdf8",
        text: "#0369a1",
        border: "#bae6fd"
      };
      cardStyle = {
        ...style,
        border: "1px solid #bae6fd",
        background: "linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%)",
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
        borderRadius: "16px",
        boxShadow: "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
        ...cardStyle,
      }}
      cover={
        <div className="relative overflow-hidden" style={{ height: "200px" }}>
          <img
            alt={name}
            src={image}
            className="w-full h-full object-cover transition-transform duration-500 hover:scale-110"
          />          {/* Room Type Badge */}
          <div
            className="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-semibold room-type-badge"
            style={{
              backgroundColor: `${themeColors.primary}15`,
              color: themeColors.primary,
              border: `1px solid ${themeColors.primary}30`
            }}
          >
            {roomType === "theLevel" ? "The Level" :
              roomType === "presidential" ? "Presidential" :
                roomType.charAt(0).toUpperCase() + roomType.slice(1)}
          </div>
        </div>
      }
      bodyStyle={{ padding: "16px" }}
    >
      <div className="space-y-3">
        {/* Room Title with enhanced styling */}
        <div className="flex items-start justify-between">
          <Title
            level={5}
            className="mb-0 font-bold leading-tight flex-1" style={{
              color: themeColors.text,
              fontSize: "16px",
            }}
          >
            {name}
          </Title>          <div className="flex items-center ml-2 star-rating">
            <StarFilled style={{ color: "#fadb14", fontSize: "14px" }} />
            <span className="text-sm font-medium ml-1 text-gray-600">
              {rating ?? "N/A"}
            </span>
          </div>
        </div>

        {/* Enhanced Room Details */}
        <Space direction="vertical" size="small" className="w-full">
          <div className="flex flex-wrap items-center gap-x-4 gap-y-2">
            {size && (
              <div className="flex items-center gap-1.5">
                <div
                  className="w-5 h-5 rounded-full flex items-center justify-center text-xs"
                  style={{ backgroundColor: `${themeColors.primary}15`, color: themeColors.primary }}
                >
                  📐
                </div>
                <span className="text-sm font-medium" style={{ color: themeColors.text }}>
                  {size} m²
                </span>
              </div>
            )}
            {view && (
              <div className="flex items-center gap-1.5">
                <div
                  className="w-5 h-5 rounded-full flex items-center justify-center"
                  style={{ backgroundColor: `${themeColors.primary}15`, color: themeColors.primary }}
                >
                  <EnvironmentOutlined style={{ fontSize: "12px" }} />
                </div>
                <span className="text-sm font-medium" style={{ color: themeColors.text }}>
                  {view}
                </span>
              </div>
            )}
          </div>

          {bedType && (
            <div className="flex items-center gap-1.5">
              <div
                className="w-5 h-5 rounded-full flex items-center justify-center text-xs"
                style={{ backgroundColor: `${themeColors.primary}15`, color: themeColors.primary }}
              >
                🛏️
              </div>
              <span className="text-sm font-medium" style={{ color: themeColors.text }}>
                {getBedTypeText(bedType)}
              </span>
            </div>
          )}
        </Space>

        {/* Enhanced Amenities Display */}        <div
          className="p-2 rounded-lg"
          style={{ backgroundColor: themeColors.secondary }}
        >
          <div className="flex items-center justify-between mb-1">
            <span className="text-xs font-semibold text-gray-600 uppercase tracking-wide">
              Tiện ích chính
            </span>
            <UserOutlined style={{ color: themeColors.primary, fontSize: "12px" }} />
          </div><RoomMainAmenities
            amenities={mainAmenities || amenities.slice(0, 4)}
            limit={6}
          />
        </div>

        {/* Enhanced Price and CTA Section */}
        <div className="flex justify-between items-end pt-1">
          <div className="flex-1">
            <div className="mb-1">
              <span className="text-xs font-medium text-gray-500 uppercase tracking-wide">
                Giá từ/đêm
              </span>
            </div>            {discount && baseVNDPrice > 0 ? (
              <div className="space-y-1">
                <div className="text-sm text-gray-400 line-through">
                  {formatVND(baseVNDPrice)}
                </div>
                <div className="flex items-center gap-2">
                  <span
                    className="text-xl font-bold"
                    style={{ color: "#ef4444" }}
                  >
                    {formatVND(discountedVNDPrice)}
                  </span>
                </div>
              </div>
            ) : baseVNDPrice > 0 ? (
              <span
                className="text-xl font-bold"
                style={{ color: themeColors.primary }}
              >
                {formatVND(baseVNDPrice)}
              </span>
            ) : (
              <span className="text-lg font-medium text-gray-500">
                Liên hệ
              </span>
            )}
          </div>

          {/* Enhanced CTA Button */}
          <Button
            type="primary"
            icon={<ArrowRightOutlined />}
            onClick={handleViewDetails}
            size="middle"
            className="transition-all duration-200 font-semibold px-6"
            style={{
              borderRadius: "8px",
              backgroundColor: themeColors.primary,
              borderColor: themeColors.primary,
              boxShadow: `0 2px 8px ${themeColors.primary}30`
            }}
          >
            Xem chi tiết
          </Button>
        </div>
      </div>
    </Card>
  );
  return card;
};

export default RoomCard;
