import React, { useState, useEffect } from "react";
import {
    Card,
    Typography,
    Rate,
    List,
    Avatar,
    Button,
    Pagination,
    Tag,
    Divider,
    Progress,
    Image,
    Tooltip,
    Badge,
    Spin,
    Alert,
} from "antd";
import {
    UserOutlined,
    LikeOutlined,
    VerifiedOutlined,
    StarFilled,
    CameraOutlined,
    FlagOutlined
} from "@ant-design/icons";
import { formatDate } from "../../utils/helpers";

const { Title, Text, Paragraph } = Typography;

interface ReviewImage {
    image_id: number;
    review_id: number;
    image_url: string;
    title_vi?: string;
    alt_text_vi?: string;
    sort_order: number;
    is_active: boolean;
}

interface Review {
    review_id: number;
    customer_id: number;
    booking_id: number;
    room_id?: number;
    overall_rating: number;
    staff_rating?: number;
    facilities_rating?: number;
    cleanliness_rating?: number;
    comfort_rating?: number;
    value_rating?: number;
    location_rating?: number;
    review_title_vi?: string;
    review_content_vi: string;
    pros_vi?: string;
    cons_vi?: string;
    is_verified: boolean;
    is_approved: boolean;
    is_featured: boolean;
    admin_response_vi?: string;
    responded_by?: number;
    responded_at?: string;
    helpful_count: number;
    created_at: string;
    updated_at: string;
    customer_name: string;
    customer_avatar?: string;
    images?: ReviewImage[];
}

interface RatingBreakdown {
    overall: number;
    staff: number;
    facilities: number;
    cleanliness: number;
    comfort: number;
    value: number;
    location: number;
}

interface RoomReviewsProps {
    roomId: number;
    rating: number;
    reviewCount: number;
    ratingBreakdown?: RatingBreakdown;
}

