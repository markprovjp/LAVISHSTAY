// src/components/news/NewsCategoryTabs.tsx
import React, { useState } from 'react';
import { Tabs, Badge, Alert } from 'antd';
import { motion } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { useNewsCategories } from '../../hooks/useNews';
import {
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
    onCategoryChange?: (categoryId: number | null) => void;
}

const NewsCategoryTabs: React.FC<CategoryTabsProps> = ({
    activeCategory,
    onCategoryChange
}) => {
    const { t } = useTranslation();
    const [selectedTab, setSelectedTab] = useState<string | undefined>(activeCategory);

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

    // Prepare categories data
    const categories = categoriesResponse?.data || [];

    // On categories load, set default selected to 'tips-tricks' if exists, else first category
    React.useEffect(() => {
        if (!categories || categories.length === 0) return;
        const tips = categories.find((c: any) => c.slug === 'tips-tricks' || c.name?.toLowerCase().includes('tips'));
        const defaultKey = tips ? (tips.id?.toString() || tips.slug) : (categories[0].id?.toString() || categories[0].slug);
        setSelectedTab(defaultKey);
        // Emit numeric id if possible
        const found = categories.find((c: any) => (c.slug === defaultKey || c.id?.toString() === defaultKey));
        const id = found ? found.id : null;
        onCategoryChange?.(id);
    }, [categories]);

    const handleTabChange = (key: string) => {
        setSelectedTab(key);
        const found = categories.find((c: any) => (c.slug === key || c.id?.toString() === key));
        const id = found ? found.id : null;
        onCategoryChange?.(id);
    };

    // Handle loading state
    if (categoriesLoading) {
        return (
            <div className="flex justify-center py-8 text-gray-500">Đang tải danh mục...</div>
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

    // Create tabs items (headers only). The actual list content is rendered by NewsList.
    const tabItems = categories.map((category: any) => ({
        key: (category.id?.toString() || category.slug || ''),
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
                        className="ml-2"
                    />
                )}
            </motion.div>
        )
    }));

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

// Note: category content is now rendered by `NewsList` via prop `categoryId`.

export default NewsCategoryTabs;
