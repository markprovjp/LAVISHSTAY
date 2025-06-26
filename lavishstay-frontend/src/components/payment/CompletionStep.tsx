import React from "react";
import {
    Card,
    Button,
    Row,
    Col,
    Space,
    Typography,
} from "antd";
import { CheckCircleOutlined, EnvironmentOutlined } from "@ant-design/icons";
import { formatCurrency, formatDate } from "../../utils/helpers";
import { CompletionStepProps } from "./types";

const { Title, Text } = Typography;

const CompletionStep: React.FC<CompletionStepProps> = ({
    bookingData,
    bookingId,
    onGoToBookings,
    onGoToHome
}) => {
    return (
        <div style={{ minHeight: '100vh', padding: '24px 0' }}>
            <Row justify="center">
                <Col span={24} md={16} lg={12}>
                    <Card
                        style={{
                            border: '1px solid #e8e8e8',
                            borderRadius: '8px',
                            boxShadow: '0 2px 8px rgba(0, 0, 0, 0.06)',
                            textAlign: 'center'
                        }}
                    >
                        <div style={{ padding: '32px 0' }}>
                            <CheckCircleOutlined
                                style={{
                                    fontSize: '64px',
                                    color: '#52c41a',
                                    marginBottom: 24
                                }}
                            />

                            <Title level={2} style={{ color: '#495057', marginBottom: 8 }}>
                                Đặt phòng thành công!
                            </Title>

                            <Text style={{ color: '#6c757d', fontSize: '16px', display: 'block', marginBottom: 24 }}>
                                Cảm ơn bạn đã tin tưởng và lựa chọn LavishStay Hotels.
                                Chúng tôi đã gửi email xác nhận đến địa chỉ email của bạn.
                            </Text>

                            <Card
                                size="small"
                                style={{
                                    backgroundColor: '#f8f9fa',
                                    border: '1px solid #e9ecef',
                                    marginBottom: 24,
                                    textAlign: 'left'
                                }}
                            >
                                <Row gutter={[16, 8]}>
                                    <Col span={12}>
                                        <div style={{ marginBottom: 8 }}>
                                            <img
                                                src={bookingData.images[0]}
                                                alt={bookingData.roomType}
                                                style={{
                                                    width: '100%',
                                                    height: '80px',
                                                    objectFit: 'cover',
                                                    borderRadius: '4px'
                                                }}
                                            />
                                        </div>
                                        <Title level={5} style={{ margin: 0 }}>{bookingData.hotelName}</Title>
                                        <Text>{bookingData.roomType}</Text>
                                        <div style={{ color: '#6c757d' }}>
                                            <EnvironmentOutlined style={{ marginRight: 4 }} />
                                            {bookingData.location}
                                        </div>
                                    </Col>
                                    <Col span={12}>
                                        <Space direction="vertical" style={{ width: '100%' }}>
                                            <div>
                                                <Text style={{ color: '#6c757d' }}>Mã đặt phòng:</Text>
                                                <div style={{ fontWeight: 500 }}>{bookingId}</div>
                                            </div>
                                            <div>
                                                <Text style={{ color: '#6c757d' }}>Nhận phòng:</Text>
                                                <div>{formatDate(bookingData.checkIn)}</div>
                                            </div>
                                            <div>
                                                <Text style={{ color: '#6c757d' }}>Trả phòng:</Text>
                                                <div>{formatDate(bookingData.checkOut)}</div>
                                            </div>
                                            <div>
                                                <Text style={{ color: '#6c757d' }}>Số khách:</Text>
                                                <div>{bookingData.guests} người</div>
                                            </div>
                                            <div>
                                                <Text style={{ color: '#6c757d' }}>Tổng tiền:</Text>
                                                <div style={{ fontWeight: 500 }}>{formatCurrency(bookingData.total)}</div>
                                            </div>
                                        </Space>
                                    </Col>
                                </Row>
                            </Card>

                            <Space size="large">
                                <Button
                                    type="primary"
                                    size="large"
                                    onClick={onGoToBookings}
                                    style={{
                                        backgroundColor: '#1890ff',
                                        borderColor: '#1890ff',
                                        height: '48px',
                                        padding: '0 32px',
                                        fontSize: '16px',
                                        fontWeight: 500
                                    }}
                                >
                                    Xem đặt phòng của tôi
                                </Button>

                                <Button
                                    size="large"
                                    onClick={onGoToHome}
                                    style={{
                                        height: '48px',
                                        padding: '0 32px',
                                        fontSize: '16px',
                                        borderColor: '#d9d9d9'
                                    }}
                                >
                                    Về trang chủ
                                </Button>
                            </Space>
                        </div>
                    </Card>
                </Col>
            </Row>
        </div>
    );
};

export default CompletionStep;
