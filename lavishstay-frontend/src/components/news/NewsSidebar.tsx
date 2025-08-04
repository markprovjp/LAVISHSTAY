// src/components/news/NewsSidebar.tsx
import React, { useState, useEffect } from 'react';
import { Card, List, Typography, Avatar, Tag, Divider, Skeleton, Progress, Statistic } from 'antd';
import { motion, AnimatePresence } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { useQuery } from '@tanstack/react-query';
import {
    FireOutlined,
    EyeOutlined,
    ClockCircleOutlined,
    CloudOutlined,
    EnvironmentOutlined,
    RiseOutlined,
    FallOutlined,
    TrophyOutlined,
    CalendarOutlined
} from '@ant-design/icons';
import dayjs from 'dayjs';
import axios from 'axios';

const { Title, Text } = Typography;

interface TrendingNews {
    id: string;
    title: string;
    views: number;
    thumbnail?: string;
    publishedAt: string;
    category: string;
    trend: 'up' | 'down' | 'stable';
}

interface WeatherData {
    location: string;
    temperature: number;
    condition: string;
    humidity: number;
    windSpeed: number;
    icon: string;
}

interface TopCategory {
    name: string;
    count: number;
    percentage: number;
    color: string;
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

    // Mock trending news data
    const { data: trendingNews, isLoading: isTrendingLoading } = useQuery({
        queryKey: ['trending-news'],
        queryFn: async (): Promise<TrendingNews[]> => {
            // Mock API call
            await new Promise(resolve => setTimeout(resolve, 1000));
            return [
                {
                    id: '1',
                    title: 'Công nghệ AI mới nhất trong năm 2024',
                    views: 125000,
                    thumbnail: 'https://picsum.photos/60/60?random=1',
                    publishedAt: '2024-01-15T10:30:00Z',
                    category: 'Công nghệ',
                    trend: 'up'
                },
                {
                    id: '2',
                    title: 'Thị trường chứng khoán biến động mạnh',
                    views: 98000,
                    thumbnail: 'https://picsum.photos/60/60?random=2',
                    publishedAt: '2024-01-15T09:15:00Z',
                    category: 'Kinh tế',
                    trend: 'down'
                },
                {
                    id: '3',
                    title: 'Du lịch Việt Nam hồi phục mạnh mẽ',
                    views: 87000,
                    thumbnail: 'https://picsum.photos/60/60?random=3',
                    publishedAt: '2024-01-15T08:45:00Z',
                    category: 'Du lịch',
                    trend: 'up'
                },
                {
                    id: '4',
                    title: 'Bóng đá Việt Nam chuẩn bị Asian Cup',
                    views: 76000,
                    thumbnail: 'https://picsum.photos/60/60?random=4',
                    publishedAt: '2024-01-15T07:30:00Z',
                    category: 'Thể thao',
                    trend: 'stable'
                },
                {
                    id: '5',
                    title: 'Giáo dục số hóa - xu hướng tương lai',
                    views: 65000,
                    thumbnail: 'https://picsum.photos/60/60?random=5',
                    publishedAt: '2024-01-15T06:00:00Z',
                    category: 'Giáo dục',
                    trend: 'up'
                }
            ];
        },
        staleTime: 5 * 60 * 1000, // 5 minutes
    });

    // Fetch weather data from OpenWeatherMap
    const { data: weather, isLoading: isWeatherLoading } = useQuery({
        queryKey: ['weather'],
        queryFn: async (): Promise<WeatherData> => {
            const apiKey = 'fe046278d91d553814da6a861d374570'; // <-- IMPORTANT: Replace with your actual API key
            const lat = 19.8063; // Latitude for Thanh Hóa
            const lon = 105.7739; // Longitude for Thanh Hóa
            const url = `https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric&lang=vi`;

            try {
                const response = await axios.get(url);
                const data = response.data;
                return {
                    location: data.name,
                    temperature: Math.round(data.main.temp),
                    condition: data.weather[0].description,
                    humidity: data.main.humidity,
                    windSpeed: Math.round(data.wind.speed * 3.6), // m/s to km/h
                    icon: `https://openweathermap.org/img/wn/${data.weather[0].icon}@2x.png`
                };
            } catch (error) {
                console.error("Failed to fetch weather data:", error);
                // Return mock data or handle error state
                return {
                    location: 'Thanh Hóa',
                    temperature: 28,
                    condition: 'Không thể tải dữ liệu',
                    humidity: 65,
                    windSpeed: 12,
                    icon: '' // No icon on error
                };
            }
        },
        staleTime: 10 * 60 * 1000, // 10 minutes
    });

