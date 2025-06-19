import React from 'react';
import { Card, Row, Col, Typography, Space, Tag, Descriptions, Image, Button, Tooltip, Alert } from 'antd';
import { TeamOutlined, StarFilled, PlusOutlined, MinusOutlined, EyeOutlined, CheckCircleOutlined } from '@ant-design/icons';
import { Bed, Bath, Tv, Mountain, Waves, Building, Car, Wifi, Coffee } from 'lucide-react';
import { Room } from '../../../mirage/models';
import { RoomOption } from '../../../mirage/roomoption';
import { formatVND } from '../../../utils/helpers';

const { Title, Text } = Typography;

interface RoomCardProps {
    room: Room & { options: RoomOption[]; allocationSuggestion?: any };
    selectedRooms: { [roomId: string]: { [optionId: string]: number } };
    searchFormData: any;
    onRoomOptionSelect: (roomId: string, optionId: string, quantity: number) => void;
    getRoomTypeDisplayName: (roomType: string) => string;
}

const RoomCard: React.FC<RoomCardProps> = ({
    room,
    selectedRooms,
    searchFormData,
    onRoomOptionSelect,
    getRoomTypeDisplayName
}) => {
    // Helper functions for icons
    const getAmenityIcon = (amenity: string) => {
        const lowerAmenity = amenity.toLowerCase();
        if (lowerAmenity.includes('wifi') || lowerAmenity.includes('internet')) {
            return <Wifi size={12} className="text-blue-500" />;
        }
        if (lowerAmenity.includes('tv') || lowerAmenity.includes('tivi')) {
            return <Tv size={12} className="text-gray-600" />;
        }
        if (lowerAmenity.includes('coffee') || lowerAmenity.includes('cà phê')) {
            return <Coffee size={12} className="text-amber-600" />;
        }
        if (lowerAmenity.includes('bath') || lowerAmenity.includes('phòng tắm')) {
            return <Bath size={12} className="text-cyan-500" />;
        }
        if (lowerAmenity.includes('car') || lowerAmenity.includes('xe')) {
            return <Car size={12} className="text-gray-700" />;
        }
        return <CheckCircleOutlined className="text-green-500" style={{ fontSize: '12px' }} />;
    };

    const getViewIcon = (view: string) => {
        const lowerView = view.toLowerCase();
        if (lowerView.includes('biển') || lowerView.includes('sea')) {
            return <Waves size={14} className="text-blue-400" />;
        }
        if (lowerView.includes('núi') || lowerView.includes('mountain')) {
            return <Mountain size={14} className="text-green-600" />;
        }
        if (lowerView.includes('thành phố') || lowerView.includes('city')) {
            return <Building size={14} className="text-gray-600" />;
        }
        return <Building size={14} className="text-gray-500" />;
    };

    const getBedTypeIcon = () => {
        return <Bed size={14} className="text-indigo-600" />;
    };

    return (
        <Card
            hoverable
            style={{
                borderRadius: '12px',
                border: '1px solid #f0f0f0',
                overflow: 'hidden'
            }}
            bodyStyle={{ padding: '24px' }}
        >
            <Row gutter={24}>
                {/* Room Image */}
                <Col span={6}>
                    <div style={{ position: 'relative' }}>
                        <Image
                            src={room.image}
                            alt={room.name}
                            style={{
                                width: '100%',
                                height: '280px',
                                objectFit: 'cover',
                                borderRadius: '8px'
                            }}
                            preview={false}
                        />
                        {room.rating && (
                            <div style={{
                                position: 'absolute',
                                top: '12px',
                                left: '12px',
                                background: 'rgba(0,0,0,0.7)',
                                color: 'white',
                                padding: '4px 8px',
                                borderRadius: '6px',
                                fontSize: '12px',
                                fontWeight: 'bold'
                            }}>
                                <StarFilled style={{ marginRight: '4px', color: '#faad14' }} />
                                {room.rating}
                            </div>
                        )}
                    </div>
                </Col>

                {/* Room Info */}
                <Col span={11}>
                    <Space direction="vertical" style={{ width: '100%' }} size="middle">                        <div>
                            <Title level={4} style={{ margin: 0, color: '#262626' }}>
                                {room.name}
                            </Title>
                            <Space wrap style={{ marginTop: '8px' }}>
                                <Tag color="blue">{getRoomTypeDisplayName(room.roomType)}</Tag>
                                <Tag color="green">{room.size}m²</Tag>
                                <Tag color="orange">
                                    <TeamOutlined /> {room.maxGuests} khách
                                </Tag>
                                {/* Room Allocation Suggestion */}
                                {room.allocationSuggestion && room.allocationSuggestion.isRecommended && (
                                    <Tag color="red" style={{ fontWeight: 'bold' }}>
                                        💡 Gợi ý: {room.allocationSuggestion.suggestedQuantity} phòng
                                    </Tag>
                                )}
                                {room.allocationSuggestion && room.allocationSuggestion.reasonCode === 'PERFECT_FIT' && (
                                    <Tag color="green">✨ Vừa vặn</Tag>
                                )}
                            </Space>
                            {/* Allocation Reason */}
                            {room.allocationSuggestion && (
                                <div style={{ marginTop: '6px', fontSize: '12px', color: '#666', fontStyle: 'italic' }}>
                                    {room.allocationSuggestion.reason}
                                </div>
                            )}
                        </div>

                        <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                            {getViewIcon(room.view || '')}
                            <Text type="secondary" style={{ fontSize: '14px' }}>{room.view}</Text>
                        </div>

                        <Descriptions size="small" column={1}>
                            <Descriptions.Item
                                label={
                                    <span style={{ display: 'flex', alignItems: 'center', gap: '4px' }}>
                                        {getBedTypeIcon()}
                                        <span style={{ color: '#595959' }}>Loại giường</span>
                                    </span>
                                }
                            >
                                <Text style={{ color: '#262626' }}>
                                    {typeof room.bedType === 'string' ? room.bedType : room.bedType?.default}
                                </Text>
                            </Descriptions.Item>
                        </Descriptions>

                        {room.mainAmenities && (
                            <div>
                                <Text strong style={{ color: '#262626', marginBottom: '8px', display: 'block' }}>
                                    Tiện ích chính:
                                </Text>
                                <div style={{ display: 'flex', flexWrap: 'wrap', gap: '6px' }}>
                                    {room.mainAmenities.slice(0, 8).map((amenity, index) => (
                                        <Tag
                                            key={index}
                                            style={{
                                                display: 'inline-flex',
                                                alignItems: 'center',
                                                gap: '4px',
                                                border: '1px solid #e8e8e8',
                                                borderRadius: '6px',
                                                padding: '2px 8px'
                                            }}
                                        >
                                            {getAmenityIcon(amenity)}
                                            <span style={{ fontSize: '12px' }}>{amenity}</span>
                                        </Tag>
                                    ))}
                                    {room.mainAmenities.length > 8 && (
                                        <Tag style={{ borderRadius: '6px' }}>
                                            +{room.mainAmenities.length - 8} khác
                                        </Tag>
                                    )}
                                </div>
                            </div>
                        )}
                    </Space>
                </Col>

                {/* Room Options */}
                <Col span={7}>
                    <div style={{ height: '100%', display: 'flex', flexDirection: 'column' }}>
                        <Title level={5} style={{ marginBottom: '16px', color: '#262626' }}>
                            Tùy chọn đặt phòng ({room.options.length})
                        </Title>

                        <div style={{ flex: 1, maxHeight: '280px', overflowY: 'auto' }}>
                            <Space direction="vertical" style={{ width: '100%' }} size="small">
                                {room.options.map((option) => {
                                    const currentQuantity = selectedRooms[room.id.toString()]?.[option.id] || 0;
                                    const finalPrice = option.dynamicPricing?.finalPrice || option.pricePerNight.vnd;
                                    const nights = searchFormData.dateRange[1].diff(searchFormData.dateRange[0], 'day');
                                    const totalPrice = finalPrice * nights;

                                    return (
                                        <Card
                                            key={option.id}
                                            size="small"
                                            style={{
                                                border: currentQuantity > 0 ? '2px solid #1890ff' : '1px solid #e8e8e8',
                                                borderRadius: '8px',
                                                background: currentQuantity > 0 ? '#f0f9ff' : '#fff'
                                            }}
                                        >
                                            <Space direction="vertical" style={{ width: '100%' }} size="small">
                                                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                                                    <Text strong style={{ fontSize: '13px', color: '#262626' }}>
                                                        {option.name}
                                                    </Text>
                                                    <Space size="small">
                                                        {option.recommended && <Tag color="red">Đề xuất</Tag>}
                                                        {option.mostPopular && <Tag color="orange">Phổ biến</Tag>}
                                                    </Space>
                                                </div>

                                                <div>
                                                    <Text style={{ fontSize: '16px', fontWeight: 'bold', color: '#f5222d' }}>
                                                        {formatVND(finalPrice)}/đêm
                                                    </Text>
                                                    {nights > 1 && (
                                                        <div>
                                                            <Text type="secondary" style={{ fontSize: '12px' }}>
                                                                {nights} đêm: {formatVND(totalPrice)}
                                                            </Text>
                                                        </div>
                                                    )}
                                                </div>

                                                {option.dynamicPricing && option.dynamicPricing.savings > 0 && (
                                                    <Alert
                                                        message={`Tiết kiệm ${formatVND(option.dynamicPricing.savings)}`}
                                                        type="success"
                                                        showIcon={false}
                                                        style={{ padding: '4px 8px', fontSize: '11px' }}
                                                    />
                                                )}

                                                <div style={{ display: 'flex', alignItems: 'center', gap: '8px', fontSize: '11px' }}>
                                                    <Text style={{ color: '#8c8c8c' }}>
                                                        <TeamOutlined /> {option.minGuests}-{option.maxGuests} khách
                                                    </Text>
                                                    <Text style={{ color: '#8c8c8c' }}>
                                                        Còn {option.availability.remaining} phòng
                                                    </Text>
                                                </div>

                                                <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                                                    <Space>
                                                        <Button
                                                            size="small"
                                                            icon={<MinusOutlined />}
                                                            onClick={() => onRoomOptionSelect(room.id.toString(), option.id, Math.max(0, currentQuantity - 1))}
                                                            disabled={currentQuantity === 0}
                                                            style={{ borderRadius: '4px' }}
                                                        />
                                                        <span style={{
                                                            minWidth: '24px',
                                                            textAlign: 'center',
                                                            fontWeight: 'bold',
                                                            color: currentQuantity > 0 ? '#1890ff' : '#8c8c8c'
                                                        }}>
                                                            {currentQuantity}
                                                        </span>
                                                        <Button
                                                            size="small"
                                                            icon={<PlusOutlined />}
                                                            onClick={() => onRoomOptionSelect(room.id.toString(), option.id, currentQuantity + 1)}
                                                            disabled={currentQuantity >= option.availability.remaining}
                                                            style={{ borderRadius: '4px' }}
                                                        />
                                                    </Space>

                                                    <Tooltip title="Xem chi tiết">
                                                        <Button
                                                            size="small"
                                                            icon={<EyeOutlined />}
                                                            onClick={() => {
                                                                console.log('View room details:', room.id);
                                                            }}
                                                            style={{ borderRadius: '4px' }}
                                                        />
                                                    </Tooltip>
                                                </div>
                                            </Space>
                                        </Card>
                                    );
                                })}
                            </Space>
                        </div>
                    </div>
                </Col>
            </Row>
        </Card>
    );
};

export default RoomCard;
