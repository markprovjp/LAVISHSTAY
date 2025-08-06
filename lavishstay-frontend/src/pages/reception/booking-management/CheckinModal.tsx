import React, { useState, useEffect } from 'react';
import {
    Modal,
    Descriptions,
    Alert,
    Tag,
    Button,
    Checkbox,
    Spin,
    message,
    Typography,
    Space,
    List,
    Card,
    Flex,
    Avatar,
    Statistic,
    Timeline,
    Table
} from 'antd';
import {
    UserOutlined,
    PhoneOutlined,
    MailOutlined,
    CalendarOutlined,
    TeamOutlined,
    HomeOutlined,
    CheckCircleOutlined,
    ExclamationCircleOutlined,
    ClockCircleOutlined,
    InfoCircleOutlined
} from '@ant-design/icons';
import dayjs from 'dayjs';
import { receptionAPI } from '../../../utils/api';

const { Title, Text } = Typography;

interface CheckinModalProps {
    bookingId: number | null;
    visible: boolean;
    onClose: () => void;
    onSuccess?: () => void;
}

interface CheckinInfo {
    booking_id: number;
    booking_code: string;
    guest_name: string;
    guest_email: string;
    guest_phone: string;
    check_in_date: string;
    check_out_date: string;
    status: string;
    total_price_vnd: number;
    guest_count: number;
    adults: number | null;
    children: number | null;
    children_age: number[] | null;
    notes: string | null;
    room_info: {
        total_rooms: number;
        rooms: any[];
    };
    payment_status: {
        total_required: number;
        total_paid: number;
        remaining_amount: number;
        is_sufficient: boolean;
        payment_percentage: number;
        payments: any[];
        payment_summary: {
            completed_payments: number;
            pending_payments: number;
            failed_payments: number;
        };
    };
    rooms_assigned: {
        has_rooms: boolean;
        assigned_rooms_count: number;
        required_rooms_count: number;
        room_details: any[];
    };
    documents_verified: {
        is_verified: boolean;
        verification_details: {
            has_name: boolean;
            has_email: boolean;
            has_phone: boolean;
        };
        required_documents: Record<string, string>;
        verification_status: string;
    };
    early_checkin_info: {
        is_early: boolean;
        has_fee: boolean;
        fee_amount: number;
        policy_id: number;
        policy_name: string;
        standard_time: string;
        actual_time: string;
        time_difference_minutes: number;
    };
    applicable_policies: any[];
    ready_for_checkin: boolean;
    warnings: Array<{
        type: string;
        message: string;
        details: string;
    }>;
    requirements: {
        payment_required: boolean;
        room_assignment_required: boolean;
        document_verification_required: boolean;
        early_checkin_fee_may_apply: boolean;
        policies: any[];
    };
}

