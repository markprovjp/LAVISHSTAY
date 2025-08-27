// src/components/news/NewsCategoryTabs.tsx
import React, { useState } from 'react';
import { Tabs, Badge, Button, Spin, Alert, Empty } from 'antd';
import { motion, AnimatePresence } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { useNewsCategories, useNewsByCategory, useNewsList } from '../../hooks/useNews';
import {
    HomeOutlined,
    CrownOutlined,
    GiftOutlined,
    CalendarOutlined,
    HeartOutlined,
    TeamOutlined,
    TrophyOutlined,
    StarOutlined,
    AppstoreOutlined
} from '@ant-design/icons';

interface CategoryTabsProps {
    activeCategory?: string;
    onCategoryChange?: (category: string) => void;
}

const NewsCategoryTabs: React.FC<CategoryTabsProps> = ({
    activeCategory = 'all',
    onCategoryChange
}) => {
    const { t } = useTranslation();
    const [selectedTab, setSelectedTab] = useState(activeCategory);

    // Lấy danh mục từ API
    const { data: categoriesResponse, isLoading: categoriesLoading, error: categoriesError } = useNewsCategories();

    // Dev-only: Log response để debug
    React.useEffect(() => {
        if (process.env.NODE_ENV !== 'production') {
            console.debug('NewsCategoryTabs - Categories Response:', categoriesResponse);
        }
    }, [categoriesResponse]);

    // Icon mapping cho các category
    const getIconForCategory = (slug: string) => {
        const iconMap: Record<string, React.ReactNode> = {
            'khach-san': <CrownOutlined />,
            'hotel': <CrownOutlined />,
            'uu-dai': <GiftOutlined />,
            'offers': <GiftOutlined />,
            'su-kien': <CalendarOutlined />,
            'events': <CalendarOutlined />,
            'lifestyle': <HeartOutlined />,
            'gia-dinh': <TeamOutlined />,
            'family': <TeamOutlined />,
            'giai-thuong': <TrophyOutlined />,
            'awards': <TrophyOutlined />,
            'noi-bat': <StarOutlined />,
            'featured': <StarOutlined />,
            'tin-tuc': <AppstoreOutlined />,
            'news': <AppstoreOutlined />,
        };
        return iconMap[slug] || <AppstoreOutlined />;
    };

    const handleTabChange = (key: string) => {
        setSelectedTab(key);
        onCategoryChange?.(key);
    };

    // Handle loading state
    if (categoriesLoading) {
        return (
            <div className="flex justify-center py-8">
                <Spin size="large" />
            </div>
        );
    }

    // Handle error state
    if (categoriesError) {
        return (
            <Alert
                message={t('news.error.loadCategories', 'Không thể tải danh mục')}
                description={t('news.error.tryAgain', 'Vui lòng thử lại sau')}
                type="error"
                showIcon
                className="mb-4"
            />
        );
    }

    // Prepare categories data
    const categories = categoriesResponse?.data || [];

    // Create tabs items
    const tabItems = [
        {
            key: 'all',
            label: (
                <motion.div
                    whileHover={{ scale: 1.05 }}
                    className="flex items-center space-x-2"
                >
                    <HomeOutlined />
                    <span>{t('news.categories.all', 'Tất cả')}</span>
                    <Badge
                        count={categories.reduce((sum: number, cat: any) => sum + (cat.news_count || 0), 0)}
                        size="small"
                        className="ml-2"
                    />
                </motion.div>
            ),
            children: <CategoryContent categoryId={null} />
        },
        ...categories.map((category: any) => ({
            key: category.slug || category.id.toString(),
            label: (
                <motion.div
                    whileHover={{ scale: 1.05 }}
                    className="flex items-center space-x-2"
                >
                    {getIconForCategory(category.slug)}
                    <span>{category.name}</span>
                    {category.news_count > 0 && (
                        <Badge
                            count={category.news_count}
                            size="small"
                            className="ml-2"
                        />
                    )}
                </motion.div>
            ),
            children: <CategoryContent categoryId={category.id} />
        }))
    ];

    return (
        <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
            className="w-full"
        >
            <div className="mb-4">
                <h3 className="text-lg font-semibold text-gray-900 dark:text-white">
                    {t('news.categories.title', 'Chuyên mục tin tức')}
                </h3>
            </div>

            <Tabs
                activeKey={selectedTab}
                onChange={handleTabChange}
                type="card"
                size="large"
                className="custom-news-tabs"
                items={tabItems}
                animated={{ inkBar: true, tabPane: true }}
            />
        </motion.div>
    );
};

// Component để hiển thị nội dung theo category
const CategoryContent: React.FC<{ categoryId: number | null }> = ({ categoryId }) => {
    const { t } = useTranslation();

    // Hook để lấy tin theo category hoặc tất cả tin
    const { data, isLoading, error } = categoryId
        ? useNewsByCategory(categoryId, { per_page: 6 })
        : useNewsList({ per_page: 6 });

    if (isLoading) {
        return (
            <div className="flex justify-center py-12">
                <Spin size="large" />
            </div>
        );
    }

    if (error) {
        return (
            <Alert
                message={t('news.error.loadNews', 'Không thể tải tin tức')}
                description={t('news.error.tryAgain', 'Vui lòng thử lại sau')}
                type="error"
                showIcon
            />
        );
    }

    if (!data?.data || data.data.length === 0) {
        return (
            <Empty
                image={Empty.PRESENTED_IMAGE_SIMPLE}
                description={t('news.empty.noNewsInCategory', 'Chưa có tin tức trong chuyên mục này')}
                className="py-12"
            />
        );
    }

    return (
        <AnimatePresence mode="wait">
            <motion.div
                key={categoryId || 'all'}
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                exit={{ opacity: 0, y: -20 }}
                transition={{ duration: 0.4 }}
                className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
            >
                {data.data.map((newsItem: any, index: number) => (
                    <motion.div
                        key={newsItem.id}
                        initial={{ opacity: 0, scale: 0.9 }}
                        animate={{ opacity: 1, scale: 1 }}
                        transition={{ delay: index * 0.1, duration: 0.3 }}
                        className="bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md transition-shadow border"
                    >
                        <div className="p-4">
                            <h4 className="font-semibold text-gray-900 dark:text-white line-clamp-2 mb-2">
                                {newsItem.title}
                            </h4>
                            <p className="text-gray-600 dark:text-gray-300 text-sm line-clamp-2">
                                {newsItem.summary || newsItem.meta_description || 'Không có mô tả'}
                            </p>
                            <div className="mt-3 flex justify-between items-center">
                                <span className="text-xs text-gray-500">
                                    {newsItem.views || 0} lượt xem
                                </span>
                                <Button
                                    type="link"
                                    size="small"
                                    href={`/news/${newsItem.slug}`}
                                >
                                    {t('news.readMore', 'Xem chi tiết')}
                                </Button>
                            </div>
                        </div>
                    </motion.div>
                ))}
            </motion.div>
        </AnimatePresence>
    );
};

export default NewsCategoryTabs;
