// src/pages/RoomTypesDetailsPage.tsx
import React, { useEffect, useState } from 'react';
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
import RoomActionBar from '../components/room/RoomActionBar';
import RoomRatingStats from '../components/room/RoomRatingStats';
import RoomPolicyModal from '../components/room/RoomPolicyModal';

// Store
import { useRoomDetailStore } from '../stores/roomDetailStore';

// API Interface
interface RoomDetailApiResponse {
    success: boolean;
    message?: string;
    data: {
        id: number;
        slug: string;
        code?: string;
        name: string;
        short_description?: string;
        description_html?: string;
        description_plain?: string;
        status?: string;
        capacity?: {
            adults?: number;
            children?: number;
        };
        size?: number;
        max_occupancy?: number;
        images: {
            main?: string;
            gallery: string[];
        };
        price?: {
            currency?: string;
            min_price?: number;
            max_price?: number;
            base_price?: number;
            price_by_date?: Array<{ date: string; price: number }>;
        };
        availability?: {
            total_rooms?: number;
            available_rooms?: number;
            next_available_date?: string | null;
        };
        amenities?: Array<{ id: number; name: string; slug: string; icon?: string; group?: string }>;
        policies?: Array<{ id?: number; type: string; title: string; description: string }>;
        ratings?: { average: number; count: number; distribution: Record<string, number> };
        reviews?: {
            total: number;
            page?: number;
            per_page?: number;
            items: Array<{
                id: number;
                user: { id?: number | null; name: string; avatar?: string | null };
                rating: number;
                title?: string;
                body: string;
                created_at: string;
            }>;
        };
        related_rooms?: Array<{
            id: number;
            slug: string;
            name: string;
            thumbnail?: string;
            price?: { min?: number; max?: number };
            short_description?: string;
            tags?: string[];
            score?: number;
        }>;
        meta?: { last_updated?: string | null; created_at?: string | null };
        flags?: { booking_allowed?: boolean };
        links?: { self?: string; images_base?: string };
    };
}

