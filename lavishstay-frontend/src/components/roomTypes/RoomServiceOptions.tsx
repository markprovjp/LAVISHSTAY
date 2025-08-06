import React, { useState, useEffect } from "react";
import { Card, Button, Tag, Typography, Space, Progress, Divider } from "antd";
import {
    StarFilled,
    UserOutlined,
    InfoCircleOutlined,
    CoffeeOutlined,
    CheckCircleOutlined,
    GiftOutlined,
    CreditCardOutlined,
    DollarOutlined,
    ExclamationCircleOutlined,
    SafetyCertificateOutlined,
    MinusOutlined,
    PlusOutlined,
    ShoppingCartOutlined,
} from "@ant-design/icons";
import { RoomOption } from "../../mirage/roomoption";

const { Title, Text } = Typography;

interface RoomServiceOptionsProps {
    roomOptions?: RoomOption[];
    numberOfNights?: number;
}

const RoomServiceOptions: React.FC<RoomServiceOptionsProps> = ({
    roomOptions = [],
    numberOfNights = 1
}) => {
    const [roomQuantities, setRoomQuantities] = useState<{ [optionId: string]: number }>({});

    // Use provided options or empty array
    const options = roomOptions;

    useEffect(() => {
        if (options && options.length > 0) {
            // Initialize room quantities to 0 (no selection initially)
            const initialQuantities: { [optionId: string]: number } = {};
            options.forEach(option => {
                initialQuantities[option.id] = 0;
            });
            setRoomQuantities(initialQuantities);
        }
    }, [options]);

    // Format VND currency
    const formatVND = (price: number) => {
        return new Intl.NumberFormat("vi-VN", {
            style: "currency",
            currency: "VND",
        }).format(price);
    };

    // Get cancellation policy display
    const getCancellationPolicyDisplay = (policy: RoomOption['cancellationPolicy']) => {
        switch (policy.type) {
            case 'free':
                return { text: 'Hủy miễn phí', color: 'green', icon: <CheckCircleOutlined /> };
            case 'conditional':
                return { text: 'Hủy có điều kiện', color: 'orange', icon: <InfoCircleOutlined /> };
            case 'non_refundable':
                return { text: 'Không hoàn tiền', color: 'red', icon: <ExclamationCircleOutlined /> };
            default:
                return { text: 'Liên hệ', color: 'gray', icon: <InfoCircleOutlined /> };
        }
    };

    // Get payment policy display
    const getPaymentPolicyDisplay = (policy: RoomOption['paymentPolicy']) => {
        switch (policy.type) {
            case 'pay_now_with_vietQR':
                return { text: 'Thanh toán ngay với VietQR', color: 'blue', icon: <CreditCardOutlined /> };
            case 'pay_at_hotel':
                return { text: 'Thanh toán tại khách sạn', color: 'green', icon: <DollarOutlined /> };
            default:
                return { text: 'Liên hệ', color: 'gray', icon: <InfoCircleOutlined /> };
        }
    };    // Get availability status
    const getAvailabilityStatus = (availability: RoomOption['availability']) => {
        const { remaining, total } = availability;
        const percentage = (remaining / total) * 100;

        if (remaining === 0) {
            return { color: "red", text: "Hết phòng", urgent: true };
        } else if (remaining <= 3) {
            return { color: "orange", text: `Chỉ còn ${remaining} phòng`, urgent: true };
        } else if (percentage <= 30) {
            return { color: "gold", text: `${remaining} phòng còn lại`, urgent: false };
        } else {
            return { color: "green", text: `${remaining} phòng có sẵn`, urgent: false };
        }
    };    // Handle room quantity change
    const handleQuantityChange = (optionId: string, quantity: number) => {
        setRoomQuantities(prev => ({
            ...prev,
            [optionId]: quantity
        }));
    };    // Calculate total price for an option (including multiple nights)
    const calculateTotalPrice = (option: RoomOption, quantity: number) => {
        const finalPrice = option.dynamicPricing?.finalPrice || option.pricePerNight.vnd;
        return finalPrice * quantity * numberOfNights;
    };

    // Calculate total for all selected rooms
    const calculateGrandTotal = () => {
        return Object.entries(roomQuantities).reduce((total, [optionId, quantity]) => {
            if (quantity > 0) {
                const option = options.find(opt => opt.id === optionId);
                if (option) {
                    return total + calculateTotalPrice(option, quantity);
                }
            }
            return total;
        }, 0);
    };

    // Get selected rooms count
    const getSelectedRoomsCount = () => {
        return Object.values(roomQuantities).reduce((total, quantity) => total + quantity, 0);
    };

    if (!options || options.length === 0) {
        return (
            <Card className="mt-6">
                <div className="text-center py-8">
                    <Text type="secondary">Không có lựa chọn dịch vụ cho phòng này</Text>
                </div>
            </Card>
        );
    } return (
        <div className="room-service-options-container">
            <Card className="room-service-card">
                <div className="room-service-header">
                    <Title level={3} className="room-service-title">
                        Lựa chọn dịch vụ phòng
                    </Title>
                    <Text className="room-service-subtitle">
                        Chọn số lượng phòng cho từng loại dịch vụ bạn muốn ({numberOfNights} đêm)
                    </Text>
                </div>

                {/* Room Options */}
                <div className="room-options-grid">
                    <Space direction="vertical" className="w-full" size={24}>
                        {options.map((option) => {
                            const availability = getAvailabilityStatus(option.availability);
                            const cancellation = getCancellationPolicyDisplay(option.cancellationPolicy);
                            const payment = getPaymentPolicyDisplay(option.paymentPolicy);
                            const isUnavailable = option.availability.remaining === 0;
                            const currentQuantity = roomQuantities[option.id] || 0;
                            const isSelected = currentQuantity > 0; return (
                                <Card
                                    key={option.id}
                                    className={`room-option-card ${isSelected ? 'selected' : ''} ${isUnavailable ? 'unavailable' : ''}`}
                                >                                    <div className="room-option-content">
                                        {/* Left Section - Room Info */}
                                        <div className="room-info-section">                                            {/* Room Header */}
                                            <div className="room-header">
                                                <div className="room-title-section">
                                                    <div className="room-name-badges">
                                                        <Title level={4} className="room-name">
                                                            {option.name}
                                                        </Title>                                                        {option.recommended && (
                                                            <Tag color="gold" className="badge-recommended">
                                                                <StarFilled /> Đề xuất
                                                            </Tag>
                                                        )}
                                                        {option.mostPopular && (
                                                            <Tag color="red" className="badge-popular">
                                                                🔥 Phổ biến
                                                            </Tag>
                                                        )}
                                                        {option.promotion && (
                                                            <Tag color="orange" className="badge-promotion">
                                                                {option.promotion.message}
                                                            </Tag>
                                                        )}
                                                    </div>

                                                    {/* Tags */}
                                                    <div className="room-tags">
                                                        <Tag icon={<UserOutlined />} className="tag-guests">
                                                            {option.minGuests} - {option.maxGuests} khách/phòng
                                                        </Tag>
                                                        <Tag
                                                            icon={cancellation.icon}
                                                            className="tag-cancellation"
                                                            color={cancellation.color}
                                                        >
                                                            {cancellation.text}
                                                        </Tag>
                                                        <Tag
                                                            icon={payment.icon}
                                                            className="tag-payment"
                                                            color={payment.color}
                                                        >
                                                            {payment.text}
                                                        </Tag>
                                                    </div>
                                                </div>
                                            </div>

                                            {/* Meal Options */}
                                            <div className="mb-3">
                                                <Text className="text-sm text-gray-600 block mb-2">Bữa ăn bao gồm:</Text>
                                                <div className="flex flex-wrap gap-2">
                                                    {option.mealOptions.breakfast && (
                                                        <Tag
                                                            color={option.mealOptions.breakfast.included ? "green" : "orange"}
                                                            icon={<CoffeeOutlined />}
                                                            className="border-0"
                                                        >
                                                            {option.mealOptions.breakfast.included
                                                                ? "Bữa sáng miễn phí"
                                                                : `Bữa sáng +${formatVND(option.mealOptions.breakfast.price || 0)}`
                                                            }
                                                        </Tag>
                                                    )}
                                                    {option.mealOptions.dinner && (
                                                        <Tag
                                                            color={option.mealOptions.dinner.included ? "green" : "orange"}
                                                            icon={<GiftOutlined />}
                                                            className="border-0"
                                                        >
                                                            {option.mealOptions.dinner.included
                                                                ? "Bữa tối miễn phí"
                                                                : `Bữa tối +${formatVND(option.mealOptions.dinner.price || 0)}`
                                                            }
                                                        </Tag>
                                                    )}
                                                </div>
                                            </div>

                                            {/* Additional Services */}
                                            {option.additionalServices && option.additionalServices.length > 0 && (
                                                <div className="mb-3">
                                                    <Text className="text-sm text-gray-600 block mb-2">Dịch vụ bổ sung:</Text>
                                                    <div className="flex flex-wrap gap-1">
                                                        {option.additionalServices.map((service, index) => (
                                                            <Tag key={index} className="text-xs border-0 bg-gray-100">
                                                                {service.name}
                                                                {service.price && ` (+${service.price})`}
                                                            </Tag>
                                                        ))}
                                                    </div>
                                                </div>
                                            )}

                                            {/* Availability */}
                                            <div className="mb-3">
                                                <div className="flex justify-between items-center mb-2">
                                                    <Text className="text-sm text-gray-600">Tình trạng phòng:</Text>
                                                    <Tag color={availability.color} className="text-xs border-0">
                                                        {availability.text}
                                                    </Tag>
                                                </div>
                                                <Progress
                                                    percent={Math.max(5, (option.availability.remaining / option.availability.total) * 100)}
                                                    status={isUnavailable ? "exception" : "active"}
                                                    strokeColor={
                                                        availability.color === 'red' ? '#ff4d4f' :
                                                            availability.color === 'orange' ? '#fa8c16' : '#52c41a'
                                                    }
                                                    size="small"
                                                    showInfo={false}
                                                />
                                                {option.availability.urgencyMessage && (
                                                    <Text type="warning" className="text-xs block mt-1">
                                                        ⚡ {option.availability.urgencyMessage}
                                                    </Text>
                                                )}
                                            </div>

                                            {/* Policy Details */}
                                            <div className="text-xs text-gray-500 space-y-1 border-t border-gray-100 pt-3">
                                                <div className="flex items-start gap-1">
                                                    <SafetyCertificateOutlined className="mt-0.5" />
                                                    <span>{option.cancellationPolicy.description}</span>
                                                </div>
                                                <div className="flex items-start gap-1">
                                                    <CreditCardOutlined className="mt-0.5" />
                                                    <span>{option.paymentPolicy.description}</span>
                                                </div>
                                            </div>
                                        </div>                                        {/* Right Section - Price & Quantity */}
                                        <div className="price-quantity-section">
                                            <div className="price-card">
                                                {/* Price */}                                                <div className="text-center mb-4">
                                                    <Text className="text-sm text-gray-500 block">
                                                        Tổng giá cho {numberOfNights} đêm
                                                    </Text>
                                                    <div className="text-2xl font-bold text-blue-600 mb-1">
                                                        {formatVND((option.dynamicPricing?.finalPrice || option.pricePerNight.vnd) * numberOfNights)}
                                                    </div>
                                                    <Text className="text-xs text-gray-400">
                                                        {formatVND(option.dynamicPricing?.finalPrice || option.pricePerNight.vnd)}/phòng/đêm
                                                    </Text>
                                                    {option.promotion?.discount && (
                                                        <Text className="text-xs text-green-600 block mt-1">
                                                            Tiết kiệm {option.promotion.discount}%
                                                        </Text>
                                                    )}
                                                    {option.dynamicPricing?.basePrice && option.dynamicPricing.basePrice !== (option.dynamicPricing?.finalPrice || option.pricePerNight.vnd) && (
                                                        <div className="text-xs text-gray-400 line-through">
                                                            Giá gốc: {formatVND(option.dynamicPricing.basePrice)}/đêm
                                                        </div>
                                                    )}
                                                </div>

                                                {/* Quantity Selector */}
                                                <div className="flex-1 flex flex-col justify-center">
                                                    <div className="text-center mb-3">
                                                        <Text className="text-sm text-gray-600 block mb-2">Số lượng phòng</Text>
                                                        <div className="flex items-center justify-center gap-3">
                                                            <Button
                                                                type="text"
                                                                icon={<MinusOutlined />}
                                                                size="large"
                                                                onClick={() => {
                                                                    if (currentQuantity > 0) {
                                                                        handleQuantityChange(option.id, currentQuantity - 1);
                                                                    }
                                                                }}
                                                                disabled={isUnavailable || currentQuantity <= 0}
                                                                className="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-lg hover:border-blue-400"
                                                            />
                                                            <div className="w-16 h-10 flex items-center justify-center bg-gray-50 border border-gray-200 rounded-lg">
                                                                <span className="text-lg font-medium">{currentQuantity}</span>
                                                            </div>
                                                            <Button
                                                                type="text"
                                                                icon={<PlusOutlined />}
                                                                size="large"
                                                                onClick={() => {
                                                                    if (currentQuantity < option.availability.remaining) {
                                                                        handleQuantityChange(option.id, currentQuantity + 1);
                                                                    }
                                                                }}
                                                                disabled={isUnavailable || currentQuantity >= option.availability.remaining}
                                                                className="w-10 h-10 flex items-center justify-center border border-gray-300 rounded-lg hover:border-blue-400"
                                                            />
                                                        </div>
                                                        <Text className="text-xs text-gray-400 mt-1">
                                                            Tối đa {option.availability.remaining} phòng
                                                        </Text>
                                                    </div>                                                    {/* Total for this option */}
                                                    {currentQuantity > 0 && (
                                                        <div className="text-center border-t border-gray-100 pt-3">
                                                            <Text className="text-sm text-gray-600 block">Tổng cộng</Text>
                                                            <div className="text-xl font-bold text-red-500">
                                                                {formatVND(calculateTotalPrice(option, currentQuantity))}
                                                            </div>
                                                            <Text className="text-xs text-gray-500">
                                                                {currentQuantity} phòng × {numberOfNights} đêm
                                                            </Text>
                                                        </div>
                                                    )}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </Card>
                            );
                        })}
                    </Space>
                </div>                {/* Summary Section */}
                {getSelectedRoomsCount() > 0 && (
                    <>
                        <Divider className="my-0" />
                        <div className="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border-t border-blue-100">
                            <Title level={4} className="mb-4 text-blue-800 flex items-center gap-2">
                                <ShoppingCartOutlined />
                                Tóm tắt đặt phòng ({getSelectedRoomsCount()} phòng)
                            </Title>

                            <div className="space-y-3">
                                {Object.entries(roomQuantities).map(([optionId, quantity]) => {
                                    if (quantity <= 0) return null;
                                    const option = options.find(opt => opt.id === optionId);
                                    if (!option) return null;

                                    return (
                                        <div key={optionId} className="flex justify-between items-center p-3 bg-white rounded-lg border border-blue-100">                                            <div>
                                            <Text strong className="text-gray-800">{option.name}</Text>
                                            <div className="text-sm text-gray-500">
                                                {quantity} phòng × {formatVND(option.dynamicPricing?.finalPrice || option.pricePerNight.vnd)}/phòng/đêm
                                            </div>
                                        </div>
                                            <div className="text-right">
                                                <div className="text-lg font-bold text-red-600">
                                                    {formatVND(calculateTotalPrice(option, quantity))}
                                                </div>
                                                <div className="text-xs text-gray-500">mỗi đêm</div>
                                            </div>
                                        </div>
                                    );
                                })}

                                {/* Grand Total */}
                                <div className="border-t border-blue-200 pt-3">
                                    <div className="flex justify-between items-center">
                                        <div>
                                            <Text strong className="text-lg text-gray-800">Tổng cộng:</Text>                                            <div className="text-sm text-gray-500">
                                                {getSelectedRoomsCount()} phòng cho {numberOfNights} đêm
                                            </div>
                                        </div>
                                        <div className="text-right">
                                            <div className="text-2xl font-bold text-red-600">
                                                {formatVND(calculateGrandTotal())}
                                            </div>
                                            <div className="text-sm text-gray-500">tổng {numberOfNights} đêm</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </>
                )}

                {/* Booking Button */}
                <div className="p-6 border-t border-gray-100">
                    <div className="text-center">
                        <Button
                            type="primary"
                            size="large"
                            disabled={getSelectedRoomsCount() === 0}
                            className="px-8 py-2 h-auto font-semibold"
                            style={{
                                background: getSelectedRoomsCount() === 0
                                    ? '#d9d9d9'
                                    : 'linear-gradient(135deg, #1890ff 0%, #096dd9 100%)',
                                border: 'none',
                                borderRadius: '8px',
                                fontSize: '16px',
                                height: '48px',
                                minWidth: '200px'
                            }}                        >
                            {getSelectedRoomsCount() === 0
                                ? 'Chọn phòng để đặt'
                                : `Đặt ${getSelectedRoomsCount()} phòng - ${formatVND(calculateGrandTotal())} (${numberOfNights} đêm)`
                            }
                        </Button>

                        {getSelectedRoomsCount() > 0 && (
                            <div className="mt-3 text-xs text-gray-500">
                                <SafetyCertificateOutlined className="mr-1" />
                                Đặt phòng an toàn - Thanh toán được bảo mật
                            </div>
                        )}
                    </div>
                </div>
            </Card>
        </div>
    );
};

export default RoomServiceOptions;
