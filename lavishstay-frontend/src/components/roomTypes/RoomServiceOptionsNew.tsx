import React, { useState, useEffect } from "react";
import { Card, Button, Tag, Typography, Space, Radio, Alert, Tooltip, Progress, Flex, Spin } from "antd";
import {
    ClockCircleOutlined,
    CoffeeOutlined,
    CarOutlined,
    RestOutlined,
    StarFilled,
    EyeOutlined,
    UserOutlined,
    ExclamationCircleOutlined,
    InfoCircleOutlined,
    CreditCardOutlined,
    HomeOutlined,
    SafetyOutlined,
} from "@ant-design/icons";

const { Title, Text } = Typography;

interface RoomOption {
    id: string;
    name: string;
    price: {
        vnd: number;
    };
    originalPrice?: {
        vnd: number;
    };
    memberPrice?: {
        vnd: number;
    };
    discount?: number;
    recommended?: boolean;
    available: number;
    totalRooms: number;
    maxGuests: number;
    services: {
        icon: React.ReactNode;
        name: string;
        price?: string;
        tooltip?: string;
    }[];
    policies: string[];
    promotion?: {
        type: "hot" | "limited" | "member" | "lowest" | "deal";
        message: string;
    };
    paymentType: "prepay" | "pay_at_hotel";
    cancellation: "free" | "non_refundable";
    bookingSpeed?: string;
}

interface RoomServiceOptionsProps {
    roomId: string;
}

