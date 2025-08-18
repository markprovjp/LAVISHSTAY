// src/components/news/NewsHighlights.tsx
import React from 'react';
import { Card, Tag, Avatar, Row, Col, Alert, Empty } from 'antd';
import { motion } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { ClockCircleOutlined, EyeOutlined, UserOutlined } from '@ant-design/icons';
import { useNewsList } from '../../hooks/useNews';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { normalizeNewsResponse } from '../../utils/normalizeNewsData';

dayjs.extend(relativeTime);

interface NewsHighlightsProps {
    onNewsClick?: (slug: string) => void;
}

const NewsHighlights: React.FC<NewsHighlightsProps> = ({ onNewsClick }) => {
    const { t } = useTranslation();

    // Lấy tin nổi bật khác từ API (không phải banner chính)
    const { data: newsResponse, isLoading, error } = useNewsList({
        per_page: 4,
        is_featured: 0,
        sort_by: 'published_at',
        sort_order: 'desc'
    });

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
                            <Card loading />
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
            />
        );
    }

    if (!newsResponse?.data || newsResponse.data.length === 0) {
        return (
            <Empty
                image={Empty.PRESENTED_IMAGE_SIMPLE}
                description={t('news.empty.noNews', 'Chưa có tin tức')}
            />
        );
    }

    // Normalize the response data to prevent iteration/spreading errors
    const normalizedResponse = normalizeNewsResponse(newsResponse);
    const news = normalizedResponse.data;

    return (
        <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.2 }}
            className="space-y-6"
        >
            <div className="flex items-center space-x-3 mb-6">
                <motion.div
                    animate={{ scale: [1, 1.2, 1] }}
                    transition={{ duration: 2, repeat: Infinity, ease: "easeInOut" }}
                    className="w-2 h-2 bg-blue-500 rounded-full"
                />
                <h2 className="text-xl font-bold text-gray-900 dark:text-white">
                    {t('news.subHighlights', 'Tin tức nổi bật khác')}
                </h2>
            </div>

            <Row gutter={[16, 16]}>
                {news.map((newsItem, index) => (
                    <Col xs={24} sm={12} lg={6} key={newsItem.id}>
                        <motion.div
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{
                                duration: 0.5,
                                delay: index * 0.1,
                                ease: "easeOut"
                            }}
                            whileHover={{
                                y: -5,
                                transition: { duration: 0.2 }
                            }}
                        >
                            <Card
                                hoverable
                                className="h-full shadow-md hover:shadow-xl transition-all duration-300 border-0 overflow-hidden bg-white dark:bg-gray-800"
                                cover={
                                    <div className="relative overflow-hidden group">
                                        <img
                                            alt={newsItem.title}
                                            src={newsItem.featured_image || 'https://via.placeholder.com/400x250'}
                                            className="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-110"
                                        />
                                        <div className="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />

                                        {/* Category badge */}
                                        <div className="absolute top-3 left-3">
                                            <Tag
                                                className="px-2 py-1 text-xs bg-blue-500 text-white border-blue-500 rounded-md font-medium"
                                            >
                                                {newsItem.category?.name || 'Tin tức'}
                                            </Tag>
                                        </div>
                                    </div>
                                }
                                onClick={() => onNewsClick?.(newsItem.slug)}
                            >
                                <div className="p-2">
                                    <h3 className="text-base font-bold mb-2 line-clamp-2 text-gray-900 dark:text-white leading-tight">
                                        {newsItem.title}
                                    </h3>

                                    <p className="text-gray-600 dark:text-gray-300 text-sm mb-3 line-clamp-2 leading-relaxed">
                                        {newsItem.summary}
                                    </p>

                                    {/* Author and stats */}
                                    <div className="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-3">
                                        <div className="flex items-center space-x-2">
                                            <Avatar
                                                size={20}
                                                icon={<UserOutlined />}
                                                src={newsItem.authorAvatar}
                                            />
                                            <span>{newsItem.authorName}</span>
                                        </div>

                                        <div className="flex items-center space-x-3">
                                            <div className="flex items-center space-x-1">
                                                <EyeOutlined />
                                                <span>{newsItem.views.toLocaleString()}</span>
                                            </div>
                                            <div className="flex items-center space-x-1">
                                                <ClockCircleOutlined />
                                                <span>{dayjs(newsItem.published_at).fromNow()}</span>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Tags */}
                                    {newsItem.formattedTags && newsItem.formattedTags.length > 0 && (
                                        <div className="flex flex-wrap gap-1 mb-2">
                                            {newsItem.formattedTags.slice(0, 2).map((tag, index) => (
                                                <Tag key={index} color="blue">
                                                    #{tag}
                                                </Tag>
                                            ))}
                                        </div>
                                    )}
                                </div>
                            </Card>
                        </motion.div>
                    </Col>
                ))}
            </Row>
        </motion.div>
    );
};

export default NewsHighlights;
