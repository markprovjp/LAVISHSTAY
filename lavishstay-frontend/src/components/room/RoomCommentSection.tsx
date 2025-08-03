// src/components/room/RoomCommentSection.tsx
import React, { useState } from 'react';
import { Card, Avatar, Rate, Button, Skeleton, Empty, Divider, Tag, Space, Select, Image } from 'antd';
import { motion, AnimatePresence } from 'framer-motion';
import {
    MessageCircle,
    ThumbsUp,
    Calendar,
    MapPin,
    Filter,
    Star,
    User,
    CheckCircle,
    Reply,
    MoreHorizontal
} from 'lucide-react';
import { RoomComment } from '../../types/roomDetail';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';

dayjs.extend(relativeTime);

const { Option } = Select;

interface RoomCommentSectionProps {
    comments: RoomComment[];
    loading?: boolean;
    totalComments?: number;
    onLoadMore?: () => void;
    hasMore?: boolean;
    className?: string;
}

const RoomCommentSection: React.FC<RoomCommentSectionProps> = ({
    comments,
    loading = false,
    totalComments = 0,
    onLoadMore,
    hasMore = false,
    className = ''
}) => {
    const [sortBy, setSortBy] = useState<'newest' | 'oldest' | 'helpful' | 'rating'>('newest');
    const [filterRating, setFilterRating] = useState<number | 'all'>('all');
    const [expandedComments, setExpandedComments] = useState<Set<string>>(new Set());

    const toggleExpanded = (commentId: string) => {
        const newExpanded = new Set(expandedComments);
        if (newExpanded.has(commentId)) {
            newExpanded.delete(commentId);
        } else {
            newExpanded.add(commentId);
        }
        setExpandedComments(newExpanded);
    };

    const sortedAndFilteredComments = comments
        .filter(comment => filterRating === 'all' || comment.rating === filterRating)
        .sort((a, b) => {
            switch (sortBy) {
                case 'newest':
                    return dayjs(b.createdAt).valueOf() - dayjs(a.createdAt).valueOf();
                case 'oldest':
                    return dayjs(a.createdAt).valueOf() - dayjs(b.createdAt).valueOf();
                case 'helpful':
                    return b.helpful - a.helpful;
                case 'rating':
                    return b.rating - a.rating;
                default:
                    return 0;
            }
        });

    const averageRating = comments.length > 0
        ? comments.reduce((sum, comment) => sum + comment.rating, 0) / comments.length
        : 0;

    const ratingDistribution = [5, 4, 3, 2, 1].map(rating => {
        const count = comments.filter(comment => comment.rating === rating).length;
        const percentage = comments.length > 0 ? (count / comments.length) * 100 : 0;
        return { rating, count, percentage };
    });

    const CommentCard: React.FC<{ comment: RoomComment; index: number }> = ({
        comment,
        index
    }) => {
        const isExpanded = expandedComments.has(comment.id);
        const shouldTruncate = comment.content.length > 200;

        return (
            <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.3, delay: index * 0.1 }}
                className="comment-card"
            >
                <Card className="hover:shadow-md transition-shadow duration-300" bodyStyle={{ padding: '20px' }}>
                    <div className="space-y-4">
                        {/* Header */}
                        <div className="flex items-start justify-between">
                            <div className="flex items-center gap-3">
                                <Avatar
                                    size={48}
                                    src={comment.userAvatar}
                                    icon={<User />}
                                    className="border-2 border-gray-200"
                                />
                                <div>
                                    <div className="flex items-center gap-2">
                                        <h4 className="font-semibold text-gray-900 dark:text-white">
                                            {comment.userName}
                                        </h4>
                                        {comment.isVerified && (
                                            <CheckCircle size={16} className="text-green-500" />
                                        )}
                                    </div>
                                    <div className="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                                        <Calendar size={14} />
                                        <span>{dayjs(comment.createdAt).fromNow()}</span>
                                        {comment.stayDuration && (
                                            <>
                                                <span>•</span>
                                                <span>Lưu trú {comment.stayDuration}</span>
                                            </>
                                        )}
                                        {comment.roomNumber && (
                                            <>
                                                <span>•</span>
                                                <MapPin size={14} />
                                                <span>Phòng {comment.roomNumber}</span>
                                            </>
                                        )}
                                    </div>
                                </div>
                            </div>

                            <Button
                                type="text"
                                icon={<MoreHorizontal size={16} />}
                                className="text-gray-400 hover:text-gray-600"
                            />
                        </div>

                        {/* Rating and Title */}
                        <div>
                            <div className="flex items-center gap-3 mb-2">
                                <Rate disabled value={comment.rating} size="small" />
                                <span className="text-sm font-medium text-gray-600 dark:text-gray-400">
                                    {comment.rating}/5
                                </span>
                            </div>
                            {comment.title && (
                                <h5 className="font-semibold text-gray-900 dark:text-white text-lg">
                                    {comment.title}
                                </h5>
                            )}
                        </div>

                        {/* Content */}
                        <div>
                            <p className="text-gray-700 dark:text-gray-300 leading-relaxed">
                                {shouldTruncate && !isExpanded
                                    ? `${comment.content.substring(0, 200)}...`
                                    : comment.content
                                }
                            </p>
                            {shouldTruncate && (
                                <Button
                                    type="link"
                                    onClick={() => toggleExpanded(comment.id)}
                                    className="p-0 h-auto text-blue-600 dark:text-blue-400"
                                >
                                    {isExpanded ? 'Thu gọn' : 'Xem thêm'}
                                </Button>
                            )}
                        </div>

                        {/* Images */}
                        {comment.images && comment.images.length > 0 && (
                            <div className="flex gap-2 flex-wrap">
                                {comment.images.map((image, imgIndex) => (
                                    <Image
                                        key={imgIndex}
                                        src={image}
                                        alt={`Review image ${imgIndex + 1}`}
                                        width={100}
                                        height={100}
                                        className="rounded-lg object-cover"
                                        preview={{
                                            mask: <div className="flex items-center justify-center text-white text-xs">Xem</div>
                                        }}
                                    />
                                ))}
                            </div>
                        )}

                        {/* Actions */}
                        <div className="flex items-center justify-between pt-2 border-t border-gray-100 dark:border-gray-700">
                            <div className="flex items-center gap-4">
                                <Button
                                    type="text"
                                    icon={<ThumbsUp size={16} />}
                                    className="text-gray-500 hover:text-blue-600 dark:hover:text-blue-400"
                                >
                                    Hữu ích ({comment.helpful})
                                </Button>
                                <Button
                                    type="text"
                                    icon={<Reply size={16} />}
                                    className="text-gray-500 hover:text-blue-600 dark:hover:text-blue-400"
                                >
                                    Trả lời
                                </Button>
                            </div>

                            {comment.updatedAt && comment.updatedAt !== comment.createdAt && (
                                <span className="text-xs text-gray-400">
                                    Đã chỉnh sửa
                                </span>
                            )}
                        </div>

                        {/* Replies */}
                        {comment.replies && comment.replies.length > 0 && (
                            <div className="ml-12 space-y-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                                {comment.replies.map((reply) => (
                                    <div key={reply.id} className="flex gap-3">
                                        <Avatar
                                            size={32}
                                            src={reply.userAvatar}
                                            icon={<User />}
                                        />
                                        <div className="flex-1 bg-gray-50 dark:bg-gray-700 p-3 rounded-lg">
                                            <div className="flex items-center gap-2 mb-1">
                                                <span className="font-medium text-sm text-gray-900 dark:text-white">
                                                    {reply.userName}
                                                </span>
                                                {reply.isStaff && (
                                                    <Tag color="blue" size="small">
                                                        Nhân viên
                                                    </Tag>
                                                )}
                                                <span className="text-xs text-gray-500 dark:text-gray-400">
                                                    {dayjs(reply.createdAt).fromNow()}
                                                </span>
                                            </div>
                                            <p className="text-sm text-gray-700 dark:text-gray-300">
                                                {reply.content}
                                            </p>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>
                </Card>
            </motion.div>
        );
    };

    if (loading && comments.length === 0) {
        return (
            <div className={`room-comments ${className}`}>
                <Card className="border-0 shadow-lg rounded-2xl">
                    <div className="space-y-6">
                        <Skeleton active paragraph={{ rows: 2 }} />
                        {[...Array(3)].map((_, index) => (
                            <div key={index} className="space-y-4">
                                <Skeleton avatar active paragraph={{ rows: 3 }} />
                                <Divider />
                            </div>
                        ))}
                    </div>
                </Card>
            </div>
        );
    }

    return (
        <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.5 }}
            className={`room-comments ${className}`}
        >
            <Card className="border-0 shadow-lg rounded-2xl overflow-hidden">
                <div className="space-y-6">
                    {/* Header */}
                    <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <h2 className="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                <MessageCircle size={24} />
                                Đánh giá của khách hàng
                            </h2>
                            <p className="text-gray-600 dark:text-gray-400 mt-1">
                                {totalComments} đánh giá • Điểm trung bình {averageRating.toFixed(1)}/5
                            </p>
                        </div>

                        <div className="flex items-center gap-2">
                            <Rate disabled value={averageRating} allowHalf />
                            <span className="text-lg font-semibold text-gray-900 dark:text-white">
                                {averageRating.toFixed(1)}
                            </span>
                        </div>
                    </div>

                    {/* Rating Distribution */}
                    <div className="bg-gray-50 dark:bg-gray-800 p-4 rounded-xl">
                        <h3 className="font-semibold text-gray-900 dark:text-white mb-3">
                            Phân bố đánh giá 
                        </h3>
                        <div className="space-y-2">
                            {ratingDistribution.map(({ rating, count, percentage }) => (
                                <div key={rating} className="flex items-center gap-3">
                                    <div className="flex items-center gap-1 w-16">
                                        <span className="text-sm font-medium">{rating}</span>
                                        <Star size={14} className="text-yellow-500" />
                                    </div>
                                    <div className="flex-1 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                        <div
                                            className="bg-yellow-500 h-2 rounded-full transition-all duration-500"
                                            style={{ width: `${percentage}%` }}
                                        />
                                    </div>
                                    <span className="text-sm text-gray-600 dark:text-gray-400 w-8">
                                        {count}
                                    </span>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Filters and Sort */}
                    <div className="flex flex-col sm:flex-row gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-xl">
                        <div className="flex items-center gap-2">
                            <Filter size={16} />
                            <span className="text-sm font-medium">Lọc và sắp xếp:</span>
                        </div>
                        <div className="flex gap-2 flex-wrap">
                            <Select
                                value={sortBy}
                                onChange={setSortBy}
                                className="w-40"
                                size="small"
                            >
                                <Option value="newest">Mới nhất</Option>
                                <Option value="oldest">Cũ nhất</Option>
                                <Option value="helpful">Hữu ích nhất</Option>
                                <Option value="rating">Điểm cao nhất</Option>
                            </Select>
                            <Select
                                value={filterRating}
                                onChange={setFilterRating}
                                className="w-32"
                                size="small"
                            >
                                <Option value="all">Tất cả</Option>
                                <Option value={5}>5 sao</Option>
                                <Option value={4}>4 sao</Option>
                                <Option value={3}>3 sao</Option>
                                <Option value={2}>2 sao</Option>
                                <Option value={1}>1 sao</Option>
                            </Select>
                        </div>
                    </div>

                    {/* Comments List */}
                    <div className="space-y-4">
                        {sortedAndFilteredComments.length > 0 ? (
                            <AnimatePresence>
                                {sortedAndFilteredComments.map((comment, index) => (
                                    <CommentCard
                                        key={comment.id}
                                        comment={comment}
                                        index={index}
                                    />
                                ))}
                            </AnimatePresence>
                        ) : (
                            <Empty
                                description="Chưa có đánh giá nào"
                                className="py-12"
                            />
                        )}
                    </div>

                    {/* Load More */}
                    {hasMore && (
                        <div className="text-center">
                            <Button
                                type="primary"
                                ghost
                                onClick={onLoadMore}
                                loading={loading}
                                className="px-8"
                            >
                                Xem thêm đánh giá
                            </Button>
                        </div>
                    )}
                </div>
            </Card>
        </motion.div>
    );
};

export default RoomCommentSection;
