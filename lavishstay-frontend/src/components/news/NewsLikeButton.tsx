// src/components/news/NewsLikeButton.tsx
import React, { useState } from 'react';
import { Button, Tooltip, message } from 'antd';
import { motion, AnimatePresence } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { HeartOutlined, HeartFilled } from '@ant-design/icons';
import { create } from 'zustand';
import { persist } from 'zustand/middleware';

// Zustand store for likes
interface LikeStore {
    likes: Map<string, number>; // newsId -> like count
    userLikes: Set<string>; // newsIds that user liked
    toggleLike: (newsId: string, initialCount?: number) => void;
    isLiked: (newsId: string) => boolean;
    getLikeCount: (newsId: string) => number;
}

const useLikeStore = create<LikeStore>()(
    persist(
        (set, get) => ({
            likes: new Map<string, number>(),
            userLikes: new Set<string>(),
            toggleLike: (newsId: string, initialCount = 0) => {
                const { likes, userLikes } = get();
                const newLikes = new Map(likes);
                const newUserLikes = new Set(userLikes);

                if (!newLikes.has(newsId)) {
                    newLikes.set(newsId, initialCount);
                }

                const currentCount = newLikes.get(newsId) || 0;

                if (newUserLikes.has(newsId)) {
                    newUserLikes.delete(newsId);
                    newLikes.set(newsId, Math.max(0, currentCount - 1));
                    message.success('Đã bỏ thích');
                } else {
                    newUserLikes.add(newsId);
                    newLikes.set(newsId, currentCount + 1);
                    message.success('Đã thích tin tức');
                }

                set({ likes: newLikes, userLikes: newUserLikes });
            },
            isLiked: (newsId: string) => {
                return get().userLikes.has(newsId);
            },
            getLikeCount: (newsId: string) => {
                return get().likes.get(newsId) || 0;
            },
        }),
        {
            name: 'news-likes',
            serialize: (state) => JSON.stringify({
                likes: Array.from(state.likes.entries()),
                userLikes: Array.from(state.userLikes)
            }),
            deserialize: (str) => {
                const parsed = JSON.parse(str);
                return {
                    likes: new Map(parsed.likes),
                    userLikes: new Set(parsed.userLikes),
                    toggleLike: get().toggleLike,
                    isLiked: get().isLiked,
                    getLikeCount: get().getLikeCount,
                };
            },
        }
    )
);

interface NewsLikeButtonProps {
    newsId: string;
    initialLikeCount?: number;
    isLiked?: boolean;
    size?: 'small' | 'middle' | 'large';
    type?: 'default' | 'text' | 'link';
    showCount?: boolean;
}

const NewsLikeButton: React.FC<NewsLikeButtonProps> = ({
    newsId,
    initialLikeCount = 0,
    isLiked: propIsLiked,
    size = 'small',
    type = 'text',
    showCount = true
}) => {
    const { t } = useTranslation();
    const { toggleLike, isLiked: storeIsLiked, getLikeCount } = useLikeStore();
    const [isAnimating, setIsAnimating] = useState(false);

    // Use prop value if provided, otherwise use store value
    const isLiked = propIsLiked !== undefined ? propIsLiked : storeIsLiked(newsId);
    const likeCount = getLikeCount(newsId) || initialLikeCount;

    const handleLike = async (e: React.MouseEvent) => {
        e.stopPropagation(); // Prevent card click

        if (isAnimating) return;

        setIsAnimating(true);
        toggleLike(newsId, initialLikeCount);

        // Reset animation state after animation completes
        setTimeout(() => setIsAnimating(false), 600);
    };

    const formatLikeCount = (count: number): string => {
        if (count >= 1000000) {
            return `${(count / 1000000).toFixed(1)}M`;
        } else if (count >= 1000) {
            return `${(count / 1000).toFixed(1)}K`;
        }
        return count.toString();
    };

    return (
        <Tooltip title={isLiked ? t('news.like.remove', 'Bỏ thích') : t('news.like.add', 'Thích')}>
            <motion.div
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
                className="flex items-center space-x-1"
            >
                <Button
                    type={type}
                    size={size}
                    icon={
                        <motion.div
                            animate={isLiked && isAnimating ? {
                                scale: [1, 1.5, 1.2, 1],
                                rotate: [0, -15, 15, 0]
                            } : {}}
                            transition={{ duration: 0.6, ease: "easeOut" }}
                            className="relative"
                        >
                            {isLiked ?
                                <HeartFilled className="text-red-500" /> :
                                <HeartOutlined className="text-gray-500 hover:text-red-500" />
                            }

                            {/* Heart explosion effect */}
                            <AnimatePresence>
                                {isLiked && isAnimating && (
                                    <>
                                        {[...Array(6)].map((_, i) => (
                                            <motion.div
                                                key={i}
                                                className="absolute inset-0 text-red-400"
                                                initial={{
                                                    scale: 0,
                                                    rotate: i * 60,
                                                    opacity: 0.8
                                                }}
                                                animate={{
                                                    scale: [0, 1.5, 0],
                                                    x: [0, Math.cos(i * 60 * Math.PI / 180) * 20, 0],
                                                    y: [0, Math.sin(i * 60 * Math.PI / 180) * 20, 0],
                                                    opacity: [0.8, 0.4, 0]
                                                }}
                                                exit={{ opacity: 0 }}
                                                transition={{
                                                    duration: 0.6,
                                                    delay: i * 0.1,
                                                    ease: "easeOut"
                                                }}
                                            >
                                                ♥
                                            </motion.div>
                                        ))}
                                    </>
                                )}
                            </AnimatePresence>
                        </motion.div>
                    }
                    onClick={handleLike}
                    className={`border-none shadow-none hover:bg-gray-100 dark:hover:bg-gray-700 ${isLiked ? 'text-red-500' : 'text-gray-500'
                        }`}
                />

                {showCount && likeCount > 0 && (
                    <motion.span
                        key={likeCount}
                        initial={{ scale: 1.2, opacity: 0.8 }}
                        animate={{ scale: 1, opacity: 1 }}
                        transition={{ duration: 0.3 }}
                        className={`text-xs font-medium ${isLiked ? 'text-red-500' : 'text-gray-500'
                            }`}
                    >
                        {formatLikeCount(likeCount)}
                    </motion.span>
                )}
            </motion.div>
        </Tooltip>
    );
};

export default NewsLikeButton;
