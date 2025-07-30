import React, { useState, useMemo } from 'react';
import { useLocation, useNavigate, Navigate } from 'react-router-dom';
import dayjs from 'dayjs';
import { formatCurrency } from '../../../utils/helpers';
import {
    App, Button, Card, Col, Divider, Form, Input, Layout,
    Modal, Radio, Row, Space, Table, Tag, Typography, Alert
} from 'antd';
import { CreditCard, Edit3, UserPlus, Clipboard, Home, Users, Calendar } from 'lucide-react';
import { CreateMultiRoomBookingRequest, RepresentativeInfo } from '../../../types/booking';

const { Title, Text, Paragraph } = Typography;
const { Content } = Layout;

// --- Type Definitions ---
interface Room {
    id: string;
    name: string;
    room_type: { id: number; name: string; };
}
interface GuestRoom {
    adults: number;
    children: { age: number }[];
}
interface PackageInfo {
    package_id: number;
    package_name: string;
    price_per_room_per_night: number;
}
interface RoomTypeData {
    room_type_id: number;
    room_type_name: string;
    package_options: PackageInfo[];
}
interface SurchargeRule {
    min_age: number;
    max_age: number;
    surcharge_amount_vnd: number;
    is_free: boolean;
}
interface AvailableRoomsResponse {
    data: RoomTypeData[];
    summary: { children_surcharge_rules: SurchargeRule[]; };
}
interface BookingData {
    checkInDate: string;
    checkOutDate: string;
    guestRooms: GuestRoom[];
    selectedPackages: Record<string, number>;
    availableRoomsData: AvailableRoomsResponse;
}
interface LocationState {
    selectedRooms?: Room[];
    bookingData?: BookingData;
    // Add the new, simpler fields for direct data passing
    guestDetails?: { adults: number; children: number; childrenAges: string[] }[];
    adults?: number;
    children?: number;
    checkInDate?: string;
    checkOutDate?: string;
}
type RepresentativeMode = 'all' | 'individual';

