// src/components/news/NewsHighlights.tsx
import React from 'react';
import { Card, Tag, Avatar, Skeleton, Row, Col } from 'antd';
import { motion } from 'framer-motion';
import { useQuery } from '@tanstack/react-query';
import { useTranslation } from 'react-i18next';
import { ClockCircleOutlined, EyeOutlined, UserOutlined } from '@ant-design/icons';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';

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
}

interface NewsHighlightsProps {
    onNewsClick?: (news: NewsItem) => void;
}

// Mock API function
const fetchHighlightNews = async (): Promise<NewsItem[]> => {
    // Simulate API delay
    await new Promise(resolve => setTimeout(resolve, 1000));

    return [
        {
            id: '4',
            title: 'Spa & Wellness mới tại LavishStay - Trải nghiệm thư giãn hoàn hảo',
            summary: 'Khu spa rộng 2000m² với các liệu pháp massage và chăm sóc sức khỏe đẳng cấp quốc tế.',
            imageUrl: 'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?w=400&h=250&fit=crop',
            category: 'Spa & Wellness',
            publishedAt: new Date('2024-01-12'),
            author: { name: 'Phạm Thị D' },
            views: 4321,
            tags: ['spa', 'wellness', 'massage']
        },
        {
            id: '5',
            title: 'Hội nghị khách hàng VIP 2024 - Tri ân và ưu đãi đặc biệt',
            summary: 'Sự kiện dành riêng cho khách hàng thân thiết với nhiều phần quà giá trị và ưu đãi hấp dẫn.',
            imageUrl: 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?w=400&h=250&fit=crop',
            category: 'Sự kiện',
            publishedAt: new Date('2024-01-11'),
            author: { name: 'Võ Văn E' },
            views: 3654,
            tags: ['hội nghị', 'VIP', 'tri ân']
        },
        {
            id: '6',
            title: 'Khu vui chơi trẻ em mới - Thiên đường cho gia đình',
            summary: 'Không gian vui chơi an toàn và thú vị dành cho các bé với nhiều trò chơi hấp dẫn.',
            imageUrl: 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=400&h=250&fit=crop',
            category: 'Gia đình',
            publishedAt: new Date('2024-01-10'),
            author: { name: 'Hoàng Thị F' },
            views: 2987,
            tags: ['trẻ em', 'gia đình', 'vui chơi']
        },
        {
            id: '7',
            title: 'Gói cưới tại LavishStay - Ngày trọng đại hoàn hảo',
            summary: 'Dịch vụ tổ chức tiệc cưới đẳng cấp với không gian lãng mạn và menu cao cấp.',
            imageUrl: 'https://images.unsplash.com/photo-1519741497674-611481863552?w=400&h=250&fit=crop',
            category: 'Tiệc cưới',
            publishedAt: new Date('2024-01-09'),
            author: { name: 'Đặng Văn G' },
            views: 5432,
            tags: ['tiệc cưới', 'lãng mạn', 'cao cấp']
        }
    ];
};

const NewsHighlights: React.FC<NewsHighlightsProps> = ({ onNewsClick }) => {
    const { t } = useTranslation();

    const { data: news, isLoading, error } = useQuery({
        queryKey: ['highlightNews'],
        queryFn: fetchHighlightNews,
        staleTime: 5 * 60 * 1000, // 5 minutes
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
                    {[1, 2, 3, 4].map((item) => (
                        <Col xs={24} sm={12} lg={6} key={item}>
                            <Card className="h-full">
                                <Skeleton.Image style={{ width: '100%', height: 150 }} />
                                <div className="mt-4">
                                    <Skeleton active paragraph={{ rows: 3 }} />
                                </div>
                            </Card>
                        </Col>
                    ))}
                </Row>
            </div>
        );
    }

    if (error) {
        return (
            <div className="text-center py-8">
                <p className="text-red-500">{t('news.error', 'Có lỗi xảy ra khi tải tin tức')}</p>
            </div>
        );
    }

    return (
        <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay: 0.2 }}
            className="space-y-6"
        >
            <div className="flex items-center space-x-3">
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
                {news?.map((item, index) => (
                    <Col xs={24} sm={12} lg={6} key={item.id}>
                        <motion.div
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.5, delay: index * 0.1 }}
                            whileHover={{ y: -5 }}
                            className="h-full"
                        >
                            <Card
                                hoverable
                                className="h-full group cursor-pointer border-gray-200 dark:border-gray-700 dark:bg-gray-800 shadow-sm hover:shadow-lg transition-all duration-300"
                                onClick={() => onNewsClick?.(item)}
                                cover={
                                    <div className="relative overflow-hidden">
                                        <img
                                            alt={item.title}
                                            src={item.imageUrl}
                                            className="w-full h-40 object-cover group-hover:scale-110 transition-transform duration-500"
                                        />
                                        <div className="absolute top-2 left-2">
                                            <Tag color="blue" className="px-2 py-1 text-xs rounded-full">
                                                {item.category}
                                            </Tag>
                                        </div>
                                        <div className="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
                                    </div>
                                }
                            >
                                <div className="space-y-3">
                                    {/* Title */}
                                    <h3 className="font-semibold text-gray-900 dark:text-white line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                        {item.title}
                                    </h3>

                                    {/* Summary */}
                                    <p className="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                        {item.summary}
                                    </p>

                                    {/* Tags */}
                                    <div className="flex flex-wrap gap-1">
                                        {item.tags.slice(0, 2).map((tag) => (
                                            <Tag
                                                key={tag}
                                                className="text-xs px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 border-none rounded-full"
                                            >
                                                #{tag}
                                            </Tag>
                                        ))}
                                    </div>

                                    {/* Meta */}
                                    <div className="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 pt-2 border-t border-gray-100 dark:border-gray-700">
                                        <div className="flex items-center space-x-2">
                                            <Avatar
                                                size="small"
                                                icon={<UserOutlined />}
                                                className="w-5 h-5"
                                            />
                                            <span>{item.author.name}</span>
                                        </div>

                                        <div className="flex items-center space-x-3">
                                            <div className="flex items-center space-x-1">
                                                <ClockCircleOutlined />
                                                <span>{dayjs(item.publishedAt).fromNow()}</span>
                                            </div>
                                            <div className="flex items-center space-x-1">
                                                <EyeOutlined />
                                                <span>{item.views.toLocaleString()}</span>
                                            </div>
                                        </div>
                                    </div>
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
