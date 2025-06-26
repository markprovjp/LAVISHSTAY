// Custom hook for booking management with anti-spam
import { useState, useEffect, useCallback } from 'react';
import { message } from 'antd';
import { useSelector } from 'react-redux';
import { selectBookingState } from '../store/slices/bookingSlice';
import { selectSearchData } from '../store/slices/searchSlice';
import bookingAntiSpamService from '../services/bookingAntiSpamService';
import {
    showRateLimitNotification,
    showBookingReuseNotification,
    showSpamAttemptWarning,
    showCountdownNotification
} from '../components/notifications/RateLimitNotifications';

interface UseBookingManagerResult {
    bookingCode: string;
    isProcessing: boolean;
    canProceed: boolean;
    cooldownInfo: { inCooldown: boolean; remainingTime?: number };
    createBooking: (customerData: any) => Promise<void>;
    resetBooking: () => void;
    getSessionInfo: () => any;
}

export const useBookingManager = (): UseBookingManagerResult => {
    const [bookingCode, setBookingCode] = useState('');
    const [isProcessing, setIsProcessing] = useState(false);
    const [canProceed, setCanProceed] = useState(true);
    const [cooldownInfo, setCooldownInfo] = useState({ inCooldown: false });

    // Redux state
    const bookingState = useSelector(selectBookingState);
    const searchData = useSelector(selectSearchData);    // Check eligibility on mount and when data changes
    useEffect(() => {
        const checkEligibility = () => {
            // Kiểm tra cooldown đơn giản thay vì method phức tạp
            const cooldown = bookingAntiSpamService.getCooldownInfo();
            setCooldownInfo(cooldown);
            setCanProceed(!cooldown.inCooldown);            // Lấy session info để check existing booking
            const currentSession = bookingAntiSpamService.getSessionInfo();
            if (currentSession.session) {
                setBookingCode(currentSession.session.bookingCode);
            }

            // Show appropriate notifications
            if (cooldown.inCooldown && cooldown.remainingTime) {
                showRateLimitNotification(cooldown.remainingTime, () => {
                    bookingAntiSpamService.clearRateLimit();
                    checkEligibility();
                });
            }

            // Show countdown if in cooldown
            if (cooldown.inCooldown && cooldown.remainingTime) {
                showCountdownNotification(
                    cooldown.remainingTime,
                    () => {
                        checkEligibility();
                        message.success('Đã hết thời gian khóa. Bạn có thể đặt phòng lại.');
                    },
                    () => {
                        bookingAntiSpamService.clearRateLimit();
                        checkEligibility();
                    }
                );
            }            // Show spam warning if approaching limit
            const latestSession = bookingAntiSpamService.getSessionInfo();
            if (latestSession.rateLimit && latestSession.rateLimit.attempts >= 3 && latestSession.rateLimit.attempts < 5) {
                const attemptsLeft = 5 - latestSession.rateLimit.attempts;
                showSpamAttemptWarning(attemptsLeft);
            }
        };

        checkEligibility();
    }, [bookingState.selectedRooms, searchData, bookingState.preferences, bookingState.totals]);

    // Create booking with anti-spam protection
    const createBooking = useCallback(async (customerData: any) => {
        if (!canProceed) {
            message.error('Không thể tạo đặt phòng lúc này');
            return;
        }

        setIsProcessing(true);
        try {
            // Add total amount from Redux state to customer data
            const customerDataWithTotal = {
                ...customerData,
                totalAmount: bookingState.totals.finalTotal || bookingState.totals.roomsTotal
            }; const result = await bookingAntiSpamService.createBooking(
                bookingState.selectedRooms,
                searchData,
                customerDataWithTotal
            );

            setBookingCode(result.bookingCode);

            if (result.isReused) {
                showBookingReuseNotification(result.bookingCode);
            } else {
                message.success('Tạo đặt phòng thành công');
            }
        } catch (error: any) {
            message.error(error.message || 'Có lỗi xảy ra khi tạo đặt phòng');
            throw error;
        } finally {
            setIsProcessing(false);
        }
    }, [canProceed, bookingState.selectedRooms, searchData, bookingState.preferences, bookingState.totals]);

    // Reset booking session
    const resetBooking = useCallback(() => {
        bookingAntiSpamService.clearSession();
        bookingAntiSpamService.clearRateLimit(); // Also clear rate limit for dev testing
        setBookingCode(''); setCanProceed(true);
        setCooldownInfo({ inCooldown: false });

        // Force re-check eligibility
        setTimeout(() => {
            const cooldown = bookingAntiSpamService.getCooldownInfo();
            setCooldownInfo(cooldown);
            setCanProceed(!cooldown.inCooldown);

            const info = bookingAntiSpamService.getSessionInfo();
            if (info.session) {
                setBookingCode(info.session.bookingCode);
            }
        }, 100);

        message.success('Đã xóa thông tin đặt phòng và reset rate limit');
    }, [bookingState.selectedRooms, searchData, bookingState.preferences, bookingState.totals]);

    // Get session info for debugging
    const getSessionInfo = useCallback(() => {
        return bookingAntiSpamService.getSessionInfo();
    }, []);

    return {
        bookingCode,
        isProcessing,
        canProceed,
        cooldownInfo,
        createBooking,
        resetBooking,
        getSessionInfo
    };
};
