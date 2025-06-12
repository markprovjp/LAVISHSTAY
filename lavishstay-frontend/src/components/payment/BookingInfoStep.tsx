import React, { useEffect } from 'react';
import { Card, Form, Input, Row, Col, Button, Checkbox } from 'antd';
import { useSelector } from 'react-redux';
import { RootState } from '../../store';

const { TextArea } = Input;

interface BookingInfoStepProps {
    form: any;
    onSubmit: (values: any) => void;
    isProcessing: boolean;
}

const BookingInfoStep: React.FC<BookingInfoStepProps> = ({
    form,
    onSubmit,
    isProcessing
}) => {
    const { isAuthenticated, user } = useSelector((state: RootState) => state.auth);

    // Auto-fill form with user data if logged in
    useEffect(() => {
        if (isAuthenticated && user) {
            // Split name into firstName and lastName if fullName is provided
            const nameParts = user.name?.split(' ') || [];
            const firstName = nameParts[0] || '';
            const lastName = nameParts.slice(1).join(' ') || '';

            form.setFieldsValue({
                firstName: firstName,
                lastName: lastName,
                email: user.email,
                phone: user.phone || '', // Use phone if available, otherwise empty
            });
        }
    }, [isAuthenticated, user, form]);
    return (
        <Card title="Thông tin khách hàng" className="mb-4">
            <Form form={form} layout="vertical" onFinish={onSubmit}>
                <Row gutter={16}>
                    <Col span={12}>
                        <Form.Item
                            name="firstName"
                            label="Họ"
                            rules={[{ required: true, message: 'Vui lòng nhập họ' }]}
                        >
                            <Input placeholder="Nhập họ" size="large" />
                        </Form.Item>
                    </Col>
                    <Col span={12}>
                        <Form.Item
                            name="lastName"
                            label="Tên"
                            rules={[{ required: true, message: 'Vui lòng nhập tên' }]}
                        >
                            <Input placeholder="Nhập tên" size="large" />
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

                <Form.Item name="specialRequests" label="Yêu cầu đặc biệt">
                    <TextArea rows={3} placeholder="Nhập yêu cầu đặc biệt (tùy chọn)" />
                </Form.Item>

                <Form.Item>
                    <Checkbox>
                        Tôi đồng ý với <a href="/terms">điều khoản dịch vụ</a> và <a href="/privacy">chính sách bảo mật</a>
                    </Checkbox>
                </Form.Item>

                <Form.Item>
                    <Button
                        type="primary"
                        htmlType="submit"
                        size="large"
                        loading={isProcessing}
                        block
                        style={{
                            height: '50px',
                            fontSize: '16px',
                            fontWeight: 600,
                            borderRadius: '8px'
                        }}
                    >
                        Tiếp tục thanh toán
                    </Button>
                </Form.Item>
            </Form>
        </Card>
    );
};

export default BookingInfoStep;
