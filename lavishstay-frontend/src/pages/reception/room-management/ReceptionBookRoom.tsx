import React, { useState } from 'react';
import { Card, Form, DatePicker, Select, InputNumber, Switch, Button, Row, Col, Tag, Checkbox, Space, Typography, Table, Tooltip } from 'antd';
import { useSelector, useDispatch } from 'react-redux';
import { CalendarIcon, Users, Bed, ShoppingCart, Home } from 'lucide-react';
import styled from 'styled-components';
import dayjs from 'dayjs';
import { useNavigate } from 'react-router-dom';

import { RootState } from '../../../store';
import { setDateRange, setRoomTypes, setOccupancy, setCleanedOnly, resetFilters } from '../../../store/slices/Reception';
import * as ReceptionActions from '../../../store/slices/Reception';
import { useGetRoomTypes } from "../../../hooks/useRoomTypes";
import { useGetRooms } from '../../../hooks/useRooms';

const { RangePicker } = DatePicker;
const { Option } = Select;
const { Title, Text } = Typography;

// Styled Components
const FilterSection = styled.div`
  border-radius: 12px;
  padding: 24px;
  margin-bottom: 24px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
  border: 1px solid #f0f0f0;
`;

const BookingCart = styled.div<{ $visible: boolean }>`
  position: fixed;
  bottom: ${props => props.$visible ? '16px' : '-100px'};
  left: 8px;
  right: 8px;
  transform: none;
  background: white;
  border-radius: 12px;
  padding: 16px;
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.16);
  z-index: 1000;
`;

