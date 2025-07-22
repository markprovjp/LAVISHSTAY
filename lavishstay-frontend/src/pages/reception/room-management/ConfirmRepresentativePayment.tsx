import React, { useState } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';
import { Card, Button, Modal, Form, Input, Typography, Space, App, Table, Tag, Divider, Row, Col, Radio } from 'antd';
import { Edit3, CreditCard, Clipboard, UserPlus } from 'lucide-react';
import dayjs from 'dayjs';

const { Title, Text, Paragraph } = Typography;

// --- Type Definitions ---
type RepresentativeMode = 'all' | 'individual';
interface Room { id: string; name: string; status: string; room_type: { id: string; name: string; adjusted_price: number; max_guests: number; }; }
interface RepresentativeInfo { fullName: string; phoneNumber: string; email: string; idCard: string; }
interface GuestInfo { adults: number; children: Array<{ age: number }>; }
interface RoomGuestData { [roomId: string]: GuestInfo; }
interface BookingPayload { guests: RoomGuestData; notes?: string; quickBookNights?: number; }

// --- Helper ---
const formatCurrency = (amount: number) => new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(amount);

const ConfirmRepresentativePayment: React.FC = () => {
    const navigate = useNavigate();
    const location = useLocation();
    const [repForm] = Form.useForm();
    const { message } = App.useApp();

    // --- Data Retrieval & State ---
    const {
        selectedRooms = [],
        bookingData,
        checkInDate: navCheckIn,
        checkOutDate: navCheckOut,
        quoteData // NEW: Receive the pre-calculated quote
    } = (location.state || {}) as {
        selectedRooms?: Room[];
        bookingData?: BookingPayload;
        checkInDate?: string;
        checkOutDate?: string;
        quoteData?: any; // The data from the calculation API
    };

    const [representativeMode, setRepresentativeMode] = useState<RepresentativeMode>('all');
    const [representatives, setRepresentatives] = useState<{ [roomId: string]: RepresentativeInfo }>({});
    const [isModalVisible, setIsModalVisible] = useState(false);
    const [currentEditingRoomId, setCurrentEditingRoomId] = useState<string | 'all' | null>(null);

    // --- Date Calculation ---
    const checkInDate = dayjs(navCheckIn || dayjs());
    const nights = bookingData?.quickBookNights || (navCheckOut ? dayjs(navCheckOut).diff(checkInDate, 'day') : 1);
    const checkOutDate = dayjs(navCheckOut || checkInDate.add(nights, 'day'));

    const roomsWithBookingInfo = selectedRooms.map(room => ({
        ...room,
        guestInfo: bookingData?.guests[room.id] || { adults: 1, children: [] },
        totalPrice: (room.room_type?.adjusted_price || 0) * nights,
    }));

    // --- Event Handlers ---
    const handleOpenRepModal = (roomId: string | 'all') => {
        setCurrentEditingRoomId(roomId);
        const currentRep = representatives[roomId] || null;
        repForm.setFieldsValue(currentRep);
        setIsModalVisible(true);
    };

    const handleSaveRepresentative = async () => {
        try {
            const values = await repForm.validateFields();
            if (currentEditingRoomId) {
                setRepresentatives(prev => ({ ...prev, [currentEditingRoomId]: values }));
            }
            setIsModalVisible(false);
            repForm.resetFields();
            message.success('Lưu thông tin người đại diện thành công');
        } catch (error) { console.error('Validation failed:', error); }
    };

    const handleProceedToBooking = async () => {
        const isDataReady = representativeMode === 'all'
            ? !!representatives['all']
            : selectedRooms.every(room => !!representatives[room.id]);

        if (!isDataReady || !quoteData) {
            message.warning('Vui lòng nhập đủ thông tin người đại diện.');
            return;
        }

        const apiData = {
            checkInDate: checkInDate.format('YYYY-MM-DD'),
            checkOutDate: checkOutDate.format('YYYY-MM-DD'),
            representativeMode,
            representative: representativeMode === 'all' ? representatives['all'] : undefined,
            representatives: representativeMode === 'individual' ? representatives : undefined,
            rooms: selectedRooms.map(room => ({
                room_id: room.id,
                ...bookingData?.guests[room.id]
            })),
            notes: bookingData?.notes,
            quote: quoteData, // Use the pre-calculated quote
        };

        console.log("FE is sending this to FINAL booking API:", apiData);
        message.success("Đã sẵn sàng tạo đặt phòng, xem console log để biết chi tiết.");
    };

    // --- Table Columns ---
    const columns = [
        { title: 'Số phòng', dataIndex: 'name', key: 'name', render: (text: string) => <Text strong>{text}</Text> },
        { title: 'Loại phòng', dataIndex: ['room_type', 'name'], key: 'roomType', render: (name: string) => <Tag color="blue">{name}</Tag> },
        {
            title: 'Khách', key: 'guests',
            render: (_: any, room: any) => (
                <div className="text-sm">
                    <div>NL: {room.guestInfo.adults}</div>
                    {room.guestInfo.children?.length > 0 && (
                        <div>TE: {room.guestInfo.children.map((c: { age: number }) => `${c.age}t`).join(', ')}</div>
                    )}
                </div>
            )
        },
        ...(representativeMode === 'individual' ? [{
            title: 'Người đại diện', key: 'representative',
            render: (_: any, room: any) => {
                const rep = representatives[room.id];
                return rep ? (
                    <Button icon={<Edit3 size={16} />} onClick={() => handleOpenRepModal(room.id)} size="small">{rep.fullName}</Button>
                ) : (
                    <Button icon={<UserPlus size={16} />} type="primary" ghost onClick={() => handleOpenRepModal(room.id)} size="small">Thêm</Button>
                );
            }
        }] : [])
    ];

    return (
        <div className="p-6 bg-gray-50 min-h-screen">
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
                                    {representatives['all'] ? (
                                        <Button icon={<Edit3 size={16} />} onClick={() => handleOpenRepModal('all')}>{representatives['all'].fullName}</Button>
                                    ) : (
                                        <Button icon={<Clipboard size={16} />} type="primary" onClick={() => handleOpenRepModal('all')}>Thêm người đại diện</Button>
                                    )}
                                </div>
                            )}
                        </Card>
                        <Card title="Danh sách phòng đã chọn">
                            <Table columns={columns} dataSource={roomsWithBookingInfo} rowKey="id" pagination={false} bordered size="small" />
                        </Card>
                    </Space>
                </Col>
                <Col xs={24} lg={8}>
                    <Card title="Tóm tắt chi phí" className="sticky top-6">
                        {!quoteData && <Alert message="Không có thông tin báo giá." type="warning" />}
                        {quoteData && (
                            <Space direction="vertical" className="w-full" size="middle">
                                <div className="flex justify-between"><Text>Ngày nhận phòng:</Text><Text strong>{checkInDate.format('DD/MM/YYYY')}</Text></div>
                                <div className="flex justify-between"><Text>Ngày trả phòng:</Text><Text strong>{checkOutDate.format('DD/MM/YYYY')}</Text></div>
                                <div className="flex justify-between"><Text>Số đêm:</Text><Text strong>{nights}</Text></div>
                                <Divider className="my-0" />
                                {quoteData.price_details?.map((item: any) => (
                                     <div key={item.label} className="flex justify-between"><Text>{item.label}:</Text><Text>{formatCurrency(item.amount)}</Text></div>
                                ))}
                                <Divider className="my-0" />
                                <div className="flex justify-between items-center"><Title level={4} className="!mb-0">Tổng cộng:</Title><Title level={4} className="!mb-0 text-blue-600">{formatCurrency(quoteData.total_price)}</Title></div>
                                <Paragraph type="secondary" className="text-xs text-center">Giá đã bao gồm thuế và phí dịch vụ.</Paragraph>
                                <Button type="primary" size="large" icon={<CreditCard size={20} />} onClick={handleProceedToBooking} block>Xác nhận & Tiến tới thanh toán</Button>
                            </Space>
                        )}
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
                    </Row>
                </Form>
            </Modal>
        </div>
    );
};

export default ConfirmRepresentativePayment;
