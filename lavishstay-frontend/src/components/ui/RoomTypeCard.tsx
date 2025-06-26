import React, { useCallback, useMemo } from "react";
import { Card, Typography, Button, Tag } from "antd";
import { useNavigate } from "react-router-dom";
import {
  ArrowRightOutlined,
  StarFilled,
  EyeOutlined,
  HomeOutlined,
  CrownOutlined,
  FireOutlined,
  ThunderboltOutlined,
  CheckCircleOutlined,
  CalendarOutlined,
  TeamOutlined,
} from "@ant-design/icons";
import { theme } from "antd";

const { Title } = Typography;

export interface RoomTypeProps {
  id: number;
  name: string;
  image?: string;
  images?: string[]; // Thêm trường images
  size?: number;
  avg_size?: number; // Kích thước trung bình từ bảng room
  view?: string;
  common_views?: string[]; // Tầm nhìn phổ biến từ bảng room
  bedType?: string;
  amenities?: string[];
  mainAmenities?: string[];
  roomType?: "deluxe" | "premium" | "suite" | "presidential" | "theLevel";
  rating?: number;
  avg_rating?: number; // Đánh giá trung bình từ bảng room
  maxGuests?: number;
  className?: string;
  style?: React.CSSProperties;
  base_price_vnd?: number;
  base_price?: number; // Giá từ API backend
  min_price?: number;
  max_price?: number;
  avg_price?: number;
  lavish_plus_discount?: number;
  description?: string;
  room_code?: string;
  rooms_count?: number; // Tổng số phòng
  available_rooms_count?: number; // Số phòng còn trống
  available_floors?: number[]; // Các tầng có sẵn
}

