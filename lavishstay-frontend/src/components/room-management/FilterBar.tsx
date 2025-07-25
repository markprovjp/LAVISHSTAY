import React, { useState, useCallback, useEffect } from 'react';
import { Form, Select, DatePicker, Button, Space, Card, Row, Col, Badge, Flex, InputNumber, Popover, Input, Typography } from 'antd';
import { SearchOutlined, ReloadOutlined, ArrowRightOutlined, UserOutlined, PlusOutlined, MinusOutlined, DeleteOutlined, HomeOutlined } from '@ant-design/icons';
import { useRoomManagementStore } from '../../stores/roomManagementStore';
import { RoomFilters } from '../../types/room';
import dayjs from 'dayjs';

const { RangePicker } = DatePicker;
const { Option } = Select;

interface FilterBarProps {
    roomTypes: any[];
    floors?: any[]; // Add floors prop
    onSearch: (filters: RoomFilters) => void;
    loading?: boolean;
    selectedRoomsCount?: number;
    multiSelectMode?: boolean; // Keep this to receive the 'true' value
    onProceedToBooking?: () => void;
}

interface GuestRoom {
    adults: number;
    children: { age: number }[];
}

const FilterBar: React.FC<FilterBarProps> = ({
    roomTypes,
    floors = [], // Default to empty array
    onSearch,
    loading = false,
    selectedRoomsCount = 0,
    onProceedToBooking
}) => {
    const { filters, setFilters, resetFilters } = useRoomManagementStore();
    const [form] = Form.useForm();
    const [guestRooms, setGuestRooms] = useState<GuestRoom[]>(filters.guestRooms || [{ adults: 1, children: [] }]);
    const [popoverVisible, setPopoverVisible] = useState(false);

    // Sync form with external state
    useEffect(() => {
        form.setFieldsValue({
            dateRange: filters.dateRange ? [dayjs(filters.dateRange[0]), dayjs(filters.dateRange[1])] : undefined,
            roomType: filters.roomType || undefined,
            roomName: filters.roomName || undefined,
            floor: filters.floor || undefined,
        });
        if (filters.guestRooms) {
            setGuestRooms(filters.guestRooms);
        }
    }, [filters, form]);

    const handleSearch = () => {
        const values = form.getFieldsValue();
        const searchFilters: RoomFilters = {
            dateRange: values.dateRange
                ? [values.dateRange[0]?.format('YYYY-MM-DD'), values.dateRange[1]?.format('YYYY-MM-DD')]
                : undefined,
            roomType: values.roomType || undefined,
            roomName: values.roomName || undefined,
            floor: values.floor || undefined,
            guestRooms: guestRooms,
            adults: guestRooms.reduce((sum, room) => sum + room.adults, 0),
            children: guestRooms.flatMap(room => room.children),
        };
        setFilters(searchFilters);
        onSearch(searchFilters);
        setPopoverVisible(false);
    };

    const handleReset = () => {
        form.resetFields();
        setGuestRooms([{ adults: 1, children: [] }]);
        resetFilters();
        onSearch({ guestRooms: [{ adults: 1, children: [] }] });
    };

    const updateGuestRoom = (roomIndex: number, newRoomData: Partial<GuestRoom>) => {
        const newGuestRooms = [...guestRooms];
        newGuestRooms[roomIndex] = { ...newGuestRooms[roomIndex], ...newRoomData };
        setGuestRooms(newGuestRooms);
    };

    const handleAddRoom = () => {
        if (guestRooms.length < 8) { // Increased limit
            setGuestRooms([...guestRooms, { adults: 1, children: [] }]);
        }
    };

    const handleRemoveRoom = (roomIndex: number) => {
        if (guestRooms.length > 1) {
            const newGuestRooms = [...guestRooms];
            newGuestRooms.splice(roomIndex, 1);
            setGuestRooms(newGuestRooms);
        }
    };

    const formatGuestSummary = useCallback(() => {
        const totalAdults = guestRooms.reduce((sum, room) => sum + room.adults, 0);
        const totalChildren = guestRooms.reduce((sum, room) => sum + room.children.length, 0);
        return `${guestRooms.length} phòng, ${totalAdults + totalChildren} khách`;
    }, [guestRooms]);

    const guestPopoverContent = (
        <div style={{ width: 400, maxHeight: 500, overflowY: 'auto' }} className="p-2">
            <Flex justify="space-between" align="center" className="mb-3">
                <Typography.Text strong>Phòng và khách</Typography.Text>
                <Button icon={<PlusOutlined />} onClick={handleAddRoom} disabled={guestRooms.length >= 8} size="small">Thêm phòng</Button>
            </Flex>
            <Space direction="vertical" style={{ width: '100%' }}>
                {guestRooms.map((room, roomIndex) => (
                    <Card key={roomIndex} size="small" title={
                        <Flex justify="space-between" align="center">
                            <Space><HomeOutlined /><Typography.Text>Phòng {roomIndex + 1}</Typography.Text></Space>
                            {guestRooms.length > 1 && <Button type="text" danger icon={<DeleteOutlined />} onClick={() => handleRemoveRoom(roomIndex)} size="small" />}
                        </Flex>
                    }>
                        <Row align="middle" className="mb-2">
                            <Col span={10}>Người lớn</Col>
                            <Col span={14}>
                                <Space>
                                    <Button icon={<MinusOutlined />} size="small" disabled={room.adults <= 1} onClick={() => updateGuestRoom(roomIndex, { adults: room.adults - 1 })} />
                                    <span>{room.adults}</span>
                                    <Button icon={<PlusOutlined />} size="small" disabled={room.adults >= 4 || room.adults + room.children.length >= 6} onClick={() => updateGuestRoom(roomIndex, { adults: room.adults + 1 })} />
                                </Space>
                            </Col>
                        </Row>
                        <Row align="middle">
                            <Col span={10}>Trẻ em</Col>
                            <Col span={14}>
                                <Space>
                                    <Button icon={<MinusOutlined />} size="small" disabled={room.children.length <= 0} onClick={() => updateGuestRoom(roomIndex, { children: room.children.slice(0, -1) })} />
                                    <span>{room.children.length}</span>
                                    <Button icon={<PlusOutlined />} size="small" disabled={room.children.length >= 4 || room.adults + room.children.length >= 6} onClick={() => updateGuestRoom(roomIndex, { children: [...room.children, { age: 6 }] })} />
                                </Space>
                            </Col>
                        </Row>
                        {room.children.length > 0 && (
                            <div className="mt-2">
                                <Typography.Text type="secondary" style={{ fontSize: 12 }}>Tuổi của trẻ em:</Typography.Text>
                                <Row gutter={[8, 8]} className="mt-1">
                                    {room.children.map((child, childIndex) => (
                                        <Col key={childIndex}>
                                            <InputNumber
                                                min={0} max={17}
                                                value={child.age}
                                                onChange={(age) => {
                                                    const newChildren = [...room.children];
                                                    newChildren[childIndex] = { age: age || 0 };
                                                    updateGuestRoom(roomIndex, { children: newChildren });
                                                }}
                                                size="small"
                                                style={{ width: 60 }}
                                            />
                                        </Col>
                                    ))}
                                </Row>
                            </div>
                        )}
                    </Card>
                ))}
            </Space>
        </div>
    );

    return (
        <Card className="mb-6 shadow-sm">
            <Form form={form} layout="vertical" onFinish={handleSearch}>
                <Row gutter={16} align="bottom">
                    <Col xs={24} sm={12} md={8} lg={5}>
                        <Form.Item label="Ngày nhận - trả phòng" name="dateRange"><RangePicker className="w-full" format="DD/MM/YYYY" placeholder={['Ngày nhận', 'Ngày trả']} /></Form.Item>
                    </Col>
                    <Col xs={24} sm={12} md={8} lg={4}>
                        <Form.Item label="Loại phòng" name="roomType"><Select placeholder="Tất cả" allowClear>{roomTypes.map(type => <Option key={type.id} value={type.id}>{type.name}</Option>)}</Select></Form.Item>
                    </Col>

                    <Col xs={24} sm={12} md={8} lg={5}>
                        <Form.Item label="Số lượng khách">
                            <Popover content={guestPopoverContent} title="Chỉnh sửa số lượng khách" trigger="click" placement="bottomLeft" open={popoverVisible} onOpenChange={setPopoverVisible}>
                                <Input readOnly value={formatGuestSummary()} prefix={<UserOutlined />} style={{ cursor: 'pointer' }} />
                            </Popover>
                        </Form.Item>
                    </Col>
                    <Col xs={24} sm={24} md={24} lg={24} xl={3}>
                        <Form.Item label=" ">
                                <Space>
                                    <Button type="primary" icon={<SearchOutlined />} onClick={handleSearch} loading={loading}>Tìm</Button>
                                    <Button icon={<ReloadOutlined />} onClick={handleReset}>Reset</Button>
                                </Space>
                        </Form.Item>
                    </Col>
                </Row>
                 <Row>
                    <Col span={24} style={{ textAlign: 'right' }}>
                         <Badge count={selectedRoomsCount} showZero={false}>
                            <Button type="primary" ghost disabled={selectedRoomsCount === 0} icon={<ArrowRightOutlined />} onClick={onProceedToBooking}>
                                Tiến hành đặt ({selectedRoomsCount})
                            </Button>
                        </Badge>
                    </Col>
                </Row>
            </Form>
        </Card>
    );
};

export default FilterBar; 