// src/components/news/NewsMainHighlight.tsx
import React from 'react';
import { Card, Tag, Badge, Avatar } from 'antd';
import { motion } from 'framer-motion';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Navigation, Pagination, Autoplay, EffectFade } from 'swiper/modules';
import { useTranslation } from 'react-i18next';
import { ClockCircleOutlined, EyeOutlined, UserOutlined } from '@ant-design/icons';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import 'dayjs/locale/vi';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';

dayjs.extend(relativeTime);
dayjs.locale('vi');

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
    isHighlight: boolean;
    tags: string[];
}

interface NewsMainHighlightProps {
    news?: NewsItem[];
    onNewsClick?: (news: NewsItem) => void;
}

const NewsMainHighlight: React.FC<NewsMainHighlightProps> = ({
    news,
    onNewsClick
}) => {
    const { t } = useTranslation();

    // Mock data nếu không có props
    const mockNews: NewsItem[] = [
        {
            id: '1',
            title: 'LavishStay khai trương khách sạn 5 sao mới tại trung tâm thành phố',
            summary: 'Với thiết kế hiện đại và dịch vụ đẳng cấp quốc tế, LavishStay hứa hẹn mang đến trải nghiệm nghỉ dưỡng tuyệt vời cho du khách.',
            imageUrl: 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800&h=600&fit=crop',
            category: 'Khách sạn',
            publishedAt: new Date('2024-01-15'),
            author: { name: 'Nguyễn Văn A', avatar: '' },
            views: 15420,
            isHighlight: true,
            tags: ['khách sạn', 'khai trương', '5 sao']
        },
        {
            id: '2',
            title: 'Ưu đãi đặc biệt mùa hè 2024 - Giảm đến 50% cho kỳ nghỉ tuyệt vời',
            summary: 'Chương trình ưu đãi hấp dẫn dành cho các gia đình và cặp đôi trong mùa hè này với nhiều gói dịch vụ đa dạng.',
            imageUrl: 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800&h=600&fit=crop',
            category: 'Ưu đãi',
            publishedAt: new Date('2024-01-14'),
            author: { name: 'Trần Thị B', avatar: '' },
            views: 8965,
            isHighlight: true,
            tags: ['ưu đãi', 'mùa hè', 'giảm giá']
        },
        {
            id: '3',
            title: 'Trải nghiệm ẩm thực đỉnh cao tại nhà hàng LavishDine',
            summary: 'Thực đơn mới với hơn 100 món ăn từ khắp nơi trên thế giới, được chế biến bởi đội ngũ đầu bếp chuyên nghiệp.',
            imageUrl: 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=800&h=600&fit=crop',
            category: 'Ẩm thực',
            publishedAt: new Date('2024-01-13'),
            author: { name: 'Lê Văn C', avatar: '' },
            views: 6543,
            isHighlight: true,
            tags: ['ẩm thực', 'nhà hàng', 'thực đơn mới']
        }
    ];

    const newsData = news || mockNews;

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
                {newsData.map((item, index) => (
                    <SwiperSlide key={item.id}>
                        <motion.div
                            initial={{ scale: 1.1 }}
                            animate={{ scale: 1 }}
                            transition={{ duration: 0.8 }}
                            className="relative h-full cursor-pointer group"
                            onClick={() => onNewsClick?.(item)}
                        >
                            {/* Background Image */}
                            <div
                                className="absolute inset-0 bg-cover bg-center bg-no-repeat"
                                style={{ backgroundImage: `url(${item.imageUrl})` }}
                            >
                                <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent" />
                            </div>

                            {/* Content Overlay */}
                            <div className="relative h-full flex flex-col justify-end p-6 md:p-8 text-white">
                                {/* Category & Tags */}
                                <div className="mb-4 flex flex-wrap gap-2">
                                    <Tag
                                        color="blue"
                                        className="px-3 py-1 text-sm font-medium rounded-full"
                                    >
                                        {item.category}
                                    </Tag>
                                    {item.tags.map((tag) => (
                                        <Tag
                                            key={tag}
                                            className="px-2 py-1 text-xs bg-white/20 text-white border-white/30 rounded-full"
                                        >
                                            #{tag}
                                        </Tag>
                                    ))}
                                </div>

                                {/* Title */}
                                <motion.h3
                                    initial={{ opacity: 0, y: 20 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ delay: index * 0.1 + 0.3 }}
                                    className="text-2xl md:text-4xl font-bold mb-4 leading-tight group-hover:text-blue-300 transition-colors"
                                >
                                    {item.title}
                                </motion.h3>

                                {/* Summary */}
                                <motion.p
                                    initial={{ opacity: 0, y: 20 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ delay: index * 0.1 + 0.4 }}
                                    className="text-gray-200 text-lg mb-6 line-clamp-3 leading-relaxed"
                                >
                                    {item.summary}
                                </motion.p>

                                {/* Meta Info */}
                                <motion.div
                                    initial={{ opacity: 0, y: 20 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ delay: index * 0.1 + 0.5 }}
                                    className="flex items-center justify-between"
                                >
                                    <div className="flex items-center space-x-4">
                                        <div className="flex items-center space-x-2">
                                            <Avatar
                                                size="small"
                                                src={item.author.avatar}
                                                icon={<UserOutlined />}
                                                className="border-2 border-white/30"
                                            />
                                            <span className="text-sm text-gray-200">{item.author.name}</span>
                                        </div>

                                        <div className="flex items-center space-x-1 text-gray-300">
                                            <ClockCircleOutlined className="text-xs" />
                                            <span className="text-sm">{dayjs(item.publishedAt).fromNow()}</span>
                                        </div>
                                    </div>

                                    <div className="flex items-center space-x-1 text-gray-300">
                                        <EyeOutlined />
                                        <span className="text-sm">{item.views.toLocaleString()}</span>
                                    </div>
                                </motion.div>
                            </div>

                            {/* Hover Effect */}
                            <div className="absolute inset-0 bg-blue-600/0 group-hover:bg-blue-600/10 transition-all duration-300" />
                        </motion.div>
                    </SwiperSlide>
                ))}
            </Swiper>

            {/* Animated decorations */}
            <motion.div
                animate={{
                    y: [0, -10, 0],
                    rotate: [0, 5, -5, 0]
                }}
                transition={{
                    duration: 3,
                    repeat: Infinity,
                    ease: "easeInOut"
                }}
                className="absolute -top-4 -right-4 w-8 h-8 bg-yellow-400 rounded-full opacity-80 z-10"
            />

            <motion.div
                animate={{
                    y: [0, 10, 0],
                    rotate: [0, -5, 5, 0]
                }}
                transition={{
                    duration: 4,
                    repeat: Infinity,
                    ease: "easeInOut",
                    delay: 1
                }}
                className="absolute -bottom-4 -left-4 w-6 h-6 bg-pink-400 rounded-full opacity-60 z-10"
            />
        </motion.div>
    );
};

export default NewsMainHighlight;
