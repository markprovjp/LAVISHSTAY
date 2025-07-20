import React, { useMemo } from "react";
import {
    Card,
    Typography,
    Button,
    Row,
    Col,
    Tag,
    Image,
    Badge,
    Carousel,
    Space,
    Descriptions,
    Avatar,
    Flex,
    Statistic
} from "antd";
import {
    HomeOutlined,
    StarFilled,
    TeamOutlined,
    PictureOutlined,
    ThunderboltOutlined,
    GiftOutlined,
    EyeOutlined,
    LeftOutlined,
    RightOutlined,
    AreaChartOutlined,
    UserOutlined,
    CrownOutlined
} from "@ant-design/icons";
import { motion } from "framer-motion";
import CountUp from "react-countup";
import { Bed, MapPin, Star } from "lucide-react";
import AmenityDisplay from '../common/AmenityDisplay';
// import './RoomTypeShowcase.css';

const { Title, Text, Paragraph } = Typography;

// Interface định nghĩa cấu trúc dữ liệu
interface RoomTypeData {
    id: number;
    name: string;
    room_code: string;
    description?: string;
    size: number;
    max_guests: number;
    rating?: number;
    images?: Array<{
        id: number;
        image_url: string;
        alt_text: string;
        is_main?: number;
    }>;
    main_image?: {
        id: number;
        image_url: string;
        alt_text: string;
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
    view?: string;
}

interface RoomTypeShowcaseProps {
    roomTypes: RoomTypeData[];
    className?: string;
}

// Component con để hiển thị từng loại phòng
const RoomTypeCard: React.FC<{ roomType: RoomTypeData; index: number }> = ({ roomType, index }) => {

    // Xử lý tên loại phòng
    const roomTypeDisplayName = useMemo(() => {
        const code = roomType.room_code.toLowerCase();
        if (code.includes('deluxe')) return 'Deluxe';
        if (code.includes('premium')) return 'Premium';
        if (code.includes('suite')) return 'Suite';
        if (code.includes('presidential')) return 'Presidential';
        if (code.includes('level')) return 'The Level';
        return 'Standard';
    }, [roomType.room_code]);

    // Màu sắc theo loại phòng
    const themeColors = useMemo(() => {
        const code = roomType.room_code.toLowerCase();
        if (code.includes('deluxe')) return {
            primary: '#1e3a8a', // Blue
            secondary: '#dbeafe',
            accent: '#3b82f6'
        };
        if (code.includes('premium') || code.includes('corner')) return {
            primary: '#7c2d12', // Orange
            secondary: '#fed7aa',
            accent: '#ea580c'
        };
        if (code.includes('suite')) return {
            primary: '#4c1d95', // Purple
            secondary: '#ede9fe',
            accent: '#8b5cf6'
        };
        if (code.includes('presidential')) return {
            primary: '#991b1b', // Red
            secondary: '#fecaca',
            accent: '#dc2626'
        };
        if (code.includes('level')) return {
            primary: '#059669', // Emerald
            secondary: '#d1fae5',
            accent: '#10b981'
        };
        return {
            primary: '#374151', // Gray
            secondary: '#f3f4f6',
            accent: '#6b7280'
        };
    }, [roomType.room_code]);

    // Chia amenities theo category
    // Không cần dùng nữa vì đã có AmenityDisplay xử lý

    const CustomArrow = ({ direction, onClick }: { direction: 'left' | 'right', onClick?: () => void }) => (
        <div
            className={`absolute top-1/2 transform -translate-y-1/2 z-10 
        ${direction === 'left' ? 'left-4' : 'right-4'}
        w-10 h-10 bg-white/90 hover:bg-white rounded-full 
        flex items-center justify-center cursor-pointer shadow-lg
        transition-all duration-300 hover:scale-110`}
            onClick={onClick}
        >
            {direction === 'left' ?
                <LeftOutlined className="text-gray-700" /> :
                <RightOutlined className="text-gray-700" />
            }
        </div>
    );

    return (
        <Card
            className={`overflow-hidden transition-all duration-500 hover:shadow-2xl hover:-translate-y-2 border-0 rounded-2xl`}
            style={{
                background: `linear-gradient(135deg, ${themeColors.secondary} 0%, white 100%)`,
                animationDelay: `${index * 0.2}s`
            }}
            bodyStyle={{ padding: 0 }}
        >
            {/* Header Section với ảnh */}
            <div className="relative h-80 overflow-hidden">
                <Carousel
                    autoplay
                    autoplaySpeed={4000}
                    effect="fade"
                    dots={{ className: 'custom-dots' }}
                    prevArrow={<CustomArrow direction="left" />}
                    nextArrow={<CustomArrow direction="right" />}
                >
                    {(roomType.images && roomType.images.length > 0 ? roomType.images : roomType.main_image ? [roomType.main_image] : []).map((image) => (
                        <div key={image.id} className="relative">
                            <Image
                                src={image.image_url}
                                alt={image.alt_text}
                                className="w-full h-80 object-cover"
                                preview={false}
                                fallback="https://dam.melia.com/melia/file/iXGwjwBVnTHehdUyTT57.jpg"
                            />
                            <div className="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent" />
                        </div>
                    ))}
                </Carousel>

                {/* Room Type Badge */}
                <div className="absolute top-6 left-6">
                    <Badge.Ribbon
                        text={roomTypeDisplayName}
                        color={themeColors.primary}
                        className="font-semibold"
                    />
                </div>

                {/* Image Gallery Button */}
                <div className="absolute top-6 right-6">
                    <Image.PreviewGroup items={(roomType.images || []).map(img => img.image_url)}>
                        <Button
                            type="primary"
                            icon={<PictureOutlined />}
                            className="bg-white/20 backdrop-blur-sm border-0 text-white hover:bg-white/30"
                        >
                            {(roomType.images || []).length} ảnh
                        </Button>
                    </Image.PreviewGroup>
                </div>

                {/* Rating & Room info overlay */}
                <div className="absolute bottom-6 left-6 right-6 flex justify-between items-end">
                    <div className="text-white">
                        <Title level={2} className="text-white mb-1 font-bold">
                            {roomType.name}
                        </Title>
                        <div className="flex items-center gap-4 text-white/90">
                            <span className="flex items-center gap-1">
                                <HomeOutlined /> {roomType.size}m²
                            </span>
                            {roomType.view && (
                                <span className="flex items-center gap-1">
                                    <Bed className="w-4 h-4" /> {roomType.view}
                                </span>
                            )}
                            <span className="flex items-center gap-1">
                                <TeamOutlined /> {roomType.max_guests} khách 
                            </span>
                        </div>
                    </div>

                    {roomType.rating && roomType.rating > 0 && (
                        <div className="bg-white/20 backdrop-blur-sm rounded-full px-3 py-1">
                            <div className="flex items-center gap-1 text-white">
                                <StarFilled className="text-yellow-400" />
                                <span className="font-semibold">{roomType.rating}</span>
                            </div>
                        </div>
                    )}
                </div>
            </div>

            {/* Content Section */}
            <div className="p-8">
                {/* Description */}
                <div className="mb-8">
                    <Paragraph className="text-gray-700 text-base leading-relaxed">
                        {roomType.description || "Phòng sang trọng với đầy đủ tiện nghi hiện đại"}
                    </Paragraph>
                </div>

                {/* Highlighted Amenities */}
                <div className="space-y-6">
                    <div className="flex items-center gap-2 mb-4">
                        <ThunderboltOutlined
                            className="text-xl"
                            style={{ color: themeColors.accent }}
                        />
                        <Title level={4} className="mb-0" style={{ color: themeColors.primary }}>
                            Tiện nghi nổi bật
                        </Title>
                    </div>

                    {roomType.highlighted_amenities && roomType.highlighted_amenities.length > 0 ? (
                        <AmenityDisplay
                            amenities={roomType.highlighted_amenities}
                            maxDisplay={12}
                            layout="grid"
                            showCategories={true}
                        />
                    ) : (
                        <div className="text-gray-500 text-center py-4">
                            Không có thông tin tiện nghi
                        </div>
                    )}

                    {/* Total amenities count */}
                    {roomType.amenities && roomType.highlighted_amenities && roomType.amenities.length > roomType.highlighted_amenities.length && (
                        <div className="mt-6 p-4 rounded-xl" style={{ backgroundColor: themeColors.secondary }}>
                            <div className="flex items-center justify-center gap-2">
                                <GiftOutlined style={{ color: themeColors.primary }} />
                                <Text style={{ color: themeColors.primary }} className="font-medium">
                                    Và còn {roomType.amenities.length - roomType.highlighted_amenities.length} tiện nghi khác
                                </Text>
                            </div>
                        </div>
                    )}
                </div>

                {/* CTA Button */}
                <div className="mt-8 text-center">
                    <Button
                        type="primary"
                        size="large"
                        className="h-12 px-8 font-semibold rounded-xl border-0 shadow-lg hover:shadow-xl transition-all duration-300"
                        style={{
                            background: `linear-gradient(135deg, ${themeColors.primary} 0%, ${themeColors.accent} 100%)`,
                        }}
                        icon={<EyeOutlined />}
                    >
                        Khám phá chi tiết
                    </Button>
                </div>
            </div>
        </Card>
    );
};

// Component chính
const RoomTypeShowcase: React.FC<RoomTypeShowcaseProps> = ({ roomTypes, className = "" }) => {
    return (
        <div className={`room-type-showcase ${className}`}>
            {/* Header */}
            <div className="text-center mb-12">
                <Title level={1} className="mb-4 bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">
                    Khám Phá Các Loại Phòng
                </Title>
                <Paragraph className="text-lg text-gray-600 max-w-3xl mx-auto">
                    Trải nghiệm không gian nghỉ dưỡng đẳng cấp với các loại phòng được thiết kế tinh tế,
                    mang đến sự thoải mái và sang trọng cho kỳ nghỉ của bạn.
                </Paragraph>
            </div>

            {/* Stats */}
            <Row gutter={[24, 24]} className="mb-12">
                <Col xs={24} sm={8}>
                    <div className="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl">
                        <div className="text-3xl font-bold text-blue-600 mb-2">
                            {roomTypes.length}
                        </div>
                        <Text className="text-blue-700 font-medium">Loại phòng</Text>
                    </div>
                </Col>
                <Col xs={24} sm={8}>
                    <div className="text-center p-6 bg-gradient-to-br from-green-50 to-green-100 rounded-2xl">
                        <div className="text-3xl font-bold text-green-600 mb-2">
                            {Math.round(roomTypes.reduce((sum, room) => sum + room.size, 0) / roomTypes.length)}m²
                        </div>
                        <Text className="text-green-700 font-medium">Diện tích trung bình</Text>
                    </div>
                </Col>
                <Col xs={24} sm={8}>
                    <div className="text-center p-6 bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl">
                        <div className="text-3xl font-bold text-purple-600 mb-2">
                            {roomTypes.reduce((sum, room) => sum + (room.amenities?.length || 0), 0)}
                        </div>
                        <Text className="text-purple-700 font-medium">Tổng tiện nghi</Text>
                    </div>
                </Col>
            </Row>

            {/* Room Type Cards */}
            <Row gutter={[32, 32]}>
                {roomTypes.map((roomType, index) => (
                    <Col xs={24} lg={12} xl={8} key={roomType.id}>
                        <RoomTypeCard roomType={roomType} index={index} />
                    </Col>
                ))}
            </Row>
        </div>
    );
};

export default RoomTypeShowcase;
