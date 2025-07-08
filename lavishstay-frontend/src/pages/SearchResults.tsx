import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import {
    Card,
    Row,
    Col,
    Typography,
    Button,
    Space,
    message,
    Tag,
    Skeleton,
    Empty,
    Image,
    Radio,
    Alert,
    Badge,
    Modal,
    Carousel,
    Divider
} from 'antd';
import {
    CheckCircleOutlined,
    TeamOutlined,
    WifiOutlined,
    CarOutlined,
    HomeOutlined,
    CoffeeOutlined,
    ShopOutlined,
    RestOutlined,
    SafetyOutlined,
    FireOutlined,
    StarOutlined,
    ExpandOutlined,
    ArrowRightOutlined,
    InfoCircleOutlined
} from '@ant-design/icons';
import { useSelector, useDispatch } from 'react-redux';
import { RootState } from '../store';
import { initializeBookingSelection, setRoomsData, recalculateTotals } from '../store/slices/bookingSlice';
import { selectSearchResults, selectHasSearched, selectIsLoading } from '../store/slices/searchSlice';
import { searchService } from '../services/searchService';
import SearchForm from '../components/SearchForm';
import { Bed } from 'lucide-react';
import dayjs from 'dayjs';

const { Title, Text } = Typography;

// Amenity icon mapping
const getAmenityIcon = (amenityName: string) => {
    const iconMap: Record<string, React.ReactNode> = {
        'WiFi': <WifiOutlined />,
        'Free WiFi': <WifiOutlined />,
        'Parking': <CarOutlined />,
        'Free Parking': <CarOutlined />,
        'Room Service': <HomeOutlined />,
        'Coffee Maker': <CoffeeOutlined />,
        'Mini Bar': <ShopOutlined />,
        'Safe': <SafetyOutlined />,
        'Air Conditioning': <RestOutlined />,
        'TV': <RestOutlined />,
        'Telephone': <RestOutlined />,
        'Bathtub': <RestOutlined />,
        'Shower': <RestOutlined />,
        'Hair Dryer': <RestOutlined />,
        'Balcony': <HomeOutlined />,
        'City View': <HomeOutlined />,
        'Ocean View': <HomeOutlined />,
        'Mountain View': <HomeOutlined />
    };
    return iconMap[amenityName] || <CheckCircleOutlined />;
};

// Room type grouping - Only separate "The Level" from others
const getRoomTypeGroup = (roomType: string): string => {
    if (roomType.includes('the_level')) return 'The Level';
    return 'Premium Rooms';
};

const getRoomTypeDisplayName = (roomType: string): string => {
    const typeMap: Record<string, string> = {
        'deluxe': 'Deluxe Room',
        'premium': 'Premium Room',
        'premium_corner': 'Premium Corner Room',
        'suite': 'Executive Suite',
        'the_level_premium': 'The Level Premium',
        'the_level_premium_corner': 'The Level Premium Corner',
        'the_level_suite': 'The Level Suite',
        'presidential': 'Presidential Suite'
    };
    return typeMap[roomType] || roomType.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
};

// Get room tier styling
const getRoomTier = (roomType: string) => {
    if (roomType.includes('the_level')) {
        return { color: '#8B5CF6', icon: <FireOutlined />, tier: 'The Level' };
    }
    return { color: '#1890FF', icon: <StarOutlined />, tier: 'Premium' };
};