const ConfirmRepresentativePayment = () => {
    const navigate = useNavigate();
    const location = useLocation();
    const { message } = App.useApp();
    const [repForm] = Form.useForm();

    const { selectedRooms = [], bookingData, guestDetails, adults, children, checkInDate, checkOutDate } = (location.state || {}) as LocationState;

    const [representativeMode, setRepresentativeMode] = useState<RepresentativeMode>('all');
    const [representatives, setRepresentatives] = useState<{ [key: string]: RepresentativeInfo }>({});
    const [isModalVisible, setIsModalVisible] = useState(false);
    const [currentEditingRoomId, setCurrentEditingRoomId] = useState<string | 'all' | null>(null);

    const summary = useMemo(() => {
        if (!selectedRooms.length || !checkInDate || !checkOutDate || !guestDetails) {
             console.error("Dữ liệu không hợp lệ được chuyển đến trang xác nhận:", location.state);
            return null;
        }

        const nights = dayjs(checkOutDate).diff(dayjs(checkInDate), 'day');
        let totalRoomPrice = selectedRooms.reduce((acc, room) => acc + (room.price || 0), 0) * nights;

        // This is a fallback, real price should come from bookingData if available
        if (bookingData?.selectedPackages && bookingData?.availableRoomsData) {
             let calculatedPrice = 0;
             const groupedByRoomType = selectedRooms.reduce((acc: any, room: any) => {
                const typeId = room.room_type.id.toString();
                if (!acc[typeId]) { acc[typeId] = { rooms: [] }; }
                acc[typeId].rooms.push(room);
                return acc;
            }, {});

            Object.entries(groupedByRoomType).forEach(([roomTypeId, group]: [string, any]) => {
                const roomTypeData = bookingData.availableRoomsData.data.find((rt: any) => rt.room_type_id.toString() === roomTypeId);
                const packageId = bookingData.selectedPackages[roomTypeId];
                if (roomTypeData && packageId) {
                    const pkg = roomTypeData.package_options.find((p: any) => p.package_id === packageId);
                    if (pkg) {
                        calculatedPrice += pkg.price_per_room_per_night * group.rooms.length * nights;
                    }
                }
            });
            totalRoomPrice = calculatedPrice;
        }

        return {
            checkInDate: dayjs(checkInDate),
            checkOutDate: dayjs(checkOutDate),
            nights,
            totalPrice: totalRoomPrice,
            totalAdults: adults,
            totalChildren: children,
            roomsWithGuests: selectedRooms.map((room, index) => ({
                ...room,
                guestConfig: guestDetails[index]
            }))
        };
    }, [location.state]);

    if (!summary) {
        return (
            <Content className="p-6 bg-gray-50 min-h-screen flex items-center justify-center">
                <Alert message="Lỗi dữ liệu" description="Không tìm thấy thông tin đặt phòng. Vui lòng quay lại và thử lại." type="error" showIcon action={<Button type="primary" onClick={() => navigate('/reception/room-management')}>Quay lại</Button>} />
            </Content>
        );
    }

    const handleOpenRepModal = (id: string | 'all') => {
        setCurrentEditingRoomId(id);
        repForm.setFieldsValue(representatives[id] || {});
        setIsModalVisible(true);
    };

    const handleSaveRepresentative = async () => {
        try {
            const values = await repForm.validateFields();
            if (currentEditingRoomId) setRepresentatives(prev => ({ ...prev, [currentEditingRoomId]: values }));
            setIsModalVisible(false);
            message.success('Lưu thông tin thành công!');
        } catch (error) { console.error('Validation failed:', error); }
    };

    const handleProceedToBooking = () => {
        // This is where the actual API call would happen.
        // Since bookingService doesn't exist, we'll simulate it.
        console.log("Proceeding to booking with payload:", {
            booking_details: {
                check_in_date: summary.checkInDate.format('YYYY-MM-DD'),
                check_out_date: summary.checkOutDate.format('YYYY-MM-DD'),
                adults: summary.totalAdults,
                children: summary.totalChildren,
                total_price: summary.totalPrice,
            },
            rooms: summary.roomsWithGuests.map(room => ({
                room_id: room.id,
                adults: room.guestConfig.adults,
                children: room.guestConfig.children,
                children_age: room.guestConfig.childrenAges,
            })),
            representative_info: {
                mode: representativeMode,
                details: representativeMode === 'all' ? representatives.all : representatives,
            },
        });
        message.success('Chức năng đang được phát triển. Dữ liệu đã được ghi lại trong console.');
        // navigate('/reception/payment-booking', { state: { bookingDetails: finalPayload } });
    };

    const columns = [
        { title: 'Phòng', dataIndex: 'name', key: 'name', render: (name: string) => <Text strong>{name}</Text> },
        { title: 'Loại phòng', dataIndex: ['room_type', 'name'], key: 'roomType' },
        { title: 'Khách', dataIndex: 'guestConfig', key: 'guests', render: (gc: any) => `${gc.adults} NL, ${gc.children} TE` },
        ...(representativeMode === 'individual' ? [{
            title: 'Người đại diện', key: 'representative',
            render: (_: any, room: any) => representatives[room.id]
                ? <Button icon={<Edit3 size={14} />} onClick={() => handleOpenRepModal(room.id)} size="small">{representatives[room.id].fullName}</Button>
                : <Button icon={<UserPlus size={14} />} type="primary" ghost onClick={() => handleOpenRepModal(room.id)} size="small">Thêm</Button>
        }] : [])
    ];

    return (
        <Content className="p-8">
            <Title level={2} className="mb-6">Xác nhận thông tin & Thanh toán</Title>
            <Row gutter={[24, 24]}>
                <Col xs={24} lg={16}>
                    <Space direction="vertical" size="large" className="w-full">
                        <Card title="Thông tin người đại diện">
                            <Radio.Group onChange={(e) => setRepresentativeMode(e.target.value)} value={representativeMode} className="mb-4">
                                <Radio value="all">Một người đại diện cho tất cả</Radio>
                                <Radio value="individual">Đại diện riêng cho từng phòng</Radio>
                            </Radio.Group>
                            {representativeMode === 'all' && (
                                <div className="p-4 bg-blue-50 rounded-lg flex justify-between items-center">
                                    <Text>Người đại diện cho {selectedRooms.length} phòng</Text>
                                    {representatives.all
                                        ? <Button icon={<Edit3 size={16} />} onClick={() => handleOpenRepModal('all')}>{representatives.all.fullName}</Button>
                                        : <Button icon={<Clipboard size={16} />} type="primary" onClick={() => handleOpenRepModal('all')}>Thêm người đại diện</Button>}
                                </div>
                            )}
                        </Card>
                        <Card title="Chi tiết các phòng đã chọn">
                            <Table columns={columns} dataSource={summary.roomsWithGuests} rowKey="id" pagination={false} bordered size="small" />
                        </Card>
                    </Space>
                </Col>
                <Col xs={24} lg={8}>
                    <Card title="Tóm tắt chi phí" className="sticky top-6">
                        <Space direction="vertical" className="w-full" size="middle">
                            <div className="flex justify-between"><Text><Calendar /> Nhận phòng:</Text><Text strong>{summary.checkInDate.format('DD/MM/YYYY')}</Text></div>
                            <div className="flex justify-between"><Text><Calendar /> Trả phòng:</Text><Text strong>{summary.checkOutDate.format('DD/MM/YYYY')}</Text></div>
                            <div className="flex justify-between"><Text><Home /> Số đêm:</Text><Text strong>{summary.nights}</Text></div>
                            <div className="flex justify-between"><Text><Users /> Khách:</Text><Text strong>{summary.totalAdults} NL, {summary.totalChildren} TE</Text></div>
                            <Divider className="my-1" />
                            <div className="flex justify-between items-center">
                                <Title level={4} className="!mb-0">Tổng cộng:</Title>
                                <Title level={4} className="!mb-0 text-blue-600">{formatCurrency(summary.totalPrice)}</Title>
                            </div>
                            <Paragraph type="secondary" className="text-xs text-center">Giá đã bao gồm thuế và phí dịch vụ.</Paragraph>
                            <Button type="primary" size="large" icon={<CreditCard size={20} />} onClick={handleProceedToBooking} block>Xác nhận & Tiến tới thanh toán</Button>
                        </Space>
                    </Card>
                </Col>
            </Row>
            <Modal title="Thông tin người đại diện" open={isModalVisible} onOk={handleSaveRepresentative} onCancel={() => setIsModalVisible(false)} okText="Lưu" cancelText="Hủy" width={600} centered>
                <Divider />
                <Form form={repForm} layout="vertical" className="mt-6">
                    <Row gutter={16}>
                        <Col span={12}><Form.Item label="Họ và Tên" name='fullName' rules={[{ required: true }]}><Input placeholder="Họ và tên" size="large" /></Form.Item></Col>
                        <Col span={12}><Form.Item label="Số Điện Thoại" name='phoneNumber' rules={[{ required: true }]}><Input placeholder="Số điện thoại" size="large" /></Form.Item></Col>
                        <Col span={12}><Form.Item label="Email" name='email' rules={[{ required: true, type: 'email' }]}><Input placeholder="Địa chỉ email" size="large" /></Form.Item></Col>
                        <Col span={12}><Form.Item label="ID Card / Passport" name='idCard' rules={[{ required: true }]}><Input placeholder="Số CMND/CCCD" size="large" /></Form.Item></Col>
                        <Col span={24}><Form.Item label="Ghi chú" name='notes'><Input.TextArea rows={3} placeholder="Thêm ghi chú..." /></Form.Item></Col>
                    </Row>
                </Form>
            </Modal>
        </Content>
    );
};

export default ConfirmRepresentativePayment;
