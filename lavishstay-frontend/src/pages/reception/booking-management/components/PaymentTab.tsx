
import React from 'react';
import {
    Card,
    Row,
    Col,
    Statistic,
    Typography,
} from 'antd';
import dayjs from 'dayjs';

const { Text } = Typography;

interface Payment {
    amount_vnd: number;
    payment_type: string;
    status: string;
    transaction_id: string;
    created_at: string;
}

interface StatusBadgeProps {
    status: string;
    type: 'booking' | 'payment' | 'room';
}

const StatusBadge: React.FC<StatusBadgeProps> = ({ status, type }) => {
    const getConfig = () => {
        if (type === 'booking') {
            const configs = {
                pending: { color: '#fa8c16', bg: '#fff7e6', text: 'Chờ xác nhận' },
                confirmed: { color: '#1890ff', bg: '#e6f7ff', text: 'Đã xác nhận' },
                completed: { color: '#52c41a', bg: '#f6ffed', text: 'Hoàn thành' },
                cancelled: { color: '#ff4d4f', bg: '#fff2f0', text: 'Đã hủy' },
            };
            return configs[status as keyof typeof configs] || configs.pending;
        }
        if (type === 'payment') {
            const configs = {
                pending: { color: '#fa8c16', bg: '#fff7e6', text: 'Chờ thanh toán' },
                completed: { color: '#52c41a', bg: '#f6ffed', text: 'Đã thanh toán' },
                failed: { color: '#ff4d4f', bg: '#fff2f0', text: 'Thất bại' },
                refunded: { color: '#722ed1', bg: '#f9f0ff', text: 'Đã hoàn tiền' },
            };
            return configs[status as keyof typeof configs] || configs.pending;
        }
        const configs = {
            available: { color: '#52c41a', bg: '#f6ffed', text: 'Trống' },
            occupied: { color: '#ff4d4f', bg: '#fff2f0', text: 'Có khách' },
            cleaning: { color: '#1890ff', bg: '#e6f7ff', text: 'Dọn dẹp' },
            maintenance: { color: '#fa8c16', bg: '#fff7e6', text: 'Bảo trì' },
            deposited: { color: '#722ed1', bg: '#f9f0ff', text: 'Đã cọc' },
            no_show: { color: '#ff7875', bg: '#fff2f0', text: 'Không đến' },
            check_in: { color: '#13c2c2', bg: '#e6fffb', text: 'Đang nhận' },
            check_out: { color: '#a0d911', bg: '#fcffe6', text: 'Đang trả' },
        };
        return configs[status as keyof typeof configs] || configs.available;
    };

    const config = getConfig();
    return (
        <span
            style={{
                padding: '4px 8px',
                borderRadius: '6px',
                fontSize: '12px',
                fontWeight: 500,
                color: config.color,
                backgroundColor: config.bg,
                border: `1px solid ${config.color}20`,
            }}
        >
            {config.text}
        </span>
    );
};

interface PaymentTabProps {
    payment: Payment | null;
}

const PaymentTab: React.FC<PaymentTabProps> = ({ payment }) => {
    return (
        <Card
            style={{
                borderRadius: '12px',
                boxShadow: '0 2px 8px 0 rgba(0, 0, 0, 0.04)',
                border: '1px solid #f0f0f0'
            }}
            bodyStyle={{ padding: '20px' }}
        >
            {payment ? (
                <Row gutter={[24, 24]}>
                    <Col span={12}>
                        <Statistic
                            title="Số tiền thanh toán"
                            value={payment.amount_vnd}
                            formatter={(value) => `${new Intl.NumberFormat('vi-VN').format(Number(value))} ₫`}
                            valueStyle={{ color: '#f50', fontSize: '24px', fontWeight: 600 }}
                        />
                    </Col>
                    <Col span={12}>
                        <div style={{ marginBottom: 16 }}>
                            <Text strong style={{ fontSize: '14px', color: '#262626' }}>
                                Trạng thái:
                            </Text>
                            <span style={{ marginLeft: 8 }}>
                                <StatusBadge status={payment.status} type="payment" />
                            </span>
                        </div>
                        <div style={{ marginBottom: 16 }}>
                            <Text strong style={{ fontSize: '14px', color: '#262626' }}>
                                Hình thức:
                            </Text>
                            <span style={{ marginLeft: 8, fontSize: '14px' }}>
                                {payment.payment_type || 'Chưa xác định'}
                            </span>
                        </div>
                        {payment.transaction_id && (
                            <div style={{ marginBottom: 16 }}>
                                <Text strong style={{ fontSize: '14px', color: '#262626' }}>
                                    Mã giao dịch:
                                </Text>
                                <span style={{ marginLeft: 8, fontSize: '14px', fontFamily: 'monospace' }}>
                                    {payment.transaction_id}
                                </span>
                            </div>
                        )}
                        <div>
                            <Text strong style={{ fontSize: '14px', color: '#262626' }}>
                                Thời gian thanh toán:
                            </Text>
                            <span style={{ marginLeft: 8, fontSize: '14px' }}>
                                {dayjs(payment.created_at).format('DD/MM/YYYY HH:mm')}
                            </span>
                        </div>
                    </Col>
                </Row>
            ) : (
                <div style={{ textAlign: 'center', padding: '40px 0' }}>
                    <Text type="secondary">Chưa có thông tin thanh toán</Text>
                </div>
            )}
        </Card>
    );
};

export default PaymentTab;