const SearchResults: React.FC = () => {
    const navigate = useNavigate();
    const dispatch = useDispatch();
    const searchData = useSelector((state: RootState) => state.search);

    // Get search results from Redux store
    const searchResults = useSelector(selectSearchResults);
    const hasSearched = useSelector(selectHasSearched);
    const isSearchLoading = useSelector(selectIsLoading);

    const [loading, setLoading] = useState(true);
    const [roomData, setRoomData] = useState<any[]>([]);
    const [searchSummary, setSearchSummary] = useState<any>(null);
    const [selectedPackages, setSelectedPackages] = useState<Record<string, string>>({});
    const [modalVisible, setModalVisible] = useState(false);
    const [selectedRoom, setSelectedRoom] = useState<any>(null);

    // Format VND currency
    const formatVND = (price: number) => {
        return new Intl.NumberFormat("vi-VN", {
            style: "currency",
            currency: "VND",
        }).format(price);
    };

    // Handle show room details
    const showRoomDetails = (room: any) => {
        setSelectedRoom(room);
        setModalVisible(true);
    };

    // Handle package selection
    const handlePackageSelect = (roomId: string, packageId: string) => {
        setSelectedPackages(prev => ({
            ...prev,
            [roomId]: packageId
        }));
    };

    // Handle booking with rooms_needed from API
    const handleBookRoom = (room: any, packageOption: any) => {
        console.log('📋 Initializing booking with:', { room, packageOption });

        // Get the number of rooms from the search data
        const roomsNeeded = searchData.rooms?.length || 1;

        // Calculate nights
        const checkIn = searchData.checkIn || searchData.dateRange?.[0];
        const checkOut = searchData.checkOut || searchData.dateRange?.[1];
        let nights = 1;
        if (checkIn && checkOut) {
            if (typeof checkIn === 'string' && typeof checkOut === 'string') {
                nights = Math.ceil((new Date(checkOut).getTime() - new Date(checkIn).getTime()) / (1000 * 60 * 60 * 24));
            } else {
                // Handle dayjs objects
                const checkInDate = dayjs(checkIn);
                const checkOutDate = dayjs(checkOut);
                nights = checkOutDate.diff(checkInDate, 'day');
            }
        }

        // Dispatch the new action to properly initialize the selection
        dispatch(initializeBookingSelection({
            room: room,
            option: packageOption,
            quantity: roomsNeeded, // Use the count from search data
        }));

        // Also ensure the full rooms data is in the store for the summary selector
        dispatch(setRoomsData(roomData));

        // Recalculate totals with the new nights and guest count
        const totalGuests = searchData.rooms?.reduce((sum, room) => sum + room.adults + room.children, 0) || 2;
        dispatch(recalculateTotals({
            nights: nights,
            guestCount: totalGuests
        }));

        // Navigate to payment page
        navigate('/payment');
    };

    // Load search results from Redux store or fetch if not available
    useEffect(() => {
        if (searchResults && hasSearched) {
            // Use results from Redux store
            console.log('📥 Using search results from Redux store:', searchResults);
            setRoomData(searchResults.rooms || []);
            setSearchSummary(searchResults.searchSummary || null);
            setLoading(false);
        } else if (searchData.dateRange && searchData.guests > 0) {
            // Fallback: fetch results if not in store
            const fetchResults = async () => {
                try {
                    setLoading(true);
                    console.log('🔍 Fetching search results (fallback) with data:', searchData);

                    const results = await searchService.searchRooms(searchData);
                    console.log('📥 Search results (fallback):', results);

                    setRoomData(results.rooms || []);
                    setSearchSummary(results.searchSummary || null);
                } catch (error: any) {
                    message.error('Có lỗi xảy ra khi tải kết quả tìm kiếm');
                    console.error('❌ Search error:', error);
                } finally {
                    setLoading(false);
                }
            };

            fetchResults();
        } else {
            // No search data available
            setLoading(false);
            message.warning('Không có dữ liệu tìm kiếm. Vui lòng thực hiện tìm kiếm mới.');
        }
    }, [searchResults, hasSearched, searchData]);

    if (loading || isSearchLoading) {
        return (
            <div className="min-h-screen py-8">
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

    if (roomData.length === 0) {
        return (
            <div className="min-h-screen py-8">
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

    // Group rooms by type
    const groupedRooms = roomData.reduce((groups: Record<string, any[]>, room) => {
        const group = getRoomTypeGroup(room.roomType);
        if (!groups[group]) {
            groups[group] = [];
        }
        groups[group].push(room);
        return groups;
    }, {});

    return (
        <div className="">
            {/* Search Form Header */}
            <Card className="rounded-none border-0 shadow-sm">
                <div className="max-w-7xl mx-auto">
                    <SearchForm className="search-form-compact" />
                </div>
            </Card>

            {/* Main Content */}
            <div className="max-w-7xl mx-auto px-4 py-8">
                <Space direction="vertical" size={40} className="w-full">
                    {Object.entries(groupedRooms).map(([groupName, rooms]) => {
                        const tierInfo = getRoomTier(rooms[0]?.roomType || '');

                        return (
                            <div key={groupName}>
                                {groupName === 'The Level' && (
                                    <Alert
                                        message={
                                            <div className="flex items-center">
                                                <FireOutlined className="text-yellow-600 mr-2" />
                                                <span className="font-semibold">Khám phá dịch vụ tại The Level</span>
                                            </div>
                                        }
                                        description="Tại LavishStay, The Level đại diện cho đỉnh cao của sự sang trọng, mang đến những đặc quyền và tiện nghi độc quyền."
                                        type="info"
                                        showIcon={false}
                                        className="mb-6"
                                        action={
                                            <Button type="link" size="small" className="p-0">
                                                Tìm hiểu thêm <ArrowRightOutlined />
                                            </Button>
                                        }
                                    />
                                )}


                                {/* Room Cards */}
                                <Row gutter={[24, 24]} align="stretch">
                                    {rooms.map((room: any) => (
                                        <Col key={room.id} xs={24} lg={12} xl={8} className="flex">
                                            <Badge.Ribbon text={getRoomTypeDisplayName(room.roomType)} placement="start" color={tierInfo.color}>
                                                <Card
                                                    className="w-full h-full flex flex-col shadow-md hover:shadow-lg transition-all duration-300 rounded-xl overflow-hidden"
                                                    bodyStyle={{ padding: 0, flex: 1, display: 'flex', flexDirection: 'column', height: '100%' }}
                                                    cover={
                                                        <div className="relative">
                                                            <Image
                                                                alt={room.name}
                                                                src={room.image}
                                                                className="object-cover w-full"
                                                                preview={{
                                                                    mask: (
                                                                        <div className="flex items-center justify-center">
                                                                            <ExpandOutlined className="text-white text-lg" />
                                                                            <span className="ml-2 text-white">Xem ảnh</span>
                                                                        </div>
                                                                    )
                                                                }}
                                                                fallback="https://dam.melia.com/melia/file/iXGwjwBVnTHehdUyTT57.jpg?im=RegionOfInterestCrop=(1920,1281),regionOfInterest=(1771.5,1181.5)"
                                                            />
                                                            <div className="absolute top-3 right-3">
                                                                <Tag color="gold" className="border-0 shadow-sm">
                                                                    <StarOutlined /> {room.rating}
                                                                </Tag>
                                                            </div>
                                                        </div>
                                                    }
                                                >
                                                    <div className="p-5 flex-grow flex flex-col min-h-[600px]">
                                                        {/* Room Info */}
                                                        <div className="mb-4 flex-shrink-0">
                                                            <Title level={4} className="mb-2 text-gray-800 line-clamp-2" style={{ minHeight: '60px' }}>
                                                                {room.name}
                                                            </Title>
                                                            <Text className="text-gray-600 text-sm leading-relaxed line-clamp-3" style={{ minHeight: '60px', display: '-webkit-box', WebkitLineClamp: 3, WebkitBoxOrient: 'vertical', overflow: 'hidden' }}>
                                                                {room.description}
                                                            </Text>
                                                        </div>

                                                        {/* Room Details */}
                                                        <div className="grid grid-cols-3 gap-2 mb-4 py-3 px-4 bg-gray-50 rounded-lg text-center">
                                                            <div className="flex flex-col items-center">
                                                                <HomeOutlined className="text-blue-600 mb-1" />
                                                                <span className="text-xs text-gray-700">{room.size || 35}m²</span>
                                                            </div>
                                                            <div className="flex flex-col items-center">
                                                                <Bed className="text-orange-600 mb-1" />
                                                                <div className="flex flex-wrap justify-center gap-x-2">
                                                                    {room.bed_type_name ? (
                                                                        <span className="text-xs text-gray-700">{room.bed_type_name}</span>
                                                                    ) : (
                                                                        <span className="text-xs text-gray-700">Standard</span>
                                                                    )}
                                                                </div>
                                                            </div>
                                                            <div className="flex flex-col items-center">
                                                                <TeamOutlined className="text-green-600 mb-1" />
                                                                <span className="text-xs text-gray-700">{room.maxGuests || 2} khách</span>
                                                            </div>
                                                        </div>

                                                        {/* Amenities */}
                                                        <div className="mb-5 flex-shrink-0">
                                                            <div className="flex flex-wrap gap-2">
                                                                {(room.highlighted_amenities || room.mainAmenities || ['WiFi', 'TV', 'Mini Bar', 'Safe'])
                                                                    .slice(0, 4)
                                                                    .map((amenity: string, index: number) => (
                                                                        <Tag key={index} className="text-xs border-gray-200 text-gray-600">
                                                                            {getAmenityIcon(amenity)}
                                                                            <span className="ml-1">{amenity}</span>
                                                                        </Tag>
                                                                    ))}
                                                            </div>
                                                            <Button
                                                                type="link"
                                                                size="small"
                                                                onClick={() => showRoomDetails(room)}
                                                                className="p-0 mt-2 text-blue-600"
                                                                icon={<InfoCircleOutlined />}
                                                            >
                                                                Xem chi tiết phòng
                                                            </Button>
                                                        </div>

                                                        {/* Package Options */}
                                                        <div className="mb-5">
                                                            <Text strong className="text-gray-800 mb-3 block text-sm">
                                                                Chọn gói dịch vụ
                                                            </Text>
                                                            <Space direction="vertical" className="w-full" size={12}>
                                                                {(room.options || []).map((option: any) => {
                                                                    const isSelected = selectedPackages[room.id] === option.id || (!selectedPackages[room.id] && room.options?.[0]?.id === option.id);
                                                                    const packageCard = (
                                                                        <div
                                                                            key={option.id}
                                                                            className={`relative p-4 border rounded-lg cursor-pointer transition-all duration-300 ease-in-out ${isSelected ? 'border-blue-600 bg-blue-50/50 shadow-sm' : 'border-gray-200 bg-white hover:border-blue-400 hover:bg-gray-50/50'}`}
                                                                            onClick={() => handlePackageSelect(room.id, option.id)}
                                                                        >
                                                                            <div className={`absolute left-0 top-0 h-full w-1 rounded-l-lg transition-all duration-300 ${isSelected ? 'bg-blue-600' : 'bg-transparent'}`}></div>
                                                                            <div className="flex justify-between items-center w-full">
                                                                                <div className="flex-1 pr-4">
                                                                                    <div className="flex items-center gap-2 mb-1">
                                                                                        <span className="text-base font-semibold text-gray-900">{option.name}</span>
                                                                                    </div>
                                                                                    <Text className="text-sm text-gray-600 block w-full">{option.description}</Text>
                                                                                </div>
                                                                                <div className="text-right ml-2">
                                                                                    <div className="font-bold text-blue-600 text-lg">{formatVND(option.totalPrice || 0)}</div>
                                                                                    <div className="text-sm text-gray-500">{formatVND(option.pricePerNight?.vnd || 0)}/đêm</div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    );

                                                                    if (option.recommended) {
                                                                        return (
                                                                            <Badge.Ribbon text="Đề xuất" color="gold">
                                                                                {packageCard}
                                                                            </Badge.Ribbon>
                                                                        );
                                                                    }
                                                                    return packageCard;
                                                                })}
                                                            </Space>
                                                        </div>

                                                        {/* Book Button */}
                                                        <div className="mt-auto">
                                                            <Button
                                                                type="primary"
                                                                block
                                                                size="large"
                                                                onClick={() => {
                                                                    const selectedPackageId = selectedPackages[room.id] || room.options?.[0]?.id;
                                                                    const selectedPackage = room.options?.find((opt: any) => opt.id === selectedPackageId);
                                                                    if (selectedPackage) {
                                                                        handleBookRoom(room, selectedPackage);
                                                                    } else {
                                                                        message.warning('Vui lòng chọn gói dịch vụ');
                                                                    }
                                                                }}
                                                                className="h-11 text-base rounded-lg font-medium"
                                                                icon={<ArrowRightOutlined />}
                                                            >
                                                                Đặt phòng ngay
                                                            </Button>
                                                        </div>
                                                    </div>
                                                </Card>
                                            </Badge.Ribbon>
                                        </Col>
                                    ))}
                                </Row>
                            </div>
                        );
                    })}
                </Space>
            </div>

            {/* Room Details Modal */}
            <Modal
                title={selectedRoom?.name || "Chi tiết phòng"}
                open={modalVisible}
                onCancel={() => setModalVisible(false)}
                footer={[
                    <Button key="close" onClick={() => setModalVisible(false)}>
                        Đóng
                    </Button>,
                    <Button
                        key="book"
                        type="primary"
                        onClick={() => {
                            const selectedPackageId = selectedPackages[selectedRoom?.id] || selectedRoom?.options?.[0]?.id;
                            const selectedPackage = selectedRoom?.options?.find((opt: any) => opt.id === selectedPackageId);
                            if (selectedPackage) {
                                handleBookRoom(selectedRoom, selectedPackage);
                            } else {
                                message.warning('Vui lòng chọn gói dịch vụ');
                            }
                        }}
                    >
                        Đặt phòng ngay
                    </Button>
                ]}
                width={800}
                className="room-details-modal"
            >
                {selectedRoom && (
                    <div>
                        {/* Image Gallery */}
                        <div className="mb-6">
                            <Carousel autoplay>
                                {selectedRoom.images?.length > 0 ? selectedRoom.images.map((img: any, index: number) => (
                                    <div key={index}>
                                        <img
                                            src={img.image_url}
                                            alt={img.alt_text || selectedRoom.name}
                                            className="w-full h-64 object-cover rounded-lg"
                                        />
                                    </div>
                                )) : (
                                    <div>
                                        <img
                                            src={selectedRoom.image || "https://dam.melia.com/melia/file/iXGwjwBVnTHehdUyTT57.jpg?im=RegionOfInterestCrop=(1920,1281),regionOfInterest=(1771.5,1181.5)"}
                                            alt={selectedRoom.name}
                                            className="w-full h-64 object-cover rounded-lg"
                                        />
                                    </div>
                                )}
                            </Carousel>
                        </div>

                        {/* Room Information */}
                        <div className="mb-6">
                            <Text className="text-gray-600 leading-relaxed">
                                {selectedRoom.description}
                            </Text>
                        </div>

                        {/* Room Specifications */}
                        <div className="grid grid-cols-2 gap-4 mb-6">
                            <div className="p-4 bg-gray-50 rounded-lg">
                                <div className="flex items-center mb-2">
                                    <HomeOutlined className="text-blue-600 mr-2" />
                                    <span className="font-medium">Diện tích</span>
                                </div>
                                <span className="text-gray-600">{selectedRoom.size || 35}m²</span>
                            </div>
                            <div className="p-4 bg-gray-50 rounded-lg">
                                <div className="flex items-center mb-2">
                                    <Bed className="text-orange-600 mr-2" />
                                    <span className="font-medium">Loại giường</span>
                                </div>
                                <span className="text-gray-600">{selectedRoom.bed_type_name || 'Standard'}</span>
                            </div>
                            <div className="p-4 bg-gray-50 rounded-lg">
                                <div className="flex items-center mb-2">
                                    <TeamOutlined className="text-green-600 mr-2" />
                                    <span className="font-medium">Số khách tối đa</span>
                                </div>
                                <span className="text-gray-600">{selectedRoom.maxGuests || 2} khách</span>
                            </div>
                            <div className="p-4 bg-gray-50 rounded-lg">
                                <div className="flex items-center mb-2">
                                    <StarOutlined className="text-yellow-600 mr-2" />
                                    <span className="font-medium">Đánh giá</span>
                                </div>
                                <span className="text-gray-600">{selectedRoom.rating}/5</span>
                            </div>
                        </div>

                        {/* All Amenities */}
                        <div className="mb-6">
                            <Title level={5} className="mb-3">Tiện nghi phòng</Title>
                            <div className="grid grid-cols-2 gap-2">
                                {(selectedRoom.amenities || []).map((amenity: any, index: number) => (
                                    <div key={index} className="flex items-center p-2 border border-gray-200 rounded">
                                        {getAmenityIcon(amenity.name)}
                                        <span className="ml-2 text-sm">{amenity.name}</span>
                                    </div>
                                ))}
                            </div>
                        </div>

                        <Divider />

                        {/* Package Selection in Modal */}
                        <div className="mb-4">
                            <Title level={5} className="mb-3">Chọn gói dịch vụ</Title>
                            <Space direction="vertical" className="w-full" size={12}>
                                {(selectedRoom.options || []).map((option: any) => {
                                    const isSelected = selectedPackages[selectedRoom.id] === option.id || (!selectedPackages[selectedRoom.id] && selectedRoom.options?.[0]?.id === option.id);
                                    return (
                                        <div
                                            key={option.id}
                                            className={`p-4 border rounded-lg cursor-pointer transition-all ${isSelected ? 'border-blue-600 bg-blue-50' : 'border-gray-200 hover:border-blue-400'}`}
                                            onClick={() => handlePackageSelect(selectedRoom.id, option.id)}
                                        >
                                            <div className="flex justify-between items-center">
                                                <div>
                                                    <div className="font-semibold text-gray-900">{option.name}</div>
                                                    <Text className="text-sm text-gray-600">{option.description}</Text>
                                                </div>
                                                <div className="text-right">
                                                    <div className="font-bold text-blue-600 text-lg">{formatVND(option.totalPrice || 0)}</div>
                                                    <div className="text-sm text-gray-500">{formatVND(option.pricePerNight?.vnd || 0)}/đêm</div>
                                                </div>
                                            </div>
                                        </div>
                                    );
                                })}
                            </Space>
                        </div>
                    </div>
                )}
            </Modal>
        </div>
    );
};

export default SearchResults;
