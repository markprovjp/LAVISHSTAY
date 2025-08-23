import React, { useEffect, useState } from 'react';
import {
    Modal, Typography, Spin, Card, List, Descriptions, Tag, Button, Space,
    Collapse, Alert, Statistic, Row, Col, Progress, Badge, message, InputNumber, Popconfirm
} from 'antd';
import {
    CheckCircleOutlined, ExclamationCircleOutlined, ClockCircleOutlined,
    DollarOutlined, UserOutlined, HomeOutlined, CreditCardOutlined,
    SafetyOutlined, WarningOutlined, GiftOutlined, ToolOutlined
} from '@ant-design/icons';

import { receptionAPI } from '../../../utils/api';
import CompensationRequestModal from '../../../components/CompensationRequestModal';
import { ServicePaymentModal } from '../../../components/ServicePaymentModal';

const { Title, Text } = Typography;
const { Panel } = Collapse;

interface CheckoutInfoModalProps {
    visible: boolean;
    onCancel?: () => void;
    // some parents use `onClose` instead of `onCancel` — accept both
    onClose?: () => void;
    bookingId: number | null;
    onCheckoutComplete?: () => void;
    onAddServicesRequest?: () => void;
}

interface CheckoutData {
    booking_info: {
        booking_id: number;
        booking_code: string;
        status: string;
        check_in_date: string;
        check_out_date: string;
        original_total_price_vnd: number;
        guest_count: number;
        adults: number | null;
        children: number | null;
        notes: string;
    };
    guest_info: {
        guest_name: string;
        guest_email: string;
        guest_phone: string;
        guest_count: number;
        adults: number | null;
        children: number | null;
        children_age: string | null;
    };
    room_info: {
        total_rooms: number;
        rooms: Array<{
            room_id: number;
            room_number: string;
            room_type: string;
            room_type_id: number;
            floor: number | null;
            status: string;
            description: string | null;
        }>;
    };
    hotel_info: {
        hotel_id: number | null;
        hotel_name: string;
        hotel_address: string;
        hotel_phone: string;
        hotel_email: string | null;
    };
    available_services: {
        total_services: number;
        services: Array<{
            service_id: number;
            name: string;
            description: string;
            price_vnd: string;
            unit: string;
            formatted_price: string;
            price_with_unit: string;
        }>;
    };
    booking_services: {
        total_services: number;
        total_service_amount: number;
        services: any[];
    };
    amount_calculation: {
        room_amount: number;
        service_amount: number;
        total_amount: number;
        breakdown: {
            original_booking_amount: number;
            additional_services_amount: number;
            final_total_amount: number;
        };
    };
    payment_status: {
        total_required: number;
        total_paid: string;
        remaining_amount: number;
        is_sufficient: boolean;
        payment_percentage: number;
        payments: Array<{
            payment_id: number;
            amount_vnd: string;
            payment_type: string;
            status: string;
            transaction_id: string;
            created_at: string;
        }>;
        payment_summary: {
            completed_payments: number;
            pending_payments: number;
            failed_payments: number;
        };
    };
    checkout_validation: {
        summary: {
            can_checkout: boolean;
            total_rules_checked: number;
            blocking_failures_count: number;
            warnings_count: number;
            passed_count: number;
            validation_status: string;
        };
        blocking_failures: any[];
        warnings: any[];
        passed_rules: Array<{
            rule_id: string;
            passes: boolean;
            message: string;
            priority: number;
            is_blocking: boolean;
        }>;
    };
    checkout_conditions: {
        payment_sufficient: boolean;
        ready_for_checkout: boolean;
        has_warnings: boolean;
        has_blocking_issues: boolean;
    };
    compensation_policies: {
        total_policies: number;
        policies: Array<{
            compensation_policy_id: number;
            name: string;
            description: string;
            condition_type: string;
            condition_type_label: string;
            discount_type: string;
            discount_type_label: string;
            discount_value: string;
            formatted_discount_value: string;
            max_compensation_amount: string | null;
            applies_to_room_type_id: number | null;
        }>;
    };
    existing_compensation_requests: {
        total_requests: number;
        requests: any[];
    };
    checkout_summary: {
        can_checkout: boolean;
        validation_status: string;
        total_stay_amount: number;
        remaining_payment: number;
        checkout_date: string;
    };
}

