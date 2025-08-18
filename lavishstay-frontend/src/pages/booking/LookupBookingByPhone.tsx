import React, { useState, useCallback } from 'react';
import {
    Card,
    Form,
    Input,
    Button,
    Table,
    Drawer,
    Descriptions,
    Tag,
    Typography,
    Space,
    Empty,
    message,
    Divider,
    Row,
    Col,
    Alert,
    Badge
} from 'antd';
import {
    SearchOutlined,
    PhoneOutlined,
    UserOutlined,
    CalendarOutlined,
    DollarOutlined,
    HomeOutlined,
    InfoCircleOutlined,
    EyeOutlined
} from '@ant-design/icons';
import axiosInstance from '../../utils/api';

const { Title, Text } = Typography;
const { Item } = Descriptions;

interface BookingData {
    booking_id: number;
    booking_code: string;
    guest_name: string;
    guest_phone: string;
    guest_email: string;
    check_in_date: string;
    check_out_date: string;
    total_price_vnd: string;
    total_price_raw: number;
    status: string;
    guest_count: number;
    nights: number;
    rooms: Array<{
        room_id: number;
        room_name: string;
        room_type: string;
        option_name: string;
        adults: number;
        children: number;
        total_price: string;
    }>;
    representatives: Array<{
        name: string;
        phone: string;
    }>;
    total_paid: number;
    created_at: string;
}

interface BookingDetailData extends Omit<BookingData, 'representatives'> {
    id: number;
    booking_code: string;
    check_in_date: string;
    check_out_date: string;
    total_amount: number;
    total_price_formatted: string;
    total_price_raw: number;
    status: string;
    notes?: string;
    created_at: string;
    updated_at: string;
    representatives: {
        id: number;
        full_name: string;
        phone_number: string;
        email: string;
        id_card: string;
    }[];
    rooms_detail: {
        id: number;
        room_name: string;
        room_type: string;
        floor?: string;
        option_name?: string;
        adults?: number;
        children?: number;
        nights?: number;
        quantity: number;
        price_per_night: number;
        total_price: number;
    }[];
    booking_rooms: {
        id: number;
        room_type: string;
        quantity: number;
        price_per_night: number;
        total_price: number;
    }[];
    payments: {
        id: number;
        payment_id: number;
        method: string;
        payment_type: string;
        amount: number;
        amount_vnd: string;
        status: string;
        created_at: string;
    }[];
}interface SearchResponse {
    success: boolean;
    data: BookingData[];
    meta: {
        total: number;
        page: number;
        per_page: number;
        last_page: number;
        search_type: string;
        search_term: string;
    };
}

