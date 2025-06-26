import React from "react";
import {
    Card,
    Divider,
    Row,
    Space,
    Typography,
} from "antd";
import { EnvironmentOutlined } from "@ant-design/icons";
import { formatCurrency, formatDate } from "../../utils/helpers";
import { BookingData } from "./types";

const { Title, Text } = Typography;

interface BookingSummaryProps {
    bookingData: BookingData;
    bookingId: string;
}

const BookingSummary: React.FC<BookingSummaryProps> = ({ bookingData, bookingId }) => {
    return (
        <Card
            title="Thông tin đặt phòng"
            style={{
                border: '1px solid #e8e8e8',
                borderRadius: '8px',
                boxShadow: '0 2px 8px rgba(0, 0, 0, 0.06)'
            }}
        >
            <div style={{ marginBottom: 16 }}>
                <img
                    src={bookingData.images[0]}
                    alt={bookingData.roomType}
                    style={{
                        width: '100%',
                        height: '120px',
                        objectFit: 'cover',
                        borderRadius: '6px',
                        marginBottom: 12
                    }}
                />
                <Title level={5} style={{ color: '#495057', marginBottom: 4 }}>
                    {bookingData.hotelName}
                </Title>
                <Text style={{ color: '#6c757d', display: 'block', marginBottom: 4 }}>
                    {bookingData.roomType}
                </Text>
                <Text style={{ color: '#6c757d', fontSize: '13px' }}>
                    <EnvironmentOutlined style={{ marginRight: 4 }} />
                    {bookingData.location}
                </Text>
            </div>

            <Divider style={{ margin: '16px 0' }} />

            <Space direction="vertical" style={{ width: '100%' }}>
                <Row justify="space-between">
                    <Text style={{ color: '#6c757d' }}>Mã đặt phòng:</Text>
                    <Text strong style={{ color: '#495057' }}>{bookingId}</Text>
                </Row>
                <Row justify="space-between">
                    <Text style={{ color: '#6c757d' }}>Nhận phòng:</Text>
                    <Text style={{ color: '#495057' }}>{formatDate(bookingData.checkIn)}</Text>
                </Row>
                <Row justify="space-between">
                    <Text style={{ color: '#6c757d' }}>Trả phòng:</Text>
                    <Text style={{ color: '#495057' }}>{formatDate(bookingData.checkOut)}</Text>
                </Row>
                <Row justify="space-between">
                    <Text style={{ color: '#6c757d' }}>Số khách:</Text>
                    <Text style={{ color: '#495057' }}>{bookingData.guests} người</Text>
                </Row>
                <Row justify="space-between">
                    <Text style={{ color: '#6c757d' }}>Số đêm:</Text>
                    <Text style={{ color: '#495057' }}>{bookingData.nights} đêm</Text>
                </Row>
            </Space>

            <Divider style={{ margin: '16px 0' }} />

            <Row justify="space-between" style={{ marginBottom: 8 }}>
                <Text style={{ color: '#6c757d' }}>Giá phòng:</Text>
                <Text style={{ color: '#495057' }}>{formatCurrency(bookingData.price)}</Text>
            </Row>
            <Row justify="space-between" style={{ marginBottom: 12 }}>
                <Text style={{ color: '#6c757d' }}>Thuế & phí:</Text>
                <Text style={{ color: '#495057' }}>{formatCurrency(bookingData.tax)}</Text>
            </Row>
            <Row justify="space-between">
                <Text strong style={{ color: '#495057', fontSize: '16px' }}>Tổng cộng:</Text>
                <Text strong style={{ color: '#d32f2f', fontSize: '18px' }}>
                    {formatCurrency(bookingData.total)}
                </Text>
            </Row>
        </Card>
    );
};

export default BookingSummary;
