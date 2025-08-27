import React, { useEffect, useState } from 'react';
import { Card, Form, Input, Row, Col, Button, Checkbox, Typography, Divider, Collapse, Select, Tag } from 'antd';
import { useSelector } from 'react-redux';
import { RootState } from '../../store';
import { selectSelectedRoomsSummary } from '../../store/slices/bookingSlice';
import { Phone } from 'lucide-react';
import CouponInput from './CouponInput';
import { AppliedCoupon } from '../../services/couponService';

const { Title } = Typography;
const { Panel } = Collapse;

interface BookingInfoStepProps {
    form: any;
    onSubmit: (values: any) => void;
    isProcessing: boolean;
    disabled?: boolean;
    selectedPaymentMethod?: string;
    // Coupon related props
    appliedCoupon?: AppliedCoupon | null;
    onCouponChange?: (coupon: AppliedCoupon | null) => void;
    totals?: {
        roomsTotal: number;
        serviceFee: number;
        taxAmount: number;
        finalTotal: number;
    };
    formatVND?: (amount: number) => string;
    onPaymentMethodSelect?: (method: string) => void;
    // selectedPaymentMethod?: string;
}

const BookingInfoStep: React.FC<BookingInfoStepProps> = ({
    form,
    onSubmit,
    isProcessing,
    disabled = false,
    appliedCoupon,
    onCouponChange,
    totals,
    formatVND = (amount: number) => new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    }).format(amount),
}) => {
    const { isAuthenticated, user } = useSelector((state: RootState) => state.auth);
    const selectedRoomsSummary = useSelector(selectSelectedRoomsSummary);

    // Preset options to help users express special requests in a way
    // that maps well to backend smart assignment keywords
    const SPECIAL_REQUEST_OPTIONS = [
        { label: 'Gần nhau / cùng tầng', value: 'gần nhau' },
        { label: 'Tầng cao / view đẹp', value: 'tầng cao' },
        { label: 'Tầng thấp / dễ đi lại', value: 'tầng thấp' },
        { label: 'Yên tĩnh', value: 'yên tĩnh' },
        { label: 'View / phòng góc', value: 'view đẹp' },
        { label: 'Gia đình (cần cũi / thêm giường)', value: 'gia đình' },
        { label: 'Ưu tiên phòng liền kề', value: 'kế tiếp' },
    ];

    // Auto-fill form with user data if logged in
    useEffect(() => {
        if (isAuthenticated && user) {
            form.setFieldsValue({
                fullName: user.name,
                email: user.email,
                phone: user.phone || '',

            });
        }
    }, [isAuthenticated, user, form]);

    // Ensure specialRequests is an array (Select mode="tags" returns array)
    const handleFinish = (values: any) => {
        let special = values.specialRequests || [];
        if (typeof special === 'string') {
            special = special.split(',').map((s: string) => s.trim()).filter(Boolean);
        }
        values.specialRequests = special;

        if (process.env.NODE_ENV === 'development') {
            // Debugging helper to ensure frontend is sending specialRequests correctly
            // eslint-disable-next-line no-console
            console.debug('BookingInfoStep handleFinish - specialRequests:', special, 'full values:', values);
        }

        onSubmit(values);
    };

    return (
        <Card title="Thông tin khách hàng" className="mb-4">
            <Form form={form} layout="vertical" onFinish={handleFinish} initialValues={{ termsAgreement: true }}>
                {/* Payment method is selected in PaymentStep; no selector here */}
                <Title level={5}>Thông tin người đại diện</Title>
                <Row gutter={16}>
                    <Col span={24}>
                        <Form.Item
                            name="fullName"
                            label="Họ và tên người đại diện"
                            rules={[{ required: true, message: 'Vui lòng nhập họ và tên' }]}
                        >
                            <Input placeholder="Nhập họ và tên người đại diện cho tất cả phòng" size="large" />
                        </Form.Item>
                    </Col>
                </Row>

                <Row gutter={16}>
                    <Col span={12}>
                        <Form.Item
                            name="email"
                            label="Email"
                            rules={[
                                { required: true, message: 'Vui lòng nhập email' },
                                { type: 'email', message: 'Email không hợp lệ' }
                            ]}
                        >
                            <Input placeholder="Nhập email" size="large" />
                        </Form.Item>
                    </Col>
                    <Col span={12}>
                        <Form.Item
                            name="phone"
                            label="Số điện thoại"
                            rules={[{ required: true, message: 'Vui lòng nhập số điện thoại' }]}
                        >
                            <Input placeholder="Nhập số điện thoại" size="large" />
                        </Form.Item>
                    </Col>
                </Row>

                {/* Per-room guest information for multi-room bookings */}
                {selectedRoomsSummary.length > 1 && (
                    <>
                        <Divider orientation="left">Thông tin cho từng phòng</Divider>
                        <Collapse defaultActiveKey={['0']} className="mb-4">
                            {selectedRoomsSummary.map((roomSummary, index) => (
                                <Panel
                                    header={`Phòng ${index + 1}: ${roomSummary.room.name} - ${roomSummary.option.name}`}
                                    key={index.toString()}
                                >
                                    <Title level={5} className="mt-2">Thông tin khách cho phòng {index + 1}</Title>
                                    <p className="text-sm mb-3">Để trống nếu giống với thông tin người đại diện</p>

                                    <Form.Item
                                        name={`room_${index}_guest_name`}
                                        label="Họ và tên"
                                    >
                                        <Input placeholder="Nhập tên khách ở phòng này (nếu khác với người đại diện)" />
                                    </Form.Item>

                                    <Row gutter={16}>
                                        <Col span={12}>
                                            <Form.Item
                                                name={`room_${index}_guest_email`}
                                                label="Email"
                                            >
                                                <Input placeholder="Email của khách" />
                                            </Form.Item>
                                        </Col>
                                        <Col span={12}>
                                            <Form.Item
                                                name={`room_${index}_guest_phone`}
                                                label="Số điện thoại"
                                            >
                                                <Input placeholder="Số điện thoại của khách" />
                                            </Form.Item>
                                        </Col>
                                    </Row>
                                </Panel>
                            ))}
                        </Collapse>
                    </>
                )}

                <Form.Item name="specialRequests" label="Yêu cầu đặc biệt (tùy chọn)">
                    <Select
                        mode="tags"
                        showSearch
                        placeholder="Chọn hoặc nhập yêu cầu (ví dụ: tầng cao, gần nhau)..."
                        options={SPECIAL_REQUEST_OPTIONS.map(o => ({ label: o.label, value: o.value }))}
                        tokenSeparators={[',']}
                        style={{ width: '100%' }}
                        onChange={(val) => form.setFieldsValue({ specialRequests: val })}
                        tagRender={({ label, closable, onClose }) => (
                            <Tag color="#2f54eb" closable={closable} onClose={onClose} style={{ marginRight: 6 }}>
                                {label}
                            </Tag>
                        )}
                    />
                    <div style={{ marginTop: 8, color: 'rgba(0,0,0,0.45)' }}>Bạn có thể gõ để thêm yêu cầu riêng, hoặc chọn từ gợi ý.</div>
                </Form.Item>

                {/* Coupon Input Section */}
                {totals && onCouponChange && (
                    <CouponInput
                        bookingPreview={{
                            base_price_vnd: totals.roomsTotal + totals.serviceFee + totals.taxAmount,
                            taxes_vnd: totals.taxAmount,
                            fees_vnd: totals.serviceFee,
                            room_type_id: Number(selectedRoomsSummary[0]?.room?.room_type_id || selectedRoomsSummary[0]?.room?.id || 1),
                            // Include nights and total_price_vnd to match backend validation rules
                            nights: 1,
                            total_price_vnd: totals.finalTotal ?? (totals.roomsTotal + totals.serviceFee + totals.taxAmount)
                        }}
                        appliedCoupon={appliedCoupon}
                        onCouponChange={onCouponChange}
                        formatVND={formatVND}
                        disabled={disabled || isProcessing}
                    />
                )}

                <Form.Item
                    name="termsAgreement"
                    valuePropName="checked"
                    rules={[
                        {
                            validator: (_, value) =>
                                value ? Promise.resolve() : Promise.reject(new Error('Bạn phải đồng ý với điều khoản')),
                        },
                    ]}
                >
                    <Checkbox>
                        Tôi đồng ý với{' '}
                        <Typography.Link href="/about" target="_blank" rel="noopener noreferrer" underline style={{ color: '#1677ff', fontWeight: 600 }}>
                            điều khoản dịch vụ
                        </Typography.Link>{' '}
                        và{' '}
                        <Typography.Link href="/about" target="_blank" rel="noopener noreferrer" underline style={{ color: '#1677ff', fontWeight: 600 }}>
                            chính sách bảo mật
                        </Typography.Link>.
                    </Checkbox>
                </Form.Item>

                <Form.Item>
                    <Button
                        type="primary"
                        htmlType="submit"
                        size="large"
                        loading={isProcessing}
                        disabled={disabled}
                        block
                        style={{
                            height: '50px',
                            fontSize: '16px',
                            fontWeight: 600,
                            borderRadius: '8px'
                        }}
                    >
                        {isProcessing
                            ? 'Đang xử lý...'
                            : disabled
                                ? 'Không thể tiến hành'
                                : 'Tiếp tục thanh toán'
                        }
                    </Button>
                </Form.Item>
            </Form>
        </Card>
    );
};

export default BookingInfoStep;
