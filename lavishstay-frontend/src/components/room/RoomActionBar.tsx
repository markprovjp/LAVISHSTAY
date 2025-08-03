// src/components/room/RoomActionBar.tsx
import React, { useState } from 'react';
import { Button, message, Tooltip, Space, Modal, Input, Divider, Badge } from 'antd';
import { motion } from 'framer-motion';
import {
    Heart,
    Bookmark,
    Share2,
    Copy,
    Facebook,
    Twitter,
    MessageCircle,
    Mail
} from 'lucide-react';
import { useRoomDetailStore } from '../../stores/roomDetailStore';
import { RoomTypeDetail } from '../../types/roomDetail';

interface RoomActionBarProps {
    room: RoomTypeDetail;
    className?: string;
    variant?: 'horizontal' | 'vertical';
}

const RoomActionBar: React.FC<RoomActionBarProps> = ({
    room,
    className = '',
    variant = 'horizontal'
}) => {
    const [shareModalOpen, setShareModalOpen] = useState(false);
    const { roomActions, toggleLike, toggleBookmark, initializeRoomAction } = useRoomDetailStore();

    // Initialize room action state if not exists
    React.useEffect(() => {
        initializeRoomAction(room.id);
    }, [room.id, initializeRoomAction]);

    const currentRoomAction = roomActions[room.id] || {
        roomId: room.id,
        isLiked: false,
        isBookmarked: false,
        likeCount: 0,
        bookmarkCount: 0,
    };

    const handleLike = () => {
        toggleLike(room.id);
        message.success(
            currentRoomAction.isLiked
                ? 'Đã bỏ thích phòng này'
                : 'Đã thích phòng này'
        );
    };

    const handleBookmark = () => {
        toggleBookmark(room.id);
        message.success(
            currentRoomAction.isBookmarked
                ? 'Đã bỏ lưu phòng này'
                : 'Đã lưu phòng này'
        );
    };

    const handleShare = () => {
        setShareModalOpen(true);
    };

    const currentUrl = window.location.href;
    const shareTitle = `${room.name} - LavishStay`;
    const shareDescription = room.description;

    const handleCopyLink = async () => {
        try {
            await navigator.clipboard.writeText(currentUrl);
            message.success('Đã sao chép liên kết');
        } catch (error) {
            message.error('Không thể sao chép liên kết');
        }
    };

    const handleSocialShare = (platform: string) => {
        let shareUrl = '';

        switch (platform) {
            case 'facebook':
                shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(currentUrl)}`;
                break;
            case 'twitter':
                shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(currentUrl)}&text=${encodeURIComponent(shareTitle)}`;
                break;
            case 'email':
                shareUrl = `mailto:?subject=${encodeURIComponent(shareTitle)}&body=${encodeURIComponent(`${shareDescription}\n\n${currentUrl}`)}`;
                break;
            default:
                return;
        }

        window.open(shareUrl, '_blank', 'width=600,height=400');
        setShareModalOpen(false);
    };

    const ActionButton: React.FC<{
        icon: React.ReactNode;
        label: string;
        count?: number;
        active?: boolean;
        onClick: () => void;
        tooltip: string;
    }> = ({ icon, label, count, active, onClick, tooltip }) => (
        <Tooltip title={tooltip}>
            <motion.div
                whileHover={{ scale: 1.05 }}
                whileTap={{ scale: 0.95 }}
            >
                <Badge count={count && count > 0 ? count : 0} size="small" offset={[8, 0]} style={{ background: active ? '#1677ff' : '#d9d9d9', color: '#fff', fontWeight: 600, boxShadow: '0 1px 4px #0001' }}>
                    <Button
                        type={active ? 'primary' : 'default'}
                        icon={icon}
                        onClick={onClick}
                        className={`
                            flex items-center gap-2 h-10 px-4 rounded-full border-2 transition-all duration-300
                            ${active
                                ? 'bg-blue-500 border-blue-500 text-white shadow-lg'
                                : 'bg-white dark:bg-gray-700 border-gray-200 dark:border-gray-600 hover:border-blue-400 dark:hover:border-blue-500'
                            }
                        `}
                        style={{ color: active ? '#fff' : undefined }}
                    >
                        {variant === 'horizontal' && (
                            <span className="font-medium">{label}</span>
                        )}
                    </Button>
                </Badge>
            </motion.div>
        </Tooltip>
    );

    const actions = [
        {
            icon: <Heart size={16} className={currentRoomAction.isLiked ? 'fill-current' : ''} />,
            label: 'Thích',
            count: currentRoomAction.likeCount,
            active: currentRoomAction.isLiked,
            onClick: handleLike,
            tooltip: currentRoomAction.isLiked ? 'Bỏ thích' : 'Thích phòng này'
        },
        {
            icon: <Bookmark size={16} className={currentRoomAction.isBookmarked ? 'fill-current' : ''} />,
            label: 'Lưu',
            count: currentRoomAction.bookmarkCount,
            active: currentRoomAction.isBookmarked,
            onClick: handleBookmark,
            tooltip: currentRoomAction.isBookmarked ? 'Bỏ lưu' : 'Lưu phòng này'
        },
        {
            icon: <Share2 size={16} />,
            label: 'Chia sẻ',
            count: 0,
            active: false,
            onClick: handleShare,
            tooltip: 'Chia sẻ phòng này'
        }
    ];

    return (
        <>
            <motion.div
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.5 }}
                className={`room-action-bar ${className}`}
            >
                <Space
                    direction={variant === 'vertical' ? 'vertical' : 'horizontal'}
                    size={variant === 'vertical' ? 'small' : 'middle'}
                    wrap={variant === 'horizontal'}
                >
                    {actions.map((action, index) => (
                        <motion.div
                            key={action.label}
                            initial={{ opacity: 0, x: variant === 'horizontal' ? -20 : 0, y: variant === 'vertical' ? -20 : 0 }}
                            animate={{ opacity: 1, x: 0, y: 0 }}
                            transition={{ duration: 0.3, delay: index * 0.1 }}
                        >
                            <ActionButton {...action} />
                        </motion.div>
                    ))}
                </Space>
            </motion.div>

            {/* Share Modal */}
            <Modal
                title={
                    <div className="flex items-center gap-2">
                        <Share2 size={20} />
                        <span>Chia sẻ phòng</span>
                    </div>
                }
                open={shareModalOpen}
                onCancel={() => setShareModalOpen(false)}
                footer={null}
                width={500}
                className="share-modal"
            >
                <div className="space-y-6">
                    {/* Room Info */}
                    <div className="flex gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <img
                            src={room.images[0]?.url}
                            alt={room.name}
                            className="w-16 h-16 object-cover rounded-lg"
                        />
                        <div>
                            <h3 className="font-semibold text-gray-900 dark:text-white">
                                {room.name}
                            </h3>
                            <p className="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                {room.description}
                            </p>
                        </div>
                    </div>

                    {/* Copy Link */}
                    <div>
                        <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Liên kết
                        </label>
                        <div className="flex gap-2">
                            <Input
                                value={currentUrl}
                                readOnly
                                className="flex-1"
                            />
                            <Button
                                icon={<Copy size={16} />}
                                onClick={handleCopyLink}
                                type="primary"
                                ghost
                            >
                                Sao chép
                            </Button>
                        </div>
                    </div>

                    <Divider />

                    {/* Social Share */}
                    <div>
                        <label className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            Chia sẻ qua
                        </label>
                        <div className="grid grid-cols-2 gap-3">
                            <Button
                                icon={<Facebook size={18} />}
                                onClick={() => handleSocialShare('facebook')}
                                className="h-12 flex items-center justify-center gap-2 bg-blue-600 text-white border-blue-600 hover:bg-blue-700"
                            >
                                Facebook
                            </Button>
                            <Button
                                icon={<Twitter size={18} />}
                                onClick={() => handleSocialShare('twitter')}
                                className="h-12 flex items-center justify-center gap-2 bg-sky-500 text-white border-sky-500 hover:bg-sky-600"
                            >
                                Twitter
                            </Button>
                            <Button
                                icon={<MessageCircle size={18} />}
                                onClick={() => handleSocialShare('whatsapp')}
                                className="h-12 flex items-center justify-center gap-2 bg-green-500 text-white border-green-500 hover:bg-green-600"
                            >
                                WhatsApp
                            </Button>
                            <Button
                                icon={<Mail size={18} />}
                                onClick={() => handleSocialShare('email')}
                                className="h-12 flex items-center justify-center gap-2"
                            >
                                Email
                            </Button>
                        </div>
                    </div>
                </div>
            </Modal>

            <style>{`
                .line-clamp-2 {
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    overflow: hidden;
                }
            `}</style>
        </>
    );
};

export default RoomActionBar;
