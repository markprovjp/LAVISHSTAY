import React, { useState } from 'react';
import { Card, Form, DatePicker, Select, InputNumber, Button, Row, Col, Tag, Space, Typography, Table, Tooltip, Alert, Drawer, Badge } from 'antd';
import { useSelector, useDispatch } from 'react-redux';
import { CalendarIcon, Users, Bed, ShoppingCart, Home, Search, RotateCcw } from 'lucide-react';
import dayjs from 'dayjs';
import { useNavigate } from 'react-router-dom';

import { RootState } from '../../../store';
import { setDateRange, setRoomTypes, setOccupancy, resetFilters } from '../../../store/slices/Reception';
import * as ReceptionActions from '../../../store/slices/Reception';
import { useGetRoomTypes } from "../../../hooks/useRoomTypes";
import { useGetRooms } from '../../../hooks/useRooms';
import { useGetAvailableRooms } from '../../../hooks/useRoomAvailability';

const { RangePicker } = DatePicker;
const { Option } = Select;
const { Title, Text } = Typography;

const ReceptionBooking: React.FC = () => {
    const dispatch = useDispatch();
    const navigate = useNavigate();
    const filters = useSelector((state: RootState) => state.Reception);

    const { data: roomTypesData, isLoading: isRoomTypesLoading } = useGetRoomTypes();
    const roomTypes = roomTypesData?.data || [];

    // Prepare date parameters for availability API
    const availabilityParams = React.useMemo(() => {
        if (filters.dateRange && filters.dateRange.length === 2 && filters.dateRange[0] && filters.dateRange[1]) {
            const checkInDate = typeof filters.dateRange[0] === 'string'
                ? filters.dateRange[0]
                : dayjs(filters.dateRange[0]).format('YYYY-MM-DD');
            const checkOutDate = typeof filters.dateRange[1] === 'string'
                ? filters.dateRange[1]
                : dayjs(filters.dateRange[1]).format('YYYY-MM-DD');

            return {
                check_in_date: checkInDate,
                check_out_date: checkOutDate,
                enabled: true
            };
        }
        return { enabled: false };
    }, [filters.dateRange]);

    // Use room availability API when dates are selected, fallback to regular rooms API
    const { data: availableRoomsData, isLoading: isAvailableRoomsLoading } = useGetAvailableRooms(availabilityParams);

    const { data: roomsData = { data: [] }, isLoading: isRoomsLoading } = useGetRooms({
        ...filters,
        include: 'room_type' // Fallback khi chưa chọn ngày
    });

    // Determine which data to use based on availability
    const useAvailabilityData = availabilityParams.enabled && !isAvailableRoomsLoading && availableRoomsData?.success;

    const roomsToDisplay = React.useMemo(() => {
        if (useAvailabilityData && availableRoomsData?.data) {
            // Convert room types from availability API to room-like structure
            const convertedRooms: any[] = [];

            availableRoomsData.data.forEach((roomType: any) => {
                // Create a room entry for each available room of this type
                roomType.available_rooms.forEach((availableRoom: any, index: number) => {
                    convertedRooms.push({
                        id: `${roomType.room_type_id}-${availableRoom.room_id}`, // Unique ID
                        room_id: availableRoom.room_id,
                        name: availableRoom.room_name || `${roomType.room_code || roomType.name}-${String(index + 1).padStart(2, '0')}`, // Use actual room name or generate
                        bed_type_name: availableRoom.bed_type_name || 'Không xác định', // Bed type name
                        status: availableRoom.room_status || 'available',
                        isClean: true, // Assume available rooms are clean
                        room_type_id: roomType.room_type_id,
                        room_type: {
                            id: roomType.room_type_id,
                            name: roomType.name,
                            description: roomType.description,
                            base_price: roomType.base_price,
                            adjusted_price: roomType.adjusted_price,
                            size: roomType.size,
                            max_guests: roomType.max_guests,
                            rating: roomType.rating,
                            images: roomType.images,
                            main_image: roomType.main_image,
                            amenities: roomType.amenities,
                            highlighted_amenities: roomType.highlighted_amenities,
                            pricing_summary: roomType.pricing_summary
                        }
                    });
                });
            });

            return convertedRooms;
        } else {
            // Use regular rooms data
            const roomsRaw = roomsData.data || [];
            return filters.roomTypes && filters.roomTypes.length > 0
                ? roomsRaw.filter((room: any) => filters.roomTypes.includes(room.room_type?.id))
                : roomsRaw;
        }
    }, [useAvailabilityData, availableRoomsData, roomsData, filters.roomTypes]);

    // Loading state
    const isLoading = useAvailabilityData ? isAvailableRoomsLoading : isRoomsLoading;

    const [selectedRooms, setSelectedRooms] = useState<any[]>([]);
    const [currentPage, setCurrentPage] = useState(1);
    const [pageSize, setPageSize] = useState(20);
    const [cartDrawerOpen, setCartDrawerOpen] = useState(false);

    const isRoomSelected = (roomId: number) => selectedRooms.some(room => room.id === roomId);

    const handleRoomSelection = (room: any, checked: boolean) => {
        if (checked) {
            setSelectedRooms(prev => [...prev, room]);
        } else {
            setSelectedRooms(prev => prev.filter(r => r.id !== room.id));
        }
    };

    const removeRoom = (roomId: number) => {
        setSelectedRooms(prev => prev.filter(r => r.id !== roomId));
    };

    const clearCart = () => setSelectedRooms([]);

    // Helper: Lấy roomType chi tiết cho 1 room
    const getRoomTypeDetail = React.useMemo(
        () => (room: any) => roomTypes.find((rt: any) => rt.id === (room.room_type?.id || room.room_type)) || {},
        [roomTypes]
    );

    const getTotalPrice = React.useCallback(() =>
        selectedRooms.reduce((sum, room) => {
            const roomType = getRoomTypeDetail(room);
            return sum + Number(roomType.adjusted_price || 0);
        }, 0),
        [selectedRooms, getRoomTypeDetail]
    );

    // Table columns configuration - loại bỏ cột checkbox thừa
    const columns = [
        {
            title: 'Số phòng',
            dataIndex: 'name',
            key: 'name',
            width: 120,
            render: (text: string, room: any) => (
                <Tooltip title={`Phòng ${text}${room.bed_type_name ? ` - ${room.bed_type_name}` : ''}`}>
                    <div className="flex flex-col gap-1">
                        <div className="flex items-center gap-2">
                            <Home size={16} className="text-blue-500" />
                            <span className="font-medium">{text}</span>
                        </div>
                        {room.bed_type_name && (
                            <div className="flex items-center gap-1 text-xs text-gray-500">
                                <Bed size={12} className="text-gray-400" />
                                <span>{room.bed_type_name}</span>
                            </div>
                        )}
                    </div>
                </Tooltip>
            ),
        },
        {
            title: 'Loại phòng',
            dataIndex: ['room_type', 'name'],
            key: 'roomType',
            width: 150,
            render: (_: any, room: any) => {
                const roomType = getRoomTypeDetail(room);
                return (
                    <div>
                        <div className="font-medium">{roomType.name || 'N/A'}</div>
                        <div className="text-xs text-gray-500">
                            {roomType.size && `${roomType.size}m²`}
                        </div>
                    </div>
                );
            }
        },
        {
            title: 'Giá/đêm',
            dataIndex: ['room_type', 'adjusted_price'],
            key: 'price',
            width: 120,
            align: 'right' as const,
            render: (_: any, room: any) => {
                const roomType = getRoomTypeDetail(room);
                const price = roomType.adjusted_price;
                const pricingSummary = roomType.pricing_summary;

                return (
                    <div className="text-right">
                        <div className="font-semibold text-blue-600">
                            {price ? new Intl.NumberFormat('vi-VN').format(Number(price)) + ' VNĐ' : 'Liên hệ'}
                        </div>
                        {useAvailabilityData && pricingSummary && (
                            <div className="text-xs text-gray-500">
                                {pricingSummary.nights} đêm • Tổng: {new Intl.NumberFormat('vi-VN').format(pricingSummary.total_price)} VNĐ
                            </div>
                        )}
                    </div>
                );
            }
        },
        {
            title: 'Sức chứa',
            dataIndex: ['room_type', 'max_guests'],
            key: 'capacity',
            width: 100,
            align: 'center' as const,
            render: (_: any, room: any) => {
                const roomType = getRoomTypeDetail(room);
                return (
                    <div className="flex items-center justify-center gap-1">
                        <Users size={14} />
                        <span>{roomType.max_guests || '?'}</span>
                    </div>
                );
            }
        },
        {
            title: 'Trạng thái',
            dataIndex: 'status',
            key: 'status',
            width: 140,
            render: (status: string, room: any) => {
                const isClean = room.isClean;
                return (
                    <div className="flex flex-col gap-1">
                        <Tag color={status === 'available' ? 'green' : status === 'occupied' ? 'red' : 'orange'}>
                            {status === 'available' ? 'Trống' : status === 'occupied' ? 'Đã thuê' : 'Bảo trì'}
                        </Tag>
                        <Tag color={isClean ? 'blue' : 'gold'}>
                            {isClean ? 'Sạch' : 'Cần dọn'}
                        </Tag>
                        {room.room_id && (
                            <div className="text-xs text-gray-400">
                                ID: {room.room_id}
                            </div>
                        )}
                    </div>
                );
            }
        },
        {
            title: 'Thao tác',
            key: 'actions',
            width: 100,
            render: (_: any, room: any) => (
                <Tooltip title={isRoomSelected(room.id) ? "Bỏ chọn phòng" : "Thêm vào giỏ"}>
                    <Button
                        type={isRoomSelected(room.id) ? "default" : "primary"}
                        size="small"
                        icon={<ShoppingCart size={14} />}
                        onClick={() => handleRoomSelection(room, !isRoomSelected(room.id))}
                    >
                        {isRoomSelected(room.id) ? 'Bỏ chọn' : 'Chọn'}
                    </Button>
                </Tooltip>
            ),
        },
    ];

    const handleProceedToConfirm = () => {
        // Convert dateRange (dayjs) to string for Redux - chỉ khi có đủ 2 giá trị valid
        if (filters.dateRange && filters.dateRange.length === 2 && filters.dateRange[0] && filters.dateRange[1]) {
            const dateRangeStr: [string, string] = [
                typeof filters.dateRange[0] === 'string' ? filters.dateRange[0] : dayjs(filters.dateRange[0]).toISOString(),
                typeof filters.dateRange[1] === 'string' ? filters.dateRange[1] : dayjs(filters.dateRange[1]).toISOString()
            ];
            dispatch(ReceptionActions.setDateRange(dateRangeStr));
        }

        // Gắn thông tin ngày, số đêm, giá, room_type chi tiết vào từng phòng trước khi dispatch
        let nights = 1;
        if (filters.dateRange && filters.dateRange[0] && filters.dateRange[1]) {
            const startDate = typeof filters.dateRange[0] === 'string' ? dayjs(filters.dateRange[0]) : dayjs(filters.dateRange[0]);
            const endDate = typeof filters.dateRange[1] === 'string' ? dayjs(filters.dateRange[1]) : dayjs(filters.dateRange[1]);
            nights = endDate.diff(startDate, 'day');
            if (nights < 1) nights = 1;
        }
        const selectedRoomsWithDetails = selectedRooms.map(room => {
            const roomType = getRoomTypeDetail(room);
            const adjusted_price = Number(roomType.adjusted_price || 0);
            return {
                ...room,
                room_type: roomType, // Đảm bảo room_type là object chi tiết
                checkIn: filters.dateRange && filters.dateRange[0] ? (typeof filters.dateRange[0] === 'string' ? filters.dateRange[0] : dayjs(filters.dateRange[0]).toISOString()) : null,
                checkOut: filters.dateRange && filters.dateRange[1] ? (typeof filters.dateRange[1] === 'string' ? filters.dateRange[1] : dayjs(filters.dateRange[1]).toISOString()) : null,
                nights,
                adjusted_price,
                totalPrice: adjusted_price * nights
            };
        });
        dispatch(ReceptionActions.setSelectedRooms(selectedRoomsWithDetails));
        navigate('/reception/confirm-representative-payment');
    };

    return (
        <div className="min-h-screen p-6">
            {/* Filter Section */}
            <Card className="mb-6">
                <div className="flex items-center gap-2 mb-6">
                    <Search size={20} className="text-blue-500" />
                    <Title level={3} className="mb-0">
                        Tìm kiếm & Đặt phòng
                    </Title>
                </div>

                <Form layout="vertical">
                    <Row gutter={[16, 16]} align="bottom">
                        <Col xs={24} sm={12} lg={6}>
                            <Form.Item label={
                                <Space>
                                    <CalendarIcon size={16} />
                                    <span>Ngày nhận - trả phòng</span>
                                </Space>
                            }>
                                <RangePicker
                                    value={
                                        Array.isArray(filters.dateRange) && filters.dateRange.length === 2
                                            ? [
                                                filters.dateRange[0] ? dayjs(filters.dateRange[0]) : null,
                                                filters.dateRange[1] ? dayjs(filters.dateRange[1]) : null
                                            ]
                                            : [null, null]
                                    }
                                    onChange={(dates) => dispatch(setDateRange(dates as [dayjs.Dayjs, dayjs.Dayjs]))}
                                    className="w-full"
                                    size="large"
                                    format="DD/MM/YYYY"
                                    placeholder={["Ngày nhận phòng", "Ngày trả phòng"]}
                                />
                            </Form.Item>
                        </Col>

                        <Col xs={24} sm={12} lg={8}>
                            <Form.Item label={
                                <Space>
                                    <Bed size={16} />
                                    <span>Loại phòng</span>
                                </Space>
                            }>
                                <Select
                                    mode="multiple"
                                    placeholder="Chọn loại phòng"
                                    value={filters.roomTypes}
                                    onChange={(values) => dispatch(setRoomTypes(values))}
                                    className="w-full"
                                    size="large"
                                    loading={isRoomTypesLoading}
                                    maxTagCount="responsive"
                                    showSearch
                                    filterOption={(input, option) =>
                                        (option?.children as unknown as string)?.toLowerCase().includes(input.toLowerCase())
                                    }
                                >
                                    {roomTypes.map((type: any) => (
                                        <Option key={type.id} value={type.id}>
                                            {type.name}
                                        </Option>
                                    ))}
                                </Select>
                            </Form.Item>
                        </Col>

                        <Col xs={12} sm={8} lg={4}>
                            <Form.Item label={
                                <Space>
                                    <Users size={16} />
                                    <span>Số khách</span>
                                </Space>
                            }>
                                <InputNumber
                                    min={1}
                                    max={10}
                                    value={filters.occupancy}
                                    onChange={(value) => dispatch(setOccupancy(value || 1))}
                                    className="w-full"
                                    size="large"
                                    placeholder="Số khách"
                                />
                            </Form.Item>
                        </Col>

                        <Col xs={12} sm={8} lg={6}>
                            <Form.Item label=" ">
                                <Space>
                                    <Button
                                        type="primary"
                                        size="large"
                                        icon={<Search size={16} />}
                                        loading={isAvailableRoomsLoading}
                                        disabled={!availabilityParams.enabled}
                                    >
                                        {useAvailabilityData ? 'Tìm phòng trống' : 'Tìm kiếm'}
                                    </Button>
                                    <Button
                                        size="large"
                                        icon={<RotateCcw size={16} />}
                                        onClick={() => dispatch(resetFilters())}
                                    >
                                        Đặt lại
                                    </Button>
                                </Space>
                            </Form.Item>
                        </Col>
                    </Row>
                </Form>
            </Card>

            {/* Room Table */}
            <Card className="mb-8">
                {/* Show availability mode indicator */}
                {useAvailabilityData && (
                    <Alert
                        message={
                            <Space>
                                <CalendarIcon size={16} />
                                <span>
                                    Hiển thị phòng trống từ {dayjs(availabilityParams.check_in_date).format('DD/MM/YYYY')}
                                    đến {dayjs(availabilityParams.check_out_date).format('DD/MM/YYYY')}
                                </span>
                            </Space>
                        }
                        description={
                            availableRoomsData?.summary && (
                                <Text>
                                    Tổng cộng {availableRoomsData.summary.total_available_rooms} phòng trống
                                    thuộc {availableRoomsData.summary.total_room_types} loại phòng
                                </Text>
                            )
                        }
                        type="info"
                        showIcon
                        className="mb-4"
                    />
                )}

                {/* Show error if availability API fails */}
                {availabilityParams.enabled && !isAvailableRoomsLoading && availableRoomsData && !availableRoomsData.success && (
                    <Alert
                        message="Lỗi tìm kiếm phòng trống"
                        description="Không thể tìm kiếm phòng trống. Hiển thị tất cả phòng thay thế."
                        type="error"
                        showIcon
                        className="mb-4"
                    />
                )}

                {/* Show message when no dates selected */}
                {!availabilityParams.enabled && (
                    <Alert
                        message="Chưa chọn ngày"
                        description="Vui lòng chọn ngày nhận và trả phòng để xem phòng trống khả dụng"
                        type="warning"
                        showIcon
                        className="mb-4"
                    />
                )}

                <div className="flex justify-between items-center mb-4">
                    <div>
                        <Title level={4} className="mb-1">Danh sách phòng</Title>
                        <Text className="text-gray-600">
                            Tìm thấy {roomsToDisplay.length} phòng
                            {useAvailabilityData && availableRoomsData?.summary && (
                                <span className="ml-2 text-blue-600">
                                    ({availableRoomsData.summary.total_room_types} loại phòng khả dụng)
                                </span>
                            )}
                        </Text>
                    </div>
                    {selectedRooms.length > 0 && (
                        <Space>
                            <Badge count={selectedRooms.length} showZero={false}>
                                <Button
                                    type="primary"
                                    icon={<ShoppingCart size={16} />}
                                    onClick={() => setCartDrawerOpen(true)}
                                >
                                    Giỏ phòng
                                </Button>
                            </Badge>
                            <Button size="small" onClick={clearCart}>
                                Xóa tất cả
                            </Button>
                        </Space>
                    )}
                </div>

                <Table
                    columns={columns}
                    dataSource={roomsToDisplay}
                    rowKey="id"
                    loading={isLoading || isRoomTypesLoading}
                    pagination={{
                        current: currentPage,
                        pageSize: pageSize,
                        total: roomsToDisplay.length,
                        showTotal: (total, range) => `${range[0]}-${range[1]} của ${total} phòng`,
                        showSizeChanger: true,
                        showQuickJumper: true,
                        pageSizeOptions: ['10', '20', '50', '100'],
                        onChange: (page, size) => {
                            setCurrentPage(page);
                            setPageSize(size || 20);
                        },
                    }}
                    scroll={{ x: 900 }}
                    size="middle"
                />
            </Card>

            {/* Booking Cart Drawer */}
            <Drawer
                title={
                    <Space>
                        <ShoppingCart size={20} />
                        <span>Giỏ phòng ({selectedRooms.length} phòng)</span>
                    </Space>
                }
                placement="right"
                width={400}
                open={cartDrawerOpen}
                onClose={() => setCartDrawerOpen(false)}
                footer={
                    <Space className="w-full justify-between">
                        <div>
                            <Text strong>Tổng tiền: </Text>
                            <Text className="text-lg font-bold text-blue-600">
                                {new Intl.NumberFormat('vi-VN').format(getTotalPrice())} VNĐ
                            </Text>
                        </div>
                        <Space>
                            <Button onClick={clearCart}>
                                Xóa tất cả
                            </Button>
                            <Button
                                type="primary"
                                size="large"
                                onClick={handleProceedToConfirm}
                                disabled={selectedRooms.length < 1}
                            >
                                Đặt phòng ngay
                            </Button>
                        </Space>
                    </Space>
                }
            >
                {selectedRooms.length === 0 ? (
                    <div className="text-center py-8">
                        <ShoppingCart size={48} className="mx-auto text-gray-300 mb-4" />
                        <Text className="text-gray-500">Chưa có phòng nào được chọn</Text>
                    </div>
                ) : (
                    <Space direction="vertical" className="w-full" size="middle">
                        {selectedRooms.map((room) => {
                            const roomType = getRoomTypeDetail(room);
                            return (
                                <Card key={room.id} size="small" className="shadow-sm">
                                    <div className="flex justify-between items-start">
                                        <div className="flex-1">
                                            <Text strong className="text-base">{room.name}</Text>
                                            {room.bed_type_name && (
                                                <div className="flex items-center gap-1 text-xs text-gray-500 mt-1">
                                                    <Bed size={12} />
                                                    <span>{room.bed_type_name}</span>
                                                </div>
                                            )}
                                            <div className="mt-2">
                                                <Text className="text-sm text-gray-600">{roomType.name}</Text>
                                                <div className="text-blue-600 font-semibold">
                                                    {new Intl.NumberFormat('vi-VN').format(Number(roomType.adjusted_price || 0))} VNĐ/đêm
                                                </div>
                                            </div>
                                        </div>
                                        <Button
                                            type="text"
                                            size="small"
                                            onClick={() => removeRoom(room.id)}
                                            className="text-red-500 hover:text-red-700"
                                        >
                                            Xóa
                                        </Button>
                                    </div>
                                </Card>
                            );
                        })}
                    </Space>
                )}
            </Drawer>
        </div>
    );
};

export default ReceptionBooking;
