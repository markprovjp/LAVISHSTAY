// src/components/news/NewsModal.tsx
import React, { useState, useEffect } from 'react';
import {
    Modal,
    Typography,
    Space,
    Tag,
    Avatar,
    Divider,
    Comment,
    Form,
    Input,
    Button,
    List,
    Rate,
    Skeleton,
    Image
} from 'antd';
import { motion, AnimatePresence } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import {
    ClockCircleOutlined,
    EyeOutlined,
    UserOutlined,
    SendOutlined,
    MessageOutlined
} from '@ant-design/icons';
import dayjs from 'dayjs';
import NewsBookmarkButton from './NewsBookmarkButton';
import NewsLikeButton from './NewsLikeButton';
import NewsShareButton from './NewsShareButton';

const { Title, Text, Paragraph } = Typography;
const { TextArea } = Input;

interface NewsComment {
    id: string;
    author: string;
    avatar?: string;
    content: string;
    createdAt: string;
    likes: number;
    replies?: NewsComment[];
}

interface NewsDetail {
    id: string;
    title: string;
    content: string;
    summary: string;
    thumbnail: string;
    images?: string[];
    author: {
        name: string;
        avatar?: string;
        bio?: string;
    };
    category: string;
    tags: string[];
    publishedAt: string;
    updatedAt?: string;
    views: number;
    likes: number;
    isLiked: boolean;
    isBookmarked: boolean;
    rating: number;
    totalRatings: number;
    readTime: number;
    comments: NewsComment[];
    relatedNews: Array<{
        id: string;
        title: string;
        thumbnail: string;
    }>;
}

interface NewsModalProps {
    newsId: string | null;
    open: boolean;
    onClose: () => void;
}

