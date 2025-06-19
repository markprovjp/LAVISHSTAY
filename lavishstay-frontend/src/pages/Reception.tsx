import React, { useState, useEffect } from 'react';
import {
    Layout,
    Button,
    Row,
    Col,
    Space,
    Spin,
    Empty,
    Drawer,
    message,
    Alert
} from 'antd';
import { ShoppingCartOutlined, ClearOutlined, TeamOutlined } from '@ant-design/icons';
import { useDispatch, useSelector } from 'react-redux';
import dayjs from 'dayjs';

// Import types and services
import { Room } from '../mirage/models';
import { RoomOption } from '../mirage/roomoption';
import { generateRoomOptionsWithDynamicPricing } from '../utils/dynamicPricing';
import { formatVND } from '../utils/helpers';
import { calculateRoomAllocation } from '../utils/roomAllocation';

// Import Redux
import {
    updateRoomSelection,
    selectBookingState,
    setRoomsData,
    clearBookingData
} from '../store/slices/bookingSlice';

import {
    updateSearchData,
    setDateRange,
    updateGuestDetails,
    selectSearchData
} from '../store/slices/searchSlice';

// Import components
import { ReceptionHeader, SearchForm, RoomCard } from './reception/room-booking';
import BookingSummary from '../components/search/BookingSummary';
import ValidationSummary from '../components/booking/ValidationSummary';

const { Content, Sider } = Layout;

