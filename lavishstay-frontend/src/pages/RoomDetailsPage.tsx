import React from "react";
import { useParams, useNavigate } from "react-router-dom";
import {
    Button,
    Row,
    Col,
    Tag,
    Typography,
    Breadcrumb,
    Card,
    Anchor,
    Spin,
    Alert,
} from "antd";
import {
    HomeOutlined,
    StarOutlined,
    CheckCircleOutlined,
    ExpandOutlined,
    EyeOutlined,
    TeamOutlined,
} from "@ant-design/icons";

// Import hooks
import { useGetRoomById } from "../hooks/useApi";
import { RoomOption } from "../mirage/roomoption";

// Import components
import RoomImageGallery from "../components/room/RoomImageGallery";
import RoomAmenities from "../components/room/RoomAmenities";
import RoomAvailabilityFilter from "../components/room/RoomAvailabilityFilter";
import RoomReviews from "../components/room/RoomReviews";
import RoomQuickFilters from "../components/room/RoomQuickFiltersNew";
import RoomServiceOptions from "../components/room/RoomServiceOptions";

import SimilarRooms from "../components/room/SimilarRooms";


const { Title, Text } = Typography;

const RoomDetailsPage: React.FC = () => {
    const { id } = useParams<{ id: string }>();
    const navigate = useNavigate();

    // Use the hook to fetch room data
    const { data: roomData, isLoading: loading, error } = useGetRoomById(id || '');
    const room = roomData?.room;

    if (loading) {
        return (
            <div className="container mx-auto px-4 py-8 text-center">
                <Spin size="large" />
                <div className="mt-4">Đang tải thông tin phòng...</div>
            </div>
        );
    } if (error || !room) {
        return (
            <div className="container mx-auto px-4 py-8">
                <Alert
                    message="Lỗi"
                    description={error?.message || "Không tìm thấy phòng"}
                    type="error"
                    showIcon
                    action={
                        <Button onClick={() => navigate("/")} type="primary">
                            Quay về trang chủ
                        </Button>
                    }
                />
            </div>
        );
    }

    const formatVND = (price: number) => {
        return new Intl.NumberFormat("vi-VN", {
            style: "currency",
            currency: "VND",
        }).format(price);
    };    // Calculate prices from options or fallback to priceVND
    const calculatePriceInfo = () => {
        if (room.options && room.options.length > 0) {
            // Lấy giá thấp nhất và cao nhất từ options
            const prices = room.options.map((option: RoomOption) => option.pricePerNight);
            const minPrice = Math.min(...prices);
            const maxPrice = Math.max(...prices);
            const hasMultipleOptions = room.options.length > 1;

            return {
                originalPrice: room.discount ? minPrice / (1 - room.discount / 100) : minPrice,
                discountedPrice: minPrice,
                minPrice,
                maxPrice,
                hasMultipleOptions,
                optionsCount: room.options.length
            };
        } else {
            // Fallback to original logic
            const originalPrice = room.priceVND;
            const discountedPrice = room.discount
                ? room.priceVND - (room.priceVND * room.discount / 100)
                : room.priceVND;

            return {
                originalPrice,
                discountedPrice,
                minPrice: discountedPrice,
                maxPrice: discountedPrice,
                hasMultipleOptions: false,
                optionsCount: 0
            };
        }
    };

    const priceInfo = calculatePriceInfo();
    const savings = priceInfo.originalPrice - priceInfo.discountedPrice;

    return (
        <div className="min-h-screen ">
            {/* Inject custom styles */}
            <style>{anchorStyles}</style>

            {/* Breadcrumb Navigation */}
            <div className=" border-b">
                <div className="container mx-auto px-4 py-4">
                    <Breadcrumb
                        items={[{
                            href: "/",
                            title: (
                                <span>
                                    <HomeOutlined />
                                    <span className="ml-1">Trang chủ</span>
                                </span>
                            ),
                        },
                        {
                            title: room.name,
                        },
                        ]}
                    />
                </div>
            </div>
            {/* Navigation Anchor */}
            <div className="container mx-auto px-4 mb-6 ">
                <div className="   rounded-lg px-6 py-4 shadow-sm ">
                    <Anchor
                        direction="horizontal"
                        offsetTop={80}
                        className="anchor-nav"
                        items={[
                            {
                                key: 'room-gallery',
                                href: '#room-gallery',
                                title: 'Hình ảnh',
                            },
                            {
                                key: 'room-info',
                                href: '#room-info',
                                title: 'Thông tin phòng',
                            },
                            {
                                key: 'room-amenities',
                                href: '#room-amenities',
                                title: 'Tiện nghi',
                            },
                            {
                                key: 'room-booking',
                                href: '#room-booking',
                                title: 'Đặt phòng',
                            },
                            {
                                key: 'room-services',
                                href: '#room-services',
                                title: 'Dịch vụ',
                            },
                            {
                                key: 'room-reviews',
                                href: '#room-reviews',
                                title: 'Đánh giá',
                            },
                            {
                                key: 'similar-rooms',
                                href: '#similar-rooms',
                                title: 'Phòng tương tự',
                            },
                        ]}
                    />
                </div>
            </div>

            <div className="container mx-auto px-4 pb-8">
                {" "}                {/* Room Image Gallery - Full Width */}
                <Row gutter={[0, 24]} id="room-gallery">
                    <Col span={24}>
                        <Card className="mb-6">
                            <RoomImageGallery
                                images={room.images}
                                roomName={room.name}
                            />
                        </Card>
                    </Col>
                </Row>                {/* Room Information Section */}
                <Row gutter={[24, 24]} className="mt-6" id="room-info">
                    <Col span={24}>
                        <Card className="shadow-lg">
                            {/* Room Header */}
                            <div className="border-b pb-6 mb-6">
                                <div className="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6">
                                    <div className="flex-1">
                                        <div className="flex items-center gap-3 mb-3">
                                            <Title level={1} className="mb-0 text-3xl font-bold text-gray-800">
                                                {room.name}
                                            </Title>
                                            {room.rating && (
                                                <div className="flex items-center bg-green-100 px-1 py-1 rounded-full">
                                                    <StarOutlined className="text-yellow-500 mr-1" />
                                                    <Text className="text-lg  text-green-700">{room.rating}</Text>
                                                </div>
                                            )}
                                        </div>

                                        <div className="flex flex-wrap items-center gap-4 mb-4">
                                            <Tag
                                                color="blue"
                                                className="text-sm px-4 py-2 rounded-full font-medium border-0"
                                                style={{ background: 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)', color: 'white' }}
                                            >
                                                {room.roomType === "deluxe" ? "Phòng Deluxe" :
                                                    room.roomType === "premium" ? "Phòng Premium" :
                                                        room.roomType === "suite" ? "Suite" :
                                                            room.roomType === "presidential" ? "Presidential Suite" :
                                                                "The Level"}
                                            </Tag>

                                            {room.discount && (
                                                <Tag color="red" className="text-sm px-3 py-1 rounded-full font-bold animate-pulse">
                                                    🔥 Giảm {room.discount}%
                                                </Tag>
                                            )}
                                        </div>

                                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                                            <div className="flex items-center gap-2 p-3 bg-blue-50 rounded-lg">
                                                <EyeOutlined className="text-blue-600 text-lg" />
                                                <div>
                                                    <div className="text-gray-500 text-xs">Tầm nhìn</div>
                                                    <Text className="font-medium text-gray-800">{room.view}</Text>
                                                </div>
                                            </div>

                                            <div className="flex items-center gap-2 p-3 bg-green-50 rounded-lg">
                                                <ExpandOutlined className="text-green-600 text-lg" />
                                                <div>
                                                    <div className="text-gray-500 text-xs">Diện tích</div>
                                                    <Text className="font-medium text-gray-800">{room.size}m²</Text>
                                                </div>
                                            </div>

                                            <div className="flex items-center gap-2 p-3 bg-purple-50 rounded-lg">
                                                <TeamOutlined className="text-purple-600 text-lg" />
                                                <div>
                                                    <div className="text-gray-500 text-xs">Sức chứa</div>
                                                    <Text className="font-medium text-gray-800">Tối đa {room.maxGuests} khách</Text>
                                                </div>
                                            </div>

                                            <div className="flex items-center gap-2 p-3 bg-orange-50 rounded-lg">
                                                <HomeOutlined className="text-orange-600 text-lg" />
                                                <div>
                                                    <div className="text-gray-500 text-xs">Loại giường</div>
                                                    <Text className="font-medium text-gray-800">
                                                        {typeof room.bedType === 'string' ? room.bedType : room.bedType.default}
                                                    </Text>
                                                </div>
                                            </div>
                                        </div>
                                    </div>                                  

                                </div>
                            </div>

                            {/* Room Description */}
                            <div className="mb-6">
                                <Title level={3} className="mb-4 flex items-center gap-2 text-xl">
                                    <CheckCircleOutlined className="text-blue-500" />
                                    Về căn phòng này
                                </Title>
                                <div className="bg-gray-50 p-6 rounded-xl">
                                    <Typography.Paragraph className="leading-relaxed text-base text-gray-700 mb-0">
                                        {room.description}
                                    </Typography.Paragraph>
                                </div>
                            </div>

                            {/* Additional Room Details */}
                            {typeof room.bedType === 'object' && room.bedType.options && room.bedType.options.length > 1 && (
                                <div className="mb-6">
                                    <Title level={4} className="mb-3 text-lg">Tùy chọn giường</Title>
                                    <div className="flex flex-wrap gap-2">
                                        {room.bedType.options.map((option: string, index: number) => (
                                            <Tag key={index} className="px-3 py-1 rounded-full border border-gray-300">
                                                {option}
                                            </Tag>
                                        ))}
                                    </div>
                                </div>
                            )}
                        </Card>
                    </Col>
                </Row>{" "}
                {/* Room Amenities - Full Width */}
                <Row gutter={[0, 24]} className="mt-8" id="room-amenities">
                    <Col span={24}>
                        <RoomAmenities amenities={room.amenities} />
                    </Col>
                </Row>
                {/* Availability Filter and Quick Filters - Side by Side */}
                <Row gutter={[24, 24]} className="mt-8" id="room-booking">
                    <Col xs={24} lg={12}>
                        <RoomAvailabilityFilter
                            maxGuests={room.maxGuests}
                            onSearch={(dates, guests) => {
                                console.log("Search availability:", dates, guests);
                            }}
                        />
                    </Col>
                    <Col xs={24} lg={12}>
                        <RoomQuickFilters />
                    </Col>
                </Row>                {/* Room Service Options - Full Width */}
                <Row gutter={[0, 24]} className="mt-8" id="room-services">
                    <Col span={24}>
                        <RoomServiceOptions roomId={room.id.toString()} roomOptions={room.options} />
                    </Col>
                </Row>{/* Reviews Section - Full Width */}
                <Row gutter={[0, 24]} className="mt-8" id="room-reviews">
                    <Col span={24}>
                        <RoomReviews roomId={room.id.toString()} />
                    </Col>
                </Row>
                {/* Similar Rooms - Full Width */}
                <Row gutter={[0, 24]} className="mt-8" id="similar-rooms">
                    <Col span={24}>
                        <SimilarRooms currentRoomId={room.id.toString()} />
                    </Col>
                </Row>
            </div>
        </div>
    );
};

export default RoomDetailsPage;

// Add custom styles for anchor navigation
const anchorStyles = `
    .anchor-nav .ant-anchor-wrapper {
        background: transparent;
    }
    
    .anchor-nav .ant-anchor {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(8px);
        border-radius: 8px;
        .dark & {
            background: rgba(30, 41, 59, 0.7);
        }
    }
    
    .anchor-nav .ant-anchor-horizontal .ant-anchor-link {
        margin-right: 4px;
        border-radius: 6px;
        transition: all 0.2s ease;
    }
    
    .anchor-nav .ant-anchor-horizontal .ant-anchor-link-title {
        color: #64748b;
        font-weight: 500;
        font-size: 14px;
        text-decoration: none;
    }
    
    .anchor-nav .ant-anchor-horizontal .ant-anchor-link:hover .ant-anchor-link-title {
        color: #1890ff;
        background: rgba(24, 144, 255, 0.04);
        border-radius: 6px;
        padding: 4px 8px;
    }
    
    .anchor-nav .ant-anchor-horizontal .ant-anchor-link-active .ant-anchor-link-title {
        color: #1890ff;
        background: rgba(24, 144, 255, 0.08);
        border-radius: 6px;
        padding: 4px 8px;
        font-weight: 600;
    }
    
    .anchor-nav .ant-anchor-horizontal .ant-anchor-ink {
        display: none;
    }
`;

if (typeof window !== "undefined") {
    const style = document.createElement("style");
    style.appendChild(document.createTextNode(anchorStyles));
    document.head.appendChild(style);
}
