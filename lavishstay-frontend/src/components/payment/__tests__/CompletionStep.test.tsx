import React from 'react';
import { render, screen, waitFor, fireEvent } from '@testing-library/react';
import { Provider } from 'react-redux';
import { configureStore } from '@reduxjs/toolkit';
import CompletionStep from '../CompletionStep';

// Mock authService
jest.mock('../../../services/authService', () => ({
    isAuthenticated: jest.fn(() => false),
    register: jest.fn(),
    login: jest.fn(),
}));

// Mock axios instance
jest.mock('../../../services/axiosInstance', () => ({
    post: jest.fn(),
}));

// Mock fetch
const mockFetch = jest.fn();
global.fetch = mockFetch;

// Mock store
const createMockStore = () => configureStore({
    reducer: {
        booking: (state = { selectedRooms: {}, totals: {} }) => state,
        search: (state = {}) => state,
    },
});

const mockBookingData = {
    success: true,
    data: {
        booking: {
            booking_code: 'TEST123',
            booking_id: 1,
            created_at: '2025-08-24T10:00:00Z',
            status: 'confirmed',
            guest_name: 'John Doe',
            guest_email: 'john@example.com',
            guest_phone: '0123456789',
            check_in_date: '2025-08-25',
            check_out_date: '2025-08-27',
            total_price_vnd: 2000000,
            payment_status: 'completed'
        },
        rooms: [
            {
                room_name: 'Deluxe Room',
                selected_option_name: 'Standard Package',
                selected_option_price: 1000000,
                adults: 2,
                children: 0,
                representative_name: 'John Doe',
                representative_phone: '0123456789',
                cancellation_policy_type: 'flexible'
            }
        ],
        payment: {
            payment_method: 'vietqr',
            status: 'completed',
            qr_code_url: null
        },
        summary: {
            total_rooms: 1,
            total_guests: 2,
            total_amount: 2000000,
            payment_status: 'completed'
        }
    }
};

const defaultProps = {
    bookingCode: 'TEST123',
    selectedPaymentMethod: 'vietqr',
    onViewBookings: jest.fn(),
    onNewBooking: jest.fn(),
};

describe('CompletionStep Component', () => {
    beforeEach(() => {
        jest.clearAllMocks();
        mockFetch.mockClear();
    });

    test('renders booking details successfully (happy path)', async () => {
        mockFetch.mockResolvedValueOnce({
            ok: true,
            json: async () => mockBookingData
        });

        const store = createMockStore();

        render(
            <Provider store={store}>
                <CompletionStep {...defaultProps} />
            </Provider>
        );

        // Loading state
        expect(screen.getByText(/Đang tải thông tin đặt phòng/)).toBeInTheDocument();

        // Wait for data to load
        await waitFor(() => {
            expect(screen.getByText(/Mã đặt phòng: TEST123/)).toBeInTheDocument();
        });

        // Check booking code
        expect(screen.getByText('TEST123')).toBeInTheDocument();

        // Check room card
        expect(screen.getByText('Deluxe Room')).toBeInTheDocument();
        expect(screen.getByText('Standard Package')).toBeInTheDocument();

        // Check totals
        expect(screen.getByText(/2\.000\.000/)).toBeInTheDocument();

        // Check customer info
        expect(screen.getByText('John Doe')).toBeInTheDocument();
        expect(screen.getByText('john@example.com')).toBeInTheDocument();
    });

    test('handles payment verification flow', async () => {
        const pendingBookingData = {
            ...mockBookingData,
            data: {
                ...mockBookingData.data,
                booking: {
                    ...mockBookingData.data.booking,
                    payment_status: 'pending'
                },
                payment: {
                    payment_method: 'vietqr',
                    status: 'pending',
                    qr_code_url: 'http://example.com/qr.png'
                }
            }
        };

        // Mock initial fetch
        mockFetch
            .mockResolvedValueOnce({
                ok: true,
                json: async () => pendingBookingData
            })
            // Mock verify payment
            .mockResolvedValueOnce({
                ok: true,
                json: async () => ({ success: true, message: 'Payment verified' })
            })
            // Mock refresh after verify
            .mockResolvedValueOnce({
                ok: true,
                json: async () => mockBookingData
            });

        const store = createMockStore();

        render(
            <Provider store={store}>
                <CompletionStep {...defaultProps} />
            </Provider>
        );

        // Wait for initial load
        await waitFor(() => {
            expect(screen.getByText(/Mã đặt phòng: TEST123/)).toBeInTheDocument();
        });

        // Check payment pending status
        expect(screen.getByText('Chờ xử lý')).toBeInTheDocument();

        // Click verify payment button
        const verifyButton = screen.getByText('Đã thanh toán');
        fireEvent.click(verifyButton);

        // Wait for verification to complete
        await waitFor(() => {
            expect(mockFetch).toHaveBeenCalledWith(
                'http://localhost:8888/api/payment/verify-vietqr',
                expect.objectContaining({
                    method: 'POST',
                    body: JSON.stringify({ booking_code: 'TEST123' })
                })
            );
        });

        // Should refresh booking details after successful verification
        await waitFor(() => {
            expect(screen.getByText('Hoàn thành')).toBeInTheDocument();
        });
    });

    test('handles retry on fetch error', async () => {
        mockFetch
            .mockRejectedValueOnce(new Error('Network error'))
            .mockResolvedValueOnce({
                ok: true,
                json: async () => mockBookingData
            });

        const store = createMockStore();

        render(
            <Provider store={store}>
                <CompletionStep {...defaultProps} />
            </Provider>
        );

        // Wait for error state
        await waitFor(() => {
            expect(screen.getByText(/Không thể tải thông tin đặt phòng/)).toBeInTheDocument();
        });

        // Click retry button
        const retryButton = screen.getByText('Thử lại');
        fireEvent.click(retryButton);

        // Should load successfully on retry
        await waitFor(() => {
            expect(screen.getByText(/Mã đặt phòng: TEST123/)).toBeInTheDocument();
        });
    });

    test('renders action buttons correctly', async () => {
        mockFetch.mockResolvedValueOnce({
            ok: true,
            json: async () => mockBookingData
        });

        const store = createMockStore();

        render(
            <Provider store={store}>
                <CompletionStep {...defaultProps} />
            </Provider>
        );

        await waitFor(() => {
            expect(screen.getByText(/Mã đặt phòng: TEST123/)).toBeInTheDocument();
        });

        // Check action buttons
        expect(screen.getByText('Sao chép mã')).toBeInTheDocument();
        expect(screen.getByText('Tải hóa đơn')).toBeInTheDocument();
        expect(screen.getByText('In')).toBeInTheDocument();
        expect(screen.getByText('Gửi lại email')).toBeInTheDocument();
    });
});