const Reception: React.FC = () => {
    const dispatch = useDispatch();

    // Redux states
    const bookingState = useSelector(selectBookingState);
    const searchData = useSelector(selectSearchData);    // Local states
    const [loading, setLoading] = useState(false);
    const [rooms, setRooms] = useState<Room[]>([]);
    const [roomsWithOptions, setRoomsWithOptions] = useState<(Room & { options: RoomOption[] })[]>([]);
    const [showBookingSummary, setShowBookingSummary] = useState(false);
    const [allocationResult, setAllocationResult] = useState<any>(null);

    // Helper functions
    const getSelectedRoomsCount = (): number => {
        return Object.values(bookingState.selectedRooms).reduce((total: number, roomOptions: any) => {
            return total + Object.values(roomOptions).reduce((sum: number, quantity: any) => sum + (quantity as number), 0);
        }, 0);
    };

    const calculateTotal = () => {
        return bookingState.totals.finalTotal || 0;
    };

    const getRoomTypeDisplayName = (roomType: string) => {
        const types: { [key: string]: string } = {
            'deluxe': 'Deluxe',
            'premium': 'Premium',
            'suite': 'Suite',
            'presidential': 'Presidential',
            'theLevel': 'The Level'
        };
        return types[roomType] || roomType;
    };

    const clearAllSelections = () => {
        dispatch(clearBookingData());
        message.success('Đã xóa tất cả lựa chọn');
    };    const handleCheckout = () => {
        if (getSelectedRoomsCount() === 0) {
            message.error('Vui lòng chọn ít nhất một phòng');
            return;
        }

        // Calculate total capacity from selected rooms
        const totalCapacity = Object.entries(bookingState.selectedRooms).reduce((total, [roomId, options]) => {
            const room = roomsWithOptions.find(r => r.id.toString() === roomId);
            if (!room) return total;
            return total + Object.values(options).reduce((sum: number, quantity: any) => {
                return sum + (room.maxGuests * (quantity as number));
            }, 0);
        }, 0);

        const totalGuests = searchData.guestDetails.adults + searchData.guestDetails.children;

        // Validate capacity
        if (totalCapacity < totalGuests) {
            const missingCapacity = totalGuests - totalCapacity;
            message.error(`Không đủ chỗ! Thiếu ${missingCapacity} chỗ. Vui lòng chọn thêm phòng.`);
            return;
        }

        // Show booking summary drawer for review before payment
        setShowBookingSummary(true);
    };

    // Load rooms from Mirage on component mount
    useEffect(() => {
        loadRoomsFromMirage();
    }, []);    // Filter and generate room options when search data or form criteria changes
    useEffect(() => {
        if (rooms.length > 0 && searchData.dateRange) {
            filterAndGenerateRoomOptions();
        }
    }, [rooms, searchData]);

    // Update allocation result whenever guest details change
    useEffect(() => {
        if (searchData.guestDetails.adults > 0 || searchData.guestDetails.children > 0) {
            const newAllocationResult = calculateRoomAllocation(
                { adults: searchData.guestDetails.adults, children: searchData.guestDetails.children }
            );
            setAllocationResult(newAllocationResult);
            console.log('Updated Allocation Result:', newAllocationResult);
        }
    }, [searchData.guestDetails]);

    const loadRoomsFromMirage = async () => {
        setLoading(true);
        try {
            const response = await fetch('/api/rooms');
            const data = await response.json();

            if (data.rooms) {
                setRooms(data.rooms);
            }
        } catch (error) {
            console.error('Error loading rooms:', error);
            message.error('Lỗi khi tải danh sách phòng');
        } finally {
            setLoading(false);
        }
    };    const filterAndGenerateRoomOptions = (filterCriteria?: any) => {
        setLoading(true);
        try {
            // Use filter criteria if provided, otherwise use Redux search data
            const criteria = filterCriteria || {
                dateRange: searchData.dateRange,
                guestDetails: searchData.guestDetails,
                budget: { min: 1000, max: 50000000 }, // Default budget
                roomType: undefined,
                specialRequests: undefined
            };

            const { dateRange, guestDetails, budget, roomType, specialRequests } = criteria;

            if (!dateRange) {
                setRoomsWithOptions([]);
                return;
            }

            const [checkIn, checkOut] = dateRange;
            const totalGuests = guestDetails.adults + guestDetails.children;
            const nights = checkOut.diff(checkIn, 'day');            // Calculate room allocation suggestions
            const allocationResult = calculateRoomAllocation(
                { adults: guestDetails.adults, children: guestDetails.children }
            );

            console.log('Reception Room Allocation Result:', allocationResult);
            setAllocationResult(allocationResult);

            // Always show ALL rooms regardless of guest count - no guest filtering
            let filtered = rooms.filter(room => {
                // Filter by room type if specified
                if (roomType && room.roomType !== roomType) {
                    return false;
                }

                // Filter by special requests (amenities, bed type, view)
                if (specialRequests && specialRequests.trim()) {
                    const requestLower = specialRequests.toLowerCase();
                    const roomAmenities = (room.amenities || []).concat(room.mainAmenities || []);
                    const allRoomText = `${room.view || ''} ${room.bedType || ''} ${roomAmenities.join(' ')}`.toLowerCase();

                    if (!allRoomText.includes(requestLower)) {
                        return false;
                    }
                }

                return true;
            });

            // Generate dynamic pricing options for each room
            const roomsWithGeneratedOptions = filtered.map(room => {
                const dynamicOptions = generateRoomOptionsWithDynamicPricing(
                    2000000, // Base price - will be overridden by actual room pricing
                    room.roomType,
                    room.maxGuests,
                    checkIn,
                    checkOut,
                    dayjs(),
                    totalGuests
                );

                // Filter options by budget
                const filteredOptions = dynamicOptions.filter(option => {
                    const finalPrice = option.dynamicPricing?.finalPrice || option.pricePerNight.vnd;
                    const totalPrice = finalPrice * nights;
                    return totalPrice >= budget.min && totalPrice <= budget.max;
                });                // Add allocation suggestions to room data
                const roomSuggestion = allocationResult.suggestions.find((s: any) => s.roomType === room.roomType);

                return {
                    ...room,
                    options: filteredOptions,
                    allocationSuggestion: roomSuggestion
                };
            }).filter(room => room.options.length > 0); // Only include rooms with available options

            console.log('Reception Generated Rooms with Options:', roomsWithGeneratedOptions.length, roomsWithGeneratedOptions);

            setRoomsWithOptions(roomsWithGeneratedOptions);

            // Update Redux store
            dispatch(setRoomsData(roomsWithGeneratedOptions.map(room => ({
                id: room.id,
                name: room.name,
                image: room.image,
                images: room.images || [],
                size: room.size,
                view: room.view,
                bedType: typeof room.bedType === 'string' ? room.bedType : room.bedType?.default,
                amenities: room.amenities || [],
                mainAmenities: room.mainAmenities || [],
                roomType: room.roomType,
                rating: room.rating,
                maxGuests: room.maxGuests,
                options: room.options
            }))));

        } catch (error) {
            console.error('Error filtering rooms:', error);
            message.error('Lỗi khi lọc phòng');
        } finally {
            setLoading(false);
        }
    };

    const handleSearch = (values: any) => {
        // Update Redux state with search criteria
        const searchCriteria = {
            dateRange: values.dateRange,
            checkIn: values.dateRange?.[0]?.format('YYYY-MM-DD'),
            checkOut: values.dateRange?.[1]?.format('YYYY-MM-DD'),            guestDetails: {
                adults: values.adults || 2,
                children: values.children || 0,
                childrenAges: values.childrenAges || []
            },
            guests: (values.adults || 2) + (values.children || 0)
        };

        // Update search state
        dispatch(updateSearchData(searchCriteria));
        if (searchCriteria.dateRange) {
            dispatch(setDateRange([
                searchCriteria.checkIn!,
                searchCriteria.checkOut!
            ]));
        }
        dispatch(updateGuestDetails(searchCriteria.guestDetails));

        // Apply filters with form values
        const filterCriteria = {
            dateRange: values.dateRange,
            guestDetails: searchCriteria.guestDetails,
            budget: {
                min: values.budgetMin || 1000000,
                max: values.budgetMax || 10000000
            },
            roomType: values.roomType,
            specialRequests: values.specialRequests
        };

        filterAndGenerateRoomOptions(filterCriteria);
        message.success('Đã cập nhật kết quả tìm kiếm');
    };

    const handleRoomOptionSelect = (roomId: string, optionId: string, quantity: number) => {
        if (!searchData.dateRange) {
            message.error('Vui lòng chọn ngày trước');
            return;
        }

        const nights = searchData.dateRange[1].diff(searchData.dateRange[0], 'day');
        const guestCount = searchData.guestDetails.adults + searchData.guestDetails.children;

        dispatch(updateRoomSelection({
            roomId: roomId,
            optionId,
            quantity,
            nights,
            guestCount
        }));

        if (quantity > 0) {
            message.success(`Đã thêm ${quantity} phòng vào giỏ hàng`);
        } else {
            message.info('Đã xóa phòng khỏi giỏ hàng');
        }
    };

    // Create search form data for compatibility
    const createSearchFormData = () => {
        if (!searchData.dateRange) {
            return {
                dateRange: [dayjs().add(1, 'day'), dayjs().add(2, 'day')],
                guests: searchData.guestDetails,
                budget: { min: 1000000, max: 10000000 }
            };
        }

        return {
            dateRange: searchData.dateRange,
            guests: searchData.guestDetails,
            budget: { min: 1000000, max: 10000000 }
        };
    };

    return (
        <Layout style={{ minHeight: '100vh', background: '#f5f5f5' }}>            {/* Sidebar - Tìm kiếm */}
            <Sider width={380} style={{ background: '#fff' }}>
                <SearchForm
                    onSearch={handleSearch}
                    allocationSummary={allocationResult ? {
                        totalGuests: allocationResult.totalGuests,
                        minimumRoomsNeeded: allocationResult.minimumRoomsNeeded,
                        notes: allocationResult.notes
                    } : undefined}
                />
            </Sider>

            <Layout>
                {/* Header với thống kê */}
                <ReceptionHeader
                    availableRoomsCount={roomsWithOptions.length}
                    selectedRoomsCount={getSelectedRoomsCount()}
                    totalAmount={calculateTotal()}
                />                {/* Content - Danh sách phòng */}
                <Content style={{ padding: '24px' }}>
                    {/* Smart Room Allocation Info */}
                    {allocationResult && allocationResult.totalGuests > 0 && (
                        <div style={{ marginBottom: '20px' }}>
                            <Alert
                                type="info"
                                showIcon
                                icon={<TeamOutlined />}
                                message={
                                    <div>
                                        <strong>Gợi ý phân bổ phòng:</strong> {allocationResult.totalGuests} khách cần tối thiểu {allocationResult.minimumRoomsNeeded} phòng
                                    </div>
                                }
                                description={
                                    allocationResult.notes.length > 0 && (
                                        <div style={{ fontSize: '13px' }}>
                                            {allocationResult.notes.slice(0, 2).map((note: string, index: number) => (
                                                <div key={index}>• {note}</div>
                                            ))}
                                        </div>
                                    )
                                }
                                style={{ borderRadius: '8px' }}
                                closable
                            />
                        </div>
                    )}

                    <div style={{ marginBottom: '20px' }}>
                        <Row justify="space-between" align="middle">
                            <Col>
                                <Space size="middle">
                                    <Button
                                        icon={<ClearOutlined />}
                                        onClick={clearAllSelections}
                                        danger
                                        style={{ borderRadius: '8px' }}
                                    >
                                        Xóa tất cả
                                    </Button>
                                </Space>
                            </Col>
                            <Col>
                                <Button
                                    type="primary"
                                    size="large"
                                    icon={<ShoppingCartOutlined />}
                                    onClick={handleCheckout}
                                    disabled={getSelectedRoomsCount() === 0}
                                    style={{
                                        borderRadius: '10px',
                                        height: '48px',
                                        border: 'none',
                                        fontWeight: 600
                                    }}
                                >
                                    Thanh toán ({getSelectedRoomsCount()} phòng)
                                </Button>
                            </Col>
                        </Row>
                    </div>

                    {loading ? (
                        <div style={{ textAlign: 'center', padding: '80px' }}>
                            <Spin size="large" />
                            <div style={{ marginTop: '16px', color: '#8c8c8c' }}>Đang tải danh sách phòng...</div>
                        </div>
                    ) : roomsWithOptions.length === 0 ? (
                        <Empty
                            description="Không tìm thấy phòng phù hợp với tiêu chí tìm kiếm"
                            image={Empty.PRESENTED_IMAGE_SIMPLE}
                        />
                    ) : (
                        <Row gutter={[16, 20]}>
                            {roomsWithOptions.map((room) => (
                                <Col span={24} key={room.id}>
                                    <RoomCard
                                        room={room}
                                        selectedRooms={bookingState.selectedRooms}
                                        searchFormData={createSearchFormData()}
                                        onRoomOptionSelect={handleRoomOptionSelect}
                                        getRoomTypeDisplayName={getRoomTypeDisplayName}
                                    />
                                </Col>
                            ))}
                        </Row>
                    )}
                </Content>
            </Layout>

            {/* Booking Summary Drawer */}            <Drawer
                title={
                    <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                        <ShoppingCartOutlined />
                        <span>Tóm tắt đặt phòng</span>
                    </div>
                }
                placement="right"
                onClose={() => setShowBookingSummary(false)}
                open={showBookingSummary}
                width={480}
                styles={{
                    body: { padding: '16px' }
                }}
            >                {/* Room Capacity Validation Summary */}
                <div style={{ marginBottom: '20px' }}>
                    <ValidationSummary
                        totalGuests={searchData.guestDetails.adults + searchData.guestDetails.children}
                        totalAdults={searchData.guestDetails.adults}
                        totalChildren={searchData.guestDetails.children}                        totalCapacity={Object.entries(bookingState.selectedRooms).reduce((total, [roomId, options]) => {
                            const room = roomsWithOptions.find(r => r.id.toString() === roomId);
                            if (!room) return total;
                            return total + Object.values(options).reduce((sum: number, quantity: any) => {
                                return sum + (room.maxGuests * (quantity as number));
                            }, 0);
                        }, 0)}
                        selectedRoomsCount={getSelectedRoomsCount()}
                    />
                </div>

                <BookingSummary
                    formatVND={formatVND}
                    getNights={() => {
                        if (!searchData.dateRange) return 1;
                        return searchData.dateRange[1].diff(searchData.dateRange[0], 'day');
                    }}
                />
            </Drawer>
        </Layout>
    );
};

export default Reception;
