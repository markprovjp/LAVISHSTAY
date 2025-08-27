// src/components/news/NewsCategoryTabs.tsx
import React, { useState } from 'react';
import { Tabs, Badge, Button, Spin, Alert } from 'antd';
import { motion, AnimatePresence } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { useNewsCategories } from '../../hooks/useNews';
import {
    HomeOutlined,
    CrownOutlined,
    GiftOutlined,
    CalendarOutlined,
    HeartOutlined,
    TeamOutlined,
    TrophyOutlined,
    StarOutlined
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
    const { data: categoriesResponse, isLoading, error } = useNewsCategories();

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
        };
        return iconMap[slug] || <CrownOutlined />;
    };

    // Color mapping cho các category
    const getColorForCategory = (index: number) => {
        const colors = ['blue', 'purple', 'red', 'green', 'pink', 'orange', 'gold', 'cyan'];
        return colors[index % colors.length];
    };

    const handleTabChange = (key: string) => {
        setSelectedTab(key);
        onCategoryChange?.(key);
    };

    if (isLoading) {
        return (
            <div className="flex justify-center py-8">
                <Spin size="large" />
            </div>
        );
    }

    if (error) {
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

    // Tạo danh sách categories với "Tất cả" ở đầu
    const allCategories = [
        {
            key: 'all',
            label: t('news.categories.all', 'Tất cả'),
            icon: <HomeOutlined />,
            count: categoriesResponse?.data.reduce((total, cat) => total + (cat.news_count || 0), 0) || 0,
            color: 'blue'
        },
        ...(categoriesResponse?.data.map((category, index) => ({
            key: category.id.toString(),
            label: category.name,
            icon: getIconForCategory(category.slug),
            count: category.news_count || 0,
            color: getColorForCategory(index + 1)
        })) || [])
    ];

    const tabItems = allCategories.map((category) => ({
        key: category.key,
        label: (
            <div
                className={`flex items-center space-x-2 px-3 py-2 rounded-lg transition-all duration-200
                ${selectedTab === category.key ? 'bg-blue-50 dark:bg-blue-900/30' : 'bg-transparent'}
                hover:bg-blue-50 dark:hover:bg-blue-900/30
                `}
                style={{ minWidth: 90, cursor: 'pointer' }}
            >
                <span className={`text-lg ${selectedTab === category.key ? `text-${category.color}-600` : `text-${category.color}-500`}`}>
                    {category.icon}
                </span>
                <span className={`font-medium ${selectedTab === category.key ? `text-${category.color}-700 dark:text-${category.color}-300` : 'text-gray-700 dark:text-gray-300'}`}>
                    {category.label}
                </span>
                <Badge
                    count={category.count}
                    size="small"
                    className={`${selectedTab === category.key ? 'opacity-100' : 'opacity-60'}`}
                />
            </div>
        ),
        children: null // Content will be handled by parent component
    }));

    return (
        <div className="space-y-6">
            {/* Category Header */}
            <motion.div
                initial={{ opacity: 0, x: -20 }}
                animate={{ opacity: 1, x: 0 }}
                transition={{ duration: 0.5 }}
                className="flex items-center justify-between"
            >
                <div className="flex items-center space-x-3">
                    <motion.div
                        animate={{ rotate: [0, 10, -10, 0] }}
                        transition={{ duration: 2, repeat: Infinity }}
                        className="w-2 h-2  rounded-full"
                    />
                    <h2 className="text-xl font-bold text-gray-900 dark:text-white">
                        {t('news.categories.title', 'Chuyên mục')}
                    </h2>
                </div>

                {/* Quick Actions */}
                <div className="flex items-center space-x-2">
                    <Button
                        type="text"
                        size="small"
                        className="text-gray-500 hover:text-blue-500"
                    >
                        {t('news.refresh', 'Làm mới')}
                    </Button>
                    <Button
                        type="text"
                        size="small"
                        className="text-gray-500 hover:text-blue-500"
                    >
                        {t('news.viewAll', 'Xem tất cả')}
                    </Button>
                </div>
            </motion.div>

            {/* Animated Tabs */}
            <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.6, delay: 0.1 }}
                className="relative"
            >
                <Tabs
                    activeKey={selectedTab}
                    onChange={handleTabChange}
                    type="card"
                    size="large"
                    className="news-category-tabs"
                    items={tabItems}
                    tabBarStyle={{
                        marginBottom: 0,
                        borderBottom: 'none',
                    }}
                    tabBarGutter={8}
                    animated={{ inkBar: true, tabPane: true }}
                />

                {/* Active Tab Indicator */}
                <AnimatePresence>
                    <motion.div
                        key={selectedTab}
                        initial={{ scaleX: 0 }}
                        animate={{ scaleX: 1 }}
                        exit={{ scaleX: 0 }}
                        className="absolute bottom-0 left-0 right-0 h-1  rounded-full"
                    />
                </AnimatePresence>
            </motion.div>

            {/* Category Stats */}
            <motion.div
                initial={{ opacity: 0, y: 10 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.4, delay: 0.3 }}
                className="flex items-center justify-center space-x-6 text-sm text-gray-500 dark:text-gray-400"
            >
                <div className="flex items-center space-x-2">
                    <div className="w-2 h-2 bg-blue-500 rounded-full" />
                    <span>{t('news.stats.total', 'Tổng số')}: {allCategories.find(c => c.key === selectedTab)?.count || 0}</span>
                </div>
                <div className="flex items-center space-x-2">
                    <div className="w-2 h-2 bg-green-500 rounded-full" />
                    <span>{t('news.stats.updated', 'Cập nhật hôm nay')}: 12</span>
                </div>
                <div className="flex items-center space-x-2">
                    <div className="w-2 h-2 bg-orange-500 rounded-full" />
                    <span>{t('news.stats.trending', 'Đang xu hướng')}: 5</span>
                </div>
            </motion.div>

            {/* Floating Action Elements */}
            <div className="absolute top-0 right-0 -mt-2 -mr-2 flex space-x-2">
                {[1, 2, 3].map((i) => (
                    <motion.div
                        key={i}
                        animate={{
                            y: [0, -10, 0],
                            opacity: [0.3, 1, 0.3]
                        }}
                        transition={{
                            duration: 2,
                            delay: i * 0.2,
                            repeat: Infinity,
                            ease: "easeInOut"
                        }}
                        className={`w-1 h-1 rounded-full ${i === 1 ? 'bg-blue-400' :
                            i === 2 ? 'bg-purple-400' : 'bg-pink-400'
                            }`}
                    />
                ))}
            </div>

            <style>{`
        .news-category-tabs .ant-tabs-tab {
          background: transparent !important;
          border: none !important;
          margin-bottom: 8px !important;
          padding: 0 !important;
          min-width: 90px;
        }
        .news-category-tabs .ant-tabs-tab-active {
          background: transparent !important;
          border: none !important;
        }
        .news-category-tabs .ant-tabs-content-holder {
          display: none;
        }
        .news-category-tabs .ant-tabs-ink-bar {
          display: none;
        }
      `}</style>
        </div>
    );
};

export default NewsCategoryTabs;
