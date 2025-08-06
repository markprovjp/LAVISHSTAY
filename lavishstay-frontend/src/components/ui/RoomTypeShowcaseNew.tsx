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

const { Title, Text, Paragraph } = Typography;

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
                <Title level={4} type="secondary">
                    Không có phòng nào phù hợp với tiêu chí tìm kiếm
                </Title>
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

    const handleViewDetails = (roomType: RoomTypeData) => {
        navigate(`/room-types/${roomType.id}`);
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

    return (
        <motion.div
            className="room-type-showcase"
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
        >
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
                                    boxShadow: "0 12px 40px rgba(0, 0, 0, 0.15)",
                                    border: `1px solid ${roomStyle.color}40`,
                                }}
                            >
                                <Row gutter={[0, 24]} align="stretch">
                                    {/* Image Section */}
                                    <Col xs={24} md={12}>
                                        <div style={{ position: "relative", height: "100%", minHeight: "300px" }}>
                                            <Badge.Ribbon
                                                text={
                                                    <Flex align="center" gap={4}>
                                                        <CrownOutlined />
                                                        {roomStyle.label}
                                                    </Flex>
                                                }
                                                color={roomStyle.color}
                                                style={{
                                                    top: '16px',
                                                    right: '-5px',
                                                    zIndex: 10,
                                                    padding: '6px 12px',
                                                    fontSize: '13px'
                                                }}
                                            >
                                                <Image.PreviewGroup>
                                                    <Carousel
                                                        autoplay
                                                        autoplaySpeed={4000}
                                                        dots
                                                        dotPosition="bottom"
                                                        arrows={false} // Use custom arrows if needed, or rely on dots
                                                        style={{ height: "100%" }}
                                                        className="room-carousel"
                                                    >
                                                        {(roomType.images && roomType.images.length > 0
                                                            ? roomType.images
                                                            : roomType.main_image
                                                                ? [roomType.main_image]
                                                                : []
                                                        ).map((image, imgIndex) => (
                                                            <div key={imgIndex} style={{ height: "100%" }}>
                                                                <Image
                                                                    src={image.image_url}
                                                                    alt={image.alt_text || `${roomType.room_type_name} - Image ${imgIndex + 1}`}
                                                                    style={{
                                                                        width: "100%",
                                                                        height: "100%",
                                                                        objectFit: "cover",
                                                                        borderRadius: "16px 0 0 16px", // Rounded only on left side
                                                                    }}
                                                                />
                                                            </div>
                                                        ))}
                                                    </Carousel>
                                                </Image.PreviewGroup>
                                            </Badge.Ribbon>
                                        </div>
                                    </Col>

                                    {/* Content Section */}
                                    <Col xs={24} md={12}>
                                        <div style={{ padding: "24px 32px", display: "flex", flexDirection: "column", height: "100%" }}>
                                            {/* Header */}
                                            <Flex justify="space-between" align="flex-start" style={{ marginBottom: "16px" }}>
                                                <div className="flex-1">
                                                    <Title
                                                        level={3}
                                                        style={{ margin: "0 0 4px 0" }}
                                                    >
                                                        {roomType.room_type_name}
                                                    </Title>
                                                    <Flex gap={8} align="center" wrap="wrap">
                                                        <Tag color={roomStyle.color} className="px-2 py-1 text-xs">
                                                            {roomType.room_code.replace(/_/g, ' ').toUpperCase()}
                                                        </Tag>
                                                        {(roomType.rating && roomType.rating > 0) && (
                                                            <Flex gap={4} align="center">
                                                                <StarFilled style={{ color: "#ffc107", fontSize: "14px" }} />
                                                                <Text strong style={{ fontSize: "13px" }}>
                                                                    {roomType.rating.toFixed(1)}
                                                                </Text>
                                                            </Flex>
                                                        )}
                                                    </Flex>
                                                </div>

                                                <div className="text-right">
                                                    <Text
                                                        type="secondary"
                                                        style={{ fontSize: "12px" }}
                                                        className="block"
                                                    >
                                                        Giá từ
                                                    </Text>
                                                    <Title
                                                        level={4}
                                                        style={{
                                                            margin: 0,
                                                            color: roomStyle.color,
                                                            fontSize: "28px",
                                                            fontWeight: "700"
                                                        }}
                                                    >
                                                        {formatVND(roomType.cheapest_package_price || 0)}
                                                    </Title>
                                                    <Text
                                                        type="secondary"
                                                        style={{ fontSize: "11px" }}
                                                    >
                                                        /{roomType.search_criteria?.nights || 1} đêm
                                                    </Text>
                                                </div>
                                            </Flex>

                                            {/* Description */}
                                            {roomType.description && (
                                                <div style={{ marginBottom: "16px" }}>
                                                    <Paragraph
                                                        ellipsis={{ rows: 3, expandable: true, symbol: 'xem thêm' }}
                                                        style={{ marginBottom: 0, fontSize: "13px" }}
                                                    >
                                                        {roomType.description}
                                                    </Paragraph>
                                                </div>
                                            )}

                                            {/* Statistics Grid */}
                                            <Row gutter={[16, 16]} style={{ marginBottom: "20px" }}>
                                                <Col span={8}>
                                                    <div className="flex flex-col items-center p-3 bg-blue-50 rounded-lg shadow-sm">
                                                        <UserOutlined style={{ fontSize: '20px', color: '#1890ff', marginBottom: '4px' }} />
                                                        <Text strong style={{ fontSize: '15px', color: '#1890ff' }}>
                                                            {roomType.max_guests}
                                                        </Text>
                                                        <Text type="secondary" style={{ fontSize: '11px' }}>Khách</Text>
                                                    </div>
                                                </Col>
                                                <Col span={8}>
                                                    <div className="flex flex-col items-center p-3 bg-green-50 rounded-lg shadow-sm">
                                                        <AreaChartOutlined style={{ fontSize: '20px', color: '#52c41a', marginBottom: '4px' }} />
                                                        <Text strong style={{ fontSize: '15px', color: '#52c41a' }}>
                                                            {roomType.size}m²
                                                        </Text>
                                                        <Text type="secondary" style={{ fontSize: '11px' }}>Diện tích</Text>
                                                    </div>
                                                </Col>
                                                <Col span={8}>
                                                    <div className="flex flex-col items-center p-3 bg-purple-50 rounded-lg shadow-sm">
                                                        <Bed size={20} style={{ color: '#722ed1', marginBottom: '4px' }} />
                                                        <Text strong style={{ fontSize: '15px', color: '#722ed1' }}>
                                                            {roomType.bed_type_name ? roomType.bed_type_name.split(', ').length : 0}
                                                        </Text>
                                                        <Text type="secondary" style={{ fontSize: '11px' }}>Giường</Text>
                                                    </div>
                                                </Col>
                                            </Row>

                                            {/* Amenities */}
                                            {(roomType.highlighted_amenities && roomType.highlighted_amenities.length > 0) && (
                                                <div style={{ marginBottom: "20px" }}>
                                                    <Text
                                                        strong
                                                        style={{
                                                            fontSize: "14px",
                                                            display: "block",
                                                            marginBottom: "8px"
                                                        }}
                                                    >
                                                        <ThunderboltOutlined style={{ marginRight: "8px", color: "#faad14" }} />
                                                        Tiện nghi nổi bật:
                                                    </Text>
                                                    <AmenityDisplay
                                                        amenities={roomType.highlighted_amenities}
                                                        maxDisplay={6}
                                                        layout="inline"
                                                    />
                                                </div>
                                            )}

                                            {/* Cheapest Package Offer */}
                                            {cheapestPackage && (
                                                <div
                                                    style={{
                                                        marginBottom: "20px",
                                                        padding: "16px",
                                                        borderRadius: "12px",
                                                        background: 'linear-gradient(135deg, #e6f7ff 0%, #f6ffed 100%)',
                                                        border: '1px solid #91d5ff'
                                                    }}
                                                >
                                                    <Flex justify="space-between" align="center">
                                                        <div>
                                                            <Flex align="center" gap={8} style={{ marginBottom: '8px' }}>
                                                                <Tag color="success" icon={<GiftOutlined />} style={{ fontWeight: 500 }}>
                                                                    Gói Tốt Nhất
                                                                </Tag>
                                                                <Text strong style={{ fontSize: "15px", color: '#389e0d' }}>
                                                                    {cheapestPackage.package_name}
                                                                </Text>
                                                            </Flex>
                                                            <Text type="secondary" style={{ fontSize: '13px' }}>
                                                                {cheapestPackage.package_description}
                                                            </Text>
                                                        </div>
                                                        <Button
                                                            type="primary"
                                                            size="large"
                                                            icon={<CalendarOutlined />}
                                                            onClick={() => handleViewDetails(roomType)}
                                                            style={{
                                                                borderRadius: "8px",
                                                                height: "44px",
                                                                fontSize: "14px",
                                                                fontWeight: "600",
                                                                padding: "0 24px",
                                                                boxShadow: '0 4px 12px rgba(24, 144, 255, 0.3)'
                                                            }}
                                                        >
                                                            Chọn phòng
                                                        </Button>
                                                    </Flex>
                                                </div>
                                            )}

                                            {/* Action Buttons */}
                                            <Flex justify="flex-end" style={{ marginTop: "auto" }}>
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
                                                        transition: "all 0.3s ease"
                                                    }}
                                                    className="hover:bg-blue-50"
                                                >
                                                    Xem chi tiết & các gói khác
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
