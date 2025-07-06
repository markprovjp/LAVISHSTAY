import React, { useState } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';
import {
    Card,
    Button,
    Modal,
    Form,
    Input,
    Typography,
    Space,
    App,
    Table,
    Tag,
    Divider
} from 'antd';
import {
    Edit3,
    CreditCard,
    Clipboard
} from 'lucide-react';
import dayjs from 'dayjs';
import { useSelector } from 'react-redux';
import { RootState } from '../../../store';

const { Title, Text } = Typography;

interface RoomType {
    id: string;
    name: string;
    adjusted_price: number;
    max_guests: number;
    size: number;
    description: string;
    main_image?: {
        image_url: string;
    };
    images?: Array<{
        image_url: string;
        is_main: boolean;
    }>;
    highlighted_amenities: string[];
}

interface Room {
    id: string;
    name: string;
    status: string;
    room_type: RoomType;
}

interface RepresentativeInfo {
    fullName: string;
    phoneNumber: string;
    email: string;
    idCard: string;
}

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
    }).format(amount);
};

const ConfirmRepresentativePayment: React.FC = () => {
    const navigate = useNavigate();
    const location = useLocation();
    const [form] = Form.useForm();
    const { message } = App.useApp();

    // Get data from navigation state
    const navigationState = location.state as {
        selectedRooms?: Room[];
        guestCount?: number;
        checkInDate?: string;
        checkOutDate?: string;
        quickBookNights?: number;
        bookingData?: {
            checkInDate?: string;
            checkOutDate?: string;
            adults: number;
            children: Array<{ age: number }>;
            notes?: string;
        };
    } || {};

    // Get rooms data (prioritize navigation state, fallback to Redux)
    const reduxRooms = useSelector((state: RootState) => state.Reception?.selectedRooms || []);
    const reduxDateRange = useSelector((state: RootState) => state.Reception?.dateRange || []);

    const roomsList = navigationState.selectedRooms || reduxRooms;
    const bookingData = navigationState.bookingData;
    const totalGuestCount = bookingData ?
        bookingData.adults + bookingData.children.length :
        (navigationState.guestCount || 2);

    // Ưu tiên: bookingData -> Redux dateRange -> navigationState -> quickBook fallback
    const checkInDate = bookingData?.checkInDate ? dayjs(bookingData.checkInDate) :
        (reduxDateRange[0] ? dayjs(reduxDateRange[0]) :
            (navigationState.checkInDate ? dayjs(navigationState.checkInDate) :
                // Nếu là quick book, dùng ngày hiện tại
                (navigationState.quickBookNights ? dayjs() : null)));

    const checkOutDate = bookingData?.checkOutDate ? dayjs(bookingData.checkOutDate) :
        (reduxDateRange[1] ? dayjs(reduxDateRange[1]) :
            (navigationState.checkOutDate ? dayjs(navigationState.checkOutDate) :
                // Nếu là quick book, tính từ check-in + số đêm
                (navigationState.quickBookNights && checkInDate ?
                    checkInDate.add(navigationState.quickBookNights, 'day') : null)));

    // State management - đơn giản hóa chỉ cần một người đại diện cho tất cả
    const [representative, setRepresentative] = useState<RepresentativeInfo | null>(null);
    const [isModalVisible, setIsModalVisible] = useState(false);

    // Debug logging
    console.log('Debug ConfirmRepresentativePayment:', {
        navigationState,
        reduxDateRange,
        bookingData,
        checkInDate: checkInDate?.format('YYYY-MM-DD'),
        checkOutDate: checkOutDate?.format('YYYY-MM-DD')
    });

    // Calculate booking details
    // Ưu tiên sử dụng quickBookNights nếu có, nếu không thì tính từ ngày check-in/out
    const nights = navigationState.quickBookNights ||
        (checkInDate && checkOutDate ? checkOutDate.diff(checkInDate, 'day') : 1);

    // Tính ngày check-out cuối cùng
    const finalCheckOutDate = checkOutDate ||
        (checkInDate ? checkInDate.add(nights, 'day') : dayjs().add(nights, 'day'));

    const roomsWithBookingInfo = roomsList.map(room => ({
        ...room,
        checkIn: checkInDate?.format('YYYY-MM-DD') || '',
        checkOut: finalCheckOutDate?.format('YYYY-MM-DD') || '',
        nights,
        totalPrice: (room.room_type?.adjusted_price || 0) * nights
    }));

    const subtotal = roomsWithBookingInfo.reduce((sum, room) => sum + room.totalPrice, 0);
    const selectedCount = roomsList.length;

    // Check if proceed button should be enabled - chỉ cần có representative
    const canProceed = selectedCount > 0 && representative !== null;

    // Handle representative modal - đơn giản hóa
    const handleAddRepresentative = () => {
        setIsModalVisible(true);

        // If editing existing representative, populate form
        if (representative) {
            form.setFieldsValue(representative);
        } else {
            form.resetFields();
        }
    };

    const handleSaveRepresentative = async () => {
        try {
            const values = await form.validateFields();
            setRepresentative(values);
            setIsModalVisible(false);
            form.resetFields();
            message.success('Thông tin người đại diện được lưu thành công');
        } catch (error) {
            console.error('Xác nhận không thành công:', error);
        }
    };

    const handleCancel = () => {
        setIsModalVisible(false);
        form.resetFields();
    };

    const handleProceedToBooking = async () => {
        // Validate: phải có đủ thông tin đại diện
        if (!canProceed) {
            message.warning('Vui lòng nhập đủ thông tin người đại diện!');
            return;
        }

        try {
            // Prepare booking data - match backend validation exactly
            const apiData = {
                rooms: roomsWithBookingInfo.map(room => ({
                    id: String(room.id), // Backend expects string
                    name: room.name,
                    room_type: {
                        adjusted_price: room.room_type?.adjusted_price || 0
                    }
                })),
                representatives: {
                    all: representative // Backend expects representatives object with 'all' key
                },
                checkInDate: checkInDate?.format('YYYY-MM-DD'),
                checkOutDate: finalCheckOutDate?.format('YYYY-MM-DD'),
                guestCount: totalGuestCount,
                subtotal: subtotal,
                representativeMode: 'all', // Always use 'all' mode
                bookingData: bookingData ? {
                    adults: bookingData.adults,
                    children: bookingData.children,
                    notes: bookingData.notes
                } : {
                    adults: 1,
                    children: [],
                    notes: null
                }
            };

            console.log('Saving booking data:', apiData);
            console.log('Debug calculated values:', {
                nights,
                finalCheckOutDate: finalCheckOutDate?.format('YYYY-MM-DD'),
                checkInDate: checkInDate?.format('YYYY-MM-DD'),
                subtotal,
                representative
            });

            // Call API to create booking
            const response = await fetch('http://localhost:8888/api/reception/bookings/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
                body: JSON.stringify(apiData)
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                console.error('API Error Response:', errorData);
                throw new Error(`HTTP error! status: ${response.status}. ${errorData.message || 'Unknown error'}`);
            }

            const result = await response.json();

            if (result.success) {
                message.success(`Đặt phòng thành công! Mã đặt phòng: ${result.booking_code}`);

                // Navigate back to room management
                navigate('/reception/room-management/today');
            } else {
                message.error(result.message || 'Lỗi khi tạo đặt phòng!');
            }

        } catch (err: any) {
            console.error('Error creating booking:', err);
            const errorMessage = err.message || 'Lỗi khi tạo đặt phòng!';
            message.error(errorMessage);
        }
    };

    // Table columns for selected rooms
    const columns = [
        {
            title: 'Số phòng',
            dataIndex: 'name',
            key: 'name',
            render: (text: string) => (
                <Text strong>{text}</Text>
            )
        },
        {
            title: 'Loại phòng',
            dataIndex: ['room_type', 'name'],
            key: 'roomType',
            render: (_: any, room: any) => (
                <Tag color="blue">{room.room_type?.name}</Tag>
            )
        },
        {
            title: 'Check-in',
            dataIndex: 'checkIn',
            key: 'checkIn',
            render: (date: string) => date ? dayjs(date).format('DD/MM/YYYY') : '-'
        },
        {
            title: 'Check-out',
            dataIndex: 'checkOut',
            key: 'checkOut',
            render: (date: string) => date ? dayjs(date).format('DD/MM/YYYY') : '-'
        },
        {
            title: 'Số đêm',
            dataIndex: 'nights',
            key: 'nights',
            align: 'center' as const,
        },
        {
            title: 'Khách',
            key: 'guests',
            render: (_: any) => {
                if (bookingData) {
                    return (
                        <div className="text-sm">
                            <div>Người lớn: {bookingData.adults}</div>
                            {bookingData.children.length > 0 && (
                                <div>Trẻ em: {bookingData.children.map(child => `${child.age} tuổi`).join(', ')}</div>
                            )}
                        </div>
                    );
                }
                return `${totalGuestCount} khách`;
            }
        },
        {
            title: 'Giá/đêm',
            dataIndex: ['room_type', 'adjusted_price'],
            key: 'basePrice',
            render: (_: any, room: any) => formatCurrency(room.room_type?.adjusted_price || 0)
        },
        {
            title: 'Tổng tiền',
            dataIndex: 'totalPrice',
            key: 'totalPrice',
            render: (value: number) => formatCurrency(value || 0)
        },
        {
            title: 'Người đại diện',
            key: 'representative',
            render: () => {
                // Tất cả phòng đều dùng cùng một người đại diện
                return representative ? (
                    <Button
                        icon={<Edit3 size={16} />}
                        onClick={() => handleAddRepresentative()}
                        size="small"
                    >
                        {representative.fullName}
                    </Button>
                ) : (
                    <Button
                        icon={<Clipboard size={16} />}
                        type="primary"
                        onClick={() => handleAddRepresentative()}
                        size="small"
                    >
                        Thêm
                    </Button>
                );
            }
        }
    ];

    return (
        <div className="p-6">
            <Title level={2} className="mb-6">
                Xác nhận thông tin người đại diện và đặt phòng
            </Title>

            {/* Người đại diện chung */}
            <Card className="mb-6 shadow-sm" title="Người đại diện">
                <div className="p-4 bg-blue-50 rounded-lg">
                    <div className="flex justify-between items-center">
                        <Text>Người đại diện cho {selectedCount} phòng</Text>
                        {representative ? (
                            <Button
                                icon={<Edit3 size={16} />}
                                onClick={() => handleAddRepresentative()}
                            >
                                {representative.fullName}
                            </Button>
                        ) : (
                            <Button
                                icon={<Clipboard size={16} />}
                                type="primary"
                                onClick={() => handleAddRepresentative()}
                            >
                                Thêm người đại diện
                            </Button>
                        )}
                    </div>
                </div>
            </Card>

            {/* Room Table */}
            <Card title="Danh sách phòng đã chọn" className="mb-8">
                <Table
                    columns={columns}
                    dataSource={roomsWithBookingInfo}
                    rowKey="id"
                    pagination={false}
                    bordered
                    scroll={{ x: 900 }}
                />
            </Card>

            {/* Action Buttons */}
            <Card className="shadow-lg sticky bottom-6 bg-white">
                <div className="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                    <div className="flex-1">
                        {selectedCount > 0 && !canProceed && (
                            <Text type="warning" className="block">
                                Vui lòng thêm thông tin người đại diện
                            </Text>
                        )}
                    </div>

                    <Space>
                        <Button
                            size="large"
                            onClick={() => navigate('/reception/room-management/today')}
                        >
                            Quay lại
                        </Button>
                        <Button
                            type="primary"
                            size="large"
                            icon={<CreditCard size={20} />}
                            onClick={handleProceedToBooking}
                            disabled={!canProceed}
                            className="px-8"
                        >
                            Lưu đặt phòng
                        </Button>
                    </Space>
                </div>
            </Card>

            {/* Representative Modal */}
            <Modal
                title={
                    <div className="flex items-center gap-2">
                        <Clipboard size={20} />
                        <span>Thông tin người đại diện</span>
                    </div>
                }
                open={isModalVisible}
                onOk={handleSaveRepresentative}
                onCancel={handleCancel}
                okText="Lưu"
                cancelText="Hủy"
                width={600}
                centered
            >
                <Divider />
                <Form
                    form={form}
                    layout="vertical"
                    className="mt-6"
                >
                    <Form.Item
                        label="Họ và Tên"
                        name="fullName"
                        rules={[
                            { required: true, message: 'Vui lòng nhập họ và tên' },
                            { min: 2, message: 'Tên phải có ít nhất 2 ký tự' }
                        ]}
                    >
                        <Input placeholder="Nhập họ và tên" size="large" />
                    </Form.Item>

                    <Form.Item
                        label="Số Điện Thoại"
                        name="phoneNumber"
                        rules={[
                            { required: true, message: 'Vui lòng nhập số điện thoại' },
                            { pattern: /^[\+]?[\d\s\-\(\)]+$/, message: 'Vui lòng nhập số điện thoại hợp lệ' }
                        ]}
                    >
                        <Input placeholder="Nhập số điện thoại" size="large" />
                    </Form.Item>

                    <Form.Item
                        label="Email"
                        name="email"
                        rules={[
                            { required: true, message: 'Vui lòng nhập địa chỉ email' },
                            { type: 'email', message: 'Vui lòng nhập địa chỉ email hợp lệ' }
                        ]}
                    >
                        <Input placeholder="Nhập địa chỉ email" size="large" />
                    </Form.Item>

                    <Form.Item
                        label="ID Card / Passport"
                        name="idCard"
                        rules={[
                            { required: true, message: 'Vui lòng nhập số CMND hoặc hộ chiếu' },
                            { min: 6, message: 'ID phải có ít nhất 6 ký tự' }
                        ]}
                    >
                        <Input placeholder="Nhập số CMND hoặc hộ chiếu" size="large" />
                    </Form.Item>
                </Form>
            </Modal>
        </div>
    );
};

export default ConfirmRepresentativePayment;
