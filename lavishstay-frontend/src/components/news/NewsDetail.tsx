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
    BackTop,
    Rate,
    Image,
    message
} from 'antd';
import {
    ArrowLeftOutlined,
    EyeOutlined,
    CalendarOutlined,
    UserOutlined,
    StarOutlined,
    MessageOutlined
} from '@ant-design/icons';
import { motion } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { useParams, useNavigate } from 'react-router-dom';
import { useNewsDetail, useRateNews } from '../../hooks/useNews';
import CommentList from './CommentList';
import CommentForm from './CommentForm';
import NewsLikeButton from './NewsLikeButton';
import NewsBookmarkButton from './NewsBookmarkButton';
import NewsShareButton from './NewsShareButton';
import { normalizeNewsDetailResponse } from '../../utils/normalizeNewsData';
import { Helmet } from 'react-helmet-async';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import 'dayjs/locale/vi';

dayjs.extend(relativeTime);
dayjs.locale('vi');

const { Title, Text, Paragraph } = Typography;

const NewsDetail: React.FC = () => {
    const { t } = useTranslation();
    const { slug } = useParams<{ slug: string }>();
    const navigate = useNavigate();

    const [showComments, setShowComments] = useState(true);
    const [currentRating, setCurrentRating] = useState<number>(0);

    // API queries với chuẩn hóa dữ liệu
    const {
        data: newsRaw,
        isLoading,
        error,
        refetch
    } = useNewsDetail(slug!);

    // Chuẩn hóa dữ liệu API thành format an toàn
    const normalizedResponse = normalizeNewsDetailResponse(newsRaw);
    const news = normalizedResponse.data;

    // DEBUG: Log để kiểm tra data
    console.log('NewsDetail Debug:', {
        newsRaw: newsRaw,
        normalizedResponse: normalizedResponse,
        news: news,
        formattedTags: news?.formattedTags,
        isFormattedTagsArray: Array.isArray(news?.formattedTags)
    });

    // Mutations để tương tác với news (chỉ dùng rate)
    const rateNewsMutation = useRateNews();

    // Cập nhật rating khi có dữ liệu
    React.useEffect(() => {
        if (news?.user_rating) {
            setCurrentRating(news.user_rating);
        }
    }, [news?.user_rating]);

    // Xử lý đánh giá
    const handleRate = (rating: number) => {
        if (news && news.id > 0) {
            setCurrentRating(rating);
            rateNewsMutation.mutate(
                { newsId: news.id, rating },
                {
                    onSuccess: () => {
                        message.success('Đánh giá thành công!');
                    },
                    onError: () => {
                        setCurrentRating(news.user_rating || 0);
                        message.error('Không thể đánh giá bài viết');
                    }
                }
            );
        }
    };

    const handleBack = () => {
        navigate('/news');
    };

    // Loading state
    if (isLoading) {
        return (
            <div className="flex justify-center items-center min-h-screen">
                <Spin size="large" tip={t('news.loading', 'Đang tải tin tức...')} />
            </div>
        );
    }

    // Error state
    if (error || !news || news.id === 0) {
        return (
            <div className="max-w-4xl mx-auto p-4">
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
        <>
            {/* SEO Meta Tags */}
            <Helmet>
                <title>{news.title} - LavishStay</title>
                <meta name="description" content={news.summary} />
                <meta name="keywords" content={news.formattedTags.join(', ')} />
                <meta property="og:title" content={news.title} />
                <meta property="og:description" content={news.summary} />
                <meta property="og:image" content={news.imageUrl} />
                <meta property="og:type" content="article" />
                <meta property="og:url" content={window.location.href} />
                <meta property="article:published_time" content={news.published_at} />
                <meta property="article:author" content={news.authorName} />
                <meta property="article:section" content={news.categoryName} />
                {Array.isArray(news.formattedTags) && news.formattedTags.map((tag, index) => (
                    <meta key={index} property="article:tag" content={tag} />
                ))}
            </Helmet>

            <div className="news-detail max-w-6xl mx-auto px-4 py-6">
                <BackTop />

                {/* Breadcrumb Navigation */}
                <motion.div
                    initial={{ opacity: 0, y: -10 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.3 }}
                >
                    <Breadcrumb className="mb-6">
                        <Breadcrumb.Item>
                            <Button
                                type="link"
                                onClick={handleBack}
                                icon={<ArrowLeftOutlined />}
                                className="p-0 h-auto"
                            >
                                {t('news.title', 'Tin tức')}
                            </Button>
                        </Breadcrumb.Item>
                        <Breadcrumb.Item>
                            <Text type="secondary">{news.categoryName}</Text>
                        </Breadcrumb.Item>
                        <Breadcrumb.Item className="font-medium">
                            <Text>{news.title}</Text>
                        </Breadcrumb.Item>
                    </Breadcrumb>
                </motion.div>

                {/* Main Article */}
                <motion.div
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.6 }}
                    className="grid grid-cols-1 lg:grid-cols-3 gap-8"
                >
                    {/* Article Content - 2/3 */}
                    <div className="lg:col-span-2">
                        <Card className="shadow-lg border-0">
                            {/* Article Header */}
                            <div className="mb-8">
                                <motion.div
                                    initial={{ opacity: 0, y: 10 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ delay: 0.1 }}
                                >
                                    <Title level={1} className="mb-4 !text-2xl md:!text-3xl lg:!text-4xl font-bold leading-tight">
                                        {news.title}
                                    </Title>
                                </motion.div>

                                <motion.div
                                    initial={{ opacity: 0, y: 10 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ delay: 0.2 }}
                                >
                                    <Paragraph className="text-lg text-gray-600 mb-6 leading-relaxed">
                                        {news.summary}
                                    </Paragraph>
                                </motion.div>

                                {/* Article Meta */}
                                <motion.div
                                    initial={{ opacity: 0, y: 10 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ delay: 0.3 }}
                                >
                                    <Row gutter={[16, 12]} className="mb-6">
                                        <Col xs={24} sm={12} md={8}>
                                            <Space size="small" className="text-gray-600">
                                                <Avatar
                                                    src={news.authorAvatar}
                                                    icon={<UserOutlined />}
                                                    size="small"
                                                />
                                                <Text>{news.authorName || 'Admin'}</Text>
                                            </Space>
                                        </Col>
                                        <Col xs={24} sm={12} md={8}>
                                            <Space size="small" className="text-gray-600">
                                                <CalendarOutlined />
                                                <Text>{dayjs(news.published_at).format('DD/MM/YYYY HH:mm')}</Text>
                                            </Space>
                                        </Col>
                                        <Col xs={24} sm={12} md={8}>
                                            <Space size="small" className="text-gray-600">
                                                <EyeOutlined />
                                                <Text>{news.views?.toLocaleString() || 0} lượt xem</Text>
                                            </Space>
                                        </Col>
                                        {news.rating && (
                                            <Col xs={24} sm={12} md={8}>
                                                <Space size="small" className="text-gray-600">
                                                    <StarOutlined />
                                                    <Text>{news.rating.toFixed(1)}/5.0</Text>
                                                </Space>
                                            </Col>
                                        )}
                                    </Row>

                                    {/* Tags */}
                                    {news.formattedTags && Array.isArray(news.formattedTags) && news.formattedTags.length > 0 && (
                                        <div className="mb-6">
                                            <Space wrap size="small">
                                                {news.formattedTags.map((tag: string, index: number) => (
                                                    <Tag
                                                        key={index}
                                                        color="blue"
                                                        className="rounded-full px-3 py-1"
                                                    >
                                                        {tag}
                                                    </Tag>
                                                ))}
                                            </Space>
                                        </div>
                                    )}
                                </motion.div>
                            </div>

                            {/* Featured Image */}
                            {news.imageUrl && news.imageUrl !== '/images/default-news.jpg' && (
                                <motion.div
                                    initial={{ opacity: 0, scale: 0.95 }}
                                    animate={{ opacity: 1, scale: 1 }}
                                    transition={{ delay: 0.4, duration: 0.5 }}
                                    className="mb-8"
                                >
                                    <Image
                                        src={news.imageUrl}
                                        alt={news.title}
                                        className="w-full rounded-xl object-cover"
                                        style={{ maxHeight: '500px' }}
                                        preview={{
                                            mask: (
                                                <div className="flex items-center justify-center">
                                                    <EyeOutlined /> Xem ảnh lớn
                                                </div>
                                            )
                                        }}
                                    />
                                </motion.div>
                            )}

                            {/* Article Content */}
                            <motion.div
                                initial={{ opacity: 0, y: 20 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ delay: 0.5 }}
                                className="news-content mb-8"
                            >
                                <div
                                    className="prose prose-lg max-w-none
                                    prose-headings:text-gray-900 prose-headings:font-bold
                                    prose-p:text-gray-700 prose-p:leading-relaxed
                                    prose-a:text-blue-600 prose-a:no-underline hover:prose-a:underline
                                    prose-img:rounded-lg prose-img:shadow-md
                                    prose-blockquote:border-l-blue-500 prose-blockquote:bg-blue-50 prose-blockquote:py-2 prose-blockquote:px-4
                                    "
                                    dangerouslySetInnerHTML={{ __html: news.content }}
                                />
                            </motion.div>

                            <Divider />

                            {/* Action Buttons */}
                            <motion.div
                                initial={{ opacity: 0, y: 10 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ delay: 0.6 }}
                                className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 mb-8"
                            >
                                <Space size="large" wrap>
                                    <NewsLikeButton
                                        newsId={news.id.toString()}
                                        isLiked={news.is_liked}
                                        initialLikeCount={news.likes_count}
                                        size="large"
                                        showCount
                                    />

                                    <NewsBookmarkButton
                                        newsId={news.id.toString()}
                                        isBookmarked={news.is_bookmarked}
                                        size="large"
                                    />

                                    <NewsShareButton
                                        newsId={news.id.toString()}
                                        title={news.title}
                                        url={window.location.href}
                                        size="large"
                                    />
                                </Space>

                                {/* Rating */}
                                <div className="flex items-center gap-3">
                                    <Text className="font-medium text-gray-700">Đánh giá:</Text>
                                    <Rate
                                        value={currentRating}
                                        onChange={handleRate}
                                        disabled={rateNewsMutation.isPending}
                                        className="text-lg"
                                    />
                                    {news.rating && (
                                        <Text type="secondary" className="ml-2">
                                            ({news.rating.toFixed(1)})
                                        </Text>
                                    )}
                                </div>
                            </motion.div>
                        </Card>

                        {/* Comments Section */}
                        <motion.div
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ delay: 0.7 }}
                        >
                            <Card className="mt-8 shadow-lg border-0">
                                <div className="flex justify-between items-center mb-6">
                                    <Title level={3} className="flex items-center gap-2 !mb-0">
                                        <MessageOutlined />
                                        {t('news.comments.title', 'Bình luận')} ({news.comments_count || 0})
                                    </Title>
                                    <Button
                                        type="link"
                                        onClick={() => setShowComments(!showComments)}
                                        className="text-blue-600"
                                    >
                                        {showComments
                                            ? t('news.comments.hide', 'Ẩn bình luận')
                                            : t('news.comments.show', 'Hiện bình luận')
                                        }
                                    </Button>
                                </div>

                                {showComments && (
                                    <motion.div
                                        initial={{ opacity: 0, height: 0 }}
                                        animate={{ opacity: 1, height: 'auto' }}
                                        exit={{ opacity: 0, height: 0 }}
                                        transition={{ duration: 0.3 }}
                                    >
                                        {/* Comment Form */}
                                        <div className="mb-6">
                                            <CommentForm
                                                newsId={news.id}
                                                onCommentAdded={() => refetch()}
                                            />
                                        </div>

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

                    {/* Sidebar - 1/3 */}
                    <div className="lg:col-span-1">
                        <motion.div
                            initial={{ opacity: 0, x: 20 }}
                            animate={{ opacity: 1, x: 0 }}
                            transition={{ delay: 0.8 }}
                            className="sticky top-6"
                        >
                            {/* Article Info Card */}
                            <Card className="mb-6 shadow-lg border-0">
                                <Title level={4} className="!mb-4">Thông tin bài viết</Title>
                                <Space direction="vertical" size="middle" className="w-full">
                                    <div>
                                        <Text strong>Danh mục:</Text>
                                        <br />
                                        <Tag color="blue" className="mt-1">{news.categoryName}</Tag>
                                    </div>
                                    <div>
                                        <Text strong>Ngày xuất bản:</Text>
                                        <br />
                                        <Text type="secondary">{dayjs(news.published_at).format('DD/MM/YYYY')}</Text>
                                    </div>
                                    <div>
                                        <Text strong>Lượt xem:</Text>
                                        <br />
                                        <Text type="secondary">{news.views?.toLocaleString() || 0}</Text>
                                    </div>
                                    <div>
                                        <Text strong>Lượt thích:</Text>
                                        <br />
                                        <Text type="secondary">{news.likes_count}</Text>
                                    </div>
                                </Space>
                            </Card>

                            {/* Author Card */}
                            <Card className="shadow-lg border-0">
                                <Title level={4} className="!mb-4">Tác giả</Title>
                                <div className="text-center">
                                    <Avatar
                                        size={80}
                                        src={news.authorAvatar}
                                        icon={<UserOutlined />}
                                        className="mb-3"
                                    />
                                    <br />
                                    <Text strong className="text-lg">{news.authorName}</Text>
                                    <br />
                                    <Text type="secondary">Tác giả</Text>
                                </div>
                            </Card>
                        </motion.div>
                    </div>
                </motion.div>
            </div>
        </>
    );
};

export default NewsDetail;
