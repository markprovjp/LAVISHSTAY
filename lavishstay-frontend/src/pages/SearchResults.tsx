// filepath: d:\PRO224\DU_AN_TOT_NGHIEP\lavishstay-frontend\src\pages\SearchResults.tsx
import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import {
    Card,
    Row,
    Col,
    Typography,
    Button,
    Badge,
    Space,
    Progress,
    Divider,
    message,
    Tag,
    Image,
    Skeleton,
    Empty,
    Affix,
    Tooltip,
    Modal,
    Carousel,
    Alert
} from 'antd';
import {
    ShoppingCartOutlined,
    CheckCircleOutlined,
    InfoCircleOutlined,
    ExclamationCircleOutlined,
    CreditCardOutlined,
    DollarOutlined,
    SafetyCertificateOutlined,
    StarFilled,
    EyeOutlined,
    LeftOutlined,
    RightOutlined,
    GiftOutlined,
    ClockCircleOutlined,
    TeamOutlined,
    CoffeeOutlined,
    MinusOutlined,
    PlusOutlined
} from '@ant-design/icons';
import { useSelector } from 'react-redux';
import { RootState } from '../store';
import { Room } from '../mirage/models';
import { RoomOption } from '../mirage/roomoption';
import { searchService } from '../services/searchService';
import SearchForm from '../components/SearchForm';
import { formatAmenitiesForDisplay } from '../constants/amenities';
import { calculateNightsFromRange, calculateTotalPrice } from '../utils/helpers';
import { generateRoomOptionsWithDynamicPricing } from '../utils/dynamicPricing';
import dayjs from 'dayjs';

const { Title, Text } = Typography;

interface SelectedRooms {
    [roomId: string]: {
        [optionId: string]: number;
    };
}

