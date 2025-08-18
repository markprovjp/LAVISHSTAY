// src/components/news/NewsMainHighlight.tsx
import React from 'react';
import { Card, Tag, Badge, Avatar, Spin, Empty, Alert } from 'antd';
import { motion } from 'framer-motion';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Pagination, Autoplay, EffectFade } from 'swiper/modules';
import { useTranslation } from 'react-i18next';
import { ClockCircleOutlined, EyeOutlined, UserOutlined } from '@ant-design/icons';
import { useFeaturedNews } from '../../hooks/useNews';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import 'dayjs/locale/vi';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';
import { normalizeNewsResponse } from '../../utils/normalizeNewsData';

dayjs.extend(relativeTime);
dayjs.locale('vi');

interface NewsMainHighlightProps {
    onNewsClick?: (slug: string) => void;
}

const NewsMainHighlight: React.FC<NewsMainHighlightProps> = ({
    onNewsClick
}) => {
    const { t } = useTranslation();

    // Lấy tin nổi bật từ API
    const { data: featuredNewsResponse, isLoading, error } = useFeaturedNews({
        per_page: 5,
        is_featured: 1
    });

    // Log toàn bộ response từ backend để debug
    React.useEffect(() => {
        console.log('Featured News Response:', featuredNewsResponse);
    }, [featuredNewsResponse]);

    // Handle loading state
    if (isLoading) {
        return (
            <div className="w-full h-[500px] flex items-center justify-center bg-gray-100 dark:bg-gray-800 rounded-lg">
                <Spin size="large" />
            </div>
        );
    }

    // Handle error state
    if (error) {
        return (
            <Alert
                message={t('news.error.loadFailed', 'Không thể tải tin nổi bật')}
                description={t('news.error.tryAgain', 'Vui lòng thử lại sau')}
                type="error"
                showIcon
                className="mb-4"
            />
        );
    }

    // Handle empty state
    if (!featuredNewsResponse?.data || featuredNewsResponse.data.length === 0) {
        return (
            <Empty
                image={Empty.PRESENTED_IMAGE_SIMPLE}
                description={t('news.empty.noFeatured', 'Chưa có tin tức nổi bật')}
                className="py-12"
            />
        );
    }

    // Normalize the response data to prevent iteration/spreading errors
    const normalizedResponse = normalizeNewsResponse(featuredNewsResponse);
    const featuredNews = normalizedResponse.data;

    return (
        <motion.div
            initial={{ opacity: 0, y: 50 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.8 }}
            className="relative"
        >
            <div className="mb-6 flex items-center justify-between">
                <div className="flex items-center space-x-3">
                    <motion.div
                        animate={{ rotate: 360 }}
                        transition={{ duration: 2, repeat: Infinity, ease: "linear" }}
                        className="w-2 h-2 bg-red-500 rounded-full"
                    />
                    <h2 className="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                        {t('news.highlights', 'Tin nổi bật')}
                    </h2>
                </div>
                <Badge
                    count="HOT"
                    className="bg-gradient-to-r from-red-500 to-pink-500"
                />
            </div>

            <Swiper
                modules={[Navigation, Pagination, Autoplay, EffectFade]}
                spaceBetween={30}
                slidesPerView={1}
                navigation
                pagination={{
                    dynamicBullets: true,
                    clickable: true
                }}
                autoplay={{
                    delay: 5000,
                    disableOnInteraction: false,
                }}
                effect="fade"
                fadeEffect={{ crossFade: true }}
                className="news-highlight-swiper h-[500px] md:h-[600px] rounded-2xl overflow-hidden"
            >
                {featuredNews.map((newsItem) => (
                    <SwiperSlide key={newsItem.id}>
                        <motion.div
                            whileHover={{ scale: 1.02 }}
                            transition={{ type: "spring", stiffness: 300 }}
                            className="relative h-full cursor-pointer"
                            onClick={() => onNewsClick?.(newsItem.slug)}
                        >
                            <Card
                                className="h-full border-0 shadow-2xl overflow-hidden bg-gradient-to-b from-transparent to-black/50"
                                cover={
                                    <div className="relative h-full">
                                        <img
                                            src={newsItem.featured_image || 'https://via.placeholder.com/800x600'}
                                            alt={newsItem.title}
                                            className="w-full h-full object-cover transition-transform duration-700 hover:scale-110"
                                        />
                                        <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent" />

                                        {/* Category Badge */}
                                        <div className="absolute top-4 left-4">
                                            <Tag
                                                className="px-3 py-1 bg-red-500 text-white border-red-500 rounded-full font-medium"
                                            >
                                                {newsItem.category?.name || 'Tin tức'}
                                            </Tag>
                                        </div>

                                        {/* Content overlay */}
                                        <div className="absolute bottom-0 left-0 right-0 p-6 md:p-8 text-white">
                                            <h3 className="text-xl md:text-3xl font-bold mb-3 leading-tight line-clamp-2">
                                                {newsItem.title}
                                            </h3>

                                            <p className="text-gray-200 text-sm md:text-base mb-4 line-clamp-2">
                                                {newsItem.summary}
                                            </p>

                                            <div className="flex items-center justify-between">
                                                <div className="flex items-center space-x-4 text-xs md:text-sm">
                                                    <div className="flex items-center space-x-2">
                                                        <Avatar
                                                            size="small"
                                                            icon={<UserOutlined />}
                                                            src={newsItem.authorAvatar}
                                                        />
                                                        <span>{newsItem.authorName}</span>
                                                    </div>

                                                    <div className="flex items-center space-x-1">
                                                        <ClockCircleOutlined />
                                                        <span>{dayjs(newsItem.published_at).fromNow()}</span>
                                                    </div>

                                                    <div className="flex items-center space-x-1">
                                                        <EyeOutlined />
                                                        <span>{newsItem.views.toLocaleString()}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            {/* Tags */}
                                            <div className="flex flex-wrap gap-2 mt-4">
                                                {newsItem.formattedTags?.slice(0, 3).map((tag, index) => (
                                                    <Tag
                                                        key={index}
                                                        className="text-xs px-2 py-1 bg-white/20 border-white/30 text-white backdrop-blur-sm"
                                                    >
                                                        #{tag}
                                                    </Tag>
                                                ))}
                                            </div>
                                        </div>
                                    </div>
                                }
                            />
                        </motion.div>
                    </SwiperSlide>
                ))}
            </Swiper>
        </motion.div>
    );
};

export default NewsMainHighlight;
