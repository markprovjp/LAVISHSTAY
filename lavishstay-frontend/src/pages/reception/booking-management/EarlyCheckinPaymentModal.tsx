import React, { useState, useCallback, useEffect } from 'react';
import {
    Modal,
    Card,
    Radio,
    Button,
    Space,
    Typography,
    Image,
    Alert,
    Row,
    Col,
    Descriptions,
    Timeline,
    Spin,
    App
} from 'antd';
import {
    CheckCircleOutlined,
    ClockCircleOutlined,
    ExclamationCircleOutlined,
    QrcodeOutlined,
    DollarOutlined
} from '@ant-design/icons';
import { paymentAPI } from '../../../utils/api';

const { Text } = Typography;

interface EarlyCheckinPaymentModalProps {
    visible: boolean;
    onClose: () => void;
    onSuccess: () => void;
    bookingId: number;
    bookingCode: string;
    feeAmount: number;
}

type CheckHistoryStatus = 'checking' | 'not_found' | 'found' | 'error' | 'stopped';

interface CheckHistoryItem {
    time: string;
    status: CheckHistoryStatus;
    message: string;
}

const EarlyCheckinPaymentModal: React.FC<EarlyCheckinPaymentModalProps> = ({
    visible,
    onClose,
    onSuccess,
    bookingId,
    bookingCode,
    feeAmount
}) => {
    const { message } = App.useApp();
    const [selectedPaymentMethod, setSelectedPaymentMethod] = useState('vietqr');
    const [isCheckingPayment, setIsCheckingPayment] = useState(false);
    const [checkHistory, setCheckHistory] = useState<CheckHistoryItem[]>([]);
    const [stopCheck, setStopCheck] = useState(false);
    const [paymentId, setPaymentId] = useState<string | null>(null);
    const [qrUrl, setQrUrl] = useState<string>('');
    const [isGenerating, setIsGenerating] = useState(false);
    const [paymentContent, setPaymentContent] = useState<string | null>(null);
    const [bankInfo, setBankInfo] = useState<{ account_name?: string; account_no?: string; bank_id?: string } | null>(null);

    const addToHistory = (status: CheckHistoryStatus, msg: string) => {
        const newEntry: CheckHistoryItem = {
            time: new Date().toLocaleTimeString('vi-VN'),
            status,
            message: msg
        };
        setCheckHistory(prev => [newEntry, ...prev.slice(0, 4)]);
    };

    // Generate QR and payment ID when modal opens and VietQR is selected
    useEffect(() => {
        // Only generate QR when modal opens, VietQR selected, and no existing QR/payment present
        if (visible && selectedPaymentMethod === 'vietqr' && !paymentId && !qrUrl && !isGenerating) {
            generateQRCode();
        }
    }, [visible, selectedPaymentMethod, paymentId, qrUrl, isGenerating]);

    const generateQRCode = async () => {
        // Prevent duplicate generation or calls with missing data
        if (isGenerating) return;
        if (!bookingId || !bookingCode || !feeAmount) {
            message.error('Thiếu thông tin thanh toán. Vui lòng thử lại.');
            return;
        }
        if (paymentId || qrUrl) {
            // already generated
            return;
        }

        setIsGenerating(true);
        try {
            const response = await paymentAPI.createActionFeeQR({
                booking_id: bookingId,
                action_type: 'extend',
                amount: Number(feeAmount),
                booking_code: bookingCode,
                description: `Phí check-in sớm cho booking ${bookingCode}`
            });

            if (response && response.success && response.data) {
                const data = response.data;
                setPaymentId(data.payment_id);
                setQrUrl(data.qr_url);
                setPaymentContent(data.payment_content || data.payment_content || null);
                setBankInfo(data.bank_info || null);
            } else if (response && response.data && !response.data.success) {
                // backend responded but marked failure
                message.error('Không thể tạo mã QR thanh toán: ' + (response.data.message || ''));
            } else {
                message.error('Không thể tạo mã QR thanh toán');
            }
        } catch (error: any) {
            console.error('Error generating QR:', error);
            // If validation errors from backend exist, include them in the message
            const serverMessage = error.response?.data?.message;
            const serverErrors = error.response?.data?.errors;
            if (serverErrors) {
                const first = Object.values(serverErrors).flat()[0];
                message.error('Lỗi tạo mã QR: ' + (first || serverMessage || error.message));
            } else {
                message.error('Lỗi tạo mã QR: ' + (serverMessage || error.message));
            }
        } finally {
            setIsGenerating(false);
        }
    };

    const handlePaymentCheck = useCallback(async () => {
        if (!paymentId) {
            message.error('Chưa có mã thanh toán');
            return;
        }

        setStopCheck(false);
        setIsCheckingPayment(true);
        setCheckHistory([]);
        addToHistory('checking', 'Bắt đầu kiểm tra thanh toán...');

        const maxAttempts = 12;
        for (let attempt = 1; attempt <= maxAttempts; attempt++) {
            if (stopCheck) {
                addToHistory('stopped', 'Đã dừng kiểm tra.');
                setIsCheckingPayment(false);
                break;
            }

            try {
                addToHistory('checking', `Đang kiểm tra lần ${attempt}/${maxAttempts}...`);

                const response = await paymentAPI.checkActionFeePayment({
                    payment_id: paymentId,
                    booking_code: bookingCode,
                    action_type: 'extend',
                    booking_id: bookingId
                });

                // paymentAPI returns response.data from axios wrapper
                const res = response || {};

                const found = res.payment_found || res.success && res.payment_found;
                if (found && res.payment) {
                    addToHistory('found', 'Thanh toán phí check-in sớm thành công!');
                    setIsCheckingPayment(false);
                    message.success('Thanh toán thành công! Có thể check-in ngay.');
                    onSuccess();
                    return;
                } else {
                    const serverMsg = res.message || (res.data && res.data.message) || 'Chưa tìm thấy giao dịch.';
                    addToHistory('not_found', serverMsg);
                }

                if (attempt < maxAttempts) {
                    await new Promise(resolve => setTimeout(resolve, 5000));
                }
            } catch (error: any) {
                // Log full error for debugging
                console.error('checkActionFeePayment error:', error);
                const status = error.response?.status;
                const respBody = error.response?.data;
                const errorMessage = respBody?.message || `Lỗi kết nối server${status ? ' (status ' + status + ')' : ''}`;
                addToHistory('error', typeof respBody === 'string' ? respBody : (respBody?.message || JSON.stringify(respBody || {})));
                // surface to user on final attempt
                if (attempt === maxAttempts) {
                    message.error('Không thể xác nhận thanh toán tự động: ' + (respBody?.message || `status=${status}`));
                }
                if (attempt < maxAttempts) {
                    await new Promise(resolve => setTimeout(resolve, 5000));
                }
            }
        }
        setIsCheckingPayment(false);
    }, [paymentId, bookingCode, bookingId, stopCheck, onSuccess, message]);

    const handleCashPayment = () => {
        message.success('Đã xác nhận thanh toán tiền mặt phí check-in sớm.');
        onSuccess();
    };

    const getStatusIcon = (status: CheckHistoryStatus) => {
        switch (status) {
            case 'checking': return <ClockCircleOutlined style={{ color: '#1890ff' }} />;
            case 'found': return <CheckCircleOutlined style={{ color: '#52c41a' }} />;
            case 'error': return <ExclamationCircleOutlined style={{ color: '#ff4d4f' }} />;
            case 'stopped': return <ExclamationCircleOutlined style={{ color: '#faad14' }} />;
            default: return <ClockCircleOutlined style={{ color: '#faad14' }} />;
        }
    };

    const formatCurrency = (amount: number) => {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(amount);
    };

    return (
        <Modal
            title="Thanh toán phí check-in sớm"
            open={visible}
            onCancel={onClose}
            footer={null}
            width={800}
            destroyOnClose
        >
            <Space direction="vertical" size="large" style={{ width: '100%' }}>
                {/* Summary */}
                <Card size="small">
                    <Descriptions column={2} bordered size="small">
                        <Descriptions.Item label="Mã đặt phòng">{bookingCode}</Descriptions.Item>
                        <Descriptions.Item label="Phí check-in sớm">
                            <Text strong style={{ color: '#1890ff', fontSize: '16px' }}>
                                {formatCurrency(feeAmount)}
                            </Text>
                        </Descriptions.Item>
                    </Descriptions>
                </Card>

                {/* Payment Method Selection */}
                <Card title="Chọn phương thức thanh toán" size="small">
                    <Radio.Group
                        value={selectedPaymentMethod}
                        onChange={e => setSelectedPaymentMethod(e.target.value)}
                        style={{ width: '100%' }}
                    >
                        <Space direction="vertical" style={{ width: '100%' }}>
                            <Radio value="vietqr">
                                <Space>
                                    <QrcodeOutlined />
                                    <span>Chuyển khoản VietQR</span>
                                </Space>
                            </Radio>
                            <Radio value="cash">
                                <Space>
                                    <DollarOutlined />
                                    <span>Tiền mặt tại quầy</span>
                                </Space>
                            </Radio>
                        </Space>
                    </Radio.Group>
                </Card>

                {/* VietQR Payment */}
                {selectedPaymentMethod === 'vietqr' && (
                    <Card title="Thanh toán chuyển khoản" size="small">
                        <Row gutter={[24, 24]}>
                            <Col xs={24} md={12}>
                                {qrUrl ? (
                                    <div style={{ textAlign: 'center' }}>
                                        <Image
                                            src={qrUrl}
                                            alt="VietQR Code"
                                            width={250}
                                            preview={false}
                                        />
                                        <div style={{ marginTop: 16 }}>
                                            <Button
                                                type="primary"
                                                icon={<CheckCircleOutlined />}
                                                loading={isCheckingPayment}
                                                onClick={handlePaymentCheck}
                                                size="large"
                                            >
                                                {isCheckingPayment ? 'Đang kiểm tra...' : 'Kiểm tra thanh toán'}
                                            </Button>
                                            {isCheckingPayment && (
                                                <Button
                                                    style={{ marginLeft: 8 }}
                                                    onClick={() => setStopCheck(true)}
                                                >
                                                    Dừng kiểm tra
                                                </Button>
                                            )}
                                        </div>
                                    </div>
                                ) : (
                                    <div style={{ textAlign: 'center', padding: '40px 0' }}>
                                        <Spin size="large" />
                                        <div style={{ marginTop: 16 }}>Đang tạo mã QR...</div>
                                    </div>
                                )}
                            </Col>
                            <Col xs={24} md={12}>
                                <Alert
                                    message="Hướng dẫn thanh toán"
                                    description={
                                        <ol>
                                            <li>Quét mã QR bằng app ngân hàng</li>
                                            <li>Kiểm tra thông tin chuyển khoản</li>
                                            <li>Xác nhận thanh toán</li>
                                            <li>Bấm "Kiểm tra thanh toán" để xác minh</li>
                                        </ol>
                                    }
                                    type="info"
                                    showIcon
                                />

                                {/* Bank / transfer info (copyable) */}
                                {(paymentContent || bankInfo) && (
                                    <Card
                                        title="Thông tin chuyển khoản"
                                        size="small"
                                        style={{ marginTop: 16 }}
                                    >
                                        <div style={{ marginBottom: 8 }}>
                                            <Text strong>Ngân hàng: </Text>
                                            <Text>{bankInfo?.bank_id || '-'} - {bankInfo?.account_name || '-'}</Text>
                                        </div>
                                        <div style={{ marginBottom: 8 }}>
                                            <Text strong>Số tài khoản: </Text>
                                            <Text copyable={{ text: bankInfo?.account_no || '' }}>{bankInfo?.account_no || '-'}</Text>
                                        </div>
                                        <div style={{ marginBottom: 8 }}>
                                            <Text strong>Nội dung chuyển khoản: </Text>
                                            <div>
                                                <Text copyable={{ text: paymentContent || '' }}>{paymentContent || '-'}</Text>
                                            </div>
                                        </div>
                                        <div>
                                            <Text strong>Mã đặt phòng: </Text>
                                            <Text copyable={{ text: bookingCode }}>{bookingCode}</Text>
                                        </div>
                                    </Card>
                                )}

                                {/* Payment History */}
                                {checkHistory.length > 0 && (
                                    <Card
                                        title="Lịch sử kiểm tra"
                                        size="small"
                                        style={{ marginTop: 16 }}
                                    >
                                        <Timeline>
                                            {checkHistory.map((item, index) => (
                                                <Timeline.Item
                                                    key={index}
                                                    dot={getStatusIcon(item.status)}
                                                >
                                                    <div>
                                                        <Text strong>{item.time}</Text>
                                                        <br />
                                                        <Text>{item.message}</Text>
                                                    </div>
                                                </Timeline.Item>
                                            ))}
                                        </Timeline>
                                    </Card>
                                )}
                            </Col>
                        </Row>
                    </Card>
                )}

                {/* Cash Payment */}
                {selectedPaymentMethod === 'cash' && (
                    <Card title="Thanh toán tiền mặt" size="small">
                        <Alert
                            message="Xác nhận thanh toán tiền mặt"
                            description={`Khách hàng thanh toán ${formatCurrency(feeAmount)} tiền mặt tại quầy lễ tân.`}
                            type="info"
                            showIcon
                            style={{ marginBottom: 16 }}
                        />
                        <Button
                            type="primary"
                            icon={<CheckCircleOutlined />}
                            onClick={handleCashPayment}
                            size="large"
                        >
                            Xác nhận đã nhận tiền mặt
                        </Button>
                    </Card>
                )}
            </Space>
        </Modal>
    );
};

export default EarlyCheckinPaymentModal;
