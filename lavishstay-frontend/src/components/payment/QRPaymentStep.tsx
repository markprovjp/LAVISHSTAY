import React from "react";
import {
    Card,
    Button,
    Row,
    Col,
    QRCode,
    Space,
    Statistic,
    Typography,
} from "antd";
import {
    CopyOutlined,
    ClockCircleOutlined,
    SecurityScanOutlined,
    CheckCircleOutlined,
    ReloadOutlined,
    InfoCircleOutlined,
    WarningOutlined,
} from "@ant-design/icons";
import { QRPaymentStepProps } from "./types";

const { Title, Text } = Typography;

const QRPaymentStep: React.FC<QRPaymentStepProps> = ({
    countdown,
    paymentStatus,
    qrUrl,
    vietQRConfig,
    bookingData,
    bookingId,
    loading,
    onManualCheck,
    onCopyToClipboard
}) => {
    return (
        <div style={{ minHeight: '100vh', padding: '24px 0' }}>
            <Row gutter={[24, 24]} justify="center">
                <Col span={24} md={12}>
                    <Card
                        title={
                            <div style={{ textAlign: 'center' }}>
                                <Title level={3} style={{ color: '#495057', margin: 0 }}>
                                    Thanh toán VietQR
                                </Title>
                                <Text style={{ color: '#6c757d' }}>
                                    Quét mã QR bằng ứng dụng Banking để thanh toán
                                </Text>
                            </div>
                        }
                        style={{
                            border: '1px solid #e8e8e8',
                            borderRadius: '8px',
                            boxShadow: '0 2px 8px rgba(0, 0, 0, 0.06)'
                        }}
                    >
                        <div style={{ textAlign: 'center', marginBottom: 24 }}>
                            <Statistic.Countdown
                                title="Thời gian còn lại"
                                value={Date.now() + countdown * 1000}
                                format="mm:ss"
                                valueStyle={{
                                    color: countdown < 300 ? '#d32f2f' : '#1890ff',
                                    fontSize: '24px',
                                    fontWeight: 'bold'
                                }}
                                onFinish={() => {
                                    // Handle countdown finish in parent component
                                }}
                            />
                        </div>

                        <div style={{ textAlign: 'center', marginBottom: 24 }}>
                            {qrUrl && (
                                <QRCode
                                    value={qrUrl}
                                    size={200}
                                    style={{
                                        border: '1px solid #e8e8e8',
                                        borderRadius: '8px',
                                        padding: '16px'
                                    }}
                                />
                            )}
                        </div>

                        <Card
                            size="small"
                            style={{
                                backgroundColor: '#f8f9fa',
                                border: '1px solid #e9ecef',
                                marginBottom: 16
                            }}
                        >
                            <Row gutter={[16, 8]}>
                                <Col span={8}>
                                    <Text strong style={{ color: '#495057' }}>Ngân hàng:</Text>
                                </Col>
                                <Col span={16}>
                                    <Text style={{ color: '#6c757d' }}>VietinBank</Text>
                                    <Button
                                        type="link"
                                        size="small"
                                        icon={<CopyOutlined />}
                                        onClick={() => onCopyToClipboard("VietinBank", "tên ngân hàng")}
                                        style={{ padding: 0, height: 'auto', marginLeft: 8 }}
                                    />
                                </Col>

                                <Col span={8}>
                                    <Text strong style={{ color: '#495057' }}>Số tài khoản:</Text>
                                </Col>
                                <Col span={16}>
                                    <Text style={{ color: '#6c757d' }}>{vietQRConfig.accountNo}</Text>
                                    <Button
                                        type="link"
                                        size="small"
                                        icon={<CopyOutlined />}
                                        onClick={() => onCopyToClipboard(vietQRConfig.accountNo, "số tài khoản")}
                                        style={{ padding: 0, height: 'auto', marginLeft: 8 }}
                                    />
                                </Col>

                                <Col span={8}>
                                    <Text strong style={{ color: '#495057' }}>Chủ tài khoản:</Text>
                                </Col>
                                <Col span={16}>
                                    <Text style={{ color: '#6c757d' }}>{vietQRConfig.accountName}</Text>
                                    <Button
                                        type="link"
                                        size="small"
                                        icon={<CopyOutlined />}
                                        onClick={() => onCopyToClipboard(vietQRConfig.accountName, "tên chủ tài khoản")}
                                        style={{ padding: 0, height: 'auto', marginLeft: 8 }}
                                    />
                                </Col>

                                <Col span={8}>
                                    <Text strong style={{ color: '#495057' }}>Số tiền:</Text>
                                </Col>
                                <Col span={16}>
                                    <Text style={{ color: '#d32f2f', fontWeight: 'bold' }}>
                                        {bookingData.total.toLocaleString('vi-VN')} VNĐ
                                    </Text>
                                    <Button
                                        type="link"
                                        size="small"
                                        icon={<CopyOutlined />}
                                        onClick={() => onCopyToClipboard(bookingData.total.toString(), "số tiền")}
                                        style={{ padding: 0, height: 'auto', marginLeft: 8 }}
                                    />
                                </Col>

                                <Col span={8}>
                                    <Text strong style={{ color: '#495057' }}>Nội dung:</Text>
                                </Col>
                                <Col span={16}>
                                    <Text style={{ color: '#6c757d' }}>
                                        Thanh toan dat phong {bookingId}
                                    </Text>
                                    <Button
                                        type="link"
                                        size="small"
                                        icon={<CopyOutlined />}
                                        onClick={() => onCopyToClipboard(`Thanh toan dat phong ${bookingId}`, "nội dung chuyển khoản")}
                                        style={{ padding: 0, height: 'auto', marginLeft: 8 }}
                                    />
                                </Col>
                            </Row>
                        </Card>

                        <div style={{ textAlign: 'center', marginBottom: 16 }}>
                            <Space direction="vertical">
                                <div style={{
                                    padding: '8px 16px',
                                    backgroundColor: paymentStatus === "checking" ? '#e6f7ff' : paymentStatus === "success" ? '#f6ffed' : '#fff2e8',
                                    border: `1px solid ${paymentStatus === "checking" ? '#91d5ff' : paymentStatus === "success" ? '#b7eb8f' : '#ffcc7d'}`,
                                    borderRadius: '6px',
                                    color: paymentStatus === "checking" ? '#1890ff' : paymentStatus === "success" ? '#52c41a' : '#fa8c16'
                                }}>
                                    {paymentStatus === "pending" && (
                                        <><ClockCircleOutlined style={{ marginRight: 8 }} />Đang chờ thanh toán...</>
                                    )}
                                    {paymentStatus === "checking" && (
                                        <><SecurityScanOutlined style={{ marginRight: 8 }} />Đang kiểm tra thanh toán...</>
                                    )}
                                    {paymentStatus === "success" && (
                                        <><CheckCircleOutlined style={{ marginRight: 8 }} />Thanh toán thành công!</>
                                    )}
                                    {paymentStatus === "expired" && (
                                        <><WarningOutlined style={{ marginRight: 8 }} />Đã hết thời gian thanh toán</>
                                    )}
                                </div>

                                <Button
                                    icon={<ReloadOutlined />}
                                    onClick={onManualCheck}
                                    loading={loading}
                                    style={{ marginTop: 8 }}
                                >
                                    Kiểm tra thanh toán
                                </Button>
                            </Space>
                        </div>

                        <div style={{
                            backgroundColor: '#f0f8ff',
                            border: '1px solid #d1ecf1',
                            borderRadius: '6px',
                            padding: '12px',
                            marginTop: 16
                        }}>
                            <Text style={{ color: '#0c5460', fontSize: '13px' }}>
                                <InfoCircleOutlined style={{ marginRight: 8 }} />
                                Hệ thống sẽ tự động kiểm tra thanh toán mỗi 5 giây.
                                Nếu chậm cập nhật, bạn có thể nhấn nút "Kiểm tra thanh toán" để kiểm tra thủ công.
                            </Text>
                        </div>
                    </Card>
                </Col>
            </Row>
        </div>
    );
};

export default QRPaymentStep;
