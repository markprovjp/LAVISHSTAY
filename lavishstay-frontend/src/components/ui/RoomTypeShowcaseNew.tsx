import React, { useMemo } from "react";
import {
    Card,
    Typography,
    Button,
    Row,
    Col,
    Image,
    Badge,
    Carousel,
    Space,
    Flex,
    Tag
} from "antd";
import {
    HomeOutlined,
    StarFilled,
    PictureOutlined,
    ThunderboltOutlined,
    GiftOutlined,
    EyeOutlined,
    AreaChartOutlined,
    UserOutlined,
    CrownOutlined,
    LeftOutlined,
    RightOutlined,
    FullscreenOutlined,
    CalendarOutlined
} from "@ant-design/icons";
import { motion } from "framer-motion";
import { Bed, Package } from "lucide-react";
import { useNavigate } from "react-router-dom";
import AmenityDisplay from '../common/AmenityDisplay';
import './RoomTypeShowcase.css';

interface SearchResult {
    data: RoomTypeData[];
}

interface RoomTypeData {
    room_type_id: number;
    room_type_name: string;
    bed_type_name?: string;
    room_code: string;
    description?: string;
    size: number;
    max_guests: number;
    rating: number;
    base_price: string;
    adjusted_price: number;
    available_rooms: string;
    rooms_needed: number;
    images?: Array<{
        id: number;
        room_type_id: number;
        image_url: string;
        alt_text?: string;
        is_main?: number;
    }>;
    main_image?: {
        id: number;
        room_type_id: number;
        image_url: string;
        alt_text?: string;
        is_main?: number;
    };
    amenities?: Array<{
        id: number;
        name: string;
        icon: string;
        category: string;
        description: string;
    }>;
    highlighted_amenities?: Array<{
        id: number;
        name: string;
        icon: string;
        category: string;
        description: string;
    }>;
    package_options?: Array<{
        package_id: number;
        package_name: string;
        package_description: string;
        price_modifier_vnd: string;
        price_per_room_per_night: number;
        total_package_price: number;
        services: any[];
        pricing_breakdown: {
            base_price_per_night: string;
            adjusted_price_per_night: number;
            package_modifier: string;
            final_price_per_room_per_night: number;
            rooms_needed: number;
            nights: number;
            total_price: number;
            currency: string;
        };
    }>;
    cheapest_package_price: number;
    search_criteria: {
        guest_count: string;
        check_in_date: string;
        check_out_date: string;
        nights: number;
    };
}

interface RoomTypeShowcaseProps {
    searchResult?: SearchResult;
}

