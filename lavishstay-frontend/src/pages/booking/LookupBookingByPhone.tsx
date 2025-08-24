import React, { useState, useCallback, useMemo, useEffect } from 'react';
import {
    Card,
    Form,
    Input,
    Button,
    Table,
    Drawer,
    Descriptions,
    Tag,
    Typography,
    Space,
    Empty,
    message,
    Divider,
    Row,
    Col,
    Alert,
    Badge,
    Tabs,
    DatePicker,
    Select,
    Modal,
    Skeleton,
    Tooltip,
    Dropdown,
    MenuProps
} from 'antd';
import {
    SearchOutlined,
    PhoneOutlined,
    UserOutlined,
    CalendarOutlined,
    DollarOutlined,
    HomeOutlined,
    InfoCircleOutlined,
    EyeOutlined,
    CloseCircleOutlined,
    ExclamationCircleOutlined,
    ClockCircleOutlined,
    CheckCircleOutlined,
    SafetyOutlined,
    MoreOutlined,
    StopOutlined,
    EditOutlined,
    CommentOutlined,
    GiftOutlined
} from '@ant-design/icons';
import { CopyOutlined } from '@ant-design/icons';
import dayjs from 'dayjs';
import { roomTypesAPI, bookingsAPI, couponAPI } from '../../utils/api';
import axiosInstance from '../../utils/api';
import bookingService from '../../services/bookingService';
import { BookingSummary, BookingDetail, SearchResponse, SearchFilters } from '../../types/booking';

// Extended interface for booking with coupon data
interface ExtendedBookingSummary extends BookingSummary {
    coupon_data?: {
        coupon_code: string;
        amount_saved: number;
        redeemed_at: string;
        coupon: {
            code: string;
            description: string;
            type: 'percent' | 'fixed';
            value: number;
        };
    };
}

const { Title, Text } = Typography;
const { Item } = Descriptions;
const { RangePicker } = DatePicker;

