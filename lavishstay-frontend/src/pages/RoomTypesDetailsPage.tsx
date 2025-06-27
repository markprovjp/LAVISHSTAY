




import React from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { Row, Col, Typography, Spin, Alert, Button, Card, Tag, Divider, Space } from 'antd';
import { ArrowLeftOutlined } from '@ant-design/icons';
import { useGetRoomTypeById } from '../hooks/useApi';

// Import components từ roomTypes folder
import RoomImageGallery from '../components/roomTypes/RoomImageGallery';
import RoomAmenities from '../components/roomTypes/RoomAmenities';
import SimilarRooms from '../components/roomTypes/SimilarRooms';

const { Title, Paragraph, Text } = Typography;

const RoomTypesDetailsPage: React.FC = () => {
    const { id } = useParams<{ id: string }>();
    const navigate = useNavigate();

    const { data, isLoading, error } = useGetRoomTypeById(id!);
    const roomType = data?.data;

    // Lấy danh sách ảnh từ roomType.images (JSON array từ database)
    const images = roomType?.images ? (Array.isArray(roomType.images) ? roomType.images : JSON.parse(roomType.images)) : [];


    if (isLoading) {
        return (
            <div className="flex justify-center items-center min-h-96">
                <Spin size="large" />
                <span className="ml-3">Đang tải thông tin loại phòng...</span>
            </div>
        );
    }

    if (error || !roomType) {
        return (
            <div className="container mx-auto px-4 py-8">
                <Alert
                    message="Lỗi"
                    description="Không thể tải thông tin loại phòng. Vui lòng thử lại sau."
                    type="error"
                    showIcon
                    action={
                        <Button size="small" danger onClick={() => navigate(-1)}>
                            Quay lại
                        </Button>
                    }
                />
            </div>
        );
    }

    return (
        <div className="bg-gray-50 min-h-screen">
            <div className="container mx-auto px-4 py-8">
                {/* Header với nút back */}
                <div className="mb-6">
                    <Title level={1} className="mb-2">{roomType.name}</Title>
                </div>

                {/* Gallery ảnh full width */}
                <div className="mb-8">
                                       <RoomImageGallery
                      images={images.map((img: any) => img.image_url)}
                      roomName={roomType.name}
                    />
                </div>

                <Row gutter={[24, 24]}>
                    {/* Cột trái - Mô tả và tiện nghi */}
                    <Col xs={24} lg={16}>
                        {/* Mô tả chi tiết */}
                        <Card title="Mô tả loại phòng" className="mb-6">
                            <Paragraph className="text-gray-700 leading-relaxed">
                                {roomType.description || 'Chưa có mô tả chi tiết cho loại phòng này.'}
                            </Paragraph>
                        </Card>

                        {/* Tiện nghi sử dụng component */}
                        {roomType.amenities && roomType.amenities.length > 0 && (
                            <RoomAmenities amenities={roomType.amenities} />
                        )}
                    </Col>

                    {/* Cột phải - Thông tin cơ bản */}
                    <Col xs={24} lg={8}>
                        {/* Thông tin cơ bản */}
                        <Card title="Thông tin phòng" className="mb-6">
                            <Space direction="vertical" className="w-full" size="middle">
                                {/* Giá phòng */}
                                <div className="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                                    <Text className="block text-sm text-gray-600 mb-1">Giá phòng</Text>
                                    <Text className="text-2xl font-bold text-green-600">
                                        {new Intl.NumberFormat('vi-VN', {
                                            style: 'currency',
                                            currency: 'VND'
                                        }).format(roomType.base_price || roomType.min_price)}
                                    </Text>
                                    <Text className="block text-xs text-gray-500">mỗi đêm</Text>
                                </div>

                                <Divider className="my-3" />

                                {/* Thông tin chi tiết */}
                                <div className="flex justify-between items-center">
                                    <Text strong>Tổng số phòng:</Text>
                                    <Tag color="blue">{roomType.rooms_count || 'N/A'}</Tag>
                                </div>

                                <div className="flex justify-between items-center">
                                    <Text strong>Số khách tối đa:</Text>
                                    <Tag color="purple">{roomType.max_guests} khách</Tag>
                                </div>

                                {/* Thông tin từ bảng room */}
                                {roomType.avg_size && (
                                    <div className="flex justify-between items-center">
                                        <Text strong>Diện tích:</Text>
                                        <Tag color="cyan">{roomType.avg_size}m²</Tag>
                                    </div>
                                )}

                                {roomType.avg_rating && (
                                    <div className="flex justify-between items-center">
                                        <Text strong>Đánh giá:</Text>
                                        <Tag color="gold">{roomType.avg_rating}/10</Tag>
                                    </div>
                                )}

                                {/* Tầm nhìn có sẵn */}
                                {roomType.common_views && roomType.common_views.length > 0 && (
                                    <div>
                                        <Text strong>Tầm nhìn:</Text>
                                        <div className="mt-2 flex flex-wrap gap-1">
                                            {roomType.common_views.map((view: string, index: number) => (
                                                <Tag key={index} color="geekblue">{view}</Tag>
                                            ))}
                                        </div>
                                    </div>
                                )}

                                {/* Tầng có sẵn */}
                                {roomType.available_floors && roomType.available_floors.length > 0 && (
                                    <div>
                                        <Text strong>Tầng có sẵn:</Text>
                                        <div className="mt-2 flex flex-wrap gap-1">
                                            {roomType.available_floors.map((floor: number, index: number) => (
                                                <Tag key={index} color="magenta">Tầng {floor}</Tag>
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </Space>
                        </Card>


                    </Col>
                </Row>

                {/* Similar Room Types */}
                <div className="mt-8">
                    <SimilarRooms currentRoomId={roomType.id.toString()} />
                </div>
            </div>
        </div>
    );
};

export default RoomTypesDetailsPage;