const RoomTypeShowcase: React.FC<RoomTypeShowcaseProps> = ({ searchResult }) => {
    const navigate = useNavigate();
    const roomTypes = useMemo(() => searchResult?.data || [], [searchResult]);

    // Early return if no data
    if (!roomTypes || roomTypes.length === 0) {
        return (
            <div style={{ textAlign: 'center', padding: '40px' }}>
                <Typography.Title level={4} type="secondary">
                    Không có phòng nào phù hợp với tiêu chí tìm kiếm
                </Typography.Title>
            </div>
        );
    }

    // Format giá VND
    const formatVND = (price: number | null | undefined) => {
        if (!price || price === 0) return "Liên hệ";
        return new Intl.NumberFormat("vi-VN", {
            style: "currency",
            currency: "VND",
        }).format(price);
    };

    // Get room type styling
    const getRoomTypeStyle = (roomCode: string) => {
        if (roomCode.includes('the_level')) {
            return { color: '#722ed1', bgColor: '#f9f0ff', label: 'The Level' };
        }
        if (roomCode.includes('presidential')) {
            return { color: '#eb2f96', bgColor: '#fff0f6', label: 'Presidential' };
        }
        if (roomCode.includes('suite')) {
            return { color: '#13c2c2', bgColor: '#e6fffb', label: 'Suite' };
        }
        if (roomCode.includes('premium')) {
            return { color: '#fa8c16', bgColor: '#fff7e6', label: 'Premium' };
        }
        return { color: '#1890ff', bgColor: '#e6f7ff', label: 'Deluxe' };
    };

    // Navigate to room details
    const handleViewDetails = (roomType: RoomTypeData) => {
        navigate(`/room-types/${roomType.room_type_id}`);
    };

    const formatBeds = (bedType: string): React.ReactNode => {
        const parts = bedType.split(', ');
        return parts.map((part, index) => (
            <span key={index} className="flex items-center gap-1">
                <Bed size={14} />
                {part}
                {index < parts.length - 1 && <span className="mx-1">•</span>}
            </span>
        ));
    };

    // Custom arrows cho carousel
    const CustomArrow = ({
        direction,
        onClick
    }: {
        direction: 'left' | 'right';
        onClick?: () => void
    }) => (
        <Button
            type="text"
            className={`absolute top-1/2 transform -translate-y-1/2 z-10 ${direction === 'left' ? 'left-2' : 'right-2'
                } bg-white/80 hover:bg-white border-0 shadow-lg`}
            style={{
                width: '40px',
                height: '40px',
                borderRadius: '50%',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center'
            }}
            icon={direction === 'left' ? <LeftOutlined /> : <RightOutlined />}
            onClick={onClick}
        />
    );

    return (
        <motion.div
            className="room-type-showcase"
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
        >
            <motion.div
                style={{ marginBottom: "32px", textAlign: "center" }}
                initial={{ opacity: 0, y: -20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ delay: 0.2, duration: 0.5 }}
            >
                <Typography.Title level={2} style={{ margin: "0 0 8px 0" }}>
                    <CrownOutlined style={{ marginRight: "12px", color: "#d4af37" }} />
                    Available Room Types
                </Typography.Title>
                <Typography.Text type="secondary" style={{ fontSize: "16px" }}>
                    Discover our luxurious accommodations
                </Typography.Text>
            </motion.div>

            <Space direction="vertical" size="large" style={{ width: "100%" }}>
                {roomTypes.map((roomType, index) => {
                    const roomStyle = getRoomTypeStyle(roomType.room_code);
                    const cheapestPackage = roomType.package_options?.[0];

                    return (
                        <motion.div
                            key={roomType.room_type_id}
                            initial={{ opacity: 0, x: -50 }}
                            animate={{ opacity: 1, x: 0 }}
                            transition={{ delay: index * 0.1, duration: 0.5 }}
                        >
                            <Card
                                hoverable
                                className="horizontal-room-card"
                                style={{
                                    borderRadius: "16px",
                                    overflow: "hidden",
                                    boxShadow: "0 8px 32px rgba(0, 0, 0, 0.1)",
                                    background: "linear-gradient(145deg, #ffffff 0%, #f8fafc 100%)",
                                    border: `2px solid ${roomStyle.color}20`,
                                }}
                            >
                                <Row gutter={0}>
                                    {/* Image Section */}
                                    <Col xs={24} md={10}>
                                        <div style={{ position: "relative", height: "350px" }}>
                                            <Badge.Ribbon
                                                text={
                                                    <Flex align="center" gap={4}>
                                                        <CrownOutlined />
                                                        {roomStyle.label}
                                                    </Flex>
                                                }
                                                color={roomStyle.color}
                                                style={{
                                                    top: '12px',
                                                    right: '-5px',
                                                    zIndex: 10
                                                }}
                                            >
                                                <Carousel
                                                    autoplay
                                                    autoplaySpeed={4000}
                                                    dots
                                                    dotPosition="bottom"
                                                    arrows
                                                    prevArrow={<CustomArrow direction="left" />}
                                                    nextArrow={<CustomArrow direction="right" />}
                                                    style={{ height: "350px" }}
                                                    className="room-carousel"
                                                >
                                                    {(roomType.images && roomType.images.length > 0
                                                        ? roomType.images
                                                        : roomType.main_image
                                                            ? [roomType.main_image]
                                                            : []
                                                    ).map((image, imgIndex) => (
                                                        <div key={imgIndex} style={{ height: "350px" }}>
                                                            <Image
                                                                src={image.image_url}
                                                                alt={image.alt_text || `${roomType.room_type_name} - Image ${imgIndex + 1}`}
                                                                style={{
                                                                    width: "100%",
                                                                    height: "350px",
                                                                    objectFit: "cover",
                                                                }}
                                                                preview={{
                                                                    mask: (
                                                                        <Flex align="center" gap={8}>
                                                                            <EyeOutlined />
                                                                            <FullscreenOutlined />
                                                                            <span>Preview Gallery</span>
                                                                        </Flex>
                                                                    ),
                                                                }}
                                                            />
                                                        </div>
                                                    ))}
                                                </Carousel>
                                            </Badge.Ribbon>

                                            {/* Availability tag */}
                                            <div className="absolute bottom-4 left-4">
                                                <Tag
                                                    color="green"
                                                    className="px-3 py-1 text-sm font-medium"
                                                >
                                                    <HomeOutlined className="mr-1" />
                                                    {roomType.available_rooms || '0'} phòng còn trống
                                                </Tag>
                                            </div>
                                        </div>
                                    </Col>

                                    {/* Content Section */}
                                    <Col xs={24} md={14}>
                                        <div style={{ padding: "24px 32px" }}>
                                            {/* Header */}
                                            <Flex justify="space-between" align="flex-start" style={{ marginBottom: "20px" }}>
                                                <div className="flex-1">
                                                    <Typography.Title
                                                        level={3}
                                                        style={{ margin: "0 0 8px 0", color: "#2c3e50" }}
                                                    >
                                                        <HomeOutlined style={{ marginRight: "12px", color: roomStyle.color }} />
                                                        {roomType.room_type_name}
                                                    </Typography.Title>
                                                    <Flex gap={12} align="center" wrap="wrap">
                                                        <Tag color={roomStyle.color} className="px-2 py-1">
                                                            {roomType.room_code.replace(/_/g, ' ').toUpperCase()}
                                                        </Tag>
                                                        {(roomType.rating && roomType.rating > 0) && (
                                                            <Flex gap={4} align="center">
                                                                <StarFilled style={{ color: "#ffd700", fontSize: "16px" }} />
                                                                <Typography.Text strong style={{ fontSize: "14px" }}>
                                                                    {roomType.rating.toFixed(1)}
                                                                </Typography.Text>
                                                            </Flex>
                                                        )}
                                                    </Flex>
                                                </div>

                                                <div className="text-right">
                                                    <Typography.Text
                                                        type="secondary"
                                                        style={{ fontSize: "12px" }}
                                                        className="block"
                                                    >
                                                        Từ
                                                    </Typography.Text>
                                                    <Typography.Title
                                                        level={4}
                                                        style={{
                                                            margin: 0,
                                                            color: roomStyle.color,
                                                            fontSize: "24px"
                                                        }}
                                                    >
                                                        {formatVND(roomType.cheapest_package_price || 0)}
                                                    </Typography.Title>
                                                    <Typography.Text
                                                        type="secondary"
                                                        style={{ fontSize: "11px" }}
                                                    >
                                                        /{roomType.search_criteria?.nights || 1} đêm
                                                    </Typography.Text>
                                                </div>
                                            </Flex>

                                            {/* Description */}
                                            {roomType.description && (
                                                <div style={{ marginBottom: "20px" }}>
                                                    <Typography.Paragraph
                                                        ellipsis={{ rows: 2, expandable: true, symbol: 'xem thêm' }}
                                                        style={{ color: "#666", marginBottom: 0 }}
                                                    >
                                                        {roomType.description}
                                                    </Typography.Paragraph>
                                                </div>
                                            )}

                                            {/* Statistics Grid */}
                                            <Row gutter={[16, 16]} style={{ marginBottom: "20px" }}>
                                                <Col span={6}>
                                                    <div className="text-center p-3" style={{ backgroundColor: '#f0f9ff', borderRadius: '8px' }}>
                                                        <UserOutlined style={{ fontSize: '18px', color: '#1890ff', marginBottom: '4px' }} />
                                                        <div style={{ fontSize: '16px', fontWeight: 'bold', color: '#1890ff' }}>
                                                            {roomType.max_guests}
                                                        </div>
                                                        <div style={{ fontSize: '11px', color: '#666' }}>Khách</div>
                                                    </div>
                                                </Col>
                                                <Col span={6}>
                                                    <div className="text-center p-3" style={{ backgroundColor: '#f6ffed', borderRadius: '8px' }}>
                                                        <AreaChartOutlined style={{ fontSize: '18px', color: '#52c41a', marginBottom: '4px' }} />
                                                        <div style={{ fontSize: '16px', fontWeight: 'bold', color: '#52c41a' }}>
                                                            {roomType.size}m²
                                                        </div>
                                                        <div style={{ fontSize: '11px', color: '#666' }}>Diện tích</div>
                                                    </div>
                                                </Col>
                                                <Col span={6}>
                                                    <div className="text-center p-3" style={{ backgroundColor: '#fff7e6', borderRadius: '8px' }}>
                                                        <Package size={18} style={{ color: '#fa8c16', marginBottom: '4px' }} />
                                                        <div style={{ fontSize: '16px', fontWeight: 'bold', color: '#fa8c16' }}>
                                                            {roomType.package_options?.length || 0}
                                                        </div>
                                                        <div style={{ fontSize: '11px', color: '#666' }}>Gói</div>
                                                    </div>
                                                </Col>
                                                <Col span={6}>
                                                    <div className="text-center p-3" style={{ backgroundColor: '#f9f0ff', borderRadius: '8px' }}>
                                                        <PictureOutlined style={{ fontSize: '18px', color: '#722ed1', marginBottom: '4px' }} />
                                                        <div style={{ fontSize: '16px', fontWeight: 'bold', color: '#722ed1' }}>
                                                            {((roomType.images?.length || 0) + (roomType.main_image ? 1 : 0)) || 0}
                                                        </div>
                                                        <div style={{ fontSize: '11px', color: '#666' }}>Hình ảnh</div>
                                                    </div>
                                                </Col>
                                            </Row>

                                            {/* Bed Configuration */}
                                            {roomType.bed_type_name && (
                                                <div style={{ marginBottom: "20px" }}>
                                                    <Flex gap={8} align="center" style={{ marginBottom: "8px" }}>
                                                        <Bed size={16} style={{ color: roomStyle.color }} />
                                                        <Typography.Text strong style={{ color: "#34495e" }}>
                                                            Loại giường:
                                                        </Typography.Text>
                                                    </Flex>
                                                    <Flex gap={8} wrap="wrap">
                                                        {formatBeds(roomType.bed_type_name)}
                                                    </Flex>
                                                </div>
                                            )}

                                            {/* Package Options Preview */}
                                            {cheapestPackage && (
                                                <div style={{ marginBottom: "20px" }}>
                                                    <Flex justify="space-between" align="center" style={{ marginBottom: "8px" }}>
                                                        <Typography.Text strong style={{ color: "#34495e" }}>
                                                            <GiftOutlined style={{ marginRight: "8px", color: "#faad14" }} />
                                                            Gói phổ biến:
                                                        </Typography.Text>
                                                        <Typography.Text type="secondary" style={{ fontSize: "12px" }}>
                                                            +{(roomType.package_options?.length || 1) - 1} gói khác
                                                        </Typography.Text>
                                                    </Flex>
                                                    <div
                                                        style={{
                                                            padding: "12px 16px",
                                                            backgroundColor: roomStyle.bgColor,
                                                            borderRadius: "8px",
                                                            border: `1px solid ${roomStyle.color}30`
                                                        }}
                                                    >
                                                        <Flex justify="space-between" align="center">
                                                            <div>
                                                                <Typography.Text strong style={{ color: roomStyle.color }}>
                                                                    {cheapestPackage.package_name}
                                                                </Typography.Text>
                                                                <div style={{ fontSize: "12px", color: "#666", marginTop: "2px" }}>
                                                                    {cheapestPackage.package_description}
                                                                </div>
                                                            </div>
                                                            <Typography.Text strong style={{ color: roomStyle.color, fontSize: "16px" }}>
                                                                {formatVND(cheapestPackage.total_package_price || 0)}
                                                            </Typography.Text>
                                                        </Flex>
                                                    </div>
                                                </div>
                                            )}

                                            {/* Amenities */}
                                            {(roomType.highlighted_amenities && roomType.highlighted_amenities.length > 0) && (
                                                <div style={{ marginBottom: "24px" }}>
                                                    <Typography.Text
                                                        strong
                                                        style={{
                                                            color: "#34495e",
                                                            fontSize: "14px",
                                                            display: "block",
                                                            marginBottom: "8px"
                                                        }}
                                                    >
                                                        <ThunderboltOutlined style={{ marginRight: "8px", color: "#faad14" }} />
                                                        Tiện nghi nổi bật:
                                                    </Typography.Text>
                                                    <AmenityDisplay
                                                        amenities={roomType.highlighted_amenities}
                                                        maxDisplay={6}
                                                        layout="inline"
                                                    />
                                                </div>
                                            )}

                                            {/* Action Buttons */}
                                            <Flex gap={12} justify="flex-end">
                                                <Button
                                                    size="large"
                                                    icon={<EyeOutlined />}
                                                    onClick={() => handleViewDetails(roomType)}
                                                    style={{
                                                        borderColor: roomStyle.color,
                                                        color: roomStyle.color,
                                                        borderRadius: "8px",
                                                        height: "44px",
                                                        fontSize: "14px",
                                                        fontWeight: "500",
                                                        padding: "0 24px",
                                                    }}
                                                >
                                                    Xem chi tiết
                                                </Button>
                                                <Button
                                                    type="primary"
                                                    size="large"
                                                    icon={<CalendarOutlined />}
                                                    onClick={() => handleViewDetails(roomType)}
                                                    style={{
                                                        background: `linear-gradient(135deg, ${roomStyle.color} 0%, ${roomStyle.color}dd 100%)`,
                                                        border: "none",
                                                        borderRadius: "8px",
                                                        height: "44px",
                                                        fontSize: "14px",
                                                        fontWeight: "500",
                                                        padding: "0 28px",
                                                        boxShadow: `0 4px 12px ${roomStyle.color}40`
                                                    }}
                                                >
                                                    Đặt phòng ngay
                                                </Button>
                                            </Flex>
                                        </div>
                                    </Col>
                                </Row>
                            </Card>
                        </motion.div>
                    );
                })}
            </Space>
        </motion.div>
    );
};

export default RoomTypeShowcase;
