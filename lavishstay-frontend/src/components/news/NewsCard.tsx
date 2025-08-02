// src/components/news/NewsCard.tsx
import React from 'react';
import { Card, Tag, Avatar, Tooltip, Space } from 'antd';
import { motion } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import {
    ClockCircleOutlined,
    EyeOutlined,
    UserOutlined,
    ShareAltOutlined
} from '@ant-design/icons';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import NewsBookmarkButton from './NewsBookmarkButton';
import NewsLikeButton from './NewsLikeButton';
import NewsShareButton from './NewsShareButton';

dayjs.extend(relativeTime);

interface NewsItem {
    id: string;
    title: string;
    summary: string;
    imageUrl: string;
    category: string;
    publishedAt: Date;
    author: {
        name: string;
        avatar?: string;
    };
    views: number;
    tags: string[];
    isBookmarked: boolean;
    isLiked: boolean;
    likesCount: number;
}

interface NewsCardProps {
    news: NewsItem;
    onClick?: () => void;
    compact?: boolean;
}

const NewsCard: React.FC<NewsCardProps> = ({
    news,
    onClick,
    compact = false
}) => {
    const { t } = useTranslation();

    const cardActions = [
        <Space key="actions" size={4} className="flex items-center justify-center w-full">
            <NewsLikeButton
                newsId={news.id}
                isLiked={news.isLiked}
                initialLikeCount={news.likesCount}
                size="small"
                type="text"
            >
                <span className="ml-1">Thích</span>
            </NewsLikeButton>
            <NewsShareButton
                newsId={news.id}
                title={news.title}
                url={`/news/${news.id}`}
                size="small"
                type="text"
            >
                <span className="ml-1">Chia sẻ</span>
            </NewsShareButton>
            <NewsBookmarkButton
                newsId={news.id}
                isBookmarked={news.isBookmarked}
                size="small"
                type="text"
            >
                <span className="ml-1">Lưu</span>
            </NewsBookmarkButton>
        </Space>
    ];

    return (
        <div className="h-full">
            <Card
                hoverable
                className={`h-full group cursor-pointer border-gray-200 dark:border-gray-700 dark:bg-gray-800 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden ${compact ? 'news-card-compact' : 'news-card-full'
                    }`}
                onClick={onClick}
                cover={
                    <div className="relative overflow-hidden">
                        <motion.img
                            alt={news.title}
                            src={news.imageUrl}
                            className={`w-full object-cover group-hover:scale-110 transition-transform duration-700 ${compact ? 'h-32' : 'h-48'
                                }`}
                            whileHover={{ scale: 1.1 }}
                            transition={{ duration: 0.7 }}
                        />

                        {/* Image Overlay */}
                        <div className="absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-300" />

                        {/* Category Badge */}
                        <div className="absolute top-3 left-3">
                            <motion.div
                                initial={{ opacity: 0, x: -20 }}
                                animate={{ opacity: 1, x: 0 }}
                                transition={{ duration: 0.3 }}
                            >
                                <Tag
                                    color="blue"
                                    className="px-3 py-1 text-xs font-medium rounded-full bg-blue-500/90 text-white border-none backdrop-blur-sm"
                                >
                                    {news.category}
                                </Tag>
                            </motion.div>
                        </div>

                        {/* Quick Actions Overlay */}
                        <div className="absolute top-3 right-3 flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <motion.div
                                initial={{ scale: 0 }}
                                whileHover={{ scale: 1.1 }}
                                className="bg-white/90 backdrop-blur-sm rounded-full p-2"
                            >
                                <ShareAltOutlined className="text-gray-700 text-sm" />
                            </motion.div>
                        </div>

                        {/* Reading Time */}
                        <div className="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <div className="bg-black/70 text-white text-xs px-2 py-1 rounded-full backdrop-blur-sm">
                                {t('news.readTime', '5 phút đọc')}
                            </div>
                        </div>
                    </div>
                }
                actions={!compact ? cardActions : undefined}
            >
                <div className="space-y-3">
                    {/* Title */}
                    <motion.h3
                        initial={{ opacity: 0, y: 10 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.3 }}
                        className={`font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors leading-tight ${compact ? 'text-sm line-clamp-2' : 'text-base line-clamp-2'
                            }`}
                    >
                        {news.title}
                    </motion.h3>

                    {/* Summary */}
                    {!compact && (
                        <motion.p
                            initial={{ opacity: 0, y: 10 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.3, delay: 0.1 }}
                            className="text-sm text-gray-600 dark:text-gray-400 line-clamp-3 leading-relaxed"
                        >
                            {news.summary}
                        </motion.p>
                    )}

                    {/* Tags */}
                    <motion.div
                        initial={{ opacity: 0, y: 10 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.3, delay: 0.2 }}
                        className="flex flex-wrap gap-1"
                    >
                        {news.tags.slice(0, compact ? 2 : 3).map((tag) => (
                            <Tag
                                key={tag}
                                className="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 border-none rounded-full hover:bg-blue-100 dark:hover:bg-blue-900 hover:text-blue-600 dark:hover:text-blue-400 transition-colors cursor-pointer"
                            >
                                #{tag}
                            </Tag>
                        ))}
                    </motion.div>

                    {/* Meta Information */}
                    <motion.div
                        initial={{ opacity: 0, y: 10 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.3, delay: 0.3 }}
                        className={`flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700 ${compact ? 'text-xs' : 'text-sm'
                            }`}
                    >
                        {/* Author & Time */}
                        <div className="flex items-center gap-2 min-w-0">
                            <Avatar
                                size={compact ? 20 : 24}
                                src={news.author.avatar}
                                icon={<UserOutlined />}
                                className="border border-gray-200 dark:border-gray-600 flex-shrink-0"
                            />
                            <div className="flex flex-col min-w-0">
                                <Tooltip title={news.author.name} placement="topLeft">
                                    <span className="truncate max-w-[90px] font-medium text-gray-700 dark:text-gray-200 text-xs md:text-sm">
                                        {news.author.name}
                                    </span>
                                </Tooltip>
                                <span className="flex items-center text-xs text-gray-400 mt-0.5">
                                    <ClockCircleOutlined className="mr-1" />
                                    {dayjs(news.publishedAt).fromNow()}
                                </span>
                            </div>
                        </div>

                        {/* Views */}
                        <div className="flex items-center space-x-1 text-gray-500 dark:text-gray-400">
                            <EyeOutlined className="text-xs" />
                            <span>{news.views.toLocaleString()}</span>
                        </div>
                    </motion.div>

                    {/* Compact Actions */}
                    {compact && (
                        <motion.div
                            initial={{ opacity: 0, y: 10 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.3, delay: 0.4 }}
                            className="flex justify-between items-center pt-2"
                        >
                            <div className="flex space-x-2">
                                {cardActions}
                            </div>
                        </motion.div>
                    )}
                </div>

                {/* Animated Background Pattern */}
                <div className="absolute inset-0 opacity-0 group-hover:opacity-5 transition-opacity duration-500 pointer-events-none">
                    <div className="absolute inset-0 " />
                    <motion.div
                        animate={{
                            backgroundPosition: ['0% 0%', '100% 100%'],
                        }}
                        transition={{
                            duration: 10,
                            repeat: Infinity,
                            ease: "linear"
                        }}
                        className="absolute inset-0 opacity-30"
                        style={{
                            backgroundImage: 'radial-gradient(circle at 20% 80%, #3b82f6 0%, transparent 50%), radial-gradient(circle at 80% 20%, #8b5cf6 0%, transparent 50%)',
                            backgroundSize: '100px 100px'
                        }}
                    />
                </div>

                {/* Hover Glow Effect */}
                <motion.div
                    className="absolute -inset-1  rounded-lg opacity-0 group-hover:opacity-20 blur transition-opacity duration-300 pointer-events-none"
                    animate={{
                        scale: [1, 1.02, 1],
                    }}
                    transition={{
                        duration: 2,
                        repeat: Infinity,
                        ease: "easeInOut"
                    }}
                />
            </Card>
        </div>
    );
};

export default NewsCard;