const NewsModal: React.FC<NewsModalProps> = ({ newsId, open, onClose }) => {
    const { t } = useTranslation();
    const [commentForm] = Form.useForm();
    const [userRating, setUserRating] = useState<number>(0);
    const queryClient = useQueryClient();

    // Fetch news detail
    const { data: newsDetail, isLoading } = useQuery({
        queryKey: ['news-detail', newsId],
        queryFn: async (): Promise<NewsDetail> => {
            if (!newsId) throw new Error('No news ID provided');

            // Mock API call
            await new Promise(resolve => setTimeout(resolve, 1000));

            return {
                id: newsId,
                title: 'Công nghệ AI mới nhất trong năm 2024: Những đột phá đáng chú ý',
                content: `
          <div class="news-content">
            <p>Năm 2024 đã chứng kiến những bước tiến vượt bậc trong lĩnh vực trí tuệ nhân tạo, với nhiều công nghệ đột phá được ra mắt và ứng dụng vào thực tế.</p>
            
            <h3>1. AI Generative - Cuộc cách mạng sáng tạo</h3>
            <p>Các mô hình AI generative đã có những cải tiến đáng kể về chất lượng và tốc độ xử lý. Từ việc tạo ra nội dung văn bản, hình ảnh cho đến âm thanh và video, AI đã trở thành công cụ đắc lực cho các nhà sáng tạo.</p>
            
            <h3>2. AI trong y tế - Chẩn đoán chính xác hơn</h3>
            <p>Những tiến bộ trong machine learning đã giúp các hệ thống AI có thể chẩn đoán bệnh với độ chính xác cao hơn bác sĩ con người trong một số trường hợp cụ thể.</p>
            
            <h3>3. Autonomous Systems - Tự động hóa thông minh</h3>
            <p>Từ xe tự lái đến robot gia dụng, các hệ thống tự động được trang bị AI ngày càng trở nên thông minh và an toàn hơn.</p>
            
            <p>Tương lai của AI hứa hẹn sẽ còn nhiều điều thú vị hơn nữa trong những năm tới.</p>
          </div>
        `,
                summary: 'Tổng quan về những công nghệ AI đột phá nhất trong năm 2024, từ AI generative đến ứng dụng trong y tế và hệ thống tự động.',
                thumbnail: 'https://picsum.photos/800/400?random=1',
                images: [
                    'https://picsum.photos/600/400?random=2',
                    'https://picsum.photos/600/400?random=3',
                    'https://picsum.photos/600/400?random=4'
                ],
                author: {
                    name: 'Nguyễn Văn An',
                    avatar: 'https://picsum.photos/60/60?random=author',
                    bio: 'Chuyên gia công nghệ với 10 năm kinh nghiệm trong lĩnh vực AI và Machine Learning.'
                },
                category: 'Công nghệ',
                tags: ['AI', 'Machine Learning', 'Công nghệ', '2024', 'Đột phá'],
                publishedAt: '2024-01-15T10:30:00Z',
                updatedAt: '2024-01-15T11:00:00Z',
                views: 125000,
                likes: 1250,
                isLiked: false,
                isBookmarked: false,
                rating: 4.5,
                totalRatings: 324,
                readTime: 8,
                comments: [
                    {
                        id: '1',
                        author: 'Trần Thị B',
                        avatar: 'https://picsum.photos/40/40?random=user1',
                        content: 'Bài viết rất hay và cập nhật! Cảm ơn tác giả đã chia sẻ.',
                        createdAt: '2024-01-15T11:30:00Z',
                        likes: 15
                    },
                    {
                        id: '2',
                        author: 'Lê Văn C',
                        avatar: 'https://picsum.photos/40/40?random=user2',
                        content: 'AI thực sự đang thay đổi thế giới. Rất mong chờ những phát triển tiếp theo.',
                        createdAt: '2024-01-15T12:00:00Z',
                        likes: 8
                    }
                ],
                relatedNews: [
                    {
                        id: '2',
                        title: 'Blockchain và tương lai của tài chính số',
                        thumbnail: 'https://picsum.photos/150/100?random=related1'
                    },
                    {
                        id: '3',
                        title: 'IoT - Kết nối vạn vật trong thời đại 4.0',
                        thumbnail: 'https://picsum.photos/150/100?random=related2'
                    }
                ]
            };
        },
        enabled: !!newsId && open,
        staleTime: 5 * 60 * 1000,
    });

    // Submit comment mutation
    const submitCommentMutation = useMutation({
        mutationFn: async (commentData: { content: string }) => {
            await new Promise(resolve => setTimeout(resolve, 1000));
            return {
                id: Date.now().toString(),
                author: 'Người dùng',
                content: commentData.content,
                createdAt: new Date().toISOString(),
                likes: 0
            };
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['news-detail', newsId] });
            commentForm.resetFields();
        },
    });

    // Submit rating mutation
    const submitRatingMutation = useMutation({
        mutationFn: async (rating: number) => {
            await new Promise(resolve => setTimeout(resolve, 500));
            return { rating };
        },
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['news-detail', newsId] });
        },
    });

    const handleCommentSubmit = (values: { comment: string }) => {
        if (values.comment.trim()) {
            submitCommentMutation.mutate({ content: values.comment });
        }
    };

    const handleRatingChange = (rating: number) => {
        setUserRating(rating);
        submitRatingMutation.mutate(rating);
    };

    const modalVariants = {
        hidden: {
            opacity: 0,
            scale: 0.8,
            y: 50
        },
        visible: {
            opacity: 1,
            scale: 1,
            y: 0,
            transition: {
                duration: 0.3,
                ease: "easeOut"
            }
        },
        exit: {
            opacity: 0,
            scale: 0.8,
            y: 50,
            transition: {
                duration: 0.2
            }
        }
    };

    const contentVariants = {
        hidden: { opacity: 0 },
        visible: {
            opacity: 1,
            transition: {
                duration: 0.5,
                staggerChildren: 0.1
            }
        }
    };

    const itemVariants = {
        hidden: { opacity: 0, y: 20 },
        visible: { opacity: 1, y: 0 }
    };

    if (!newsDetail && !isLoading) return null;

    return (
        <AnimatePresence>
            {open && (
                <Modal
                    open={open}
                    onCancel={onClose}
                    footer={null}
                    width="90%"
                    style={{ maxWidth: 1000, top: 20 }}
                    className="news-detail-modal"
                    destroyOnClose
                >
                    <motion.div
                        variants={modalVariants}
                        initial="hidden"
                        animate="visible"
                        exit="exit"
                    >
                        {isLoading ? (
                            <div className="space-y-6">
                                <Skeleton.Image style={{ width: '100%', height: 300 }} />
                                <Skeleton active paragraph={{ rows: 8 }} />
                            </div>
                        ) : newsDetail && (
                            <motion.div
                                variants={contentVariants}
                                initial="hidden"
                                animate="visible"
                                className="space-y-6"
                            >
                                {/* Header */}
                                <motion.div variants={itemVariants}>
                                    <Title level={2} className="mb-4">
                                        {newsDetail.title}
                                    </Title>

                                    <div className="flex flex-wrap items-center justify-between gap-4 mb-6">
                                        <div className="flex items-center space-x-4">
                                            <Avatar
                                                src={newsDetail.author.avatar}
                                                icon={<UserOutlined />}
                                                size={40}
                                            />
                                            <div>
                                                <Text strong>{newsDetail.author.name}</Text>
                                                <br />
                                                <Text className="text-sm text-gray-500">
                                                    {dayjs(newsDetail.publishedAt).format('DD/MM/YYYY HH:mm')}
                                                    {newsDetail.updatedAt && (
                                                        <span> • Cập nhật: {dayjs(newsDetail.updatedAt).format('HH:mm')}</span>
                                                    )}
                                                </Text>
                                            </div>
                                        </div>

                                        <div className="flex items-center space-x-4">
                                            <div className="flex items-center space-x-1 text-gray-500">
                                                <ClockCircleOutlined />
                                                <span className="text-sm">{newsDetail.readTime} phút đọc</span>
                                            </div>
                                            <div className="flex items-center space-x-1 text-gray-500">
                                                <EyeOutlined />
                                                <span className="text-sm">{newsDetail.views.toLocaleString()} lượt xem</span>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Tags */}
                                    <div className="flex flex-wrap gap-2 mb-6">
                                        <Tag color="blue">{newsDetail.category}</Tag>
                                        {newsDetail.tags.map(tag => (
                                            <Tag key={tag} className="cursor-pointer hover:bg-blue-50">
                                                #{tag}
                                            </Tag>
                                        ))}
                                    </div>
                                </motion.div>

                                {/* Main Image */}
                                <motion.div variants={itemVariants}>
                                    <Image
                                        src={newsDetail.thumbnail}
                                        alt={newsDetail.title}
                                        className="w-full rounded-lg"
                                        style={{ maxHeight: 400, objectFit: 'cover' }}
                                    />
                                </motion.div>

                                {/* Summary */}
                                <motion.div variants={itemVariants}>
                                    <div className="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border-l-4 border-blue-500">
                                        <Text className="font-medium text-blue-800 dark:text-blue-200">
                                            {newsDetail.summary}
                                        </Text>
                                    </div>
                                </motion.div>

                                {/* Content */}
                                <motion.div variants={itemVariants}>
                                    <div
                                        className="prose max-w-none dark:prose-invert"
                                        dangerouslySetInnerHTML={{ __html: newsDetail.content }}
                                    />
                                </motion.div>

                                {/* Additional Images */}
                                {newsDetail.images && newsDetail.images.length > 0 && (
                                    <motion.div variants={itemVariants}>
                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            {newsDetail.images.map((image, index) => (
                                                <Image
                                                    key={index}
                                                    src={image}
                                                    alt={`${newsDetail.title} - ${index + 1}`}
                                                    className="rounded-lg"
                                                />
                                            ))}
                                        </div>
                                    </motion.div>
                                )}

                                {/* Actions */}
                                <motion.div variants={itemVariants}>
                                    <div className="flex items-center justify-between py-4 border-t border-b border-gray-200 dark:border-gray-700">
                                        <div className="flex items-center space-x-4">
                                            <NewsLikeButton
                                                newsId={newsDetail.id}
                                                initialLikeCount={newsDetail.likes}
                                                isLiked={newsDetail.isLiked}
                                                size="middle"
                                                showCount
                                            />
                                            <NewsBookmarkButton
                                                newsId={newsDetail.id}
                                                isBookmarked={newsDetail.isBookmarked}
                                                size="middle"
                                            />
                                            <NewsShareButton
                                                newsId={newsDetail.id}
                                                title={newsDetail.title}
                                                description={newsDetail.summary}
                                                size="middle"
                                            />
                                        </div>

                                        <div className="flex items-center space-x-2">
                                            <Text className="text-sm text-gray-500">Đánh giá:</Text>
                                            <Rate
                                                value={userRating || newsDetail.rating}
                                                onChange={handleRatingChange}
                                                className="text-sm"
                                            />
                                            <Text className="text-sm text-gray-500">
                                                ({newsDetail.totalRatings})
                                            </Text>
                                        </div>
                                    </div>
                                </motion.div>

                                {/* Comments Section */}
                                <motion.div variants={itemVariants}>
                                    <Title level={4} className="flex items-center space-x-2">
                                        <MessageOutlined />
                                        <span>Bình luận ({newsDetail.comments.length})</span>
                                    </Title>

                                    {/* Comment Form */}
                                    <Form
                                        form={commentForm}
                                        onFinish={handleCommentSubmit}
                                        className="mb-6"
                                    >
                                        <Form.Item
                                            name="comment"
                                            rules={[{ required: true, message: 'Vui lòng nhập bình luận' }]}
                                        >
                                            <TextArea
                                                rows={3}
                                                placeholder="Viết bình luận của bạn..."
                                                className="resize-none"
                                            />
                                        </Form.Item>
                                        <Form.Item className="mb-0">
                                            <Button
                                                type="primary"
                                                htmlType="submit"
                                                icon={<SendOutlined />}
                                                loading={submitCommentMutation.isPending}
                                                className="bg-blue-500 hover:bg-blue-600"
                                            >
                                                Gửi bình luận
                                            </Button>
                                        </Form.Item>
                                    </Form>

                                    {/* Comments List */}
                                    <List
                                        dataSource={newsDetail.comments}
                                        renderItem={(comment) => (
                                            <Comment
                                                author={comment.author}
                                                avatar={<Avatar src={comment.avatar} icon={<UserOutlined />} />}
                                                content={comment.content}
                                                datetime={dayjs(comment.createdAt).fromNow()}
                                                actions={[
                                                    <Button
                                                        key="like"
                                                        type="text"
                                                        size="small"
                                                        className="text-gray-500 hover:text-blue-500"
                                                    >
                                                        Thích ({comment.likes})
                                                    </Button>
                                                ]}
                                            />
                                        )}
                                    />
                                </motion.div>

                                {/* Related News */}
                                {newsDetail.relatedNews.length > 0 && (
                                    <motion.div variants={itemVariants}>
                                        <Divider />
                                        <Title level={4}>Tin tức liên quan</Title>
                                        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            {newsDetail.relatedNews.map((relatedItem) => (
                                                <div
                                                    key={relatedItem.id}
                                                    className="flex space-x-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer transition-colors"
                                                >
                                                    <Image
                                                        src={relatedItem.thumbnail}
                                                        alt={relatedItem.title}
                                                        width={80}
                                                        height={60}
                                                        className="rounded object-cover"
                                                    />
                                                    <Text className="flex-1 line-clamp-2 hover:text-blue-500">
                                                        {relatedItem.title}
                                                    </Text>
                                                </div>
                                            ))}
                                        </div>
                                    </motion.div>
                                )}
                            </motion.div>
                        )}
                    </motion.div>
                </Modal>
            )}
        </AnimatePresence>
    );
};

export default NewsModal;
