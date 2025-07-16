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
    Alert,
    Badge,
    Modal,
    Carousel,
    Divider,
    Radio,
    Tabs,
    Progress,
    Rate
} from 'antd';
import {
    TeamOutlined,
    HomeOutlined,
    FireOutlined,
    StarOutlined,
    ExpandOutlined,
    ArrowRightOutlined,
    InfoCircleOutlined,
    PictureOutlined,
    GiftOutlined,
    CrownOutlined,
    ThunderboltOutlined,
    CheckOutlined,
    ZoomOutOutlined,
    ZoomInOutlined,
    RotateLeftOutlined,
    RotateRightOutlined,
    SwapOutlined,
    UndoOutlined,
    LeftOutlined,
    RightOutlined,
} from '@ant-design/icons';
import { useSelector, useDispatch } from 'react-redux';
import { RootState } from '../store';
import { initializeBookingSelection, setTotals } from '../store/slices/bookingSlice';
import { selectSearchResults, selectHasSearched, selectIsLoading } from '../store/slices/searchSlice';
import { searchService } from '../services/searchService';
import SearchForm from '../components/SearchForm';
import AmenityDisplay from '../components/common/AmenityDisplay';
import { Bed } from 'lucide-react';
import dayjs from 'dayjs';
import './SearchResults.module.css';

// Custom styles for carousel
const carouselStyles = `
  .custom-dots .slick-dots {
    bottom: 8px;
  }
  .custom-dots .slick-dots li button {
    background: rgba(255, 255, 255, 0.6) !important;
    width: 8px !important;
    height: 8px !important;
    border-radius: 50% !important;
    margin: 0 2px !important;
  }
  .custom-dots .slick-dots li.slick-active button {
    background: rgba(255, 255, 255, 0.9) !important;
    width: 10px !important;
    height: 10px !important;
  }
  
  .custom-modal-dots .slick-dots {
    bottom: 16px;
  }
  .custom-modal-dots .slick-dots li button {
    background: rgba(59, 130, 246, 0.5) !important;
    width: 10px !important;
    height: 10px !important;
    border-radius: 50% !important;
  }
  .custom-modal-dots .slick-dots li.slick-active button {
    background: #3B82F6 !important;
    width: 12px !important;
    height: 12px !important;
  }
  
  .room-card {
    display: flex;
    flex-direction: column;
  }
  
  .room-card .ant-card-body {
    flex: 1;
    padding: 0;
    display: flex;
    flex-direction: column;
  }
`;

// Inject styles
if (typeof document !== 'undefined') {
    const style = document.createElement('style');
    style.textContent = carouselStyles;
    document.head.appendChild(style);
}

