




import React, { useCallback, useMemo } from "react";
import { Card, Typography, Button } from "antd";
import { useNavigate } from "react-router-dom";
import {
  ArrowRightOutlined,
  StarFilled,
  EyeOutlined,
  HomeOutlined,
  CrownOutlined,
  ThunderboltOutlined,
  CheckCircleOutlined,
  TeamOutlined,
} from "@ant-design/icons";

const { Title } = Typography;

interface RoomTypeProps {
  id: number;
  name: string;
  images?: Array<{ image_url: string; is_main?: boolean }>;
  main_image?: { image_url: string };
  room_area?: number;
  view?: string;
  adjusted_price: string;
  amenities?: any[];
  highlighted_amenities?: any[];
  mainAmenities?: any[];
  roomType?: string;
  rating?: number;
  style?: React.CSSProperties;
  max_guests: number;
  className?: string;
  base_price?: string;
  room_code?: string;
}



const RoomTypeCard: React.FC<RoomTypeProps> = ({
  id,
  name,
  images,
  main_image,
  room_area,
  view,
  adjusted_price,
  highlighted_amenities,
  mainAmenities,
  rating,
  style = {},
  max_guests,
  className = "",
  room_code,
}) => {
  const navigate = useNavigate();

  const displayImage = useMemo(() => {
    // First try to use main_image
    if (main_image?.image_url) {
      return main_image.image_url;
    }
    // Otherwise look through images array
    if (images && images.length > 0) {
      // First try to find a main image
      const mainImg = images.find(img => img.is_main);
      if (mainImg?.image_url) {
        return mainImg.image_url;
      }
      // If no main image, use the first image with a valid URL
      const validImg = images.find(img =>
        img.image_url && img.image_url.match(/\.(jpg|jpeg|png|webp|gif)$/i)
      );
      if (validImg?.image_url) {
        return validImg.image_url;
      }
      // Last resort - use first image's URL
      return images[0].image_url;
    }
    return undefined;
  }, [images, main_image]);

  // State để handle error loading ảnh
  const [imageError, setImageError] = React.useState(false);

  const handleImageError = useCallback(() => {
    setImageError(true);
  }, []);

  const handleViewDetails = useCallback(() => {
    navigate(`/room-types/${id}`);
  }, [navigate, id]);



  const actualRoomType = useMemo(() => {
    const code = room_code ? room_code.toLowerCase() : 'deluxe'; // Fallback to 'deluxe' if room_code is undefined
    if (code.includes('deluxe')) return 'deluxe';
    if (code.includes('premium')) return 'premium';
    if (code.includes('suite')) return 'suite';
    if (code.includes('presidential')) return 'presidential';
    if (code.includes('level')) return 'theLevel';
    return 'deluxe'; // Default if no match found
  }, [room_code]);

  const themeColors = useMemo(() => {
    // Thiết kế minimalist, chỉ dùng màu tinh tế
    return {
      primary: "#1f2937", // Charcoal gray
      secondary: "#6b7280", // Medium gray
      accent: "#d1d5db", // Light gray
      text: "#111827", // Almost black
      border: "#e5e7eb", // Very light gray
      light: "#f9fafb" // Off white
    };
  }, []);

  const cardStyle = useMemo(() => {
    return {
      ...style,
      border: "1px solid #f3f4f6",
      background: "#ffffff",
      borderRadius: "12px",
      boxShadow: "0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)",
      transition: "all 0.3s ease",
    };
  }, [style]);




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
      className={`room-type-card overflow-hidden transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 ${className}`}
      style={cardStyle}
      bordered={false}
      bodyStyle={{
        padding: 0,
        height: "100%",
        display: "flex",
        flexDirection: "column",
      }}
    >
      {/* Image Section */}
      <div className="relative overflow-hidden h-48 group">
        {displayImage && !imageError ? (
          <>
            <img
              src={displayImage}
              alt={name}
              onError={handleImageError}
              className="w-full h-full object-cover transition-all duration-700 group-hover:scale-105"
            />
            <div className="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
          </>
        ) : (
          <div className="w-full h-full flex items-center justify-center bg-gray-100">
            <HomeOutlined className="text-4xl text-gray-400" />
          </div>
        )}

        {/* Room Type Badge */}
        <div className="absolute top-3 left-3">
          <div className=" backdrop-blur-sm px-3 py-1.5 rounded-full shadow-lg">
            <div className="flex items-center gap-1.5">
              <CrownOutlined className="text-gray-700 text-xs" />
              <span className="text-xs font-medium text-gray-700">{roomTypeDisplayName}</span>
            </div>
          </div>
        </div>

        {/* Rating Badge */}
        {rating && rating > 0 && (
          <div className="absolute top-3 right-3">
            <div className="bg-white/90 backdrop-blur-sm px-2.5 py-1 rounded-full shadow-lg">
              <div className="flex items-center gap-1">
                <StarFilled className="text-yellow-500 text-xs" />
                <span className="text-xs font-semibold text-gray-700">{rating}</span>
              </div>
            </div>
          </div>
        )}
      </div>

      {/* Content Section */}
      <div className="p-6 flex-1 flex flex-col">
        {/* Title */}
        <div className="mb-4">
          <Title
            level={4}
            className="mb-0 font-semibold leading-tight"
            style={{
              color: themeColors.text,
              fontSize: '1.125rem',
              lineHeight: '1.4',
              margin: 0
            }}
          >
            {name}
          </Title>
        </div>

        {/* Room Details */}
        <div className="flex items-center gap-6 mb-4 text-sm text-gray-600">
          {room_area && (
            <div className="flex items-center gap-1.5">
              <HomeOutlined className="text-gray-400" />
              <span className="font-medium">{room_area}m²</span>
            </div>
          )}
          <div className="flex items-center gap-1.5">
            <TeamOutlined className="text-gray-400" />
            <span className="font-medium">{max_guests} khách</span>
          </div>
          {view && (
            <div className="flex items-center gap-1.5">
              <EyeOutlined className="text-gray-400" />
              <span className="font-medium truncate">{view}</span>
            </div>
          )}
        </div>

        {/* Amenities */}
        {((highlighted_amenities && highlighted_amenities.length > 0) || (mainAmenities && mainAmenities.length > 0)) && (
          <div className="mb-6">
            <div className="flex items-center gap-2 mb-3">
              <ThunderboltOutlined className="text-gray-500 text-sm" />
              <span className="text-sm font-medium text-gray-700">Tiện nghi nổi bật</span>
            </div>
            <div className="grid grid-cols-2 gap-2">
              {(highlighted_amenities && highlighted_amenities.length > 0
                ? highlighted_amenities.slice(0, 6)
                : (mainAmenities || []).slice(0, 6)
              ).map((amenity: any, index: number) => (
                <div
                  key={typeof amenity === 'string' ? index : amenity.id}
                  className="flex items-center gap-2 text-xs text-gray-600"
                >
                  <CheckCircleOutlined className="text-green-500 text-xs flex-shrink-0" />
                  <span className="truncate">
                    {typeof amenity === 'string' ? amenity : amenity.name}
                  </span>
                </div>
              ))}
            </div>
          </div>
        )}

        {/* Pricing & CTA */}
        <div className="mt-auto">
          <div className="flex items-center justify-between mb-4">
            <div>
              <div className="text-2xl font-bold text-gray-900">
                {adjusted_price}
              </div>
              <div className="text-sm text-gray-500">mỗi đêm</div>
            </div>
          </div>

          <Button
            type="primary"
            icon={<ArrowRightOutlined />}
            onClick={handleViewDetails}
            size="large"
            className="w-full font-medium"
            style={{
              backgroundColor: themeColors.primary,
              borderColor: themeColors.primary,
              borderRadius: "8px",
              height: "44px",
              boxShadow: "0 2px 4px rgba(0, 0, 0, 0.1)",
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



