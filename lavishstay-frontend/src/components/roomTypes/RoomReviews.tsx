import React, { useState } from "react";
import {
    Card,
    Typography,
    List,
    Avatar,
    Button,
    Pagination,
    Tag,
    Progress,
    Spin,
    Alert,
    Select,
    Row,
    Col,
} from "antd";
import {
    UserOutlined,
    LikeOutlined,
    DislikeOutlined,
    MessageOutlined,
} from "@ant-design/icons";
import { useGetRoomReviews } from "../../hooks/useApi";
import { Review, hotelRatingDetails } from "../../mirage/models";

const { Title, Text, Paragraph } = Typography;
const { Option } = Select;

interface RoomReviewsProps {
    roomId: string;
}

// Function to get rating text based on score (1-10)
const getRatingText = (rating: number): string => {
    if (rating >= 9.5) return "Trên cả tuyệt vời";
    if (rating >= 9.0) return "Tuyệt vời";
    if (rating >= 8.5) return "Rất tốt";
    if (rating >= 8.0) return "Tốt";
    if (rating >= 7.0) return "Hài lòng";
    if (rating >= 6.0) return "Khá";
    if (rating >= 5.0) return "Trung bình";
    if (rating >= 4.0) return "Dưới trung bình";
    if (rating >= 3.0) return "Kém";
    return "Rất kém";
};

// Function to get travel type text
const getTravelTypeText = (type: string): string => {
    switch (type) {
        case "business": return "Công tác";
        case "couple": return "Cặp đôi";
        case "solo": return "Du lịch một mình";
        case "family_young": return "Gia đình có trẻ nhỏ";
        case "family_teen": return "Gia đình có thanh thiếu niên";
        case "group": return "Nhóm bạn";
        default: return type;
    }
};

// Function to get travel type color
const getTravelTypeColor = (type: string): string => {
    switch (type) {
        case "business": return "blue";
        case "couple": return "red";
        case "solo": return "green";
        case "family_young": return "orange";
        case "family_teen": return "purple";
        case "group": return "cyan";
        default: return "default";
    }
};

