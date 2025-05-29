import React from "react";
import {
    Card,
    Form,
    Input,
    Button,
    Divider,
    Row,
    Col,
    Carousel,
    Typography,
} from "antd";
import {
    EnvironmentOutlined,
    CalendarOutlined,
    TeamOutlined,
    ClockCircleOutlined,
} from "@ant-design/icons";
import { formatCurrency, formatDate } from "../../utils/helpers";
import { BookingInfoStepProps } from "./types";

const { Title, Text, Paragraph } = Typography;

const BookingInfoStep: React.FC<BookingInfoStepProps> = ({
    bookingData,
    form,
    loading,
    onSubmit,
    user
}) => {
    return (
        <div style={{ minHeight: '100vh', padding: '24px 0' }}>
            <Row gutter={[24, 24]}>
                {/* Hotel Images */}
                <Col span={24} md={10}>
                    <Card
                        style={{
                            border: '1px solid #e8e8e8',
                            borderRadius: '8px',
                            boxShadow: '0 2px 8px rgba(0, 0, 0, 0.06)'
                        }}
                        bodyStyle={{ padding: 0 }}
                    >
                        <Carousel autoplay dots={{ className: 'custom-dots' }}>
                            {bookingData.images.map((image: string, index: number) => (
                                <div key={index}>
                                    <img
                                        src={image}
                                        alt={`${bookingData.roomType} - Ảnh ${index + 1}`}
                                        style={{
                                            width: '100%',
                                            height: '300px',
                                            objectFit: 'cover',
                                            borderRadius: '8px 8px 0 0'
                                        }}
                                    />
                                </div>
                            ))}
                        </Carousel>

                        <div style={{ padding: '16px' }}>
                            <Title level={3} style={{ color: '#495057', marginBottom: 8 }}>
                                {bookingData.hotelName}
                            </Title>
                            <div style={{ marginBottom: 12 }}>
                                <EnvironmentOutlined style={{ color: '#6c757d', marginRight: 8 }} />
                                <Text style={{ color: '#6c757d' }}>{bookingData.location}</Text>
                            </div>

                            <Title level={4} style={{ color: '#495057', marginBottom: 12 }}>
                                {bookingData.roomType}
                            </Title>
                            <Paragraph style={{ color: '#6c757d', marginBottom: 16 }}>
                                {bookingData.description}
                            </Paragraph>

                            <div style={{ marginBottom: 16 }}>
                                <Text strong style={{ color: '#495057', display: 'block', marginBottom: 8 }}>
                                    Tiện nghi:
                                </Text>
                                <Row gutter={[8, 8]}>
                                    {bookingData.amenities.map((amenity: string, index: number) => (
                                        <Col key={index}>
                                            <div style={{
                                                padding: '4px 8px',
                                                backgroundColor: '#f8f9fa',
                                                border: '1px solid #e9ecef',
                                                borderRadius: '4px',
                                                fontSize: '12px',
                                                color: '#6c757d'
                                            }}>
                                                {amenity}
                                            </div>
                                        </Col>
                                    ))}
                                </Row>
                            </div>
                        </div>
                    </Card>
                </Col>

                {/* Booking Information */}
                <Col span={24} md={14}>
                    <Card
                        title={<Title level={4} style={{ color: '#495057', margin: 0 }}>Chi tiết đặt phòng</Title>}
                        style={{
                            border: '1px solid #e8e8e8',
                            borderRadius: '8px',
                            boxShadow: '0 2px 8px rgba(0, 0, 0, 0.06)',
                            marginBottom: 24
                        }}
                    >
                        <Row gutter={[16, 16]} style={{ marginBottom: 24 }}>
                            <Col span={12}>
                                <div style={{ textAlign: 'center', padding: '16px', backgroundColor: '#f8f9fa', borderRadius: '6px' }}>
                                    <CalendarOutlined style={{ fontSize: 24, color: '#6c757d', marginBottom: 8 }} />
                                    <div style={{ color: '#6c757d', fontSize: '14px', marginBottom: 4 }}>Nhận phòng</div>
                                    <div style={{ color: '#495057', fontWeight: 500 }}>{formatDate(bookingData.checkIn)}</div>
                                </div>
                            </Col>
                            <Col span={12}>
                                <div style={{ textAlign: 'center', padding: '16px', backgroundColor: '#f8f9fa', borderRadius: '6px' }}>
                                    <CalendarOutlined style={{ fontSize: 24, color: '#6c757d', marginBottom: 8 }} />
                                    <div style={{ color: '#6c757d', fontSize: '14px', marginBottom: 4 }}>Trả phòng</div>
                                    <div style={{ color: '#495057', fontWeight: 500 }}>{formatDate(bookingData.checkOut)}</div>
                                </div>
                            </Col>
                            <Col span={12}>
                                <div style={{ textAlign: 'center', padding: '16px', backgroundColor: '#f8f9fa', borderRadius: '6px' }}>
                                    <TeamOutlined style={{ fontSize: 24, color: '#6c757d', marginBottom: 8 }} />
                                    <div style={{ color: '#6c757d', fontSize: '14px', marginBottom: 4 }}>Số khách</div>
                                    <div style={{ color: '#495057', fontWeight: 500 }}>{bookingData.guests} người</div>
                                </div>
                            </Col>
                            <Col span={12}>
                                <div style={{ textAlign: 'center', padding: '16px', backgroundColor: '#f8f9fa', borderRadius: '6px' }}>
                                    <ClockCircleOutlined style={{ fontSize: 24, color: '#6c757d', marginBottom: 8 }} />
                                    <div style={{ color: '#6c757d', fontSize: '14px', marginBottom: 4 }}>Số đêm</div>
                                    <div style={{ color: '#495057', fontWeight: 500 }}>{bookingData.nights} đêm</div>
                                </div>
                            </Col>
                        </Row>

                        <Divider style={{ borderColor: '#e8e8e8' }} />

                        <div style={{ marginBottom: 24 }}>
                            <Row justify="space-between" style={{ marginBottom: 8 }}>
                                <Text style={{ color: '#6c757d' }}>Giá phòng ({bookingData.nights} đêm)</Text>
                                <Text style={{ color: '#6c757d' }}>{formatCurrency(bookingData.price)}</Text>
                            </Row>
                            <Row justify="space-between" style={{ marginBottom: 8 }}>
                                <Text style={{ color: '#6c757d' }}>Thuế và phí</Text>
                                <Text style={{ color: '#6c757d' }}>{formatCurrency(bookingData.tax)}</Text>
                            </Row>
                            <Divider style={{ borderColor: '#e8e8e8', margin: '12px 0' }} />
                            <Row justify="space-between">
                                <Title level={4} style={{ color: '#495057', margin: 0 }}>Tổng cộng</Title>
                                <Title level={4} style={{ color: '#d32f2f', margin: 0 }}>
                                    {formatCurrency(bookingData.total)}
                                </Title>
                            </Row>
                        </div>
                    </Card>

                    <Card
                        title={<Title level={4} style={{ color: '#495057', margin: 0 }}>Thông tin liên hệ</Title>}
                        style={{
                            border: '1px solid #e8e8e8',
                            borderRadius: '8px',
                            boxShadow: '0 2px 8px rgba(0, 0, 0, 0.06)'
                        }}
                    >
                        <Form
                            form={form}
                            layout="vertical"
                            onFinish={onSubmit}
                            initialValues={{
                                name: user?.name || "",
                                email: user?.email || "",
                                phone: ""
                            }}
                        >
                            <Row gutter={16}>
                                <Col span={12}>
                                    <Form.Item
                                        label="Họ và tên"
                                        name="name"
                                        rules={[{ required: true, message: 'Vui lòng nhập họ tên!' }]}
                                    >
                                        <Input
                                            placeholder="Nhập họ và tên"
                                            style={{ borderColor: '#d9d9d9' }}
                                        />
                                    </Form.Item>
                                </Col>
                                <Col span={12}>
                                    <Form.Item
                                        label="Email"
                                        name="email"
                                        rules={[
                                            { required: true, message: 'Vui lòng nhập email!' },
                                            { type: 'email', message: 'Email không hợp lệ!' }
                                        ]}
                                    >
                                        <Input
                                            placeholder="Nhập email"
                                            style={{ borderColor: '#d9d9d9' }}
                                        />
                                    </Form.Item>
                                </Col>
                            </Row>
                            <Row gutter={16}>
                                <Col span={12}>
                                    <Form.Item
                                        label="Số điện thoại"
                                        name="phone"
                                        rules={[{ required: true, message: 'Vui lòng nhập số điện thoại!' }]}
                                    >
                                        <Input
                                            placeholder="Nhập số điện thoại"
                                            style={{ borderColor: '#d9d9d9' }}
                                        />
                                    </Form.Item>
                                </Col>
                                <Col span={12}>
                                    <Form.Item label="Yêu cầu đặc biệt" name="specialRequests">
                                        <Input.TextArea
                                            placeholder="Nhập yêu cầu đặc biệt (nếu có)"
                                            rows={1}
                                            style={{ borderColor: '#d9d9d9' }}
                                        />
                                    </Form.Item>
                                </Col>
                            </Row>

                            <div style={{ textAlign: 'right', marginTop: 24 }}>
                                <Button
                                    type="primary"
                                    htmlType="submit"
                                    size="large"
                                    loading={loading}
                                    style={{
                                        backgroundColor: '#1890ff',
                                        borderColor: '#1890ff',
                                        height: '48px',
                                        padding: '0 32px',
                                        fontSize: '16px',
                                        fontWeight: 500
                                    }}
                                >
                                    Tiến hành thanh toán
                                </Button>
                            </div>
                        </Form>
                    </Card>
                </Col>
            </Row>
        </div>
    );
};

export default BookingInfoStep;
