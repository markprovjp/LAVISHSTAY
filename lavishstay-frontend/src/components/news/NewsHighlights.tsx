// src/components/news/NewsHighlights.tsx
import React from 'react';
import { Card, Tag, Avatar, Row, Col, Alert, Empty, Skeleton, Image, Button } from 'antd';
import { motion } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { ClockCircleOutlined, EyeOutlined, UserOutlined, ArrowRightOutlined } from '@ant-design/icons';
import { Link } from 'react-router-dom';
import { useNewsList } from '../../hooks/useNews';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import 'dayjs/locale/vi';

dayjs.extend(relativeTime);
dayjs.locale('vi');

interface NewsHighlightsProps {
    onNewsClick?: (slug: string) => void;
}

// Helper function to get image URL with fallback
const getImageUrl = (newsItem: any): string => {
    return newsItem.featured_image ||
        newsItem.image_url ||
        newsItem.image ||
        newsItem.thumbnail?.filepath ||
        '/images/placeholder-news.png';
};

// Helper function to get summary/excerpt
const getSummary = (newsItem: any): string => {
    return newsItem.summary ||
        newsItem.excerpt ||
        newsItem.meta_description ||
        'Không có mô tả';
};

const NewsHighlights: React.FC<NewsHighlightsProps> = ({ onNewsClick }) => {
    const { t } = useTranslation();

    // Lấy tin nổi bật khác từ API (không phải banner chính)
    const { data: newsResponse, isLoading, error } = useNewsList({
        per_page: 4,
        is_featured: 0,
        sort_by: 'published_at',
        sort_order: 'desc'
    });

    // Dev-only: Log response để debug
    React.useEffect(() => {
        if (process.env.NODE_ENV !== 'production') {
            console.debug('NewsHighlights - News Response:', newsResponse);
        }
    }, [newsResponse]);

    if (isLoading) {
        return (
            <div className="space-y-6">
                <div className="flex items-center space-x-3 mb-6">
                    <div className="w-2 h-2 bg-blue-500 rounded-full" />
                    <h2 className="text-xl font-bold text-gray-900 dark:text-white">
                        {t('news.subHighlights', 'Tin tức nổi bật khác')}
                    </h2>
                </div>
                <Row gutter={[16, 16]}>
                    {Array.from({ length: 4 }, (_, index) => (
                        <Col xs={24} sm={12} lg={6} key={index}>
                            <Card>
                                <Skeleton loading active avatar paragraph={{ rows: 3 }}>
                                    <Card.Meta title="" description="" />
                                </Skeleton>
                            </Card>
                        </Col>
                    ))}
                </Row>
            </div>
        );
    }

    if (error) {
        return (
            <Alert
                message={t('news.error.loadFailed', 'Không thể tải tin tức')}
                description={t('news.error.tryAgain', 'Vui lòng thử lại sau')}
                type="error"
                showIcon
                className="mb-4"
            />
        );
    }

    if (!newsResponse?.data || newsResponse.data.length === 0) {
        return (
            <Empty
                image={Empty.PRESENTED_IMAGE_SIMPLE}
                description={t('news.empty.noNews', 'Chưa có tin tức')}
                className="py-12"
            />
        );
    }

    const newsList = newsResponse.data;

    return (
        <div className="space-y-6">
            <div className="flex items-center space-x-3 mb-6">
                <motion.div
                    animate={{ scale: [1, 1.2, 1] }}
                    transition={{ duration: 2, repeat: Infinity }}
                    className="w-2 h-2 bg-blue-500 rounded-full"
                />
                <h2 className="text-xl font-bold text-gray-900 dark:text-white">
                    {t('news.subHighlights', 'Tin tức nổi bật khác')}
                </h2>
            </div>

            <Row gutter={[16, 16]}>
                {newsList.map((newsItem: any, index: number) => (
                    <Col xs={24} sm={12} lg={6} key={newsItem.id}>
                        <motion.div
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ delay: index * 0.1, duration: 0.6 }}
                            whileHover={{ y: -5 }}
                        >
                            <Card
                                className="h-full hover:shadow-lg transition-all duration-300 border-0 shadow-sm"
                                cover={
                                    <div className="relative h-48 overflow-hidden">
                                        <Image
                                            src={getImageUrl(newsItem)}
                                            alt={newsItem.title}
                                            className="w-full h-full object-cover transition-transform duration-300 hover:scale-110"
                                            fallback="/images/placeholder-news.png"
                                            preview={false}
                                        />
                                        <div className="absolute top-2 left-2">
                                            <Tag className="bg-blue-500 text-white border-blue-500 rounded">
                                                {newsItem.category?.name || 'Tin tức'}
                                            </Tag>
                                        </div>
                                    </div>
                                }
                                actions={[
                                    <Link to={`/news/${newsItem.slug}`} key="view">
                                        <Button
                                            type="text"
                                            icon={<ArrowRightOutlined />}
                                            className="text-blue-500 hover:text-blue-600"
                                        >
                                            {t('news.readMore', 'Xem chi tiết')}
                                        </Button>
                                    </Link>
                                ]}
                            >
                                <Card.Meta
                                    title={
                                        <Link
                                            to={`/news/${newsItem.slug}`}
                                            className="text-gray-900 hover:text-blue-600 transition-colors line-clamp-2"
                                        >
                                            {newsItem.title}
                                        </Link>
                                    }
                                    description={
                                        <div className="space-y-3">
                                            <p className="text-gray-600 text-sm line-clamp-2">
                                                {getSummary(newsItem)}
                                            </p>

                                            <div className="flex items-center justify-between text-xs text-gray-500">
                                                <div className="flex items-center space-x-2">
                                                    <Avatar
                                                        size="small"
                                                        icon={<UserOutlined />}
                                                        src={newsItem.author?.avatar_url}
                                                    />
                                                    <span>{newsItem.author?.name || 'Admin'}</span>
                                                </div>

                                                <div className="flex items-center space-x-1">
                                                    <ClockCircleOutlined />
                                                    <span>{dayjs(newsItem.published_at || newsItem.publish_date).fromNow()}</span>
                                                </div>
                                            </div>

                                            <div className="flex items-center justify-between text-xs text-gray-500">
                                                <div className="flex items-center space-x-1">
                                                    <EyeOutlined />
                                                    <span>{(newsItem.views || 0).toLocaleString()} lượt xem</span>
                                                </div>

                                                {newsItem.tags && newsItem.tags.length > 0 && (
                                                    <div className="flex space-x-1">
                                                        {newsItem.tags.slice(0, 2).map((tag: any) => (
                                                            <Tag key={tag.id} className="text-xs">
                                                                #{tag.name}
                                                            </Tag>
                                                        ))}
                                                    </div>
                                                )}
                                            </div>
                                        </div>
                                    }
                                />
                            </Card>
                        </motion.div>
                    </Col>
                ))}
            </Row>
        </div>
    );
};

export default NewsHighlights;
