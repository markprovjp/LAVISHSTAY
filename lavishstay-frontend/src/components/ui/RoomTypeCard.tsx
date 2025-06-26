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
  amenities?: Array<{
    id: number;
    name: string;
    description?: string;
    icon?: string;
    category: string;
  }>; // Amenities từ API backend
  highlighted_amenities?: Array<{
    id: number;
    name: string;
    description?: string;
    icon?: string;
    category: string;
  }>; // Highlighted amenities từ API backend
  mainAmenities?: string[]; // Legacy - để tương thích
  roomType?: "deluxe" | "premium" | "suite" | "presidential" | "theLevel";
  rating?: number;
  avg_rating?: number; // Đánh giá trung bình từ bảng room
  maxGuests?: number;
  className?: string;
  style?: React.CSSProperties;
  base_price_vnd?: number;
  base_price?: number; // Giá từ API backend
  avg_price?: number;
  lavish_plus_discount?: number;
  room_code?: string;
  rooms_count?: number; // Tổng số phòng
  available_rooms_count?: number; // Số phòng còn trống
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
  amenities,
  highlighted_amenities,
  mainAmenities,
  roomType = "deluxe",
  rating,
  avg_rating,
  maxGuests,
  className = "",
  style = {},
  base_price_vnd,
  base_price,
  avg_price,
  lavish_plus_discount,
  room_code,
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
      <div className="relative overflow-hidden h-36 group">
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
            <HomeOutlined className="text-3xl opacity-70" />
          </div>
        )}

        <div className="absolute top-2 left-2">
          <Tag
            className="px-2 py-1 rounded-full font-medium border-0 text-xs"
            style={{
              backgroundColor: themeColors.primary,
              color: 'white',
              boxShadow: '0 2px 8px rgba(0, 0, 0, 0.15)'
            }}
          >
            <CrownOutlined className="mr-1" style={{ fontSize: '12px' }} />
            {roomTypeDisplayName}
          </Tag>
        </div>
      </div>

      <div className="p-4 flex-1 flex flex-col">
        <div className="mb-3">
          <Title
            level={5}
            className="mb-2 font-bold text-gray-800 leading-tight"
            style={{ color: themeColors.text, margin: 0 }}
          >
            {name}
          </Title>
        </div>

        <div className="space-y-1 mb-3">
          {/* Compact info row */}
          <div className="flex items-center justify-between text-xs text-gray-600">
            <div className="flex items-center gap-3">
              {displaySize && (
                <div className="flex items-center gap-1">
                  <HomeOutlined style={{ color: themeColors.primary, fontSize: '12px' }} />
                  <span>{displaySize}m²</span>
                </div>
              )}
              <div className="flex items-center gap-1">
                <TeamOutlined style={{ color: themeColors.primary, fontSize: '12px' }} />
                <span>{maxGuestsDisplay} khách</span>
              </div>
            </div>
            {displayRating && displayRating > 0 && (
              <div className="flex items-center gap-1">
                <StarFilled style={{ color: '#FFD700', fontSize: '12px' }} />
                <span className="text-yellow-600 font-medium">{displayRating}</span>
              </div>
            )}
          </div>

          {displayView && (
            <div className="flex items-center gap-1 text-xs text-gray-500">
              <EyeOutlined style={{ color: themeColors.primary, fontSize: '12px' }} />
              <span className="truncate">{displayView}</span>
            </div>
          )}
        </div>

        {/* Tiện nghi nổi bật - Compact version */}
        {((highlighted_amenities && highlighted_amenities.length > 0) || (mainAmenities && mainAmenities.length > 0)) && (
          <div className="mb-3">
            <div className="flex items-center gap-2 mb-2">
              <ThunderboltOutlined style={{ color: themeColors.primary, fontSize: '12px' }} />
              <span className="text-xs font-medium text-gray-600">Tiện nghi nổi bật</span>
            </div>
            <div className="flex flex-wrap gap-1">
              {/* Ưu tiên highlighted_amenities từ API, fallback về mainAmenities */}
              {(highlighted_amenities && highlighted_amenities.length > 0
                ? highlighted_amenities.slice(0, 8)
                : (mainAmenities || []).slice(0, 8)
              ).map((amenity, index) => (
                <Tag
                  key={typeof amenity === 'string' ? index : amenity.id}
                  className="text-xs px-2 py-0 rounded-full border-0 mb-1"
                  style={{
                    backgroundColor: themeColors.secondary,
                    color: themeColors.text,
                    fontSize: '12px'
                  }}
                >
                  {typeof amenity === 'string' ? amenity : (
                    <>
                      {amenity.icon && <span className="mr-1" style={{ fontSize: '12px' }} dangerouslySetInnerHTML={{ __html: amenity.icon }} />}
                      <span className="truncate max-w-20">{amenity.name}</span>
                    </>
                  )}
                </Tag>
              ))}

            </div>
          </div>
        )}

        <div className="mt-auto">
          {/* Compact pricing */}
          <div className="text-center p-2 rounded-lg mb-3"
            style={{
              backgroundColor: themeColors.secondary,
              border: `1px solid ${themeColors.border}`
            }}>
            <div className="text-lg font-bold" style={{ color: themeColors.primary }}>
              {formattedPrice}
            </div>
            <div className="text-xs text-gray-500">mỗi đêm</div>
            {lavish_plus_discount && lavish_plus_discount > 0 && (
              <div className="mt-1">
                <Tag
                  className="rounded-full border-0 text-xs font-medium"
                  style={{
                    backgroundColor: '#FF4D4F',
                    color: 'white',
                    fontSize: '12px'
                  }}
                >
                  <FireOutlined className="mr-1" />
                  -{lavish_plus_discount}%
                </Tag>
              </div>
            )}
          </div>

          {/* Compact button */}
          <Button
            type="primary"
            icon={<ArrowRightOutlined />}
            onClick={handleViewDetails}
            size="small"
            className="w-full transition-all duration-300 font-medium"
            style={{
              borderRadius: "6px",
              backgroundColor: themeColors.primary,
              border: 'none',
              height: "32px",
            }}
          >
            Xem chi tiết
          </Button>
        </div>
      </div>
    </Card>
  );
};

export default React.memo(RoomTypeCard);
