import React from 'react';
import { Card, Typography, Alert, Button, Space, Result } from 'antd';
import { CheckCircleOutlined } from '@ant-design/icons';

const { Title, Text } = Typography;

interface CompletionStepProps {
    bookingCode: string;
    selectedPaymentMethod: string;
    onViewBookings: () => void;
    onNewBooking: () => void;
}

const CompletionStep: React.FC<CompletionStepProps> = ({
    bookingCode,
    selectedPaymentMethod,
    onViewBookings,
    onNewBooking
}) => {
    const getSuccessMessage = () => {
        if (selectedPaymentMethod === 'vietqr') {
            return 'Thanh toán thành công!';
        }
        return 'Đặt phòng thành công!';
    };

    const getDescription = () => {
        if (selectedPaymentMethod === 'vietqr') {
            return 'Chúng tôi đã xác nhận thanh toán của bạn. Thông tin đặt phòng đã được gửi qua email.';
        }
        return 'Đặt phòng của bạn đã được xác nhận. Vui lòng thanh toán tại khách sạn khi nhận phòng.';
    };

    return (
        <div className="text-center">
            <Card className="max-w-2xl mx-auto">
                <Result
                    status="success"
                    title={getSuccessMessage()}
                    subTitle={getDescription()}
                    icon={<CheckCircleOutlined style={{ color: '#52c41a' }} />}
                />

                <div className="mb-6">
                    <div className="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
                        <div className="flex items-center justify-center mb-2">
                            <CheckCircleOutlined className="text-green-600 mr-2" />
                            <Text strong className="text-green-800">
                                Mã đặt phòng của bạn: {bookingCode}
                            </Text>
                        </div>
                        <Text className="text-green-700 text-sm">
                            Vui lòng lưu lại mã này để tra cứu thông tin đặt phòng
                        </Text>
                    </div>
                </div>

                <Alert
                    message="Thông tin quan trọng"
                    description={
                        <div className="text-left">
                            <p>• Vui lòng mang theo giấy tờ tùy thân và mã đặt phòng: <Text strong>{bookingCode}</Text></p>
                            <p>• Thời gian check-in: 14:00 | Thời gian check-out: 12:00</p>
                            <p>• Liên hệ: 0123456789 nếu có thắc mắc</p>
                            {selectedPaymentMethod === 'pay_at_hotel' && (
                                <p>• <Text strong>Lưu ý:</Text> Vui lòng thanh toán tại quầy lễ tân khi nhận phòng</p>
                            )}
                        </div>
                    }
                    type="info"
                    showIcon
                    className="mb-6 text-left"
                />

                <Space size="large">
                    <Button
                        type="primary"
                        size="large"
                        onClick={onViewBookings}
                        className="min-w-[140px]"
                    >
                        Xem đặt phòng
                    </Button>
                    <Button
                        size="large"
                        onClick={onNewBooking}
                        className="min-w-[140px]"
                    >
                        Đặt phòng mới
                    </Button>
                </Space>
            </Card>
        </div>
    );
};

export default CompletionStep;
