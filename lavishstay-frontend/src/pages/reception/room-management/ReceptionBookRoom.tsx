import React, { useState } from 'react';
import { Card, Form, DatePicker, Select, InputNumber, Switch, Button, Row, Col, Tag, Checkbox, Space, Typography, Spin, Empty, Skeleton } from 'antd';
import { useSelector, useDispatch } from 'react-redux';
import { CalendarIcon, Users, Bed, Star, ShoppingCart } from 'lucide-react';
import styled from 'styled-components';
import dayjs from 'dayjs';
import { useNavigate } from 'react-router-dom';

import { RootState } from '../../../store';
import { setDateRange, setRoomTypes, setOccupancy, setCleanedOnly, resetFilters } from '../../../store/slices/Reception';
import * as ReceptionActions from '../../../store/slices/Reception';
import { useGetRoomTypes } from "../../../hooks/useRoomTypes";
import { useGetRooms } from '../../../hooks/useRooms';
import { getIcon } from '../../../constants/Icons';

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

const RoomCard = styled(Card) <{ $isSelected: boolean }>`
  height: 100%;
  border-radius: 12px;
  overflow: hidden;
  transition: all 0.3s ease;
  border: 2px solid ${props => props.$isSelected ? '#1890ff' : 'transparent'};
  
  &:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
  }
  
  .ant-card-body {
    padding: 20px;
  }
`;

// Sửa RoomImage: tăng height, dùng img, object-fit cover
const RoomImage = styled.img`
  width: 100%;
  height: 260px;
  object-fit: cover;
  border-radius: 8px;
  margin-bottom: 16px;
`;

const PriceTag = styled.div`
  background: #1890ff;
  color: white;
  padding: 8px 16px;
  border-radius: 20px;
  font-weight: 600;
  font-size: 16px;
  display: inline-flex;
  align-items: center;
  gap: 4px;
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

const QuickActionButton = styled(Button)`

`;

const StatusTag = styled(Tag) <{ $status: string }>`
  border-radius: 16px;
  padding: 4px 12px;
  font-weight: 500;
  font-size: 12px;
`;

const SkeletonWrapper = styled.div`
  .ant-skeleton-image {
  }
