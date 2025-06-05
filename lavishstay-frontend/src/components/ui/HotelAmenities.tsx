import React from "react";
import { Card, Typography, Rate, Row, Col, Divider, Tag } from "antd";
import {
  WifiOutlined,
  CarOutlined,
  CustomerServiceOutlined,
  SafetyOutlined,
  ShopOutlined,
  UsergroupAddOutlined,
  PhoneOutlined,
} from "@ant-design/icons";
import {
  Waves,
  Dumbbell,
  Baby,
  Car,
  Wifi,
  Shield,
  Coffee,
  Users,
  Accessibility,
  Languages,
  MapPin,
  Calendar,
  Utensils,
  ShoppingCart,
  Building,
  TreePine,
  CigaretteOff,
  Projector,
  Sparkles,
  Settings,
  Heart,
  Clock3,
} from "lucide-react";
import "./HotelAmenities.css";

const { Title, Text } = Typography;

interface AmenityItem {
  icon: React.ReactNode;
  name: string;
  description?: string;
}

interface AmenityCategory {
  title: string;
  icon: React.ReactNode;
  items: AmenityItem[];
}

const HotelAmenities: React.FC = () => {
  const overallRating = 9.4;
  // Sắp xếp lại các categories theo độ dài để cân bằng layout
  const amenityCategories: AmenityCategory[] = [
    // Row 1: Các categories có số lượng items trung bình (6-8 items)
    {
      title: "Thư giãn & Vui chơi giải trí",
      icon: <Waves className="w-5 h-5" />,
      items: [
        {
          icon: <Waves className="w-4 h-4" />,
          name: "Bể bơi ngoài trời",
          description: "Bể bơi rộng với tầm nhìn panorama",
        },
        {
          icon: <Waves className="w-4 h-4" />,
          name: "Bể bơi trong nhà",
          description: "Bể bơi sưởi ấm quanh năm",
        },
        {
          icon: <Heart className="w-4 h-4" />,
          name: "Spa & Massage",
          description: "Dịch vụ massage chuyên nghiệp",
        },
        {
          icon: <Dumbbell className="w-4 h-4" />,
          name: "Phòng tập thể hình",
          description: "Trang thiết bị hiện đại 24/7",
        },
        {
          icon: <Sparkles className="w-4 h-4" />,
          name: "Sauna & Steam",
          description: "Phòng xông hơi khô và ướt",
        },
        {
          icon: <MapPin className="w-4 h-4" />,
          name: "Tour du lịch",
          description: "Tổ chức tour khám phá địa phương",
        },
        {
          icon: <Calendar className="w-4 h-4" />,
          name: "Dịch vụ đặt vé",
          description: "Hỗ trợ đặt vé tham quan",
        },
      ],
    },
    {
      title: "An toàn & Bảo mật",
      icon: <Shield className="w-5 h-5" />,
      items: [
        {
          icon: <SafetyOutlined />,
          name: "Bảo vệ 24/7",
          description: "An ninh chuyên nghiệp",
        },
        {
          icon: <SafetyOutlined />,
          name: "Camera giám sát",
          description: "Hệ thống camera toàn khách sạn",
        },
        {
          icon: <SafetyOutlined />,
          name: "Két sắt cá nhân",
          description: "Két sắt điện tử trong phòng",
        },
        {
          icon: <SafetyOutlined />,
          name: "Hệ thống báo cháy",
          description: "Thiết bị báo cháy hiện đại",
        },
        {
          icon: <SafetyOutlined />,
          name: "Khử trùng định kỳ",
          description: "Vệ sinh y tế đạt tiêu chuẩn",
        },
        {
          icon: <SafetyOutlined />,
          name: "Thanh toán không tiền mặt",
          description: "Hỗ trợ thẻ và ví điện tử",
        },
        {
          icon: <SafetyOutlined />,
          name: "Kit sơ cứu",
          description: "Trang bị y tế cơ bản",
        },
      ],
    },
    {
      title: "Dịch vụ khách sạn",
      icon: <Settings className="w-5 h-5" />,
      items: [
        {
          icon: <PhoneOutlined />,
          name: "Lễ tân 24/7",
          description: "Hỗ trợ khách hàng mọi lúc",
        },
        {
          icon: <Settings className="w-4 h-4" />,
          name: "Dịch vụ phòng",
          description: "Room service 24 giờ",
        },
        {
          icon: <Settings className="w-4 h-4" />,
          name: "Giặt ủi",
          description: "Giặt ủi chuyên nghiệp",
        },
        {
          icon: <Settings className="w-4 h-4" />,
          name: "Giữ hành lý",
          description: "Bảo quản hành lý miễn phí",
        },
        {
          icon: <Settings className="w-4 h-4" />,
          name: "Đổi ngoại tệ",
          description: "Hỗ trợ đổi tiền tại khách sạn",
        },
        {
          icon: <CustomerServiceOutlined />,
          name: "Dịch vụ văn phòng",
          description: "Fax, photocopy, in ấn",
        },
        {
          icon: <Building className="w-4 h-4" />,
          name: "Thang máy",
          description: "Thang máy cao tốc",
        },
      ],
    },

    // Row 2: Các categories có số lượng items ít (3-5 items)
    {
      title: "Truy cập Internet",
      icon: <Wifi className="w-5 h-5" />,
      items: [
        {
          icon: <WifiOutlined />,
          name: "Wi-Fi miễn phí",
          description: "Tốc độ cao trong tất cả khu vực",
        },
        {
          icon: <WifiOutlined />,
          name: "Wi-Fi phòng",
          description: "Kết nối riêng biệt cho từng phòng",
        },
        {
          icon: <WifiOutlined />,
          name: "Wi-Fi sự kiện",
          description: "Băng thông rộng cho hội nghị",
        },
        {
          icon: <WifiOutlined />,
          name: "Internet cáp quang",
          description: "Đường truyền ổn định",
        },
      ],
    },
    {
      title: "Đi lại & Giao thông",
      icon: <Car className="w-5 h-5" />,
      items: [
        {
          icon: <CarOutlined />,
          name: "Bãi đỗ xe miễn phí",
          description: "200+ chỗ đỗ xe an toàn",
        },
        {
          icon: <CarOutlined />,
          name: "Dịch vụ valet",
          description: "Nhân viên đỗ xe chuyên nghiệp",
        },
        {
          icon: <CarOutlined />,
          name: "Thuê xe",
          description: "Dịch vụ thuê xe du lịch",
        },
        {
          icon: <CarOutlined />,
          name: "Taxi & Shuttle",
          description: "Đưa đón sân bay",
        },
      ],
    },
    {
      title: "Tiện nghi gia đình",
      icon: <Baby className="w-5 h-5" />,
      items: [
        {
          icon: <Baby className="w-4 h-4" />,
          name: "Phòng gia đình",
          description: "Phòng rộng cho gia đình đông người",
        },
        {
          icon: <Waves className="w-4 h-4" />,
          name: "Bể bơi trẻ em",
          description: "Khu vực bơi an toàn cho trẻ",
        },
        {
          icon: <Baby className="w-4 h-4" />,
          name: "Dịch vụ giữ trẻ",
          description: "Nhân viên chăm sóc trẻ chuyên nghiệp",
        },
        {
          icon: <Users className="w-4 h-4" />,
          name: "Khu vui chơi trẻ em",
          description: "Sân chơi trong nhà và ngoài trời",
        },
      ],
    },

    // Row 3: Các categories có số lượng items ít
    {
      title: "Hội nghị & Sự kiện",
      icon: <Building className="w-5 h-5" />,
      items: [
        {
          icon: <Building className="w-4 h-4" />,
          name: "Phòng hội nghị",
          description: "5 phòng họp với sức chứa đa dạng",
        },
        {
          icon: <Projector className="w-4 h-4" />,
          name: "Thiết bị AV",
          description: "Máy chiếu 4K, âm thanh chuyên nghiệp",
        },
        {
          icon: <TreePine className="w-4 h-4" />,
          name: "Sự kiện ngoài trời",
          description: "Vườn tiệc với không gian mở",
        },
        {
          icon: <UsergroupAddOutlined />,
          name: "Tổ chức tiệc",
          description: "Hỗ trợ tổ chức tiệc cưới, sinh nhật",
        },
      ],
    },
    {
      title: "Khả năng tiếp cận",
      icon: <Accessibility className="w-5 h-5" />,
      items: [
        {
          icon: <Accessibility className="w-4 h-4" />,
          name: "Phù hợp xe lăn",
          description: "Toàn bộ khu vực có đường dốc",
        },
        {
          icon: <Languages className="w-4 h-4" />,
          name: "Đa ngôn ngữ",
          description: "Tiếng Việt, Anh, Hàn, Nhật",
        },
        {
          icon: <Heart className="w-4 h-4" />,
          name: "Thú cưng được phép",
          description: "Chấp nhận thú cưng có điều kiện",
        },
        {
          icon: <CigaretteOff className="w-4 h-4" />,
          name: "Hoàn toàn không khói",
          description: "Môi trường trong lành",
        },
      ],
    },
    {
      title: "Mua sắm & Giải trí",
      icon: <ShopOutlined className="w-5 h-5" />,
      items: [
        {
          icon: <ShopOutlined />,
          name: "Cửa hàng lưu niệm",
          description: "Quà tặng đặc sản địa phương",
        },
        {
          icon: <ShoppingCart className="w-4 h-4" />,
          name: "Tạp hóa tiện lợi",
          description: "Mở cửa 24/7",
        },
        {
          icon: <Sparkles className="w-4 h-4" />,
          name: "Salon làm đẹp",
          description: "Dịch vụ làm tóc, nail chuyên nghiệp",
        },
        {
          icon: <Coffee className="w-4 h-4" />,
          name: "Quầy đồ ăn vặt",
          description: "Snack và đồ uống nhanh",
        },
      ],
    },
  ];
  const restaurants = [
    {
      name: "Orchid Restaurant",
      type: "Buffet quốc tế",
      description: "Nhà hàng sang trọng với thực đơn buffet đa dạng món Á - Âu",
      hours: "6:00 - 22:00",
      capacity: "150 khách",
      specialties: ["Hải sản tươi sống", "BBQ nướng", "Tráng miệng"],
      image: "🍽️",
    },
    {
      name: "Lotus Lounge",
      type: "Bar & Cocktail",
      description: "Không gian thư giãn với đồ uống cao cấp và view bể bơi",
      hours: "16:00 - 02:00",
      capacity: "80 khách",
      specialties: ["Cocktail độc đáo", "Rượu vang nhập khẩu", "Tapas"],
      image: "🍸",
    },
    {
      name: "Garden Café",
      type: "Café & Light meals",
      description: "Quán cà phê ngoài trời với không gian xanh mát",
      hours: "6:30 - 23:00",
      capacity: "60 khách",
      specialties: ["Cà phê specialty", "Bánh ngọt tự làm", "Salad tươi"],
      image: "☕",
    },
    {
      name: "Sushi Zen",
      type: "Nhà hàng Nhật Bản",
      description: "Ẩm thực Nhật Bản chính thống với đầu bếp người Nhật",
      hours: "17:00 - 23:00",
      capacity: "40 khách",
      specialties: ["Sushi premium", "Sashimi tươi", "Teppanyaki"],
      image: "🍣",
    },
  ];

  return (
    <div className="hotel-amenities-container">
      <div className="text-center mb-8">
        <Title level={2} className="mb-4">
          Tiện nghi và cơ sở vật chất
        </Title>
        <div className="flex items-center justify-center gap-2 mb-6">
          <Rate disabled defaultValue={5} className="text-2xl" />
          <Text className="text-2xl font-bold text-blue-600">
            {overallRating} điểm
          </Text>
        </div>
        <Text className="text-lg opacity-80">
          Khám phá đầy đủ tiện nghi và dịch vụ cao cấp tại LavishStay Thanh Hóa
        </Text>
      </div>
      <Row gutter={[24, 24]}>
        {amenityCategories.map((category, index) => (
          <Col xs={24} md={12} lg={8} key={index}>
            <div>
              <Card
                className="amenity-category-card h-full"
                title={
                  <div className="flex items-center gap-3">
                    <div className="amenity-category-icon">{category.icon}</div>
                    <Text className="font-semibold text-base">
                      {category.title}
                    </Text>
                  </div>
                }
                variant="borderless"
              >
                <div className="space-y-3">
                  {" "}
                  {category.items.map((item, itemIndex) => (
                    <div key={itemIndex} className="amenity-item">
                      <div className="flex items-center gap-3">
                        <div className="amenity-item-icon">{item.icon}</div>
                        <Text className="amenity-item-text">{item.name}</Text>
                      </div>
                      {item.description && (
                        <Text className="text-sm opacity-70 ml-7">
                          {item.description}
                        </Text>
                      )}
                    </div>
                  ))}
                </div>
              </Card>
            </div>
          </Col>
        ))}
      </Row>{" "}
      {/* Restaurants Section */}
      <div className="mt-12">
        <Divider>
          <Title level={3} className="mb-0 flex items-center gap-3">
            <Utensils className="w-6 h-6 " />
            Đồ ăn và nơi ăn uống
          </Title>
        </Divider>
        <Row gutter={[24, 32]} className="mt-8">
          {restaurants.map((restaurant, index) => (
            <Col xs={24} md={12} lg={6} key={index}>
              <Card
                className="restaurant-card h-full"
                cover={
                  <div className="restaurant-cover relative overflow-hidden">
                    <div className="absolute inset-0 "></div>
                    <div className="relative z-10 flex flex-col items-center justify-center h-full text-white">
                      <div className="text-4xl mb-2">{restaurant.image}</div>
                      <div className="text-lg font-semibold">
                        {restaurant.type}
                      </div>
                    </div>
                  </div>
                }
                actions={[
                  <div
                    key="hours"
                    className="flex items-center justify-center gap-1 text-xs"
                  >
                    <Clock3 className="w-3 h-3" />
                    {restaurant.hours}
                  </div>,
                  <div
                    key="capacity"
                    className="flex items-center justify-center gap-1 text-xs"
                  >
                    <Users className="w-3 h-3" />
                    {restaurant.capacity}
                  </div>,
                ]}
              >
                <div className="space-y-3">
                  <div>
                    <Title level={4} className="mb-1 text-lg">
                      {restaurant.name}
                    </Title>
                    <Text className="text-sm opacity-80 leading-relaxed">
                      {restaurant.description}
                    </Text>
                  </div>

                  <div>
                    <Text className="text-xs font-semibold  mb-2 block">
                      Món đặc biệt:
                    </Text>
                    <div className="flex flex-wrap gap-1">
                      {restaurant.specialties.map((specialty, idx) => (
                        <Tag key={idx} color="orange" className="text-xs mb-1">
                          {specialty}
                        </Tag>
                      ))}
                    </div>
                  </div>
                </div>
              </Card>
            </Col>
          ))}
        </Row>{" "}
        {/* Food Services Summary */}
        <div className="food-services-summary">
          <Row gutter={[24, 24]} align="middle">
            <Col xs={24} md={6}>
              <div className="food-service-item">
                <span className="food-service-emoji">🍽️</span>
                <Text className="font-semibold text-base">
                  Room Service 24/7
                </Text>
              </div>
            </Col>
            <Col xs={24} md={6}>
              <div className="food-service-item">
                <span className="food-service-emoji">🥘</span>
                <Text className="font-semibold text-base">Bữa sáng buffet</Text>
              </div>
            </Col>
            <Col xs={24} md={6}>
              <div className="food-service-item">
                <span className="food-service-emoji">🌱</span>
                <Text className="font-semibold text-base">Thực đơn chay</Text>
              </div>
            </Col>
            <Col xs={24} md={6}>
              <div className="food-service-item">
                <span className="food-service-emoji">🍷</span>
                <Text className="font-semibold text-base">
                  Hầm rượu cao cấp
                </Text>
              </div>
            </Col>
          </Row>
        </div>
      </div>
    </div>
  );
};

export default HotelAmenities;