const CheckoutInfoModal: React.FC<CheckoutInfoModalProps> = ({
    visible,
    onCancel,
    onClose,
    bookingId,
    onCheckoutComplete,
    onAddServicesRequest
}) => {
    const [loading, setLoading] = useState(false);
    const [checkoutData, setCheckoutData] = useState<CheckoutData | null>(null);
    const [processing, setProcessing] = useState(false);
    const [editingQuantities, setEditingQuantities] = useState<Record<number, number>>({});
    const [compModalVisible, setCompModalVisible] = useState(false);
    const [servicePaymentVisible, setServicePaymentVisible] = useState(false);

    // Safe close handler: prefer onCancel, then onClose, else local fallback
    const handleClose = () => {
        if (typeof onCancel === 'function') return onCancel();
        if (typeof onClose === 'function') return onClose();
        // fallback: clear local data so modal shows empty state — parent still controls visibility
        setCheckoutData(null);
    };

    useEffect(() => {
        if (visible && bookingId) {
            fetchCheckoutInfo();
        }
    }, [visible, bookingId]);

    const fetchCheckoutInfo = async () => {
        setLoading(true);
        try {
            // Use shared receptionAPI to ensure auth and response normalization
            const data = await receptionAPI.getCheckoutInfo(bookingId as number);

            if (data) {
                setCheckoutData(data);
                // initialize quantities map for booking services
                const map: Record<number, number> = {};
                (data.booking_services?.services || []).forEach((s: any) => { map[s.service_id] = s.quantity || 1; });
                setEditingQuantities(map);
            } else {
                message.error('Không thể tải thông tin check-out');
            }
        } catch (error: any) {
            console.error('Error fetching checkout info:', error);
            message.error(error?.message || 'Không thể tải thông tin check-out');
        } finally {
            setLoading(false);
        }
    };

    const handleQuantityChange = (serviceId: number, qty: number) => {
        setEditingQuantities((s) => ({ ...s, [serviceId]: qty }));
    };

    const handleUpdateService = async (serviceId: number) => {
        if (!bookingId) return;
        const qty = editingQuantities[serviceId] || 1;
        setLoading(true);
        try {
            await receptionAPI.updateBookingService(bookingId, serviceId, { quantity: qty });
            message.success('Cập nhật dịch vụ thành công');
            // refresh
            await fetchCheckoutInfo();
        } catch (err) {
            console.error('Update service failed', err);
            message.error('Cập nhật thất bại');
        } finally {
            setLoading(false);
        }
    };

    const handleRemoveService = async (serviceId: number) => {
        if (!bookingId) return;
        setLoading(true);
        try {
            await receptionAPI.removeBookingService(bookingId, serviceId);
            message.success('Xóa dịch vụ thành công');
            await fetchCheckoutInfo();
        } catch (err) {
            console.error('Remove service failed', err);
            message.error('Xóa thất bại');
        } finally {
            setLoading(false);
        }
    };

    const handleCheckout = async () => {
        if (!bookingId || !checkoutData) return;

        setProcessing(true);
        try {
            // Use receptionAPI.processCheckout so auth and server behavior are consistent
            const resp = await receptionAPI.processCheckout(bookingId, {
                send_invoice: true,
                override_warnings: true
            });

            if (resp && resp.success) {
                message.success('Check-out thành công!');
                onCheckoutComplete?.();
                handleClose();
            } else {
                message.error(resp?.message || 'Check-out thất bại');
            }
        } catch (error: any) {
            console.error('Error processing checkout:', error);
            message.error(error?.response?.data?.message || error?.message || 'Check-out thất bại');
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

    const formatDate = (dateStr: string) => {
        return new Date(dateStr).toLocaleDateString('vi-VN', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    const getValidationIcon = (status: string) => {
        switch (status) {
            case 'APPROVED':
                return <CheckCircleOutlined style={{ color: '#52c41a' }} />;
            case 'BLOCKED':
                return <ExclamationCircleOutlined style={{ color: '#ff4d4f' }} />;
            default:
                return <ClockCircleOutlined style={{ color: '#faad14' }} />;
        }
    };

    const getPaymentStatusColor = (percentage: number) => {
        if (percentage >= 100) return '#52c41a';
        if (percentage >= 50) return '#faad14';
        return '#ff4d4f';
    };

    if (loading) {
        return (
            <Modal
                title="Thông tin Check-out"
                open={visible}
                onCancel={onCancel}
                footer={null}
                width={1200}
            >
                <div style={{ textAlign: 'center', padding: '50px' }}>
                    <Spin size="large" />
                    <div style={{ marginTop: 16 }}>Đang tải thông tin check-out...</div>
                </div>
            </Modal>
        );
    }

    if (!checkoutData) {
        return (
            <Modal
                title="Thông tin Check-out"
                open={visible}
                onCancel={onCancel}
                footer={null}
                width={1200}
            >
                <Alert message="Không có dữ liệu" type="warning" />
            </Modal>
        );
    }

    return (
        <>
            <Modal
                title={
                    <Space>
                        <HomeOutlined />
                        <span>Thông tin Check-out - {checkoutData.booking_info.booking_code}</span>
                        <Tag color={checkoutData.checkout_conditions.ready_for_checkout ? 'green' : 'red'}>
                            {checkoutData.checkout_validation.summary.validation_status}
                        </Tag>
                    </Space>
                }
                open={visible}
                onCancel={handleClose}
                width={1400}
                style={{ top: 20 }}
                footer={
                    <Space>
                        <Button onClick={handleClose}>Đóng</Button>
                        <Button onClick={() => onAddServicesRequest?.()}>Thêm dịch vụ</Button>
                        <Button
                            type="default"
                            icon={<DollarOutlined />}
                            onClick={() => setServicePaymentVisible(true)}
                        >
                            Thanh toán dịch vụ phát sinh
                        </Button>
                        <Button onClick={() => setCompModalVisible(true)}>Tạo yêu cầu bồi thường</Button>
                        <Button
                            type="primary"
                            loading={processing}
                            disabled={!checkoutData.checkout_conditions.ready_for_checkout}
                            onClick={handleCheckout}
                            icon={<CheckCircleOutlined />}
                        >
                            Thực hiện Check-out
                        </Button>
                    </Space>
                }
            >
                <div style={{ maxHeight: '90vh', overflowY: 'auto' }}>
                    {/* Header Summary */}
                    <Row gutter={[16, 16]} style={{ marginBottom: 24 }}>
                        <Col span={6}>
                            <Card size="small">
                                <Statistic
                                    title="Tổng tiền"
                                    value={checkoutData.amount_calculation.total_amount}
                                    formatter={(value) => formatCurrency(Number(value))}
                                    prefix={<DollarOutlined />}
                                />
                            </Card>
                        </Col>
                        <Col span={6}>
                            <Card size="small">
                                <Statistic
                                    title="Thanh toán"
                                    value={checkoutData.payment_status.payment_percentage}
                                    suffix="%"
                                    prefix={<CreditCardOutlined />}
                                />
                                <Progress
                                    percent={checkoutData.payment_status.payment_percentage}
                                    strokeColor={getPaymentStatusColor(checkoutData.payment_status.payment_percentage)}
                                    showInfo={false}
                                    size="small"
                                />
                            </Card>
                        </Col>
                        <Col span={6}>
                            <Card size="small">
                                <Statistic
                                    title="Số phòng"
                                    value={checkoutData.room_info.total_rooms}
                                    prefix={<HomeOutlined />}
                                />
                            </Card>
                        </Col>
                        <Col span={6}>
                            <Card size="small">
                                <Statistic
                                    title="Validation"
                                    value={checkoutData.checkout_validation.summary.passed_count}
                                    suffix={`/${checkoutData.checkout_validation.summary.total_rules_checked}`}
                                    prefix={getValidationIcon(checkoutData.checkout_validation.summary.validation_status)}
                                />
                            </Card>
                        </Col>
                    </Row>

                    {/* Main Content */}
                    <Collapse defaultActiveKey={['booking', 'payment', 'validation']} ghost>
                        {/* Booking & Guest Info */}
                        <Panel
                            header={
                                <Space>
                                    <UserOutlined />
                                    <span style={{ fontWeight: 600 }}>Thông tin đặt phòng & khách hàng</span>
                                </Space>
                            }
                            key="booking"
                        >
                            <Row gutter={[24, 16]}>
                                <Col span={12}>
                                    <Descriptions title="Thông tin đặt phòng" bordered size="small" column={1}>
                                        <Descriptions.Item label="Mã đặt phòng">
                                            <Text strong>{checkoutData.booking_info.booking_code}</Text>
                                        </Descriptions.Item>
                                        <Descriptions.Item label="Trạng thái">
                                            <Tag color="blue">{checkoutData.booking_info.status}</Tag>
                                        </Descriptions.Item>
                                        <Descriptions.Item label="Check-in">
                                            {formatDate(checkoutData.booking_info.check_in_date)}
                                        </Descriptions.Item>
                                        <Descriptions.Item label="Check-out">
                                            {formatDate(checkoutData.booking_info.check_out_date)}
                                        </Descriptions.Item>
                                        <Descriptions.Item label="Số khách">
                                            <Badge count={checkoutData.booking_info.guest_count} showZero color="blue" />
                                        </Descriptions.Item>
                                    </Descriptions>
                                </Col>
                                <Col span={12}>
                                    <Descriptions title="Thông tin khách hàng" bordered size="small" column={1}>
                                        <Descriptions.Item label="Tên khách hàng">
                                            <Text strong>{checkoutData.guest_info.guest_name}</Text>
                                        </Descriptions.Item>
                                        <Descriptions.Item label="Email">
                                            {checkoutData.guest_info.guest_email}
                                        </Descriptions.Item>
                                        <Descriptions.Item label="Điện thoại">
                                            {checkoutData.guest_info.guest_phone}
                                        </Descriptions.Item>
                                        <Descriptions.Item label="Số người lớn">
                                            {checkoutData.guest_info.adults || 'Chưa xác định'}
                                        </Descriptions.Item>
                                        <Descriptions.Item label="Số trẻ em">
                                            {checkoutData.guest_info.children || 'Không có'}
                                        </Descriptions.Item>
                                    </Descriptions>
                                </Col>
                            </Row>

                            {checkoutData.booking_info.notes && (
                                <div style={{ marginTop: 16 }}>
                                    <Text strong>Ghi chú:</Text>
                                    <div style={{ background: '#fafafa', padding: 8, borderRadius: 4, marginTop: 4 }}>
                                        <Text>{checkoutData.booking_info.notes}</Text>
                                    </div>
                                </div>
                            )}
                        </Panel>

                        {/* Room Information */}
                        <Panel
                            header={
                                <Space>
                                    <HomeOutlined />
                                    <span style={{ fontWeight: 600 }}>Thông tin phòng ({checkoutData.room_info.total_rooms} phòng)</span>
                                </Space>
                            }
                            key="rooms"
                        >
                            <List
                                grid={{ gutter: 16, column: 3 }}
                                dataSource={checkoutData.room_info.rooms}
                                renderItem={(room) => (
                                    <List.Item>
                                        <Card size="small" title={`Phòng ${room.room_number}`}>
                                            <p><Text strong>Loại phòng:</Text> {room.room_type}</p>
                                            <p><Text strong>Trạng thái:</Text> <Tag color="green">{room.status}</Tag></p>
                                            {room.floor && <p><Text strong>Tầng:</Text> {room.floor}</p>}
                                        </Card>
                                    </List.Item>
                                )}
                            />
                        </Panel>

                        {/* Payment Status */}
                        <Panel
                            header={
                                <Space>
                                    <CreditCardOutlined />
                                    <span style={{ fontWeight: 600 }}>Trạng thái thanh toán</span>
                                    <Tag color={checkoutData.payment_status.is_sufficient ? 'green' : 'red'}>
                                        {checkoutData.payment_status.is_sufficient ? 'Đủ' : 'Thiếu'}
                                    </Tag>
                                </Space>
                            }
                            key="payment"
                        >
                            <Row gutter={[24, 16]}>
                                <Col span={12}>
                                    <Descriptions title="Tổng quan thanh toán" bordered size="small" column={1}>
                                        <Descriptions.Item label="Tổng cần thanh toán">
                                            <Text strong>{formatCurrency(checkoutData.payment_status.total_required)}</Text>
                                        </Descriptions.Item>
                                        <Descriptions.Item label="Đã thanh toán">
                                            <Text strong style={{ color: '#52c41a' }}>
                                                {formatCurrency(Number(checkoutData.payment_status.total_paid))}
                                            </Text>
                                        </Descriptions.Item>
                                        <Descriptions.Item label="Còn lại">
                                            <Text strong style={{ color: checkoutData.payment_status.remaining_amount > 0 ? '#ff4d4f' : '#52c41a' }}>
                                                {formatCurrency(checkoutData.payment_status.remaining_amount)}
                                            </Text>
                                        </Descriptions.Item>
                                        <Descriptions.Item label="Tỷ lệ hoàn thành">
                                            <Progress
                                                percent={checkoutData.payment_status.payment_percentage}
                                                strokeColor={getPaymentStatusColor(checkoutData.payment_status.payment_percentage)}
                                            />
                                        </Descriptions.Item>
                                    </Descriptions>
                                </Col>
                                <Col span={12}>
                                    <Descriptions title="Chi tiết thanh toán" bordered size="small" column={1}>
                                        <Descriptions.Item label="Số giao dịch hoàn thành">
                                            <Badge count={checkoutData.payment_status.payment_summary.completed_payments} showZero color="green" />
                                        </Descriptions.Item>
                                        <Descriptions.Item label="Số giao dịch chờ xử lý">
                                            <Badge count={checkoutData.payment_status.payment_summary.pending_payments} showZero color="orange" />
                                        </Descriptions.Item>
                                        <Descriptions.Item label="Số giao dịch thất bại">
                                            <Badge count={checkoutData.payment_status.payment_summary.failed_payments} showZero color="red" />
                                        </Descriptions.Item>
                                    </Descriptions>
                                </Col>
                            </Row>

                            {checkoutData.payment_status.payments.length > 0 && (
                                <div style={{ marginTop: 16 }}>
                                    <Title level={5}>Lịch sử thanh toán</Title>
                                    <List
                                        dataSource={checkoutData.payment_status.payments}
                                        renderItem={(payment) => (
                                            <List.Item>
                                                <List.Item.Meta
                                                    title={
                                                        <Space>
                                                            <Text strong>{formatCurrency(Number(payment.amount_vnd))}</Text>
                                                            <Tag color={payment.status === 'completed' ? 'green' : 'orange'}>
                                                                {payment.status}
                                                            </Tag>
                                                        </Space>
                                                    }
                                                    description={
                                                        <Space direction="vertical" size="small">
                                                            <Text>Phương thức: {payment.payment_type}</Text>
                                                            <Text>Mã GD: {payment.transaction_id}</Text>
                                                            <Text>Thời gian: {formatDate(payment.created_at)}</Text>
                                                        </Space>
                                                    }
                                                />
                                            </List.Item>
                                        )}
                                    />
                                </div>
                            )}
                        </Panel>

                        {/* Validation Rules */}
                        <Panel
                            header={
                                <Space>
                                    <SafetyOutlined />
                                    <span style={{ fontWeight: 600 }}>Kiểm tra điều kiện check-out</span>
                                    {getValidationIcon(checkoutData.checkout_validation.summary.validation_status)}
                                </Space>
                            }
                            key="validation"
                        >
                            <Row gutter={[24, 16]}>
                                <Col span={8}>
                                    <Card size="small" title="Tổng quan">
                                        <Statistic
                                            title="Đã kiểm tra"
                                            value={checkoutData.checkout_validation.summary.total_rules_checked}
                                            suffix="quy tắc"
                                        />
                                    </Card>
                                </Col>
                                <Col span={8}>
                                    <Card size="small" title="Đạt yêu cầu">
                                        <Statistic
                                            title="Thành công"
                                            value={checkoutData.checkout_validation.summary.passed_count}
                                            valueStyle={{ color: '#52c41a' }}
                                        />
                                    </Card>
                                </Col>
                                <Col span={8}>
                                    <Card size="small" title="Cảnh báo/Lỗi">
                                        <Statistic
                                            title="Vấn đề"
                                            value={checkoutData.checkout_validation.summary.warnings_count + checkoutData.checkout_validation.summary.blocking_failures_count}
                                            valueStyle={{ color: '#ff4d4f' }}
                                        />
                                    </Card>
                                </Col>
                            </Row>

                            {checkoutData.checkout_validation.blocking_failures.length > 0 && (
                                <Alert
                                    message="Có lỗi chặn check-out"
                                    description={
                                        <List
                                            size="small"
                                            dataSource={checkoutData.checkout_validation.blocking_failures}
                                            renderItem={(item: any) => (
                                                <List.Item>
                                                    <ExclamationCircleOutlined style={{ color: '#ff4d4f' }} />
                                                    {item.message}
                                                </List.Item>
                                            )}
                                        />
                                    }
                                    type="error"
                                    style={{ marginTop: 16 }}
                                />
                            )}

                            {checkoutData.checkout_validation.warnings.length > 0 && (
                                <Alert
                                    message="Cảnh báo"
                                    description={
                                        <List
                                            size="small"
                                            dataSource={checkoutData.checkout_validation.warnings}
                                            renderItem={(item: any) => (
                                                <List.Item>
                                                    <WarningOutlined style={{ color: '#faad14' }} />
                                                    {item.message}
                                                </List.Item>
                                            )}
                                        />
                                    }
                                    type="warning"
                                    style={{ marginTop: 16 }}
                                />
                            )}

                            <div style={{ marginTop: 16 }}>
                                <Title level={5}>Chi tiết kiểm tra</Title>
                                <List
                                    size="small"
                                    dataSource={checkoutData.checkout_validation.passed_rules}
                                    renderItem={(rule) => (
                                        <List.Item>
                                            <List.Item.Meta
                                                avatar={<CheckCircleOutlined style={{ color: '#52c41a' }} />}
                                                title={rule.rule_id}
                                                description={rule.message}
                                            />
                                            <Tag color={rule.is_blocking ? 'red' : 'blue'}>
                                                {rule.is_blocking ? 'Bắt buộc' : 'Khuyến nghị'}
                                            </Tag>
                                        </List.Item>
                                    )}
                                />
                            </div>
                        </Panel>

                        {/* Services (Secondary) */}
                        <Panel
                            header={
                                <Space>
                                    <ToolOutlined />
                                    <span>Dịch vụ khả dụng ({checkoutData.available_services.total_services})</span>
                                </Space>
                            }
                            key="services"
                        >
                            <List
                                grid={{ gutter: 16, column: 2 }}
                                dataSource={checkoutData.available_services.services}
                                renderItem={(service) => (
                                    <List.Item>
                                        <Card size="small" title={service.name}>
                                            <p>{service.description}</p>
                                            <Text strong>{service.price_with_unit}</Text>
                                        </Card>
                                    </List.Item>
                                )}
                            />
                        </Panel>

                        {/* Confirmed booking services (editable) */}
                        <Panel
                            header={
                                <Space>
                                    <ToolOutlined />
                                    <span>Dịch vụ đã xác nhận ({checkoutData.booking_services.total_services})</span>
                                </Space>
                            }
                            key="booking_services"
                        >
                            <List
                                dataSource={checkoutData.booking_services.services}
                                renderItem={(item: any) => (
                                    <List.Item>
                                        <List.Item.Meta
                                            title={<Text strong>{item.service_name || item.name}</Text>}
                                            description={<Text type="secondary">{item.service_description || item.description}</Text>}
                                        />
                                        <div style={{ marginLeft: 'auto', display: 'flex', alignItems: 'center', gap: 12 }}>
                                            <div style={{ minWidth: 120, textAlign: 'right' }}>
                                                <Text>{new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.unit_price_vnd ? Number(item.unit_price_vnd) : 0)}</Text>
                                            </div>

                                            <div>
                                                <InputNumber size="middle" style={{ width: 96 }} min={1} value={editingQuantities[item.service_id]} onChange={(v) => handleQuantityChange(item.service_id, Number(v || 0))} />
                                            </div>

                                            <Space>
                                                <Button size="small" type="primary" onClick={() => handleUpdateService(item.service_id)}>Cập nhật</Button>
                                                <Popconfirm title="Xóa dịch vụ này?" onConfirm={() => handleRemoveService(item.service_id)} okText="Xóa" cancelText="Hủy">
                                                    <Button danger size="small">Xóa</Button>
                                                </Popconfirm>
                                            </Space>
                                        </div>
                                    </List.Item>
                                )}
                            />
                        </Panel>

                        {/* Compensation Policies */}
                        {checkoutData.compensation_policies.total_policies > 0 && (
                            <Panel
                                header={
                                    <Space>
                                        <GiftOutlined />
                                        <span>Chính sách bồi thường ({checkoutData.compensation_policies.total_policies})</span>
                                    </Space>
                                }
                                key="compensation"
                            >
                                <List
                                    dataSource={checkoutData.compensation_policies.policies}
                                    renderItem={(policy) => (
                                        <List.Item>
                                            <List.Item.Meta
                                                title={
                                                    <Space>
                                                        <Text strong>{policy.name}</Text>
                                                        <Tag color="blue">{policy.condition_type_label}</Tag>
                                                        <Tag color="green">{policy.formatted_discount_value}</Tag>
                                                    </Space>
                                                }
                                                description={policy.description}
                                            />
                                        </List.Item>
                                    )}
                                />
                            </Panel>
                        )}
                    </Collapse>
                </div>
            </Modal>
            <CompensationRequestModal
                visible={compModalVisible}
                bookingId={checkoutData.booking_info.booking_id}
                policies={checkoutData.compensation_policies.policies}
                onClose={() => setCompModalVisible(false)}
                onSuccess={() => fetchCheckoutInfo()}
            />

            <ServicePaymentModal
                bookingId={checkoutData.booking_info.booking_id}
                bookingCode={checkoutData.booking_info.booking_code}
                visible={servicePaymentVisible}
                onClose={() => setServicePaymentVisible(false)}
                onSuccess={() => {
                    // Refresh checkout info after successful payment
                    fetchCheckoutInfo();
                    setServicePaymentVisible(false);
                }}
            />
        </>
    );
};

export default CheckoutInfoModal;
