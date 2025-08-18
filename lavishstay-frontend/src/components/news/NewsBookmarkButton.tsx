// src/components/news/NewsBookmarkButton.tsx
import React from 'react';
import { Button, Tooltip, message } from 'antd';
import { motion } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { BookOutlined, BookFilled } from '@ant-design/icons';
import { create } from 'zustand';
import { persist } from 'zustand/middleware';

// Zustand store for bookmarks
interface BookmarkStore {
    bookmarks: Set<string>;
    toggleBookmark: (newsId: string) => void;
    isBookmarked: (newsId: string) => boolean;
}

const useBookmarkStore = create<BookmarkStore>()(
    persist(
        (set, get) => ({
            bookmarks: new Set<string>(),
            toggleBookmark: (newsId: string) => {
                let { bookmarks } = get();
                // Ensure correct type
                if (!(bookmarks instanceof Set)) {
                    if (Array.isArray(bookmarks)) {
                        bookmarks = new Set(bookmarks);
                    } else if (bookmarks && typeof bookmarks === 'object') {
                        bookmarks = new Set(Object.values(bookmarks));
                    } else {
                        bookmarks = new Set();
                    }
                }
                const newBookmarks = new Set(bookmarks);
                if (newBookmarks.has(newsId)) {
                    newBookmarks.delete(newsId);
                    message.success('Đã bỏ lưu tin tức');
                } else {
                    newBookmarks.add(newsId);
                    message.success('Đã lưu tin tức');
                }
                set({ bookmarks: newBookmarks });
            },
            isBookmarked: (newsId: string) => {
                let { bookmarks } = get();
                if (!(bookmarks instanceof Set)) {
                    if (Array.isArray(bookmarks)) {
                        bookmarks = new Set(bookmarks);
                    } else if (bookmarks && typeof bookmarks === 'object') {
                        bookmarks = new Set(Object.values(bookmarks));
                    } else {
                        bookmarks = new Set();
                    }
                }
                return bookmarks.has(newsId);
            },
        }),
        {
            name: 'news-bookmarks',
            serialize: (state) => JSON.stringify({
                ...state,
                bookmarks: Array.from(state.bookmarks)
            }),
            deserialize: (str) => {
                const parsed = JSON.parse(str);
                return {
                    ...parsed,
                    bookmarks: new Set(parsed.bookmarks)
                };
            },
        }
    )
);

interface NewsBookmarkButtonProps {
    newsId: string;
    isBookmarked?: boolean;
    size?: 'small' | 'middle' | 'large';
    type?: 'default' | 'text' | 'link';
}

const NewsBookmarkButton: React.FC<NewsBookmarkButtonProps> = ({
    newsId,
    isBookmarked: propIsBookmarked,
    size = 'small',
    type = 'text'
}) => {
    const { t } = useTranslation();
    const { toggleBookmark, isBookmarked: storeIsBookmarked } = useBookmarkStore();

    // Use prop value if provided, otherwise use store value
    const isBookmarked = propIsBookmarked !== undefined ? propIsBookmarked : storeIsBookmarked(newsId);

    const handleBookmark = (e: React.MouseEvent) => {
        e.stopPropagation(); // Prevent card click
        toggleBookmark(newsId);
    };

    return (
        <Tooltip title={isBookmarked ? t('news.bookmark.remove', 'Bỏ lưu') : t('news.bookmark.add', 'Lưu tin')}>
            <motion.div
                whileHover={{ scale: 1.1 }}
                whileTap={{ scale: 0.9 }}
            >
                <Button
                    type={type}
                    size={size}
                    icon={
                        <motion.div
                            animate={isBookmarked ? {
                                scale: [1, 1.3, 1],
                                rotate: [0, -10, 10, 0]
                            } : {}}
                            transition={{ duration: 0.5 }}
                        >
                            {isBookmarked ?
                                <BookFilled className="text-yellow-500" /> :
                                <BookOutlined className="text-gray-500 hover:text-yellow-500" />
                            }
                        </motion.div>
                    }
                    onClick={handleBookmark}
                    className={`border-none shadow-none hover:bg-gray-100 dark:hover:bg-gray-700 ${isBookmarked ? 'text-yellow-500' : 'text-gray-500'
                        }`}
                />
            </motion.div>
        </Tooltip>
    );
};

export default NewsBookmarkButton;
