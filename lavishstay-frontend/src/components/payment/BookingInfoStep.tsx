import React, { useEffect } from 'react';
import { Card, Form, Input, Row, Col, Button, Checkbox, Typography } from 'antd';
import { useSelector } from 'react-redux';
import { RootState } from '../../store';

const { TextArea } = Input;
const { Title } = Typography;

interface BookingInfoStepProps {
    form: any;
    onSubmit: (values: any) => void;
    isProcessing: boolean;
    disabled?: boolean;
    selectedPaymentMethod?: string;
}

const BookingInfoStep: React.FC<BookingInfoStepProps> = ({
    form,
    onSubmit,
    isProcessing,
    disabled = false,
}) => {
    const { isAuthenticated, user } = useSelector((state: RootState) => state.auth);

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

    return (
        <Card title="Thông tin khách hàng" className="mb-4">
            <Form form={form} layout="vertical" onFinish={onSubmit}>
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

                <Form.Item name="specialRequests" label="Yêu cầu đặc biệt (tùy chọn)">
                    <TextArea rows={3} placeholder="Ví dụ: phòng không hút thuốc, tầng cao..." />
                </Form.Item>

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
                        Tôi đồng ý với <a href="/terms" target="_blank" rel="noopener noreferrer">điều khoản dịch vụ</a> và <a href="/privacy" target="_blank" rel="noopener noreferrer">chính sách bảo mật</a>.
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
