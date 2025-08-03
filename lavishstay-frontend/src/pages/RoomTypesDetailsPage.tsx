// src/pages/RoomTypesDetailsPage.tsx
import React, { useEffect } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import { Tabs, Skeleton, Alert, Button, Breadcrumb, Space } from 'antd';
import { motion } from 'framer-motion';
import {
    ArrowLeft,
    Home,
    Building,
    Info,
    MessageSquare,
    Star,
    Settings,
    FileText
} from 'lucide-react';

// Components
import RoomGallery from '../components/room/RoomGallery';
import RoomInfo from '../components/room/RoomInfo';
import RoomFacilities from '../components/room/RoomFacilities';
import RoomDescription from '../components/room/RoomDescription';
import RoomCommentSection from '../components/room/RoomCommentSection';
import RoomRelated from '../components/room/RoomRelated';
import RoomBookingBar from '../components/room/RoomBookingBar';
import RoomPolicyModal from '../components/room/RoomPolicyModal';
import RoomActionBar from '../components/room/RoomActionBar';
import RoomRatingStats from '../components/room/RoomRatingStats';

// API & Store
import {
    useRoomDetail,
    useRoomComments,
    useRelatedRooms,
    useRoomRatingStats
} from '../api/roomDetailApi';
import { useRoomDetailStore } from '../stores/roomDetailStore';

