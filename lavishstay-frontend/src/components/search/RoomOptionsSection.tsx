import React from 'react';
import { Card, Typography, Button, Badge, Space, Progress, Divider, Alert } from 'antd';
import {
    PlusOutlined,
    MinusOutlined,
    QrcodeOutlined,
    HomeOutlined,
    StopOutlined,
    CheckCircleOutlined,
    WarningOutlined,
    GiftOutlined,
    InfoCircleOutlined
} from '@ant-design/icons';
import { Room } from '../../mirage/models';

const { Text } = Typography;

interface RoomOptionsSectionProps {
    room: Room;
    selectedRooms: { [roomId: string]: { [optionId: string]: number } };
    onQuantityChange: (roomId: string, optionId: string, quantity: number) => void;
    formatVND: (price: number) => string;
}

const RoomOptionsSection: React.FC<RoomOptionsSectionProps> = ({
    room,
    selectedRooms,
    onQuantityChange,
    formatVND
}) => {
    const getAvailabilityStatus = (availability: any) => {
        const remaining = availability.remaining;
        const total = availability.total;
        const percentage = (remaining / total) * 100;

        if (remaining === 0) {
            return {
                color: '#ef4444',
                text: 'Hết phòng',
                urgency: 'unavailable',
                percentage: 0,
                progressColor: '#ef4444'
            };
        } else if (percentage <= 20) {
            return {
                color: '#ef4444',
                text: `Chỉ còn ${remaining} phòng!`,
                urgency: 'high',
                percentage,
                progressColor: '#ef4444'
            };
        } else if (percentage <= 30) {
            return {
                color: '#f59e0b',
                text: `Còn ${remaining} phòng`,
                urgency: 'medium',
                percentage,
                progressColor: '#f59e0b'
            };
        }
        return {
            color: '#10b981',
            text: `Còn ${remaining} phòng`,
            urgency: 'low',
            percentage,
            progressColor: '#10b981'
        };
    };

    const getCancellationPolicyDisplay = (policy: string) => {
        switch (policy) {
            case 'free':
                return {
                    text: 'Hủy miễn phí',
                    color: '#10b981',
                    badge: { color: 'success', icon: <CheckCircleOutlined /> }
                };
            case 'conditional':
                return {
                    text: 'Hủy có điều kiện',
                    color: '#f59e0b',
                    badge: { color: 'warning', icon: <WarningOutlined /> }
                };
            case 'non_refundable':
            default:
                return {
                    text: 'Không hoàn tiền',
                    color: '#ef4444',
                    badge: { color: 'error', icon: <StopOutlined /> }
                };
        }
    };

    const getPaymentPolicyDisplay = (policy: string) => {
        switch (policy) {
            case 'pay_now_with_vietQR':
                return {
                    icon: <QrcodeOutlined />,
                    text: 'Thanh toán ngay bằng VietQR',
                    color: '#3b82f6',
                    badge: { color: 'processing' }
                };
            case 'pay_at_hotel':
            default:
                return {
                    icon: <HomeOutlined />,
                    text: 'Thanh toán tại khách sạn',
                    color: '#10b981',
                    badge: { color: 'success' }
                };
        }
    };

    const displayOptions = room.options.slice(0, 2); // Hiển thị tối đa 2 options
    const hasScrollableOptions = room.options.length > 2;

    return (
        <div className="space-y-3">
            {/* Options container với cuộn nếu > 2 options */}
            <div
                className={hasScrollableOptions ? "max-h-80 overflow-y-auto space-y-3 pr-1" : "space-y-3"}
                style={{
                    scrollbarWidth: 'thin',
                    scrollbarColor: '#cbd5e1 #f1f5f9'
                }}
            >
                {(hasScrollableOptions ? room.options : displayOptions).map((option: any) => {
                    const availability = getAvailabilityStatus(option.availability);
                    const cancellation = getCancellationPolicyDisplay(option.cancellationPolicy?.type);
                    const payment = getPaymentPolicyDisplay(option.paymentPolicy?.type);
                    const isUnavailable = option.availability.remaining === 0;
                    const currentQuantity = selectedRooms[room.id]?.[option.id] || 0;
                    const isSelected = currentQuantity > 0;

                    return (
                        <Card
                            key={option.id}
                            className={`transition-all duration-200 ${isSelected
                                    ? 'border-blue-300 bg-blue-50/30 shadow-md'
                                    : 'border-gray-200 hover:border-blue-200 hover:shadow-sm'
                                } ${isUnavailable ? 'opacity-60' : ''}`}
                            bodyStyle={{ padding: '14px' }}
                            size="small"
                        >
                            {/* Header */}
                            <div className="flex items-start justify-between mb-3">
                                <div className="flex-1">
                                    <div className="flex items-center gap-2 mb-2">
                                        <Text strong className="text-sm ">
                                            {option.name}
                                        </Text>

                                        {/* Option badges */}
                                        {option.recommended && (
                                            <Badge
                                                count="Đề xuất"
                                                style={{
                                                    backgroundColor: '#3b82f6',
                                                    fontSize: '10px',
                                                    height: '18px',
                                                    lineHeight: '18px',
                                                    borderRadius: '9px'
                                                }}
                                            />
                                        )}
                                        {option.mostPopular && (
                                            <Badge
                                                count="Phổ biến"
                                                style={{
                                                    backgroundColor: '#ef4444',
                                                    fontSize: '10px',
                                                    height: '18px',
                                                    lineHeight: '18px',
                                                    borderRadius: '9px'
                                                }}
                                            />
                                        )}
                                        {option.promotion && (
                                            <Badge
                                                count={option.promotion.message}
                                                style={{
                                                    backgroundColor: '#f59e0b',
                                                    fontSize: '10px',
                                                    height: '18px',
                                                    lineHeight: '18px',
                                                    borderRadius: '9px'
                                                }}
                                            />
                                        )}
                                    </div>

                                    {/* Progress bar cho availability */}
                                    <div className="mb-2">
                                        <Progress
                                            status='active'
                                            
                                            percent={availability.percentage}
                                            size="small"
                                            strokeColor={{
                                                '0%': availability.progressColor,
                                                '100%': availability.progressColor === '#ef4444' ? '#dc2626' :
                                                    availability.progressColor === '#f59e0b' ? '#d97706' : '#059669'
                                            }}
                                            trailColor="#f1f5f9"
                                            showInfo={false}
                                            strokeWidth={6}
                                        />
                                        <Text className="text-xs" style={{ color: availability.color }}>
                                            {availability.text}
                                        </Text>
                                    </div>
                                </div>

                                {/* Price and Controls */}
                                <div className="flex items-center gap-3">
                                    <div className="text-right">
                                        <Text strong className="text-blue-600 text-base">
                                            {formatVND(option.pricePerNight.vnd)}
                                        </Text>
                                        <Text className="text-xs  block">/ đêm</Text>
                                    </div>

                                    {/* Quantity Selector */}
                                    <div className="flex items-center border border-gray-300 rounded-lg overflow-hidden ">
                                        <Button
                                            type="text"
                                            icon={<MinusOutlined />}
                                            size="small"
                                            disabled={currentQuantity === 0 || isUnavailable}
                                            onClick={() => onQuantityChange(room.id.toString(), option.id, currentQuantity - 1)}
                                            className="w-8 h-8 flex items-center justify-center border-none   hover:text-blue-600"
                                        />
                                        <div className="w-10 h-8 flex items-center justify-center  border-x border-gray-200">
                                            <Text className="text-sm font-medium ">{currentQuantity}</Text>
                                        </div>
                                        <Button
                                            type="text"
                                            icon={<PlusOutlined />}
                                            size="small"
                                            disabled={currentQuantity >= option.availability.remaining || isUnavailable}
                                            onClick={() => onQuantityChange(room.id.toString(), option.id, currentQuantity + 1)}
                                            className="w-8 h-8 flex items-center justify-center border-none   hover:text-blue-600"
                                        />
                                    </div>
                                </div>
                            </div>

                            <Divider style={{ margin: '8px 0' }} />

                            {/* Policy Information với Badges */}
                            <div className="flex items-center justify-between">
                                <Space size={8} wrap>
                                    <Badge
                                        color={cancellation.badge.color}
                                        text={
                                            <span className="text-xs flex items-center gap-1">
                                                {cancellation.badge.icon}
                                                {cancellation.text}
                                            </span>
                                        }
                                    />
                                    <Badge
                                        color={payment.badge.color}
                                        text={
                                            <span className="text-xs flex items-center gap-1">
                                                {payment.icon}
                                                {payment.text}
                                            </span>
                                        }
                                    />
                                </Space>

                                {/* Additional info từ dynamicPricing */}
                                {option.dynamicPricing && (
                                    <div className="text-xs  flex items-center gap-1">
                                        {option.dynamicPricing.savings > 0 && (
                                            <span className="text-green-600 font-medium ">
                                                <GiftOutlined />
                                                Tiết kiệm {formatVND(option.dynamicPricing.savings)}
                                            </span>
                                        )}
                                    </div>
                                )}
                            </div>                            {/* Additional notifications từ option và dynamicPricing */}
                            {option.guestCountWarning && (
                                <Alert
                                    message={option.guestCountWarning}
                                    type="warning"
                                    showIcon
                                    className="mt-2"
                                />
                            )}

                            {option.guestCountWarningDetail && (
                                <Alert
                                    message={option.guestCountWarningDetail.message}
                                    description={option.guestCountWarningDetail.suggestedAction}
                                    type={option.guestCountWarningDetail.type === "exceeds_capacity" ? "error" : "info"}
                                    showIcon
                                    className="mt-2"
                                />
                            )}

                            {option.availability.urgencyMessage && (
                                <Alert
                                    message={option.availability.urgencyMessage}
                                    type="error"
                                    showIcon
                                    className="mt-2 "
                                />
                            )}

                            {/* Hiển thị tất cả thông báo từ dynamicPricing */}
                            {option.dynamicPricing?.adjustments && option.dynamicPricing.adjustments.length > 0 && (
                                <div className="mt-2">
                                    {option.dynamicPricing.adjustments
                                        .filter((adj: any) => adj.reason && adj.reason.length > 0)
                                        .slice(0, 2) // Hiển thị tối đa 2 điều chỉnh để tránh quá tải
                                        .map((adjustment: any, index: number) => (
                                            <Alert
                                                key={index}
                                                message={adjustment.reason}
                                                type={adjustment.type === 'decrease' ? 'success' : 'warning'}
                                                showIcon
                                                className="mb-1"
                                            />
                                        ))
                                    }
                                </div>
                            )}
                        </Card>
                    );
                })}
            </div>

            {/* Chỉ hiển thị thông báo "+X tùy chọn khác" nếu không cuộn */}
            {!hasScrollableOptions && room.options.length > 2 && (
                <div className="text-center py-2">
                    <Text className="text-xs  flex items-center justify-center gap-1">
                        <InfoCircleOutlined />
                        +{room.options.length - 2} tùy chọn khác
                    </Text>
                </div>
            )}
        </div>
    );
};

export default RoomOptionsSection;
