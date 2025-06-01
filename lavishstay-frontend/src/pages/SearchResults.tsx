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
    InputNumber,
    message,
    Tag,
    Image,
    Skeleton,
    Empty,
    Affix,
    Tooltip,
    Modal,
    Carousel
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
    WifiOutlined,
    CarOutlined,
    CoffeeOutlined,
    LeftOutlined,
    RightOutlined,
    ExpandOutlined
} from '@ant-design/icons';
import { useSelector } from 'react-redux';
import { RootState } from '../store';
import { Room } from '../mirage/models';
import { RoomOption } from '../mirage/roomoption';
import { searchService } from '../services/searchService';
import SearchForm from '../components/SearchForm';

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
    const [currentImageIndex, setCurrentImageIndex] = useState(0);
    const [currentRoomImages, setCurrentRoomImages] = useState<string[]>([]);

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
    };

    // Calculate total price for an option
    const calculateOptionTotal = (option: RoomOption, quantity: number) => {
        return option.pricePerNight.vnd * quantity;
    };

    // Calculate total for all selected rooms
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
    };

    // Get total selected rooms count
    const getTotalSelectedRooms = () => {
        let total = 0;
        Object.values(selectedRooms).forEach(options => {
            Object.values(options).forEach(quantity => {
                total += quantity;
            });
        });
        return total;
    };

    // Get main amenities to display (limit to 4-5)
    const getMainAmenities = (amenities: string[]) => {
        const iconMap: { [key: string]: React.ReactNode } = {
            'WiFi': <WifiOutlined />,
            'Điều hòa không khí': <CarOutlined />,
            'TV màn hình phẳng': <EyeOutlined />,
            'Tủ lạnh': <CoffeeOutlined />,
        };

        return amenities.slice(0, 4).map(amenity => ({
            name: amenity,
            icon: iconMap[amenity] || <CheckCircleOutlined />
        }));
    };

    // Handle room detail modal
    const showRoomDetail = (room: Room) => {
        setSelectedRoomDetail(room);
        setIsRoomDetailModalVisible(true);
    };

    // Handle image gallery modal
    const showImageGallery = (room: Room, startIndex: number = 0) => {
        const images = room.images && room.images.length > 0 ? room.images : [room.image];
        setCurrentRoomImages(images);
        setCurrentImageIndex(startIndex);
        setImageModalVisible(true);
    };

    // Handle room detail page navigation
    const navigateToRoomDetail = (roomId: string) => {
        navigate(`/rooms/${roomId}`);
    };

    // Fetch search results
    useEffect(() => {
        const fetchResults = async () => {
            try {
                setLoading(true);
                const results = await searchService.searchRooms(searchData);
                setRooms(results.rooms);
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
    };

    if (loading) {
        return (
            <div className="min-h-screen bg-gray-50 py-8">
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
            <div className="bg-white shadow-sm border-b">
                <div className="max-w-7xl mx-auto px-4 py-6">
                    <div className="mb-4">
                        <Title level={2} className="mb-2">
                            Kết quả tìm kiếm phòng ({total} phòng)
                        </Title>
                        <Space size="middle">
                            {searchData.dateRange && (
                                <Text type="secondary">
                                    📅 {searchData.checkIn} - {searchData.checkOut}
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
            </div>

            <div className="max-w-7xl mx-auto px-4 py-8">
                <Row gutter={[24, 24]}>
                    {/* Room Cards */}
                    <Col xs={24} lg={16}>
                        <Space direction="vertical" size="large" className="w-full">
                            {rooms.map((room) => {
                                const isSuitable = isRoomSuitable(room);

                                return (
                                    <Card
                                        key={room.id}
                                        className="shadow-sm hover:shadow-md transition-shadow"
                                        bodyStyle={{ padding: 0 }}
                                    >
                                        <Row>
                                            {/* Room Image with Gallery */}
                                            <Col xs={24} md={8}>
                                                <div className="relative h-64 md:h-full">
                                                    {/* Main Image */}
                                                    <div 
                                                        className="cursor-pointer"
                                                        onClick={() => showImageGallery(room, 0)}
                                                    >
                                                        <Image
                                                            src={room.image}
                                                            alt={room.name}
                                                            className="w-full h-full object-cover"
                                                            fallback="https://via.placeholder.com/400x300?text=Room+Image"
                                                            preview={false}
                                                        />
                                                    </div>

                                                    {/* Image Gallery Thumbnails */}
                                                    {room.images && room.images.length > 1 && (
                                                        <div className="absolute bottom-2 left-2 right-2">
                                                            <div className="flex gap-1">
                                                                {room.images.slice(0, 4).map((img, idx) => (
                                                                    <div
                                                                        key={idx}
                                                                        className="flex-1 h-12 cursor-pointer opacity-80 hover:opacity-100 transition-opacity relative"
                                                                        onClick={(e) => {
                                                                            e.stopPropagation();
                                                                            showImageGallery(room, idx);
                                                                        }}
                                                                    >
                                                                        <Image
                                                                            src={img}
                                                                            alt={`${room.name} ${idx + 1}`}
                                                                            className="w-full h-full object-cover rounded"
                                                                            preview={false}
                                                                        />
                                                                        {idx === 3 && room.images.length > 4 && (
                                                                            <div className="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center rounded">
                                                                                <Text className="text-white text-xs font-bold">
                                                                                    +{room.images.length - 3}
                                                                                </Text>
                                                                            </div>
                                                                        )}
                                                                    </div>
                                                                ))}
                                                            </div>
                                                        </div>
                                                    )}

                                                    {/* Action Buttons */}
                                                    <div className="absolute top-2 right-2">
                                                        <Space direction="vertical" size="small">
                                                            <Tooltip title="Xem chi tiết">
                                                                <Button
                                                                    type="default"
                                                                    shape="circle"
                                                                    icon={<EyeOutlined />}
                                                                    size="small"
                                                                    onClick={(e) => {
                                                                        e.stopPropagation();
                                                                        showRoomDetail(room);
                                                                    }}
                                                                />
                                                            </Tooltip>
                                                        </Space>
                                                    </div>

                                                    {/* Badges */}
                                                    {room.isSale && (
                                                        <div className="absolute top-4 left-4">
                                                            <Badge.Ribbon text={`-${room.discount}%`} color="red" />
                                                        </div>
                                                    )}
                                                    {!isSuitable && (
                                                        <div className="absolute bottom-4 left-4">
                                                            <Badge
                                                                status="warning"
                                                                text="Không phù hợp số người"
                                                                className="bg-yellow-100 px-2 py-1 rounded-full text-xs"
                                                            />
                                                        </div>
                                                    )}
                                                </div>
                                            </Col>

                                            {/* Room Details */}
                                            <Col xs={24} md={16}>
                                                <div className="p-6">
                                                    <div className="flex items-center justify-between mb-4">
                                                        <div>
                                                            <div className="flex items-center justify-between mb-4">
                                                                <div>
                                                                    <Title level={3} className="mb-2">
                                                                        {room.name}
                                                                    </Title>
                                                                    <Space size="middle" className="mb-3">
                                                                        <Text type="secondary">📐 {room.size}m²</Text>
                                                                        <Text type="secondary">🪟 {room.view}</Text>
                                                                        <Text type="secondary">👥 Tối đa {room.maxGuests} khách</Text>
                                                                    </Space>
                                                                    {room.rating && (
                                                                        <div className="flex items-center gap-2 mb-3">
                                                                            <StarFilled className="text-yellow-500" />
                                                                            <Text strong>{room.rating}/10</Text>
                                                                        </div>
                                                                    )}
                                                                </div>
                                                                <Space direction="vertical" align="end">
                                                                    <div className="text-right">
                                                                        <Text type="secondary" className="text-sm">Giá từ</Text>
                                                                        <div>
                                                                            <Title level={4} className="text-blue-600 mb-0">
                                                                                {formatVND(room.priceVND)}
                                                                            </Title>
                                                                            <Text type="secondary" className="text-xs">/đêm</Text>
                                                                        </div>
                                                                    </div>
                                                                </Space>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {/* Amenities */}
                                                    <div className="mb-4">
                                                        <Text strong className="block mb-2">Tiện ích nổi bật:</Text>
                                                        <Space size="small" wrap>
                                                            {getMainAmenities(room.mainAmenities || room.amenities).map((amenity, index) => (
                                                                <Tag key={index} icon={amenity.icon} className="rounded-full">
                                                                    {amenity.name}
                                                                </Tag>
                                                            ))}
                                                        </Space>
                                                    </div>

                                                    {/* Room Options */}
                                                    <Divider className="my-4" />
                                                    <div>
                                                        <Text strong className="block mb-3">Lựa chọn đặt phòng:</Text>
                                                        <Space direction="vertical" size="middle" className="w-full">
                                                            {room.options.map((option) => {
                                                                const availability = getAvailabilityStatus(option.availability);
                                                                const cancellation = getCancellationPolicyDisplay(option.cancellationPolicy);
                                                                const payment = getPaymentPolicyDisplay(option.paymentPolicy);
                                                                const isUnavailable = option.availability.remaining === 0;
                                                                const currentQuantity = selectedRooms[room.id]?.[option.id] || 0;

                                                                return (
                                                                    <Card
                                                                        key={option.id}
                                                                        size="small"
                                                                        className={`border-l-4 ${currentQuantity > 0
                                                                            ? 'border-l-blue-500 bg-blue-50'
                                                                            : 'border-l-gray-300'
                                                                            } ${isUnavailable ? 'opacity-60' : ''}`}
                                                                    >
                                                                        <Row align="middle" justify="space-between">
                                                                            <Col flex={1}>
                                                                                <div>
                                                                                    <Text strong className="block mb-1">
                                                                                        {option.name}
                                                                                    </Text>
                                                                                    <Space size="small" wrap className="mb-2">
                                                                                        <Tag color={cancellation.color} icon={cancellation.icon}>
                                                                                            {cancellation.text}
                                                                                        </Tag>
                                                                                        <Tag color={payment.color} icon={payment.icon}>
                                                                                            {payment.text}
                                                                                        </Tag>
                                                                                    </Space>
                                                                                    <div className="flex items-center gap-4">
                                                                                        <Text type="secondary" className="text-xs">
                                                                                            {availability.text}
                                                                                        </Text>
                                                                                        <Progress
                                                                                            percent={Math.max(5, (option.availability.remaining / option.availability.total) * 100)}
                                                                                            status={isUnavailable ? "exception" : "active"}
                                                                                            strokeColor={availability.color}
                                                                                            size="small"
                                                                                            showInfo={false}
                                                                                            className="flex-1 max-w-24"
                                                                                        />
                                                                                    </div>
                                                                                </div>
                                                                            </Col>
                                                                            <Col>
                                                                                <div className="text-right flex items-center gap-3">
                                                                                    <div>
                                                                                        <Title level={5} className="text-green-600 mb-0">
                                                                                            {formatVND(option.pricePerNight.vnd)}
                                                                                        </Title>
                                                                                        <Text type="secondary" className="text-xs">/đêm</Text>
                                                                                    </div>
                                                                                    <div className="flex items-center gap-2">
                                                                                        <Button
                                                                                            size="small"
                                                                                            disabled={currentQuantity === 0 || isUnavailable}
                                                                                            onClick={() => handleQuantityChange(room.id.toString(), option.id, currentQuantity - 1)}
                                                                                        >
                                                                                            -
                                                                                        </Button>
                                                                                        <InputNumber
                                                                                            size="small"
                                                                                            min={0}
                                                                                            max={option.availability.remaining}
                                                                                            value={currentQuantity}
                                                                                            onChange={(value) => handleQuantityChange(room.id.toString(), option.id, value || 0)}
                                                                                            style={{ width: '60px' }}
                                                                                            disabled={isUnavailable}
                                                                                        />
                                                                                        <Button
                                                                                            size="small"
                                                                                            disabled={currentQuantity >= option.availability.remaining || isUnavailable}
                                                                                            onClick={() => handleQuantityChange(room.id.toString(), option.id, currentQuantity + 1)}
                                                                                        >
                                                                                            +
                                                                                        </Button>
                                                                                    </div>
                                                                                </div>
                                                                            </Col>
                                                                        </Row>
                                                                    </Card>
                                                                );
                                                            })}
                                                        </Space>
                                                    </div>
                                                </div>
                                            </Col>
                                        </Row>
                                    </Card>
                                );
                            })}
                        </Space>
                    </Col>

                    {/* Booking Summary Sidebar */}
                    <Col xs={24} lg={8}>
                        <Affix offsetTop={190}>
                            <Card className="sticky top-24 shadow-lg">
                                <div className="text-center mb-4">
                                    <ShoppingCartOutlined className="text-2xl text-blue-500 mb-2" />
                                    <Title level={4}>Tóm tắt đặt phòng</Title>
                                </div>

                                {getTotalSelectedRooms() === 0 ? (
                                    <div className="text-center py-8">
                                        <Text type="secondary">Chưa có phòng nào được chọn</Text>
                                    </div>
                                ) : (
                                    <div>
                                        <div className="space-y-3 mb-4">
                                            {Object.entries(selectedRooms).map(([roomId, options]) => {
                                                const room = rooms.find(r => r.id.toString() === roomId);
                                                if (!room) return null;

                                                return Object.entries(options).map(([optionId, quantity]) => {
                                                    if (quantity === 0) return null;
                                                    const option = room.options.find(opt => opt.id === optionId);
                                                    if (!option) return null;

                                                    return (
                                                        <div key={`${roomId}-${optionId}`} className="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                                            <div>
                                                                <Text strong className="block">{room.name}</Text>
                                                                <Text type="secondary" className="text-sm">{option.name}</Text>
                                                                <Text type="secondary" className="text-xs">x{quantity} phòng</Text>
                                                            </div>
                                                            <div className="text-right">
                                                                <Text strong className="text-green-600">
                                                                    {formatVND(calculateOptionTotal(option, quantity))}
                                                                </Text>
                                                                <Text type="secondary" className="text-xs block">/đêm</Text>
                                                            </div>
                                                        </div>
                                                    );
                                                });
                                            })}
                                        </div>

                                        <Divider />

                                        <div className="flex justify-between items-center mb-4">
                                            <div>
                                                <Text strong>Tổng cộng ({getTotalSelectedRooms()} phòng)</Text>
                                                <Text type="secondary" className="block text-xs">1 đêm</Text>
                                            </div>
                                            <div className="text-right">
                                                <Title level={4} className="text-red-500 mb-0">
                                                    {formatVND(calculateGrandTotal())}
                                                </Title>
                                                <Text type="secondary" className="text-xs">/đêm</Text>
                                            </div>
                                        </div>

                                        <Button
                                            type="primary"
                                            size="large"
                                            block
                                            onClick={handleBooking}
                                            className="h-12 text-lg font-semibold"
                                            style={{
                                                background: 'linear-gradient(135deg, #1890ff 0%, #096dd9 100%)',
                                                border: 'none'
                                            }}
                                        >
                                            Đặt phòng ngay
                                        </Button>

                                        <div className="mt-3 text-center">
                                            <Text type="secondary" className="text-xs">
                                                <SafetyCertificateOutlined className="mr-1" />
                                                Đặt phòng an toàn - Thanh toán được bảo mật
                                            </Text>
                                        </div>
                                    </div>
                                )}
                            </Card>
                        </Affix>
                    </Col>
                </Row>
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
                        Xem trang chi tiết
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
                                            className="w-full h-64 object-cover rounded"
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
                                                <Text>{selectedRoomDetail.rating}/5</Text>
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