`;

const ReceptionBooking: React.FC = () => {
    const dispatch = useDispatch();
    const navigate = useNavigate();
    const filters = useSelector((state: RootState) => state.Reception);

    const { data: roomTypesData, isLoading: isRoomTypesLoading } = useGetRoomTypes();
    const roomTypes = roomTypesData?.data || [];

    const { data: roomsData = { data: [] }, isLoading, error } = useGetRooms({ ...filters, include: 'room_type.amenities,room_type.images' });
    const roomsRaw = roomsData.data;
    const rooms = filters.roomTypes && filters.roomTypes.length > 0
        ? roomsRaw.filter((room: any) => filters.roomTypes.includes(room.room_type?.id))
        : roomsRaw;

    const [selectedRooms, setSelectedRooms] = useState<any[]>([]);

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

    // Sửa lại hàm tính tổng tiền
    const getTotalPrice = React.useCallback(() =>
        selectedRooms.reduce((sum, room) => {
            const roomType = getRoomTypeDetail(room);
            return sum + Number(roomType.adjusted_price || 0);
        }, 0),
        [selectedRooms, getRoomTypeDetail]
    );

    const getStatusTagProps = (room: any) => {
        if (room.isClean) {
            return { color: 'success', children: 'Sạch' };
        } else {
            return { color: 'warning', children: 'Cần dọn' };
        }
    };

    const getPromotionColor = (color: string) => {
        const colorMap: Record<string, string> = {
            purple: '#722ed1',
            orange: '#fa8c16',
            gold: '#faad14'
        };
        return colorMap[color] || '#1890ff';
    };

    const handleQuickBook = (room: any, nights: number) => {
        if (!isRoomSelected(room.id)) {
            setSelectedRooms(prev => [...prev, room]);
        }
    };

    const handleProceedToConfirm = () => {
        // Convert dateRange (dayjs) to string for Redux
        if (filters.dateRange && filters.dateRange.length === 2) {
            const dateRangeStr = filters.dateRange.map((d: dayjs.Dayjs) => d ? d.toISOString() : null);
            dispatch(ReceptionActions.setDateRange(dateRangeStr));
        }
        // Gắn thông tin ngày, số đêm, giá, room_type chi tiết vào từng phòng trước khi dispatch
        let nights = 1;
        if (filters.dateRange && filters.dateRange[0] && filters.dateRange[1]) {
            nights = dayjs(filters.dateRange[1]).diff(dayjs(filters.dateRange[0]), 'day');
            if (nights < 1) nights = 1;
        }
        const selectedRoomsWithDetails = selectedRooms.map(room => {
            const roomType = getRoomTypeDetail(room);
            const adjusted_price = Number(roomType.adjusted_price || 0);
            return {
                ...room,
                room_type: roomType, // Đảm bảo room_type là object chi tiết
                checkIn: filters.dateRange && filters.dateRange[0] ? filters.dateRange[0].toISOString() : null,
                checkOut: filters.dateRange && filters.dateRange[1] ? filters.dateRange[1].toISOString() : null,
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
                                    value={filters.dateRange}
                                    onChange={(dates) => dispatch(setDateRange(dates as [dayjs.Dayjs, dayjs.Dayjs]))}
                                    className="w-full"
                                    size="large"
                                    format="MMM DD, YYYY"
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

            {/* Room Grid */}
            <div className="mb-24">
                {(isLoading || isRoomTypesLoading) ? (
                    <Row gutter={[24, 24]}>
                        {Array.from({ length: 4 }).map((_, idx) => (
                            <Col xs={24} sm={12} lg={8} xl={6} key={idx}>
                                <Card className="h-full">
                                    <div style={{ width: '100%', height: 260, marginBottom: 16, borderRadius: 8, overflow: 'hidden' }}>
                                        <Skeleton.Image
                                            style={{
                                                width: '620%',
                                                height: 260,
                                                borderRadius: 8,
                                                marginBottom: 0
                                            }}
                                            active
                                            key={`skeleton-${idx}`}
                                        />
                                    </div>
                                    <Skeleton active paragraph={{ rows: 2 }} title={{ width: '60%' }} />
                                </Card>
                            </Col>
                        ))}
                    </Row>
                ) : error ? (
                    <Empty
                        description={
                            <Text type="danger">
                                Không thể tải danh sách phòng. Vui lòng thử lại sau.
                            </Text>
                        }
                    />
                ) : (rooms.length > 0 && roomTypes.length > 0) ? (
                    <Row gutter={[24, 24]}>
                        {rooms.map((room: any) => {
                            const roomType = getRoomTypeDetail(room);
                            // Lấy thông tin phòng (room)
                            const roomName = room.name;
                            const roomStatus = room.status;
                            // Lấy ảnh chính giống Home.tsx
                            let mainImage = roomType.main_image?.image_url;
                            if (!mainImage && Array.isArray(roomType.images) && roomType.images.length > 0) {
                                const mainImgObj = roomType.images.find((img: any) => img.is_main);
                                mainImage = mainImgObj ? mainImgObj.image_url : roomType.images[0].image_url;
                            }
                            // Giá phòng
                            const price = roomType.adjusted_price !== undefined && roomType.adjusted_price !== null
                                ? Number(roomType.adjusted_price)
                                : null;
                            // Số người tối đa
                            const maxOccupancy = roomType.max_guests !== undefined && roomType.max_guests !== null
                                ? roomType.max_guests
                                : null;
                            // Amenities nổi bật
                            const amenities = Array.isArray(roomType.highlighted_amenities) ? roomType.highlighted_amenities : [];
                            const description = roomType.description;
                            const size = roomType.size;
                            return (
                                <Col xs={24} sm={12} lg={8} xl={6} key={room.id}>
                                    <RoomCard $isSelected={isRoomSelected(room.id)}>
                                        <div className="relative">
                                            <Checkbox
                                                checked={isRoomSelected(room.id)}
                                                onChange={(e) => handleRoomSelection(room, e.target.checked)}
                                                className="absolute top-3 left-3 z-10 bg-white bg-opacity-80 p-1 rounded"
                                            />
                                            <RoomImage
                                                src={mainImage || '/fallback-room.jpg'}
                                                alt={roomType.name || room.name}
                                                loading="lazy"
                                                onError={(e) => (e.currentTarget.src = '/fallback-room.jpg')}
                                            />
                                        </div>
                                        <div className="space-y-3">
                                            <div className="flex justify-between items-start">
                                                <div>
                                                    <Title level={4} className="mb-1">
                                                        {roomName}
                                                    </Title>
                                                    <Text className="text-gray-600">
                                                        {roomType.name || 'Loại phòng'}
                                                    </Text>

                                                    <div className="flex gap-2 mt-1 text-xs text-gray-500">
                                                        {size && <span>Diện tích: {size}m²</span>}

                                                    </div>
                                                </div>
                                                <div className="text-right">
                                                    <PriceTag>
                                                        {price !== null && price > 0
                                                            ? new Intl.NumberFormat('vi-VN').format(price)
                                                            : 'Liên hệ'}
                                                    </PriceTag>
                                                </div>
                                            </div>

                                            {/* Highlighted Amenities */}
                                            {amenities.length > 0 && (
                                                <div className="flex flex-wrap gap-1 mt-1">
                                                    {amenities.map((amenity: any) => (
                                                        <Tag key={amenity.id} className="flex items-center gap-1 px-2 py-1 text-xs border-0 ">
                                                            {getIcon && getIcon(amenity.icon)}
                                                            <span>{amenity.name}</span>
                                                        </Tag>
                                                    ))}
                                                </div>
                                            )}

                                            <div className="flex flex-wrap gap-0">
                                                <StatusTag $status={roomStatus === 'available' ? 'clean' : 'dirty'} {...getStatusTagProps(room)} />
                                                {size && (
                                                    <Tag color="geekblue"  className="flex items-center gap-1 px-2 py-1 text-xs rounded-full">
                                                        Diện tích: {size}m²
                                                    </Tag>
                                                )}
                                                <div className="flex items-center gap-1 px-2 py-1 text-xs border-0 bg-blue-50 rounded-full text-blue-700 font-medium">
                                                    <Users size={14} className="inline align-middle" />
                                                    <span className="ml-1">Tối đa {maxOccupancy !== null ? maxOccupancy : '?'} khách</span>
                                                </div>
                                                {room.promotion && (
                                                    <Tag
                                                        color={getPromotionColor(room.promotion.color)}
                                                        icon={<Star size={12} />}
                                                    >
                                                        {room.promotion.name}
                                                    </Tag>
                                                )}
                                            </div>

                                            <div className="flex justify-between items-center pt-2">
                                                <Space wrap>
                                                    <QuickActionButton
                                                        size="small"
                                                        onClick={() => handleQuickBook(room, 1)}
                                                    >
                                                        Đặt 1 đêm
                                                    </QuickActionButton>
                                                    <QuickActionButton
                                                        size="small"
                                                        onClick={() => handleQuickBook(room, 2)}
                                                    >
                                                        Đặt 2 đêm
                                                    </QuickActionButton>
                                                </Space>

                                                <Button
                                                    type="primary"
                                                    size="small"
                                                    onClick={() => handleRoomSelection(room, !isRoomSelected(room.id))}
                                                    className="rounded-lg"
                                                >
                                                    {isRoomSelected(room.id) ? 'Đã chọn' : 'Thêm vào giỏ'}
                                                </Button>
                                            </div>
                                        </div>
                                    </RoomCard>
                                </Col>
                            );
                        })}
                    </Row>
                ) : (
                    <Empty description="Không có phòng phù hợp" className="py-12" />
                )}
            </div>

            {/* Floating Booking Cart */}
            <BookingCart $visible={selectedRooms.length > 0} className="shadow-2xl border-blue-200 ">
                <div className="flex items-center justify-between gap-6">
                    <div className="flex items-center gap-4">
                        <div className="bg-blue-100 p-4 rounded-full border border-blue-200">
                            <ShoppingCart size={22} className="text-blue-600" />
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
                            Xóa chọn
                        </Button>
                        <Button type="primary" size="large" className="rounded-full bg-blue-600 hover:bg-blue-700" onClick={handleProceedToConfirm} disabled={selectedRooms.length < 2}>
                            Đặt phòng ngay
                        </Button>
                    </Space>
                </div>
                {selectedRooms.length > 0 && (
                    <div className="mt-3 pt-3 border-t border-gray-200">
                        <div className="flex flex-wrap gap-2">
                            {selectedRooms.map((room) => {
                                const roomType = getRoomTypeDetail(room);
                                return (
                                    <Tag
                                        key={room.id}
                                        closable
                                        onClose={() => removeRoom(room.id)}
                                        className="mb-1 px-3 py-1 rounded-full bg-blue-50 border-blue-200 text-blue-700 font-medium"
                                    >
                                        Phòng {room.name} - {new Intl.NumberFormat('vi-VN').format(Number(roomType.adjusted_price || 0))} VNĐ
                                    </Tag>
                                );
                            })}
                        </div>
                    </div>
                )}
            </BookingCart>
        </div>
    );
};

export default ReceptionBooking;
