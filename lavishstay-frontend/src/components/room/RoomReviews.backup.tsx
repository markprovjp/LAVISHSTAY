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
import "./RoomReviews.css";

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

    // Mock data based on database structure
    const reviews: Review[] = [
        {
            review_id: 1,
            customer_id: 1,
            booking_id: 1,
            room_id: 1,
            overall_rating: 9.2,
            staff_rating: 9.3,
            facilities_rating: 9.5,
            cleanliness_rating: 9.5,
            comfort_rating: 9.5,
            value_rating: 9.4,
            location_rating: 9.5,
            review_title_vi: "Kỳ nghỉ tuyệt vời tại LavishStay Thanh Hóa",
            review_content_vi: "Khách sạn tuyệt vời với dịch vụ và tiện nghi tốt. Phòng sạch sẽ và thoải mái. Rất khuyến nghị!",
            pros_vi: "Phòng sạch sẽ, nhân viên thân thiện, vị trí thuận lợi",
            cons_vi: "Wifi hơi chậm vào buổi tối",
            is_verified: true,
            is_approved: true,
            is_featured: true,
            admin_response_vi: "Cảm ơn quý khách đã lựa chọn LavishStay. Chúng tôi sẽ cải thiện hệ thống wifi để phục vụ tốt hơn.",
            helpful_count: 25,
            created_at: "2025-05-23T09:50:42Z",
            updated_at: "2025-05-23T09:50:42Z",
            customer_name: "Nguyễn Văn Quyền",
            customer_avatar: "../../../public/images/users/1.jpg",
            images: [
                {
                    image_id: 1,
                    review_id: 1,
                    image_url: "https://images.unsplash.com/photo-1566073771259-6a8506099945?w=300",
                    title_vi: "Phòng ngủ thoải mái",
                    alt_text_vi: "Hình ảnh phòng ngủ",
                    sort_order: 1,
                    is_active: true
                },
                {
                    image_id: 2,
                    review_id: 1,
                    image_url: "https://images.unsplash.com/photo-1566073771259-6a8506099945?w=300",
                    title_vi: "View từ phòng",
                    alt_text_vi: "Hình ảnh view từ phòng",
                    sort_order: 2,
                    is_active: true
                }
            ]
        },
        {
            review_id: 2,
            customer_id: 2,
            booking_id: 2,
            room_id: 11,
            overall_rating: 9.0,
            staff_rating: 9.1,
            facilities_rating: 9.3,
            cleanliness_rating: 9.4,
            comfort_rating: 9.2,
            value_rating: 9.0,
            location_rating: 9.6,
            review_title_vi: "Vị trí tuyệt vời và dịch vụ tốt",
            review_content_vi: "Vị trí hoàn hảo ở trung tâm thành phố. Nhân viên rất hữu ích và thân thiện. Giá trị tốt cho tiền.",
            pros_vi: "Vị trí trung tâm, dễ di chuyển, nhân viên nhiệt tình",
            cons_vi: "Bãi đậu xe hơi nhỏ",
            is_verified: true,
            is_approved: true,
            is_featured: false,
            helpful_count: 18,
            created_at: "2025-05-20T14:30:22Z",
            updated_at: "2025-05-20T14:30:22Z",
            customer_name: "Huỳnh Thị Bích Tuyền",
            customer_avatar: "../../../public/images/users/2.jpg",
        }
    ];

    const defaultRatingBreakdown: RatingBreakdown = {
        overall: 9.1,
        staff: 9.2,
        facilities: 9.4,
        cleanliness: 9.5,
        comfort: 9.3,
        value: 9.2,
        location: 9.6
    };

    const ratingData = ratingBreakdown || defaultRatingBreakdown; const handleHelpfulClick = (reviewId: number) => {
        setHelpfulReviews(prev => {
            const newSet = new Set(prev);
            if (newSet.has(reviewId)) {
                newSet.delete(reviewId);
            } else {
                newSet.add(reviewId);
            }
            return newSet;
        });
        // TODO: API call to update helpful count
        console.log(`Room ${roomId} - Review ${reviewId} helpful clicked`);
    }; const renderReviewItem = (review: Review) => (
        <List.Item key={review.review_id} className="border-0 px-0">
            <div className="w-full  rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
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
                                    <Text strong className="text-lg ">
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
                            <Title level={5} className="mb-3 ">
                                {review.review_title_vi}
                            </Title>
                        )}

                        {/* Detailed ratings */}
                        <div className="mb-4 p-4  rounded-lg">
                            <div className="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm">
                                {review.staff_rating && (
                                    <div className="flex justify-between">
                                        <span className="">Nhân viên:</span>
                                        <span className="font-medium text-orange-600">{review.staff_rating}</span>
                                    </div>
                                )}
                                {review.facilities_rating && (
                                    <div className="flex justify-between">
                                        <span className="">Tiện nghi:</span>
                                        <span className="font-medium text-orange-600">{review.facilities_rating}</span>
                                    </div>
                                )}
                                {review.cleanliness_rating && (
                                    <div className="flex justify-between">
                                        <span className="">Sạch sẽ:</span>
                                        <span className="font-medium text-orange-600">{review.cleanliness_rating}</span>
                                    </div>
                                )}
                                {review.comfort_rating && (
                                    <div className="flex justify-between">
                                        <span className="">Thoải mái:</span>
                                        <span className="font-medium text-orange-600">{review.comfort_rating}</span>
                                    </div>
                                )}
                                {review.value_rating && (
                                    <div className="flex justify-between">
                                        <span className="">Giá trị:</span>
                                        <span className="font-medium text-orange-600">{review.value_rating}</span>
                                    </div>
                                )}
                                {review.location_rating && (
                                    <div className="flex justify-between">
                                        <span className="">Vị trí:</span>
                                        <span className="font-medium text-orange-600">{review.location_rating}</span>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Review content */}
                        <Paragraph className="mb-4  leading-relaxed">
                            {review.review_content_vi}
                        </Paragraph>

                        {/* Pros and Cons */}
                        {(review.pros_vi || review.cons_vi) && (
                            <div className="mb-4 grid md:grid-cols-2 gap-4">
                                {review.pros_vi && (
                                    <div className="p-3  rounded-lg border-l-4 border-green-400">
                                        <Text strong className=" block mb-1">
                                            👍 Ưu điểm:
                                        </Text>
                                        <Text className=" text-sm">
                                            {review.pros_vi}
                                        </Text>
                                    </div>
                                )}
                                {review.cons_vi && (
                                    <div className="p-3  rounded-lg border-l-4 border-red-400">
                                        <Text strong className=" block mb-1">
                                            👎 Nhược điểm:
                                        </Text>
                                        <Text className=" text-sm">
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
                                    <CameraOutlined className="" />
                                    <Text className=" text-sm">
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
                            <div className="mb-4 p-4  rounded-lg border border-blue-200">
                                <div className="flex items-center gap-2 mb-2">
                                    <Avatar size="small" src="../../../public/images/favicon.ico" className="" shape="square" >
                                    </Avatar>
                                    <Text strong className="">
                                        Phản hồi từ khách sạn
                                    </Text>
                                    {review.responded_at && (
                                        <Text type="secondary" className="text-xs">
                                            {formatDate(review.responded_at)}
                                        </Text>
                                    )}
                                </div>
                                <Text className="">
                                    {review.admin_response_vi}
                                </Text>
                            </div>
                        )}

                        {/* Action buttons */}
                        <div className="flex items-center justify-between pt-3 border-t border-gray-100">
                            <div className="flex items-center gap-3">
                                <Button
                                    type="text"
                                    size="small"
                                    icon={<LikeOutlined />}
                                    className={`flex items-center gap-1 hover:text-blue-500 ${helpfulReviews.has(review.review_id) ? 'text-blue-500' : ''
                                        }`}
                                    onClick={() => handleHelpfulClick(review.review_id)}
                                >
                                    Hữu ích ({review.helpful_count + (helpfulReviews.has(review.review_id) ? 1 : 0)})
                                </Button>
                                <Button
                                    type="text"
                                    size="small"
                                    icon={<FlagOutlined />}
                                    className=" hover:text-red-500"
                                >
                                    Báo cáo
                                </Button>
                            </div>
                            <Text type="secondary" className="text-xs">
                                Booking #{review.booking_id}
                            </Text>
                        </div>
                    </div>
                </div>
            </div>
        </List.Item>
    ); return (
        <Card className="mt-6 shadow-lg border-0 overflow-hidden">
            {/* Header */}
            <div className="  p-6 -m-6 mb-6">
                <div className="flex items-center justify-between">
                    <div>
                        <Title level={3} className="mb-2 ">
                            Đánh giá từ khách hàng
                        </Title>
                        <Text className="">
                            Những chia sẻ chân thật từ khách đã trải nghiệm
                        </Text>
                    </div>
                    <div className="text-right">
                        <div className="flex items-center justify-end gap-3 mb-2">
                            <Rate
                                disabled
                                value={rating}
                                allowHalf
                                className="text-xl"
                                style={{ color: '#ffd700' }}
                            />
                            <Text strong className="text-2xl ">
                                {rating}
                            </Text>
                        </div>
                        <Text className="">
                            Từ {reviewCount.toLocaleString('vi-VN')} đánh giá
                        </Text>
                    </div>
                </div>
            </div>{/* Rating breakdown */}
            <div className="mb-8 p-6  rounded-xl border border-gray-100">
                <Title level={5} className="mb-4 ">
                    Đánh giá chi tiết
                </Title>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {[
                        { key: 'staff', label: 'Nhân viên', value: ratingData.staff, color: '#1890ff' },
                        { key: 'facilities', label: 'Tiện nghi', value: ratingData.facilities, color: '#52c41a' },
                        { key: 'cleanliness', label: 'Sạch sẽ', value: ratingData.cleanliness, color: '#722ed1' },
                        { key: 'comfort', label: 'Thoải mái', value: ratingData.comfort, color: '#fa8c16' },
                        { key: 'value', label: 'Giá trị', value: ratingData.value, color: '#eb2f96' },
                        { key: 'location', label: 'Vị trí', value: ratingData.location, color: '#13c2c2' },
                    ].map((item) => (
                        <div key={item.key} className="flex items-center justify-between">
                            <Text className=" min-w-0 flex-1">
                                {item.label}
                            </Text>
                            <div className="flex items-center gap-3 flex-shrink-0">
                                <Progress
                                    status="active"
                                    percent={(item.value / 10) * 100}
                                    size="small"
                                    strokeColor={item.color}
                                    showInfo={false}
                                    className="w-24"
                                />
                                <Text strong className=" w-8 text-right">
                                    {item.value}
                                </Text>
                            </div>
                        </div>
                    ))}
                </div>
            </div>            {/* Reviews list */}
            <div className="space-y-6">
                <List
                    dataSource={reviews}
                    renderItem={renderReviewItem}
                    className="review-list"
                    itemLayout="vertical"
                    split={false}
                />
            </div>

            {/* Pagination */}
            <div className="mt-8 text-center">
                <Pagination
                    current={currentPage}
                    total={reviewCount}
                    pageSize={5}
                    showSizeChanger={false}
                    showQuickJumper
                    showTotal={(total, range) =>
                        `${range[0]}-${range[1]} của ${total} đánh giá`
                    }
                    onChange={setCurrentPage}
                    className="custom-pagination"
                />
            </div>

            <Divider className="my-8" />

            {/* Write review button */}
            <div className="text-center  rounded-xl p-8">
                <Title level={4} className="mb-4 ">
                    Chia sẻ trải nghiệm của bạn
                </Title>
                <Text className=" block mb-6">
                    Đánh giá của bạn sẽ giúp những khách hàng khác có thêm thông tin để lựa chọn
                </Text>
                <Button
                    type="primary"
                    size="large"
                    className="h-12 px-8 "
                >
                    ✍️ Viết đánh giá
                </Button>
            </div>
        </Card>
    );
};

export default RoomReviews;