const RoomTypesDetailsPage: React.FC = () => {
    // Support routes using either :slug or :id (some routes use ":id")
    const params = useParams<Record<string, string | undefined>>();
    // prefer slug, fall back to id or other common param names
    const slugFromParams = params.slug || params.id || params.roomSlug || params.room_id;
    const resolvedSlug = slugFromParams ? String(slugFromParams).trim() : '';
    const navigate = useNavigate();
    const { activeTab, setActiveTab, setPolicyModalOpen } = useRoomDetailStore();

    // API Data State
    const [roomDetail, setRoomDetail] = useState<RoomDetailApiResponse['data'] | null>(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);

    // Fetch room detail data from API
    useEffect(() => {
        if (!resolvedSlug) {
            setError('Room slug is required');
            setLoading(false);
            return;
        }

        const fetchRoomDetail = async () => {
            try {
                setLoading(true);
                setError(null);

                const params = new URLSearchParams({
                    include: 'reviews,related,price_by_date',
                    locale: 'vi',
                    currency: 'VND',
                    reviews_per_page: '10',
                    related_limit: '6'
                });

                const response = await fetch(`http://localhost:8888/api/room-types/${encodeURIComponent(resolvedSlug)}?${params}`);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data: RoomDetailApiResponse = await response.json();

                if (!data.success) {
                    // ensure we throw a string message
                    const msg = (data as any)?.message || 'Failed to fetch room details';
                    throw new Error(String(msg));
                }

                setRoomDetail(data.data);
            } catch (err) {
                console.error('Error fetching room detail:', err);
                setError(err instanceof Error ? err.message : 'Failed to load room details');
            } finally {
                setLoading(false);
            }
        };

        fetchRoomDetail();
    }, [resolvedSlug]);

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

    // Transform API data for components - normalize shapes expected by our components
    const transformedRoomDetail = roomDetail ? (() => {
        // Normalize images: backend returns { main: string, gallery: string[] }
        const galleryUrls: string[] = (roomDetail.images && Array.isArray(roomDetail.images.gallery))
            ? roomDetail.images.gallery
            : (roomDetail.images && typeof roomDetail.images === 'object' && roomDetail.images.main ? [roomDetail.images.main] : []);

        const images = galleryUrls.map((url, idx) => ({
            id: String(idx + 1),
            url,
            alt: roomDetail.name,
            type: 'detail',
            order: idx
        }));

        // Map pricing
        const basePrice = roomDetail.price?.base_price ?? roomDetail.price?.min_price ?? 0;
        const originalPrice = roomDetail.price?.max_price ?? undefined;

        // Map main amenities to simple string list
        const mainAmenities = (roomDetail.amenities || []).slice(0, 6).map(a => a.name || String(a));

        // Build a plaintext description from available fields
        const descriptionPlain = roomDetail.description_plain
            ?? (roomDetail.description_html ? String(roomDetail.description_html).replace(/<[^>]+>/g, ' ') : '')
            ?? roomDetail.short_description
            ?? '';

        return {
            // keep original raw fields where useful
            ...roomDetail,
            // component-friendly fields
            basePrice,
            originalPrice,
            currency: roomDetail.price?.currency ?? 'VND',
            images,
            // Provide description fields expected by RoomDescription
            description: roomDetail.short_description ?? descriptionPlain,
            fullDescription: descriptionPlain,
            // RoomInfo expects facilities (we map amenities) and mainAmenities/tags
            facilities: roomDetail.amenities || [],
            specifications: (roomDetail as any).specifications || [],
            mainAmenities,
            tags: (roomDetail as any).tags || [],
            rating: roomDetail.ratings?.average ?? 0,
            totalReviews: roomDetail.reviews?.total ?? roomDetail.ratings?.count ?? 0,
            area: (roomDetail as any).size ?? (roomDetail as any).area ?? 0,
            maxGuests: roomDetail.max_occupancy ?? roomDetail.capacity?.adults ?? 1,
            availableRooms: roomDetail.availability?.available_rooms ?? 0,
            roomCount: roomDetail.availability?.total_rooms ?? 0,
            lastUpdated: roomDetail.meta?.last_updated ?? roomDetail.meta?.created_at ?? '',
        } as any;
    })() : null;

    // Map room-level reviews (which originate from bookings) into RoomComment[] used by RoomCommentSection
    const transformedComments = (roomDetail?.reviews?.items || []).map((review: any) => {
        // images/media may be under different keys depending on backend (media, media_urls, files)
        const images: string[] = [];
        if (Array.isArray(review.media)) {
            review.media.forEach((m: any) => {
                if (m.file_url) images.push(m.file_url);
                else if (m.url) images.push(m.url);
            });
        }
        if (Array.isArray(review.media_urls)) {
            review.media_urls.forEach((u: any) => { if (u) images.push(u); });
        }
        if (Array.isArray(review.files)) {
            review.files.forEach((f: any) => { if (f.url) images.push(f.url); });
        }

        const replies = (review.replies || []).map((r: any) => ({
            id: String(r.id ?? r.reply_id ?? Math.random()),
            userId: String(r.user?.id ?? r.user_id ?? ''),
            userName: r.user?.name ?? r.user_name ?? 'Staff',
            userAvatar: r.user?.avatar ?? r.avatar ?? undefined,
            content: r.body ?? r.content ?? r.message ?? '',
            createdAt: r.created_at ?? r.createdAt ?? new Date().toISOString(),
            isStaff: !!(r.is_staff || r.isStaff || r.is_admin || r.staff)
        }));

        return {
            id: String(review.id ?? review.review_id ?? Math.random()),
            userId: String(review.user?.id ?? review.user_id ?? ''),
            userName: review.user?.name ?? review.user_name ?? 'Khách',
            userAvatar: review.user?.avatar ?? review.user?.photo ?? undefined,
            rating: Number(review.rating ?? 0),
            title: review.title ?? review.subject ?? '',
            content: review.body ?? review.comment ?? review.comment_text ?? '',
            images,
            helpful: Number(review.helpful ?? review.helpful_count ?? 0),
            createdAt: review.created_at ?? review.createdAt ?? new Date().toISOString(),
            updatedAt: review.updated_at ?? review.updatedAt,
            isVerified: !!(review.user && review.user.id),
            stayDuration: review.stay_duration ?? review.stayDuration,
            roomNumber: review.room_number ?? review.roomNumber,
            replies,
        } as any;
    });

    // Debug: log reviews mapping for runtime inspection
    useEffect(() => {
        if (roomDetail) {
            // eslint-disable-next-line no-console
            console.debug('RoomDetail.reviews:', roomDetail.reviews);
            // eslint-disable-next-line no-console
            console.debug('transformedComments:', transformedComments);
        }
    }, [roomDetail]);

    const transformedRelatedRooms = roomDetail?.related_rooms?.map(r => ({
        id: r.id,
        slug: r.slug,
        name: r.name,
        basePrice: r.price?.min ?? 0,
        originalPrice: r.price?.max ?? undefined,
        mainImage: r.thumbnail ?? null,
        rating: 0,
        totalReviews: 0,
        area: 0,
        maxGuests: 0,
        mainAmenities: r.tags ?? [],
        isPopular: false,
        availableRooms: 0,
    })) || [];

    // Normalize API ratings into the shape consumed by RoomRatingStats
    const ratingStats = roomDetail && roomDetail.ratings ? {
        // overall / average
        overall: roomDetail.ratings.average ?? 0,
        average: roomDetail.ratings.average ?? 0,
        // totals
        totalReviews: roomDetail.ratings.count ?? 0,
        total: roomDetail.ratings.count ?? 0,
        count: roomDetail.ratings.count ?? 0,
        // distribution expected by the component
        ratingDistribution: roomDetail.ratings.distribution ?? {},
        // compatibility aliases
        ratingDistributionAlt: roomDetail.ratings.distribution ?? {},
        distribution: roomDetail.ratings.distribution ?? {},
        breakdown: roomDetail.ratings.distribution ?? {},
        // category-level scores (not present in API by default) - provide sensible defaults
        cleanliness: (roomDetail.ratings as any).cleanliness ?? 0,
        comfort: (roomDetail.ratings as any).comfort ?? 0,
        location: (roomDetail.ratings as any).location ?? 0,
        facilities: (roomDetail.ratings as any).facilities ?? 0,
        staff: (roomDetail.ratings as any).staff ?? 0,
        valueForMoney: (roomDetail.ratings as any).valueForMoney ?? (roomDetail.ratings as any).value_for_money ?? 0,
    } as any : null;

    // Error state
    if (error) {
        return (
            <div className="min-h-screen flex items-center justify-center p-4">
                <Alert
                    message="Lỗi tải dữ liệu"
                    description={error}
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
    if (loading) {
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

    if (!transformedRoomDetail) {
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
                                        title: transformedRoomDetail.name,
                                    },
                                ]}
                            />
                        </div>

                        <RoomActionBar room={transformedRoomDetail as any} variant="horizontal" />
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
                            images={transformedRoomDetail.images as any}
                            roomName={transformedRoomDetail.name}
                        />
                    </motion.div>

                    {/* Content Grid - Removed booking sidebar */}
                    <div className="max-w-5xl mx-auto space-y-8">
                        {/* Room Info */}
                        <RoomInfo room={transformedRoomDetail as any} />

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
                                                <RoomDescription room={transformedRoomDetail as any} />
                                                <RoomFacilities facilities={transformedRoomDetail.facilities as any} />
                                            </div>
                                        ),
                                    },
                                    {
                                        key: 'reviews',
                                        label: (
                                            <span className="flex items-center gap-2">
                                                <MessageSquare size={16} />
                                                Đánh giá ({roomDetail?.reviews?.total || 0})
                                            </span>
                                        ),
                                        children: (
                                            <div className="space-y-8">
                                                {ratingStats && (
                                                    <RoomRatingStats stats={ratingStats as any} />
                                                )}
                                                <RoomCommentSection
                                                    comments={transformedComments as any}
                                                    loading={false}
                                                    totalComments={roomDetail?.reviews?.total || 0}
                                                    hasMore={false}
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
                                                rooms={transformedRelatedRooms as any}
                                                loading={false}
                                                currentRoomId={String(transformedRoomDetail.id) as any}
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
                </div>
            </div>

            {/* Policy Modal */}
            <RoomPolicyModal
                policies={transformedRoomDetail.policies as any}
                roomName={transformedRoomDetail.name}
            />
        </div>
    );
};

export default RoomTypesDetailsPage;