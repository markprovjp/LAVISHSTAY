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
    Drawer,
    notification
} from 'antd';
import {
    ShoppingCartOutlined
} from '@ant-design/icons';
import { useSelector, useDispatch } from 'react-redux';
import { RootState } from '../store';
import {
    setRoomsData,
    updateRoomSelection,
    selectBookingState,
    selectSelectedRoomsCount
} from '../store/slices/bookingSlice';
import { Room } from '../mirage/models';
import { searchService } from '../services/searchService';
import SearchForm from '../components/SearchForm';
import { calculateNightsFromRange } from '../utils/helpers';
import { generateRoomOptionsWithDynamicPricing } from '../utils/dynamicPricing';
import { calculateRoomAllocation, type GuestDetails } from '../utils/roomAllocation';
import dayjs from 'dayjs';

// Import new components
import BookingSummary from '../components/search/BookingSummary';
import RoomTypeSection from '../components/search/RoomTypeSection';
import BookingFloatButton from '../components/search/BookingFloatButton';
import AnchorNavigation from '../components/search/AnchorNavigation';
import ImageGalleryModal from '../components/search/ImageGalleryModal';
import RoomDetailModal from '../components/search/RoomDetailModal';
import { showAddRoomNotification } from '../components/search/NotificationSystem';

const { Title, Text } = Typography;