const ReceptionBooking: React.FC = () => {
    const dispatch = useDispatch();
    const navigate = useNavigate();
    const filters = useSelector((state: RootState) => state.Reception);

    const { data: roomTypesData, isLoading: isRoomTypesLoading } = useGetRoomTypes();
    const roomTypes = roomTypesData?.data || [];

    const { data: roomsData = { data: [] }, isLoading } = useGetRooms({ 
        ...filters, 
        include: 'room_type' // Bỏ amenities và images để tăng tốc
    });
    const roomsRaw = roomsData.data;
    const rooms = filters.roomTypes && filters.roomTypes.length > 0
        ? roomsRaw.filter((room: any) => filters.roomTypes.includes(room.room_type?.id))
        : roomsRaw;

    const [selectedRooms, setSelectedRooms] = useState<any[]>([]);
    const [currentPage, setCurrentPage] = useState(1);
    const [pageSize, setPageSize] = useState(20);

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

    const handleQuickBook = (room: any) => {
        if (!isRoomSelected(room.id)) {
            setSelectedRooms(prev => [...prev, room]);
        }
    };

    // Table columns configuration
    const columns = [
        {
            title: 'Chọn',
            dataIndex: 'id',
            key: 'select',
            width: 60,
            render: (_: any, room: any) => (
                <Checkbox
                    checked={isRoomSelected(room.id)}
                    onChange={(e) => handleRoomSelection(room, e.target.checked)}
                />
            ),
        },
        {
            title: 'Số phòng',
            dataIndex: 'name',
            key: 'name',
            width: 100,
            render: (text: string) => (
                <div className="flex items-center gap-2">
                    <Home size={16} className="text-blue-500" />
                    <span className="font-medium">{text}</span>
                </div>
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
                return (
                    <div className="text-right">
                        <div className="font-semibold text-blue-600">
                            {price ? new Intl.NumberFormat('vi-VN').format(Number(price)) + ' VNĐ' : 'Liên hệ'}
                        </div>
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
            width: 120,
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
                    </div>
                );
            }
        },
        {
            title: 'Thao tác',
            key: 'actions',
            width: 150,
            render: (_: any, room: any) => (
                <Space size="small">
                    <Button
                        type="primary"
                        size="small"
                        onClick={() => handleRoomSelection(room, !isRoomSelected(room.id))}
                        className="rounded"
                    >
                        {isRoomSelected(room.id) ? 'Bỏ chọn' : 'Chọn phòng'}
                    </Button>
                    {!isRoomSelected(room.id) && (
                        <Tooltip title="Thêm nhanh vào giỏ">
                            <Button
                                size="small"
                                icon={<ShoppingCart size={14} />}
                                onClick={() => handleQuickBook(room)}
                            />
                        </Tooltip>
                    )}
                </Space>
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
        <div className="min-h-screen  p-6">
            {/* Filter Section */}
            <FilterSection>
                <Title level={3} className="mb-6 text-gray-800">
                    Đặt phòng khách sạn
                </Title>
                <Form layout="vertical">
                    <Row gutter={[24, 16]} align="bottom">
                        <Col xs={24} sm={12} lg={6}>
                            <Form.Item label="Ngày nhận - trả phòng">
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
                                    suffixIcon={<CalendarIcon size={16} />}
                                />
                            </Form.Item>
                        </Col>

                        <Col xs={24} sm={12} lg={8}>
                            <Form.Item label="Loại phòng">
                                <Select
                                    mode="multiple"
                                    placeholder="Chọn loại phòng"
                                    value={filters.roomTypes}
                                    onChange={(values) => dispatch(setRoomTypes(values))}
                                    className="w-full"
                                    size="large"
                                    suffixIcon={<Bed size={16} />}
                                    loading={isRoomTypesLoading}
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
                            <Form.Item label="Số khách">
                                <InputNumber
                                    min={1}
                                    max={10}
                                    value={filters.occupancy}
                                    onChange={(value) => dispatch(setOccupancy(value || 1))}
                                    className="w-full"
                                    size="large"
                                    prefix={<Users size={16} />}
                                    placeholder="Số khách"
                                />
                            </Form.Item>
                        </Col>

                        <Col xs={12} sm={8} lg={4}>
                            <Form.Item>
                                <Switch
                                    checked={filters.cleanedOnly}
                                    onChange={(checked) => dispatch(setCleanedOnly(checked))}
                                    checkedChildren="Sạch"
                                    unCheckedChildren="Tất cả"
                                    className="mr-3"
                                />
                                <div className="text-sm text-gray-600 mt-1">Chỉ hiển thị phòng sạch</div>
                            </Form.Item>
                        </Col>

                        <Col xs={24} sm={8} lg={4}>
                            <Space>
                                <Button
                                    type="primary"
                                    size="large"
                                    icon={<CalendarIcon size={16} />}
                                >
                                    Tìm kiếm
                                </Button>
                                <Button
                                    size="large"
                                    onClick={() => dispatch(resetFilters())}
                                >
                                    Đặt lại
                                </Button>
                            </Space>
                        </Col>
                    </Row>
                </Form>
            </FilterSection>

            {/* Room Table */}
            <Card className="mb-8">
                <div className="flex justify-between items-center mb-4">
                    <div>
                        <Title level={4} className="mb-1">Danh sách phòng</Title>
                        <Text className="text-gray-600">
                            Tìm thấy {rooms.length} phòng
                        </Text>
                    </div>
                    {selectedRooms.length > 0 && (
                        <div className="flex items-center gap-4">
                            <Text strong className="text-blue-600">
                                Đã chọn: {selectedRooms.length} phòng
                            </Text>
                            <Button size="small" onClick={clearCart}>
                                Bỏ chọn tất cả
                            </Button>
                        </div>
                    )}
                </div>
                
                <Table
                    columns={columns}
                    dataSource={rooms}
                    rowKey="id"
                    loading={isLoading || isRoomTypesLoading}
                    pagination={{
                        current: currentPage,
                        pageSize: pageSize,
                        total: rooms.length,
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
                    rowSelection={{
                        type: 'checkbox',
                        selectedRowKeys: selectedRooms.map(room => room.id),
                        onSelect: (record, selected) => {
                            handleRoomSelection(record, selected);
                        },
                        onSelectAll: (selected, _selectedRows, changeRows) => {
                            if (selected) {
                                changeRows.forEach(roomKey => {
                                    const room = rooms.find((r: any) => r.id === roomKey);
                                    if (room && !isRoomSelected(room.id)) {
                                        handleRoomSelection(room, true);
                                    }
                                });
                            } else {
                                changeRows.forEach(roomKey => {
                                    const room = rooms.find((r: any) => r.id === roomKey);
                                    if (room && isRoomSelected(room.id)) {
                                        handleRoomSelection(room, false);
                                    }
                                });
                            }
                        },
                    }}
                />
            </Card>

            {/* Floating Booking Cart */}
            <BookingCart $visible={selectedRooms.length > 0} className="shadow-2xl border-blue-200">
                <div className="flex items-center justify-between gap-6">
                    <div className="flex items-center gap-4">
                        <div className="bg-blue-100 p-3 rounded-full border border-blue-200">
                            <ShoppingCart size={20} className="text-blue-600" />
                        </div>
                        <div>
                            <Title level={5} className="mb-0 !text-lg !font-semibold text-blue-700">
                                {selectedRooms.length} phòng đã chọn
                            </Title>
                            <Text className="text-gray-600 !text-base">
                                Tổng tiền: <strong className="text-blue-600">{new Intl.NumberFormat('vi-VN').format(getTotalPrice())} VNĐ</strong>
                            </Text>
                        </div>
                    </div>
                    <Space>
                        <Button onClick={clearCart} className="rounded-full border-gray-300">
                            Xóa tất cả
                        </Button>
                        <Button 
                            type="primary" 
                            size="large" 
                            className="rounded-full bg-blue-600 hover:bg-blue-700" 
                            onClick={handleProceedToConfirm} 
                            disabled={selectedRooms.length < 1}
                        >
                            Đặt phòng ngay
                        </Button>
                    </Space>
                </div>
                {selectedRooms.length > 0 && (
                    <div className="mt-3 pt-3 border-t border-gray-200">
                        <div className="flex flex-wrap gap-2">
                            {selectedRooms.slice(0, 5).map((room) => {
                                const roomType = getRoomTypeDetail(room);
                                return (
                                    <Tag
                                        key={room.id}
                                        closable
                                        onClose={() => removeRoom(room.id)}
                                        className="mb-1 px-3 py-1 rounded-full bg-blue-50 border-blue-200 text-blue-700 font-medium"
                                    >
                                        {room.name} - {new Intl.NumberFormat('vi-VN').format(Number(roomType.adjusted_price || 0))} VNĐ
                                    </Tag>
                                );
                            })}
                            {selectedRooms.length > 5 && (
                                <Tag className="mb-1 px-3 py-1 rounded-full bg-gray-100 text-gray-600">
                                    +{selectedRooms.length - 5} phòng khác
                                </Tag>
                            )}
                        </div>
                    </div>
                )}
            </BookingCart>
        </div>
    );
};

export default ReceptionBooking;
