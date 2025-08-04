// src/components/news/NewsList.tsx
import React, { useState, useMemo } from 'react';
import { Row, Col, Pagination, Spin, Empty, Button, Select, Input } from 'antd';
import { motion, AnimatePresence } from 'framer-motion';
import { useQuery, useInfiniteQuery } from '@tanstack/react-query';
import { useTranslation } from 'react-i18next';
import { FilterOutlined, SortAscendingOutlined, ReloadOutlined } from '@ant-design/icons';
import NewsCard from './NewsCard';

const { Search } = Input;
const { Option } = Select;

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

interface NewsListProps {
    category?: string;
    searchQuery?: string;
    onNewsClick?: (news: NewsItem) => void;
}

// Mock API function
const fetchNewsList = async ({
    pageParam = 1,
    category = 'all',
    sortBy = 'publishedAt',
    searchQuery = ''
}): Promise<{
    data: NewsItem[];
    total: number;
    hasNextPage: boolean;
    nextPage?: number;
}> => {
    // Simulate API delay
    await new Promise(resolve => setTimeout(resolve, 800));

    const mockNews: NewsItem[] = [
        {
            id: '8',
            title: 'LavishStay được vinh danh "Khách sạn tốt nhất năm 2024"',
            summary: 'Giải thưởng danh giá từ Hiệp hội Du lịch Quốc tế khẳng định chất lượng dịch vụ xuất sắc của chúng tôi.',
            imageUrl: 'https://images.unsplash.com/photo-1551218808-94e220e084d2?w=400&h=250&fit=crop',
            category: 'Giải thưởng',
            publishedAt: new Date('2024-01-08'),
            author: { name: 'Ban biên tập' },
            views: 8765,
            tags: ['giải thưởng', 'vinh danh', '2024'],
            isBookmarked: false,
            isLiked: true,
            likesCount: 234
        },
        {
            id: '9',
            title: 'Workshop pha chế cocktail miễn phí cho khách hàng',
            summary: 'Học cách pha chế những ly cocktail tuyệt vời từ bartender chuyên nghiệp của chúng tôi.',
            imageUrl: 'https://images.unsplash.com/photo-1551538827-9c037cb4f32a?w=400&h=250&fit=crop',
            category: 'Sự kiện',
            publishedAt: new Date('2024-01-07'),
            author: { name: 'Nguyễn Mixer' },
            views: 3456,
            tags: ['workshop', 'cocktail', 'miễn phí'],
            isBookmarked: true,
            isLiked: false,
            likesCount: 89
        },
        {
            id: '10',
            title: 'Chương trình âm nhạc cuối tuần tại LavishLounge',
            summary: 'Thưởng thức những giai điệu tuyệt vời từ các nghệ sĩ tài năng trong không gian sang trọng.',
            imageUrl: 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=400&h=250&fit=crop',
            category: 'Giải trí',
            publishedAt: new Date('2024-01-06'),
            author: { name: 'Music Team' },
            views: 5678,
            tags: ['âm nhạc', 'cuối tuần', 'lounge'],
            isBookmarked: false,
            isLiked: true,
            likesCount: 156
        },
        {
            id: '11',
            title: 'Khóa học nấu ăn với đầu bếp Michelin Star',
            summary: 'Cơ hội hiếm có để học nấu ăn từ đầu bếp đạt sao Michelin ngay tại khách sạn.',
            imageUrl: 'https://images.unsplash.com/photo-1556909114-b7a93d48d766?w=400&h=250&fit=crop',
            category: 'Ẩm thực',
            publishedAt: new Date('2024-01-05'),
            author: { name: 'Chef Martin' },
            views: 9876,
            tags: ['nấu ăn', 'michelin', 'khóa học'],
            isBookmarked: true,
            isLiked: true,
            likesCount: 567
        },
        {
            id: '12',
            title: 'Gói nghỉ dưỡng kết hợp yoga và meditation',
            summary: 'Tìm lại sự cân bằng trong cuộc sống với chương trình yoga và thiền định chuyên nghiệp.',
            imageUrl: 'https://images.unsplash.com/photo-1506629905607-ea9a6a27a9eb?w=400&h=250&fit=crop',
            category: 'Wellness',
            publishedAt: new Date('2024-01-04'),
            author: { name: 'Yoga Master' },
            views: 4321,
            tags: ['yoga', 'meditation', 'wellness'],
            isBookmarked: false,
            isLiked: false,
            likesCount: 123
        },
        {
            id: '13',
            title: 'Triển lãm nghệ thuật "Vẻ đẹp Việt Nam" tại lobby',
            summary: 'Khám phá vẻ đẹp đất nước qua triển lãm tranh và tác phẩm nghệ thuật độc đáo.',
            imageUrl: 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=250&fit=crop',
            category: 'Văn hóa',
            publishedAt: new Date('2024-01-03'),
            author: { name: 'Art Curator' },
            views: 2345,
            tags: ['triển lãm', 'nghệ thuật', 'việt nam'],
            isBookmarked: false,
            isLiked: true,
            likesCount: 78
        }
    ];

    // Filter by category
    let filteredNews = category === 'all'
        ? mockNews
        : mockNews.filter(news => news.category.toLowerCase().includes(category.toLowerCase()));

    // Filter by search query
    if (searchQuery) {
        filteredNews = filteredNews.filter(news =>
            news.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
            news.summary.toLowerCase().includes(searchQuery.toLowerCase()) ||
            news.tags.some(tag => tag.toLowerCase().includes(searchQuery.toLowerCase()))
        );
    }

    // Sort news
    if (sortBy === 'publishedAt') {
        filteredNews.sort((a, b) => new Date(b.publishedAt).getTime() - new Date(a.publishedAt).getTime());
    } else if (sortBy === 'views') {
        filteredNews.sort((a, b) => b.views - a.views);
    } else if (sortBy === 'likes') {
        filteredNews.sort((a, b) => b.likesCount - a.likesCount);
    }

    const pageSize = 6;
    const startIndex = (pageParam - 1) * pageSize;
    const endIndex = startIndex + pageSize;
    const paginatedNews = filteredNews.slice(startIndex, endIndex);

    return {
        data: paginatedNews,
        total: filteredNews.length,
        hasNextPage: endIndex < filteredNews.length,
        nextPage: endIndex < filteredNews.length ? pageParam + 1 : undefined
    };
};

