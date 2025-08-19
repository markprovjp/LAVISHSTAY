// src/components/news/NewsSidebar.tsx
import React, { useState, useEffect } from 'react';
import { Card, List, Tag, Divider, Progress, Statistic, Empty } from 'antd';
import { motion } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { useTrendingNews, useNewsCategories } from '../../hooks/useNews';
import type { NewsItem } from '../../services/newsApi';
import {
    FireOutlined,
    EyeOutlined,
    ClockCircleOutlined,
    CloudOutlined,
    RiseOutlined,
    TrophyOutlined,
    CalendarOutlined
} from '@ant-design/icons';
import dayjs from 'dayjs';

interface WeatherData {
    location: string;
    temperature: number;
    condition: string;
    humidity: number;
    windSpeed: number;
    icon: string;
}

const NewsSidebar: React.FC = () => {
    const { t } = useTranslation();
    const [currentTime, setCurrentTime] = useState(dayjs());

    // Update clock every second
    useEffect(() => {
        const timer = setInterval(() => {
            setCurrentTime(dayjs());
        }, 1000);

        return () => clearInterval(timer);
    }, []);

    // Lấy tin thịnh hành từ API
    const { data: trendingResponse, isLoading: isTrendingLoading } = useTrendingNews({
        per_page: 5,
        sort_by: 'views',
        sort_order: 'desc'
    });

    // Lấy categories để hiển thị thống kê
    const { data: categoriesResponse } = useNewsCategories();

    // Mock weather data (có thể tích hợp API thời tiết thật)
    const weatherData: WeatherData = {
        location: 'TP. Hồ Chí Minh',
        temperature: 32,
        condition: 'Nắng ít mây',
        humidity: 75,
        windSpeed: 15,
        icon: '☀️'
    };

    // Tính toán top categories từ API data
    const topCategories = categoriesResponse?.data
        .sort((a, b) => (b.news_count || 0) - (a.news_count || 0))
        .slice(0, 5)
        .map((cat, index) => {
            const colors = ['#1890ff', '#52c41a', '#faad14', '#f5222d', '#722ed1'];
            const total = categoriesResponse.data.reduce((sum, c) => sum + (c.news_count || 0), 0);
            return {
                name: cat.name,
                count: cat.news_count || 0,
                percentage: total > 0 ? Math.round(((cat.news_count || 0) / total) * 100) : 0,
                color: colors[index % colors.length]
            };
        }) || [];

    return (
        <div className="space-y-6">
            {/* Clock Widget */}
            <motion.div
                initial={{ opacity: 0, scale: 0.9 }}
                animate={{ opacity: 1, scale: 1 }}
                transition={{ duration: 0.5 }}
            >
                <Card 
                    className="text-center bg-gradient-to-br from-blue-500 to-purple-600 text-white border-0"
                    bodyStyle={{ padding: '20px' }}
                >
                    <div className="space-y-2">
                        <ClockCircleOutlined className="text-2xl mb-2" />
                        <div className="text-2xl font-bold">
                            {currentTime.format('HH:mm:ss')}
                        </div>
                        <div className="text-sm opacity-90">
                            {currentTime.format('dddd, DD/MM/YYYY')}
                        </div>
                    </div>
                </Card>
            </motion.div>

            {/* Weather Widget */}
            <motion.div
                initial={{ opacity: 0, x: 50 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ duration: 0.5, delay: 0.1 }}
            >
                <Card 
                    title={
                        <div className="flex items-center space-x-2">
                            <CloudOutlined className="text-blue-500" />
                            <span>{t('news.sidebar.weather', 'Thời tiết')}</span>
                        </div>
                    }
                    className="shadow-md"
                    bodyStyle={{ padding: '16px' }}
                >
                    <div className="flex items-center justify-between">
                        <div>
                            <div className="text-2xl font-bold text-orange-500">
                                {weatherData.temperature}°C
                            </div>
                            <div className="text-sm text-gray-600 dark:text-gray-400">
                                {weatherData.condition}
                            </div>
                        </div>
                        <div className="text-3xl">
                            {weatherData.icon}
                        </div>
                    </div>
                    <Divider className="my-3" />
                    <div className="grid grid-cols-2 gap-2 text-xs">
                        <div className="flex items-center space-x-1">
                            <span className="opacity-70">Độ ẩm:</span>
                            <span>{weatherData.humidity}%</span>
                        </div>
                        <div className="flex items-center space-x-1">
                            <span className="opacity-70">Gió:</span>
                            <span>{weatherData.windSpeed} km/h</span>
                        </div>
                    </div>
                </Card>
            </motion.div>

            {/* Trending News */}
            <motion.div
                initial={{ opacity: 0, x: 50 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ duration: 0.5, delay: 0.2 }}
            >
                <Card 
                    title={
                        <div className="flex items-center space-x-2">
                            <FireOutlined className="text-red-500" />
                            <span>{t('news.sidebar.trending', 'Tin thịnh hành')}</span>
                        </div>
                    }
                    className="shadow-md"
                    bodyStyle={{ padding: '12px' }}
                >
                    {isTrendingLoading ? (
                        <div className="space-y-3">
                            {Array.from({ length: 3 }, (_, i) => (
                                <div key={i} className="flex space-x-3 animate-pulse">
                                    <div className="w-12 h-12 bg-gray-200 rounded"></div>
                                    <div className="flex-1 space-y-2">
                                        <div className="h-3 bg-gray-200 rounded w-3/4"></div>
                                        <div className="h-2 bg-gray-200 rounded w-1/2"></div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : !trendingResponse?.data || trendingResponse.data.length === 0 ? (
                        <Empty 
                            image={Empty.PRESENTED_IMAGE_SIMPLE}
                            description={t('news.empty.noTrending', 'Chưa có tin thịnh hành')}
                            className="py-4"
                        />
                    ) : (
                        <List
                            dataSource={trendingResponse.data}
                            renderItem={(item: NewsItem, index: number) => (
                                <List.Item className="px-0 py-2 border-b-0 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors rounded cursor-pointer">
                                    <div className="flex items-start space-x-3 w-full">
                                        <div className="flex-shrink-0">
                                            <div className="w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                                        </div>
                                        <div className="flex-1 min-w-0">
                                            <h4 className="text-sm font-medium line-clamp-2 text-gray-900 dark:text-white mb-1">
                                                {item.title}
                                            </h4>
                                            <div className="flex items-center space-x-2 text-xs text-gray-500 dark:text-gray-400">
                                                <div className="flex items-center space-x-1">
                                                    <EyeOutlined />
                                                    <span>{item.views.toLocaleString()}</span>
                                                </div>
                                                <div className="flex items-center space-x-1">
                                                    <RiseOutlined className="text-green-500" />
                                                    <span>#{index + 1}</span>
                                                </div>
                                            </div>
                                            <div className="mt-1">
                                                <Tag 
                                                    className="text-xs px-2 py-0"
                                                    color="blue"
                                                >
                                                    {item.category?.name || 'Tin tức'}
                                                </Tag>
                                            </div>
                                        </div>
                                    </div>
                                </List.Item>
                            )}
                        />
                    )}
                </Card>
            </motion.div>

            {/* Top Categories */}
            <motion.div
                initial={{ opacity: 0, x: 50 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ duration: 0.5, delay: 0.3 }}
            >
                <Card 
                    title={
                        <div className="flex items-center space-x-2">
                            <TrophyOutlined className="text-yellow-500" />
                            <span>{t('news.sidebar.topCategories', 'Chủ đề hàng đầu')}</span>
                        </div>
                    }
                    className="shadow-md"
                    bodyStyle={{ padding: '16px' }}
                >
                    {topCategories.length === 0 ? (
                        <Empty 
                            image={Empty.PRESENTED_IMAGE_SIMPLE}
                            description={t('news.empty.noCategories', 'Chưa có danh mục')}
                            className="py-4"
                        />
                    ) : (
                        <div className="space-y-3">
                            {topCategories.map((category, index) => (
                                <motion.div
                                    key={category.name}
                                    initial={{ opacity: 0, x: -20 }}
                                    animate={{ opacity: 1, x: 0 }}
                                    transition={{ duration: 0.3, delay: index * 0.05 }}
                                    className="space-y-2"
                                >
                                    <div className="flex items-center justify-between">
                                        <span className="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {category.name}
                                        </span>
                                        <div className="flex items-center space-x-2">
                                            <span className="text-xs text-gray-500">
                                                {category.count}
                                            </span>
                                            <span className="text-xs text-gray-400">
                                                ({category.percentage}%)
                                            </span>
                                        </div>
                                    </div>
                                    <Progress
                                        percent={category.percentage}
                                        size="small"
                                        strokeColor={category.color}
                                        showInfo={false}
                                        className="mb-0"
                                    />
                                </motion.div>
                            ))}
                        </div>
                    )}
                </Card>
            </motion.div>

            {/* Quick Stats */}
            <motion.div
                initial={{ opacity: 0, x: 50 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ duration: 0.5, delay: 0.4 }}
            >
                <Card 
                    title={
                        <div className="flex items-center space-x-2">
                            <CalendarOutlined className="text-green-500" />
                            <span>{t('news.sidebar.quickStats', 'Thống kê nhanh')}</span>
                        </div>
                    }
                    className="shadow-md"
                    bodyStyle={{ padding: '16px' }}
                >
                    <div className="grid grid-cols-2 gap-4">
                        <Statistic
                            title={t('news.stats.totalNews', 'Tổng bài viết')}
                            value={categoriesResponse?.data.reduce((sum, c) => sum + (c.news_count || 0), 0) || 0}
                            valueStyle={{ fontSize: '16px', color: '#1890ff' }}
                        />
                        <Statistic
                            title={t('news.stats.categories', 'Danh mục')}
                            value={categoriesResponse?.data.length || 0}
                            valueStyle={{ fontSize: '16px', color: '#52c41a' }}
                        />
                    </div>
                </Card>
            </motion.div>
        </div>
    );
};

export default NewsSidebar;