const SearchResults: React.FC = () => {
    const navigate = useNavigate();
    const dispatch = useDispatch();
    const searchData = useSelector((state: RootState) => state.search);
    const bookingState = useSelector(selectBookingState);
    const selectedRoomsCount = useSelector(selectSelectedRoomsCount);

    const [loading, setLoading] = useState(true);
    const [rooms, setRooms] = useState<Room[]>([]);
    const [isBookingDrawerVisible, setIsBookingDrawerVisible] = useState(false);

    // Modal states
    const [imageGalleryState, setImageGalleryState] = useState({
        visible: false,
        images: [] as string[],
        currentIndex: 0,
        roomName: ''
    });
    const [roomDetailState, setRoomDetailState] = useState({
        visible: false,
        room: null as Room | null
    });

    // Notification API
    const [api, contextHolder] = notification.useNotification();

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

    // Handle room quantity change with notification
    const handleQuantityChange = (roomId: string, optionId: string, quantity: number) => {
        const prevQuantity = bookingState.selectedRooms[roomId]?.[optionId] || 0;
        const nights = getNights();
        const guestCount = (searchData.guestDetails?.adults || 0) + (searchData.guestDetails?.children || 0);

        // Update Redux store
        dispatch(updateRoomSelection({
            roomId,
            optionId,
            quantity,
            nights,
            guestCount
        }));

        // Show notification when adding rooms
        if (quantity > prevQuantity) {
            const room = rooms.find(r => r.id.toString() === roomId);
            const option = room?.options.find((opt: any) => opt.id === optionId);

            if (room && option) {
                showAddRoomNotification(api, room.name, option.name, quantity);
            }
        }
    };    // Helper function to get display name for room type
    const getRoomTypeDisplayName = (roomType: string) => {
        const typeMap: { [key: string]: string } = {
            'deluxe': 'Deluxe',
            'premium': 'Premium',
            'suite': 'Suite',
            'theLevelPremium': 'The Level Premium',
            'theLevelPremiumCorner': 'The Level Premium Corner',
            'theLevelSuite': 'The Level Suite',
            'presidential': 'Presidential Suite'
        };
        return typeMap[roomType] || roomType.charAt(0).toUpperCase() + roomType.slice(1);
    };

    // Get total selected items count
    const getTotalSelectedItems = () => {
        return selectedRoomsCount;
    };    // Check if room suggestion should be shown - now always shows all rooms
    const shouldShowSuggestion = () => {
        return true; // Luôn hiển thị tất cả phòng
    };

    // Get room allocation suggestions for smart recommendations
    const getRoomAllocationSuggestions = () => {
        if (!searchData.guestDetails) {
            return calculateRoomAllocation({ adults: 2, children: 0 });
        }
        
        const guestDetails: GuestDetails = {
            adults: searchData.guestDetails.adults || 2,
            children: searchData.guestDetails.children || 0
        };
        
        return calculateRoomAllocation(guestDetails);
    };

    // Get priority suggestion for a specific room type
    const getRoomPriority = (roomType: string) => {
        const suggestions = getRoomAllocationSuggestions();
        const suggestion = suggestions.suggestions.find(s => s.roomType === roomType);
        return suggestion?.priority || 99;
    };

    // Handle image gallery
    const showImageGallery = (room: Room) => {
        setImageGalleryState({
            visible: true,
            images: room.images || [room.image || ''],
            currentIndex: 0,
            roomName: room.name
        });
    };

    // Handle image gallery navigation
    const handleImageGalleryNext = () => {
        setImageGalleryState(prev => ({
            ...prev,
            currentIndex: (prev.currentIndex + 1) % prev.images.length
        }));
    };

    const handleImageGalleryPrevious = () => {
        setImageGalleryState(prev => ({
            ...prev,
            currentIndex: prev.currentIndex === 0 ? prev.images.length - 1 : prev.currentIndex - 1
        }));
    };

    const closeImageGallery = () => {
        setImageGalleryState(prev => ({ ...prev, visible: false }));
    };

    // Handle room detail modal (for future use)
    // const showRoomDetail = (room: Room) => {
    //     setRoomDetailState({
    //         visible: true,
    //         room: room
    //     });
    // };

    const closeRoomDetail = () => {
        setRoomDetailState({ visible: false, room: null });
    };

    const handleViewDetail = (roomId: string) => {
        navigate(`/room/${roomId}`);
    };

    const handleBookNow = () => {
        navigate('/booking');
    };

    // Fetch search results
    useEffect(() => {
        const fetchResults = async () => {
            try {
                setLoading(true);
                console.log('Fetching search results with data:', searchData);
                const results = await searchService.searchRooms(searchData);
                console.log('Search results:', results);
                console.log('Number of rooms returned:', results.rooms?.length || 0);
                console.log('Room types found:', results.rooms?.map((r: any) => r.roomType) || []);

                // Apply dynamic pricing to each room
                const roomsWithDynamicPricing = results.rooms.map((room: any) => {
                    // Safe date handling with defaults - convert to dayjs
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
                        1900000, // Base price
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
                console.log('Final rooms state:', roomsWithDynamicPricing);
                console.log('Final room types:', roomsWithDynamicPricing.map((r: any) => r.roomType));

                // Update Redux store with rooms data
                dispatch(setRoomsData(roomsWithDynamicPricing.map((room: any) => ({
                    id: room.id,
                    name: room.name,
                    image: room.image || '',
                    images: room.images || [],
                    size: room.size,
                    view: room.view,
                    bedType: typeof room.bedType === 'string' ? room.bedType : room.bedType?.default,
                    amenities: room.amenities || [],
                    mainAmenities: room.mainAmenities || [],
                    roomType: room.roomType,
                    rating: room.rating,
                    maxGuests: room.maxGuests,
                    options: room.options.map((option: any) => ({
                        id: option.id,
                        name: option.name,
                        pricePerNight: {
                            vnd: option.pricePerNight?.vnd || 1900000
                        },
                        maxGuests: option.maxGuests || room.maxGuests,
                        minGuests: option.minGuests || 1,
                        roomType: room.roomType,
                        cancellationPolicy: option.cancellationPolicy,
                        paymentPolicy: option.paymentPolicy,
                        availability: option.availability,
                        additionalServices: option.additionalServices,
                        promotion: option.promotion,
                        recommended: option.recommended,
                        mostPopular: option.mostPopular,
                        dynamicPricing: option.dynamicPricing
                    }))
                }))));
            } catch (error: any) {
                message.error('Có lỗi xảy ra khi tải kết quả tìm kiếm');
                console.error('Search error:', error);
            } finally {
                setLoading(false);
            }
        };

        fetchResults();
    }, [searchData, dispatch]);

    if (loading) {
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

    if (rooms.length === 0) {
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

    return (
        <div className="min-h-screen">
            {contextHolder}

            {/* Search Form Header */}
            <div className="shadow-sm border-b">
                <div className="max-w-7xl mx-auto px-4 py-6">
                    <div className="mb-4">
                        <Title level={2} className="mb-2">
                            Kết quả tìm kiếm phòng ({rooms.length} phòng)
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
                    <div className="p-4 rounded-lg">
                        <Text strong className="block mb-3">Thay đổi tìm kiếm:</Text>
                        <SearchForm className="search-form-compact" />
                    </div>
                </div>
            </div>

            {/* Main Content Layout */}
            <div className="relative">
                {/* Anchor Navigation - fixed position cho desktop */}
                {rooms.length > 0 && (
                    <AnchorNavigation
                        rooms={rooms}
                        getRoomTypeDisplayName={getRoomTypeDisplayName}
                    />
                )}

                {/* Room Cards Container - Full Width */}
                <div className="max-w-7xl mx-auto px-4 py-8">                    <Space direction="vertical" size="large" className="w-full">                        {/* Room Allocation Summary */}
                        {searchData.guestDetails && (
                            <Card className="bg-blue-50 border-blue-200">
                                <div className="text-center">
                                    <Title level={4} className="mb-2 text-blue-800">
                                        🎯 Gợi ý phòng cho {getRoomAllocationSuggestions().totalGuests} khách
                                    </Title>
                                    <div className="space-y-2">
                                        {getRoomAllocationSuggestions().suggestions
                                            .filter(s => s.isRecommended)
                                            .slice(0, 3)
                                            .map((suggestion, index) => (
                                            <Tag 
                                                key={suggestion.roomType}
                                                color={index === 0 ? 'gold' : index === 1 ? 'blue' : 'green'}
                                                className="mx-1 mb-2"
                                            >
                                                {index === 0 && '⭐ '}
                                                {getRoomTypeDisplayName(suggestion.roomType)}: {suggestion.reason}
                                            </Tag>
                                        ))}
                                    </div>
                                    {getRoomAllocationSuggestions().notes.length > 0 && (
                                        <div className="mt-3 text-sm text-blue-600 space-y-1">
                                            {getRoomAllocationSuggestions().notes.slice(0, 2).map((note, index) => (
                                                <div key={index}>💡 {note}</div>
                                            ))}
                                        </div>
                                    )}
                                    <div className="mt-2 text-xs text-gray-500">
                                        Tối thiểu cần {getRoomAllocationSuggestions().minimumRoomsNeeded} phòng cho {getRoomAllocationSuggestions().totalAdults} người lớn
                                    </div>
                                </div>
                            </Card>
                        )}                        {/* Group rooms by type and create sections */}
                        {Array.from(new Set(rooms.map(room => room.roomType)))
                            .sort((a, b) => getRoomPriority(a) - getRoomPriority(b)) // Sort by priority
                            .map((roomType) => {
                            const roomsOfType = rooms.filter(room => room.roomType === roomType);
                            const priority = getRoomPriority(roomType);
                            const suggestion = getRoomAllocationSuggestions().suggestions.find(s => s.roomType === roomType);
                            const isRecommended = suggestion?.isRecommended || false;
                            
                            return (
                                <div key={roomType} className={isRecommended ? 'relative' : ''}>
                                    {isRecommended && (
                                        <div className="absolute -top-2 -right-2 z-10">
                                            <Tag color="gold" className="shadow-lg">
                                                {priority === 1 ? '⭐ Khuyến nghị' : '👍 Phù hợp'}
                                            </Tag>
                                        </div>
                                    )}
                                    <RoomTypeSection
                                        roomType={roomType}
                                        rooms={roomsOfType}
                                        onQuantityChange={handleQuantityChange}
                                        onShowImageGallery={showImageGallery}
                                        shouldShowSuggestion={shouldShowSuggestion}
                                        searchData={searchData}
                                        formatVND={formatVND}
                                        getNights={getNights}
                                        getRoomTypeDisplayName={getRoomTypeDisplayName}
                                    />
                                </div>
                            );
                        })}
                    </Space>
                </div>
            </div>



            {/* Booking Summary Drawer */}
            <Drawer
                title={
                    <div className="flex items-center gap-3 py-2">
                        <div className="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                            <ShoppingCartOutlined className="text-white text-lg" />
                        </div>
                        <div>
                            <div className="font-bold text-lg text-gray-800">
                                Tóm tắt đặt phòng
                            </div>
                            <div className="text-xs text-gray-500">
                                {getTotalSelectedItems()} phòng đã chọn
                            </div>
                        </div>
                        <Tag color="blue" className="ml-auto">
                            {getTotalSelectedItems()}
                        </Tag>
                    </div>
                }
                placement="right"
                width={480}
                open={isBookingDrawerVisible}
                onClose={() => setIsBookingDrawerVisible(false)}
                bodyStyle={{
                    padding: '16px',
                    backgroundColor: '#fafafa'
                }}
                headerStyle={{
                    background: 'white',
                    borderBottom: '1px solid #e8e8e8',
                    padding: '16px 24px'
                }}
            >            <BookingSummary
                    formatVND={formatVND}
                    getNights={getNights}
                />
            </Drawer>

            {/* Booking Float Button - hiển thị khi có phòng được chọn */}
            {getTotalSelectedItems() > 0 && (
                <BookingFloatButton
                    totalItems={getTotalSelectedItems()}
                    onClick={() => setIsBookingDrawerVisible(true)}
                />
            )}

            {/* Image Gallery Modal */}
            <ImageGalleryModal
                visible={imageGalleryState.visible}
                images={imageGalleryState.images}
                currentIndex={imageGalleryState.currentIndex}
                roomName={imageGalleryState.roomName}
                onClose={closeImageGallery}
                onNext={handleImageGalleryNext}
                onPrevious={handleImageGalleryPrevious}
            />

            {/* Room Detail Modal */}
            <RoomDetailModal
                visible={roomDetailState.visible}
                room={roomDetailState.room}
                onClose={closeRoomDetail}
                onViewDetail={handleViewDetail}
                onBookNow={handleBookNow}
                formatVND={formatVND}
            />
        </div>
    );
};

export default SearchResults;
