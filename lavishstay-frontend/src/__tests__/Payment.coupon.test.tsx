// src/__tests__/Payment.coupon.test.tsx
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { Provider } from 'react-redux';
import { configureStore } from '@reduxjs/toolkit';
import '@testing-library/jest-dom';
import CouponInput from '../components/payment/CouponInput';
import { couponService } from '../services/couponService';

// Mock the coupon service
jest.mock('../services/couponService');
const mockedCouponService = couponService as jest.Mocked<typeof couponService>;

// Mock store setup
const createMockStore = () => {
    return configureStore({
        reducer: {
            // Add minimal reducers needed for testing
            auth: (state = { isAuthenticated: false, user: null }) => state,
            booking: (state = { selectedRoomsSummary: [], totals: null }) => state,
        },
    });
};

describe('CouponInput Component', () => {
    const mockProps = {
        bookingPreview: {
            base_price_vnd: 1000000,
            taxes_vnd: 100000,
            fees_vnd: 50000,
            room_type_id: 1,
        },
        appliedCoupon: null,
        onCouponChange: jest.fn(),
        formatVND: (amount: number) => `${amount.toLocaleString('vi-VN')}₫`,
        disabled: false,
    };

    beforeEach(() => {
        jest.clearAllMocks();
    });

    it('renders coupon input with placeholder', () => {
        render(
            <Provider store={createMockStore()}>
                <CouponInput {...mockProps} />
            </Provider>
        );

        expect(screen.getByPlaceholderText('Nhập mã giảm giá')).toBeInTheDocument();
        expect(screen.getByRole('button', { name: 'Kiểm tra' })).toBeInTheDocument();
    });

    it('disables check button when input is empty', () => {
        render(
            <Provider store={createMockStore()}>
                <CouponInput {...mockProps} />
            </Provider>
        );

        const checkButton = screen.getByRole('button', { name: 'Kiểm tra' });
        expect(checkButton).toBeDisabled();
    });

    it('enables check button when input has value', async () => {
        render(
            <Provider store={createMockStore()}>
                <CouponInput {...mockProps} />
            </Provider>
        );

        const input = screen.getByPlaceholderText('Nhập mã giảm giá');
        const checkButton = screen.getByRole('button', { name: 'Kiểm tra' });

        fireEvent.change(input, { target: { value: 'WELCOME2024' } });

        await waitFor(() => {
            expect(checkButton).not.toBeDisabled();
        });
    });

    it('transforms input to uppercase', async () => {
        render(
            <Provider store={createMockStore()}>
                <CouponInput {...mockProps} />
            </Provider>
        );

        const input = screen.getByPlaceholderText('Nhập mã giảm giá') as HTMLInputElement;

        fireEvent.change(input, { target: { value: 'welcome2024' } });

        await waitFor(() => {
            expect(input.value).toBe('WELCOME2024');
        });
    });

    it('shows success message for valid coupon', async () => {
        mockedCouponService.checkCode.mockResolvedValue({
            exists: true,
            coupon: {
                code: 'WELCOME2024',
                type: 'percent',
                value: 15,
                description: 'Welcome discount',
            },
            message: 'Mã giảm giá hợp lệ',
        });

        mockedCouponService.validateCoupon.mockResolvedValue({
            valid: true,
            coupon: {
                code: 'WELCOME2024',
                type: 'percent',
                value: 15,
                description: 'Welcome discount',
                min_booking_amount_vnd: 500000,
            },
            discount_vnd: 150000,
            new_total_vnd: 1000000,
            message: 'Coupon is valid',
        });

        render(
            <Provider store={createMockStore()}>
                <CouponInput {...mockProps} />
            </Provider>
        );

        const input = screen.getByPlaceholderText('Nhập mã giảm giá');
        const checkButton = screen.getByRole('button', { name: 'Kiểm tra' });

        fireEvent.change(input, { target: { value: 'WELCOME2024' } });
        fireEvent.click(checkButton);

        await waitFor(() => {
            expect(screen.getByText('Mã giảm giá hợp lệ!')).toBeInTheDocument();
        });
    });

    it('shows error message for expired coupon', async () => {
        mockedCouponService.checkCode.mockResolvedValue({
            exists: true,
            coupon: {
                code: 'EXPIRED10',
                type: 'percent',
                value: 10,
                description: 'Expired coupon',
            },
            message: 'Coupon exists',
        });

        mockedCouponService.validateCoupon.mockResolvedValue({
            valid: false,
            reason: 'expired',
            message: 'Mã giảm giá đã hết hạn',
        });

        render(
            <Provider store={createMockStore()}>
                <CouponInput {...mockProps} />
            </Provider>
        );

        const input = screen.getByPlaceholderText('Nhập mã giảm giá');
        const checkButton = screen.getByRole('button', { name: 'Kiểm tra' });

        fireEvent.change(input, { target: { value: 'EXPIRED10' } });
        fireEvent.click(checkButton);

        await waitFor(() => {
            expect(screen.getByText('Mã giảm giá đã hết hạn')).toBeInTheDocument();
        });
    });

    it('shows error message for non-existent coupon', async () => {
        mockedCouponService.checkCode.mockResolvedValue({
            exists: false,
            message: 'Mã giảm giá không tồn tại',
        });

        render(
            <Provider store={createMockStore()}>
                <CouponInput {...mockProps} />
            </Provider>
        );

        const input = screen.getByPlaceholderText('Nhập mã giảm giá');
        const checkButton = screen.getByRole('button', { name: 'Kiểm tra' });

        fireEvent.change(input, { target: { value: 'INVALID123' } });
        fireEvent.click(checkButton);

        await waitFor(() => {
            expect(screen.getByText('Mã giảm giá không tồn tại')).toBeInTheDocument();
        });
    });

    it('calls onCouponChange when auto-apply is enabled', async () => {
        const mockOnCouponChange = jest.fn();

        mockedCouponService.checkCode.mockResolvedValue({
            exists: true,
            coupon: {
                code: 'WELCOME2024',
                type: 'percent',
                value: 15,
                description: 'Welcome discount',
            },
            message: 'Coupon exists',
        });

        mockedCouponService.validateCoupon.mockResolvedValue({
            valid: true,
            coupon: {
                code: 'WELCOME2024',
                type: 'percent',
                value: 15,
                description: 'Welcome discount',
                min_booking_amount_vnd: 500000,
            },
            discount_vnd: 150000,
            new_total_vnd: 1000000,
            message: 'Coupon is valid',
        });

        render(
            <Provider store={createMockStore()}>
                <CouponInput {...mockProps} onCouponChange={mockOnCouponChange} />
            </Provider>
        );

        const input = screen.getByPlaceholderText('Nhập mã giảm giá');
        const checkButton = screen.getByRole('button', { name: 'Kiểm tra' });

        fireEvent.change(input, { target: { value: 'WELCOME2024' } });
        fireEvent.click(checkButton);

        await waitFor(() => {
            expect(mockOnCouponChange).toHaveBeenCalledWith({
                code: 'WELCOME2024',
                type: 'percent',
                value: 15,
                description: 'Welcome discount',
                discount_vnd: 150000,
                new_total_vnd: 1000000,
            });
        });
    });

    it('displays applied coupon with remove option', () => {
        const appliedCoupon = {
            code: 'APPLIED123',
            type: 'fixed' as const,
            value: 100000,
            description: 'Applied discount',
            discount_vnd: 100000,
            new_total_vnd: 1050000,
        };

        render(
            <Provider store={createMockStore()}>
                <CouponInput {...mockProps} appliedCoupon={appliedCoupon} />
            </Provider>
        );

        expect(screen.getByText('APPLIED123')).toBeInTheDocument();
        expect(screen.getByText('Đã áp dụng')).toBeInTheDocument();
        expect(screen.getByText('Applied discount')).toBeInTheDocument();
        expect(screen.getByText('-100,000₫')).toBeInTheDocument();
        expect(screen.getByText('1,050,000₫')).toBeInTheDocument();
        expect(screen.getByRole('button', { name: 'Gỡ mã' })).toBeInTheDocument();
    });

    it('removes applied coupon when remove button is clicked', () => {
        const mockOnCouponChange = jest.fn();
        const appliedCoupon = {
            code: 'APPLIED123',
            type: 'fixed' as const,
            value: 100000,
            description: 'Applied discount',
            discount_vnd: 100000,
            new_total_vnd: 1050000,
        };

        render(
            <Provider store={createMockStore()}>
                <CouponInput
                    {...mockProps}
                    appliedCoupon={appliedCoupon}
                    onCouponChange={mockOnCouponChange}
                />
            </Provider>
        );

        const removeButton = screen.getByRole('button', { name: 'Gỡ mã' });
        fireEvent.click(removeButton);

        expect(mockOnCouponChange).toHaveBeenCalledWith(null);
    });

    it('handles network errors gracefully', async () => {
        mockedCouponService.checkCode.mockRejectedValue(new Error('Network error'));

        render(
            <Provider store={createMockStore()}>
                <CouponInput {...mockProps} />
            </Provider>
        );

        const input = screen.getByPlaceholderText('Nhập mã giảm giá');
        const checkButton = screen.getByRole('button', { name: 'Kiểm tra' });

        fireEvent.change(input, { target: { value: 'TESTCODE' } });
        fireEvent.click(checkButton);

        await waitFor(() => {
            expect(screen.getByText('Lỗi kết nối mạng. Vui lòng thử lại')).toBeInTheDocument();
        });
    });

    it('handles rate limiting errors', async () => {
        const rateLimitError = {
            response: { status: 429 },
        };
        mockedCouponService.checkCode.mockRejectedValue(rateLimitError);

        render(
            <Provider store={createMockStore()}>
                <CouponInput {...mockProps} />
            </Provider>
        );

        const input = screen.getByPlaceholderText('Nhập mã giảm giá');
        const checkButton = screen.getByRole('button', { name: 'Kiểm tra' });

        fireEvent.change(input, { target: { value: 'TESTCODE' } });
        fireEvent.click(checkButton);

        await waitFor(() => {
            expect(screen.getByText('Quá nhiều yêu cầu. Vui lòng đợi một chút')).toBeInTheDocument();
        });
    });

    it('disables input and buttons when disabled prop is true', () => {
        render(
            <Provider store={createMockStore()}>
                <CouponInput {...mockProps} disabled={true} />
            </Provider>
        );

        const input = screen.getByPlaceholderText('Nhập mã giảm giá');
        const checkButton = screen.getByRole('button', { name: 'Kiểm tra' });

        expect(input).toBeDisabled();
        expect(checkButton).toBeDisabled();
    });
});