const RoomReviews: React.FC<RoomReviewsProps> = ({ roomId }) => {
    const [currentPage, setCurrentPage] = useState(1);
    const [helpfulReviews, setHelpfulReviews] = useState<Set<number>>(new Set());
    const [notHelpfulReviews, setNotHelpfulReviews] = useState<Set<number>>(new Set());
    const [filterType, setFilterType] = useState<string>("all");
    const [sortBy, setSortBy] = useState<string>("newest");
    const reviewsPerPage = 5;

    // Use hook to fetch room reviews
    const { data: reviewsData, isLoading: loading, error } = useGetRoomReviews(roomId);
    const reviews = reviewsData?.reviews || [];
    const totalReviews = reviewsData?.count || 0;

    // Filter and sort reviews
    const filteredReviews = reviews.filter((review: Review) => {
        if (filterType === "all") return true;
        return review.travelType === filterType;
    });

    const sortedReviews = [...filteredReviews].sort((a: Review, b: Review) => {
        switch (sortBy) {
            case "newest":
                return new Date(b.reviewDate).getTime() - new Date(a.reviewDate).getTime();
            case "oldest":
                return new Date(a.reviewDate).getTime() - new Date(b.reviewDate).getTime();
            case "highest":
                return b.rating - a.rating;
            case "lowest":
                return a.rating - b.rating;
            default:
                return 0;
        }
    });

    // Calculate pagination
    const startIndex = (currentPage - 1) * reviewsPerPage;
    const endIndex = startIndex + reviewsPerPage;
    const currentReviews = sortedReviews.slice(startIndex, endIndex);

    const handleHelpfulClick = (reviewId: number) => {
        setHelpfulReviews(prev => {
            const newSet = new Set(prev);
            if (newSet.has(reviewId)) {
                newSet.delete(reviewId);
            } else {
                newSet.add(reviewId);
                // Remove from not helpful if was there
                setNotHelpfulReviews(prevNot => {
                    const newNotSet = new Set(prevNot);
                    newNotSet.delete(reviewId);
                    return newNotSet;
                });
            }
            return newSet;
        });
    };

    const handleNotHelpfulClick = (reviewId: number) => {
        setNotHelpfulReviews(prev => {
            const newSet = new Set(prev);
            if (newSet.has(reviewId)) {
                newSet.delete(reviewId);
            } else {
                newSet.add(reviewId);
                // Remove from helpful if was there
                setHelpfulReviews(prevHelp => {
                    const newHelpSet = new Set(prevHelp);
                    newHelpSet.delete(reviewId);
                    return newHelpSet;
                });
            }
            return newSet;
        });
    };

    const formatDate = (dateString: string) => {
        return new Date(dateString).toLocaleDateString('vi-VN', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    };

    if (loading) {
        return (
            <Card title="Đánh giá của khách hàng" className="shadow-md">
                <div className="text-center py-8">
                    <Spin size="large" />
                    <div className="mt-4">Đang tải đánh giá...</div>
                </div>
            </Card>
        );
    }

    if (error) {
        return (
            <Card title="Đánh giá của khách hàng" className="shadow-md">
                <Alert
                    message="Không thể tải đánh giá"
                    description="Vui lòng thử lại sau"
                    type="warning"
                    showIcon
                />
            </Card>
        );
    }

    if (reviews.length === 0) {
        return (
            <Card title="Đánh giá của khách hàng" className="shadow-md">
                <div className="text-center py-8">
                    <Alert
                        message="Chưa có đánh giá"
                        description="Phòng này chưa có đánh giá từ khách hàng."
                        type="info"
                        showIcon
                    />
                </div>
            </Card>
        );
    }

    return (
        <div style={{ marginTop: 24 }}>
            <div style={{ marginBottom: 24 }}>
                <Title level={3} style={{ marginBottom: 8 }}>
                    Đánh Giá Của Khách Hàng
                </Title>
                <Text type="secondary">
                    {totalReviews} đánh giá từ khách hàng đã lưu trú
                </Text>
            </div>            {/* Hotel Overall Rating - New Section */}
            <Card className="mb-6 shadow-sm" title="Điểm số qua LavishStay">
                <Row gutter={[32, 24]} align="middle">
                    <Col xs={24} md={8}>
                        <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: '16px' }}>
                            <Progress
                                type="circle"
                                percent={(hotelRatingDetails.overallRating / 10) * 100}
                                size={320}
                                strokeColor={{
                                    '0%': '#108ee9',
                                    '100%': '#87d068'
                                }}
                                strokeWidth={8}
                                format={() => (
                                    <div style={{ textAlign: 'center' }}>
                                        <div style={{ fontSize: '82px', fontWeight: 'bold', color: '#1890ff' }}>
                                            {hotelRatingDetails.overallRating}
                                        </div>
                                    </div>
                                )}
                            />
                            <div style={{ textAlign: 'center' }}>
                                <div style={{ fontSize: '20px', fontWeight: '600', color: '#333', marginBottom: '4px' }}>
                                    {getRatingText(hotelRatingDetails.overallRating)}
                                </div>
                                <div style={{ fontSize: '20px', color: '#666' }}>
                                    {hotelRatingDetails.totalReviews.toLocaleString()} đánh giá
                                </div>
                            </div>
                        </div>
                    </Col>
                    <Col xs={24} md={16}>
                        <div style={{ display: 'flex', flexDirection: 'column', gap: '20px' }}>
                            {Object.entries(hotelRatingDetails.ratings).map(([key, value]) => {
                                const labels = {
                                    cleanliness: "Độ sạch sẽ",
                                    location: "Vị trí",
                                    facilities: "Cơ sở vật chất",
                                    service: "Dịch vụ",
                                    valueForMoney: "Đáng giá tiền"
                                };
                                const label = labels[key as keyof typeof labels]; return (
                                    <div
                                        key={key}
                                        style={{
                                            padding: '12px 16px',
                                            backgroundColor: '#fafafa',
                                            borderRadius: '8px',
                                            border: '1px solid #f0f0f0',
                                            transition: 'all 0.3s ease',
                                            cursor: 'pointer'
                                        }}
                                        onMouseEnter={(e) => {
                                            e.currentTarget.style.backgroundColor = '#f0f8ff';
                                            e.currentTarget.style.borderColor = '#d6e9ff';
                                            e.currentTarget.style.transform = 'translateY(-1px)';
                                            e.currentTarget.style.boxShadow = '0 2px 8px rgba(24, 144, 255, 0.12)';
                                        }}
                                        onMouseLeave={(e) => {
                                            e.currentTarget.style.backgroundColor = '#fafafa';
                                            e.currentTarget.style.borderColor = '#f0f0f0';
                                            e.currentTarget.style.transform = 'translateY(0)';
                                            e.currentTarget.style.boxShadow = 'none';
                                        }}
                                    >
                                        <div style={{
                                            display: 'flex',
                                            justifyContent: 'space-between',
                                            alignItems: 'center',
                                            marginBottom: '8px'
                                        }}>
                                            <span style={{
                                                fontSize: '15px',
                                                fontWeight: '500',
                                                color: '#333'
                                            }}>
                                                {label}
                                            </span>
                                            <span style={{
                                                fontSize: '16px',
                                                fontWeight: '600',
                                                color: '#1890ff',
                                                minWidth: '45px',
                                                textAlign: 'right'
                                            }}>
                                                {value}/10
                                            </span>
                                        </div>
                                        <Progress
                                            status="active"
                                            percent={(value / 10) * 100}
                                            showInfo={false}
                                            strokeColor={{
                                                from: '#108ee9',
                                                to: '#87d068'
                                            }}
                                            trailColor="#e8e8e8"
                                            strokeWidth={6}
                                            style={{
                                                marginBottom: 0
                                            }}
                                        />
                                    </div>
                                );
                            })}
                            <div style={{
                                textAlign: 'center',
                                fontSize: '13px',
                                color: '#888',
                                marginTop: '8px',
                                fontStyle: 'italic'
                            }}>
                                Điểm số dựa trên đánh giá của khách tại {hotelRatingDetails.location}
                            </div>
                        </div>
                    </Col>
                </Row>
            </Card>

            {/* Filters and Sort */}
            <Card className="mb-6 shadow-sm">
                <Row gutter={16} align="middle">
                    <Col>
                        <Text strong>Lọc theo:</Text>
                    </Col>
                    <Col>
                        <Select
                            value={filterType}
                            onChange={setFilterType}
                            style={{ width: 200 }}
                            placeholder="Chọn loại khách"
                        >
                            <Option value="all">Tất cả</Option>
                            <Option value="business">Công tác</Option>
                            <Option value="couple">Cặp đôi</Option>
                            <Option value="solo">Du lịch một mình</Option>
                            <Option value="family_young">Gia đình có trẻ nhỏ</Option>
                            <Option value="family_teen">Gia đình có thanh thiếu niên</Option>
                            <Option value="group">Nhóm bạn</Option>
                        </Select>
                    </Col>
                    <Col>
                        <Text strong>Sắp xếp:</Text>
                    </Col>
                    <Col>
                        <Select
                            value={sortBy}
                            onChange={setSortBy}
                            style={{ width: 150 }}
                        >
                            <Option value="newest">Mới nhất</Option>
                            <Option value="oldest">Cũ nhất</Option>
                            <Option value="highest">Điểm cao nhất</Option>
                            <Option value="lowest">Điểm thấp nhất</Option>
                        </Select>
                    </Col>
                    <Col flex="auto" className="text-right">
                        <Text type="secondary">
                            Hiển thị {filteredReviews.length} đánh giá
                        </Text>
                    </Col>
                </Row>            </Card>

            {/* Reviews List */}
            <Card className="shadow-sm" title="Đánh giá chi tiết">
                <List
                    dataSource={currentReviews}
                    renderItem={(review: Review) => (
                        <List.Item key={review.id} style={{ border: 'none', padding: '0' }}>
                            <div style={{
                                width: '100%',
                                borderRadius: '8px',
                                padding: '16px',
                                transition: 'background-color 0.2s'
                            }}>
                                <div style={{ display: 'flex', alignItems: 'flex-start', gap: '16px' }}>
                                    <Avatar
                                        src={review.userAvatar}
                                        icon={<UserOutlined />}
                                        size={48}
                                        style={{ flexShrink: 0 }}
                                    />

                                    <div style={{ flex: 1, minWidth: 0 }}>
                                        {/* Header */}
                                        <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '8px' }}>
                                            <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                                                <Text strong style={{ fontSize: '16px' }}>
                                                    {review.userName}
                                                </Text>
                                                <Tag
                                                    color={getTravelTypeColor(review.travelType)}
                                                    style={{ fontSize: '12px' }}
                                                >
                                                    {getTravelTypeText(review.travelType)}
                                                </Tag>
                                                <Tag style={{ fontSize: '12px', backgroundColor: '#e6f7ff', color: '#1890ff', border: '1px solid #91d5ff' }}>
                                                    {review.roomType}
                                                </Tag>
                                            </div>                                            <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                                                <Text type="secondary" style={{ fontSize: '14px' }}>
                                                    Lưu trú: {formatDate(review.stayDate)}
                                                </Text>
                                                <Text type="secondary" style={{ fontSize: '14px' }}>
                                                    •
                                                </Text>
                                                <Text type="secondary" style={{ fontSize: '14px' }}>
                                                    Đánh giá: {formatDate(review.reviewDate)}
                                                </Text>
                                            </div>
                                        </div>

                                        {/* Rating */}
                                        <div style={{ display: 'flex', alignItems: 'center', gap: '8px', marginBottom: '12px' }}>
                                            <div style={{
                                                display: 'inline-flex',
                                                alignItems: 'center',
                                                padding: '4px 12px',
                                                backgroundColor: '#1890ff',
                                                color: 'white',
                                                borderRadius: '4px',
                                                fontSize: '14px',
                                                fontWeight: 'bold'
                                            }}>
                                                {review.rating}/10
                                            </div>
                                            <Text style={{ fontSize: '14px', fontWeight: 500, color: '#1890ff' }}>
                                                {getRatingText(review.rating)}
                                            </Text>
                                        </div>

                                        {/* Review Title */}
                                        {review.title && (
                                            <Title level={5} style={{ marginBottom: '8px' }}>
                                                {review.title}
                                            </Title>
                                        )}

                                        {/* Review Content */}
                                        <Paragraph style={{ color: '#595959', marginBottom: '12px' }}>
                                            {review.comment}
                                        </Paragraph>

                                        {/* Admin Reply */}
                                        {review.adminReply && (
                                            <div style={{
                                                backgroundColor: '#f6f8fa',
                                                border: '1px solid #e1e4e8',
                                                borderRadius: '6px',
                                                padding: '12px',
                                                marginBottom: '12px'
                                            }}>
                                                <div style={{ display: 'flex', alignItems: 'center', gap: '8px', marginBottom: '8px' }}>
                                                    <MessageOutlined style={{ color: '#1890ff' }} />
                                                    <Text strong style={{ color: '#1890ff' }}>
                                                        Phản hồi từ {review.adminReply.adminName}
                                                    </Text>
                                                    <Text type="secondary" style={{ fontSize: '12px' }}>
                                                        {formatDate(review.adminReply.date)}
                                                    </Text>
                                                </div>
                                                <Text style={{ fontSize: '14px' }}>
                                                    {review.adminReply.content}
                                                </Text>
                                            </div>
                                        )}                                        {/* Helpful Voting Section */}
                                        <div style={{
                                            display: 'flex',
                                            alignItems: 'center',
                                            justifyContent: 'space-between',
                                            paddingTop: '12px',
                                            borderTop: '1px solid #e8e8e8'
                                        }}>
                                            <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                                                <Button
                                                    type={helpfulReviews.has(review.id) ? "primary" : "text"}
                                                    size="small"
                                                    icon={<LikeOutlined />}
                                                    onClick={() => handleHelpfulClick(review.id)}
                                                    style={{ fontSize: '12px' }}
                                                >
                                                    Hữu ích ({review.helpful + (helpfulReviews.has(review.id) ? 1 : 0)})
                                                </Button>
                                                <Button
                                                    type={notHelpfulReviews.has(review.id) ? "primary" : "text"}
                                                    size="small"
                                                    icon={<DislikeOutlined />}
                                                    onClick={() => handleNotHelpfulClick(review.id)}
                                                    style={{ fontSize: '12px' }}
                                                    danger={notHelpfulReviews.has(review.id)}
                                                >
                                                    Không hữu ích ({review.notHelpful + (notHelpfulReviews.has(review.id) ? 1 : 0)})
                                                </Button>
                                            </div>
                                            <Text type="secondary" style={{ fontSize: '12px' }}>
                                                Đánh giá được xác minh
                                            </Text>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </List.Item>
                    )}
                />

                {/* Pagination */}
                {totalReviews > reviewsPerPage && (
                    <div style={{ textAlign: 'center', marginTop: '24px' }}>
                        <Pagination
                            current={currentPage}
                            total={totalReviews}
                            pageSize={reviewsPerPage}
                            onChange={setCurrentPage}
                            showSizeChanger={false}
                            showQuickJumper
                            showTotal={(total, range) =>
                                `${range[0]}-${range[1]} của ${total} đánh giá`
                            }
                        />
                    </div>
                )}
            </Card>
        </div>
    );
};

export default RoomReviews;