    // Mock top categories
    const { data: topCategories, isLoading: isCategoriesLoading } = useQuery({
        queryKey: ['top-categories'],
        queryFn: async (): Promise<TopCategory[]> => {
            await new Promise(resolve => setTimeout(resolve, 600));
            return [
                { name: 'Công nghệ', count: 45, percentage: 25, color: '#1890ff' },
                { name: 'Kinh tế', count: 38, percentage: 21, color: '#52c41a' },
                { name: 'Thể thao', count: 32, percentage: 18, color: '#faad14' },
                { name: 'Du lịch', count: 28, percentage: 16, color: '#f759ab' },
                { name: 'Giáo dục', count: 22, percentage: 12, color: '#722ed1' },
                { name: 'Khác', count: 15, percentage: 8, color: '#8c8c8c' }
            ];
        },
        staleTime: 15 * 60 * 1000, // 15 minutes
    });

    const formatViews = (views: number): string => {
        if (views >= 1000000) {
            return `${(views / 1000000).toFixed(1)}M`;
        } else if (views >= 1000) {
            return `${(views / 1000).toFixed(1)}K`;
        }
        return views.toString();
    };

    const getTrendIcon = (trend: TrendingNews['trend']) => {
        switch (trend) {
            case 'up':
                return <RiseOutlined className="text-green-500" />;
            case 'down':
                return <FallOutlined className="text-red-500" />;
            default:
                return <EyeOutlined className="text-gray-500" />;
        }
    };

    const sidebarVariants = {
        hidden: { opacity: 0, x: 20 },
        visible: {
            opacity: 1,
            x: 0,
            transition: {
                duration: 0.5,
                staggerChildren: 0.1
            }
        }
    };

    const cardVariants = {
        hidden: { opacity: 0, y: 20 },
        visible: { opacity: 1, y: 0 }
    };

