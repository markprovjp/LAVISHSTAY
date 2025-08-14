// src/components/news/NewsDetail.tsx
import React, { useState } from 'react';
import {
    Card,
    Typography,
    Spin,
    Tag,
    Avatar,
    Space,
    Button,
    Divider,
    Alert,
    Row,
    Col,
    Breadcrumb,
    BackTop
} from 'antd';
import {
    ArrowLeftOutlined,
    EyeOutlined,
    LikeOutlined,
    BookOutlined,
    ShareAltOutlined,
    CalendarOutlined,
    UserOutlined,
    StarOutlined
} from '@ant-design/icons';
import { motion } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { useParams, useNavigate } from 'react-router-dom';
import { useNewsDetail, useToggleLike, useToggleBookmark, useRateNews } from '../../hooks/useNews';
import CommentList from './CommentList';
import CommentForm from './CommentForm';
import { formatTimeAgo } from '../../utils/timeHelpers';
import { normalizeNewsDetailResponse, NormalizedNewsItem } from '../../utils/normalizeNewsData';

const { Title, Text, Paragraph } = Typography;

const NewsDetail: React.FC = () => {
    const { t } = useTranslation();
    const { slug } = useParams<{ slug: string }>();
    const navigate = useNavigate();

    const [showComments, setShowComments] = useState(true);


    // API queries
    const {
        data: newsRaw,
        isLoading,
        error,
        refetch
    } = useNewsDetail(slug!);

    // Normalize the response data to prevent iteration/spreading errors
    const normalizedResponse = normalizeNewsDetailResponse(newsRaw);
    const news = normalizedResponse.data;

    const toggleLikeMutation = useToggleLike();
    const toggleBookmarkMutation = useToggleBookmark();
    const rateNewsMutation = useRateNews();

    // Handle actions
    const handleLike = () => {
        if (news) {
            toggleLikeMutation.mutate(news.id);
        }
    };

    const handleBookmark = () => {
        if (news) {
            toggleBookmarkMutation.mutate(news.id);
        }
    };

    const handleRate = (rating: number) => {
        if (news) {
            rateNewsMutation.mutate({ newsId: news.id, rating });
        }
    };

    const handleShare = async () => {
        if (news && navigator.share) {
            try {
                await navigator.share({
                    title: news.title,
                    text: news.summary,
                    url: window.location.href
                });
            } catch (error) {
                // Fallback to copy to clipboard
                navigator.clipboard.writeText(window.location.href);
            }
        } else {
            // Fallback to copy to clipboard
            navigator.clipboard.writeText(window.location.href);
        }
    };

    const handleBack = () => {
        navigate('/news');
    };

    // Loading state
    if (isLoading) {
        return (
            <div className="flex justify-center items-center py-20">
                <Spin size="large" tip={t('news.loading', 'Đang tải tin tức...')} />
            </div>
        );
    }

    // Error state
    if (error || !news) {
        return (
            <div className="py-8">
                <Alert
                    message={t('news.error.notFound', 'Không tìm thấy bài viết')}
                    description={t('news.error.notFoundDesc', 'Bài viết bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.')}
                    type="error"
                    showIcon
                    action={
                        <Space>
                            <Button size="small" onClick={() => refetch()}>
                                {t('common.retry', 'Thử lại')}
                            </Button>
                            <Button size="small" type="primary" onClick={handleBack}>
                                {t('news.backToList', 'Về danh sách tin tức')}
                            </Button>
                        </Space>
                    }
                />
            </div>
        );
    }

    return (
        <div className="news-detail max-w-4xl mx-auto p-4">
            <BackTop />

            {/* Breadcrumb */}
            <Breadcrumb className="mb-4">
                <Breadcrumb.Item>
                    <Button type="link" onClick={handleBack} icon={<ArrowLeftOutlined />}>
                        {t('news.title', 'Tin tức')}
                    </Button>
                </Breadcrumb.Item>
                <Breadcrumb.Item>{news.categoryName}</Breadcrumb.Item>
                <Breadcrumb.Item className="font-medium">{news.title}</Breadcrumb.Item>
            </Breadcrumb>

            <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.5 }}
            >
                <Card className="shadow-lg">
                    {/* Header */}
                    <div className="mb-6">
                        <Title level={1} className="mb-4 text-2xl md:text-3xl">
                            {news.title}
                        </Title>

                        <Text className="text-lg text-gray-600 block mb-4">
                            {news.summary}
                        </Text>

                        {/* Meta information */}
                        <Row gutter={[16, 8]} className="mb-4">
                            <Col xs={24} sm={12}>
                                <Space>
                                    <Avatar
                                        src={news.authorAvatar}
                                        icon={<UserOutlined />}
                                        size="small"
                                    />
                                    <Text>{news.authorName || t('news.author.anonymous', 'Ẩn danh')}</Text>
                                </Space>
                            </Col>
                            <Col xs={24} sm={12}>
                                <Space>
                                    <CalendarOutlined />
                                    <Text>{formatTimeAgo(news.published_at)}</Text>
                                </Space>
                            </Col>
                            <Col xs={24} sm={12}>
                                <Space>
                                    <EyeOutlined />
                                    <Text>{news.views?.toLocaleString() || 0} lượt xem</Text>
                                </Space>
                            </Col>
                            {news.rating && (
                                <Col xs={24} sm={12}>
                                    <Space>
                                        <StarOutlined />
                                        <Text>{news.rating.toFixed(1)}/5.0</Text>
                                    </Space>
                                </Col>
                            )}
                        </Row>

                        {/* Tags */}
                        {news.formattedTags && news.formattedTags.length > 0 && (
                            <div className="mb-4">
                                <Space wrap>
                                    {news.formattedTags.map((tag: string, index: number) => (
                                        <Tag key={index} color="blue">
                                            {tag}
                                        </Tag>
                                    ))}
                                </Space>
                            </div>
                        )}
                    </div>

                    {/* Featured Image */}
                    {news.featured_image && (
                        <div className="mb-6">
                            <img
                                src={news.featured_image}
                                alt={news.title}
                                className="w-full h-64 md:h-80 object-cover rounded-lg"
                            />
                        </div>
                    )}

                    {/* Content */}
                    <div className="news-content mb-8">
                        <div
                            className="prose prose-lg max-w-none"
                            dangerouslySetInnerHTML={{ __html: news.content }}
                        />
                    </div>

                    <Divider />

                    {/* Actions */}
                    <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <Space size="middle">
                            <Button
                                type={news.is_liked ? 'primary' : 'default'}
                                icon={<LikeOutlined />}
                                onClick={handleLike}
                                loading={toggleLikeMutation.isPending}
                            >
                                {news.likes_count || 0}
                            </Button>

                            <Button
                                type={news.is_bookmarked ? 'primary' : 'default'}
                                icon={<BookOutlined />}
                                onClick={handleBookmark}
                                loading={toggleBookmarkMutation.isPending}
                            >
                                {t('news.bookmark', 'Lưu')}
                            </Button>

                            <Button
                                icon={<ShareAltOutlined />}
                                onClick={handleShare}
                            >
                                {t('news.share', 'Chia sẻ')}
                            </Button>
                        </Space>

                        {/* Rating */}
                        <Space>
                            <Text>{t('news.rate', 'Đánh giá:')} </Text>
                            {[1, 2, 3, 4, 5].map((star) => (
                                <Button
                                    key={star}
                                    type="text"
                                    icon={<StarOutlined />}
                                    size="small"
                                    className={`${news.user_rating && star <= news.user_rating
                                        ? 'text-yellow-500'
                                        : 'text-gray-300'
                                        } hover:text-yellow-500`}
                                    onClick={() => handleRate(star)}
                                    loading={rateNewsMutation.isPending}
                                />
                            ))}
                        </Space>
                    </div>
                </Card>

                {/* Comments Section */}
                <Card className="mt-6 shadow-lg">
                    <div className="flex justify-between items-center mb-6">
                        <Title level={3}>
                            {t('news.comments.title', 'Bình luận')} ({news.comments_count || 0})
                        </Title>
                        <Button
                            type="link"
                            onClick={() => setShowComments(!showComments)}
                        >
                            {showComments
                                ? t('news.comments.hide', 'Ẩn bình luận')
                                : t('news.comments.show', 'Hiện bình luận')
                            }
                        </Button>
                    </div>

                    {showComments && (
                        <motion.div
                            initial={{ opacity: 0 }}
                            animate={{ opacity: 1 }}
                            transition={{ duration: 0.3 }}
                        >
                            {/* Comment Form */}
                            <CommentForm
                                newsId={news.id}
                                onCommentAdded={() => refetch()}
                            />

                            <Divider />

                            {/* Comment List */}
                            <CommentList
                                newsId={news.id}
                                onCommentUpdated={() => refetch()}
                            />
                        </motion.div>
                    )}
                </Card>
            </motion.div>
        </div>
    );
};

export default NewsDetail;
