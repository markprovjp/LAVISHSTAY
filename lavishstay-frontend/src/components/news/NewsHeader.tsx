// src/components/news/NewsHeader.tsx
import React, { useState } from 'react';
import { Layout, Input, Switch, Drawer, Menu, Badge, Avatar, Dropdown } from 'antd';
import { motion } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import {
    SearchOutlined,
    MenuOutlined,
    BellOutlined,
    UserOutlined,
    SettingOutlined,
    MoonOutlined,
    SunOutlined,
    GlobalOutlined
} from '@ant-design/icons';
import { FiSearch, FiMenu, FiBell, FiUser, FiSettings, FiMoon, FiSun, FiGlobe } from 'react-icons/fi';

const { Header } = Layout;
const { Search } = Input;

interface NewsHeaderProps {
    onSearch?: (value: string) => void;
    onToggleTheme?: () => void;
    darkMode?: boolean;
}

const NewsHeader: React.FC<NewsHeaderProps> = ({
    onSearch,
    onToggleTheme,
    darkMode = false
}) => {
    const { t, i18n } = useTranslation();
    const [drawerVisible, setDrawerVisible] = useState(false);
    const [searchVisible, setSearchVisible] = useState(false);

    const menuItems = [
        { key: 'home', label: t('nav.home', 'Trang chủ') },
        { key: 'hotel', label: t('nav.hotel', 'Khách sạn') },
        { key: 'news', label: t('nav.news', 'Tin tức') },
        { key: 'offers', label: t('nav.offers', 'Ưu đãi') },
        { key: 'contact', label: t('nav.contact', 'Liên hệ') },
    ];

    const languageMenu = {
        items: [
            { key: 'vi', label: 'Tiếng Việt', onClick: () => i18n.changeLanguage('vi') },
            { key: 'en', label: 'English', onClick: () => i18n.changeLanguage('en') },
        ]
    };

    const userMenu = {
        items: [
            { key: 'profile', label: t('user.profile', 'Hồ sơ'), icon: <UserOutlined /> },
            { key: 'settings', label: t('user.settings', 'Cài đặt'), icon: <SettingOutlined /> },
            { key: 'logout', label: t('user.logout', 'Đăng xuất') },
        ]
    };

    return (
        <Header className="fixed top-0 left-0 right-0 z-50 bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 px-0">
            <motion.div
                initial={{ y: -100 }}
                animate={{ y: 0 }}
                transition={{ duration: 0.5 }}
                className="container mx-auto px-4 h-full flex items-center justify-between"
            >
                {/* Logo */}
                <motion.div
                    whileHover={{ scale: 1.05 }}
                    className="flex items-center space-x-3"
                >
                    <div className="w-10 h-10 bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg flex items-center justify-center">
                        <span className="text-white font-bold text-lg">L</span>
                    </div>
                    <div className="hidden md:block">
                        <h1 className="text-xl font-bold text-gray-900 dark:text-white">LavishStay</h1>
                        <p className="text-xs text-gray-500 dark:text-gray-400">{t('news.subtitle', 'Tin tức')}</p>
                    </div>
                </motion.div>

                {/* Desktop Menu */}
                <div className="hidden lg:flex items-center space-x-8">
                    {menuItems.map((item) => (
                        <motion.a
                            key={item.key}
                            href={`/${item.key}`}
                            className="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors"
                            whileHover={{ y: -2 }}
                        >
                            {item.label}
                        </motion.a>
                    ))}
                </div>

                {/* Search & Actions */}
                <div className="flex items-center space-x-4">
                    {/* Search */}
                    <div className="hidden md:block">
                        <Search
                            placeholder={t('search.placeholder', 'Tìm kiếm tin tức...')}
                            allowClear
                            enterButton={<SearchOutlined />}
                            size="middle"
                            className="w-64"
                            onSearch={onSearch}
                        />
                    </div>

                    {/* Mobile Search Toggle */}
                    <motion.button
                        whileTap={{ scale: 0.95 }}
                        className="md:hidden p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400"
                        onClick={() => setSearchVisible(!searchVisible)}
                    >
                        <FiSearch size={20} />
                    </motion.button>

                    {/* Language Switcher */}
                    <Dropdown menu={languageMenu} placement="bottomRight">
                        <motion.button
                            whileTap={{ scale: 0.95 }}
                            className="p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400"
                        >
                            <FiGlobe size={20} />
                        </motion.button>
                    </Dropdown>

                    {/* Theme Toggle */}
                    <motion.div whileTap={{ scale: 0.95 }}>
                        <Switch
                            checked={darkMode}
                            onChange={onToggleTheme}
                            checkedChildren={<MoonOutlined />}
                            unCheckedChildren={<SunOutlined />}
                            className="bg-gray-300"
                        />
                    </motion.div>

                    {/* Notifications */}
                    <Badge count={3} size="small">
                        <motion.button
                            whileTap={{ scale: 0.95 }}
                            className="p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400"
                        >
                            <FiBell size={20} />
                        </motion.button>
                    </Badge>

                    {/* User Menu */}
                    <Dropdown menu={userMenu} placement="bottomRight">
                        <motion.div whileTap={{ scale: 0.95 }} className="cursor-pointer">
                            <Avatar size="default" icon={<UserOutlined />} />
                        </motion.div>
                    </Dropdown>

                    {/* Mobile Menu Toggle */}
                    <motion.button
                        whileTap={{ scale: 0.95 }}
                        className="lg:hidden p-2 text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400"
                        onClick={() => setDrawerVisible(true)}
                    >
                        <FiMenu size={20} />
                    </motion.button>
                </div>
            </motion.div>

            {/* Mobile Search Bar */}
            {searchVisible && (
                <motion.div
                    initial={{ opacity: 0, y: -20 }}
                    animate={{ opacity: 1, y: 0 }}
                    exit={{ opacity: 0, y: -20 }}
                    className="md:hidden border-t border-gray-200 dark:border-gray-700 p-4"
                >
                    <Search
                        placeholder={t('search.placeholder', 'Tìm kiếm tin tức...')}
                        allowClear
                        enterButton={<SearchOutlined />}
                        size="middle"
                        onSearch={onSearch}
                        autoFocus
                    />
                </motion.div>
            )}

            {/* Mobile Drawer */}
            <Drawer
                title="Menu"
                placement="right"
                onClose={() => setDrawerVisible(false)}
                open={drawerVisible}
                className="lg:hidden"
            >
                <Menu
                    mode="vertical"
                    items={menuItems.map(item => ({
                        key: item.key,
                        label: item.label,
                    }))}
                    className="border-none"
                />
            </Drawer>
        </Header>
    );
};

export default NewsHeader;
