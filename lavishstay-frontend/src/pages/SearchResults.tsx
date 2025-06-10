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
    Image,
    Skeleton,
    Empty,
    Modal,
    Carousel,
    Divider,
    Drawer,
    notification
} from 'antd';
import {
    LeftOutlined,
    RightOutlined,
    StarFilled,
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
import dayjs from 'dayjs';

// Import new components
import BookingSummary from '../components/search/BookingSummary';
import RoomTypeSection from '../components/search/RoomTypeSection';
import AnchorNavigation from '../components/search/AnchorNavigation';
import BookingFloatButton from '../components/search/BookingFloatButton';
import { showAddRoomNotification } from '../components/search/NotificationSystem';



const { Title, Text } = Typography;

const SearchResults: React.FC = () => {
    const navigate = useNavigate(); const dispatch = useDispatch();
    const searchData = useSelector((state: RootState) => state.search);
    const bookingState = useSelector(selectBookingState);
    const selectedRoomsCount = useSelector(selectSelectedRoomsCount);

    const [loading, setLoading] = useState(true);
    const [rooms, setRooms] = useState<Room[]>([]);
    const [isRoomDetailModalVisible, setIsRoomDetailModalVisible] = useState(false);
    const [selectedRoomDetail, setSelectedRoomDetail] = useState<Room | null>(null);

    // Drawer state for booking summary
    const [isBookingDrawerVisible, setIsBookingDrawerVisible] = useState(false);

    // Notification API
    const [api, contextHolder] = notification.useNotification();

    // Calculate number of nights from search data
    const getNights = () => {
        return calculateNightsFromRange(searchData.dateRange) || 1;
    };    // Format VND currency
    const formatVND = (price: number) => {
        return new Intl.NumberFormat("vi-VN", {
            style: "currency",
            currency: "VND",
        }).format(price);
    };    // Check if room suggestion should be shown - now always shows all rooms
    const shouldShowSuggestion = () => {
        // Always return true to show all rooms regardless of guest count
        // Capacity warnings will be handled by the room options themselves
        return true;
    };    // Handle room quantity change with notification
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
    };// Handle room detail modal
    const showRoomDetail = (room: Room) => {
        setSelectedRoomDetail(room);
        setIsRoomDetailModalVisible(true);
    };

    // Handle image gallery - simplified to just show room detail
    const showImageGallery = (room: Room) => {
        showRoomDetail(room);
    };// Helper function to get display name for room type
    const getRoomTypeDisplayName = (roomType: string) => {
        const typeMap: { [key: string]: string } = {
            'deluxe': 'Deluxe',
            'premium': 'Premium',
            'suite': 'Suite',
            'theLevel': 'The Level Premium',
            'theLevelSuite': 'The Level Suite',
            'theLevelCorner': 'The Level Corner',
            'corner': 'Corner Suite',
            'presidential': 'Presidential Suite'
        };
        return typeMap[roomType] || roomType.charAt(0).toUpperCase() + roomType.slice(1);
    };    // Get total selected items count
    const getTotalSelectedItems = () => {
        return selectedRoomsCount;
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

                // Apply dynamic pricing to each room
                const roomsWithDynamicPricing = results.rooms.map(room => {
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
                }); setRooms(roomsWithDynamicPricing);

                // Update Redux store with rooms data
                dispatch(setRoomsData(roomsWithDynamicPricing.map(room => ({
                    id: room.id, // Keep as number, not string
                    name: room.name,
                    image: room.image || '', // Main image
                    images: room.images || [], // All images array
                    size: room.size,
                    view: room.view,
                    bedType: typeof room.bedType === 'string' ? room.bedType : room.bedType?.default,
                    amenities: room.amenities || [],
                    mainAmenities: room.mainAmenities || [],
                    roomType: room.roomType,
                    rating: room.rating,
                    maxGuests: room.maxGuests,
                    availableRooms: room.availableRooms,
                    options: room.options.map(option => ({
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
    }, [searchData]);

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
    } return (
        <div className="min-h-screen">
            {contextHolder}

            {/* Search Form Header */}
            <div className="shadow-sm border-b">
                <div className="max-w-7xl mx-auto px-4 py-6">
                    <div className="mb-4">                        <Title level={2} className="mb-2">
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
            </div>            {/* Main Content Layout */}
            <div className="relative">
                {/* Room Cards Container - Full Width */}
                <div className="max-w-7xl mx-auto px-4 py-8">                    <Space direction="vertical" size="large" className="w-full">
                    {/* Group rooms by type and create sections */}
                    {Array.from(new Set(rooms.map(room => room.roomType))).map((roomType) => {
                        const roomsOfType = rooms.filter(room => room.roomType === roomType);
                        return (<RoomTypeSection
                            key={roomType}
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
                        );
                    })}
                </Space>
                </div>                {/* Sticky Anchor Navigation - Outside Container */}
                <AnchorNavigation
                    rooms={rooms}
                    getRoomTypeDisplayName={getRoomTypeDisplayName}
                />
            </div>            {/* Floating Action Button for Booking Summary */}
            <BookingFloatButton
                totalItems={getTotalSelectedItems()}
                onClick={() => setIsBookingDrawerVisible(true)}
            />            {/* Booking Summary Drawer */}
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
            >                <BookingSummary
                    formatVND={formatVND}
                    getNights={getNights}
                />
            </Drawer>



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
        </div >
    );
};

export default SearchResults;