const RoomReviews: React.FC<RoomReviewsProps> = ({
    roomId,
    rating,
    reviewCount,
    ratingBreakdown,
}) => {
    const [currentPage, setCurrentPage] = useState(1);
    const [helpfulReviews, setHelpfulReviews] = useState<Set<number>>(new Set());
    const [reviews, setReviews] = useState<Review[]>([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchReviews = async () => {
            try {
                setLoading(true);
                const response = await fetch(`/api/rooms/${roomId}/reviews`);
                if (response.ok) {
                    const data = await response.json();
                    setReviews(data.reviews || []);
                }
            } catch (error) {
                console.error('Failed to fetch reviews:', error);
                setReviews([]);
            } finally {
                setLoading(false);
            }
        };

        if (roomId) {
            fetchReviews();
        }
    }, [roomId]);

    const defaultRatingBreakdown: RatingBreakdown = {
        overall: 9.1,
        staff: 9.2,
        facilities: 9.4,
        cleanliness: 9.5,
        comfort: 9.3,
        value: 9.2,
        location: 9.6
    };

    const ratingData = ratingBreakdown || defaultRatingBreakdown;

    const handleHelpfulClick = (reviewId: number) => {
        setHelpfulReviews(prev => {
            const newSet = new Set(prev);
            if (newSet.has(reviewId)) {
                newSet.delete(reviewId);
            } else {
                newSet.add(reviewId);
            }
            return newSet;
        });
        console.log(`Room ${roomId} - Review ${reviewId} helpful clicked`);
    };

    const renderReviewItem = (review: Review) => (
        <List.Item key={review.review_id} className="border-0 px-0">
            <div className="w-full rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                <div className="flex items-start space-x-4">
                    <div className="flex-shrink-0">
                        <Badge
                            dot={review.is_verified}
                            color="#52c41a"
                            offset={[-5, 5]}
                        >
                            <Avatar
                                src={review.customer_avatar}
                                icon={<UserOutlined />}
                                size={56}
                                className="border-2 border-gray-100"
                            />
                        </Badge>
                    </div>

                    <div className="flex-1 min-w-0">
                        {/* Header with user info and rating */}
                        <div className="flex items-start justify-between mb-3">
                            <div className="flex-1">
                                <div className="flex items-center gap-2 mb-1">
                                    <Text strong className="text-lg">
                                        {review.customer_name}
                                    </Text>
                                    {review.is_verified && (
                                        <Tooltip title="Đánh giá đã xác thực">
                                            <VerifiedOutlined className="text-blue-500 text-sm" />
                                        </Tooltip>
                                    )}
                                    {review.is_featured && (
                                        <Tag color="gold" className="text-xs px-2">
                                            <StarFilled className="mr-1" />
                                            Nổi bật
                                        </Tag>
                                    )}
                                </div>
                                <Text type="secondary" className="text-sm">
                                    {formatDate(review.created_at)}
                                </Text>
                            </div>
                            <div className="text-right">
                                <div className="flex items-center gap-2 mb-1">
                                    <Rate
                                        disabled
                                        value={review.overall_rating}
                                        allowHalf
                                        className="text-base"
                                    />
                                    <Text strong className="text-lg text-orange-500">
                                        {review.overall_rating}
                                    </Text>
                                </div>
                            </div>
                        </div>

                        {/* Review title */}
                        {review.review_title_vi && (
                            <Title level={5} className="mb-3">
                                {review.review_title_vi}
                            </Title>
                        )}

                        {/* Detailed ratings */}
                        <div className="mb-4 p-4 rounded-lg bg-gray-50">
                            <div className="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm">
                                {review.staff_rating && (
                                    <div className="flex justify-between">
                                        <span>Nhân viên:</span>
                                        <span className="font-medium text-orange-600">{review.staff_rating}</span>
                                    </div>
                                )}
                                {review.facilities_rating && (
                                    <div className="flex justify-between">
                                        <span>Tiện nghi:</span>
                                        <span className="font-medium text-orange-600">{review.facilities_rating}</span>
                                    </div>
                                )}
                                {review.cleanliness_rating && (
                                    <div className="flex justify-between">
                                        <span>Sạch sẽ:</span>
                                        <span className="font-medium text-orange-600">{review.cleanliness_rating}</span>
                                    </div>
                                )}
                                {review.comfort_rating && (
                                    <div className="flex justify-between">
                                        <span>Thoải mái:</span>
                                        <span className="font-medium text-orange-600">{review.comfort_rating}</span>
                                    </div>
                                )}
                                {review.value_rating && (
                                    <div className="flex justify-between">
                                        <span>Giá trị:</span>
                                        <span className="font-medium text-orange-600">{review.value_rating}</span>
                                    </div>
                                )}
                                {review.location_rating && (
                                    <div className="flex justify-between">
                                        <span>Vị trí:</span>
                                        <span className="font-medium text-orange-600">{review.location_rating}</span>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Review content */}
                        <Paragraph className="mb-4 leading-relaxed">
                            {review.review_content_vi}
                        </Paragraph>

                        {/* Pros and Cons */}
                        {(review.pros_vi || review.cons_vi) && (
                            <div className="mb-4 grid md:grid-cols-2 gap-4">
                                {review.pros_vi && (
                                    <div className="p-3 rounded-lg border-l-4 border-green-400 bg-green-50">
                                        <Text strong className="block mb-1">
                                            👍 Ưu điểm:
                                        </Text>
                                        <Text className="text-sm">
                                            {review.pros_vi}
                                        </Text>
                                    </div>
                                )}
                                {review.cons_vi && (
                                    <div className="p-3 rounded-lg border-l-4 border-red-400 bg-red-50">
                                        <Text strong className="block mb-1">
                                            👎 Nhược điểm:
                                        </Text>
                                        <Text className="text-sm">
                                            {review.cons_vi}
                                        </Text>
                                    </div>
                                )}
                            </div>
                        )}

                        {/* Review images */}
                        {review.images && review.images.length > 0 && (
                            <div className="mb-4">
                                <div className="flex items-center gap-2 mb-3">
                                    <CameraOutlined />
                                    <Text className="text-sm">
                                        Hình ảnh từ khách hàng ({review.images.length})
                                    </Text>
                                </div>
                                <div className="flex gap-2 overflow-x-auto pb-2">
                                    <Image.PreviewGroup>
                                        {review.images
                                            .filter(img => img.is_active)
                                            .sort((a, b) => a.sort_order - b.sort_order)
                                            .map((image) => (
                                                <Image
                                                    key={image.image_id}
                                                    src={image.image_url}
                                                    alt={image.alt_text_vi || 'Hình ảnh đánh giá'}
                                                    className="rounded-lg object-cover flex-shrink-0"
                                                    width={200}
                                                    height={150}
                                                />
                                            ))
                                        }
                                    </Image.PreviewGroup>
                                </div>
                            </div>
                        )}

                        {/* Admin response */}
                        {review.admin_response_vi && (
                            <div className="mb-4 p-4 rounded-lg border border-blue-200 bg-blue-50">
                                <div className="flex items-center gap-2 mb-2">
                                    <Avatar size="small" src="/images/favicon.ico" shape="square" />
                                    <Text strong>
                                        Phản hồi từ khách sạn
                                    </Text>
                                    {review.responded_at && (
                                        <Text type="secondary" className="text-xs">
                                            {formatDate(review.responded_at)}
                                        </Text>
                                    )}
                                </div>
                                <Text className="text-sm">
                                    {review.admin_response_vi}
                                </Text>
                            </div>
                        )}

                        {/* Action buttons */}
                        <div className="flex items-center justify-between pt-3 border-t border-gray-100">
                            <Button
                                type="text"
                                icon={<LikeOutlined />}
                                onClick={() => handleHelpfulClick(review.review_id)}
                                className={`text-sm ${helpfulReviews.has(review.review_id) ? 'text-blue-500' : 'text-gray-500'}`}
                            >
                                Hữu ích ({review.helpful_count + (helpfulReviews.has(review.review_id) ? 1 : 0)})
                            </Button>
                            <Button
                                type="text"
                                icon={<FlagOutlined />}
                                className="text-sm text-gray-500"
                            >
                                Báo cáo
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </List.Item>
    );

    const getRatingColor = (rating: number) => {
        if (rating >= 9) return '#52c41a'; // green
        if (rating >= 8) return '#faad14'; // yellow
        if (rating >= 7) return '#fa8c16'; // orange
        return '#ff4d4f'; // red
    };

    const getRatingText = (rating: number) => {
        if (rating >= 9.5) return 'Xuất sắc';
        if (rating >= 9) return 'Rất tốt';
        if (rating >= 8) return 'Tốt';
        if (rating >= 7) return 'Khá tốt';
        return 'Cần cải thiện';
    };

    const pageSize = 5;
    const startIndex = (currentPage - 1) * pageSize;
    const endIndex = startIndex + pageSize;
    const currentReviews = reviews.slice(startIndex, endIndex);

    if (loading) {
        return (
            <Card className="shadow-md">
                <div className="text-center py-8">
                    <Spin size="large" />
                    <div className="mt-4">Đang tải đánh giá...</div>
                </div>
            </Card>
        );
    }

    return (
        <Card className="shadow-md">
            <div className="mb-6">
                <Title level={3} className="mb-6">
                    Đánh giá của khách ({reviewCount})
                </Title>

                {/* Overall Rating Summary */}
                <div className="grid md:grid-cols-2 gap-8 mb-8">
                    {/* Left: Overall Score */}
                    <div className="text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                        <div className="text-4xl font-bold text-blue-600 mb-2">
                            {rating.toFixed(1)}
                        </div>
                        <Rate disabled value={5} className="text-xl mb-2" />
                        <Text className="text-lg font-medium text-blue-800">
                            {getRatingText(rating)}
                        </Text>
                        <div className="text-sm text-blue-600 mt-1">
                            Dựa trên {reviewCount} đánh giá
                        </div>
                    </div>

                    {/* Right: Rating Breakdown */}
                    <div>
                        <Text strong className="text-base mb-4 block">
                            Chi tiết đánh giá
                        </Text>
                        <div className="space-y-3">
                            {Object.entries({
                                'Nhân viên': ratingData.staff,
                                'Tiện nghi': ratingData.facilities,
                                'Sạch sẽ': ratingData.cleanliness,
                                'Thoải mái': ratingData.comfort,
                                'Giá trị': ratingData.value,
                                'Vị trí': ratingData.location
                            }).map(([label, value]) => (
                                <div key={label} className="flex items-center gap-3">
                                    <span className="w-16 text-sm">{label}:</span>
                                    <Progress
                                        percent={(value / 10) * 100}
                                        strokeColor={getRatingColor(value)}
                                        trailColor="#f0f0f0"
                                        showInfo={false}
                                        className="flex-1"
                                    />
                                    <span className="w-8 text-sm font-medium text-right" style={{ color: getRatingColor(value) }}>
                                        {value.toFixed(1)}
                                    </span>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>

                <Divider />

                {/* Reviews List */}
                {reviews.length === 0 ? (
                    <Alert
                        message="Chưa có đánh giá"
                        description="Phòng này chưa có đánh giá nào từ khách hàng."
                        type="info"
                        showIcon
                        className="text-center"
                    />
                ) : (
                    <>
                        <List
                            dataSource={currentReviews}
                            renderItem={renderReviewItem}
                            className="review-list"
                        />

                        {/* Pagination */}
                        {reviews.length > pageSize && (
                            <div className="text-center mt-6">
                                <Pagination
                                    current={currentPage}
                                    total={reviews.length}
                                    pageSize={pageSize}
                                    onChange={setCurrentPage}
                                    showSizeChanger={false}
                                    showQuickJumper
                                    showTotal={(total, range) =>
                                        `${range[0]}-${range[1]} của ${total} đánh giá`
                                    }
                                />
                            </div>
                        )}
                    </>
                )}
            </div>
        </Card>
    );
};

export default RoomReviews;
