import React from 'react';
import { Card, Row, Col, Typography, Flex, Space, Divider } from 'antd';
import { CheckCircleOutlined, CloseCircleOutlined, PercentageOutlined } from '@ant-design/icons';

const { Title, Text } = Typography;

interface CouponTabProps {
    couponApplied?: boolean;
    coupon?: {
        redemption_id: number;
        coupon_id: number;
        code: string;
        amount_saved_vnd: number;
        applied_amount_vnd: number;
        applied_at: string;
        meta: {
            original_total: number;
            discount_calculation: {
                original_amount: number;
                discount_amount: number;
                new_total: number;
                discount_type: string;
                discount_value: string;
            };
            applied_at: string;
            coupon_snapshot: {
                code: string;
                type: string;
                value: string;
            };
        };
    } | null;
    originalTotal: number;
}

const CouponTab: React.FC<CouponTabProps> = ({ couponApplied, coupon, originalTotal }) => {
    const formatCurrency = (amount: number): string => {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND',
            minimumFractionDigits: 0
        }).format(amount);
    };

    const formatDate = (dateString: string): string => {
        return new Date(dateString).toLocaleString('vi-VN', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    };

    if (!couponApplied || !coupon) {
        return (
            <Card>
                <Flex align="center" gap={16} style={{ padding: '24px' }}>
                    <CloseCircleOutlined
                        style={{
                            fontSize: '48px',
                            color: '#d9d9d9'
                        }}
                    />
                    <div>
                        <Title level={4} style={{ margin: 0, color: '#8c8c8c' }}>
                            Không có mã giảm giá
                        </Title>
                        <Text type="secondary">
                            Booking này không sử dụng mã giảm giá nào
                        </Text>
                    </div>
                </Flex>
            </Card>
        );
    }

    const discountCalculation = coupon.meta?.discount_calculation;
    const couponSnapshot = coupon.meta?.coupon_snapshot;

    return (
        <Space direction="vertical" size="large" style={{ width: '100%' }}>
            {/* Thông tin mã giảm giá */}
            <Card title={
                <Flex align="center" gap={8}>
                    <CheckCircleOutlined style={{ color: '#52c41a', fontSize: '20px' }} />
                    <span>Mã giảm giá đã áp dụng</span>
                </Flex>
            }>
                <Row gutter={[16, 16]}>
                    <Col span={12}>
                        <Text strong>Mã giảm giá:</Text>
                        <br />
                        <Text
                            copyable
                            style={{
                                fontSize: '16px',
                                fontFamily: 'monospace',
                                backgroundColor: '#f0f0f0',
                                padding: '4px 8px',
                                borderRadius: '4px'
                            }}
                        >
                            {coupon.code}
                        </Text>
                    </Col>
                    <Col span={12}>
                        <Text strong>Thời gian áp dụng:</Text>
                        <br />
                        <Text>{formatDate(coupon.applied_at)}</Text>
                    </Col>
                    <Col span={12}>
                        <Text strong>Loại giảm giá:</Text>
                        <br />
                        <Flex align="center" gap={4}>
                            <PercentageOutlined />
                            <Text>
                                {couponSnapshot?.type === 'percentage'
                                    ? `Giảm ${couponSnapshot.value}%`
                                    : `Giảm ${formatCurrency(Number(couponSnapshot?.value || 0))}`
                                }
                            </Text>
                        </Flex>
                    </Col>
                    <Col span={12}>
                        <Text strong>ID Redemption:</Text>
                        <br />
                        <Text type="secondary">#{coupon.redemption_id}</Text>
                    </Col>
                </Row>
            </Card>

            {/* Chi tiết tính toán giảm giá */}
            {discountCalculation && (
                <Card title="Chi tiết tính toán giảm giá">
                    <Space direction="vertical" size="middle" style={{ width: '100%' }}>
                        <Row gutter={[16, 8]}>
                            <Col span={16}>
                                <Text>Tổng tiền gốc (trước giảm giá):</Text>
                            </Col>
                            <Col span={8} style={{ textAlign: 'right' }}>
                                <Text strong style={{ fontSize: '16px' }}>
                                    {formatCurrency(discountCalculation.original_amount)}
                                </Text>
                            </Col>
                        </Row>

                        <Row gutter={[16, 8]}>
                            <Col span={16}>
                                <Text>Số tiền giảm:</Text>
                            </Col>
                            <Col span={8} style={{ textAlign: 'right' }}>
                                <Text
                                    strong
                                    style={{
                                        fontSize: '16px',
                                        color: '#52c41a'
                                    }}
                                >
                                    -{formatCurrency(discountCalculation.discount_amount)}
                                </Text>
                            </Col>
                        </Row>

                        <Divider style={{ margin: '8px 0' }} />

                        <Row gutter={[16, 8]}>
                            <Col span={16}>
                                <Text strong style={{ fontSize: '16px' }}>
                                    Tổng tiền sau giảm giá:
                                </Text>
                            </Col>
                            <Col span={8} style={{ textAlign: 'right' }}>
                                <Text
                                    strong
                                    style={{
                                        fontSize: '18px',
                                        color: '#1890ff'
                                    }}
                                >
                                    {formatCurrency(discountCalculation.new_total)}
                                </Text>
                            </Col>
                        </Row>

                        <Row gutter={[16, 8]}>
                            <Col span={16}>
                                <Text type="secondary">
                                    Tiết kiệm được:
                                </Text>
                            </Col>
                            <Col span={8} style={{ textAlign: 'right' }}>
                                <Text
                                    type="success"
                                    strong
                                    style={{ fontSize: '14px' }}
                                >
                                    {formatCurrency(coupon.amount_saved_vnd)}
                                </Text>
                            </Col>
                        </Row>
                    </Space>
                </Card>
            )}

            {/* Thông tin kỹ thuật */}
            <Card
                title="Thông tin kỹ thuật"
                size="small"
                style={{ backgroundColor: '#fafafa' }}
            >
                <Row gutter={[16, 8]}>
                    <Col span={12}>
                        <Text type="secondary">Coupon ID:</Text>
                        <br />
                        <Text code>#{coupon.coupon_id}</Text>
                    </Col>
                    <Col span={12}>
                        <Text type="secondary">Số tiền áp dụng:</Text>
                        <br />
                        <Text code>{formatCurrency(coupon.applied_amount_vnd)}</Text>
                    </Col>
                </Row>
            </Card>
        </Space>
    );
};

export default CouponTab;
