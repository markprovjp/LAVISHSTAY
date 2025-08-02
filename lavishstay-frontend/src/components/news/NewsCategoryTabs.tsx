// src/components/news/NewsCategoryTabs.tsx
import React, { useState } from 'react';
import { Tabs, Badge, Button, Tag } from 'antd';
import { motion, AnimatePresence } from 'framer-motion';
import { useTranslation } from 'react-i18next';
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

interface Category {
    key: string;
    label: string;
    icon: React.ReactNode;
    count?: number;
    color: string;
}

const NewsCategoryTabs: React.FC<CategoryTabsProps> = ({
    activeCategory = 'all',
    onCategoryChange
}) => {
    const { t } = useTranslation();
    const [selectedTab, setSelectedTab] = useState(activeCategory);

    const categories: Category[] = [
        {
            key: 'all',
            label: t('news.categories.all', 'Tất cả'),
            icon: <HomeOutlined />,
            count: 156,
            color: 'blue'
        },
        {
            key: 'hotel',
            label: t('news.categories.hotel', 'Khách sạn'),
            icon: <CrownOutlined />,
            count: 45,
            color: 'purple'
        },
        {
            key: 'offers',
            label: t('news.categories.offers', 'Ưu đãi'),
            icon: <GiftOutlined />,
            count: 23,
            color: 'red'
        },
        {
            key: 'events',
            label: t('news.categories.events', 'Sự kiện'),
            icon: <CalendarOutlined />,
            count: 18,
            color: 'green'
        },
        {
            key: 'lifestyle',
            label: t('news.categories.lifestyle', 'Lifestyle'),
            icon: <HeartOutlined />,
            count: 32,
            color: 'pink'
        },
        {
            key: 'family',
            label: t('news.categories.family', 'Gia đình'),
            icon: <TeamOutlined />,
            count: 15,
            color: 'orange'
        },
        {
            key: 'awards',
            label: t('news.categories.awards', 'Giải thưởng'),
            icon: <TrophyOutlined />,
            count: 12,
            color: 'gold'
        },
        {
            key: 'featured',
            label: t('news.categories.featured', 'Nổi bật'),
            icon: <StarOutlined />,
            count: 28,
            color: 'cyan'
        }
    ];

    const handleTabChange = (key: string) => {
        setSelectedTab(key);
        onCategoryChange?.(key);
    };

    const tabItems = categories.map((category) => ({
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
                    <span>{t('news.stats.total', 'Tổng số')}: {categories.find(c => c.key === selectedTab)?.count || 0}</span>
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