const SearchResults: React.FC = () => {
    const navigate = useNavigate();
    const searchData = useSelector((state: RootState) => state.search);

    const [loading, setLoading] = useState(true);
    const [rooms, setRooms] = useState<Room[]>([]);
    const [selectedRooms, setSelectedRooms] = useState<SelectedRooms>({});
    const [total, setTotal] = useState(0);
    const [isRoomDetailModalVisible, setIsRoomDetailModalVisible] = useState(false);
    const [selectedRoomDetail, setSelectedRoomDetail] = useState<Room | null>(null);
    const [imageModalVisible, setImageModalVisible] = useState(false);
    const [currentImageIndex, setCurrentImageIndex] = useState(0); const [currentRoomImages, setCurrentRoomImages] = useState<string[]>([]);

    // Calculate number of nights from search data
    const getNights = () => {
        return calculateNightsFromRange(searchData.dateRange) || 1;
    };

    // Format VND currency
    const formatVND = (price: number) => {
        return new Intl.NumberFormat("vi-VN", {
            style: "currency",
            currency: "VND",
        }).format(price);
    };

    // Check if room is suitable for guest count
    const isRoomSuitable = (room: Room) => {
        if (!searchData.guestDetails) return true;
        const totalGuests = searchData.guestDetails.adults + searchData.guestDetails.children;
        return room.maxGuests >= totalGuests;
    };

    // Get availability status
    const getAvailabilityStatus = (availability: RoomOption['availability']) => {
        const { remaining, total } = availability;
        const percentage = (remaining / total) * 100;

        if (remaining === 0) {
            return { color: "red", text: "Hết phòng", urgent: true };
        } else if (remaining <= 3) {
            return { color: "orange", text: `Chỉ còn ${remaining} phòng`, urgent: true };
        } else if (percentage <= 30) {
            return { color: "gold", text: `${remaining} phòng còn lại`, urgent: false };
        } else {
            return { color: "green", text: `${remaining} phòng có sẵn`, urgent: false };
        }
    };

    // Get cancellation policy display
    const getCancellationPolicyDisplay = (policy: RoomOption['cancellationPolicy']) => {
        switch (policy.type) {
            case 'free':
                return { text: 'Hủy miễn phí', color: 'green', icon: <CheckCircleOutlined /> };
            case 'conditional':
                return { text: 'Hủy có điều kiện', color: 'orange', icon: <InfoCircleOutlined /> };
            case 'non_refundable':
                return { text: 'Không hoàn tiền', color: 'red', icon: <ExclamationCircleOutlined /> };
            default:
                return { text: 'Liên hệ', color: 'gray', icon: <InfoCircleOutlined /> };
        }
    };

    // Get payment policy display
    const getPaymentPolicyDisplay = (policy: RoomOption['paymentPolicy']) => {
        switch (policy.type) {
            case 'pay_now_with_vietQR':
                return { text: 'Thanh toán ngay với VietQR', color: 'blue', icon: <CreditCardOutlined /> };
            case 'pay_at_hotel':
                return { text: 'Thanh toán tại khách sạn', color: 'green', icon: <DollarOutlined /> };
            default:
                return { text: 'Liên hệ', color: 'gray', icon: <InfoCircleOutlined /> };
        }
    };

    // Handle room quantity change
    const handleQuantityChange = (roomId: string, optionId: string, quantity: number) => {
        setSelectedRooms(prev => ({
            ...prev,
            [roomId]: {
                ...prev[roomId],
                [optionId]: quantity
            }
        }));
    };    // Calculate total price for an option (including nights)
    const calculateOptionTotal = (option: RoomOption, quantity: number) => {
        const nights = calculateNightsFromRange(searchData.dateRange) || 1;
        return calculateTotalPrice(option.pricePerNight.vnd, nights, quantity);
    };

    // Calculate total for all selected rooms (including nights)
    const calculateGrandTotal = () => {
        let total = 0;
        Object.entries(selectedRooms).forEach(([roomId, options]) => {
            Object.entries(options).forEach(([optionId, quantity]) => {
                if (quantity > 0) {
                    const room = rooms.find(r => r.id.toString() === roomId);
                    if (room) {
                        const option = room.options.find(opt => opt.id === optionId);
                        if (option) {
                            total += calculateOptionTotal(option, quantity);
                        }
                    }
                }
            });
        });
        return total;
    };    // Get total selected rooms count
    const getTotalSelectedRooms = () => {
        let total = 0;
        Object.values(selectedRooms).forEach(options => {
            Object.values(options).forEach(quantity => {
                total += quantity;
            });
        });
        return total;
    };    // Calculate total savings from dynamic pricing
    const calculateTotalSavings = () => {
        let totalSavings = 0;
        Object.entries(selectedRooms).forEach(([roomId, options]) => {
            Object.entries(options).forEach(([optionId, quantity]) => {
                if (quantity > 0) {
                    const room = rooms.find(r => r.id.toString() === roomId);
                    if (room) {
                        const option = room.options.find(opt => opt.id === optionId);
                        if (option && option.dynamicPricing) {
                            // Use dynamic pricing savings if available
                            const savings = option.dynamicPricing.savings * quantity * getNights();
                            totalSavings += savings;
                        }
                    }
                }
            });
        });
        return totalSavings;
    };    // Nhận các tiện nghi chính để hiển thị với các biểu tượng thích hợp
    const getMainAmenities = (amenities: string[]) => {
        return formatAmenitiesForDisplay(amenities); // Hiển thị tất cả amenities
    };

    // Check if room suggestion should be shown
    const shouldShowSuggestion = (room: Room) => {
        if (!searchData.guestDetails) return false;
        const totalGuests = searchData.guestDetails.adults + searchData.guestDetails.children;
        return room.maxGuests >= totalGuests;
    };    // Handle room detail modal
    const showRoomDetail = (room: Room) => {
        setSelectedRoomDetail(room);
        setIsRoomDetailModalVisible(true);
    };

    // Handle image gallery
    const showImageGallery = (room: Room, startIndex: number = 0) => {
        setCurrentRoomImages(room.images || []);
        setCurrentImageIndex(startIndex);
        setImageModalVisible(true);
    };    // Handle previous image
    const handlePrevImage = () => {
        setCurrentImageIndex((prev) =>
            prev === 0 ? currentRoomImages.length - 1 : prev - 1
        );
    };

    // Handle next image
    const handleNextImage = () => {
        setCurrentImageIndex((prev) =>
            prev === currentRoomImages.length - 1 ? 0 : prev + 1
        );
    };

    // Handle room detail page navigation
    const navigateToRoomDetail = (roomId: string) => {
        navigate(`/rooms/${roomId}`);
    };    // Fetch search results
    useEffect(() => {
        const fetchResults = async () => {
            try {
                setLoading(true);
                const results = await searchService.searchRooms(searchData);                // Apply dynamic pricing to each room
                const roomsWithDynamicPricing = results.rooms.map(room => {                    // Safe date handling with defaults - convert to dayjs
                    const checkInDate = searchData.dateRange && searchData.dateRange[0] ?
                        searchData.dateRange[0] : dayjs();
                    const checkOutDate = searchData.dateRange && searchData.dateRange[1] ?
                        searchData.dateRange[1] : dayjs().add(1, 'day');

                    // Calculate actual guest count
                    const actualGuestCount = searchData.guestDetails
                        ? searchData.guestDetails.adults + searchData.guestDetails.children
                        : 2; // Default to 2 guests

                    // Generate dynamic room options using dayjs dates với guest count
                    const dynamicOptions = generateRoomOptionsWithDynamicPricing(
                        room.priceVND || 1900000, // TĂNG FALLBACK PRICE LÊN 3M
                        room.roomType, // roomType
                        room.maxGuests, // maxGuests
                        checkInDate, // checkInDate as dayjs
                        checkOutDate,  // checkOutDate as dayjs
                        dayjs(), // bookingDate
                        actualGuestCount // guestCount for prioritization
                    );

                    return {
                        ...room,
                        options: dynamicOptions
                    };
                });

                setRooms(roomsWithDynamicPricing);
                setTotal(results.total);
            } catch (error: any) {
                message.error('Có lỗi xảy ra khi tải kết quả tìm kiếm');
                console.error('Search error:', error);
            } finally {
                setLoading(false);
            }
        };

        fetchResults();
    }, [searchData]);

    // Handle booking
    const handleBooking = () => {
        if (getTotalSelectedRooms() === 0) {
            message.warning('Vui lòng chọn ít nhất một phòng');
            return;
        }

        // Navigate to booking page with selected rooms data
        const bookingData = {
            selectedRooms,
            searchData,
            totalPrice: calculateGrandTotal(),
            totalRooms: getTotalSelectedRooms()
        };

        console.log('Booking data:', bookingData);
        message.success(`Đang chuyển đến trang đặt phòng với ${getTotalSelectedRooms()} phòng`);
        // navigate('/booking', { state: bookingData });
    };    // Get dynamic pricing display
    const getDynamicPricingDisplay = (option: RoomOption) => {
        // Check if option has dynamic pricing data
        if (option.promotion || option.availability.urgencyMessage) {
            return {
                hasPromotion: !!option.promotion,
                promotionText: option.promotion?.message,
                urgency: option.availability.urgencyMessage,
                recommended: option.recommended
            };
        }
        return null;
    };// Calculate savings for display
    const calculateSavings = (option: RoomOption) => {
        // Use dynamic pricing savings if available
        if (option.dynamicPricing) {
            return option.dynamicPricing.savings;
        }
        // Fallback: estimate based on payment type
        if (option.paymentPolicy.type === 'pay_now_with_vietQR') {
            const savings = option.pricePerNight.vnd * 0.05; // 5% savings estimate
            return Math.round(savings);
        }
        return 0;
    };

    // Get urgency level styling
    const getUrgencyLevelStyling = (option: RoomOption) => {
        if (option.availability.remaining <= 1) {
            return { color: 'red', icon: '🔥', message: 'Cực kỳ khan hiếm!' };
        } else if (option.availability.remaining <= 3) {
            return { color: 'orange', icon: '⚡', message: 'Sắp hết phòng!' };
        } else if (option.recommended) {
            return { color: 'blue', icon: '⭐', message: 'Được đề xuất' };
        }
        return null;
    };

    if (loading) {
        return (
            <div className="min-h-screen -50 py-8">
                <div className="max-w-7xl mx-auto px-4">
                    <Skeleton active paragraph={{ rows: 8 }} className="mb-8" />
                    <Row gutter={[24, 24]}>
                        {[1, 2, 3].map(i => (
                            <Col key={i} xs={24}>
                                <Card>
                                    <Skeleton active avatar paragraph={{ rows: 4 }} />
                                </Card>
                            </Col>
                        ))}
                    </Row>
                </div>
            </div>
        );
    }

    if (rooms.length === 0) {
        return (
            <div className="min-h-screen  py-8">
                <div className="max-w-7xl mx-auto px-4">
                    <Card className="text-center py-12">
                        <Empty
                            description={
                                <div>
                                    <Title level={3}>Không tìm thấy phòng phù hợp</Title>
                                    <Text>Vui lòng thử thay đổi tiêu chí tìm kiếm</Text>
                                </div>
                            }
                        />
                        <Button
                            type="primary"
                            size="large"
                            onClick={() => navigate('/')}
                            className="mt-4"
                        >
                            Quay lại trang chủ
                        </Button>
                    </Card>
                </div>
            </div>
        );
    }

    return (
        <div className="min-h-screen ">
            {/* Search Form Header */}
            <div className=" shadow-sm border-b">
                <div className="max-w-7xl mx-auto px-4 py-6">                    <div className="mb-4">
                    <Title level={2} className="mb-2">
                        Kết quả tìm kiếm phòng ({total} phòng)
                    </Title>
                    <Space size="middle">
                        {searchData.dateRange && (
                            <Text type="secondary">
                                📅 {searchData.checkIn} - {searchData.checkOut} ({getNights()} đêm)
                            </Text>
                        )}
                        {searchData.guestDetails && (
                            <Text type="secondary">
                                👥 {searchData.guestDetails.adults + searchData.guestDetails.children} khách
                            </Text>
                        )}
                        {searchData.guestType && (
                            <Tag color="blue">{searchData.guestType}</Tag>
                        )}
                    </Space>
                </div>

                    {/* Embedded Search Form for easy re-searching */}
                    <div className=" p-4 rounded-lg">
                        <Text strong className="block mb-3">Thay đổi tìm kiếm:</Text>
                        <SearchForm className="search-form-compact" />
                    </div>
                </div>
            </div>            <div className="max-w-7xl mx-auto px-4 py-8">                <Row gutter={[24, 24]}>
                {/* Room Cards - 70% Width */}
                <Col xs={24} lg={16}>
                    <Space direction="vertical" size="large" className="w-full">
                        {rooms.map((room) => {
                            const isSuitable = isRoomSuitable(room);

                            return (
                                <Card
                                    key={room.id}
                                    className="shadow-sm hover:shadow-md transition-shadow"
                                    bodyStyle={{ padding: '24px' }}
                                >
                                    {/* Full Width Room Details - No Image Section */}
                                    <div className="w-full">
                                        {/* Room Header with Clickable Name and Action Buttons */}
                                        <div className="flex items-start justify-between mb-6">
                                            <div className="flex-1 min-w-0">
                                                {/* Clickable Room Name for Image Gallery */}
                                                <div
                                                    className="cursor-pointer hover:text-blue-600 transition-colors group"
                                                    onClick={() => showImageGallery(room, 0)}
                                                >
                                                    <Title level={3} className="mb-2  transition-colors flex items-center gap-2">
                                                        {room.name}
                                                    </Title>
                                                </div>

                                                <Space size="large" className="mt-3" wrap>
                                                    <Text type="secondary">📐 {room.size}m²</Text>
                                                    <Text type="secondary">🪟 {room.view}</Text>
                                                    <Text type="secondary">👥 Tối đa {room.maxGuests} khách</Text>
                                                    {room.rating && (
                                                        <div className="flex items-center gap-1">
                                                            <StarFilled className="text-yellow-500" />
                                                            <Text strong>{room.rating}/10</Text>
                                                            <Text type="secondary" className="text-sm">Tuyệt vời</Text>
                                                        </div>
                                                    )}
                                                </Space>
                                            </div>

                                            {/* Action Buttons and Badges */}
                                            <div className="flex items-start gap-3">
                                                {/* Room Status Badges */}
                                                <div className="flex flex-col gap-2">
                                                    {room.isSale && (
                                                        <Badge.Ribbon text={`-${room.discount}%`} color="red" />
                                                    )}
                                                    {!isSuitable && (
                                                        <Badge
                                                            status="warning"
                                                            text="Không phù hợp số người"
                                                            className="bg-yellow-100 px-2 py-1 rounded-full text-xs"
                                                        />
                                                    )}
                                                    {shouldShowSuggestion(room) && (
                                                        <Badge
                                                            status="success"
                                                            text="Được gợi ý"
                                                            className="bg-green-100 px-2 py-1 rounded-full text-xs"
                                                        />
                                                    )}                                                    </div>

                                                {/* Action Buttons */}
                                                <Space direction="vertical" size="small">
                                                    <Tooltip title="Xem chi tiết phòng">
                                                        <Button
                                                            type="default"
                                                            icon={<EyeOutlined />}
                                                            size="small"
                                                            onClick={() => showRoomDetail(room)}
                                                        />
                                                    </Tooltip>
                                                </Space>
                                            </div>
                                        </div>{/* Comprehensive Breakfast Information */}

                                        {/* Alert Messages */}
                                        <Space direction="vertical" size="small" className="w-full mb-4">
                                            {shouldShowSuggestion(room) && (
                                                <Alert
                                                    message={
                                                        <div className="flex items-center gap-2">
                                                            <TeamOutlined />
                                                            <Text strong>Được gợi ý cho bạn</Text>
                                                        </div>
                                                    }
                                                    type="success"
                                                    showIcon={false}
                                                    className="border-green-200 bg-green-50"
                                                />
                                            )}

                                            {room.urgencyRoomMessage && (
                                                <Alert
                                                    message={
                                                        <div className="flex items-center gap-2">
                                                            <ClockCircleOutlined />
                                                            <Text strong>{room.urgencyRoomMessage}</Text>
                                                        </div>
                                                    }
                                                    type="warning"
                                                    showIcon={false}
                                                    className="border-orange-200 bg-orange-50"
                                                />
                                            )}

                                            {room.lavishPlusDiscount && (
                                                <Alert
                                                    message={
                                                        <div className="flex items-center gap-2">
                                                            <GiftOutlined />
                                                            <Text strong>LavishPlus Exclusive</Text>
                                                        </div>
                                                    }
                                                    description={`Giảm thêm ${room.lavishPlusDiscount}% cho thành viên LavishPlus`}
                                                    type="info"
                                                    showIcon={false}
                                                    className="border-purple-200 bg-purple-50"
                                                    action={
                                                        <Button size="small" type="link" className="text-purple-600">
                                                            Tìm hiểu thêm
                                                        </Button>
                                                    }
                                                />
                                            )}
                                        </Space>                                        {/* Enhanced Amenities Display */}
                                        <div className="mb-6">
                                            <Text strong className="block mb-3 text-gray-800">Tiện ích nổi bật:</Text>
                                            <div className="flex flex-wrap gap-2">
                                                {getMainAmenities(room.mainAmenities || room.amenities).slice(0, 8).map((amenity, index) => (
                                                    <Tag
                                                        key={index}
                                                        icon={amenity.icon}
                                                        className="rounded-full px-3 py-1 border-blue-200 text-blue-700 bg-blue-50"
                                                    >
                                                        {amenity.name}
                                                    </Tag>
                                                ))}
                                                {getMainAmenities(room.mainAmenities || room.amenities).length > 8 && (
                                                    <Tag className="rounded-full px-3 py-1 border-gray-200 text-gray-600 bg-gray-50 cursor-pointer hover:bg-gray-100">
                                                        +{getMainAmenities(room.mainAmenities || room.amenities).length - 8} tiện ích khác
                                                    </Tag>
                                                )}
                                            </div>
                                        </div>

                                        {/* Room Options Section with Full Width */}
                                        <Divider className="my-6">
                                            <Text strong className="text-lg text-gray-800 flex items-center gap-2">
                                                <CreditCardOutlined className="text-blue-600" />
                                                Lựa chọn đặt phòng
                                            </Text>
                                        </Divider>

                                        {/* Room Options with Enhanced Layout */}
                                        <div className="bg-gray-50 p-6 rounded-xl">
                                            <Space direction="vertical" size="large" className="w-full">
                                                {room.options.map((option) => {
                                                    const availability = getAvailabilityStatus(option.availability);
                                                    const cancellation = getCancellationPolicyDisplay(option.cancellationPolicy);
                                                    const payment = getPaymentPolicyDisplay(option.paymentPolicy);
                                                    const isUnavailable = option.availability.remaining === 0;
                                                    const currentQuantity = selectedRooms[room.id]?.[option.id] || 0;
                                                    const dynamicPricing = getDynamicPricingDisplay(option);
                                                    const urgencyStyling = getUrgencyLevelStyling(option);
                                                    const savings = calculateSavings(option);
                                                    const isSelected = currentQuantity > 0;
                                                    const isRecommended = option.recommended;
                                                    const isMostPopular = option.mostPopular; return (
                                                        <div key={option.id} className="relative mb-8">
                                                            {/* Recommended/Popular Badge */}
                                                            {(isRecommended || isMostPopular) && (
                                                                <div className="absolute -top-3 left-4 z-10">
                                                                    <div className={`px-4 py-1 rounded-full text-xs font-bold text-white shadow-lg ${isMostPopular
                                                                        ? 'bg-gradient-to-r from-red-500 to-pink-500'
                                                                        : 'bg-gradient-to-r from-blue-500 to-indigo-500'
                                                                        }`}>
                                                                        {isMostPopular ? (
                                                                            <span className="flex items-center gap-1">
                                                                                🔥 Phổ biến nhất
                                                                            </span>
                                                                        ) : (
                                                                            <span className="flex items-center gap-1">
                                                                                <StarFilled /> Được đề xuất
                                                                            </span>
                                                                        )}
                                                                    </div>
                                                                </div>
                                                            )}

                                                            <Card
                                                                className={`room-option-card transition-all duration-300 hover:shadow-xl ${isSelected
                                                                    ? 'border-2 border-blue-500 bg-gradient-to-r from-blue-50 to-indigo-50 shadow-lg transform scale-[1.02]'
                                                                    : isRecommended || isMostPopular
                                                                        ? 'border-2 border-gradient-to-r from-blue-300 to-indigo-300 hover:border-blue-400'
                                                                        : 'border border-gray-200 hover:border-blue-300 hover:shadow-md'
                                                                    } ${isUnavailable ? 'opacity-60 grayscale' : ''}`}
                                                                style={{
                                                                    borderRadius: '16px',
                                                                    overflow: 'hidden',
                                                                    background: isSelected
                                                                        ? 'linear-gradient(135deg, #f8faff 0%, #f0f7ff 100%)'
                                                                        : isRecommended || isMostPopular
                                                                            ? 'linear-gradient(135deg, #fefefe 0%, #f9fafb 100%)'
                                                                            : undefined
                                                                }}
                                                                bodyStyle={{ padding: '24px' }}
                                                            >                                                                            {/* Option Header - Full Width */}
                                                                <div className="mb-4">
                                                                    <div className="flex items-start justify-between">
                                                                        <div className="flex-1 min-w-0">
                                                                            <Title level={4} className="mb-2 text-gray-800 font-semibold">
                                                                                {option.name}
                                                                            </Title>

                                                                            {/* Savings & Promotions */}
                                                                            {(savings > 0 || dynamicPricing?.hasPromotion) && (
                                                                                <div className="flex flex-wrap gap-2 mb-3">
                                                                                    {savings > 0 && (
                                                                                        <div className="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold flex items-center gap-1">
                                                                                            <GiftOutlined />
                                                                                            Tiết kiệm {formatVND(savings)}
                                                                                        </div>
                                                                                    )}
                                                                                    {dynamicPricing?.hasPromotion && (
                                                                                        <div className="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-semibold flex items-center gap-1">
                                                                                            <StarFilled />
                                                                                            {dynamicPricing.promotionText}
                                                                                        </div>
                                                                                    )}
                                                                                </div>
                                                                            )}

                                                                            {urgencyStyling && (
                                                                                <div className={`inline-flex px-3 py-1 rounded-full text-xs font-bold items-center gap-1 ${urgencyStyling.color === 'red' ? 'bg-red-100 text-red-700' :
                                                                                    urgencyStyling.color === 'orange' ? 'bg-orange-100 text-orange-700' :
                                                                                        'bg-blue-100 text-blue-700'
                                                                                    }`}>
                                                                                    <span>{urgencyStyling.icon}</span>
                                                                                    <span>{urgencyStyling.message}</span>
                                                                                </div>
                                                                            )}
                                                                        </div>

                                                                        {/* Price & Quantity - Compact Right Section */}
                                                                        <div className="flex items-center gap-4 ml-4">
                                                                            {/* Price */}
                                                                            <div className="text-right">
                                                                                {savings > 0 ? (
                                                                                    <div>
                                                                                        <Text className="text-sm text-gray-500 line-through block">
                                                                                            {formatVND(option.pricePerNight.vnd + savings)}
                                                                                        </Text>
                                                                                        <div className="flex items-center gap-2">
                                                                                            <Text strong className="text-lg text-green-600">
                                                                                                {formatVND(option.pricePerNight.vnd)}
                                                                                            </Text>
                                                                                            <span className="bg-red-500 text-white px-2 py-1 rounded text-xs font-bold">
                                                                                                -{Math.round((savings / (option.pricePerNight.vnd + savings)) * 100)}%
                                                                                            </span>
                                                                                        </div>
                                                                                        <Text type="secondary" className="text-xs">/đêm</Text>
                                                                                    </div>
                                                                                ) : (
                                                                                    <div>
                                                                                        <Text strong className="text-lg text-blue-600 block">
                                                                                            {formatVND(option.pricePerNight.vnd)}
                                                                                        </Text>
                                                                                        <Text type="secondary" className="text-xs">/đêm</Text>
                                                                                    </div>
                                                                                )}
                                                                            </div>

                                                                            {/* Quantity Controls - Compact */}
                                                                            <div className="flex items-center border border-gray-200 rounded-lg">
                                                                                <Button
                                                                                    type="text"
                                                                                    icon={<MinusOutlined />}
                                                                                    size="small"
                                                                                    disabled={currentQuantity === 0 || isUnavailable}
                                                                                    onClick={() => handleQuantityChange(room.id.toString(), option.id, currentQuantity - 1)}
                                                                                    className="w-8 h-8 flex items-center justify-center hover:bg-blue-50"
                                                                                />
                                                                                <div className="w-12 h-8 flex items-center justify-center bg-gray-50 border-x border-gray-200">
                                                                                    <Text strong className="text-sm">{currentQuantity}</Text>
                                                                                </div>
                                                                                <Button
                                                                                    type="text"
                                                                                    icon={<PlusOutlined />}
                                                                                    size="small"
                                                                                    disabled={currentQuantity >= option.availability.remaining || isUnavailable}
                                                                                    onClick={() => handleQuantityChange(room.id.toString(), option.id, currentQuantity + 1)}
                                                                                    className="w-8 h-8 flex items-center justify-center hover:bg-blue-50"
                                                                                />
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    {/* Total for selected quantity */}
                                                                    {currentQuantity > 0 && (
                                                                        <div className="mt-3 p-3 bg-green-50 rounded-lg border border-green-200">
                                                                            <div className="flex justify-between items-center">
                                                                                <Text className="text-sm text-green-700">
                                                                                    {currentQuantity} phòng × {getNights()} đêm
                                                                                </Text>
                                                                                <Text strong className="text-green-700">
                                                                                    {formatVND(calculateOptionTotal(option, currentQuantity))}
                                                                                </Text>
                                                                            </div>
                                                                        </div>
                                                                    )}
                                                                </div>

                                                                {/* Policy & Availability Section */}
                                                                <div className="space-y-4">
                                                                    {/* Policy Information - Simplified */}
                                                                    <div className="grid grid-cols-2 gap-3">
                                                                        <div className="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                                                                            <div className={`w-6 h-6 rounded-full flex items-center justify-center ${cancellation.color === 'green' ? 'bg-green-100' :
                                                                                cancellation.color === 'orange' ? 'bg-orange-100' :
                                                                                    'bg-red-100'
                                                                                }`}>
                                                                                {cancellation.icon}
                                                                            </div>
                                                                            <div>
                                                                                <Text className="text-xs text-gray-500">Hủy phòng</Text>
                                                                                <Text strong className={`block text-sm ${cancellation.color === 'green' ? 'text-green-700' :
                                                                                    cancellation.color === 'orange' ? 'text-orange-700' :
                                                                                        'text-red-700'
                                                                                    }`}>
                                                                                    {cancellation.text}
                                                                                </Text>
                                                                            </div>
                                                                        </div>
                                                                        <div className="flex items-center gap-2 p-2 bg-gray-50 rounded-lg">
                                                                            <div className={`w-6 h-6 rounded-full flex items-center justify-center ${payment.color === 'green' ? 'bg-green-100' :
                                                                                payment.color === 'blue' ? 'bg-blue-100' :
                                                                                    'bg-gray-100'
                                                                                }`}>
                                                                                {payment.icon}
                                                                            </div>
                                                                            <div>
                                                                                <Text className="text-xs text-gray-500">Thanh toán</Text>
                                                                                <Text strong className={`block text-sm ${payment.color === 'green' ? 'text-green-700' :
                                                                                    payment.color === 'blue' ? 'text-blue-700' :
                                                                                        'text-gray-700'
                                                                                    }`}>
                                                                                    {payment.text}
                                                                                </Text>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    {/* Availability & Meal Options */}
                                                                    <div className="flex items-center justify-between">
                                                                        <div className="flex-1">
                                                                            <div className="flex items-center gap-2 mb-1">
                                                                                <Text className="text-sm font-medium text-gray-600">Tình trạng:</Text>
                                                                                <Text className={`text-sm font-semibold ${availability.urgent ? 'text-orange-600' :
                                                                                    availability.color === 'red' ? 'text-red-600' :
                                                                                        availability.color === 'orange' ? 'text-orange-600' :
                                                                                            'text-green-600'
                                                                                    }`}>
                                                                                    {availability.text}
                                                                                </Text>
                                                                            </div>
                                                                            <Progress
                                                                                percent={Math.max(5, (option.availability.remaining / option.availability.total) * 100)}
                                                                                status={isUnavailable ? "exception" : "active"}
                                                                                strokeColor={{
                                                                                    '0%': availability.color === 'red' ? '#ef4444' :
                                                                                        availability.color === 'orange' ? '#f97316' : '#10b981',
                                                                                    '100%': availability.color === 'red' ? '#dc2626' :
                                                                                        availability.color === 'orange' ? '#ea580c' : '#059669'
                                                                                }}
                                                                                trailColor="#f3f4f6"
                                                                                size="small"
                                                                                showInfo={false}
                                                                                className="rounded-full"
                                                                            />
                                                                        </div>

                                                                        {/* Meal Options - Compact */}
                                                                        {option.mealOptions && (
                                                                            <div className="ml-4">
                                                                                <div className="flex gap-1">
                                                                                    {option.mealOptions.breakfast && (
                                                                                        <div className={`px-2 py-1 rounded text-xs flex items-center gap-1 ${option.mealOptions.breakfast.included
                                                                                            ? 'bg-green-100 text-green-700'
                                                                                            : 'bg-orange-100 text-orange-700'
                                                                                            }`}>
                                                                                            <CoffeeOutlined />
                                                                                            {option.mealOptions.breakfast.included ? "Sáng" : "+Sáng"}
                                                                                        </div>
                                                                                    )}
                                                                                    {option.mealOptions.dinner && (
                                                                                        <div className={`px-2 py-1 rounded text-xs flex items-center gap-1 ${option.mealOptions.dinner.included
                                                                                            ? 'bg-green-100 text-green-700'
                                                                                            : 'bg-orange-100 text-orange-700'
                                                                                            }`}>
                                                                                            <GiftOutlined />
                                                                                            {option.mealOptions.dinner.included ? "Tối" : "+Tối"}
                                                                                        </div>
                                                                                    )}
                                                                                </div>
                                                                            </div>
                                                                        )}
                                                                    </div>
                                                                </div>
                                                            </Card>
                                                        </div>
                                                    );
                                                })}
                                            </Space>
                                        </div>
                                    </div>
                                </Card>
                            );
                        })}
                    </Space>                    </Col>

                {/* Booking Summary Sidebar - 30% Width */}
                <Col xs={24} lg={8}>
                    <Affix offsetTop={190}>
                        <Card className="sticky top-24 shadow-lg border-t-4 border-t-blue-500">
                            <div className="text-center mb-6">
                                <div className="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <ShoppingCartOutlined className="text-xl text-blue-600" />
                                </div>
                                <Title level={4} className="mb-1">Tóm tắt đặt phòng</Title>
                                <Text type="secondary">Xem lại lựa chọn của bạn</Text>
                            </div>

                            {getTotalSelectedRooms() === 0 ? (
                                <div className="text-center py-12">
                                    <div className="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <ShoppingCartOutlined className="text-2xl text-gray-400" />
                                    </div>
                                    <Text type="secondary" className="text-base">Chưa có phòng nào được chọn</Text>
                                    <Text type="secondary" className="text-sm block mt-2">
                                        Hãy chọn phòng ở bên trái để bắt đầu đặt phòng
                                    </Text>
                                </div>) : (
                                <div>
                                    {/* Selected Rooms List */}
                                    <div className="space-y-4 mb-6 max-h-64 overflow-y-auto">
                                        {Object.entries(selectedRooms).map(([roomId, options]) => {
                                            const room = rooms.find(r => r.id.toString() === roomId);
                                            if (!room) return null;

                                            return Object.entries(options).map(([optionId, quantity]) => {
                                                if (quantity === 0) return null;
                                                const option = room.options.find(opt => opt.id === optionId);
                                                if (!option) return null;

                                                return (
                                                    <div
                                                        key={`${roomId}-${optionId}`}
                                                        className="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-lg border border-blue-100"
                                                    >
                                                        <div className="flex justify-between items-start mb-2">
                                                            <div className="flex-1 min-w-0 mr-3">
                                                                <Text strong className="block truncate text-base">
                                                                    {room.name}
                                                                </Text>
                                                                <Text type="secondary" className="text-sm block truncate">
                                                                    {option.name}
                                                                </Text>
                                                            </div>
                                                            <Badge
                                                                count={quantity}
                                                                showZero={false}
                                                            />
                                                        </div>                                                            <div className="flex justify-between items-center">
                                                            <Text type="secondary" className="text-sm">
                                                                {quantity} phòng × {getNights()} đêm × {formatVND(option.pricePerNight.vnd)}
                                                            </Text>
                                                            <Text strong className="text-green-600 text-base">
                                                                {formatVND(calculateOptionTotal(option, quantity))}
                                                            </Text>
                                                        </div>
                                                    </div>
                                                );
                                            });
                                        })}
                                    </div>

                                    <Divider className="my-4" />                                        {/* Total Summary */}
                                    <div className="bg-gradient-to-r from-green-50 to-emerald-50 p-4 rounded-lg border border-green-200 mb-6">
                                        <div className="flex justify-between items-center mb-2">
                                            <div>
                                                <Text strong className="text-lg">Tổng cộng</Text>
                                                <Text type="secondary" className="block text-sm">
                                                    {getTotalSelectedRooms()} phòng • {getNights()} đêm
                                                </Text>
                                            </div>
                                            <div className="text-right">
                                                <Title level={3} className="text-green-600 mb-0">
                                                    {formatVND(calculateGrandTotal())}
                                                </Title>
                                                <Text type="secondary" className="text-sm">tổng tiền</Text>
                                            </div>
                                        </div>

                                        {/* Savings Display */}
                                        {calculateTotalSavings() > 0 && (
                                            <div className="flex justify-between items-center mt-2 pt-2 border-t border-green-200">
                                                <Text className="text-green-700">
                                                    <GiftOutlined className="mr-1" />
                                                    Tổng tiết kiệm:
                                                </Text>
                                                <Text strong className="text-green-700">
                                                    {formatVND(calculateTotalSavings())}
                                                </Text>
                                            </div>
                                        )}
                                    </div>

                                    {/* Booking Button */}
                                    <Button
                                        type="primary"
                                        size="large"
                                        block
                                        onClick={handleBooking}
                                        className="h-14 text-lg font-semibold shadow-lg hover:shadow-xl transition-all"
                                        style={{
                                            background: 'linear-gradient(135deg, #1890ff 0%, #096dd9 100%)',
                                            border: 'none',
                                            borderRadius: '8px'
                                        }}
                                    >
                                        <div className="flex items-center justify-center gap-2">
                                            <CreditCardOutlined />
                                            Đặt phòng ngay
                                        </div>
                                    </Button>

                                    {/* Security Notice */}
                                    <div className="mt-4 text-center">
                                        <Text type="secondary" className="text-xs flex items-center justify-center gap-1">
                                            <SafetyCertificateOutlined className="text-green-500" />
                                            Đặt phòng an toàn và bảo mật
                                        </Text>
                                    </div>
                                </div>
                            )}


                        </Card>
                    </Affix>
                </Col>
            </Row>
            </div>

            {/* Dynamic Pricing Alert */}
            <div className="max-w-7xl mx-auto px-4 mb-6">
                <Alert
                    message="Giá động thông minh được áp dụng"
                    description={
                        <div>
                            <Text>Giá phòng được tự động điều chỉnh dựa trên:</Text>
                            <ul className="mt-2 ml-4 text-sm text-gray-600">
                                <li>• Thời điểm đặt phòng (đặt sớm hoặc phút cuối)</li>
                                <li>• Ngày cao điểm/cuối tuần</li>
                                <li>• Chính sách hủy và thanh toán linh hoạt</li>
                                <li>• Độ dài lưu trú (ưu đãi lưu trú dài hạn)</li>
                            </ul>
                            <Text className="text-green-600 font-medium mt-2 block">
                                🎯 Hệ thống tự động tìm giá tốt nhất cho bạn!
                            </Text>
                        </div>
                    }
                    type="info"
                    showIcon
                    icon={<ClockCircleOutlined />}
                    className="mb-4"
                    closable
                />
            </div>

            {/* Room Detail Modal */}
            <Modal
                title={selectedRoomDetail?.name}
                open={isRoomDetailModalVisible}
                onCancel={() => setIsRoomDetailModalVisible(false)}
                footer={[
                    <Button key="back" onClick={() => setIsRoomDetailModalVisible(false)}>
                        Đóng
                    </Button>,
                    <Button
                        key="detail"
                        type="default"
                        onClick={() => {
                            if (selectedRoomDetail) {
                                navigateToRoomDetail(selectedRoomDetail.id.toString());
                            }
                        }}
                    >
                        Xem chi tiết phòng
                    </Button>,
                    <Button
                        key="book"
                        type="primary"
                        onClick={() => {
                            if (selectedRoomDetail) {
                                navigateToRoomDetail(selectedRoomDetail.id.toString());
                            }
                        }}
                    >
                        Đặt phòng ngay
                    </Button>
                ]}
                width={1000}
            >
                {selectedRoomDetail && (
                    <div>
                        {/* Room Images Carousel */}
                        {selectedRoomDetail.images && selectedRoomDetail.images.length > 0 ? (
                            <Carousel
                                dots={{ className: '' }}
                                arrows
                                prevArrow={<LeftOutlined />}
                                nextArrow={<RightOutlined />}
                                className="mb-4"
                            >
                                {selectedRoomDetail.images.map((img, index) => (
                                    <div key={index}>
                                        <Image
                                            src={img}
                                            alt={`${selectedRoomDetail.name} ${index + 1}`}
                                            className="w-full h-54 object-cover rounded"
                                            fallback="https://via.placeholder.com/800x300?text=Room+Image"
                                        />
                                    </div>
                                ))}
                            </Carousel>
                        ) : (
                            <Image
                                src={selectedRoomDetail.image}
                                alt={selectedRoomDetail.name}
                                className="w-full h-64 object-cover rounded mb-4"
                                fallback="https://via.placeholder.com/800x300?text=Room+Image"
                            />
                        )}

                        {/* Room Details */}
                        <Row gutter={[16, 16]}>
                            <Col span={12}>
                                <Space direction="vertical" size="small" className="w-full">
                                    <div>
                                        <Text strong>Diện tích: </Text>
                                        <Text>{selectedRoomDetail.size}m²</Text>
                                    </div>
                                    <div>
                                        <Text strong>View: </Text>
                                        <Text>{selectedRoomDetail.view}</Text>
                                    </div>
                                    <div>
                                        <Text strong>Số khách tối đa: </Text>
                                        <Text>{selectedRoomDetail.maxGuests} khách</Text>
                                    </div>
                                    {selectedRoomDetail.rating && (
                                        <div>
                                            <Text strong>Đánh giá: </Text>
                                            <Space>
                                                <StarFilled className="text-yellow-500" />
                                                <Text>{selectedRoomDetail.rating}/10</Text>
                                            </Space>
                                        </div>
                                    )}
                                </Space>
                            </Col>
                            <Col span={12}>
                                <div className="text-right">
                                    <Text type="secondary">Giá từ</Text>
                                    <div>
                                        <Title level={3} className="text-blue-600 mb-0">
                                            {formatVND(selectedRoomDetail.priceVND)}
                                        </Title>
                                        <Text type="secondary">/đêm</Text>
                                    </div>
                                </div>
                            </Col>
                        </Row>

                        <Divider />

                        {/* All Amenities */}
                        <div>
                            <Text strong className="block mb-3">Tiện ích đầy đủ:</Text>
                            <Space size="small" wrap>
                                {selectedRoomDetail.amenities.map((amenity, index) => (
                                    <Tag key={index} className="rounded-full">
                                        {amenity}
                                    </Tag>
                                ))}
                            </Space>
                        </div>

                        {selectedRoomDetail.description && (
                            <>
                                <Divider />
                                <div>
                                    <Text strong className="block mb-2">Mô tả:</Text>
                                    <Text>{selectedRoomDetail.description}</Text>
                                </div>
                            </>
                        )}
                    </div>
                )}
            </Modal>

            {/* Image Gallery Modal */}
        </div>
    );
};

export default SearchResults;
