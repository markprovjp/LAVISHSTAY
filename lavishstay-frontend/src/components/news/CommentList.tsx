
// src/components/news/CommentList.tsx
import React from 'react';
import { List, Avatar, Button, Space, Empty, Spin, message } from 'antd';
import { UserOutlined, LikeOutlined, ReplyArrowIcon } from '@ant-design/icons';
import { motion } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { useComments, useToggleCommentLike } from '../../hooks/useNews';
import { Comment } from '../../services/newsApi';
import { formatTimeAgo } from '../../utils/timeHelpers';

interface CommentListProps {
    newsId: number;
    onCommentUpdated?: () => void;
}

const CommentList: React.FC<CommentListProps> = ({
    newsId,
    onCommentUpdated
}) => {
    const { t } = useTranslation();

    // API queries
    const {
        data: commentsResponse,
        isLoading,
        error
    } = useComments(newsId);

    const comments = commentsResponse?.data || [];

    const toggleCommentLikeMutation = useToggleCommentLike();

    // Handle comment like
    const handleLikeComment = (commentId: number) => {
        if (!newsId) {
            message.error(t('news.comments.error.no_news_id', 'Không tìm thấy bài viết'));
            return;
        }

        toggleCommentLikeMutation.mutate({ newsId, commentId }, {
            onSuccess: () => {
                onCommentUpdated?.();
            },
            onError: () => {
                message.error(t('news.comments.error.like', 'Không thể thích bình luận'));
            }
        });
    };

    // Loading state
    if (isLoading) {
        return (
            <div className="flex justify-center py-8">
                <Spin tip={t('news.comments.loading', 'Đang tải bình luận...')} />
            </div>
        );
    }

    // Error state
    if (error) {
        return (
            <Empty
                description={t('news.comments.error.load', 'Không thể tải bình luận')}
                image={Empty.PRESENTED_IMAGE_SIMPLE}
            />
        );
    }

    // Empty state
    if (!comments || comments.length === 0) {
        return (
            <Empty
                description={t('news.comments.empty', 'Chưa có bình luận nào')}
                image={Empty.PRESENTED_IMAGE_SIMPLE}
            />
        );
    }

    return (
        <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.3 }}
        >
            <List
                itemLayout="vertical"
                dataSource={comments}
                renderItem={(comment: Comment, index) => (
                    <motion.div
                        key={comment.id}
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ delay: index * 0.1 }}
                    >
                        <List.Item
                            key={comment.id}
                            actions={[
                                <Space key="like">
                                    <Button
                                        type="text"
                                        icon={<LikeOutlined />}
                                        size="small"
                                        className={comment.is_liked ? 'text-blue-500' : ''}
                                        onClick={() => handleLikeComment(comment.id)}
                                        loading={toggleCommentLikeMutation.isPending}
                                    >
                                        {(comment as any).likes ?? comment.likes_count ?? 0}
                                    </Button>
                                </Space>,
                                <Space key="time">
                                    <span className="text-gray-500 text-sm">
                                        {formatTimeAgo(comment.created_at)}
                                    </span>
                                </Space>
                            ]}
                        >
                            <List.Item.Meta
                                avatar={
                                    <Avatar
                                        src={comment.user?.avatar_url}
                                        icon={<UserOutlined />}
                                        size="default"
                                    />
                                }
                                title={
                                    <span className="font-medium">
                                        {comment.user?.name || t('news.author.anonymous', 'Ẩn danh')}
                                    </span>
                                }
                                description={
                                    <div
                                        className="mt-2 text-gray-800 whitespace-pre-wrap"
                                        dangerouslySetInnerHTML={{ __html: comment.content }}
                                    />
                                }
                            />
                            {/* Reply form placeholder - show reply form when clicking Reply */}
                            {/* This component focuses on displaying comments. Replying will be handled by parent (NewsDetail) via CommentForm. */}

                            {/* Replies */}
                            {comment.replies && Array.isArray(comment.replies) && comment.replies.length > 0 && (
                                <div className="ml-12 mt-4 border-l-2 border-gray-100 pl-4">
                                    {comment.replies.map((reply: Comment) => (
                                        <div key={reply.id} className="mb-4">
                                            <div className="flex items-start space-x-3">
                                                <Avatar
                                                    src={reply.user?.avatar_url}
                                                    icon={<UserOutlined />}
                                                    size="small"
                                                />
                                                <div className="flex-1">
                                                    <div className="flex items-center space-x-2">
                                                        <span className="font-medium text-sm">
                                                            {reply.user?.name || t('news.author.anonymous', 'Ẩn danh')}
                                                        </span>
                                                        <span className="text-gray-500 text-xs">
                                                            {formatTimeAgo(reply.created_at)}
                                                        </span>
                                                    </div>
                                                    <div
                                                        className="mt-1 text-gray-800 text-sm whitespace-pre-wrap"
                                                        dangerouslySetInnerHTML={{ __html: reply.content }}
                                                    />
                                                    <div className="mt-2">
                                                        <Button
                                                            type="text"
                                                            icon={<LikeOutlined />}
                                                            size="small"
                                                            className={`text-xs ${reply.is_liked ? 'text-blue-500' : ''}`}
                                                            onClick={() => handleLikeComment(reply.id)}
                                                            loading={toggleCommentLikeMutation.isPending}
                                                        >
                                                            {(reply as any).likes ?? reply.likes_count ?? 0}
                                                        </Button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </List.Item>
                    </motion.div>
                )}
                pagination={{
                    pageSize: 10,
                    showSizeChanger: false,
                    showQuickJumper: false,
                    showTotal: (total, range) =>
                        `${range[0]}-${range[1]} trong ${total} bình luận`,
                }}
            />
        </motion.div>
    );
};

export default CommentList;
