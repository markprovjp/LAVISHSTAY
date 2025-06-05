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
    Affix,
    Modal,
    Carousel,
    Divider
} from 'antd';
import {
    LeftOutlined,
    RightOutlined,
    StarFilled
} from '@ant-design/icons';
import { useSelector } from 'react-redux';
import { RootState } from '../store';
import { Room } from '../mirage/models';
import { searchService } from '../services/searchService';
import SearchForm from '../components/SearchForm';
import { calculateNightsFromRange } from '../utils/helpers';
import { generateRoomOptionsWithDynamicPricing } from '../utils/dynamicPricing';
import dayjs from 'dayjs';

// Import new components
import RoomCard from '../components/search/RoomCard';
import BookingSummary from '../components/search/BookingSummary';

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

    // Calculate number of nights from search data
    const getNights = () => {
        return calculateNightsFromRange(searchData.dateRange) || 1;
    };    // Format VND currency
    const formatVND = (price: number) => {
        return new Intl.NumberFormat("vi-VN", {
            style: "currency",
            currency: "VND",
        }).format(price);
    };

    // Check if room suggestion should be shown
    const shouldShowSuggestion = (room: Room) => {
        if (!searchData.guestDetails) return false;
        const totalGuests = searchData.guestDetails.adults + searchData.guestDetails.children;
        return room.maxGuests >= totalGuests;
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

    // Handle room detail modal
    const showRoomDetail = (room: Room) => {
        setSelectedRoomDetail(room);
        setIsRoomDetailModalVisible(true);
    };    // Handle image gallery - simplified to just show room detail
    const showImageGallery = (room: Room) => {
        showRoomDetail(room);
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
            {/* Search Form Header */}
            <div className="shadow-sm border-b">
                <div className="max-w-7xl mx-auto px-4 py-6">
                    <div className="mb-4">
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
                    <div className="p-4 rounded-lg">
                        <Text strong className="block mb-3">Thay đổi tìm kiếm:</Text>
                        <SearchForm className="search-form-compact" />
                    </div>
                </div>
            </div>

            <div className="max-w-7xl mx-auto px-4 py-8">
                <Row gutter={[24, 24]}>
                    {/* Room Cards - 70% Width */}
                    <Col xs={24} lg={16}>
                        <Space direction="vertical" size="large" className="w-full">
                            {rooms.map((room) => {
                                return (
                                    <div key={room.id}>
                                        {/* Room Card Component */}                                        <RoomCard
                                            room={room}
                                            selectedRooms={selectedRooms}
                                            onQuantityChange={handleQuantityChange}
                                            onShowImageGallery={showImageGallery}
                                            shouldShowSuggestion={shouldShowSuggestion}
                                            searchData={searchData}
                                            formatVND={formatVND}
                                            getNights={getNights}
                                        />
                                    </div>
                                );
                            })}
                        </Space>
                    </Col>

                    {/* Booking Summary Sidebar - 30% Width */}
                    <Col xs={24} lg={8}>                        <Affix offsetTop={190}>
                        <BookingSummary
                            selectedRooms={selectedRooms}
                            rooms={rooms}
                            formatVND={formatVND}
                            getNights={getNights}
                            searchData={searchData}
                            onQuantityChange={handleQuantityChange}
                        />
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
        </div>
    );
};

export default SearchResults;
