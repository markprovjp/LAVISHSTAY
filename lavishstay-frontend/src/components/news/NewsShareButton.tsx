// src/components/news/NewsShareButton.tsx
import React from 'react';
import { Button, Dropdown, Tooltip, message } from 'antd';
import { motion } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import {
    ShareAltOutlined,
    FacebookOutlined,
    TwitterOutlined,
    LinkedinOutlined,
    WhatsAppOutlined,
    CopyOutlined,
    MailOutlined
} from '@ant-design/icons';
import type { MenuProps } from 'antd';

interface NewsShareButtonProps {
    newsId: string;
    title: string;
    url?: string;
    description?: string;
    size?: 'small' | 'middle' | 'large';
    type?: 'default' | 'text' | 'link';
}

const NewsShareButton: React.FC<NewsShareButtonProps> = ({
    newsId,
    title,
    url = window.location.href,
    description = '',
    size = 'small',
    type = 'text'
}) => {
    const { t } = useTranslation();

    const encodedUrl = encodeURIComponent(url);
    const encodedTitle = encodeURIComponent(title);
    const encodedDescription = encodeURIComponent(description);

    const copyToClipboard = async () => {
        try {
            await navigator.clipboard.writeText(`${title}\n${url}`);
            message.success(t('news.share.copied', 'Đã sao chép liên kết'));
        } catch (error) {
            console.error('Failed to copy:', error);
            message.error(t('news.share.copyFailed', 'Không thể sao chép liên kết'));
        }
    };

    const shareToFacebook = () => {
        const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}&quote=${encodedTitle}`;
        window.open(facebookUrl, '_blank', 'width=600,height=400');
    };

    const shareToTwitter = () => {
        const twitterUrl = `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedTitle}`;
        window.open(twitterUrl, '_blank', 'width=600,height=400');
    };

    const shareToLinkedIn = () => {
        const linkedinUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}`;
        window.open(linkedinUrl, '_blank', 'width=600,height=400');
    };

    const shareToWhatsApp = () => {
        const whatsappUrl = `https://wa.me/?text=${encodedTitle}%0A${encodedUrl}`;
        window.open(whatsappUrl, '_blank');
    };

    const shareByEmail = () => {
        const emailUrl = `mailto:?subject=${encodedTitle}&body=${encodedDescription}%0A%0A${encodedUrl}`;
        window.location.href = emailUrl;
    };

    const handleNativeShare = async () => {
        if (navigator.share) {
            try {
                await navigator.share({
                    title: title,
                    text: description,
                    url: url,
                });
            } catch (error) {
                console.error('Native share failed:', error);
                // Fallback to dropdown menu
            }
        }
    };

    const shareItems: MenuProps['items'] = [
        {
            key: 'facebook',
            icon: <FacebookOutlined className="text-blue-600" />,
            label: (
                <span className="flex items-center space-x-2">
                    <span>Facebook</span>
                </span>
            ),
            onClick: shareToFacebook,
        },
        {
            key: 'twitter',
            icon: <TwitterOutlined className="text-blue-400" />,
            label: (
                <span className="flex items-center space-x-2">
                    <span>Twitter</span>
                </span>
            ),
            onClick: shareToTwitter,
        },
        {
            key: 'linkedin',
            icon: <LinkedinOutlined className="text-blue-700" />,
            label: (
                <span className="flex items-center space-x-2">
                    <span>LinkedIn</span>
                </span>
            ),
            onClick: shareToLinkedIn,
        },
        {
            key: 'whatsapp',
            icon: <WhatsAppOutlined className="text-green-500" />,
            label: (
                <span className="flex items-center space-x-2">
                    <span>WhatsApp</span>
                </span>
            ),
            onClick: shareToWhatsApp,
        },
        {
            key: 'email',
            icon: <MailOutlined className="text-gray-600" />,
            label: (
                <span className="flex items-center space-x-2">
                    <span>Email</span>
                </span>
            ),
            onClick: shareByEmail,
        },
        {
            type: 'divider',
        },
        {
            key: 'copy',
            icon: <CopyOutlined className="text-gray-500" />,
            label: (
                <span className="flex items-center space-x-2">
                    <span>{t('news.share.copyLink', 'Sao chép liên kết')}</span>
                </span>
            ),
            onClick: copyToClipboard,
        },
    ];

    const handleClick = (e: React.MouseEvent) => {
        e.stopPropagation(); // Prevent card click

        // Try native share first on mobile
        if (navigator.share && /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
            handleNativeShare();
        }
    };

    return (
        <Dropdown
            menu={{ items: shareItems }}
            trigger={['click']}
            placement="bottomLeft"
            overlayClassName="news-share-dropdown"
        >
            <Tooltip title={t('news.share.title', 'Chia sẻ')}>
                <motion.div
                    whileHover={{ scale: 1.1 }}
                    whileTap={{ scale: 0.9 }}
                >
                    <Button
                        type={type}
                        size={size}
                        icon={
                            <motion.div
                                whileHover={{ rotate: 15 }}
                                transition={{ duration: 0.2 }}
                            >
                                <ShareAltOutlined className="text-gray-500 hover:text-blue-500" />
                            </motion.div>
                        }
                        onClick={handleClick}
                        className="border-none shadow-none hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-500 hover:text-blue-500"
                    />
                </motion.div>
            </Tooltip>
        </Dropdown>
    );
};

export default NewsShareButton;