const RoomServiceOptions: React.FC<RoomServiceOptionsProps> = ({ roomId }) => {
    const [selectedRoom, setSelectedRoom] = useState<string>("");
    const [roomOptions, setRoomOptions] = useState<RoomOption[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchRoomOptions = async () => {
            try {
                setLoading(true);
                const response = await fetch(`/api/rooms/${roomId}/options`);
                if (response.ok) {
                    const data = await response.json();
                    setRoomOptions(data.options || []);
                    if (data.options && data.options.length > 0) {
                        setSelectedRoom(data.options[0].id);
                    }
                }
            } catch (error) {
                console.error('Failed to fetch room options:', error);
                // Fallback to empty array
                setRoomOptions([]);
            } finally {
                setLoading(false);
            }
        };

        if (roomId) {
            fetchRoomOptions();
        }
    }, [roomId]);

    if (loading) {
        return (
            <Card title="Tùy chọn phòng" className="shadow-md">
                <div className="text-center py-8">
                    <Spin size="large" />
                    <div className="mt-4">Đang tải tùy chọn phòng...</div>
                </div>
            </Card>
        );
    }

    const formatVND = (price: number) => {
        return new Intl.NumberFormat("vi-VN", {
            style: "currency",
            currency: "VND",
        }).format(price);
    };

    const getPromotionStyle = (type: string) => {
        switch (type) {
            case "hot":
                return { color: "#ff4d4f", background: "#fff2f0", border: "#ffccc7" };
            case "limited":
                return { color: "#fa8c16", background: "#fff7e6", border: "#ffd591" };
            case "member":
                return { color: "#722ed1", background: "#f9f0ff", border: "#d3adf7" };
            case "lowest":
                return { color: "#52c41a", background: "#f6ffed", border: "#b7eb8f" };
            case "deal":
                return { color: "#1890ff", background: "#e6f7ff", border: "#91d5ff" };
            default:
                return { color: "#52c41a", background: "#f6ffed", border: "#b7eb8f" };
        }
    };

    const getGuestTooltip = (maxGuests: number, selectedGuests: number = 2) => {
        if (selectedGuests > maxGuests) {
            return {
                title: `Vượt quá sức chứa khách Ưu đãi này bao gồm 1 Phòng, ${maxGuests} Người Lớn.`,
                color: "red"
            };
        }
        return {
            title: `Ưu đãi này chứa được nhóm du lịch của bạn! Ưu đãi này bao gồm 1 Phòng, ${maxGuests} Người Lớn.`,
            color: "green"
        };
    };

    const getAvailabilityStatus = (available: number, total: number) => {
        const percentage = (available / total) * 100;
        if (available === 0) return { color: "red", text: "Hết phòng" };
        if (percentage <= 25)
            return { color: "orange", text: `Chỉ còn ${available} phòng` };
        if (percentage <= 50)
            return { color: "gold", text: `Còn ${available} phòng` };
        return { color: "green", text: `${available} phòng trống` };
    };

    if (roomOptions.length === 0) {
        return (
            <Card title="Tùy chọn phòng" className="shadow-md">
                <div className="text-center py-8">
                    <Alert
                        message="Không có tùy chọn phòng"
                        description="Hiện tại không có tùy chọn phòng nào khả dụng. "
                        type="info"
                        showIcon
                    />
                </div>
            </Card>
        );
    }

    return (
        <div style={{ marginTop: 22 }}>
            <div style={{ marginBottom: 24 }}>
                <Title level={3} style={{ marginBottom: 8 }}>
                    Lựa Chọn Phòng & Dịch Vụ 
                </Title>
                <Text type="secondary">
                    Chọn gói dịch vụ phù hợp với nhu cầu của bạn
                </Text>
            </div>

            {/* Special Promotion Alert */}
            <Alert
                message="🔥 Ưu đãi đặc biệt!"
                description="Giá thấp nhất chúng tôi có được! Đặt ngay để không bỏ lỡ cơ hội."
                type="success"
                showIcon
                style={{
                    marginBottom: 24,
                    border: "1px solid #52c41a",
                    borderRadius: "8px",
                }}
            />

            <Radio.Group
                value={selectedRoom}
                onChange={(e) => setSelectedRoom(e.target.value)}
                style={{ width: "100%" }}
            >
                <div
                    style={{
                        display: "flex",
                        overflowX: "auto",
                        gap: "16px",
                        padding: "8px 0",
                    }}
                >
                    {roomOptions.map((room) => {
                        const availability = getAvailabilityStatus(
                            room.available,
                            room.totalRooms
                        );
                        const promotionStyle = room.promotion
                            ? getPromotionStyle(room.promotion.type)
                            : null;
                        const isRecommended = room.recommended;
                        const isSelected = selectedRoom === room.id;
                        const isUnavailable = room.available === 0;

                        return (
                            <div
                                key={room.id}
                                style={{
                                    minWidth: "280px",
                                    maxWidth: "380px",
                                    position: "relative",
                                }}
                            >
                                {/* Recommended Badge */}
                                {isRecommended && (
                                    <div
                                        style={{
                                            position: "absolute",
                                            top: "-8px",
                                            left: "16px",
                                            zIndex: 10,
                                            background: "linear-gradient(135deg, #ff6b6b, #ee5a24)",
                                            color: "white",
                                            padding: "4px 12px",
                                            borderRadius: "12px",
                                            fontSize: "12px",
                                            fontWeight: "bold",
                                            display: "flex",
                                            alignItems: "center",
                                            gap: "4px",
                                            boxShadow: "0 2px 8px rgba(255, 107, 107, 0.3)",
                                        }}
                                    >
                                        <StarFilled style={{ fontSize: "10px" }} />
                                        Đề xuất
                                    </div>
                                )}

                                <Card
                                    hoverable={!isUnavailable}
                                    className={`room-option-card ${isSelected ? "selected" : ""
                                        } ${isRecommended ? "recommended" : ""}`}
                                    style={{
                                        height: "100%",
                                        border: isSelected
                                            ? "2px solid #1890ff"
                                            : isRecommended
                                                ? "2px solid #ff6b6b"
                                                : "1px solid #d9d9d9",
                                        borderRadius: "12px",
                                        overflow: "hidden",
                                        opacity: isUnavailable ? 0.6 : 1,
                                        boxShadow: isRecommended
                                            ? "0 4px 20px rgba(255, 107, 107, 0.15)"
                                            : isSelected
                                                ? "0 4px 20px rgba(24, 144, 255, 0.15)"
                                                : "0 2px 8px rgba(0, 0, 0, 0.06)",
                                        transform: isRecommended ? "translateY(-4px)" : "none",
                                        transition: "all 0.3s ease",
                                    }}
                                >
                                    <div style={{ padding: "16px" }}>
                                        {/* Header */}
                                        <div style={{ marginBottom: 16 }}>
                                            <Radio
                                                value={room.id}
                                                disabled={isUnavailable}
                                                style={{ marginBottom: 8 }}
                                            >
                                                <Title
                                                    level={5}
                                                    style={{ margin: 0, display: "inline" }}
                                                >
                                                    {room.name}
                                                </Title>
                                            </Radio>
                                            {/* Promotion Tag */}
                                            {room.promotion && (
                                                <Tag
                                                    style={{
                                                        marginLeft: 8,
                                                        border: `1px solid ${promotionStyle?.border}`,
                                                        background: promotionStyle?.background,
                                                        color: promotionStyle?.color,
                                                        fontSize: "11px",
                                                    }}
                                                >
                                                    {room.promotion.message}
                                                </Tag>
                                            )}
                                        </div>

                                        {/* Price */}
                                        <div style={{ marginBottom: 16, textAlign: "right" }}>
                                            {room.memberPrice && room.promotion?.type === "member" && (
                                                <div style={{ marginBottom: 4 }}>
                                                    <Text type="secondary" style={{ fontSize: "12px" }}>
                                                        Giá thành viên
                                                    </Text>
                                                </div>
                                            )}
                                            {room.originalPrice && room.discount && (
                                                <div style={{ marginBottom: 4 }}>
                                                    <Text
                                                        delete
                                                        type="secondary"
                                                        style={{ fontSize: "14px" }}
                                                    >
                                                        {formatVND(room.originalPrice.vnd)}
                                                    </Text>
                                                    <Tag
                                                        color="red"
                                                        style={{ marginLeft: 4, fontSize: "11px" }}
                                                    >
                                                        -{room.discount}%
                                                    </Tag>
                                                </div>
                                            )}
                                            <Text strong style={{ fontSize: "18px", color: "#1890ff" }}>
                                                {formatVND(room.memberPrice?.vnd || room.price.vnd)}
                                            </Text>
                                            <div style={{ marginTop: 4 }}>
                                                {room.policies.map((policy, idx) => (
                                                    <Text key={idx} type="secondary" style={{ fontSize: "10px", display: "block" }}>
                                                        {policy}
                                                    </Text>
                                                ))}
                                            </div>
                                        </div>

                                        {/* Guest Capacity with Tooltip */}
                                        <div style={{ marginBottom: 16 }}>
                                            <Tooltip title={getGuestTooltip(room.maxGuests, 2).title}>
                                                <div style={{
                                                    display: "flex",
                                                    alignItems: "center",
                                                    gap: 4,
                                                    color: getGuestTooltip(room.maxGuests, 2).color === "red" ? "#ff4d4f" : "#52c41a"
                                                }}>
                                                    <UserOutlined style={{ fontSize: "12px" }} />
                                                    <Text style={{ fontSize: "12px" }}>
                                                        Tối đa {room.maxGuests} khách
                                                    </Text>
                                                    <InfoCircleOutlined style={{ fontSize: "10px", opacity: 0.6 }} />
                                                </div>
                                            </Tooltip>
                                        </div>

                                        {/* Services */}
                                        <div style={{ marginBottom: 16 }}>
                                            <Text
                                                strong
                                                style={{
                                                    fontSize: "13px",
                                                    marginBottom: 8,
                                                    display: "block",
                                                }}
                                            >
                                                Dịch vụ bao gồm:
                                            </Text>
                                            <Space
                                                direction="vertical"
                                                size={4}
                                                style={{ width: "100%" }}
                                            >
                                                {room.services.slice(0, 4).map((service, index) => (
                                                    <div
                                                        key={index}
                                                        style={{
                                                            display: "flex",
                                                            alignItems: "center",
                                                            gap: 6,
                                                            justifyContent: "space-between",
                                                        }}
                                                    >
                                                        <div style={{ display: "flex", alignItems: "center", gap: 6 }}>
                                                            <span
                                                                style={{ color: "#1890ff", fontSize: "12px" }}
                                                            >
                                                                {service.icon}
                                                            </span>
                                                            <Text style={{ fontSize: "12px" }}>
                                                                {service.name}
                                                            </Text>
                                                        </div>
                                                        {service.price && (
                                                            <Text type="secondary" style={{ fontSize: "10px" }}>
                                                                {service.price}
                                                            </Text>
                                                        )}
                                                    </div>
                                                ))}
                                                {room.services.length > 4 && (
                                                    <Text type="secondary" style={{ fontSize: "11px" }}>
                                                        +{room.services.length - 4} dịch vụ khác
                                                    </Text>
                                                )}
                                            </Space>
                                        </div>

                                        {/* Booking Speed Badge */}
                                        {room.bookingSpeed && (
                                            <div style={{ marginBottom: 8 }}>
                                                <Tag
                                                    color="processing"
                                                    style={{
                                                        fontSize: "11px",
                                                        fontWeight: "bold",
                                                        padding: "2px 8px"
                                                    }}
                                                >
                                                    {room.bookingSpeed}
                                                </Tag>
                                            </div>
                                        )}

                                        {/* Availability Status with Progress Bar */}
                                        <div style={{ marginBottom: 16 }}>
                                            <div style={{ marginBottom: 8, display: "flex", justifyContent: "space-between", alignItems: "center" }}>
                                                <Text style={{ fontSize: "12px", fontWeight: 500 }}>
                                                    Tình trạng:
                                                </Text>
                                                <Tag
                                                    color={availability.color}
                                                    style={{ fontSize: "11px" }}
                                                >
                                                    {availability.text}
                                                </Tag>
                                            </div>
                                            <Progress
                                                percent={room.available === 0 ? 0 : Math.max((room.available / room.totalRooms) * 100, 10)}
                                                size="small"
                                                status="active"
                                                strokeColor={{
                                                    '0%': room.available === 0 ? '#ff4d4f' :
                                                        room.available <= 1 ? '#ff7875' :
                                                            room.available <= 2 ? '#ffa940' : '#73d13d',
                                                    '100%': room.available === 0 ? '#cf1322' :
                                                        room.available <= 1 ? '#ff4d4f' :
                                                            room.available <= 2 ? '#fa8c16' : '#52c41a'
                                                }}
                                                trailColor="#f5f5f5"
                                                showInfo={false}
                                            />
                                        </div>
                                    </div>
                                </Card>
                            </div>
                        );
                    })}
                </div>
            </Radio.Group>

            {/* View More Button */}
            <div style={{ textAlign: "center", marginTop: 24 }}>
                <Button
                    type="link"
                    icon={<EyeOutlined />}
                    style={{ color: "#1890ff", fontWeight: 500 }}
                >
                    Xem thêm lựa chọn phòng khác
                </Button>
            </div>

            {/* Price Note */}
            <Alert
                message="Giá mỗi đêm chưa gồm thuế và phí dịch vụ"
                type="info"
                showIcon={false}
                style={{
                    marginTop: 16,
                    border: "1px solid #e1e8ed",
                    borderRadius: "8px",
                }}
            />

            {/* Book Now Button */}
            <div style={{ marginTop: 24, textAlign: "center" }}>
                <Button
                    type="primary"
                    size="large"
                    disabled={
                        roomOptions.find((r) => r.id === selectedRoom)?.available === 0
                    }
                    style={{
                        background: "linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%)",
                        borderColor: "transparent",
                        height: 48,
                        paddingLeft: 40,
                        paddingRight: 40,
                        borderRadius: "24px",
                        fontWeight: "bold",
                        fontSize: "16px",
                        boxShadow: "0 4px 15px rgba(255, 107, 107, 0.4)",
                        transition: "all 0.3s ease",
                    }}
                    onClick={() => {
                        window.location.href = `/payment`;
                    }}
                >
                    {(() => {
                        const selectedRoomData = roomOptions.find((r) => r.id === selectedRoom);
                        if (selectedRoomData?.available === 0) {
                            return "Hết phòng";
                        }
                        const price = selectedRoomData?.memberPrice || selectedRoomData?.price;
                        return `Đặt phòng ngay - ${formatVND(price?.vnd || 0)}`;
                    })()}
                </Button>
            </div>
        </div>
    );
};

export default RoomServiceOptions;
