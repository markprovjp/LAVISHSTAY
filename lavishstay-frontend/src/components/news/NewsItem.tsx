// src/components/news/NewsItem.tsx
import React from 'react';
import { Card, Tag, Space, Typography, Avatar } from 'antd';
import { EyeOutlined, CalendarOutlined, UserOutlined } from '@ant-design/icons';
import { motion } from 'framer-motion';
import { NormalizedNewsItem } from '../../utils/normalizeNewsData';

const { Title, Text, Paragraph } = Typography;

interface NewsItemProps {
    news: NormalizedNewsItem;
    onClick?: (slug: string) => void;
    showCategory?: boolean;
    showAuthor?: boolean;
    showViews?: boolean;
    showTags?: boolean;
    className?: string;
}

const NewsItem: React.FC<NewsItemProps> = ({
    news,
    onClick,
    showCategory = true,
    showAuthor = true,
    showViews = true,
    showTags = true,
    className = '',
}) => {
    // No need for formatNewsData since data is already normalized
    const formattedNews = news;

    const handleClick = () => {
        if (onClick) {
            onClick(formattedNews.slug);
        }
    };

    // Simple time formatting function
    const formatTimeAgo = (date: Date) => {
        const now = new Date();
        const diffInHours = Math.floor((now.getTime() - date.getTime()) / (1000 * 60 * 60));

        if (diffInHours < 1) return 'Vừa xong';
        if (diffInHours < 24) return `${diffInHours} giờ trước`;

        const diffInDays = Math.floor(diffInHours / 24);
        if (diffInDays < 7) return `${diffInDays} ngày trước`;

        const diffInWeeks = Math.floor(diffInDays / 7);
        if (diffInWeeks < 4) return `${diffInWeeks} tuần trước`;

        const diffInMonths = Math.floor(diffInDays / 30);
        if (diffInMonths < 12) return `${diffInMonths} tháng trước`;

        const diffInYears = Math.floor(diffInDays / 365);
        return `${diffInYears} năm trước`;
    };

    const timeAgo = formatTimeAgo(formattedNews.publishedDate);

    const cardContent = (
        <>
            {/* Image */}
            <div className="relative overflow-hidden rounded-lg">
                <img
                    src={formattedNews.imageUrl}
                    alt={formattedNews.title}
                    className="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105"
                    onError={(e) => {
                        const target = e.target as HTMLImageElement;
                        target.src = '/images/default-news.jpg';
                    }}
                />
                {showCategory && (
                    <div className="absolute top-3 left-3">
                        <Tag
                            color="blue"
                            className="px-2 py-1 text-xs font-medium bg-blue-500 text-white border-0"
                        >
                            {formattedNews.categoryName}
                        </Tag>
                    </div>
                )}
            </div>

            {/* Content */}
            <div className="p-4">
                {/* Title */}
                <Title
                    level={4}
                    className="mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors"
                    style={{ margin: '0 0 8px 0' }}
                >
                    {formattedNews.title}
                </Title>

                {/* Summary */}
                <Paragraph
                    className="text-gray-600 mb-3 line-clamp-3"
                    style={{ margin: '0 0 12px 0' }}
                >
                    {formattedNews.summary}
                </Paragraph>

                {/* Meta Info */}
                <div className="flex flex-col gap-2">
                    <Space size="middle" wrap>
                        <Space size="small">
                            <CalendarOutlined className="text-gray-400" />
                            <Text type="secondary" className="text-sm">
                                {timeAgo}
                            </Text>
                        </Space>

                        {showViews && (
                            <Space size="small">
                                <EyeOutlined className="text-gray-400" />
                                <Text type="secondary" className="text-sm">
                                    {formattedNews.views.toLocaleString()} lượt xem
                                </Text>
                            </Space>
                        )}

                        {showAuthor && (
                            <Space size="small">
                                <Avatar
                                    size="small"
                                    src={formattedNews.authorAvatar}
                                    icon={<UserOutlined />}
                                />
                                <Text type="secondary" className="text-sm">
                                    {formattedNews.authorName}
                                </Text>
                            </Space>
                        )}
                    </Space>

                    {/* Tags */}
                    {showTags && formattedNews.formattedTags && formattedNews.formattedTags.length > 0 && (
                        <div className="flex flex-wrap gap-1">
                            {formattedNews.formattedTags.slice(0, 3).map((tag: string, index: number) => (
                                <Tag
                                    key={index}
                                    className="text-xs px-2 py-1 bg-gray-50 border-gray-200 text-gray-600"
                                >
                                    #{tag}
                                </Tag>
                            ))}
                            {formattedNews.formattedTags.length > 3 && (
                                <Tag className="text-xs px-2 py-1 bg-gray-50 border-gray-200 text-gray-400">
                                    +{formattedNews.formattedTags.length - 3}
                                </Tag>
                            )}
                        </div>
                    )}
                </div>
            </div>
        </>
    );

    return (
        <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.3 }}
            className={className}
        >
            <Card
                hoverable
                className="h-full cursor-pointer group shadow-sm hover:shadow-lg transition-all duration-300 border-0"
                bodyStyle={{ padding: 0 }}
                onClick={handleClick}
            >
                {cardContent}
            </Card>
        </motion.div>
    );
};

export default NewsItem;