const LookupBookingByPhone: React.FC = () => {
    const [form] = Form.useForm();

    const [loading, setLoading] = useState(false);
    const [detailLoading, setDetailLoading] = useState(false);
    const [bookings, setBookings] = useState<BookingData[]>([]);
    const [searchMeta, setSearchMeta] = useState<SearchResponse['meta'] | null>(null);
    const [selectedBooking, setSelectedBooking] = useState<BookingDetailData | null>(null);
    const [drawerVisible, setDrawerVisible] = useState(false);

    // Search bookings
    const handleSearch = useCallback(async (values: { search: string }, page = 1) => {
        if (!values.search?.trim()) {
            message.warning('Vui lòng nhập số điện thoại hoặc mã booking');
            return;
        }

        setLoading(true);
        try {
            const response = await axiosInstance.get<SearchResponse>('/public/bookings/search', {
                params: {
                    search: values.search.trim(),
                    page,
                    per_page: 20
                }
            });

            if (response.data.success) {
                setBookings(response.data.data);
                setSearchMeta(response.data.meta);

                if (response.data.data.length === 0) {
                    message.info('Không tìm thấy booking nào với thông tin này');
                } else {
                    message.success(`Tìm thấy ${response.data.meta.total} booking`);
                }
            } else {
                message.error('Không thể tìm kiếm booking. Vui lòng thử lại!');
            }
        } catch (error: any) {
            console.error('Search error:', error);
            if (error.response?.status === 429) {
                message.error('Bạn tìm kiếm quá nhanh. Vui lòng chờ một chút và thử lại!');
            } else {
                message.error('Đã xảy ra lỗi khi tìm kiếm. Vui lòng thử lại!');
            }
        } finally {
            setLoading(false);
        }
    }, []);

    // Get booking detail
    const handleViewDetail = useCallback(async (bookingId: number) => {
        setDetailLoading(true);
        try {
            const response = await axiosInstance.get<{ success: boolean; data: BookingDetailData }>(`/public/bookings/${bookingId}/detail`);

            if (response.data.success) {
                setSelectedBooking(response.data.data);
                setDrawerVisible(true);
            } else {
                message.error('Không thể lấy thông tin chi tiết booking');
            }
        } catch (error) {
            console.error('Detail error:', error);
            message.error('Đã xảy ra lỗi khi lấy thông tin chi tiết');
        } finally {
            setDetailLoading(false);
        }
    }, []);

    // Handle pagination
    const handleTableChange = (pagination: any) => {
        const currentSearch = form.getFieldValue('search');
        if (currentSearch) {
            handleSearch({ search: currentSearch }, pagination.current);
        }
    };

    // Status color mapping
    const getStatusColor = (status: string) => {
        const colorMap: Record<string, string> = {
            'Pending': 'orange',
            'Confirmed': 'blue',
            'Operational': 'cyan',
            'Completed': 'green',
            'Cancelled': 'red',
            'Cancelled With Penalty': 'red',
            'Unsuccessful': 'red'
        };
        return colorMap[status] || 'default';
    };

    // Status text mapping
    const getStatusText = (status: string) => {
        const textMap: Record<string, string> = {
            'Pending': 'Chờ xác nhận',
            'Confirmed': 'Đã xác nhận',
            'Operational': 'Đang thực hiện',
            'Completed': 'Hoàn thành',
            'Cancelled': 'Đã hủy',
            'Cancelled With Penalty': 'Hủy có phí',
            'Unsuccessful': 'Không thành công'
        };
        return textMap[status] || status;
    };

    // Table columns
    const columns = [
        {
            title: 'Mã booking',
            dataIndex: 'booking_code',
            key: 'booking_code',
            render: (text: string, record: BookingData) => (
                <Button
                    type="link"
                    size="small"
                    onClick={() => handleViewDetail(record.booking_id)}
                    loading={detailLoading}
                >
                    <strong>{text}</strong>
                </Button>
            ),
        },
        {
            title: 'Tên khách',
            dataIndex: 'guest_name',
            key: 'guest_name',
            render: (text: string) => (
                <Space>
                    <UserOutlined />
                    {text}
                </Space>
            ),
        },
        {
            title: 'Số điện thoại',
            dataIndex: 'guest_phone',
            key: 'guest_phone',
            render: (text: string) => (
                <Space>
                    <PhoneOutlined />
                    {text}
                </Space>
            ),
        },
        {
            title: 'Ngày nhận/trả phòng',
            key: 'dates',
            render: (record: BookingData) => (
                <Space direction="vertical" size={0}>
                    <Text><CalendarOutlined /> {record.check_in_date}</Text>
                    <Text type="secondary">đến {record.check_out_date}</Text>
                    <Badge count={`${record.nights} đêm`} color="blue" />
                </Space>
            ),
        },
        {
            title: 'Tổng tiền',
            dataIndex: 'total_price_vnd',
            key: 'total_price_vnd',
            render: (text: string) => (
                <Space>
                    <DollarOutlined />
                    <strong>{text} VNĐ</strong>
                </Space>
            ),
        },
        {
            title: 'Trạng thái',
            dataIndex: 'status',
            key: 'status',
            render: (status: string) => (
                <Tag color={getStatusColor(status)}>
                    {getStatusText(status)}
                </Tag>
            ),
        },
        {
            title: 'Hành động',
            key: 'action',
            render: (record: BookingData) => (
                <Button
                    type="primary"
                    size="small"
                    icon={<EyeOutlined />}
                    onClick={() => handleViewDetail(record.booking_id)}
                    loading={detailLoading}
                >
                    Chi tiết
                </Button>
            ),
        },
    ];

    return (
        <div style={{ maxWidth: 1200, margin: '0 auto', padding: '24px' }}>
            {/* Header */}
            <Card style={{ marginBottom: 24, textAlign: 'center' }}>
                <Title level={2}>
                    <SearchOutlined /> Tra cứu thông tin đặt phòng
                </Title>
                <Text type="secondary">
                    Nhập số điện thoại hoặc mã booking để tra cứu thông tin đặt phòng của bạn
                </Text>
            </Card>

            {/* Search Form */}
            <Card style={{ marginBottom: 24 }}>
                <Form
                    form={form}
                    layout="vertical"
                    onFinish={(values) => handleSearch(values, 1)}
                >
                    <Row gutter={16}>
                        <Col xs={24} sm={18}>
                            <Form.Item
                                name="search"
                                label="Số điện thoại hoặc mã booking"
                                rules={[
                                    { required: true, message: 'Vui lòng nhập thông tin tìm kiếm' },
                                    { min: 3, message: 'Vui lòng nhập ít nhất 3 ký tự' }
                                ]}
                            >
                                <Input
                                    size="large"
                                    placeholder="Ví dụ: 0901234567 hoặc LS-2025-00001"
                                    prefix={<SearchOutlined />}
                                    allowClear
                                />
                            </Form.Item>
                        </Col>
                        <Col xs={24} sm={6}>
                            <Form.Item label=" ">
                                <Button
                                    type="primary"
                                    size="large"
                                    htmlType="submit"
                                    loading={loading}
                                    block
                                    icon={<SearchOutlined />}
                                >
                                    Tìm kiếm
                                </Button>
                            </Form.Item>
                        </Col>
                    </Row>
                </Form>

                <Alert
                    message="Lưu ý bảo mật"
                    description="Thông tin booking của bạn được bảo vệ. Chỉ hiển thị thông tin cơ bản để xác minh danh tính."
                    type="info"
                    showIcon
                    icon={<InfoCircleOutlined />}
                />
            </Card>

            {/* Search Results */}
            {searchMeta && (
                <Card
                    title={
                        <Space>
                            <Title level={4} style={{ margin: 0 }}>
                                Kết quả tìm kiếm
                            </Title>
                            <Badge count={searchMeta.total} color="blue" />
                        </Space>
                    }
                >
                    {bookings.length > 0 ? (
                        <Table
                            columns={columns}
                            dataSource={bookings}
                            rowKey="booking_id"
                            loading={loading}
                            pagination={{
                                current: searchMeta.page,
                                total: searchMeta.total,
                                pageSize: searchMeta.per_page,
                                showSizeChanger: false,
                                showQuickJumper: true,
                                showTotal: (total, range) =>
                                    `${range[0]}-${range[1]} của ${total} booking`,
                            }}
                            onChange={handleTableChange}
                            scroll={{ x: 800 }}
                        />
                    ) : (
                        <Empty
                            description={
                                <Space direction="vertical">
                                    <Text>Không tìm thấy booking nào</Text>
                                    <Text type="secondary">
                                        Vui lòng kiểm tra lại thông tin hoặc liên hệ lễ tân để được hỗ trợ
                                    </Text>
                                </Space>
                            }
                        />
                    )}
                </Card>
            )}

            {/* Detail Drawer */}
            <Drawer
                title={
                    <Space>
                        <InfoCircleOutlined />
                        Chi tiết booking: {selectedBooking?.booking_code}
                    </Space>
                }
                placement="right"
                width={720}
                open={drawerVisible}
                onClose={() => {
                    setDrawerVisible(false);
                    setSelectedBooking(null);
                }}
                loading={detailLoading}
            >
                {selectedBooking && (
                    <div>
                        {/* Basic Info */}
                        <Descriptions
                            title="Thông tin cơ bản"
                            bordered
                            column={1}
                            size="middle"
                        >
                            <Item label="Mã booking">{selectedBooking.booking_code}</Item>
                            <Item label="Trạng thái">
                                <Tag color={getStatusColor(selectedBooking.status)}>
                                    {getStatusText(selectedBooking.status)}
                                </Tag>
                            </Item>
                            <Item label="Tên khách hàng">{selectedBooking.guest_name}</Item>
                            <Item label="Số điện thoại">{selectedBooking.guest_phone}</Item>
                            <Item label="Email">{selectedBooking.guest_email}</Item>
                            <Item label="Ngày nhận phòng">{selectedBooking.check_in_date}</Item>
                            <Item label="Ngày trả phòng">{selectedBooking.check_out_date}</Item>
                            <Item label="Số đêm">
                                <Badge count={selectedBooking.nights} color="blue" />
                            </Item>
                            <Item label="Số khách">{selectedBooking.guest_count} người</Item>
                            <Item label="Tổng tiền">
                                <Text strong style={{ color: '#1890ff', fontSize: '16px' }}>
                                    {selectedBooking.total_price_formatted}
                                </Text>
                            </Item>
                        </Descriptions>

                        <Divider />

                        {/* Rooms Detail */}
                        <Title level={4}>
                            <HomeOutlined /> Chi tiết phòng
                        </Title>
                        {selectedBooking.rooms_detail.map((room, index) => (
                            <Card
                                key={room.id}
                                size="small"
                                title={`Phòng ${index + 1}: ${room.room_name}`}
                                style={{ marginBottom: 16 }}
                            >
                                <Descriptions column={2} size="small">
                                    <Item label="Loại phòng">{room.room_type}</Item>
                                    <Item label="Tầng">{room.floor}</Item>
                                    <Item label="Gói dịch vụ">{room.option_name}</Item>
                                    <Item label="Người lớn">{room.adults}</Item>
                                    <Item label="Trẻ em">{room.children}</Item>
                                    <Item label="Giá/đêm">{room.price_per_night} VNĐ</Item>
                                    <Item label="Số đêm">{room.nights}</Item>
                                    <Item label="Tổng phòng">
                                        <strong>{room.total_price} VNĐ</strong>
                                    </Item>
                                </Descriptions>
                            </Card>
                        ))}

                        <Divider />

                        {/* Representatives */}
                        {selectedBooking.representatives.length > 0 && (
                            <>
                                <Title level={4}>
                                    <UserOutlined /> Người đại diện
                                </Title>
                                {selectedBooking.representatives.map((rep) => (
                                    <Descriptions
                                        key={rep.id}
                                        bordered
                                        size="small"
                                        column={1}
                                        style={{ marginBottom: 16 }}
                                    >
                                        <Item label="Họ tên">{rep.full_name}</Item>
                                        <Item label="Số điện thoại">{rep.phone_number}</Item>
                                        <Item label="Email">{rep.email}</Item>
                                        <Item label="CCCD/Hộ chiếu">{rep.id_card}</Item>
                                    </Descriptions>
                                ))}
                                <Divider />
                            </>
                        )}

                        {/* Payments */}
                        {selectedBooking.payments.length > 0 && (
                            <>
                                <Title level={4}>
                                    <DollarOutlined /> Lịch sử thanh toán
                                </Title>
                                {selectedBooking.payments.map((payment) => (
                                    <Card
                                        key={payment.payment_id}
                                        size="small"
                                        style={{ marginBottom: 8 }}
                                    >
                                        <Descriptions column={2} size="small">
                                            <Item label="Số tiền">{payment.amount_vnd}</Item>
                                            <Item label="Loại">
                                                <Tag>{payment.payment_type}</Tag>
                                            </Item>
                                            <Item label="Trạng thái">
                                                <Tag color={payment.status === 'completed' ? 'green' : 'orange'}>
                                                    {payment.status}
                                                </Tag>
                                            </Item>
                                            <Item label="Thời gian">{payment.created_at}</Item>
                                        </Descriptions>
                                    </Card>
                                ))}
                                <Divider />
                            </>
                        )}

                        {/* Notes */}
                        {selectedBooking.notes && (
                            <>
                                <Title level={4}>Ghi chú</Title>
                                <Card size="small">
                                    <Text>{selectedBooking.notes}</Text>
                                </Card>
                                <Divider />
                            </>
                        )}

                        {/* Timestamps */}
                        <Descriptions size="small" column={1}>
                            <Item label="Thời gian đặt">{selectedBooking.created_at}</Item>
                            <Item label="Cập nhật cuối">{selectedBooking.updated_at}</Item>
                        </Descriptions>
                    </div>
                )}
            </Drawer>
        </div>
    );
};

export default LookupBookingByPhone;