const LookupBookingByPhone: React.FC = () => {
    const [form] = Form.useForm();

    // helper to trim microseconds from ISO timestamps like 2025-08-23T17:00:00.000000Z
    const sanitizeIso = (s?: string | null) => {
        if (!s || typeof s !== 'string') return '';
        try {
            return s.replace(/\\.\\d+Z$/, 'Z');
        } catch (e) {
            return '';
        }
    };

    // Format number to VND without decimals. Example: 3384000 -> "3.384.000" 
    const formatVNDPlain = (amount: number | string | undefined | null): string => {
        const n = Number(amount) || 0;
        // use toLocaleString with en-US to get comma thousands, then replace commas with dots, no decimals
        const formatted = n.toLocaleString('en-US', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
        return formatted.replace(/,/g, '.');
    };

    const formatVNDCurrency = (amount: number | string | undefined | null): string => {
        return `${formatVNDPlain(amount)} VNĐ`;
    };

    const formatCouponSaved = (amount: number | string | undefined | null): string => {
        const n = Number(amount) || 0;
        // use same thousands formatting as other amounts, no decimals
        const formatted = formatVNDPlain(n);
        return `-${formatted}₫`;
    };

    const formatCheckInDisplay = (iso?: string | null) => {
        if (!iso) return '-';
        const d = dayjs(sanitizeIso(iso));
        if (!d.isValid()) return '-';
        return `${d.format('YYYY-MM-DD')} 14:00`;
    };

    const formatCheckOutDisplay = (iso?: string | null) => {
        if (!iso) return '-';
        const d = dayjs(sanitizeIso(iso));
        if (!d.isValid()) return '-';
        return `${d.format('YYYY-MM-DD')} 12:00`;
    };

    // Helper to ensure array response
    const ensureArray = (data: any): any[] => {
        if (Array.isArray(data)) return data;
        if (data && typeof data === 'object') {
            if (data.data && Array.isArray(data.data)) return data.data;
            return Object.values(data).filter(item => item && typeof item === 'object');
        }
        return [];
    };

    // Core state
    const [loading, setLoading] = useState(false);
    const [initialLoading, setInitialLoading] = useState(true);
    const [detailLoading, setDetailLoading] = useState(false);
    const [bookings, setBookings] = useState<ExtendedBookingSummary[]>([]);
    // Client-side filtered view
    const [filteredBookings, setFilteredBookings] = useState<ExtendedBookingSummary[]>([]);
    const [currentPage, setCurrentPage] = useState<number>(1);
    const [searchMeta, setSearchMeta] = useState<SearchResponse['meta'] | null>(null);
    const [selectedBooking, setSelectedBooking] = useState<BookingDetail | null>(null);
    const [drawerVisible, setDrawerVisible] = useState(false);

    // Detail cache and coupon cache
    const [detailCache, setDetailCache] = useState<Record<number, BookingDetail>>({});
    const [, setCouponMap] = useState<Record<string, any>>({});

    // Filter states
    const [activeTab, setActiveTab] = useState('all');
    const [searchFilters, setSearchFilters] = useState<SearchFilters>({});

    // Action states (Cancel, Extend, Reschedule, Review)
    const [cancelPolicy, setCancelPolicy] = useState<any | null>(null);
    const [cancelLoading, setCancelLoading] = useState(false);
    const [cancelBookingId, setCancelBookingId] = useState<number | null>(null);
    const [cancelConfirming, setCancelConfirming] = useState(false);

    const [extendPolicy, setExtendPolicy] = useState<any | null>(null);
    const [extendLoading, setExtendLoading] = useState(false);
    const [extendBookingId, setExtendBookingId] = useState<number | null>(null);
    const [extendConfirming, setExtendConfirming] = useState(false);
    const [extendDate, setExtendDate] = useState<dayjs.Dayjs | null>(null);

    const [rescheduleModalVisible, setRescheduleModalVisible] = useState(false);


    const [reviewEligibility, setReviewEligibility] = useState<Record<number, { eligible: boolean; reason?: string }>>({});

    // Available room types for filter (if API supports it)
    const [roomTypes, setRoomTypes] = useState<string[]>([]);

    // Debounced search
    const [searchDebounceTimer, setSearchDebounceTimer] = useState<NodeJS.Timeout | null>(null);

    // Load user bookings on mount (authenticated endpoint)
    const loadUserBookings = useCallback(async (filters: SearchFilters = {}, page = 1) => {
        setLoading(true);
        try {
            const params: any = {
                page,
                per_page: 20
            };

            // Add filters if present
            if (filters.search?.trim()) params.search = filters.search.trim();
            if (filters.status && filters.status !== 'all') params.status = filters.status;
            if (filters.from) params.from = filters.from;
            if (filters.to) params.to = filters.to;
            if (filters.room_type) params.room_type = filters.room_type;

            // Call authenticated endpoint and coupon API in parallel
            const [bookingsResponse, couponsResponse] = await Promise.allSettled([
                bookingsAPI.getByUser(params),
                couponAPI.getMyRedemptions()
            ]);

            let bookingsList: any[] = [];
            let couponsList: any[] = [];

            // Handle bookings response
            if (bookingsResponse.status === 'fulfilled') {
                const bookingsData = bookingsResponse.value.data;
                // Backend may return the list as { data: [...] } (pagination),
                // or { bookings: [...] } (custom response), or raw array.
                bookingsList = ensureArray(
                    bookingsData.data || bookingsData.bookings || bookingsData
                );

                // Set meta if available
                if (bookingsData.meta) {
                    setSearchMeta(bookingsData.meta);
                } else {
                    setSearchMeta({
                        page: 1,
                        per_page: 20,
                        total: bookingsList.length,
                        last_page: 1,
                        search_type: 'user_bookings',
                        search_term: filters.search || ''
                    });
                }
            } else {
                console.error('Failed to load bookings:', bookingsResponse.reason);
                if (bookingsResponse.reason?.response?.status === 403) {
                    message.error('Bạn không có quyền xem thông tin booking này');
                } else {
                    message.error('Không thể tải danh sách booking. Vui lòng thử lại!');
                }
            }

            // Handle coupons response
            if (couponsResponse.status === 'fulfilled') {
                const couponsData = couponsResponse.value.data;
                couponsList = ensureArray(couponsData.data || couponsData);
            } else {
                console.warn('Failed to load coupons (non-fatal):', couponsResponse.reason);
            }

            // Build coupon map by booking_code
            const newCouponMap: Record<string, any> = {};
            couponsList.forEach((coupon: any) => {
                if (coupon.booking_code) {
                    newCouponMap[coupon.booking_code] = coupon;
                }
            });
            setCouponMap(newCouponMap);

            // Merge booking data with coupon data
            const mergedBookings = bookingsList.map((booking: any) => ({
                ...booking,
                coupon_data: newCouponMap[booking.booking_code] || null
            }));

            setBookings(mergedBookings);

            if (mergedBookings.length === 0 && Object.keys(filters).length > 0) {
                message.info('Không tìm thấy booking nào với tiêu chí này');
            }

        } catch (error: any) {
            console.error('Load bookings error:', error);
            if (error.response?.status === 403) {
                message.error('Bạn không có quyền xem thông tin booking này');
            } else {
                message.error('Đã xảy ra lỗi khi tải danh sách booking. Vui lòng thử lại!');
            }
        } finally {
            setLoading(false);
            setInitialLoading(false);
        }
    }, []);

    // Search bookings with filters (use authenticated endpoint)
    const handleSearch = useCallback(async (filters: SearchFilters = {}, page = 1) => {
        const searchParams = { ...searchFilters, ...filters, page };

        // Use authenticated endpoint for search
        await loadUserBookings(searchParams, page);
    }, [searchFilters, loadUserBookings]);

    // Get booking detail (lazy load + cache)
    const handleViewDetail = useCallback(async (bookingId: number) => {
        // Check cache first
        if (detailCache[bookingId]) {
            setSelectedBooking(detailCache[bookingId]);
            setDrawerVisible(true);
            return;
        }

        setDetailLoading(true);
        try {
            const response = await axiosInstance.get<{ success: boolean; data: BookingDetail }>(`/public/bookings/${bookingId}/detail`);

            if (response.data.success) {
                const detail = response.data.data;

                // Cache the detail
                setDetailCache(prev => ({ ...prev, [bookingId]: detail }));

                // Merge detail fields into booking list
                setBookings(prev => prev.map(b =>
                    b.booking_id === bookingId
                        ? {
                            ...b,
                            rooms_detail: detail.rooms_detail,
                            booking_rooms: detail.booking_rooms,
                            payments: detail.payments,
                            representatives: detail.representatives,
                            total_price_formatted: detail.total_price_formatted,
                            total_price_raw: detail.total_price_raw,
                            notes: detail.notes,
                            updated_at: detail.updated_at
                        }
                        : b
                ));

                setSelectedBooking(detail);
                setDrawerVisible(true);
            } else {
                message.error('Không thể lấy thông tin chi tiết booking');
            }
        } catch (error) {
            console.error('Detail error:', error);
            message.error('Đã xảy ra lỗi khi lấy thông tin chi tiết');
        } finally {
            setDetailLoading(false);
        }
    }, [detailCache]);

    // Handle pagination
    const handleTableChange = (pagination: any) => {
        handleSearch({}, pagination.current);
    };

    // Status helpers
    const getStatusColor = useCallback((status: string) => {
        const colorMap: Record<string, string> = {
            'Pending': 'orange',
            'pending': 'orange',
            'Confirmed': 'blue',
            'confirmed': 'blue',
            'Operational': 'cyan',
            'operational': 'cyan',
            'Completed': 'green',
            'completed': 'green',
            'Cancelled': 'red',
            'cancelled': 'red',
            'Cancelled With Penalty': 'red',
            'Unsuccessful': 'red'
        };
        return colorMap[status] || 'default';
    }, []);

    const getStatusText = useCallback((status: string) => {
        const textMap: Record<string, string> = {
            'Pending': 'Chờ thanh toán',
            'pending': 'Chờ thanh toán',
            'Confirmed': 'Đã thanh toán',
            'confirmed': 'Đã thanh toán',
            'Operational': 'Đang ở',
            'operational': 'Đang ở',
            'Completed': 'Hoàn thành',
            'completed': 'Hoàn thành',
            'Cancelled': 'Đã hủy',
            'cancelled': 'Đã hủy',
            'Cancelled With Penalty': 'Hủy có phí',
            'Unsuccessful': 'Không thành công'
        };
        return textMap[status] || status;
    }, []);

    const getStatusIcon = useCallback((status?: string) => {
        const lowerStatus = typeof status === 'string' ? status.toLowerCase() : '';
        switch (lowerStatus) {
            case 'confirmed': return <CheckCircleOutlined />;
            case 'completed': return <CheckCircleOutlined />;
            case 'cancelled': return <CloseCircleOutlined />;
            case 'pending': return <ExclamationCircleOutlined />;
            default: return <ClockCircleOutlined />;
        }
    }, []);

    // Coupon display helper with new design
    const getCouponDisplay = (booking: ExtendedBookingSummary) => {
        if (!booking.coupon_data) {
            return <Text type="secondary" style={{ fontSize: 12 }}>Không có mã giảm giá</Text>;
        }

        const { coupon_data } = booking;
        const handleCopy = async () => {
            try {
                await navigator.clipboard.writeText(coupon_data.coupon_code);
                message.success('Đã sao chép mã giảm giá');
            } catch (err) {
                message.error('Không thể sao chép mã giảm giá');
            }
        };

        return (
            <div style={{ display: 'flex', flexDirection: 'column', gap: 4 }}>
                <div style={{ display: 'flex', alignItems: 'center', gap: 8 }}>
                    <Tag color="magenta" style={{ fontSize: 12, fontWeight: 700 }}>
                        {coupon_data.coupon_code}
                    </Tag>
                    <Tooltip title="Sao chép mã">
                        <Button size="small" icon={<CopyOutlined />} onClick={handleCopy} />
                    </Tooltip>
                </div>
                <Text style={{ color: '#cf1322', fontSize: 12, fontWeight: 700 }}>{formatCouponSaved(coupon_data.amount_saved)}</Text>
            </div>
        );
    };

    // Load initial data on mount
    useEffect(() => {
        loadUserBookings();
    }, [loadUserBookings]);

    // Action handlers
    const handleCancelBooking = useCallback(async (booking: BookingSummary) => {
        setCancelLoading(true);
        setCancelPolicy(null);
        setCancelBookingId(booking.booking_id);
        setDrawerVisible(false);

        try {
            const policy = await bookingService.getCancelPolicy(booking.booking_id);
            setCancelPolicy(policy);
        } catch (err: any) {
            Modal.error({
                title: 'Không thể lấy chính sách huỷ',
                content: err?.message || 'Đã có lỗi xảy ra',
            });
        } finally {
            setCancelLoading(false);
        }
    }, []);

    const handleConfirmCancel = useCallback(async () => {
        if (!cancelBookingId) return;
        setCancelConfirming(true);
        try {
            await bookingService.confirmCancelBooking(cancelBookingId);
            Modal.success({
                title: 'Huỷ đặt phòng thành công',
                content: 'Đặt phòng đã được huỷ. Danh sách đã được cập nhật.',
            });
            setBookings(prev => prev.filter(b => b.booking_id !== cancelBookingId));
            setCancelPolicy(null);
            setCancelBookingId(null);
        } catch (err: any) {
            Modal.error({
                title: 'Huỷ đặt phòng thất bại',
                content: err?.message || 'Đã có lỗi xảy ra',
            });
        } finally {
            setCancelConfirming(false);
        }
    }, [cancelBookingId]);

    const handleExtendBooking = useCallback(async (booking: BookingSummary) => {
        setExtendLoading(true);
        setExtendPolicy(null);
        setExtendBookingId(booking.booking_id);
        const co = sanitizeIso(booking.check_out_date);
        const newDate = co ? dayjs(co).add(1, 'day') : dayjs().add(1, 'day');
        setExtendDate(newDate);
        setDrawerVisible(false);

        try {
            const policy = await bookingService.getExtendPolicy(booking.booking_id, newDate.format('YYYY-MM-DD'));
            setExtendPolicy(policy);
        } catch (err: any) {
            Modal.error({
                title: 'Không thể lấy chính sách gia hạn',
                content: err?.message || 'Đã có lỗi xảy ra',
            });
        } finally {
            setExtendLoading(false);
        }
    }, []);

    const handleConfirmExtend = useCallback(async () => {
        if (!extendBookingId || !extendDate) return;
        setExtendConfirming(true);
        try {
            await bookingService.confirmExtendBooking(extendBookingId, extendDate.format('YYYY-MM-DD'));
            Modal.success({
                title: 'Gia hạn thành công',
                content: 'Đặt phòng đã được gia hạn. Danh sách đã được cập nhật.',
            });
            setBookings(prev => prev.map(b =>
                b.booking_id === extendBookingId
                    ? { ...b, check_out_date: extendDate.format('YYYY-MM-DD') }
                    : b
            ));
            setExtendPolicy(null);
            setExtendBookingId(null);
        } catch (err: any) {
            Modal.error({
                title: 'Gia hạn thất bại',
                content: err?.message || 'Đã có lỗi xảy ra',
            });
        } finally {
            setExtendConfirming(false);
        }
    }, [extendBookingId, extendDate]);

    const handleRescheduleBooking = useCallback(async (_booking: BookingSummary) => {
        setRescheduleModalVisible(true);
        setDrawerVisible(false);
    }, []);

    const fetchReviewEligibility = useCallback(async (bookingId: number) => {
        if (reviewEligibility[bookingId]) return reviewEligibility[bookingId];
        try {
            const res = await bookingService.getReviewEligibility(bookingId);
            const entry = { eligible: !!res.eligible, reason: res.reason };
            setReviewEligibility(prev => ({ ...prev, [bookingId]: entry }));
            return entry;
        } catch (err: any) {
            const entry = { eligible: false, reason: err?.reason || err?.message || 'Không thể kiểm tra điều kiện' };
            setReviewEligibility(prev => ({ ...prev, [bookingId]: entry }));
            return entry;
        }
    }, [reviewEligibility]);

    const handleReviewBooking = useCallback(async (booking: BookingSummary) => {
        const entry = await fetchReviewEligibility(booking.booking_id);
        if (entry.eligible) {
            window.location.href = `/review-booking?booking=${booking.booking_id}`;
        } else {
            message.info(entry.reason || 'Không đủ điều kiện để đánh giá');
        }
    }, [fetchReviewEligibility]);

    // Filter change handlers
    const handleTabChange = useCallback((key: string) => {
        setActiveTab(key);
        const newFilters = { ...searchFilters, status: key };
        setSearchFilters(newFilters);
        // Client-side: filters will be applied locally via effect
        setCurrentPage(1);
    }, [searchFilters, bookings.length]);

    const handleFilterChange = useCallback((_changedValues: any, allValues: any) => {
        if (searchDebounceTimer) {
            clearTimeout(searchDebounceTimer);
        }

        const timer = setTimeout(() => {
            const newFilters: SearchFilters = {
                search: allValues.search,
                status: activeTab !== 'all' ? activeTab : undefined,
                from: allValues.dateRange?.[0]?.format('YYYY-MM-DD'),
                to: allValues.dateRange?.[1]?.format('YYYY-MM-DD'),
                room_type: allValues.room_type
            };
            setSearchFilters(newFilters);

            // Client-side: set filters and let client filtering apply
            // reset to page 1 when filters change
            setCurrentPage(1);
        }, 300);

        setSearchDebounceTimer(timer);
    }, [activeTab, searchDebounceTimer]);

    // Apply client-side filters to `bookings` and produce `filteredBookings`.
    const applyClientFilters = useCallback((filters: SearchFilters) => {
        const list = bookings.filter((b) => {
            // search by booking_code, guest_name, guest_phone
            if (filters.search && filters.search.trim()) {
                const q = filters.search.trim().toLowerCase();
                const hay = `${b.booking_code || ''} ${b.guest_name || ''} ${b.guest_phone || ''}`.toLowerCase();
                if (!hay.includes(q)) return false;
            }

            // status
            if (filters.status && filters.status !== 'all') {
                const st = (b.status || '').toString().toLowerCase();
                if (st !== filters.status.toString().toLowerCase()) return false;
            }

            // room_type
            if (filters.room_type) {
                const rt = (b.room_type || '').toString().toLowerCase();
                if (!rt.includes(filters.room_type.toString().toLowerCase())) return false;
            }

            // date range: check_in_date within [from, to]
            if (filters.from || filters.to) {
                const from = filters.from ? dayjs(filters.from, 'YYYY-MM-DD') : null;
                const to = filters.to ? dayjs(filters.to, 'YYYY-MM-DD') : null;
                const checkIn = b.check_in_date ? dayjs(sanitizeIso(b.check_in_date)) : null;
                if (from && checkIn && checkIn.isBefore(from, 'day')) return false;
                if (to && checkIn && checkIn.isAfter(to, 'day')) return false;
            }

            return true;
        });

        setFilteredBookings(list);
    }, [bookings]);

    // Re-apply filters whenever bookings or searchFilters change
    useEffect(() => {
        applyClientFilters(searchFilters);
    }, [bookings, searchFilters, applyClientFilters]);

    // Load room types for the filter select on mount
    useEffect(() => {
        let mounted = true;
        const load = async () => {
            try {
                const res: any = await roomTypesAPI.getAll();
                const payload = res?.data ?? res;
                if (!mounted) return;
                const types: string[] = (payload || []).map((r: any) => r.room_type_name || r.name || r.room_type || r.room_type_name || r.room_type_name_vn || r.room_type_name_en).filter(Boolean);
                setRoomTypes(types);
            } catch (err) {
                // Non-fatal: keep roomTypes empty
                // console.error('Failed to load room types', err);
            }
        };
        load();
        return () => { mounted = false; };
    }, []);

    // Copy booking code to clipboard
    const handleCopyBookingCode = useCallback(async (code?: string) => {
        if (!code) return;
        try {
            await navigator.clipboard.writeText(code);
            message.success('Đã sao chép mã booking');
        } catch (err) {
            message.error('Không thể sao chép mã booking');
        }
    }, []);

    // Tab items with counts
    const tabItems = useMemo(() => {
        const getCount = (status: string) => {
            if (status === 'all') return bookings.length;
            return bookings.filter(b => (typeof b.status === 'string' ? b.status.toLowerCase() : '') === status).length;
        };

        return [
            { key: 'all', label: `Tất cả (${getCount('all')})` },
            { key: 'pending', label: `Chờ xác nhận (${getCount('pending')})` },
            { key: 'confirmed', label: `Đã xác nhận (${getCount('confirmed')})` },
            { key: 'completed', label: `Hoàn thành (${getCount('completed')})` },
            { key: 'cancelled', label: `Đã hủy (${getCount('cancelled')})` }
        ];
    }, [bookings]);

    // Check if action buttons should be enabled
    const isActionEnabled = useCallback((booking: BookingSummary, action: 'cancel' | 'extend' | 'reschedule') => {
        const isConfirmed = (typeof booking.status === 'string' ? booking.status.toLowerCase() : '') === 'confirmed';
        const checkIn = sanitizeIso(booking.check_in_date);
        const checkOut = sanitizeIso(booking.check_out_date);
        const checkInFuture = checkIn ? dayjs(checkIn).isAfter(dayjs()) : false;
        const checkOutFuture = checkOut ? dayjs(checkOut).isAfter(dayjs()) : false;

        switch (action) {
            case 'cancel':
            case 'reschedule':
                return isConfirmed && checkInFuture;
            case 'extend':
                return isConfirmed && checkOutFuture;
            default:
                return false;
        }
    }, []);

    // Table columns
    const columns = [
        {
            title: 'Mã booking',
            dataIndex: 'booking_code',
            key: 'booking_code',
            render: (text: string, record: BookingSummary) => (
                <Space>
                    <Button
                        type="link"
                        size="small"
                        onClick={() => handleViewDetail(record.booking_id)}
                        loading={detailLoading}
                    >
                        <strong>{text}</strong>
                    </Button>
                    <Tooltip title="Sao chép mã booking">
                        <Button
                            type="text"
                            size="small"
                            icon={<CopyOutlined />}
                            onClick={() => handleCopyBookingCode(text)}
                        />
                    </Tooltip>
                </Space>
            ),
        },
        {
            title: 'Tên khách',
            dataIndex: 'guest_name',
            key: 'guest_name',
            render: (text: string) => (
                <Space>
                    <UserOutlined />
                    {text}
                </Space>
            ),
        },
        {
            title: 'Số điện thoại',
            dataIndex: 'guest_phone',
            key: 'guest_phone',
            render: (text: string) => (
                <Space>
                    <PhoneOutlined />
                    {text}
                </Space>
            ),
        },
        {
            title: 'Ngày nhận/trả phòng',
            key: 'dates',
            render: (record: BookingSummary) => {
                const checkInDate = record.check_in_date ? dayjs(sanitizeIso(record.check_in_date)).format('YYYY-MM-DD') : '-';
                const checkOutDate = record.check_out_date ? dayjs(sanitizeIso(record.check_out_date)).format('YYYY-MM-DD') : '-';

                return (
                    <div style={{ display: 'flex', flexDirection: 'column', alignItems: 'center', gap: 5 }}>
                        <div style={{ display: 'flex', alignItems: 'center', gap: 5 }}>
                            <Tooltip title="Nhận phòng 14:00">
                                <Text style={{ fontSize: 12, fontWeight: 500 }}>{checkInDate}</Text>
                            </Tooltip>
                            <div style={{ color: '#1890ff', fontSize: 14 }}>→</div>
                            <Tooltip title="Trả phòng 12:00">
                                <Text style={{ fontSize: 12, fontWeight: 500 }}>{checkOutDate}</Text>
                            </Tooltip>
                        </div>
                        <Tag color="blue" icon={<CalendarOutlined />} style={{ fontSize: 11, padding: '0 6px' }}>
                            {`${record.nights || 1} đêm`}
                        </Tag>
                    </div>
                );
            },
        },
        {
            title: 'Tổng tiền',
            key: 'total_price',
            render: (record: BookingSummary) => (
                <Space>
                    <DollarOutlined />
                    <strong>
                        {record.total_price_formatted || formatVNDCurrency(record.total_price_vnd)}
                    </strong>
                </Space>
            ),
        },
        {
            title: 'Coupon',
            key: 'coupon',
            render: (record: ExtendedBookingSummary) => getCouponDisplay(record),
        },
        {
            title: 'Trạng thái',
            dataIndex: 'status',
            key: 'status',
            render: (status: string) => (
                <Tag color={getStatusColor(status)} icon={getStatusIcon(status)}>
                    {getStatusText(status)}
                </Tag>
            ),
        },
        {
            title: 'Hành động',
            key: 'action',
            render: (record: ExtendedBookingSummary) => {
                const menuItems: MenuProps['items'] = [
                    // {
                    //     key: 'view',
                    //     icon: <EyeOutlined />,
                    //     label: 'Chi tiết',
                    //     onClick: () => handleViewDetail(record.booking_id)
                    // },
                    // { type: 'divider' },
                    {
                        key: 'cancel',
                        icon: <StopOutlined />,
                        label: 'Huỷ booking',
                        danger: true,
                        disabled: !isActionEnabled(record, 'cancel'),
                        onClick: () => handleCancelBooking(record)
                    },
                    {
                        key: 'extend',
                        icon: <CalendarOutlined />,
                        label: 'Gia hạn',
                        disabled: !isActionEnabled(record, 'extend'),
                        onClick: () => handleExtendBooking(record)
                    },
                    {
                        key: 'reschedule',
                        icon: <EditOutlined />,
                        label: 'Dời lịch',
                        disabled: !isActionEnabled(record, 'reschedule'),
                        onClick: () => handleRescheduleBooking(record)
                    },
                    {
                        key: 'review',
                        icon: <CommentOutlined />,
                        label: 'Đánh giá',
                        disabled: record.status?.toLowerCase() !== 'completed',
                        onClick: () => handleReviewBooking(record)
                    }
                ];

                return (
                    <Space>
                        <Button
                            type="primary"
                            size="small"
                            icon={<EyeOutlined />}
                            onClick={() => handleViewDetail(record.booking_id)}
                            loading={detailLoading}
                        >
                            Chi tiết
                        </Button>
                        <Dropdown
                            menu={{ items: menuItems }}
                            trigger={['click']}
                            placement="bottomRight"
                        >
                            <Button
                                size="small"
                                icon={<MoreOutlined />}
                            />
                        </Dropdown>
                    </Space>
                );
            },
        },
    ];

    return (
        <div style={{ maxWidth: 1500, margin: '0 auto', padding: '24px' }}>

            {/* Enhanced Search & Filter Form */}
            <Card style={{ marginBottom: 24 }}>
                <Form
                    form={form}
                    layout="vertical"
                    onFinish={(values) => {
                        const filters: SearchFilters = {
                            search: values.search,
                            status: activeTab !== 'all' ? activeTab : undefined,
                            from: values.dateRange?.[0]?.format('YYYY-MM-DD'),
                            to: values.dateRange?.[1]?.format('YYYY-MM-DD'),
                            room_type: values.room_type
                        };
                        setSearchFilters(filters);
                        handleSearch(filters, 1);
                    }}
                    onValuesChange={handleFilterChange}
                >
                    <Row gutter={16}>
                        <Col xs={24} sm={12} md={8}>
                            <Form.Item
                                name="search"
                                label="Tìm kiếm trong booking của tôi"
                                rules={[
                                    { min: 3, message: 'Vui lòng nhập ít nhất 3 ký tự' }
                                ]}
                            >
                                <Input
                                    size="large"
                                    placeholder="Mã booking, tên phòng..."
                                    prefix={<SearchOutlined />}
                                    allowClear
                                />
                            </Form.Item>
                        </Col>
                        <Col xs={24} sm={12} md={8}>
                            <Form.Item name="dateRange" label="Khoảng ngày">
                                <RangePicker
                                    size="large"
                                    style={{ width: '100%' }}
                                    placeholder={['Từ ngày', 'Đến ngày']}
                                />
                            </Form.Item>
                        </Col>
                        <Col xs={24} sm={12} md={4}>
                            <Form.Item name="room_type" label="Loại phòng">
                                <Select
                                    size="large"
                                    placeholder="Chọn loại phòng"
                                    allowClear
                                >
                                    {roomTypes.map(type => (
                                        <Select.Option key={type} value={type}>{type}</Select.Option>
                                    ))}
                                </Select>
                            </Form.Item>
                        </Col>
                        <Col xs={24} sm={12} md={4}>
                            <Form.Item label=" ">
                                <Button
                                    type="primary"
                                    size="large"
                                    htmlType="submit"
                                    loading={loading}
                                    block
                                    icon={<SearchOutlined />}
                                >
                                    Tìm kiếm
                                </Button>
                            </Form.Item>
                        </Col>
                    </Row>
                </Form>

            </Card>

            {/* Show initial loading or results */}
            {initialLoading ? (
                <Card>
                    <Skeleton active paragraph={{ rows: 8 }} />
                </Card>
            ) : searchMeta && (
                <Card

                >
                    {/* Status Filter Tabs */}
                    <Tabs
                        activeKey={activeTab}
                        onChange={handleTabChange}
                        style={{ marginBottom: 16 }}
                        items={tabItems}
                    />

                    {filteredBookings.length > 0 ? (
                        <Table
                            columns={columns}
                            dataSource={filteredBookings}
                            rowKey="booking_id"
                            loading={loading}
                            pagination={{
                                current: currentPage,
                                total: filteredBookings.length,
                                pageSize: searchMeta?.per_page || 20,
                                showSizeChanger: false,
                                showQuickJumper: true,
                                onChange: (page) => setCurrentPage(page),
                                showTotal: (total, range) => `${range[0]}-${range[1]} của ${total} booking`,
                            }}
                            onChange={handleTableChange}
                            scroll={{ x: 1000 }}
                        />
                    ) : (
                        <Empty
                            description={
                                <Space direction="vertical">
                                    <Text>Bạn chưa có booking nào</Text>
                                    <Text type="secondary">
                                        Hãy đặt phòng đầu tiên tại LavishStay để bắt đầu trải nghiệm tuyệt vời
                                    </Text>
                                </Space>
                            }
                        />
                    )}
                </Card>
            )}

            {/* Enhanced Detail Drawer */}
            <Drawer
                title={
                    <Space>
                        <InfoCircleOutlined />
                        Chi tiết booking: {selectedBooking?.booking_code}
                    </Space>
                }
                placement="right"
                width={720}
                open={drawerVisible}
                onClose={() => {
                    setDrawerVisible(false);
                    setSelectedBooking(null);
                }}
            >
                {detailLoading ? (
                    <Skeleton active paragraph={{ rows: 8 }} />
                ) : selectedBooking && (
                    <div>
                        {/* Basic Info */}
                        <Descriptions
                            title="Thông tin cơ bản"
                            bordered
                            column={1}
                            size="middle"
                        >
                            <Item label="Mã booking">{selectedBooking.booking_code}</Item>
                            <Item label="Trạng thái">
                                <Tag color={getStatusColor(selectedBooking.status)} icon={getStatusIcon(selectedBooking.status)}>
                                    {getStatusText(selectedBooking.status)}
                                </Tag>
                            </Item>
                            <Item label="Tên khách hàng">{selectedBooking.guest_name}</Item>
                            <Item label="Số điện thoại">{selectedBooking.guest_phone}</Item>
                            <Item label="Email">{selectedBooking.guest_email}</Item>
                            <Item label="Ngày nhận phòng">{formatCheckInDisplay(selectedBooking.check_in_date)}</Item>
                            <Item label="Ngày trả phòng">{formatCheckOutDisplay(selectedBooking.check_out_date)}</Item>
                            <Item label="Số đêm">
                                <Badge count={`${selectedBooking.nights || 1} đêm`} color="blue" />
                            </Item>
                            <Item label="Số khách">{selectedBooking.guest_count} người</Item>
                            <Item label="Tổng tiền">
                                <Text strong style={{ color: '#1890ff', fontSize: '16px' }}>
                                    {selectedBooking.total_price_formatted || formatVNDCurrency(selectedBooking.total_price_vnd)}
                                </Text>
                            </Item>

                            {/* Coupon display in drawer */}
                            <Item label="Coupon được sử dụng">
                                {(() => {
                                    const booking = bookings.find(b => b.booking_id === selectedBooking.booking_id) as ExtendedBookingSummary;
                                    if (booking?.coupon_data) {
                                        const { coupon_data } = booking;
                                        // Nicely formatted coupon display: TAG + formatted negative VND amount
                                        return (
                                            <Space direction="vertical" size={4}>
                                                <Space align="center">
                                                    <Tag color="gold" icon={<GiftOutlined />} style={{ fontWeight: 700 }}>
                                                        {coupon_data.coupon_code}
                                                    </Tag>
                                                    <Text strong style={{ color: '#52c41a', fontSize: 14 }}>{formatCouponSaved(coupon_data.amount_saved)}</Text>
                                                </Space>

                                                {coupon_data.coupon?.description && (
                                                    <Text type="secondary">{coupon_data.coupon.description}</Text>
                                                )}

                                                <Text type="secondary" style={{ fontSize: 12 }}>
                                                    Áp dụng: {coupon_data.redeemed_at ? dayjs(coupon_data.redeemed_at).format('YYYY-MM-DD HH:mm') : '-'}
                                                </Text>
                                            </Space>
                                        );
                                    }
                                    return <Text type="secondary">Không có coupon adsda</Text>;
                                })()}
                            </Item>
                        </Descriptions>

                        <Divider />

                        {/* Rooms Detail */}
                        <Title level={4}>
                            <HomeOutlined /> Chi tiết phòng
                        </Title>
                        {selectedBooking.rooms_detail && selectedBooking.rooms_detail.length > 0 ? (
                            selectedBooking.rooms_detail.map((room, index) => {
                                const isUnassigned = !room.room_name || room.room_name === 'N/A' ||
                                    !room.room_type || room.room_type === 'N/A';

                                return (
                                    <Card
                                        key={room.id}
                                        size="small"
                                        title={`Phòng ${index + 1}: ${isUnassigned ? 'Chưa gán phòng' : room.room_name}`}
                                        style={{ marginBottom: 16 }}
                                    >
                                        {isUnassigned ? (
                                            <Alert
                                                message="Chưa gán phòng cụ thể"
                                                description="Phòng sẽ được gán khi check-in hoặc trước đó 1 ngày"
                                                type="info"
                                                showIcon
                                                style={{ fontSize: 12 }}
                                            />
                                        ) : (
                                            <Descriptions column={2} size="small">
                                                <Item label="Loại phòng">{room.room_type}</Item>
                                                <Item label="Tầng">{room.floor}</Item>
                                                <Item label="Gói dịch vụ">{room.option_name}</Item>
                                                <Item label="Người lớn">{room.adults}</Item>
                                                <Item label="Trẻ em">{room.children}</Item>
                                                <Item label="Giá/đêm">{room.price_per_night?.toLocaleString('vi-VN')} VNĐ</Item>
                                                <Item label="Số đêm">{room.nights}</Item>
                                                <Item label="Tổng phòng">
                                                    <strong>{room.total_price?.toLocaleString('vi-VN')} VNĐ</strong>
                                                </Item>
                                            </Descriptions>
                                        )}
                                    </Card>
                                );
                            })
                        ) : selectedBooking.rooms && selectedBooking.rooms.length > 0 ? (
                            selectedBooking.rooms.map((room, index) => {
                                const isUnassigned = !room.room_name || room.room_name === 'N/A' ||
                                    !room.room_type || room.room_type === 'N/A';

                                return (
                                    <Card
                                        key={room.room_id}
                                        size="small"
                                        title={`Phòng ${index + 1}: ${isUnassigned ? 'Chưa gán phòng' : room.room_name}`}
                                        style={{ marginBottom: 16 }}
                                    >
                                        {isUnassigned ? (
                                            <Alert
                                                message="Chưa gán phòng cụ thể"
                                                description="Phòng sẽ được gán khi check-in hoặc trước đó 1 ngày"
                                                type="info"
                                                showIcon
                                                style={{ fontSize: 12 }}
                                            />
                                        ) : (
                                            <Descriptions column={2} size="small">
                                                <Item label="Loại phòng">{room.room_type}</Item>
                                                <Item label="Gói dịch vụ">{room.option_name}</Item>
                                                <Item label="Người lớn">{room.adults}</Item>
                                                <Item label="Trẻ em">{room.children}</Item>
                                                <Item label="Tổng phòng">
                                                    <strong>{room.total_price} VNĐ</strong>
                                                </Item>
                                            </Descriptions>
                                        )}
                                    </Card>
                                );
                            })
                        ) : (
                            <Card size="small" style={{ marginBottom: 16 }}>
                                <Text type="secondary">Thông tin phòng đang được tải...</Text>
                            </Card>
                        )}

                        <Divider />

                        {/* Representatives */}
                        {selectedBooking.representatives && selectedBooking.representatives.length > 0 && (
                            <>
                                <Title level={4}>
                                    <UserOutlined /> Người đại diện
                                </Title>
                                {selectedBooking.representatives.map((rep, index) => (
                                    <Descriptions
                                        key={rep.id || index}
                                        bordered
                                        size="small"
                                        column={1}
                                        style={{ marginBottom: 16 }}
                                    >
                                        <Item label="Họ tên">{rep.full_name || rep.name}</Item>
                                        <Item label="Số điện thoại">{rep.phone_number || rep.phone}</Item>
                                        <Item label="Email">{rep.email}</Item>
                                        <Item label="CCCD/Hộ chiếu">{rep.id_card}</Item>
                                    </Descriptions>
                                ))}
                                <Divider />
                            </>
                        )}

                        {/* Payments */}
                        {selectedBooking.payments && selectedBooking.payments.length > 0 && (
                            <>
                                <Title level={4}>
                                    <DollarOutlined /> Lịch sử thanh toán
                                </Title>
                                {selectedBooking.payments.map((payment) => (
                                    <Card
                                        key={payment.payment_id}
                                        size="small"
                                        style={{ marginBottom: 8 }}
                                    >
                                        <Descriptions column={2} size="small">
                                            <Item label="Số tiền">{payment.amount_vnd || payment.amount?.toLocaleString('vi-VN') + ' VNĐ'}</Item>
                                            <Item label="Loại">
                                                <Tag>{payment.payment_type}</Tag>
                                            </Item>
                                            <Item label="Trạng thái">
                                                <Tag color={payment.status === 'completed' ? 'green' : 'orange'}>
                                                    {payment.status}
                                                </Tag>
                                            </Item>
                                            <Item label="Thời gian">{payment.created_at}</Item>
                                        </Descriptions>
                                    </Card>
                                ))}
                                <Divider />
                            </>
                        )}

                        {/* Notes */}
                        {selectedBooking.notes && (
                            <>
                                <Title level={4}>Ghi chú</Title>
                                <Card size="small">
                                    <Text>{selectedBooking.notes}</Text>
                                </Card>
                                <Divider />
                            </>
                        )}

                        {/* Action Buttons in Drawer */}
                        <Card size="small" title="Thao tác">
                            <Space wrap>
                                <Tooltip title={!isActionEnabled(selectedBooking, 'cancel') ? 'Chỉ có thể huỷ khi đã xác nhận và trước ngày check-in' : ''}>
                                    <Button
                                        danger
                                        loading={cancelLoading && cancelBookingId === selectedBooking.booking_id}
                                        onClick={() => handleCancelBooking(selectedBooking)}
                                        disabled={!isActionEnabled(selectedBooking, 'cancel')}
                                    >
                                        Huỷ đặt phòng
                                    </Button>
                                </Tooltip>
                                <Tooltip title={!isActionEnabled(selectedBooking, 'extend') ? 'Chỉ có thể gia hạn khi đã xác nhận và trước ngày check-out' : ''}>
                                    <Button
                                        type="primary"
                                        loading={extendLoading && extendBookingId === selectedBooking.booking_id}
                                        onClick={() => handleExtendBooking(selectedBooking)}
                                        disabled={!isActionEnabled(selectedBooking, 'extend')}
                                    >
                                        Gia hạn
                                    </Button>
                                </Tooltip>
                                <Tooltip title={!isActionEnabled(selectedBooking, 'reschedule') ? 'Chỉ có thể dời lịch khi đã xác nhận và trước ngày check-in' : ''}>
                                    <Button
                                        onClick={() => handleRescheduleBooking(selectedBooking)}
                                        disabled={!isActionEnabled(selectedBooking, 'reschedule')}
                                    >
                                        Dời lịch
                                    </Button>
                                </Tooltip>
                                <Button onClick={() => handleReviewBooking(selectedBooking)}>
                                    Đánh giá
                                </Button>
                            </Space>
                        </Card>

                        {/* Timestamps */}
                        <Descriptions size="small" column={1} style={{ marginTop: 16 }}>
                            <Item label="Thời gian đặt">{selectedBooking.created_at}</Item>
                            <Item label="Cập nhật cuối">{selectedBooking.updated_at}</Item>
                        </Descriptions>
                    </div>
                )}
            </Drawer>

            {/* Cancel Policy Modal */}
            <Modal
                open={!!cancelPolicy}
                onCancel={() => { setCancelPolicy(null); setCancelBookingId(null); }}
                title={<div style={{ display: 'flex', alignItems: 'center', fontWeight: 600 }}>
                    <SafetyOutlined style={{ color: '#faad14', marginRight: 8 }} />
                    Xác nhận huỷ phòng
                </div>}
                footer={null}
                width={500}
                destroyOnClose
                maskClosable={false}
            >
                {cancelPolicy && (
                    <div>
                        <div style={{ marginBottom: 12, color: '#faad14', fontWeight: 500 }}>
                            {cancelPolicy.message}
                        </div>
                        <div style={{ marginBottom: 8 }}><b>Chính sách:</b> {cancelPolicy.policy}</div>
                        <div style={{ marginBottom: 8 }}><b>Lý do:</b> {cancelPolicy.reason}</div>
                        <div style={{ marginBottom: 8 }}><b>Công thức:</b> {cancelPolicy.formula}</div>
                        <div style={{ marginBottom: 8 }}>
                            <b>Tiền phạt:</b>
                            <span style={{ color: '#d4380d', fontWeight: 600 }}>
                                {cancelPolicy.penalty?.toLocaleString('vi-VN')}₫
                            </span>
                        </div>
                        <div style={{ marginBottom: 8 }}>
                            <b>Tổng giá trị booking:</b>
                            {cancelPolicy.booking_info?.total_price?.toLocaleString('vi-VN')}₫
                        </div>
                        <div style={{ marginBottom: 8 }}><b>Mã booking:</b> {cancelPolicy.booking_info?.booking_code}</div>
                        <div style={{ marginTop: 24, display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                            <Button onClick={() => { setCancelPolicy(null); setCancelBookingId(null); }}>
                                Quay lại
                            </Button>
                            <Button type="primary" danger loading={cancelConfirming} onClick={handleConfirmCancel}>
                                Xác nhận huỷ
                            </Button>
                        </div>
                    </div>
                )}
            </Modal>

            {/* Extend Policy Modal */}
            <Modal
                open={!!extendPolicy}
                onCancel={() => { setExtendPolicy(null); setExtendBookingId(null); setExtendDate(null); }}
                title={<div style={{ display: 'flex', alignItems: 'center', fontWeight: 600 }}>
                    <SafetyOutlined style={{ color: '#1890ff', marginRight: 8 }} />
                    Xác nhận gia hạn phòng
                </div>}
                footer={null}
                width={500}
                destroyOnClose
                maskClosable={false}
            >
                {extendPolicy && (
                    <div>
                        <div style={{ marginBottom: 12, color: '#1890ff', fontWeight: 500 }}>
                            {extendPolicy.message}
                        </div>
                        <div style={{ marginBottom: 8 }}><b>Chính sách:</b> {extendPolicy.policy}</div>
                        <div style={{ marginBottom: 8 }}><b>Phí gia hạn:</b>
                            <span style={{ color: '#1890ff', fontWeight: 600 }}>
                                {extendPolicy.extension_fee?.toLocaleString('vi-VN')}₫
                            </span>
                        </div>
                        <div style={{ marginBottom: 8 }}><b>Số ngày gia hạn:</b> {extendPolicy.extension_days}</div>
                        <div style={{ marginBottom: 8 }}>
                            <b>Chọn ngày trả phòng mới:</b>
                            <DatePicker
                                value={extendDate}
                                onChange={(date) => setExtendDate(date)}
                                style={{ marginLeft: 8 }}
                                disabledDate={current => {
                                    if (!current) return false;
                                    const minDateRaw = sanitizeIso(extendPolicy.booking_info?.check_out_date);
                                    const minDate = minDateRaw ? dayjs(minDateRaw) : null;
                                    return minDate ? (current.isSame(minDate, 'day') || current.isBefore(minDate, 'day')) : false;
                                }}
                                format="YYYY-MM-DD"
                            />
                        </div>
                        <div style={{ marginTop: 24, display: 'flex', justifyContent: 'flex-end', gap: 8 }}>
                            <Button onClick={() => { setExtendPolicy(null); setExtendBookingId(null); setExtendDate(null); }}>
                                Quay lại
                            </Button>
                            <Button type="primary" loading={extendConfirming} onClick={handleConfirmExtend}>
                                Xác nhận gia hạn
                            </Button>
                        </div>
                    </div>
                )}
            </Modal>

            {/* Reschedule Modal (simplified for this implementation) */}
            <Modal
                open={rescheduleModalVisible}
                onCancel={() => setRescheduleModalVisible(false)}
                title="Dời lịch đặt phòng"
                footer={[
                    <Button key="cancel" onClick={() => setRescheduleModalVisible(false)}>
                        Đóng
                    </Button>,
                    <Button key="submit" type="primary" disabled>
                        Liên hệ lễ tân để dời lịch
                    </Button>
                ]}
            >
                <Alert
                    message="Chức năng dời lịch"
                    description="Vui lòng liên hệ trực tiếp với lễ tân để được hỗ trợ dời lịch đặt phòng. Chúng tôi sẽ kiểm tra tình trạng phòng và áp dụng chính sách phù hợp."
                    type="info"
                    showIcon
                />
            </Modal>
        </div>
    );
};

export default LookupBookingByPhone;