    return (
        <motion.div
            variants={sidebarVariants}
            initial="hidden"
            animate="visible"
            className="space-y-6"
        >
            {/* Current Time Widget */}
            <motion.div variants={cardVariants}>
                <Card className="shadow-sm hover:shadow-md transition-all duration-300">
                    <div className="text-center">
                        <CalendarOutlined className="text-2xl text-blue-500 mb-2" />
                        <Statistic
                            value={currentTime.format('HH:mm:ss')}
                            valueStyle={{ 
                                fontSize: '2rem', 
                                fontWeight: 'bold', 
                                color: '#1a202c' // Use a specific color from your theme if available
                            }}
                        />
                        <div className="text-sm text-gray-500">
                            {dayjs(currentTime).format('dddd, DD/MM/YYYY')}
                        </div>
                    </div>
                </Card>
            </motion.div>

            {/* Weather Widget */}
            <motion.div variants={cardVariants}>
                <Card
                    title={
                        <div className="flex items-center space-x-2">
                            <CloudOutlined className="text-blue-500" />
                            <span>{t('news.sidebar.weather', 'Thời tiết')}</span>
                        </div>
                    }
                    className="shadow-sm hover:shadow-md transition-all duration-300"
                >
                    {isWeatherLoading ? (
                        <Skeleton active paragraph={{ rows: 3 }} />
                    ) : weather && (
                        <div className="space-y-3">
                            <div className="flex items-center justify-between">
                                <div className="flex items-center space-x-2">
                                    <EnvironmentOutlined className="text-red-500" />
                                    <Text>{weather.location}</Text>
                                </div>
                                <div className="text-2xl">
                                    {weather.icon && <img src={weather.icon} alt={weather.condition} className="w-12 h-12" />}
                                </div>
                            </div>

                            <div className="text-center">
                                <div className="text-3xl font-bold text-orange-500">
                                    {weather.temperature}°C
                                </div>
                                <div className="text-sm text-gray-500">
                                    {weather.condition}
                                </div>
                            </div>

                            <div className="grid grid-cols-2 gap-4 text-xs">
                                <div className="text-center">
                                    <div className="text-gray-500">Độ ẩm</div>
                                    <div className="font-semibold">{weather.humidity}%</div>
                                </div>
                                <div className="text-center">
                                    <div className="text-gray-500">Gió</div>
                                    <div className="font-semibold">{weather.windSpeed} km/h</div>
                                </div>
                            </div>
                        </div>
                    )}
                </Card>
            </motion.div>

            {/* Trending News */}
            <motion.div variants={cardVariants}>
                <Card
                    title={
                        <div className="flex items-center space-x-2">
                            <FireOutlined className="text-red-500" />
                            <span>{t('news.sidebar.trending', 'Tin thịnh hành')}</span>
                        </div>
                    }
                    className="shadow-sm hover:shadow-md transition-all duration-300"
                >
                    {isTrendingLoading ? (
                        <Skeleton active avatar paragraph={{ rows: 2 }} />
                    ) : (
                        <List
                            itemLayout="horizontal"
                            dataSource={trendingNews}
                            renderItem={(item, index) => (
                                <motion.div
                                    initial={{ opacity: 0, x: -20 }}
                                    animate={{ opacity: 1, x: 0 }}
                                    transition={{ delay: index * 0.1 }}
                                >
                                    <List.Item className="hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg p-2 transition-colors cursor-pointer">
                                        <List.Item.Meta
                                            avatar={
                                                <div className="relative">
                                                    <Avatar
                                                        src={item.thumbnail}
                                                        size={40}
                                                        className="border-2 border-gray-200"
                                                    />
                                                    <div className="absolute -top-1 -right-1 text-xs">
                                                        {getTrendIcon(item.trend)}
                                                    </div>
                                                </div>
                                            }
                                            title={
                                                <div className="space-y-1">
                                                    <Text
                                                        className="text-sm font-medium line-clamp-2 hover:text-blue-500 transition-colors"
                                                        title={item.title}
                                                    >
                                                        {item.title}
                                                    </Text>
                                                    <div className="flex items-center justify-between text-xs text-gray-500">
                                                        <div className="flex items-center space-x-1">
                                                            <EyeOutlined />
                                                            <span>{formatViews(item.views)}</span>
                                                        </div>
                                                        <div className="flex items-center space-x-1">
                                                            <ClockCircleOutlined />
                                                            <span>{dayjs(item.publishedAt).fromNow()}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            }
                                        />
                                    </List.Item>
                                </motion.div>
                            )}
                        />
                    )}
                </Card>
            </motion.div>

            {/* Top Categories */}
            <motion.div variants={cardVariants}>
                <Card
                    title={
                        <div className="flex items-center space-x-2">
                            <TrophyOutlined className="text-yellow-500" />
                            <span>{t('news.sidebar.topCategories', 'Chủ đề hàng đầu')}</span>
                        </div>
                    }
                    className="shadow-sm hover:shadow-md transition-all duration-300"
                >
                    {isCategoriesLoading ? (
                        <Skeleton active paragraph={{ rows: 4 }} />
                    ) : (
                        <div className="space-y-4">
                            <AnimatePresence>
                                {topCategories?.map((category, index) => (
                                    <motion.div
                                        key={category.name}
                                        initial={{ opacity: 0, scale: 0.9 }}
                                        animate={{ opacity: 1, scale: 1 }}
                                        exit={{ opacity: 0, scale: 0.9 }}
                                        transition={{ delay: index * 0.05 }}
                                        className="space-y-2"
                                    >
                                        <div className="flex items-center justify-between">
                                            <div className="flex items-center space-x-2">
                                                <Tag color={category.color} className="m-0">
                                                    {category.name}
                                                </Tag>
                                                <Text className="text-xs text-gray-500">
                                                    {category.count} bài
                                                </Text>
                                            </div>
                                            <Text className="text-xs font-medium">
                                                {category.percentage}%
                                            </Text>
                                        </div>
                                        <Progress
                                            percent={category.percentage}
                                            strokeColor={category.color}
                                            showInfo={false}
                                            size="small"
                                        />
                                    </motion.div>
                                ))}
                            </AnimatePresence>
                        </div>
                    )}
                </Card>
            </motion.div>
        </motion.div>
    );
};

export default NewsSidebar;
