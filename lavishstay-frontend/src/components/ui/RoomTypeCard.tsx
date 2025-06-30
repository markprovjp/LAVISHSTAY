




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
  DesktopOutlined, // For TV
  UserOutlined, // For Bathrobe
} from "@ant-design/icons";
import { Palette, Footprints } from 'lucide-react'; // For Palette and Slippers
import { theme } from "antd";
import { getIcon } from "../../constants/Icons";

const { Title } = Typography;



const RoomTypeCard: React.FC<RoomTypeProps> = ({
  id,
  name,
  images,
  main_image,
  room_area,
  view,
adjusted_price,

  amenities,
  highlighted_amenities,
  mainAmenities,
  roomType = "deluxe",
  rating,
  style = {},
  max_guests,
  className = "",
 

  base_price,

  room_code,
}) => {
  const { token } = theme.useToken();
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
            level={4}
            className="mb-2 font-bold text-gray-800 leading-tight"
            style={{ color: themeColors.text, margin: 0 }}
          >
            {name}
          </Title>
        </div>

        <div className="space-y-1 mb-3">
          {/* Compact info row */}
          <div className="flex items-center justify-between text-sm text-gray-600">
            <div className="flex items-center gap-3">
              {room_area && (
                <div className="flex items-center gap-1">
                  <HomeOutlined style={{ color: themeColors.primary, fontSize: '14px' }} />
                  <span>{room_area}m²</span>
                </div>
              )}
              <div className="flex items-center gap-1">
                <TeamOutlined style={{ color: themeColors.primary, fontSize: '14px' }} />
                <span>{max_guests} khách</span>
              </div>
              {view && (
                <div className="flex items-center gap-1">
                  <EyeOutlined style={{ color: themeColors.primary, fontSize: '14px' }} />
                  <span className="truncate">{view}</span>
                </div>
              )}
            </div>
            {rating && rating > 0 && (
              <div className="flex items-center gap-1">
                <StarFilled style={{ color: '#FFD700', fontSize: '14px' }} />
                <span className="text-yellow-600 font-medium">{rating}</span>
              </div>
            )}
          </div>
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
                  className="text-sx px-0 py-0 rounded-full border-0 mb-1 flex items-center"
                  style={{
                    backgroundColor: themeColors.secondary,
                    color: themeColors.text,
                    fontSize: '8px'
                  }}
                >
                  {typeof amenity === 'string' ? amenity : (
                    <span className="flex items-center">
                      {getIcon(amenity.icon, amenity.icon_lib) && <span className="mr-1" style={{ fontSize: '10px' }}>{getIcon(amenity.icon, amenity.icon_lib)}</span>}
                      <span className="truncate ">{amenity.name}</span>
                    </span>
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
            <div className="text-xl font-bold" style={{ color: themeColors.primary }}>
              {adjusted_price}
            </div>
            <div className="text-sm text-gray-500">mỗi đêm</div>
          </div>

          {/* Compact button */}
          <Button
            type="primary"
            icon={<ArrowRightOutlined />}
            onClick={handleViewDetails}
            size="middle"
            className="w-full transition-all duration-300 font-medium"
            style={{
              borderRadius: "6px",
              backgroundColor: themeColors.primary,
              border: 'none',
              height: "40px",
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