const CheckinModal: React.FC<CheckinModalProps> = ({
    bookingId,
    visible,
    onClose,
    onSuccess
}) => {
    const [loading, setLoading] = useState(false);
    const [processing, setProcessing] = useState(false);
    const [checkinInfo, setCheckinInfo] = useState<CheckinInfo | null>(null);
    const [error, setError] = useState<string | null>(null);
    const [earlyCheckinFeeAccepted, setEarlyCheckinFeeAccepted] = useState(false);

    useEffect(() => {
        if (visible && bookingId) {
            fetchCheckinInfo();
        }
    }, [visible, bookingId]);

    const fetchCheckinInfo = async () => {
        if (!bookingId) return;

        setLoading(true);
        setError(null);
        try {
            const response = await receptionAPI.getCheckinInfo(bookingId);
            if (response.success) {
                setCheckinInfo(response.data);
            } else {
                setError(response.message || 'Không thể lấy thông tin check-in');
            }
        } catch (err: any) {
            console.error('Error fetching checkin info:', err);
            setError(err.response?.data?.error || err.message || 'Có lỗi xảy ra khi lấy thông tin check-in');
        } finally {
            setLoading(false);
        }
    };

    const handleCheckin = async () => {
        if (!bookingId || !checkinInfo) return;

        setProcessing(true);
        try {
            const payload: any = {};

            // Nếu có phí early check-in và user đã đồng ý
            if (checkinInfo.early_checkin_info.has_fee && earlyCheckinFeeAccepted) {
                payload.early_checkin_fee_accepted = true;
            }

            const response = await receptionAPI.processCheckin(bookingId, payload);

            if (response.success) {
                message.success('Check-in thành công!');
                onSuccess?.();
                onClose();
            } else {
                message.error(response.message || 'Check-in thất bại');
            }
        } catch (err: any) {
            console.error('Error processing checkin:', err);
            const errorMessage = err.response?.data?.error || err.message || 'Có lỗi xảy ra khi check-in';
            message.error(errorMessage);
        } finally {
            setProcessing(false);
        }
    };

    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    };

    const getStatusColor = (status: string) => {
        const statusMap: Record<string, string> = {
            'pending': 'orange',
            'confirmed': 'blue',
            'operational': 'green',
            'completed': 'purple',
            'cancelled': 'red'
        };
        return statusMap[status.toLowerCase()] || 'default';
    };

    const renderBasicInfo = () => {
        if (!checkinInfo) return null;

        return (
            <Card title="Thông tin đặt phòng" size="small" style={{ marginBottom: 16 }}>
                <Descriptions column={2} size="small">
                    <Descriptions.Item label="Mã đặt phòng">
                        <Text strong copyable>{checkinInfo.booking_code}</Text>
                    </Descriptions.Item>
                    <Descriptions.Item label="Trạng thái">
                        <Tag color={getStatusColor(checkinInfo.status)}>{checkinInfo.status}</Tag>
                    </Descriptions.Item>
                    <Descriptions.Item label="Tổng tiền">
                        <Text strong style={{ color: '#f5222d' }}>
                            {formatCurrency(checkinInfo.total_price_vnd)}
                        </Text>
                    </Descriptions.Item>
                    <Descriptions.Item label="Số khách">
                        <Space>
                            <TeamOutlined />
                            <Text>{checkinInfo.guest_count} khách</Text>
                            {checkinInfo.adults && (
                                <Text type="secondary">({checkinInfo.adults} người lớn{checkinInfo.children ? `, ${checkinInfo.children} trẻ em` : ''})</Text>
                            )}
                        </Space>
                    </Descriptions.Item>
                    <Descriptions.Item label="Check-in">
                        <Space>
                            <CalendarOutlined />
                            <Text>{dayjs(checkinInfo.check_in_date).format('DD/MM/YYYY HH:mm')}</Text>
                        </Space>
                    </Descriptions.Item>
                    <Descriptions.Item label="Check-out">
                        <Space>
                            <CalendarOutlined />
                            <Text>{dayjs(checkinInfo.check_out_date).format('DD/MM/YYYY HH:mm')}</Text>
                        </Space>
                    </Descriptions.Item>
                </Descriptions>

                {checkinInfo.notes && (
                    <Alert
                        message="Ghi chú đặt phòng"
                        description={checkinInfo.notes}
                        type="info"
                        showIcon
                        style={{ marginTop: 12 }}
                    />
                )}
            </Card>
        );
    };

    const renderGuestInfo = () => {
        if (!checkinInfo) return null;

        return (
            <Card title="Thông tin khách hàng" size="small" style={{ marginBottom: 16 }}>
                <Flex align="center" gap="middle">
                    <Avatar size={64} icon={<UserOutlined />} style={{ backgroundColor: '#1890ff' }} />
                    <Flex vertical flex={1}>
                        <Title level={4} style={{ margin: 0 }}>{checkinInfo.guest_name}</Title>
                        <Space direction="vertical" size={4}>
                            <Space>
                                <PhoneOutlined style={{ color: '#52c41a' }} />
                                <Text copyable>{checkinInfo.guest_phone}</Text>
                            </Space>
                            <Space>
                                <MailOutlined style={{ color: '#1890ff' }} />
                                <Text copyable>{checkinInfo.guest_email}</Text>
                            </Space>
                        </Space>
                    </Flex>
                </Flex>
            </Card>
        );
    };

    const renderConditionsStatus = () => {
        if (!checkinInfo) return null;

        const conditions = [
            {
                title: 'Thanh toán',
                status: checkinInfo.payment_status.is_sufficient,
                details: `${formatCurrency(checkinInfo.payment_status.total_paid)} / ${formatCurrency(checkinInfo.payment_status.total_required)}`,
                extra: checkinInfo.payment_status.remaining_amount > 0 ? `Còn thiếu: ${formatCurrency(checkinInfo.payment_status.remaining_amount)}` : null
            },
            {
                title: 'Gán phòng',
                status: checkinInfo.rooms_assigned.has_rooms,
                details: `${checkinInfo.rooms_assigned.assigned_rooms_count} / ${checkinInfo.rooms_assigned.required_rooms_count} phòng`,
                extra: null
            },
            {
                title: 'Xác minh giấy tờ',
                status: checkinInfo.documents_verified.is_verified,
                details: checkinInfo.documents_verified.verification_status,
                extra: null
            }
        ];

        return (
            <Card title="Điều kiện check-in" size="small" style={{ marginBottom: 16 }}>
                <List
                    dataSource={conditions}
                    renderItem={(item) => (
                        <List.Item>
                            <List.Item.Meta
                                avatar={
                                    <Avatar
                                        size="small"
                                        style={{
                                            backgroundColor: item.status ? '#52c41a' : '#ff4d4f',
                                            color: 'white'
                                        }}
                                        icon={item.status ? <CheckCircleOutlined /> : <ExclamationCircleOutlined />}
                                    />
                                }
                                title={item.title}
                                description={
                                    <Space direction="vertical" size={0}>
                                        <Text>{item.details}</Text>
                                        {item.extra && <Text type="secondary">{item.extra}</Text>}
                                    </Space>
                                }
                            />
                        </List.Item>
                    )}
                />
            </Card>
        );
    };

    const renderPaymentDetails = () => {
        if (!checkinInfo) return null;

        const columns = [
            {
                title: 'Mã thanh toán',
                dataIndex: 'payment_id',
                key: 'payment_id'
            },
            {
                title: 'Số tiền',
                dataIndex: 'amount_vnd',
                key: 'amount_vnd',
                render: (amount: string) => formatCurrency(parseFloat(amount))
            },
            {
                title: 'Loại',
                dataIndex: 'payment_type',
                key: 'payment_type',
                render: (type: string) => <Tag>{type.toUpperCase()}</Tag>
            },
            {
                title: 'Trạng thái',
                dataIndex: 'status',
                key: 'status',
                render: (status: string) => (
                    <Tag color={status === 'completed' ? 'green' : status === 'pending' ? 'orange' : 'red'}>
                        {status}
                    </Tag>
                )
            }
        ];

        return (
            <Card title="Chi tiết thanh toán" size="small" style={{ marginBottom: 16 }}>
                <Space direction="vertical" style={{ width: '100%' }}>
                    <Flex justify="space-between">
                        <Statistic
                            title="Hoàn thành"
                            value={checkinInfo.payment_status.payment_summary.completed_payments}
                            valueStyle={{ color: '#52c41a' }}
                        />
                        <Statistic
                            title="Đang chờ"
                            value={checkinInfo.payment_status.payment_summary.pending_payments}
                            valueStyle={{ color: '#faad14' }}
                        />
                        <Statistic
                            title="Thất bại"
                            value={checkinInfo.payment_status.payment_summary.failed_payments}
                            valueStyle={{ color: '#ff4d4f' }}
                        />
                        <Statistic
                            title="Tỷ lệ thanh toán"
                            value={checkinInfo.payment_status.payment_percentage}
                            suffix="%"
                            valueStyle={{ color: checkinInfo.payment_status.is_sufficient ? '#52c41a' : '#ff4d4f' }}
                        />
                    </Flex>

                    {checkinInfo.payment_status.payments.length > 0 && (
                        <Table
                            dataSource={checkinInfo.payment_status.payments}
                            columns={columns}
                            size="small"
                            pagination={false}
                            rowKey="payment_id"
                        />
                    )}
                </Space>
            </Card>
        );
    };

    const renderRoomInfo = () => {
        if (!checkinInfo) return null;

        return (
            <Card title="Thông tin phòng" size="small" style={{ marginBottom: 16 }}>
                {checkinInfo.room_info.total_rooms > 0 ? (
                    <List
                        dataSource={checkinInfo.room_info.rooms}
                        renderItem={(room: any) => (
                            <List.Item>
                                <List.Item.Meta
                                    avatar={<Avatar icon={<HomeOutlined />} style={{ backgroundColor: '#52c41a' }} />}
                                    title={room.room_name || room.name}
                                    description={room.room_type || room.type}
                                />
                            </List.Item>
                        )}
                    />
                ) : (
                    <Alert
                        message="Chưa có phòng được gán"
                        description="Cần gán phòng trước khi check-in"
                        type="warning"
                        showIcon
                    />
                )}
            </Card>
        );
    };

    const renderEarlyCheckinInfo = () => {
        if (!checkinInfo || !checkinInfo.early_checkin_info.is_early) return null;

        return (
            <Card title="Thông tin check-in sớm" size="small" style={{ marginBottom: 16 }}>
                <Alert
                    message={`Check-in sớm - ${checkinInfo.early_checkin_info.policy_name}`}
                    description={
                        <Space direction="vertical">
                            <Text>Thời gian chuẩn: {checkinInfo.early_checkin_info.standard_time}</Text>
                            <Text>Thời gian thực tế: {checkinInfo.early_checkin_info.actual_time}</Text>
                            {checkinInfo.early_checkin_info.has_fee && (
                                <Text strong style={{ color: '#f5222d' }}>
                                    Phí check-in sớm: {formatCurrency(checkinInfo.early_checkin_info.fee_amount)}
                                </Text>
                            )}
                        </Space>
                    }
                    type={checkinInfo.early_checkin_info.has_fee ? "warning" : "info"}
                    showIcon
                />

                {checkinInfo.early_checkin_info.has_fee && (
                    <Checkbox
                        checked={earlyCheckinFeeAccepted}
                        onChange={(e) => setEarlyCheckinFeeAccepted(e.target.checked)}
                        style={{ marginTop: 12 }}
                    >
                        Tôi đồng ý thanh toán phí check-in sớm: {formatCurrency(checkinInfo.early_checkin_info.fee_amount)}
                    </Checkbox>
                )}
            </Card>
        );
    };

    const renderWarnings = () => {
        if (!checkinInfo || checkinInfo.warnings.length === 0) return null;

        return (
            <Card title="Cảnh báo" size="small" style={{ marginBottom: 16 }}>
                {checkinInfo.warnings.map((warning, index) => (
                    <Alert
                        key={index}
                        message={warning.message}
                        description={warning.details}
                        type="error"
                        showIcon
                        style={{ marginBottom: index < checkinInfo.warnings.length - 1 ? 8 : 0 }}
                    />
                ))}
            </Card>
        );
    };

    const renderPolicies = () => {
        if (!checkinInfo || checkinInfo.applicable_policies.length === 0) return null;

        return (
            <Card title="Chính sách áp dụng" size="small" style={{ marginBottom: 16 }}>
                <Timeline
                    items={checkinInfo.applicable_policies.slice(0, 5).map((policy: any) => ({
                        dot: <InfoCircleOutlined style={{ color: '#1890ff' }} />,
                        children: (
                            <Space direction="vertical" size={2}>
                                <Text strong>{policy.name}</Text>
                                <Text type="secondary">{policy.description}</Text>
                                <Text style={{ fontSize: '12px', color: '#666' }}>{policy.action}</Text>
                            </Space>
                        )
                    }))}
                />
            </Card>
        );
    };

    const canCheckin = () => {
        if (!checkinInfo) return false;

        // Kiểm tra điều kiện cơ bản
        if (!checkinInfo.ready_for_checkin) return false;

        // Nếu có phí early check-in thì phải đồng ý
        if (checkinInfo.early_checkin_info.has_fee && !earlyCheckinFeeAccepted) {
            return false;
        }

        return true;
    };

    return (
        <Modal
            title={
                <Space>
                    <ClockCircleOutlined />
                    <span>Check-in Booking</span>
                    {checkinInfo && <Text type="secondary">({checkinInfo.booking_code})</Text>}
                </Space>
            }
            open={visible}
            onCancel={onClose}
            width={800}
            footer={[
                <Button key="cancel" onClick={onClose}>
                    Đóng
                </Button>,
                <Button
                    key="checkin"
                    type="primary"
                    loading={processing}
                    disabled={!canCheckin()}
                    onClick={handleCheckin}
                    icon={<CheckCircleOutlined />}
                >
                    Xác nhận Check-in
                </Button>
            ]}
            destroyOnClose
        >
            <Spin spinning={loading}>
                {error ? (
                    <Alert
                        message="Lỗi"
                        description={error}
                        type="error"
                        showIcon
                        action={
                            <Button size="small" onClick={fetchCheckinInfo}>
                                Thử lại
                            </Button>
                        }
                    />
                ) : checkinInfo ? (
                    <Space direction="vertical" style={{ width: '100%' }}>
                        {renderBasicInfo()}
                        {renderGuestInfo()}
                        {renderConditionsStatus()}
                        {renderWarnings()}
                        {renderEarlyCheckinInfo()}
                        {renderRoomInfo()}
                        {renderPaymentDetails()}
                        {renderPolicies()}
                    </Space>
                ) : null}
            </Spin>
        </Modal>
    );
};

export default CheckinModal;