const NewsList: React.FC<NewsListProps> = ({
    category = 'all',
    searchQuery = '',
    onNewsClick
}) => {
    const { t } = useTranslation();
    const [currentPage, setCurrentPage] = useState(1);
    const [sortBy, setSortBy] = useState<'publishedAt' | 'views' | 'likes'>('publishedAt');
    const [localSearchQuery, setLocalSearchQuery] = useState(searchQuery);

    const {
        data,
        isLoading,
        error,
        refetch,
        isFetching
    } = useQuery({
        queryKey: ['newsList', category, sortBy, localSearchQuery, currentPage],
        queryFn: () => fetchNewsList({
            pageParam: currentPage,
            category,
            sortBy,
            searchQuery: localSearchQuery
        }),
        staleTime: 2 * 60 * 1000, // 2 minutes
    });

    const handlePageChange = (page: number) => {
        setCurrentPage(page);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const handleSortChange = (value: 'publishedAt' | 'views' | 'likes') => {
        setSortBy(value);
        setCurrentPage(1);
    };

    const handleSearch = (value: string) => {
        setLocalSearchQuery(value);
        setCurrentPage(1);
    };

    const handleRefresh = () => {
        refetch();
    };

    if (isLoading) {
        return (
            <div className="space-y-6">
                <div className="flex justify-center py-12">
                    <Spin size="large" />
                </div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="text-center py-12">
                <Empty
                    description={t('news.error', 'Có lỗi xảy ra khi tải tin tức')}
                    image={Empty.PRESENTED_IMAGE_SIMPLE}
                >
                    <Button type="primary" onClick={handleRefresh}>
                        {t('news.retry', 'Thử lại')}
                    </Button>
                </Empty>
            </div>
        );
    }

    return (
        <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.5 }}
            className="space-y-6"
        >
            {/* Header Controls */}
            <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <div className="flex items-center space-x-3">
                    <h3 className="text-lg font-semibold text-gray-900 dark:text-white">
                        {t('news.list.title', 'Danh sách tin tức')}
                    </h3>
                    {isFetching && (
                        <motion.div
                            animate={{ rotate: 360 }}
                            transition={{ duration: 1, repeat: Infinity, ease: "linear" }}
                        >
                            <ReloadOutlined className="text-blue-500" />
                        </motion.div>
                    )}
                </div>

                <div className="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full sm:w-auto">
                    {/* Search */}
                    <Search
                        placeholder={t('news.search.placeholder', 'Tìm kiếm tin tức...')}
                        allowClear
                        enterButton
                        size="middle"
                        className="w-full sm:w-64"
                        onSearch={handleSearch}
                        defaultValue={localSearchQuery}
                    />

                    {/* Sort */}
                    <Select
                        value={sortBy}
                        onChange={handleSortChange}
                        size="middle"
                        className="w-full sm:w-40"
                        suffixIcon={<SortAscendingOutlined />}
                    >
                        <Option value="publishedAt">{t('news.sort.latest', 'Mới nhất')}</Option>
                        <Option value="views">{t('news.sort.popular', 'Phổ biến')}</Option>
                        <Option value="likes">{t('news.sort.liked', 'Yêu thích')}</Option>
                    </Select>

                    {/* Refresh */}
                    <Button
                        type="text"
                        icon={<FilterOutlined />}
                        onClick={handleRefresh}
                        loading={isFetching}
                        className="w-full sm:w-auto"
                    >
                        {t('news.refresh', 'Làm mới')}
                    </Button>
                </div>
            </div>

            {/* News Grid */}
            <AnimatePresence mode="wait">
                {data?.data && data.data.length > 0 ? (
                    <motion.div
                        key={`${category}-${sortBy}-${currentPage}`}
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        exit={{ opacity: 0, y: -20 }}
                        transition={{ duration: 0.4 }}
                    >
                        <Row gutter={[24, 24]}>
                            {data.data.map((news, index) => (
                                <Col xs={24} sm={12} lg={8} key={news.id}>
                                    <motion.div
                                        initial={{ opacity: 0, y: 30 }}
                                        animate={{ opacity: 1, y: 0 }}
                                        transition={{
                                            duration: 0.5,
                                            delay: index * 0.1,
                                            ease: "easeOut"
                                        }}
                                        whileHover={{ y: -5 }}
                                        className="h-full"
                                    >
                                        <NewsCard
                                            news={news}
                                            onClick={() => onNewsClick?.(news)}
                                        />
                                    </motion.div>
                                </Col>
                            ))}
                        </Row>

                        {/* Pagination */}
                        {data.total > 6 && (
                            <motion.div
                                initial={{ opacity: 0 }}
                                animate={{ opacity: 1 }}
                                transition={{ delay: 0.3 }}
                                className="flex justify-center mt-8"
                            >
                                <Pagination
                                    current={currentPage}
                                    total={data.total}
                                    pageSize={6}
                                    onChange={handlePageChange}
                                    showSizeChanger={false}
                                    showQuickJumper
                                    showTotal={(total, range) =>
                                        `${range[0]}-${range[1]} ${t('news.pagination.of', 'trong')} ${total} ${t('news.pagination.items', 'tin tức')}`
                                    }
                                    className="custom-pagination"
                                />
                            </motion.div>
                        )}
                    </motion.div>
                ) : (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        className="py-12"
                    >
                        <Empty
                            description={t('news.empty', 'Không có tin tức nào')}
                            image={Empty.PRESENTED_IMAGE_SIMPLE}
                        />
                    </motion.div>
                )}
            </AnimatePresence>

            {/* Stats */}
            <motion.div
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                transition={{ delay: 0.5 }}
                className="text-center text-sm text-gray-500 dark:text-gray-400"
            >
                {data?.total && (
                    <p>
                        {t('news.stats.showing', 'Hiển thị')} {data.data.length} {t('news.stats.of', 'trong')} {data.total} {t('news.stats.articles', 'bài viết')}
                    </p>
                )}
            </motion.div>
        </motion.div>
    );
};

export default NewsList;
