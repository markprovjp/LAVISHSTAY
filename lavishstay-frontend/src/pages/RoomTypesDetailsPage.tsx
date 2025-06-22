import React from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { Row, Col, Typography, Spin, Alert, Button, Card, Tag, Divider, Space } from 'antd';
import { ArrowLeftOutlined } from '@ant-design/icons';
import { useGetRoomTypeById } from '../hooks/useApi';

// Import components từ roomTypes folder
import RoomImageGallery from '../components/roomTypes/RoomImageGallery';
import RoomAmenities from '../components/roomTypes/RoomAmenities';
import RoomAvailabilityFilter from '../components/roomTypes/RoomAvailabilityFilter';
import RoomBookingForm from '../components/roomTypes/RoomBookingForm';
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
                    <Button
                        icon={<ArrowLeftOutlined />}
                        onClick={() => navigate(-1)}
                        className="mb-4"
                    >
                        Quay lại
                    </Button>
                    <Title level={1} className="mb-2">{roomType.name}</Title>
                    <Text type="secondary" className="text-lg">
                        Mã loại phòng: {roomType.room_code}
                    </Text>
                </div>

                <Row gutter={[24, 24]}>
                    {/* Cột trái - Ảnh */}
                    <Col xs={24} lg={16}>
                        {/* Gallery ảnh sử dụng component */}
                        <RoomImageGallery
                            images={images}
                            roomName={roomType.name}
                        />

                        {/* Mô tả chi tiết */}
                        <Card title="Mô tả loại phòng" className="mt-6">
                            <Paragraph className="text-gray-700 leading-relaxed">
                                {roomType.description || 'Chưa có mô tả chi tiết cho loại phòng này.'}
                            </Paragraph>
                        </Card>

                        {/* Tiện nghi sử dụng component */}
                        {roomType.amenities && roomType.amenities.length > 0 && (
                            <RoomAmenities amenities={roomType.amenities} />
                        )}
                    </Col>

                    {/* Cột phải - Thông tin */}
                    <Col xs={24} lg={8}>
                        {/* Form đặt phòng */}
                        <RoomBookingForm room={roomType} />

                        {/* Thông tin cơ bản */}
                        <Card title="Thông tin cơ bản" className="mb-6">
                            <Space direction="vertical" className="w-full" size="middle">
                                <div className="flex justify-between items-center">
                                    <Text strong>Tổng số phòng:</Text>
                                    <Tag color="blue">{roomType.rooms_count || 'N/A'}</Tag>
                                </div>

                                <div className="flex justify-between items-center">
                                    <Text strong>Phòng còn trống:</Text>
                                    <Tag color="green">{roomType.available_rooms_count || 'N/A'}</Tag>
                                </div>

                                <div className="flex justify-between items-center">
                                    <Text strong>Phòng đã đặt:</Text>
                                    <Tag color="red">{(roomType.rooms_count - roomType.available_rooms_count) || 'N/A'}</Tag>
                                </div>

                                <Divider className="my-3" />

                                {/* Thông tin giá từ dữ liệu thực tế */}
                                <div className="flex justify-between items-center">
                                    <Text strong>Giá thấp nhất:</Text>
                                    <Text className="text-lg font-semibold text-green-600">
                                        {new Intl.NumberFormat('vi-VN', {
                                            style: 'currency',
                                            currency: 'VND'
                                        }).format(roomType.min_price)}
                                    </Text>
                                </div>

                                <div className="flex justify-between items-center">
                                    <Text strong>Giá cao nhất:</Text>
                                    <Text className="text-lg font-semibold text-orange-600">
                                        {new Intl.NumberFormat('vi-VN', {
                                            style: 'currency',
                                            currency: 'VND'
                                        }).format(roomType.max_price)}
                                    </Text>
                                </div>

                                <div className="flex justify-between items-center">
                                    <Text strong>Giá trung bình:</Text>
                                    <Text className="text-lg font-semibold text-blue-600">
                                        {new Intl.NumberFormat('vi-VN', {
                                            style: 'currency',
                                            currency: 'VND'
                                        }).format(roomType.avg_price)}
                                    </Text>
                                </div>

                                <Divider className="my-3" />

                                {/* Thông tin từ bảng room */}
                                {roomType.avg_size && (
                                    <div className="flex justify-between items-center">
                                        <Text strong>Diện tích trung bình:</Text>
                                        <Tag color="cyan">{roomType.avg_size}m²</Tag>
                                    </div>
                                )}

                                {roomType.avg_rating && (
                                    <div className="flex justify-between items-center">
                                        <Text strong>Điểm đánh giá trung bình:</Text>
                                        <Tag color="gold">{roomType.avg_rating}/10</Tag>
                                    </div>
                                )}

                                <div className="flex justify-between items-center">
                                    <Text strong>Số khách tối đa:</Text>
                                    <Tag color="purple">{roomType.max_guests} khách</Tag>
                                </div>

                                {/* Tầm nhìn có sẵn */}
                                {roomType.common_views && roomType.common_views.length > 0 && (
                                    <div>
                                        <Text strong>Tầm nhìn:</Text>
                                        <div className="mt-2 flex flex-wrap gap-1">
                                            {roomType.common_views.map((view, index) => (
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
                                            {roomType.available_floors.map((floor, index) => (
                                                <Tag key={index} color="magenta">Tầng {floor}</Tag>
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </Space>
                        </Card>

                        {/* Bộ lọc tình trạng phòng */}
                        <RoomAvailabilityFilter
                            maxGuests={roomType.max_guests || 2}
                            onSearch={(dates, guests) => {
                                console.log("Search availability:", dates, guests);
                            }}
                        />

                        {/* Tiện nghi nổi bật */}
                        {roomType.highlighted_amenities && roomType.highlighted_amenities.length > 0 && (
                            <Card title="Tiện nghi nổi bật" className="mb-6">
                                <div className="space-y-2">
                                    {roomType.highlighted_amenities.map((amenity: any) => (
                                        <div key={amenity.id} className="flex items-center space-x-2">
                                            {amenity.icon && (
                                                <span dangerouslySetInnerHTML={{ __html: amenity.icon }} />
                                            )}
                                            <Text>{amenity.name}</Text>
                                        </div>
                                    ))}
                                </div>
                            </Card>
                        )}

                        {/* Tất cả tiện nghi */}
                        {roomType.amenities && roomType.amenities.length > 0 && (
                            <Card title="Tất cả tiện nghi">
                                <div className="flex flex-wrap gap-2">
                                    {roomType.amenities.map((amenity: any) => (
                                        <Tag key={amenity.id} className="mb-2">
                                            {amenity.icon && (
                                                <span
                                                    dangerouslySetInnerHTML={{ __html: amenity.icon }}
                                                    className="mr-1"
                                                />
                                            )}
                                            {amenity.name}
                                        </Tag>
                                    ))}
                                </div>
                            </Card>
                        )}
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