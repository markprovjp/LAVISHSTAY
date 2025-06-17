import React from 'react';
import {
    Card,
    Typography,
    Button,
    Space,
    Tooltip,
    Tag,
    Flex,
    Progress
} from 'antd';
import {
    PlusOutlined,
    MinusOutlined,
    QrcodeOutlined,
    HomeOutlined,
    StopOutlined,
    CheckCircleOutlined,
    WarningOutlined,
    InfoCircleOutlined
} from '@ant-design/icons';
import { Room } from '../../mirage/models';

const { Text, Title } = Typography;

interface RoomOptionsSectionProps {
    room: Room;
    selectedRooms: { [roomId: string]: { [optionId: string]: number } };
    onQuantityChange: (roomId: string, optionId: string, quantity: number) => void;
    formatVND: (price: number) => string;
    getNights?: () => number;
}

const RoomOptionsSection: React.FC<RoomOptionsSectionProps> = ({
    room, selectedRooms,
    onQuantityChange,
    formatVND,
    getNights: _
}) => {
    const getAvailabilityStatus = (availability: any) => {
        const remaining = availability.remaining;
        const total = availability.total;
        const percentage = (remaining / total) * 100;

        if (remaining === 0) {
            return {
                color: '#ff4d4f',
                text: 'Hết phòng',
                percentage: 0,
                strokeColor: '#ff4d4f'
            };
        } else if (remaining === 1) {
            return {
                color: '#ff7875',
                text: `Chỉ còn ${remaining} phòng!`,
                percentage,
                strokeColor: '#ff7875'
            };
        } else if (remaining === 2) {
            return {
                color: '#fa8c16',
                text: `Chỉ còn ${remaining} phòng`,
                percentage,
                strokeColor: '#fa8c16'
            };
        } else if (remaining === 3) {
            return {
                color: '#faad14',
                text: `Còn ${remaining} phòng`,
                percentage,
                strokeColor: '#faad14'
            };
        } else if (remaining <= 5) {
            return {
                color: '#52c41a',
                text: `Còn ${remaining} phòng`,
                percentage,
                strokeColor: '#52c41a'
            };
        } else if (remaining <= 8) {
            return {
                color: '#1890ff',
                text: `${remaining} phòng có sẵn`,
                percentage,
                strokeColor: '#1890ff'
            };
        } else {
            return {
                color: '#722ed1',
                text: `${remaining} phòng có sẵn`,
                percentage,
                strokeColor: '#722ed1'
            };
        }
    }; const getCancellationPolicyDisplay = (policy: any) => {
        if (!policy) {
            return {
                text: 'Không hoàn tiền',
                icon: <StopOutlined />,
                description: 'Không có thông tin chính sách hủy'
            };
        }

        switch (policy.type) {
            case 'free':
                return {
                    text: 'Hủy miễn phí',
                    icon: <CheckCircleOutlined />,
                    description: policy.description || 'Hủy miễn phí'
                };
            case 'conditional':
                return {
                    text: 'Hủy có điều kiện',
                    icon: <WarningOutlined />,
                    description: policy.description || 'Hủy có điều kiện'
                };
            case 'non_refundable':
            default:
                return {
                    text: 'Phí hủy : toàn bộ tiền phòng',
                    icon: <StopOutlined />,
                    description: policy.description || 'Không hoàn tiền'
                };
        }
    };

    const getPaymentPolicyDisplay = (policy: string) => {
        switch (policy) {
            case 'pay_now_with_vietQR':
                return {
                    icon: <QrcodeOutlined />,
                    text: 'VietQR'
                };
            case 'pay_at_hotel':
            default:
                return {
                    icon: <HomeOutlined />,
                    text: 'Tại khách sạn'
                };
        }
    }; const displayOptions = room.options.slice(0, 2);
    const hasScrollableOptions = room.options.length > 2;

    return (
        <div className="space-y-3">
            <div
                className={hasScrollableOptions ? "max-h-80 overflow-y-auto space-y-3 pr-1" : "space-y-3"}
                style={{
                    scrollbarWidth: 'thin',
                    scrollbarColor: '#cbd5e1 transparent'
                }}
            >                {(hasScrollableOptions ? room.options : displayOptions).map((option: any) => {
                const availability = getAvailabilityStatus(option.availability);
                const cancellation = getCancellationPolicyDisplay(option.cancellationPolicy);
                const payment = getPaymentPolicyDisplay(option.paymentPolicy?.type);
                const isUnavailable = option.availability.remaining === 0;
                const currentQuantity = selectedRooms[room.id]?.[option.id] || 0;
                const isSelected = currentQuantity > 0;

                return (
                    <Card
                        key={option.id}
                        className={`
                                transition-all duration-200
                                ${isSelected
                                ? 'border-blue-500 bg-blue-50'
                                : 'border-gray-200 hover:border-blue-300 hover:shadow-sm'
                            } 
                                ${isUnavailable ? 'opacity-60' : ''}
                            `}
                        bodyStyle={{ padding: '16px' }}
                        style={{
                            borderRadius: '8px',
                            border: isSelected ? '2px solid #3b82f6' : '1px solid #e5e7eb'
                        }}
                    >
                        {/* Header với tên option và badges */}
                        <Flex justify="space-between" align="flex-start" className="mb-3">
                            <div className="flex-1 mr-4">
                                <div className="flex items-center gap-2 mb-1">
                                    <Title level={5} className="mb-0 text-gray-800" style={{ fontSize: '16px' }}>
                                        {option.name}
                                    </Title>

                                    {/* Simple badges */}
                                    {option.recommended && (
                                        <Tag color="gold" style={{ fontSize: '11px' }}>
                                            Đề xuất
                                        </Tag>
                                    )}
                                    {option.mostPopular && (
                                        <Tag color="red" style={{ fontSize: '11px' }}>
                                            Phổ biến
                                        </Tag>
                                    )}
                                </div>                                    {/* Availability display with progress bar */}
                                {!isUnavailable && (
                                    <div className="mb-2">
                                        <div className="flex items-center justify-between mb-1">
                                            <Text className="text-xs font-medium" style={{ color: availability.color }}>
                                                {availability.text}
                                            </Text>

                                        </div>
                                        <Progress
                                            status='active'
                                            percent={availability.percentage}
                                            size="small"
                                            strokeColor={availability.strokeColor}
                                            showInfo={false}
                                            trailColor="#f0f0f0"
                                        />
                                    </div>
                                )}

                                {isUnavailable && (
                                    <div className="mb-2">
                                        <div className="flex items-center gap-1">
                                            <StopOutlined style={{ color: '#ff4d4f', fontSize: '12px' }} />
                                            <Text className="text-xs font-medium text-red-500">
                                                Hết phòng
                                            </Text>
                                        </div>
                                        <Progress

                                            percent={0}
                                            size="small"
                                            strokeColor="#ff4d4f"
                                            showInfo={false}
                                            trailColor="#f0f0f0"
                                        />
                                    </div>
                                )}

                                {/* Warning cho capacity */}
                                {option.guestCountWarningDetail?.type === "exceeds_capacity" && (
                                    <Tag
                                        color="orange"
                                        icon={<WarningOutlined />}
                                        style={{ fontSize: '11px', marginBottom: '8px' }}
                                    >
                                        Cần đặt thêm phòng
                                    </Tag>
                                )}
                            </div>

                            {/* Price và quantity */}
                            <div className="flex items-center gap-3">                                <div className="text-right">
                                <div className="text-lg font-semibold text-gray-800">
                                    {formatVND(option.dynamicPricing?.finalPrice || option.pricePerNight.vnd)}
                                </div>
                                <div className="text-xs text-gray-500">/ đêm</div>
                                {option.dynamicPricing?.savings > 0 && (
                                    <div className="text-xs text-green-600 font-medium">
                                        Tiết kiệm {formatVND(option.dynamicPricing.savings)}
                                    </div>
                                )}
                                {option.dynamicPricing?.basePrice && option.dynamicPricing.basePrice !== (option.dynamicPricing?.finalPrice || option.pricePerNight.vnd) && (
                                    <div className="text-xs text-gray-400 line-through">
                                        {formatVND(option.dynamicPricing.basePrice)}
                                    </div>
                                )}
                            </div>

                                {/* Quantity selector */}
                                <div className="flex items-center border border-gray-300 rounded-md overflow-hidden bg-white">
                                    <Button
                                        type="text"
                                        icon={<MinusOutlined style={{ fontSize: '12px' }} />}
                                        size="small"
                                        disabled={currentQuantity === 0 || isUnavailable}
                                        onClick={() => onQuantityChange(room.id.toString(), option.id, currentQuantity - 1)}
                                        className="w-8 h-8 border-none hover:bg-gray-50"
                                        style={{ borderRadius: '0' }}
                                    />
                                    <div className="w-10 h-8 flex items-center justify-center bg-gray-50 border-x border-gray-200 text-sm font-medium">
                                        {currentQuantity}
                                    </div>
                                    <Button
                                        type="text"
                                        icon={<PlusOutlined style={{ fontSize: '12px' }} />}
                                        size="small"
                                        disabled={currentQuantity >= option.availability.remaining || isUnavailable}
                                        onClick={() => onQuantityChange(room.id.toString(), option.id, currentQuantity + 1)}
                                        className="w-8 h-8 border-none hover:bg-gray-50"
                                        style={{ borderRadius: '0' }}
                                    />
                                </div>
                            </div>
                        </Flex>                            {/* Policy info đơn giản */}
                        <div className="pt-2 border-t border-gray-100">
                            <Space size={8}>
                                <Tooltip title={cancellation.description}>
                                    <Tag
                                        icon={cancellation.icon}
                                        className="cursor-help"
                                        style={{
                                            fontSize: '11px',
                                            backgroundColor: '#f8fafc',
                                            border: '1px solid #e2e8f0',
                                            color: '#64748b'
                                        }}
                                    >
                                        {cancellation.text}
                                    </Tag>
                                </Tooltip>
                                <Tooltip title={`Thanh toán ${payment.text}`}>
                                    <Tag
                                        icon={payment.icon}
                                        className="cursor-help"
                                        style={{
                                            fontSize: '11px',
                                            backgroundColor: '#f8fafc',
                                            border: '1px solid #e2e8f0',
                                            color: '#64748b'
                                        }}
                                    >
                                        {payment.text}
                                    </Tag>
                                </Tooltip>
                            </Space>

                            {/* Hiển thị chi tiết chính sách hủy */}
                            {cancellation.description && (
                                <div className="mt-2">
                                    <Text className="text-xs text-gray-500" style={{ lineHeight: '1.3' }}>
                                        {cancellation.description}
                                    </Text>
                                </div>
                            )}
                        </div>
                    </Card>
                );
            })}
            </div>

            {/* Summary for additional options */}
            {!hasScrollableOptions && room.options.length > 2 && (
                <div className="text-center py-2 px-3 bg-gray-50 rounded-md border border-gray-200">
                    <Text className="text-sm text-gray-600">
                        <InfoCircleOutlined className="mr-1" />
                        Còn {room.options.length - 2} tùy chọn khác
                    </Text>
                </div>
            )}
        </div>
    );
};

export default RoomOptionsSection;