import React, { useState } from 'react';
import { Card, Form, DatePicker, Select, InputNumber, Switch, Button, Row, Col, Tag, Checkbox, Space, Typography, Spin, Empty } from 'antd';
import { useSelector, useDispatch } from 'react-redux';
import { CalendarIcon, Users, Bed, Star, ShoppingCart } from 'lucide-react';
import styled from 'styled-components';
import dayjs from 'dayjs';

import { RootState } from '../../../store';
import { setDateRange, setRoomTypes, setOccupancy, setCleanedOnly, resetFilters } from '../../../store/slices/Reception';
import { useGetRoomTypes } from "../../../hooks/useRoomTypes";
import { useGetRooms } from '../../../hooks/useRooms';



const { RangePicker } = DatePicker;
const { Option } = Select;
const { Title, Text } = Typography;

// Styled Components
const FilterSection = styled.div`
  background: white;
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

const RoomImage = styled.div`
  width: 100%;
  height: 200px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  background-size: cover;
  background-position: center;
  border-radius: 8px;
  margin-bottom: 16px;
  position: relative;
  
  &::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.3) 100%);
    border-radius: 8px;
  }
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
  bottom: ${props => props.$visible ? '24px' : '-100px'};
  left: 50%;
  transform: translateX(-50%);
  background: white;
  border-radius: 16px;
  padding: 20px 32px;
  box-shadow: 0 12px 32px rgba(0, 0, 0, 0.16);
  border: 1px solid #e8e8e8;
  z-index: 1000;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  min-width: 400px;
  max-width: 90vw;
`;

const QuickActionButton = styled(Button)`
  border-radius: 20px;
  font-size: 12px;
  height: 28px;
  padding: 0 12px;
`;

const StatusTag = styled(Tag) <{ $status: string }>`
  border-radius: 16px;
  padding: 4px 12px;
  font-weight: 500;
  font-size: 12px;
`;

const ReceptionBooking: React.FC = () => {
    const dispatch = useDispatch();
    const filters = useSelector((state: RootState) => state.Reception);

    const { data: roomTypesData, isLoading: isRoomTypesLoading } = useGetRoomTypes();
    const roomTypes = roomTypesData?.data || [];

    const { data: roomsData = { data: [] }, isLoading, error } = useGetRooms(filters);
    // Lọc phòng theo loại phòng đã chọn (nếu có chọn)
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

    const getTotalPrice = () => selectedRooms.reduce((sum, room) => sum + (room.price || 0), 0);

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

    // Đặt nhanh (ví dụ, book 1 hoặc 2 đêm)
    const handleQuickBook = (room: any, nights: number) => {
        if (!isRoomSelected(room.id)) {
            setSelectedRooms(prev => [...prev, room]);
        }
        // Có thể mở modal hoặc chuyển trang đặt phòng ở đây
        // alert(`Đặt phòng ${room.name} trong ${nights} đêm`);
    };

    return (
        <div className="min-h-screen bg-gray-50 p-6">
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

                        <Col xs={24} sm={12} lg={7}>
                            <Form.Item label="Loại phòng">
                                <Select
                                    mode="multiple"
                                    placeholder="Chọn loại phòng"
                                    value={filters.roomTypes}
                                    onChange={(values) => dispatch(setRoomTypes(values))}
                                    className="w-full"
                                    size="large"
                                    suffixIcon={<Bed size={16} />}
                                >
                                    {roomTypes.map(type => (
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
                {isLoading && (
                    <div className="flex justify-center py-12">
                        <Spin size="large" />
                    </div>
                )}

                {error && (
                    <div className="text-center py-12">
                        <Text type="danger">Có lỗi xảy ra</Text>
                    </div>
                )}

                {!isLoading && !error && rooms.length === 0 && (
                    <Empty
                        description="Không tìm thấy phòng phù hợp"
                        className="py-12"
                    />
                )}

                {!isLoading && !error && rooms.length > 0 && (
                    <Row gutter={[24, 24]}>
                        {rooms.map((room: any) => {
                            const roomType = room.room_type || {};
                            const mainImage = room.image || '/images/room-default.jpg';
                            const price = room.base_price_vnd;
                            const maxOccupancy = room.max_guests;
                            return (
                                <Col xs={24} sm={12} lg={8} xl={6} key={room.id}>
                                    <RoomCard $isSelected={isRoomSelected(room.id)}>
                                        <div className="relative">
                                            <Checkbox
                                                checked={isRoomSelected(room.id)}
                                                onChange={(e) => handleRoomSelection(room, e.target.checked)}
                                                className="absolute top-3 left-3 z-10 bg-white bg-opacity-80 p-1 rounded"
                                            />
                                            <RoomImage style={{ backgroundImage: `url(${mainImage})` }} />
                                        </div>

                                        <div className="space-y-3">
                                            <div className="flex justify-between items-start">
                                                <div>
                                                    <Title level={4} className="mb-1">
                                                        {room.name}
                                                    </Title>
                                                    <Text className="text-gray-600">
                                                        {roomType.name || 'Loại phòng'}
                                                    </Text>
                                                </div>
                                                <div className="text-right">
                                                    <PriceTag>
                                                        {price ? new Intl.NumberFormat('vi-VN').format(price) : 'Liên hệ'}
                                                        <span className="text-xs opacity-80">/đêm</span>
                                                    </PriceTag>
                                                </div>
                                            </div>

                                            <div className="flex flex-wrap gap-2">
                                                <StatusTag $status={room.isClean ? 'clean' : 'dirty'} {...getStatusTagProps(room)} />
                                                <Tag icon={<Users size={12} />} color="blue">
                                                    Tối đa {maxOccupancy || '?'} khách
                                                </Tag>
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
                )}
            </div>

            {/* Floating Booking  */}
            <BookingCart $visible={selectedRooms.length > 0}>
                <div className="flex items-center justify-between">
                    <div className="flex items-center gap-4">
                        <div className="bg-blue-100 p-2 rounded-full">
                            <ShoppingCart size={20} className="text-blue-600" />
                        </div>
                        <div>
                            <Title level={5} className="mb-0">
                                {selectedRooms.length} phòng đã chọn
                            </Title>
                            <Text className="text-gray-600">
                                Tổng tiền: <strong>{new Intl.NumberFormat('vi-VN').format(getTotalPrice())} VNĐ</strong>
                            </Text>
                        </div>
                    </div>

                    <Space>
                        <Button onClick={clearCart}>
                            Xóa chọn
                        </Button>
                        <Button type="primary" size="large" className="rounded-lg">
                            Đặt phòng ngay
                        </Button>
                    </Space>
                </div>

                {selectedRooms.length > 0 && (
                    <div className="mt-3 pt-3 border-t border-gray-200">
                        <div className="flex flex-wrap gap-2">
                            {selectedRooms.map((room) => (
                                <Tag
                                    key={room.id}
                                    closable
                                    onClose={() => removeRoom(room.id)}
                                    className="mb-1"
                                >
                                    Phòng {room.name} - {new Intl.NumberFormat('vi-VN').format(room.base_price_vnd)} VNĐ
                                </Tag>
                            ))}
                        </div>
                    </div>
                )}
            </BookingCart>
        </div>
    );
};

export default ReceptionBooking;