const RoomTypesDetailsPage: React.FC = () => {
    const { slug } = useParams<{ slug: string }>();
    const navigate = useNavigate();
    const { activeTab, setActiveTab, setPolicyModalOpen } = useRoomDetailStore();

    // Simulate room ID from slug (in real app, you might need to resolve this)
    const roomId = slug || '1'; // Use slug or fallback to mock room ID

    // API Queries
    const {
        data: roomDetail,
        isLoading: roomLoading,
        error: roomError
    } = useRoomDetail(roomId);

    const {
        data: commentsData,
        isLoading: commentsLoading
    } = useRoomComments(roomId);

    const {
        data: relatedRooms,
        isLoading: relatedLoading
    } = useRelatedRooms(roomId);

    const {
        data: ratingStats,
        isLoading: statsLoading
    } = useRoomRatingStats(roomId);

    // Set page title
    useEffect(() => {
        if (roomDetail) {
            document.title = `${roomDetail.name} - LavishStay`;
        }
    }, [roomDetail]);

    // Handle back navigation
    const handleGoBack = () => {
        navigate(-1);
    };

    const handleGoHome = () => {
        navigate('/');
    };

    const handleViewPolicies = () => {
        setPolicyModalOpen(true);
    };

    // Error state
    if (roomError) {
        return (
            <div className="min-h-screen flex items-center justify-center p-4">
                <Alert
                    message="Lỗi tải dữ liệu"
                    description="Không thể tải thông tin phòng. Vui lòng thử lại sau."
                    type="error"
                    showIcon
                    action={
                        <Space>
                            <Button size="small" onClick={() => window.location.reload()}>
                                Thử lại
                            </Button>
                            <Button size="small" type="primary" onClick={handleGoBack}>
                                Quay lại
                            </Button>
                        </Space>
                    }
                />
            </div>
        );
    }

    // Loading state
    if (roomLoading) {
        return (
            <div className="min-h-screen bg-gray-50 dark:bg-gray-900">
                <div className="container mx-auto px-4 py-8">
                    <div className="max-w-7xl mx-auto space-y-8">
                        <Skeleton.Image className="w-full h-96 rounded-2xl" />
                        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <div className="lg:col-span-2 space-y-6">
                                <Skeleton active paragraph={{ rows: 4 }} />
                                <Skeleton active paragraph={{ rows: 6 }} />
                                <Skeleton active paragraph={{ rows: 8 }} />
                            </div>
                            <div className="space-y-4">
                                <Skeleton active paragraph={{ rows: 6 }} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }

    if (!roomDetail) {
        return (
            <div className="min-h-screen flex items-center justify-center p-4">
                <Alert
                    message="Không tìm thấy phòng"
                    description="Phòng bạn đang tìm kiếm không tồn tại hoặc đã bị xóa."
                    type="warning"
                    showIcon
                    action={
                        <Button type="primary" onClick={handleGoBack}>
                            Quay lại
                        </Button>
                    }
                />
            </div>
        );
    }

    return (
        <div className=" bg-gray-50 dark:bg-gray-900">
            {/* Breadcrumb & Navigation */}
            <div className="sticky top-0 z-40 bg-white/95 dark:bg-gray-800/95 backdrop-blur-sm border-b border-gray-200 dark:border-gray-700">
                <div className="container mx-auto px-4 py-4">
                    <div className="flex items-center justify-between mt-10">
                        <div className="flex items-center gap-4">
                            <Button
                                type="text"
                                icon={<ArrowLeft size={18} />}
                                onClick={handleGoBack}
                                className="hover:bg-gray-100 dark:hover:bg-gray-700"
                            >
                                Quay lại
                            </Button>

                            <Breadcrumb
                                items={[
                                    {
                                        key: 'home',
                                        title: (
                                            <span className="flex items-center gap-1 cursor-pointer" onClick={handleGoHome}>
                                                <Home size={14} />
                                                Trang chủ
                                            </span>
                                        ),
                                    },
                                    {
                                        key: 'current',
                                        title: roomDetail.name,
                                    },
                                ]}
                            />
                        </div>

                        <RoomActionBar room={roomDetail} variant="horizontal" />
                    </div>
                </div>
            </div>

            {/* Main Content */}
            <div className="container mx-auto px-4 py-8">
                <div className="max-w-7xl mx-auto">
                    {/* Gallery */}
                    <motion.div
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.6 }}
                        className="mb-8"
                    >
                        <RoomGallery
                            images={roomDetail.images}
                            roomName={roomDetail.name}
                        />
                    </motion.div>

                    {/* Content Grid */}
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {/* Main Content */}
                        <div className="lg:col-span-2 space-y-8">
                            {/* Room Info */}
                            <RoomInfo room={roomDetail} />

                            {/* Tabs Content */}
                            <motion.div
                                initial={{ opacity: 0, y: 20 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.6, delay: 0.2 }}
                            >
                                <Tabs
                                    activeKey={activeTab}
                                    onChange={setActiveTab}
                                    size="large"
                                    className="room-detail-tabs"
                                    items={[
                                        {
                                            key: 'overview',
                                            label: (
                                                <span className="flex items-center gap-2">
                                                    <Info size={16} />
                                                    Tổng quan
                                                </span>
                                            ),
                                            children: (
                                                <div className="space-y-8">
                                                    <RoomDescription room={roomDetail} />
                                                    <RoomFacilities facilities={roomDetail.facilities} />
                                                </div>
                                            ),
                                        },
                                        {
                                            key: 'reviews',
                                            label: (
                                                <span className="flex items-center gap-2">
                                                    <MessageSquare size={16} />
                                                    Đánh giá ({commentsData?.total || 0})
                                                </span>
                                            ),
                                            children: (
                                                <div className="space-y-8">
                                                    {ratingStats && !statsLoading && (
                                                        <RoomRatingStats stats={ratingStats} />
                                                    )}
                                                    <RoomCommentSection
                                                        comments={commentsData?.comments || []}
                                                        loading={commentsLoading}
                                                        totalComments={commentsData?.total || 0}
                                                        hasMore={commentsData?.hasMore || false}
                                                    />
                                                </div>
                                            ),
                                        },
                                        {
                                            key: 'similar',
                                            label: (
                                                <span className="flex items-center gap-2">
                                                    <Star size={16} />
                                                    Phòng tương tự
                                                </span>
                                            ),
                                            children: (
                                                <RoomRelated
                                                    rooms={relatedRooms || []}
                                                    loading={relatedLoading}
                                                    currentRoomId={roomDetail.id}
                                                />
                                            ),
                                        },
                                        {
                                            key: 'policies',
                                            label: (
                                                <span className="flex items-center gap-2">
                                                    <FileText size={16} />
                                                    Chính sách
                                                </span>
                                            ),
                                            children: (
                                                <div className="text-center py-12">
                                                    <Settings size={48} className="mx-auto text-gray-400 mb-4" />
                                                    <h3 className="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                                        Chính sách phòng
                                                    </h3>
                                                    <p className="text-gray-600 dark:text-gray-400 mb-6">
                                                        Xem chi tiết các chính sách và quy định của phòng
                                                    </p>
                                                    <Button
                                                        type="primary"
                                                        size="large"
                                                        onClick={handleViewPolicies}
                                                        icon={<FileText size={18} />}
                                                    >
                                                        Xem chính sách
                                                    </Button>
                                                </div>
                                            ),
                                        },
                                    ]}
                                />
                            </motion.div>
                        </div>

                        {/* Booking Sidebar */}
                        <div className="lg:col-span-1">
                            <motion.div
                                initial={{ opacity: 0, x: 20 }}
                                animate={{ opacity: 1, x: 0 }}
                                transition={{ duration: 0.6, delay: 0.4 }}
                            >
                                <RoomBookingBar
                                    room={roomDetail}
                                    position="sticky"
                                />
                            </motion.div>
                        </div>
                    </div>
                </div>
            </div>

            {/* Policy Modal */}
            <RoomPolicyModal
                policies={roomDetail.policies}
                roomName={roomDetail.name}
            />

          
        </div>
    );
};

export default RoomTypesDetailsPage;