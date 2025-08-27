// src/components/news/NewsSidebar.tsx
import React, { useState, useEffect } from 'react';
import { Card, List, Tag, Divider, Progress, Statistic, Empty, Spin, Button } from 'antd';
import { motion } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { Link } from 'react-router-dom';
import { useTrendingNews, useNewsCategories, useNewsList } from '../../hooks/useNews';
import {
    FireOutlined,
    EyeOutlined,

    CloudOutlined,
    RiseOutlined,
    TrophyOutlined,
    CalendarOutlined,

    ArrowRightOutlined,
    ThunderboltOutlined,
    StarOutlined,
    BarChartOutlined
} from '@ant-design/icons';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import 'dayjs/locale/vi';

dayjs.extend(relativeTime);
dayjs.locale('vi');

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

    // Lấy tổng số tin để thống kê
    const { data: allNewsResponse } = useNewsList({ per_page: 1 });

    // Dev-only: Log responses để debug
    React.useEffect(() => {
        if (process.env.NODE_ENV !== 'production') {
            console.debug('NewsSidebar - Trending Response:', trendingResponse);
            console.debug('NewsSidebar - Categories Response:', categoriesResponse);
            console.debug('NewsSidebar - All News Response:', allNewsResponse);
        }
    }, [trendingResponse, categoriesResponse, allNewsResponse]);

    // Mock weather data (có thể thay bằng API thật). User requested Thanh Hóa.
    const [weather] = useState<WeatherData>({
        location: 'Thanh Hóa',
        temperature: 28,
        condition: 'Nắng',
        humidity: 65,
        windSpeed: 12,
        icon: '☀️'
    });

    // Calculate statistics từ dữ liệu API
    // Compute statistics, fallback if backend doesn't provide counts
    const computedTotalArticles = allNewsResponse?.total || (categoriesResponse?.data ? categoriesResponse.data.reduce((s: number, c: any) => s + (c.news_count || 0), 0) : 0);
    const statistics = {
        totalArticles: computedTotalArticles,
        totalCategories: categoriesResponse?.data?.length || 0,
        totalViews: trendingResponse?.data?.reduce((sum: number, item: any) => sum + (item.views || 0), 0) || 0,
        todayArticles: Math.floor(Math.random() * 5) + 1 // Mock data for today
    };

    return (
        <div className="space-y-6">
            {/* Quick Statistics */}
            <motion.div
                initial={{ opacity: 0, x: 20 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ duration: 0.6 }}
            >
                <Card
                    title={
                        <div className="flex items-center space-x-2">
                            <BarChartOutlined className="text-blue-500" />
                            <span>{t('news.sidebar.statistics', 'Thống kê nhanh')}</span>
                        </div>
                    }
                    className="shadow-sm"
                >
                    <div className="grid grid-cols-2 gap-4">
                        <Statistic
                            title={t('news.sidebar.totalArticles', 'Tổng bài viết')}
                            value={statistics.totalArticles}
                            prefix={<RiseOutlined />}
                            valueStyle={{ color: '#3f8600' }}
                        />
                        <Statistic
                            title={t('news.sidebar.totalViews', 'Lượt xem')}
                            value={statistics.totalViews}
                            prefix={<EyeOutlined />}
                            valueStyle={{ color: '#1890ff' }}
                        />
                        <Statistic
                            title={t('news.sidebar.categories', 'Chuyên mục')}
                            value={statistics.totalCategories}
                            prefix={<TrophyOutlined />}
                            valueStyle={{ color: '#722ed1' }}
                        />
                        <Statistic
                            title={t('news.sidebar.todayArticles', 'Hôm nay')}
                            value={statistics.todayArticles}
                            prefix={<CalendarOutlined />}
                            valueStyle={{ color: '#fa541c' }}
                        />
                    </div>
                </Card>
            </motion.div>

            {/* Trending News */}
            <motion.div
                initial={{ opacity: 0, x: 20 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ duration: 0.6, delay: 0.1 }}
            >
                <Card
                    title={
                        <div className="flex items-center space-x-2">
                            <FireOutlined className="text-red-500" />
                            <span>{t('news.sidebar.trending', 'Tin thịnh hành')}</span>
                        </div>
                    }
                    className="shadow-sm"
                >
                    {isTrendingLoading ? (
                        <div className="flex justify-center py-8">
                            <Spin />
                        </div>
                    ) : trendingResponse?.data && trendingResponse.data.length > 0 ? (
                        <List
                            dataSource={trendingResponse.data}
                            renderItem={(item: any, index: number) => (
                                <List.Item className="px-0">
                                    <div className="flex space-x-3 w-full">
                                        <div className="flex-shrink-0">
                                            <div className={`
                                                w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold text-white
                                                ${index === 0 ? 'bg-yellow-500' : index === 1 ? 'bg-gray-400' : index === 2 ? 'bg-orange-500' : 'bg-gray-300'}
                                            `}>
                                                {index + 1}
                                            </div>
                                        </div>

                                        <div className="flex-grow min-w-0">
                                            <Link
                                                to={`/news/${item.slug}`}
                                                className="block hover:text-blue-600 transition-colors"
                                            >
                                                <h4 className="text-sm font-medium line-clamp-2 mb-1">
                                                    {item.title}
                                                </h4>
                                            </Link>

                                            <div className="flex items-center justify-between text-xs text-gray-500">
                                                <div className="flex items-center space-x-1">
                                                    <EyeOutlined />
                                                    <span>{(item.views || 0).toLocaleString()}</span>
                                                </div>
                                                <span>{dayjs(item.published_at || item.publish_date).fromNow()}</span>
                                            </div>
                                        </div>
                                    </div>
                                </List.Item>
                            )}
                        />
                    ) : (
                        <Empty
                            image={Empty.PRESENTED_IMAGE_SIMPLE}
                            description={t('news.sidebar.noTrending', 'Chưa có tin thịnh hành')}
                        />
                    )}
                </Card>
            </motion.div>

            {/* Categories Overview */}
            <motion.div
                initial={{ opacity: 0, x: 20 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ duration: 0.6, delay: 0.2 }}
            >
                <Card
                    title={
                        <div className="flex items-center space-x-2">
                            <TrophyOutlined className="text-purple-500" />
                            <span>{t('news.sidebar.topCategories', 'Chuyên mục hàng đầu')}</span>
                        </div>
                    }
                    className="shadow-sm"
                >
                    {categoriesResponse?.data && categoriesResponse.data.length > 0 ? (
                        <div className="space-y-3">
                            {categoriesResponse.data.slice(0, 5).map((category: any, index: number) => (
                                <div key={category.id} className="flex items-center justify-between">
                                    <div className="flex items-center space-x-2">
                                        <Tag color={['blue', 'green', 'orange', 'purple', 'red'][index % 5]}>
                                            {category.name}
                                        </Tag>
                                    </div>
                                    <div className="flex items-center space-x-2">
                                        <span className="text-xs text-gray-500">
                                            {category.news_count || 0} bài
                                        </span>
                                        <Progress
                                            percent={Math.min((category.news_count || 0) / Math.max(statistics.totalArticles, 1) * 100, 100)}
                                            size="small"
                                            showInfo={false}
                                            className="w-16"
                                        />
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : (
                        <Empty
                            image={Empty.PRESENTED_IMAGE_SIMPLE}
                            description={t('news.sidebar.noCategories', 'Chưa có chuyên mục')}
                        />
                    )}
                </Card>
            </motion.div>

            {/* Current Time & Weather */}
            <motion.div
                initial={{ opacity: 0, x: 20 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ duration: 0.6, delay: 0.3 }}
            >
                <Card
                    title={
                        <div className="flex items-center space-x-2">
                            <CloudOutlined className="text-cyan-500" />
                            <span>{t('news.sidebar.currentInfo', 'Thông tin hiện tại')}</span>
                        </div>
                    }
                    className="shadow-sm"
                >
                    <div className="space-y-4">
                        {/* Current Time */}
                        <div className="text-center">
                            <div className="text-2xl font-bold text-blue-600">
                                {currentTime.format('HH:mm:ss')}
                            </div>
                            <div className="text-sm text-gray-500">
                                {currentTime.format('dddd, DD/MM/YYYY')}
                            </div>
                        </div>

                        <Divider />

                        {/* Weather */}
                        <div className="text-center">
                            <div className="text-3xl mb-2">{weather.icon}</div>
                            <div className="text-lg font-semibold">{weather.temperature}°C</div>
                            <div className="text-sm text-gray-600">{weather.location}</div>
                            <div className="text-xs text-gray-500">{weather.condition}</div>

                            <div className="mt-2 flex justify-between text-xs text-gray-500">
                                <span>Độ ẩm: {weather.humidity}%</span>
                                <span>Gió: {weather.windSpeed}km/h</span>
                            </div>
                        </div>
                    </div>
                </Card>
            </motion.div>

            {/* Quick Actions */}
            <motion.div
                initial={{ opacity: 0, x: 20 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ duration: 0.6, delay: 0.4 }}
            >
                <Card
                    title={
                        <div className="flex items-center space-x-2">
                            <ThunderboltOutlined className="text-yellow-500" />
                            <span>{t('news.sidebar.quickActions', 'Liên kết nhanh')}</span>
                        </div>
                    }
                    className="shadow-sm"
                >
                    <div className="space-y-2">
                        <Button
                            block
                            icon={<StarOutlined />}
                            className="text-left"
                        >
                            {t('news.sidebar.featured', 'Tin nổi bật')}
                        </Button>

                        <Button
                            block
                            icon={<FireOutlined />}
                            className="text-left"
                        >
                            {t('news.sidebar.trending', 'Tin thịnh hành')}
                        </Button>

                        <Button
                            block
                            icon={<CalendarOutlined />}
                            className="text-left"
                        >
                            {t('news.sidebar.latest', 'Tin mới nhất')}
                        </Button>

                        <Button
                            block
                            icon={<ArrowRightOutlined />}
                            type="primary"
                            className="text-left"
                        >
                            {t('news.sidebar.viewAll', 'Xem tất cả')}
                        </Button>
                    </div>
                </Card>
            </motion.div>
        </div>
    );
};

export default NewsSidebar;