const RoomTypeCard: React.FC<RoomTypeProps> = ({
  id,
  name,
  image,
  images,
  size,
  avg_size,
  view,
  common_views,
  bedType,
  mainAmenities,
  roomType = "deluxe",
  rating,
  avg_rating,
  maxGuests,
  className = "",
  style = {},
  base_price_vnd,
  base_price,
  min_price,
  max_price,
  avg_price,
  lavish_plus_discount,
  description,
  room_code,
  rooms_count,
  available_rooms_count,
  available_floors,
}) => {
  const { token } = theme.useToken();
  const navigate = useNavigate();

  // Memoize image display - ưu tiên images[0] nếu có, fallback về image
  const displayImage = useMemo(() => {
    if (images && images.length > 0) {
      return images[0];
    }
    return image;
  }, [images, image]);

  // State để handle error loading ảnh
  const [imageError, setImageError] = React.useState(false);

  const handleImageError = useCallback(() => {
    setImageError(true);
  }, []);

  const handleViewDetails = useCallback(() => {
    navigate(`/room-types/${id}`);
  }, [navigate, id]);

  const maxGuestsDisplay = useMemo(() => maxGuests || 2, [maxGuests]);

  const actualRoomType = useMemo(() => {
    if (room_code) {
      const code = room_code.toLowerCase();
      if (code.includes('deluxe')) return 'deluxe';
      if (code.includes('premium')) return 'premium';
      if (code.includes('suite')) return 'suite';
      if (code.includes('presidential')) return 'presidential';
      if (code.includes('level')) return 'theLevel';
    }
    return roomType;
  }, [room_code, roomType]);

  const bedTypeText = useMemo(() => {
    return bedType || 'King Bed';
  }, [bedType]);

  const themeColors = useMemo(() => {
    // Màu sắc đơn giản, sang trọng và tinh tế
    const baseColors = {
      primary: "#2D3748", // Dark gray
      secondary: "#F7FAFC",
      accent: "#CBD5E0",
      text: "#2D3748",
      border: "#E2E8F0",
      light: "#EDF2F7"
    };

    switch (actualRoomType) {
      case "deluxe":
        return {
          primary: "#4A5568", // Sophisticated gray
          secondary: "#F7FAFC",
          accent: "#A0AEC0",
          text: "#2D3748",
          border: "#E2E8F0",
          light: "#EDF2F7"
        };
      case "premium":
        return {
          primary: "#2F855A", // Elegant green
          secondary: "#F0FFF4",
          accent: "#9AE6B4",
          text: "#2F855A",
          border: "#C6F6D5",
          light: "#F0FFF4"
        };
      case "suite":
        return {
          primary: "#C53030", // Refined red
          secondary: "#FFF5F5",
          accent: "#FEB2B2",
          text: "#C53030",
          border: "#FED7D7",
          light: "#FFF5F5"
        };
      case "presidential":
        return {
          primary: "#B7791F", // Luxurious gold
          secondary: "#FFFAF0",
          accent: "#F6E05E",
          text: "#B7791F",
          border: "#FAD5A5",
          light: "#FFFAF0"
        };
      case "theLevel":
        return {
          primary: "#2C5282", // Premium blue
          secondary: "#EBF8FF",
          accent: "#90CDF4",
          text: "#2C5282",
          border: "#BEE3F8",
          light: "#EBF8FF"
        };
      default:
        return baseColors;
    }
  }, [actualRoomType, token.colorTextBase]);

  const cardStyle = useMemo(() => {
    const baseStyle = { ...style };
    switch (actualRoomType) {
      case "deluxe":
        return {
          ...baseStyle,
          border: "1px solid #E2E8F0",
          background: "#FFFFFF",
          boxShadow: "0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06)",
        };
      case "premium":
        return {
          ...baseStyle,
          border: "1px solid #C6F6D5",
          background: "#FFFFFF",
          boxShadow: "0 1px 3px rgba(47, 133, 90, 0.1), 0 1px 2px rgba(47, 133, 90, 0.06)",
        };
      case "suite":
        return {
          ...baseStyle,
          border: "1px solid #FED7D7",
          background: "#FFFFFF",
          boxShadow: "0 1px 3px rgba(197, 48, 48, 0.1), 0 1px 2px rgba(197, 48, 48, 0.06)",
        };
      case "presidential":
        return {
          ...baseStyle,
          border: "1px solid #FAD5A5",
          background: "#FFFFFF",
          boxShadow: "0 1px 3px rgba(183, 121, 31, 0.1), 0 1px 2px rgba(183, 121, 31, 0.06)",
        };
      case "theLevel":
        return {
          ...baseStyle,
          border: "1px solid #BEE3F8",
          background: "#FFFFFF",
          boxShadow: "0 1px 3px rgba(44, 82, 130, 0.1), 0 1px 2px rgba(44, 82, 130, 0.06)",
        };
      default:
        return {
          ...baseStyle,
          border: "1px solid #E2E8F0",
          background: "#FFFFFF",
          boxShadow: "0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06)",
        };
    }
  }, [actualRoomType, style]);

  const formattedPrice = useMemo(() => {
    // Logic lấy giá: ưu tiên base_price từ API backend,
    // fallback về avg_price (giá trung bình từ bảng room),
    // sau đó base_price_vnd (nếu có), cuối cùng là giá mặc định
    const price = base_price || avg_price || base_price_vnd || 1200000;

    return new Intl.NumberFormat('vi-VN', {
      style: 'currency',
      currency: 'VND'
    }).format(price);
  }, [base_price, avg_price, base_price_vnd]);

  // Sử dụng thông tin từ bảng room nếu có
  const displaySize = useMemo(() => {
    return avg_size || size;
  }, [avg_size, size]);

  const displayRating = useMemo(() => {
    return avg_rating || rating;
  }, [avg_rating, rating]);

  const displayView = useMemo(() => {
    if (common_views && common_views.length > 0) {
      return common_views[0]; // Lấy view phổ biến nhất
    }
    return view;
  }, [common_views, view]);

  const roomTypeDisplayName = useMemo(() => {
    switch (actualRoomType) {
      case "deluxe":
        return "Deluxe";
      case "premium":
        return "Premium";
      case "suite":
        return "Suite";
      case "presidential":
        return "Presidential";
      case "theLevel":
        return "The Level";
      default:
        return "Standard";
    }
  }, [actualRoomType]);

  return (
    <Card
      className={`room-type-card overflow-hidden transition-all duration-300 hover:shadow-xl  ${className}`}
      style={cardStyle}
      bordered={false}
      bodyStyle={{
        padding: 0,
        height: "100%",
        display: "flex",
        flexDirection: "column",
      }}
    >
      <div className="relative overflow-hidden h-48 group">
        {displayImage && !imageError ? (
          <>
            <img
              src={displayImage}
              alt={name}
              onError={handleImageError}
              className="w-full h-full object-cover transition-all duration-500 group-hover:scale-110"
            />
            {/* Gradient overlay */}
            <div className="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </>
        ) : (
          <div
            className="w-full h-full flex items-center justify-center text-white relative"
            style={{ backgroundColor: themeColors.primary }}
          >
            <HomeOutlined className="text-4xl opacity-70" />
          </div>
        )}

        <div className="absolute top-3 left-3">
          <Tag
            className="px-3 py-1 rounded-full font-medium border-0"
            style={{
              backgroundColor: themeColors.primary,
              color: 'white',
              boxShadow: '0 2px 8px rgba(0, 0, 0, 0.15)'
            }}
          >
            <CrownOutlined className="mr-1" />
            {roomTypeDisplayName}
          </Tag>
        </div>          {displayRating && displayRating > 0 && (
          <div className="absolute top-3 right-3">
            <Tag
              className="px-2 py-1 rounded-full font-medium flex items-center gap-1 border-0"
              style={{
                backgroundColor: '#FFD700',
                color: 'white',
                boxShadow: '0 2px 8px rgba(255, 215, 0, 0.3)'
              }}
            >
              <StarFilled className="text-xs" />
              {displayRating}
            </Tag>
          </div>
        )}
      </div>

      <div className="p-6 flex-1 flex flex-col">
        <div className="mb-4">
          <Title
            level={4}
            className="mb-3 font-bold text-gray-800 leading-tight"
            style={{ color: themeColors.text }}
          >
            {name}
          </Title>

        </div>

        <div className="space-y-3 mb-4">
          {displaySize && (
            <div className="flex items-center gap-3 text-sm">
              <div className="flex items-center justify-center w-8 h-8 rounded-full"
                style={{ backgroundColor: `${themeColors.primary}20` }}>
                <HomeOutlined style={{ color: themeColors.primary }} />
              </div>
              <span className="text-gray-700 font-medium">{displaySize}m² · {bedTypeText}</span>
            </div>
          )}

          {displayView && (
            <div className="flex items-center gap-3 text-sm">
              <div className="flex items-center justify-center w-8 h-8 rounded-full"
                style={{ backgroundColor: `${themeColors.primary}20` }}>
                <EyeOutlined style={{ color: themeColors.primary }} />
              </div>
              <span className="text-gray-700 font-medium">{displayView}</span>
            </div>
          )}

          <div className="flex items-center gap-3 text-sm">
            <div className="flex items-center justify-center w-8 h-8 rounded-full"
              style={{ backgroundColor: `${themeColors.primary}20` }}>
              <TeamOutlined style={{ color: themeColors.primary }} />
            </div>
            <span className="text-gray-700 font-medium">Tối đa {maxGuestsDisplay} khách</span>
          </div>

          {/* Hiển thị thông tin thống kê phòng với thiết kế đẹp */}
          {(rooms_count !== undefined && available_rooms_count !== undefined) && (
            <div className="flex items-center gap-3 text-sm">
              <div className="flex items-center justify-center w-8 h-8 rounded-full"
                style={{ backgroundColor: `${themeColors.accent}30` }}>
                <CheckCircleOutlined style={{ color: themeColors.primary }} />
              </div>
              <div className="flex items-center gap-2">
                <Tag color="success" className="rounded-full border-0 text-xs font-medium">
                  {available_rooms_count} có sẵn
                </Tag>
                <span className="text-gray-500">/ {rooms_count} phòng</span>
              </div>
            </div>
          )}

          {/* Hiển thị tầng có sẵn với icon */}
          {available_floors && available_floors.length > 0 && (
            <div className="flex items-center gap-3 text-sm">
              <div className="flex items-center justify-center w-8 h-8 rounded-full"
                style={{ backgroundColor: `${themeColors.accent}30` }}>
                <CalendarOutlined style={{ color: themeColors.primary }} />
              </div>
              <div className="flex flex-wrap gap-1">
                {available_floors.slice(0, 3).map((floor, index) => (
                  <Tag key={index} color="geekblue" className="rounded-full text-xs border-0">
                    Tầng {floor}
                  </Tag>
                ))}
                {available_floors.length > 3 && (
                  <Tag color="default" className="rounded-full text-xs border-0">
                    +{available_floors.length - 3}
                  </Tag>
                )}
              </div>
            </div>
          )}
        </div>

        {(mainAmenities && mainAmenities.length > 0) && (
          <div className="mb-4">
            <div className="flex items-center gap-2 mb-3">
              <ThunderboltOutlined style={{ color: themeColors.primary }} />
              <span className="text-sm font-semibold text-gray-700">Tiện nghi nổi bật</span>
            </div>
            <div className="flex flex-wrap gap-2">
              {mainAmenities.slice(0, 4).map((amenity, index) => (
                <Tag
                  key={index}
                  className="rounded-full border-0 text-xs font-medium px-3 py-1"
                  style={{
                    backgroundColor: themeColors.secondary,
                    color: themeColors.text,
                    boxShadow: '0 1px 3px rgba(0, 0, 0, 0.1)'
                  }}
                >
                  {amenity}
                </Tag>
              ))}
              {mainAmenities.length > 4 && (
                <Tag className="rounded-full border-0 text-xs font-medium px-3 py-1 bg-gray-100 text-gray-600">
                  +{mainAmenities.length - 4}
                </Tag>
              )}
            </div>
          </div>
        )}

        <div className="mb-4 mt-auto">
          <div className="text-center p-4 rounded-xl"
            style={{
              backgroundColor: themeColors.secondary,
              border: `1px solid ${themeColors.border}`
            }}>
            {/* Hiển thị range giá nếu có min và max khác nhau */}
            {min_price && max_price && min_price !== max_price ? (
              <div>
                <div className="text-lg font-bold" style={{ color: themeColors.primary }}>
                  {new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                  }).format(min_price)}
                  <span className="text-gray-500 font-normal mx-1">đến</span>
                  {new Intl.NumberFormat('vi-VN', {
                    style: 'currency',
                    currency: 'VND'
                  }).format(max_price)}
                </div>
                <div className="text-xs text-gray-500 mt-1">
                  /đêm • Chưa bao gồm thuế
                </div>
              </div>
            ) : (
              <div>
                <div className="text-xl font-bold" style={{ color: themeColors.primary }}>
                  {formattedPrice}
                </div>
                <div className="text-xs text-gray-500 mt-1">
                  /đêm • Chưa bao gồm thuế
                </div>
              </div>
            )}
            {lavish_plus_discount && lavish_plus_discount > 0 && (
              <div className="mt-2">
                <Tag
                  className="rounded-full border-0 text-xs font-medium"
                  style={{
                    backgroundColor: '#FF4D4F',
                    color: 'white'
                  }}
                >
                  <FireOutlined className="mr-1" />
                  Giảm {lavish_plus_discount}% cho Lavish+
                </Tag>
              </div>
            )}
          </div>
        </div>

        <div className="pt-3">
          <Button
            type="primary"
            icon={<ArrowRightOutlined />}
            onClick={handleViewDetails}
            size="large"
            className="w-full transition-all duration-300 font-semibold hover:scale-105"
            style={{
              borderRadius: "12px",
              backgroundColor: themeColors.primary,
              border: 'none',
              boxShadow: `0 4px 15px ${themeColors.primary}40`,
              height: "48px",
            }}
          >
            Xem chi tiết loại phòng
          </Button>
        </div>
      </div>
    </Card>
  );
};

export default React.memo(RoomTypeCard);
