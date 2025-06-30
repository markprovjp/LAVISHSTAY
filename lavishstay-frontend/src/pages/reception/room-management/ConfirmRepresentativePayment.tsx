import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import {
    Card,
    Checkbox,
    Button,
    Modal,
    Form,
    Input,
    Typography,
    Space,
    Divider,
    message,
    Badge,
    Table,
    Tooltip,
    Tag
} from 'antd';
import {
    Edit3,
    CheckCircle,
    Calendar,
    Users,
    CreditCard,
    Clipboard
} from 'lucide-react';
import dayjs from 'dayjs';
import { useSelector } from 'react-redux';
import { RootState } from '../../../store';
import { getIcon } from '../../../constants/Icons';

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
    checkIn: string;
    checkOut: string;
    nights: number;
    totalPrice: number;
}

interface RepresentativeInfo {
    fullName: string;
    phoneNumber: string;
    email: string;
    idCard: string;
}

interface ConfirmRepresentativePaymentProps {
    rooms?: Room[];
}

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
    }).format(amount);
};

const ConfirmRepresentativePayment: React.FC<ConfirmRepresentativePaymentProps> = () => {
    const navigate = useNavigate();
    const [form] = Form.useForm();

    // State management
    const [selectedRooms, setSelectedRooms] = useState<Set<string>>(new Set());
    const [representatives, setRepresentatives] = useState<Record<string, RepresentativeInfo>>({});
    const [isModalVisible, setIsModalVisible] = useState(false);
    const [currentRoomId, setCurrentRoomId] = useState<string>('');
    const [selectAll, setSelectAll] = useState(false);

    // Lấy danh sách phòng đã chọn từ Redux
    const selectedRoomsList = useSelector((state: RootState) => state.Reception.selectedRooms || []);
    // Lấy ngày nhận/trả phòng từ Redux
    const dateRange = useSelector((state: RootState) => state.Reception.dateRange || []);
    const checkInDate = dateRange[0] ? dayjs(dateRange[0]) : null;
    const checkOutDate = dateRange[1] ? dayjs(dateRange[1]) : null;

    // Calculate totals for selected rooms only
    const subtotal = selectedRoomsList.reduce((sum, room) => sum + (room.totalPrice || (room.room_type.adjusted_price * (room.nights || 1)) || 0), 0);
    const selectedCount = selectedRoomsList.length;

    // Check if proceed button should be enabled
    const canProceed = selectedCount > 0 && selectedRoomsList.every(room => representatives[room.id]);

    // Handle select all checkbox
    const handleSelectAll = (checked: boolean) => {
        setSelectAll(checked);
        if (checked) {
            setSelectedRooms(new Set(selectedRoomsList.map(room => room.id)));
        } else {
            setSelectedRooms(new Set());
            // Clear all representative data when deselecting all
            setRepresentatives({});
        }
    };

    // Handle individual room selection
    const handleRoomSelect = (roomId: string, checked: boolean) => {
        const newSelected = new Set(selectedRooms);
        if (checked) {
            newSelected.add(roomId);
        } else {
            newSelected.delete(roomId);
            // Remove representative info if room is deselected
            const newRepresentatives = { ...representatives };
            delete newRepresentatives[roomId];
            setRepresentatives(newRepresentatives);
        }
        setSelectedRooms(newSelected);
    };

    // Update select all state when individual selections change
    useEffect(() => {
        const allSelected = selectedRoomsList.length > 0 && selectedRoomsList.every(room => selectedRooms.has(room.id));
        setSelectAll(allSelected);
    }, [selectedRooms, selectedRoomsList.length]);

    // Handle representative modal
    const handleAddRepresentative = (roomId: string) => {
        setCurrentRoomId(roomId);
        setIsModalVisible(true);

        // If editing existing representative, populate form
        if (representatives[roomId]) {
            form.setFieldsValue(representatives[roomId]);
        } else {
            form.resetFields();
        }
    };

    const handleSaveRepresentative = async () => {
        try {
            const values = await form.validateFields();
            setRepresentatives({
                ...representatives,
                [currentRoomId]: values
            });
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

    const handleProceedToPayment = () => {
        navigate('/reception/payment-booking', {
            state: {
                selectedRooms: selectedRoomsList,
                representatives,
                subtotal,
                dateRange,
            }
        });
    };

    const getRoomImage = (room: Room) => {
        const roomType = room.room_type;
        let mainImage = roomType.main_image?.image_url;

        if (!mainImage && Array.isArray(roomType.images) && roomType.images.length > 0) {
            const mainImgObj = roomType.images.find((img: any) => img.is_main);
            mainImage = mainImgObj ? mainImgObj.image_url : roomType.images[0].image_url;
        }

        return mainImage || 'https://images.pexels.com/photos/164595/pexels-photo-164595.jpeg';
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
            render: (_: any, room: Room) => (
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
            title: 'Giá/đêm',
            dataIndex: ['room_type', 'adjusted_price'],
            key: 'basePrice',
            render: (_: any, room: Room) => formatCurrency(room.room_type?.adjusted_price || 0)
        },
        {
            title: 'Tổng tiền',
            dataIndex: 'totalPrice',
            key: 'totalPrice',
            render: (value: number, room: Room) => formatCurrency(value || (room.room_type?.adjusted_price * (room.nights || 1)))
        },

        {
            title: 'Người đại diện',
            key: 'representative',
            render: (_: any, room: Room) => (
                representatives[room.id] ? (
                    <Tooltip title="Chỉnh sửa thông tin đại diện">
                        <Button icon={<Edit3 size={16} />} onClick={() => handleAddRepresentative(room.id)} size="small">
                            {representatives[room.id].fullName}
                        </Button>
                    </Tooltip>
                ) : (
                    <Button icon={<Clipboard size={16} />} type="primary" onClick={() => handleAddRepresentative(room.id)} size="small">
                        Thêm
                    </Button>
                )
            )
        }
    ];

    return (
        <div className=" p-6  ">

            <Title level={2} className="mb-6">
                Xác nhận thông tin người đại diện và thanh toán
            </Title>
            {/* Select All Checkbox */}
            <Card className="mb-6 shadow-sm">
                <Checkbox
                    checked={selectAll}
                    onChange={(e) => handleSelectAll(e.target.checked)}
                    className="text-lg font-medium"
                >
                    Chọn tất cả các phòng ({selectedRoomsList.length})
                </Checkbox>
            </Card>

            {/* Room Table */}
            <Card title="Danh sách phòng đã chọn" className="mb-8">
                <Table
                    columns={columns}
                    dataSource={selectedRoomsList}
                    rowKey="id"
                    pagination={false}
                    bordered
                    scroll={{ x: 900 }}
                />
            </Card>

            {/* Payment Summary */}
            <Card className="shadow-lg sticky bottom-6 bg-white">
                <div className="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                    <div className="flex-1">
                        <Title level={4} className="mb-2">
                            Tóm tắt thanh toán
                        </Title>
                        <div className="flex flex-col sm:flex-row gap-4 text-lg">
                            <Text strong>
                                {selectedCount} phòng đã chọn
                            </Text>
                            <Divider type="vertical" className="hidden sm:block" />
                            <Text strong className="text-blue-600">
                                Tổng phụ: {formatCurrency(subtotal)}
                            </Text>
                        </div>

                        {selectedCount > 0 && !canProceed && (
                            <Text type="warning" className="block mt-2">
                                Vui lòng thêm thông tin đại diện cho tất cả các phòng được chọn
                            </Text>
                        )}
                    </div>

                    <Button
                        type="primary"
                        size="large"
                        icon={<CreditCard size={20} />}
                        onClick={handleProceedToPayment}
                        disabled={!canProceed}
                        className="w-full lg:w-auto px-8 py-6 h-auto text-lg font-medium"
                    >
                        Tiến hành thanh toán
                    </Button>
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
                className="top-8"
            >
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