const { Title, Text } = Typography;

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
    const [selectedPackages, setSelectedPackages] = useState<Record<string, string>>({});
    const [modalVisible, setModalVisible] = useState(false);
    const [compareModalVisible, setCompareModalVisible] = useState(false);
    const [selectedRoom, setSelectedRoom] = useState<any>(null);
    const [currentImageIndex, setCurrentImageIndex] = useState(0);

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
    };    // Handle booking with rooms_needed from API
    const handleBookRoom = (room: any, packageOption: any) => {
        // Khi người dùng chọn một gói phòng, họ chỉ đặt 1 phòng, không phải nhiều phòng
        const roomsNeeded = 1;

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

        // Lấy trực tiếp totalPrice từ packageOption - đây là giá hiển thị cho người dùng
        // Đã được tính toán đúng và hiển thị trên UI
        const totalPrice = packageOption.totalPrice || 0;

        // Prepare room data with full policy information
        const roomDataWithPolicies = {
            ...room,
            option_name: packageOption.name,
            option_price: packageOption.totalPrice,
            room_price: packageOption.pricePerNight?.vnd || packageOption.totalPrice,
            package_id: packageOption.id.replace('pkg-', ''), // Remove prefix if exists
            // Include full policies data from API
            policies: packageOption.policies || {},
            // Include individual policy fields for easier access
            cancellation_policy: packageOption.cancellationPolicy,
            payment_policy: packageOption.paymentPolicy,
            check_out_policy: packageOption.checkOutPolicy,
            deposit_percentage: packageOption.depositPercentage,
            deposit_fixed_amount: packageOption.depositFixedAmount,
            free_cancellation_days: packageOption.freeCancellationDays,
            penalty_percentage: packageOption.penaltyPercentage,
            penalty_fixed_amount: packageOption.penaltyFixedAmount,
            standard_check_out_time: packageOption.standardCheckOutTime,
            meal_type: packageOption.mealType,
            bed_type: packageOption.bedType
        };

        // Dispatch đơn giản để chuẩn bị cho trang thanh toán
        dispatch(initializeBookingSelection({
            room: roomDataWithPolicies, // Pass room with policies
            option: packageOption,
            quantity: roomsNeeded
        }));

        // Set giá trị tổng cộng chính xác
        const correctTotals = {
            roomsTotal: totalPrice,
            breakfastTotal: 0,
            serviceFee: 0,
            taxAmount: 0,
            discountAmount: 0,
            finalTotal: totalPrice,
            nights: nights
        };

        // Đặt tổng giá trị vào store
        dispatch(setTotals(correctTotals));

        // Navigate to payment page
        navigate('/payment');
    };

    // Load search results from Redux store or fetch if not available
    useEffect(() => {
        if (searchResults && hasSearched) {
            // Use results from Redux store
            console.log('📥 Using search results from Redux store:', searchResults);
            setRoomData(searchResults.rooms || []);
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
                <div className="">
                    <SearchForm className="search-form-compact" />
                </div>
            </Card>

            {/* Main Content */}
            <div className="max-w-7xl mx-auto px-4 py-8 fadeIn">
                {/* Search Summary Bar */}
                {searchResults && (
                    <Card className="mb-6 border-0 shadow-sm bg-gradient-to-r from-blue-50 to-indigo-50">
                        <div className="flex flex-wrap items-center justify-between gap-4">
                            <div className="flex items-center gap-4">
                                <div className="text-center">
                                    <div className="text-2xl font-bold text-blue-600">{searchResults.total}</div>
                                    <div className="text-sm text-gray-600">Phòng tìm thấy</div>
                                </div>
                                <Divider type="vertical" className="h-12" />
                                <div className="text-center">
                                    <div className="text-lg font-semibold text-gray-800">{searchResults.searchSummary?.nights || 1}</div>
                                    <div className="text-sm text-gray-600">Đêm</div>
                                </div>
                                <Divider type="vertical" className="h-12" />
                                <div className="text-center">
                                    <div className="text-lg font-semibold text-gray-800">{searchResults.searchSummary?.totalGuests || 2}</div>
                                    <div className="text-sm text-gray-600">Khách</div>
                                </div>
                            </div>
                            <div className="text-right">
                                <div className="text-sm text-gray-600">
                                    {dayjs(searchResults.searchSummary?.checkIn).format('DD/MM/YYYY')} - {dayjs(searchResults.searchSummary?.checkOut).format('DD/MM/YYYY')}
                                </div>
                                <div className="text-xs text-gray-500 mt-1">
                                    {searchResults.searchSummary?.adults} người lớn{searchResults.searchSummary?.children ? `, ${searchResults.searchSummary.children} trẻ em` : ''}
                                </div>
                            </div>
                        </div>
                    </Card>
                )}

                {/* hiển thị toàn bộ searchResult qua thẻ p và json đẹp */}
                {/* <pre>{JSON.stringify(searchResults, null, 2)}</pre> */}
                <Space direction="vertical" size={40} className="w-full">
                    {Object.entries(groupedRooms).map(([groupName, rooms], groupIndex) => {
                        const tierInfo = getRoomTier(rooms[0]?.roomType || '');

                        return (
                            <div key={groupName} className="slideInUp" style={{ animationDelay: `${groupIndex * 0.1}s` }}>
                                {/* Group Header */}
                                <div className="mb-6">
                                    <div className="flex items-center gap-4 mb-4">
                                        <div className={`w-1 h-12 rounded-full bg-gradient-to-b ${tierInfo.color === 'gold' ? 'from-yellow-400 to-yellow-600' : tierInfo.color === 'purple' ? 'from-purple-400 to-purple-600' : 'from-blue-400 to-blue-600'}`}></div>
                                        <div>
                                            <Title level={3} className="mb-1 text-gray-800">
                                                {groupName}
                                            </Title>
                                            <Text className="text-gray-600">
                                                {rooms.length} loại phòng có sẵn
                                            </Text>
                                        </div>
                                    </div>

                                    {groupName === 'The Level' && (
                                        <Alert
                                            message={
                                                <div className="flex items-center">
                                                    <CrownOutlined className="text-yellow-600 mr-2" />
                                                    <span className="font-semibold">Khám phá dịch vụ tại The Level</span>
                                                </div>
                                            }
                                            description="Tại LavishStay, The Level đại diện cho đỉnh cao của sự sang trọng, mang đến những đặc quyền và tiện nghi độc quyền."
                                            type="info"
                                            showIcon={false}
                                            className="mb-6 border-0 bg-gradient-to-r from-yellow-50 to-orange-50"
                                            action={
                                                <Button type="link" size="small" className="p-0 text-yellow-700 hover:text-yellow-800">
                                                    Tìm hiểu thêm <ArrowRightOutlined />
                                                </Button>
                                            }
                                        />
                                    )}
                                </div>


                                {/* Room Cards */}
                                <Space direction="vertical" size="large" className="w-full">
                                    {rooms.map((room: any, roomIndex: number) => (
                                        <Card
                                            key={room.id}
                                            className="roomCard shadow-lg hover:shadow-2xl transition-all duration-500 rounded-2xl overflow-hidden border-0 slideInUp"
                                            style={{ animationDelay: `${(roomIndex + 1) * 0.1}s` }}
                                            bodyStyle={{ padding: 0 }}
                                        >
                                            <div className="p-6">
                                                {/* Room Title & Basic Info */}
                                                <div className="mb-6">
                                                    <div className="flex items-start justify-between mb-3">
                                                        <div>
                                                            <Title level={3} className="mb-2 text-gray-800">
                                                                {room.name}
                                                            </Title>
                                                            <div className="flex items-center gap-4 text-sm text-gray-600 mb-2">
                                                                <span className="flex items-center gap-1">
                                                                    <HomeOutlined /> {room.size}m²
                                                                </span>
                                                                <span className="flex items-center gap-1">
                                                                    <Bed className="w-4 h-4" /> {room.bedType}
                                                                </span>
                                                                <span className="flex items-center gap-1">
                                                                    <TeamOutlined /> {room.maxGuests || 2} khách
                                                                </span>
                                                            </div>
                                                            <Text className="text-gray-600 text-sm leading-relaxed">
                                                                {room.description}
                                                            </Text>
                                                        </div>
                                                        <div className="flex items-center gap-2">
                                                            <Tag color="gold" className="border-0 shadow-sm">
                                                                <StarOutlined /> {room.rating || 5.0}
                                                            </Tag>
                                                            <Tag color="green" className="border-0 shadow-sm">
                                                                Còn {room.availableRooms} phòng
                                                            </Tag>
                                                        </div>
                                                    </div>
                                                </div>

                                                {/* Image Gallery */}
                                                <div className="mb-6">
                                                    <Row gutter={[12, 12]}>
                                                        {/* Get all images for this room */}
                                                        {(() => {
                                                            const allImages = room.packageData?.images || room.images || [];
                                                            const allImageUrls = [
                                                                room.image || room.images?.[0] || room.packageData?.images?.[0]?.image_url,
                                                                ...allImages.slice(1).map((img: any) => typeof img === 'string' ? img : img.image_url)
                                                            ].filter(Boolean);

                                                            return (
                                                                <Image.PreviewGroup
                                                                    preview={{
                                                                        items: allImageUrls,
                                                                        toolbarRender: (
                                                                            _,
                                                                            {
                                                                                transform: { scale },
                                                                                actions: {
                                                                                    onActive,
                                                                                    onFlipY,
                                                                                    onFlipX,
                                                                                    onRotateLeft,
                                                                                    onRotateRight,
                                                                                    onZoomOut,
                                                                                    onZoomIn,
                                                                                    onReset,
                                                                                },
                                                                            },
                                                                        ) => (
                                                                            <Space size={12} className="flex items-center justify-center w-full px-4 py-3 bg-black/80 rounded-lg">
                                                                                <LeftOutlined
                                                                                    onClick={() => onActive?.(-1)}
                                                                                    className="text-white hover:text-blue-400 cursor-pointer transition-colors text-xl"
                                                                                />
                                                                                <RightOutlined
                                                                                    onClick={() => onActive?.(1)}
                                                                                    className="text-white hover:text-blue-400 cursor-pointer transition-colors text-xl"
                                                                                />
                                                                                <SwapOutlined
                                                                                    rotate={90}
                                                                                    onClick={onFlipY}
                                                                                    className="text-white hover:text-blue-400 cursor-pointer transition-colors text-xl"
                                                                                />
                                                                                <SwapOutlined
                                                                                    onClick={onFlipX}
                                                                                    className="text-white hover:text-blue-400 cursor-pointer transition-colors text-xl"
                                                                                />
                                                                                <RotateLeftOutlined
                                                                                    onClick={onRotateLeft}
                                                                                    className="text-white hover:text-blue-400 cursor-pointer transition-colors text-xl"
                                                                                />
                                                                                <RotateRightOutlined
                                                                                    onClick={onRotateRight}
                                                                                    className="text-white hover:text-blue-400 cursor-pointer transition-colors text-xl"
                                                                                />
                                                                                <ZoomOutOutlined
                                                                                    disabled={scale === 1}
                                                                                    onClick={onZoomOut}
                                                                                    className="text-white hover:text-blue-400 cursor-pointer transition-colors text-xl disabled:text-gray-500"
                                                                                />
                                                                                <ZoomInOutlined
                                                                                    disabled={scale === 50}
                                                                                    onClick={onZoomIn}
                                                                                    className="text-white hover:text-blue-400 cursor-pointer transition-colors text-xl disabled:text-gray-500"
                                                                                />
                                                                                <UndoOutlined
                                                                                    onClick={onReset}
                                                                                    className="text-white hover:text-blue-400 cursor-pointer transition-colors text-xl"
                                                                                />
                                                                            </Space>
                                                                        ),
                                                                        onChange: (index) => setCurrentImageIndex(index),
                                                                    }}
                                                                >
                                                                    {/* Main Large Image */}
                                                                    <Col xs={24} md={15}>
                                                                        <div className="relative overflow-hidden rounded-xl group">
                                                                            <Image
                                                                                alt={room.name}
                                                                                src={allImageUrls[0]}
                                                                                className="w-full h-[300px] object-cover transition-transform duration-700  rounded-lg"
                                                                                preview={{
                                                                                    mask: (
                                                                                        <div className="flex flex-col items-center justify-center space-y-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                                                            <ExpandOutlined className="text-white text-2xl" />
                                                                                            <span className="text-white font-medium">Xem ảnh</span>
                                                                                        </div>
                                                                                    )
                                                                                }}
                                                                                fallback="https://dam.melia.com/melia/file/iXGwjwBVnTHehdUyTT57.jpg?im=RegionOfInterestCrop=(1920,1281),regionOfInterest=(1771.5,1181.5)"
                                                                                loading="lazy"
                                                                            />

                                                                            {/* Image Count Badge */}
                                                                            {allImageUrls.length > 1 && (
                                                                                <div className="absolute bottom-3 right-3 transition-transform duration-300 group-hover:translate-y-1">
                                                                                    <Tag className="border-0 shadow-lg text-white bg-black/60 backdrop-blur-sm rounded-full px-3">
                                                                                        <PictureOutlined className="mr-2" />
                                                                                        {allImageUrls.length} ảnh
                                                                                    </Tag>
                                                                                </div>
                                                                            )}
                                                                        </div>
                                                                    </Col>

                                                                    {/* Thumbnail Grid */}
                                                                    <Col xs={24} md={9}>
                                                                        <div className="grid grid-cols-2 gap-3">
                                                                            {allImageUrls.slice(1, 7).map((imageUrl: string, index: number) => {
                                                                                const isLastThumbnail = index === 5 && allImageUrls.length > 5;
                                                                                const remainingImages = allImageUrls.length - 5;

                                                                                return (
                                                                                    <div key={index} className="relative overflow-hidden rounded-xl group">
                                                                                        <Image
                                                                                            alt={`${room.name} - Ảnh ${index + 2}`}
                                                                                            src={imageUrl}
                                                                                            className="w-full rounded-lg object-cover transition-transform duration-500 "
                                                                                            style={{ height: '155px' }}
                                                                                            fallback="https://dam.melia.com/melia/file/iXGwjwBVnTHehdUyTT57.jpg?im=RegionOfInterestCrop=(1920,1281),regionOfInterest=(1771.5,1181.5)"
                                                                                            loading="lazy"
                                                                                        />

                                                                                        {/* Hover gradient for normal images */}
                                                                                        {!isLastThumbnail && (
                                                                                            <div className="absolute inset-0  to-transparent pointer-events-none opacity-50 group-hover:opacity-0 transition-opacity duration-300"></div>
                                                                                        )}

                                                                                        {/* Lớp phủ cho hình ảnh cuối cùng hiển thị số lượng còn lại */}
                                                                                        {isLastThumbnail && remainingImages > 0 && (
                                                                                            <div className="absolute inset-0 bg-black/70 backdrop-blur-sm flex flex-col items-center justify-center cursor-pointer transition-all duration-300 hover:bg-black/70">
                                                                                                <div className="text-center text-white">
                                                                                                    <PictureOutlined className="text-xl " />
                                                                                                    <div className="text-sm">
                                                                                                        +{remainingImages}
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        )}
                                                                                    </div>
                                                                                );
                                                                            })}
                                                                        </div>
                                                                    </Col>


                                                                </Image.PreviewGroup>
                                                            );
                                                        })()}
                                                    </Row>
                                                </div>

                                                {/* Amenities */}
                                                <div className="mb-6">
                                                    <Title level={5} className="mb-3">Tiện nghi nổi bật</Title>
                                                    <AmenityDisplay
                                                        amenities={room.packageData?.highlighted_amenities || []}
                                                        maxDisplay={12}
                                                        layout="inline"
                                                        showCategories={false}
                                                    />
                                                </div>

                                                {/* Package Selection - Horizontal Layout */}
                                                <div className="mb-6">
                                                    <div className="flex items-center justify-between mb-4">
                                                        <Title level={5} className="mb-0">Chọn gói dịch vụ</Title>
                                                        <Button
                                                            type="link"
                                                            size="small"
                                                            icon={<InfoCircleOutlined />}
                                                            onClick={() => {
                                                                setSelectedRoom(room);
                                                                setCompareModalVisible(true);
                                                            }}
                                                            className="text-blue-600"
                                                        >
                                                            Xem chi tiết và so sánh gói
                                                        </Button>
                                                    </div>

                                                    <Row gutter={[16, 16]}>
                                                        {(room.options || []).slice(0, 3).map((option: any, index: number) => {
                                                            const isSelected = selectedPackages[room.id] === option.id || (!selectedPackages[room.id] && index === 0);
                                                            return (
                                                                <Col key={option.id} xs={24} md={8}>
                                                                    <div
                                                                        className={`packageCard cursor-pointer transition-all duration-300 ${isSelected ? 'packageCardSelected' : ''
                                                                            }`}
                                                                        onClick={() => handlePackageSelect(room.id, option.id)}
                                                                    >
                                                                        <div className={`relative p-4 rounded-xl border-2 h-full ${isSelected
                                                                            ? 'border-blue-500 bg-gradient-to-br from-blue-50 to-white shadow-lg'
                                                                            : 'border-gray-200 bg-white hover:border-blue-300 hover:shadow-md'
                                                                            }`}>
                                                                            {/* Selection Indicator */}
                                                                            <div className={`absolute top-3 right-3 w-5 h-5 rounded-full border-2 transition-all duration-200 ${isSelected
                                                                                ? 'border-blue-500 bg-blue-500'
                                                                                : 'border-gray-300 bg-white'
                                                                                }`}>
                                                                                {isSelected && (
                                                                                    <CheckOutlined className="text-white text-xs absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2" />
                                                                                )}
                                                                            </div>

                                                                            <div className="">
                                                                                {/* Package Header */}
                                                                                <div className="mb-3">
                                                                                    <div className="flex items-center gap-2 mb-2">
                                                                                        {option.recommended && <CrownOutlined className="text-yellow-500 text-sm" />}
                                                                                        {option.mostPopular && <ThunderboltOutlined className="text-red-500 text-sm" />}
                                                                                        <span className="font-semibold text-gray-900 text-base">{option.name}</span>
                                                                                    </div>

                                                                                    <div className="flex gap-1 mb-2">
                                                                                        {option.recommended && (
                                                                                            <Tag color="gold" className="text-xs px-2 py-0 rounded-full">
                                                                                                Đề xuất
                                                                                            </Tag>
                                                                                        )}
                                                                                        {option.mostPopular && (
                                                                                            <Tag color="red" className="text-xs px-2 py-0 rounded-full">
                                                                                                Phổ biến
                                                                                            </Tag>
                                                                                        )}
                                                                                    </div>
                                                                                </div>

                                                                                {/* Description */}
                                                                                <Text className="text-sm text-gray-600 leading-relaxed block mb-4 line-clamp-3">
                                                                                    {option.description}
                                                                                </Text>

                                                                                {/* Price Section */}
                                                                                <div className={`text-center p-3 rounded-lg ${isSelected ? 'bg-blue-50' : 'bg-gray-50'
                                                                                    }`}>
                                                                                    <div className="mb-2">
                                                                                        <div className={`text-xl font-bold ${isSelected ? 'text-blue-600' : 'text-gray-800'
                                                                                            }`}>
                                                                                            {formatVND(option.totalPrice || 0)}
                                                                                        </div>
                                                                                        <div className="text-sm text-gray-500">
                                                                                            {formatVND(option.pricePerNight?.vnd || 0)}/đêm
                                                                                        </div>
                                                                                    </div>

                                                                                    {/* Pricing breakdown from API */}
                                                                                    {option.pricing && (
                                                                                        <div className="text-xs text-gray-500 border-t pt-2">
                                                                                            <div>Phòng: {formatVND(option.pricing.final_price_per_room_per_night || 0)}/đêm</div>
                                                                                            <div>{option.pricing.nights || 1} đêm × {option.pricing.rooms_needed || 1} phòng</div>
                                                                                        </div>
                                                                                    )}
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </Col>
                                                            );
                                                        })}
                                                    </Row>
                                                </div>

                                                {/* Action Buttons */}
                                                <div className="flex gap-4">
                                                    <Button
                                                        type="default"
                                                        size="large"
                                                        onClick={() => showRoomDetails(room)}
                                                        className="flex-1"
                                                        icon={<InfoCircleOutlined />}
                                                    >
                                                        Xem chi tiết
                                                    </Button>
                                                    <Button
                                                        type="primary"
                                                        size="large"
                                                        className="gradientButton flex-1 font-semibold"
                                                        onClick={() => {
                                                            const selectedPackageId = selectedPackages[room.id] || room.options?.[0]?.id;
                                                            const selectedPackage = room.options?.find((opt: any) => opt.id === selectedPackageId);
                                                            if (selectedPackage) {
                                                                handleBookRoom(room, selectedPackage);
                                                            } else {
                                                                message.warning('Vui lòng chọn gói dịch vụ');
                                                            }
                                                        }}
                                                        icon={<ArrowRightOutlined />}
                                                    >
                                                        Đặt phòng ngay
                                                    </Button>
                                                </div>
                                            </div>
                                        </Card>
                                    ))}
                                </Space>
                            </div>
                        );
                    })}
                </Space>
            </div>

            {/* Room Details Modal - Optimized Layout */}
            <Modal
                title={
                    <div className="flex items-center gap-3">
                        <span className="text-xl font-semibold text-gray-800">{selectedRoom?.name || "Chi tiết phòng"}</span>
                        <Tag color="blue" className="px-3 py-1">
                            <StarOutlined /> {selectedRoom?.rating || 5.0}
                        </Tag>
                    </div>
                }
                open={modalVisible}
                onCancel={() => setModalVisible(false)}
                footer={[
                    <Button key="close" size="large" onClick={() => setModalVisible(false)}>
                        Đóng
                    </Button>
                ]}
                width={1400}
                className="room-details-modal"
                style={{ top: 20 }}
            >
                {selectedRoom && (
                    <Row gutter={[24, 16]}>
                        {/* Left Column - Images & Basic Info */}
                        <Col xs={24} lg={14}>
                            {/* Image Gallery - Compact */}
                            <div className="mb-4">
                                <div className="mb-2">
                                    <Title level={5} className="mb-1">Hình ảnh phòng</Title>
                                    <Text className="text-gray-500 text-xs">
                                        {selectedRoom.packageData?.images?.length || selectedRoom.images?.length || 1} ảnh có sẵn
                                    </Text>
                                </div>

                                {selectedRoom.packageData?.images?.length > 0 || selectedRoom.images?.length > 0 ? (
                                    <Carousel
                                        autoplay
                                        dots={{ className: 'custom-modal-dots' }}
                                        effect="fade"
                                    >
                                        {(selectedRoom.packageData?.images || selectedRoom.images || [selectedRoom.image]).map((img: any, index: number) => (
                                            <div key={index}>
                                                <Image
                                                    src={typeof img === 'string' ? img : img.image_url}
                                                    alt={typeof img === 'string' ? `${selectedRoom.name} - Ảnh ${index + 1}` : (img.alt_text || selectedRoom.name)}
                                                    className="w-full h-60 object-cover rounded-lg"
                                                    fallback="https://dam.melia.com/melia/file/iXGwjwBVnTHehdUyTT57.jpg?im=RegionOfInterestCrop=(1920,1281),regionOfInterest=(1771.5,1181.5)"
                                                />
                                            </div>
                                        ))}
                                    </Carousel>
                                ) : (
                                    <Image
                                        src={selectedRoom.image || "https://dam.melia.com/melia/file/iXGwjwBVnTHehdUyTT57.jpg?im=RegionOfInterestCrop=(1920,1281),regionOfInterest=(1771.5,1181.5)"}
                                        alt={selectedRoom.name}
                                        className="w-full h-60 object-cover rounded-lg"
                                    />
                                )}
                            </div>

                            {/* Room Description - Compact */}
                            <div className="mb-4">
                                <Title level={5} className="mb-2">Mô tả phòng</Title>
                                <Text className="text-gray-600 leading-relaxed text-sm">
                                    {selectedRoom.description}
                                </Text>
                            </div>

                            {/* Room Specifications - Horizontal Layout */}
                            <div className="grid grid-cols-4 gap-3 mb-4">
                                <div className="text-center p-3 bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg border border-blue-200">
                                    <div className="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <HomeOutlined className="text-white text-sm" />
                                    </div>
                                    <div className="text-lg font-bold text-blue-600">{selectedRoom.size || 35}m²</div>
                                    <div className="text-xs text-gray-600">Diện tích</div>
                                </div>
                                <div className="text-center p-3 bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg border border-orange-200">
                                    <div className="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <Bed className="text-white w-4 h-4" />
                                    </div>
                                    <div className="text-sm font-bold text-orange-600">{selectedRoom.bedType || 'Standard'}</div>
                                    <div className="text-xs text-gray-600">Loại giường</div>
                                </div>
                                <div className="text-center p-3 bg-gradient-to-br from-green-50 to-green-100 rounded-lg border border-green-200">
                                    <div className="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <TeamOutlined className="text-white text-sm" />
                                    </div>
                                    <div className="text-sm font-bold text-green-600">{selectedRoom.maxGuests || 2} khách</div>
                                    <div className="text-xs text-gray-600">Số khách tối đa</div>
                                </div>
                                <div className="text-center p-3 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-lg border border-yellow-200">
                                    <div className="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-2">
                                        <StarOutlined className="text-white text-sm" />
                                    </div>
                                    <div className="text-sm font-bold text-yellow-600">{selectedRoom.rating || 5.0}/5 ⭐</div>
                                    <div className="text-xs text-gray-600">Đánh giá</div>
                                </div>
                            </div>
                        </Col>

                        {/* Right Column - Amenities & Package Info */}
                        <Col xs={24} lg={10}>
                            {/* Amenities - Compact Tabs */}
                            <div className="mb-4">
                                <Title level={5} className="mb-3">Tiện nghi phòng</Title>
                                <Tabs
                                    defaultActiveKey="highlighted"
                                    size="small"
                                    items={[
                                        {
                                            key: 'highlighted',
                                            label: 'Nổi bật',
                                            children: (
                                                <div className="max-h-40 overflow-y-auto">
                                                    <AmenityDisplay
                                                        amenities={selectedRoom.packageData?.highlighted_amenities || []}
                                                        layout="inline"
                                                        showCategories={false}
                                                        maxDisplay={20}
                                                    />
                                                </div>
                                            )
                                        },
                                        {
                                            key: 'all',
                                            label: 'Tất cả',
                                            children: (
                                                <div className="max-h-40 overflow-y-auto">
                                                    <AmenityDisplay
                                                        amenities={selectedRoom.packageData?.amenities || []}
                                                        layout="inline"
                                                        showCategories={false}
                                                        maxDisplay={50}
                                                    />
                                                </div>
                                            )
                                        }
                                    ]}
                                />
                            </div>

                            {/* Package Summary - Compact */}
                            <div className="mb-4">
                                <Title level={5} className="mb-3">Gói dịch vụ ({selectedRoom.options?.length || 0} gói)</Title>
                                <div className="space-y-3 max-h-60 overflow-y-auto">
                                    {(selectedRoom.options || []).map((option: any, index: number) => {
                                        const isSelected = selectedPackages[selectedRoom.id] === option.id || (!selectedPackages[selectedRoom.id] && index === 0);
                                        return (
                                            <div
                                                key={option.id}
                                                className={`p-3 rounded-lg border cursor-pointer transition-all duration-200 ${isSelected
                                                    ? 'border-blue-500 bg-blue-50'
                                                    : 'border-gray-200 hover:border-blue-300'
                                                    }`}
                                                onClick={() => handlePackageSelect(selectedRoom.id, option.id)}
                                            >
                                                <div className="flex items-center justify-between mb-2">
                                                    <div className="flex items-center gap-2">
                                                        <div className={`w-4 h-4 rounded-full border-2 ${isSelected ? 'border-blue-500 bg-blue-500' : 'border-gray-300 bg-white'
                                                            }`}>
                                                            {isSelected && (
                                                                <CheckOutlined className="text-white text-xs absolute transform translate-x-0.5 -translate-y-0.5" />
                                                            )}
                                                        </div>
                                                        <span className="font-semibold text-sm">{option.name}</span>
                                                    </div>
                                                    <div className="text-right">
                                                        <div className="text-sm font-bold text-blue-600">
                                                            {formatVND(option.totalPrice || 0)}
                                                        </div>
                                                        <div className="text-xs text-gray-500">
                                                            {formatVND(option.pricePerNight?.vnd || 0)}/đêm
                                                        </div>
                                                    </div>
                                                </div>
                                                <Text className="text-xs text-gray-600 leading-relaxed line-clamp-2">
                                                    {option.description}
                                                </Text>

                                                {/* Tags */}
                                                <div className="flex gap-1 mt-2">
                                                    {option.recommended && (
                                                        <Tag color="gold" className="text-xs px-2 py-0">
                                                            Đề xuất
                                                        </Tag>
                                                    )}
                                                    {option.mostPopular && (
                                                        <Tag color="red" className="text-xs px-2 py-0">
                                                            Phổ biến
                                                        </Tag>
                                                    )}
                                                </div>
                                            </div>
                                        );
                                    })}
                                </div>
                            </div>

                            {/* Quick Actions */}
                            <div className="space-y-3">
                                <Button
                                    type="default"
                                    block
                                    size="large"
                                    onClick={() => {
                                        setModalVisible(false);
                                        setCompareModalVisible(true);
                                    }}
                                    icon={<GiftOutlined />}
                                >
                                    So sánh tất cả gói
                                </Button>
                                <Button
                                    type="primary"
                                    block
                                    size="large"
                                    className="gradientButton"
                                    onClick={() => {
                                        const selectedPackageId = selectedPackages[selectedRoom.id] || selectedRoom.options?.[0]?.id;
                                        const selectedPackage = selectedRoom.options?.find((opt: any) => opt.id === selectedPackageId);
                                        if (selectedPackage) {
                                            handleBookRoom(selectedRoom, selectedPackage);
                                        } else {
                                            message.warning('Vui lòng chọn gói dịch vụ');
                                        }
                                    }}
                                    icon={<ArrowRightOutlined />}
                                >
                                    Đặt phòng ngay - {formatVND(
                                        selectedRoom.options?.find((opt: any) =>
                                            opt.id === (selectedPackages[selectedRoom.id] || selectedRoom.options?.[0]?.id)
                                        )?.totalPrice || 0
                                    )}
                                </Button>
                            </div>
                        </Col>
                    </Row>
                )}
            </Modal>

            {/* Package Comparison Modal */}
            <Modal
                title={
                    <div className="flex items-center gap-3">
                        <GiftOutlined className="text-blue-600" />
                        <span className="text-xl font-semibold text-gray-800">So sánh gói dịch vụ - {selectedRoom?.name}</span>
                    </div>
                }
                open={compareModalVisible}
                onCancel={() => setCompareModalVisible(false)}
                footer={[
                    <Button key="close" size="large" onClick={() => setCompareModalVisible(false)}>
                        Đóng
                    </Button>
                ]}
                width={1200}
                className="package-comparison-modal"
            >
                {selectedRoom && (
                    <div>
                        {/* Package Comparison Table */}
                        <div className="mb-6">
                            <Title level={5} className="mb-4">Tất cả gói dịch vụ có sẵn</Title>
                            <Row gutter={[24, 24]}>
                                {(selectedRoom.options || []).map((option: any, index: number) => {
                                    const isSelected = selectedPackages[selectedRoom.id] === option.id || (!selectedPackages[selectedRoom.id] && index === 0);
                                    return (
                                        <Col key={option.id} xs={24} md={8}>
                                            <Card
                                                className={`h-full transition-all duration-300 cursor-pointer ${isSelected
                                                    ? 'border-blue-500 shadow-lg bg-gradient-to-br from-blue-50 to-white'
                                                    : 'border-gray-200 hover:border-blue-300 hover:shadow-md'
                                                    }`}
                                                onClick={() => handlePackageSelect(selectedRoom.id, option.id)}
                                            >
                                                <div className="relative">
                                                    {/* Selection Badge */}
                                                    {isSelected && (
                                                        <div className="absolute -top-3 -right-3 w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center shadow-lg z-10">
                                                            <CheckOutlined className="text-white text-sm" />
                                                        </div>
                                                    )}

                                                    {/* Package Header */}
                                                    <div className="mb-4">
                                                        <div className="flex items-center justify-between mb-2">
                                                            <div className="flex items-center gap-2">
                                                                {option.recommended && <CrownOutlined className="text-yellow-500" />}
                                                                {option.mostPopular && <ThunderboltOutlined className="text-red-500" />}
                                                                <span className="font-bold text-lg text-gray-900">{option.name}</span>
                                                            </div>
                                                        </div>

                                                        <div className="flex gap-2 mb-3">
                                                            {option.recommended && (
                                                                <Tag color="gold" className="px-3 py-1">
                                                                    <StarOutlined className="mr-1" /> Đề xuất
                                                                </Tag>
                                                            )}
                                                            {option.mostPopular && (
                                                                <Tag color="red" className="px-3 py-1">
                                                                    <FireOutlined className="mr-1" /> Phổ biến
                                                                </Tag>
                                                            )}
                                                        </div>
                                                    </div>

                                                    {/* Price Display */}
                                                    <div className={`text-center p-4 rounded-lg mb-4 ${isSelected ? 'bg-blue-100' : 'bg-gray-100'
                                                        }`}>
                                                        <div className="mb-2">
                                                            <div className={`text-2xl font-bold ${isSelected ? 'text-blue-600' : 'text-gray-800'
                                                                }`}>
                                                                {formatVND(option.totalPrice || 0)}
                                                            </div>
                                                            <div className="text-sm text-gray-600">
                                                                Tổng cho {searchResults?.searchSummary?.nights || 1} đêm
                                                            </div>
                                                        </div>

                                                        <Divider className="my-3" />

                                                        <div className="text-sm space-y-1">
                                                            <div className="flex justify-between">
                                                                <span className="text-gray-600">Giá mỗi đêm:</span>
                                                                <span className="font-semibold">
                                                                    {formatVND(option.pricePerNight?.vnd || 0)}
                                                                </span>
                                                            </div>

                                                            {/* Pricing breakdown from API */}
                                                            {option.pricing && (
                                                                <>
                                                                    <div className="flex justify-between">
                                                                        <span className="text-gray-600">Giá cơ bản:</span>
                                                                        <span>{formatVND(option.pricing.base_price_per_night || 0)}</span>
                                                                    </div>
                                                                    <div className="flex justify-between">
                                                                        <span className="text-gray-600">Phụ phí gói:</span>
                                                                        <span>{formatVND(option.pricing.package_modifier || 0)}</span>
                                                                    </div>
                                                                    <div className="flex justify-between">
                                                                        <span className="text-gray-600">Số đêm:</span>
                                                                        <span>{option.pricing.nights || 1} đêm</span>
                                                                    </div>
                                                                    <div className="flex justify-between">
                                                                        <span className="text-gray-600">Số phòng:</span>
                                                                        <span>{option.pricing.rooms_needed || 1} phòng</span>
                                                                    </div>
                                                                </>
                                                            )}
                                                        </div>
                                                    </div>

                                                    {/* Package Description */}
                                                    <div className="mb-4">
                                                        <Text className="text-gray-600 leading-relaxed text-sm">
                                                            {option.description}
                                                        </Text>
                                                    </div>

                                                    {/* Additional Services */}
                                                    {option.additionalServices && option.additionalServices.length > 0 && (
                                                        <div className="mb-4">
                                                            <Title level={5} className="mb-2 text-sm">Dịch vụ bổ sung</Title>
                                                            <div className="space-y-1">
                                                                {option.additionalServices.map((service: any, idx: number) => (
                                                                    <div key={idx} className="flex items-center text-sm text-gray-600">
                                                                        <CheckOutlined className="text-green-500 mr-2 text-xs" />
                                                                        {service.name || service}
                                                                    </div>
                                                                ))}
                                                            </div>
                                                        </div>
                                                    )}

                                                    {/* Package Features - Using API data instead of hardcoded values */}
                                                    <div className="space-y-2">
                                                        {/* Cancellation Policy */}
                                                        {option.cancellationPolicy && (
                                                            <div className="flex items-center text-sm">
                                                                <CheckOutlined className="text-green-500 mr-2" />
                                                                <span>{option.cancellationPolicy}</span>
                                                                {option.freeCancellationDays && (
                                                                    <span className="text-gray-500 ml-1">
                                                                        (Miễn phí {option.freeCancellationDays} ngày trước check-in)
                                                                    </span>
                                                                )}
                                                            </div>
                                                        )}

                                                        {/* Payment Policy */}
                                                        {option.paymentPolicy && (
                                                            <div className="flex items-center text-sm">
                                                                <CheckOutlined className="text-green-500 mr-2" />
                                                                <span>{option.paymentPolicy}</span>
                                                                {(option.depositPercentage || option.depositFixedAmount) && (
                                                                    <span className="text-gray-500 ml-1">
                                                                        (Đặt cọc: {option.depositPercentage ? `${option.depositPercentage}%` : ''}
                                                                        {option.depositFixedAmount ? `${formatVND(option.depositFixedAmount)}` : ''})
                                                                    </span>
                                                                )}
                                                            </div>
                                                        )}

                                                        {/* Check-out Policy */}
                                                        {option.checkOutPolicy && (
                                                            <div className="flex items-center text-sm">
                                                                <CheckOutlined className="text-green-500 mr-2" />
                                                                <span>{option.checkOutPolicy}</span>
                                                                {option.standardCheckOutTime && (
                                                                    <span className="text-gray-500 ml-1">
                                                                        (Trả phòng: {option.standardCheckOutTime})
                                                                    </span>
                                                                )}
                                                            </div>
                                                        )}

                                                        {/* Always show instant confirmation */}
                                                        <div className="flex items-center text-sm">
                                                            <CheckOutlined className="text-green-500 mr-2" />
                                                            <span>Xác nhận ngay lập tức</span>
                                                        </div>
                                                    </div>

                                                    {/* Select Button */}
                                                    <div className="mt-4">
                                                        <Button
                                                            type={isSelected ? "primary" : "default"}
                                                            block
                                                            size="large"
                                                            className={isSelected ? "gradientButton" : ""}
                                                        >
                                                            {isSelected ? 'Đã chọn' : 'Chọn gói này'}
                                                        </Button>
                                                    </div>
                                                </div>
                                            </Card>
                                        </Col>
                                    );
                                })}
                            </Row>
                        </div>

                        {/* Summary */}
                        <div className="border-t pt-4">
                            <div className="flex items-center justify-between">
                                <div>
                                    <span className="text-gray-600">Gói đã chọn: </span>
                                    <span className="font-semibold">
                                        {selectedRoom.options?.find((opt: any) =>
                                            opt.id === (selectedPackages[selectedRoom.id] || selectedRoom.options?.[0]?.id)
                                        )?.name || 'Chưa chọn'}
                                    </span>
                                </div>
                                <div className="text-right">
                                    <div className="text-lg font-bold text-blue-600">
                                        Tổng: {formatVND(
                                            selectedRoom.options?.find((opt: any) =>
                                                opt.id === (selectedPackages[selectedRoom.id] || selectedRoom.options?.[0]?.id)
                                            )?.totalPrice || 0
                                        )}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </Modal>
        </div>
    );
};

export default SearchResults;
