// src/components/news/CommentForm.tsx
import React, { useState } from 'react';
import { Form, Input, Button, Avatar, Space, message } from 'antd';
import { UserOutlined, SendOutlined } from '@ant-design/icons';
import { useTranslation } from 'react-i18next';
import { useCreateComment } from '../../hooks/useNews';

const { TextArea } = Input;

interface CommentFormProps {
    newsId: number;
    parentId?: number; // For reply comments
    onCommentAdded?: () => void;
    onCancel?: () => void;
    placeholder?: string;
}

const CommentForm: React.FC<CommentFormProps> = ({
    newsId,
    parentId,
    onCommentAdded,
    onCancel,
    placeholder
}) => {
    const { t } = useTranslation();
    const [form] = Form.useForm();
    const [isSubmitting, setIsSubmitting] = useState(false);

    const createCommentMutation = useCreateComment();

    const handleSubmit = async (values: { content: string }) => {
        if (!values.content.trim()) {
            message.warning(t('news.comments.form.contentRequired', 'Vui lòng nhập nội dung bình luận'));
            return;
        }

        setIsSubmitting(true);

        try {
            await createCommentMutation.mutateAsync({
                content: values.content.trim(),
                parent_id: parentId
            });

            message.success(
                parentId
                    ? t('news.comments.form.replySuccess', 'Trả lời thành công!')
                    : t('news.comments.form.commentSuccess', 'Bình luận thành công!')
            );

            form.resetFields();
            onCommentAdded?.();

        } catch (error) {
            message.error(
                parentId
                    ? t('news.comments.form.replyError', 'Không thể gửi trả lời')
                    : t('news.comments.form.commentError', 'Không thể gửi bình luận')
            );
        } finally {
            setIsSubmitting(false);
        }
    };

    return (
        <div className="comment-form">
            <div className="flex items-start space-x-3">
                <Avatar icon={<UserOutlined />} />

                <div className="flex-1">
                    <Form
                        form={form}
                        onFinish={handleSubmit}
                        className="w-full"
                    >
                        <Form.Item
                            name="content"
                            rules={[
                                { required: true, message: t('news.comments.form.contentRequired', 'Vui lòng nhập nội dung bình luận') },
                                { min: 5, message: t('news.comments.form.contentTooShort', 'Bình luận phải có ít nhất 5 ký tự') },
                                { max: 1000, message: t('news.comments.form.contentTooLong', 'Bình luận không được quá 1000 ký tự') }
                            ]}
                        >
                            <TextArea
                                rows={parentId ? 3 : 4}
                                placeholder={
                                    placeholder ||
                                    (parentId
                                        ? t('news.comments.form.replyPlaceholder', 'Viết trả lời...')
                                        : t('news.comments.form.commentPlaceholder', 'Chia sẻ suy nghĩ của bạn về bài viết này...')
                                    )
                                }
                                showCount
                                maxLength={1000}
                            />
                        </Form.Item>

                        <div className="flex justify-between items-center">
                            <div className="text-xs text-gray-500">
                                {t('news.comments.form.guidelines', 'Vui lòng giữ bình luận tích cực và tôn trọng người khác.')}
                            </div>

                            <Space>
                                {onCancel && (
                                    <Button onClick={onCancel}>
                                        {t('common.cancel', 'Hủy')}
                                    </Button>
                                )}

                                <Button
                                    type="primary"
                                    htmlType="submit"
                                    icon={<SendOutlined />}
                                    loading={isSubmitting}
                                    disabled={isSubmitting}
                                >
                                    {parentId
                                        ? t('news.comments.form.reply', 'Trả lời')
                                        : t('news.comments.form.comment', 'Bình luận')
                                    }
                                </Button>
                            </Space>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    );
};

export default CommentForm;
