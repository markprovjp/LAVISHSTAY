import React, { useState, useMemo } from 'react';
import { useLocation, useNavigate } from 'react-router-dom';
import {
    App, Button, Card, Col, Divider, Form, Input, Layout,
    Modal, Radio, Row, Space, Table, Tag, Typography, Alert
} from 'antd';
import { CreditCard, Edit3, UserPlus, Clipboard, Home, Users, Calendar } from 'lucide-react';
import dayjs from 'dayjs';
import { formatCurrency } from '../../../utils/helpers';
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
}
type RepresentativeMode = 'all' | 'individual';

const ConfirmRepresentativePayment: React.FC = () => {
    const navigate = useNavigate();
    const location = useLocation();
    const { message } = App.useApp();
    const [repForm] = Form.useForm();

    const { selectedRooms = [], bookingData } = (location.state || {}) as LocationState;
    console.log("Received state in ConfirmRepresentativePayment:", location.state);

    const [representativeMode, setRepresentativeMode] = useState<RepresentativeMode>('all');
    const [representatives, setRepresentatives] = useState<{ [key: string]: RepresentativeInfo }>({});
    const [isModalVisible, setIsModalVisible] = useState(false);
    const [currentEditingRoomId, setCurrentEditingRoomId] = useState<string | 'all' | null>(null);

    const summary = useMemo(() => {
        // KIỂM TRA AN TOÀN TOÀN DIỆN:
        // Đảm bảo tất cả dữ liệu cần thiết đều tồn tại và có cấu trúc đúng.
        if (
            !bookingData ||
            !selectedRooms || !selectedRooms.length ||
            !Array.isArray(bookingData.guestRooms) ||
            selectedRooms.length !== bookingData.guestRooms.length || // Số phòng phải khớp
            !bookingData.availableRoomsData ||
            !Array.isArray(bookingData.availableRoomsData.data)
        ) {
            console.error("Dữ liệu không hợp lệ hoặc không đồng bộ được chuyển đến trang xác nhận:", { selectedRooms, bookingData });
            return null; // Ngăn chặn render và kích hoạt giao diện báo lỗi
        }

        const { checkInDate, checkOutDate, selectedPackages, availableRoomsData, guestRooms } = bookingData;
        const nights = dayjs(checkOutDate).diff(dayjs(checkInDate), 'day');
        const surchargeRules = availableRoomsData.summary?.children_surcharge_rules || [];

        const roomsWithGuests = selectedRooms.map((room, index) => ({
            ...room,
            guestConfig: guestRooms[index] // Bây giờ truy cập này đã an toàn
        }));

        const groupedRooms = roomsWithGuests.reduce((acc, room) => {
            const typeId = room.room_type.id.toString();
            if (!acc[typeId]) {
                acc[typeId] = { roomTypeName: room.room_type.name, rooms: [], packageInfo: null, subtotal: 0 };
            }
            acc[typeId].rooms.push(room);
            return acc;
        }, {} as Record<string, any>);

        let totalRoomPrice = 0;
        const priceDetails: { label: string; amount: number; type: 'room' | 'surcharge' }[] = [];

        Object.keys(groupedRooms).forEach(typeId => {
            const group = groupedRooms[typeId];
            const roomTypeData = availableRoomsData.data.find(rt => rt.room_type_id.toString() === typeId);
            const packageId = selectedPackages[typeId];

            if (roomTypeData && packageId) {
                const pkg = roomTypeData.package_options.find(p => p.package_id === packageId);
                if (pkg) {
                    const subtotal = pkg.price_per_room_per_night * group.rooms.length * nights;
                    group.packageInfo = pkg;
                    group.subtotal = subtotal;
                    totalRoomPrice += subtotal;
                    priceDetails.push({
                        label: `${group.roomTypeName} (${group.rooms.length} phòng x ${nights} đêm)`,
                        amount: subtotal,
                        type: 'room',
                    });
                }
            }
        });

        // --- CHILDREN SURCHARGE LOGIC ---
        let totalChildrenSurcharge = 0;
        const allChildren = guestRooms.flatMap(g => g.children);
        if (allChildren.length > 0 && surchargeRules.length > 0) {
            allChildren.forEach((child, index) => {
                // Find the correct rule for this child's age
                const rule = surchargeRules.find(r => child.age >= r.min_age && child.age <= r.max_age);
                if (rule && !rule.is_free && rule.surcharge_amount_vnd) {
                    const surchargePerNight = rule.surcharge_amount_vnd;
                    const surchargeTotal = surchargePerNight * nights;
                    totalChildrenSurcharge += surchargeTotal;
                    priceDetails.push({
                        label: `Phụ phí trẻ em ${index + 1} (${child.age} tuổi) x ${nights} đêm`,
                        amount: surchargeTotal,
                        type: 'surcharge',
                    });
                } else if (rule && rule.is_free) {
                    priceDetails.push({
                        label: `Trẻ em ${index + 1} (${child.age} tuổi) miễn phí`,
                        amount: 0,
                        type: 'surcharge',
                    });
                }
            });
        }

        const totalPrice = totalRoomPrice + totalChildrenSurcharge;
        const totalAdults = guestRooms.reduce((sum, room) => sum + room.adults, 0);
        const totalChildren = guestRooms.reduce((sum, room) => sum + room.children.length, 0);

        return {
            checkInDate: dayjs(checkInDate),
            checkOutDate: dayjs(checkOutDate),
            nights,
            totalPrice,
            priceDetails,
            groupedRooms,
            totalAdults,
            totalChildren,
            roomsWithGuests,
        };
    }, [bookingData, selectedRooms]);

    if (!summary || !bookingData) {
        return (
            <Content className="p-6 bg-gray-50 min-h-screen flex items-center justify-center">
                <Alert message="Lỗi dữ liệu" description="Không tìm thấy thông tin. Vui lòng quay lại." type="error" showIcon action={<Button type="primary" onClick={() => navigate('/reception/room-management-list')}>Quay lại</Button>} />
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
        const isDataReady = representativeMode === 'all' ? !!representatives.all : summary.roomsWithGuests.every(room => !!representatives[room.id]);
        if (!isDataReady) {
            message.warning('Vui lòng nhập đủ thông tin người đại diện.');
            return;
        }

        const finalPayload: CreateMultiRoomBookingRequest = {
            booking_details: {
                check_in_date: summary.checkInDate.format('YYYY-MM-DD'),
                check_out_date: summary.checkOutDate.format('YYYY-MM-DD'),
                adults: summary.totalAdults,
                children: bookingData.guestRooms.flatMap(g => g.children),
                total_price: summary.totalPrice,
                notes: representativeMode === 'all' ? representatives.all?.notes : undefined,
            },
            rooms: summary.roomsWithGuests.map(room => ({
                room_id: room.id,
                package_id: bookingData.selectedPackages[room.room_type.id.toString()],
                room_type_id: room.room_type.id, // Ensure room_type_id is present
                adults: room.guestConfig.adults,
                children: room.guestConfig.children,
            })),
            representative_info: {
                mode: representativeMode,
                details: representativeMode === 'all' ? representatives.all : representatives,
            },
            payment_method: 'pay_at_hotel',
        };

        console.log("Navigating to PaymentBookingReception with state:", { bookingDetails: finalPayload });
        navigate('/reception/payment-booking', { state: { bookingDetails: finalPayload } });
    };

    const columns = [
        { title: 'Phòng', dataIndex: 'name', key: 'name', render: (name: string) => <Text strong>{name}</Text> },
        { title: 'Loại phòng', dataIndex: ['room_type', 'name'], key: 'roomType' },
        {
            title: 'Gói dịch vụ', key: 'package', render: (_: any, room: any) => {
                // Find package info for this room
                const typeId = room.room_type.id.toString();
                const packageId = bookingData.selectedPackages[typeId];
                const roomTypeData = bookingData.availableRoomsData.data.find(rt => rt.room_type_id.toString() === typeId);
                const pkg = roomTypeData?.package_options.find(p => p.package_id === packageId);
                return pkg ? <Tag color="blue">{pkg.package_name}</Tag> : <Tag color="default">Không rõ</Tag>;
            }
        },
        {
            title: 'Khách', dataIndex: 'guestConfig', key: 'guests', render: (gc: GuestRoom) => {
                const childrenAges = gc.children.map((c: { age: number }, idx: number) => `${c.age} tuổi`).join(', ');
                return (
                    <span>
                        <span style={{ fontWeight: 500 }}>{gc.adults} NL</span>
                        {gc.children.length > 0 && (
                            <span>, {gc.children.length} TE ({childrenAges})</span>
                        )}
                    </span>
                );
            }
        },
        ...(representativeMode === 'individual' ? [{
            title: 'Người đại diện', key: 'representative',
            render: (_: any, room: any) => representatives[room.id]
                ? <Button icon={<Edit3 size={14} />} onClick={() => handleOpenRepModal(room.id)} size="small">{representatives[room.id].fullName}</Button>
                : <Button icon={<UserPlus size={14} />} type="primary" ghost onClick={() => handleOpenRepModal(room.id)} size="small">Thêm</Button>
        }] : [])
    ];

    return (
        <Content className="">
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
                        <Card title="Chi tiết đặt phòng">
                            <Table columns={columns} dataSource={summary.roomsWithGuests} rowKey="id" pagination={false} bordered size="small" />
                        </Card>
                    </Space>
                </Col>
                <Col xs={24} lg={8}>
                    <Card title="Tóm tắt chi phí" className="sticky top-6">
                        <Space direction="vertical" className="w-full" size="middle">
                            <div className="flex justify-between"><Text><Calendar /> Ngày nhận phòng:</Text><Text strong>{summary.checkInDate.format('DD/MM/YYYY')}</Text></div>
                            <div className="flex justify-between"><Text><Calendar /> Ngày trả phòng:</Text><Text strong>{summary.checkOutDate.format('DD/MM/YYYY')}</Text></div>
                            <div className="flex justify-between"><Text><Home /> Số đêm:</Text><Text strong>{summary.nights}</Text></div>
                            <div className="flex justify-between"><Text><Users /> Số khách:</Text><Text strong>{summary.totalAdults} NL, {summary.totalChildren} TE</Text></div>
                            <Divider className="my-0" />
                            <Title level={5}>Chi tiết giá</Title>
                            {summary.priceDetails.map((item, index) => (
                                <div key={index} className="flex justify-between">
                                    <Text type={item.type === 'surcharge' ? 'secondary' : undefined}>{item.label}:</Text>
                                    <Text type={item.type === 'surcharge' ? 'secondary' : undefined}>{formatCurrency(item.amount)}</Text>
                                </div>
                            ))}
                            <Divider className="my-0" />
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
                        <Col span={24}><Form.Item label="Ghi chú" name='notes'><Input.TextArea rows={3} placeholder="Thêm ghi chú cho phòng này..." /></Form.Item></Col>
                    </Row>
                </Form>
            </Modal>
        </Content>
    );
};

export default ConfirmRepresentativePayment;