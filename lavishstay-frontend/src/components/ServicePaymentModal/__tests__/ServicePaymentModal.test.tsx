import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest';
import { render, screen, fireEvent, waitFor } from '@testing-library/react';
import { message } from 'antd';
import axios from 'axios';
import ServicePaymentModal from '../ServicePaymentModal';
import type { ServicePaymentInfo, QRResponse, PaymentCheckResponse } from '../../../types/payment';

// Mock axios
vi.mock('axios');
const mockedAxios = vi.mocked(axios);

// Mock antd message
vi.mock('antd', async () => {
    const actual = await vi.importActual('antd');
    return {
        ...actual,
        message: {
            error: vi.fn(),
            success: vi.fn(),
            info: vi.fn(),
        },
    };
});

const mockPaymentInfo: ServicePaymentInfo = {
    booking_id: 23,
    booking_code: 'LAVISHSTAY_509999',
    services: [
        {
            booking_service_id: 15,
            service_id: 2,
            service_name: 'Dịch vụ spa và massage',
            service_description: 'Spa services',
            quantity: 2,
            unit_price_vnd: '500000.00',
            total_price_vnd: 1000000,
            paid_amount_vnd: '0.00',
            outstanding_amount_vnd: 1000000,
            payment_status: 'pending',
            payment_percentage: 0,
            formatted_total_price: '1.000.000 ₫',
            formatted_paid_amount: '0 ₫',
            formatted_outstanding: '1.000.000 ₫',
        },
        {
            booking_service_id: 16,
            service_id: 5,
            service_name: 'Xe đưa đón sân bay',
            service_description: 'Airport transfer',
            quantity: 1,
            unit_price_vnd: '300000.00',
            total_price_vnd: 300000,
            paid_amount_vnd: '150000.00',
            outstanding_amount_vnd: 150000,
            payment_status: 'partial',
            payment_percentage: 50,
            formatted_total_price: '300.000 ₫',
            formatted_paid_amount: '150.000 ₫',
            formatted_outstanding: '150.000 ₫',
        },
        {
            booking_service_id: 17,
            service_id: 3,
            service_name: 'Dịch vụ giặt ủi',
            service_description: 'Laundry service',
            quantity: 3,
            unit_price_vnd: '150000.00',
            total_price_vnd: 450000,
            paid_amount_vnd: '450000.00',
            outstanding_amount_vnd: 0,
            payment_status: 'paid',
            payment_percentage: 100,
            formatted_total_price: '450.000 ₫',
            formatted_paid_amount: '450.000 ₫',
            formatted_outstanding: '0 ₫',
        },
    ],
    summary: {
        total_service_amount: 1750000,
        total_paid_amount: 600000,
        total_outstanding: 1150000,
        payment_percentage: 34.29,
        services_count: 3,
        pending_services: 1,
        partial_services: 1,
        paid_services: 1,
    },
};

const mockQRResponse: QRResponse = {
    payment_id: 143,
    qr_url: 'https://img.vietqr.io/image/test-qr.png',
    amount: 100000,
    formatted_amount: '100.000 ₫',
    payment_content: 'LVSS LAVISHSTAY_509999 143',
    transaction_id: 'SERVICE_LAVISHSTAY_509999_1755784151',
    bank_info: {
        bank_id: 'MBBank',
        account_no: '0335920306',
        account_name: 'NGUYEN VAN QUYEN',
    },
    selected_services: [15, 16],
    expires_at: new Date(Date.now() + 15 * 60 * 1000).toISOString(), // 15 minutes from now
};

const mockPaymentCheckSuccess: PaymentCheckResponse = {
    success: true,
    payment_found: true,
    message: 'Payment confirmed successfully',
    payment: {
        payment_id: 143,
        amount: 100000,
        status: 'completed',
        transaction_id: 'CPAY_SERVICE_LAVISHSTAY_509999_123',
        confirmed_at: new Date().toISOString(),
    },
};

const mockPaymentCheckNotFound: PaymentCheckResponse = {
    success: true,
    payment_found: false,
    message: 'Payment not found',
};

describe('ServicePaymentModal', () => {
    const defaultProps = {
        bookingId: 23,
        bookingCode: 'LAVISHSTAY_509999',
        visible: true,
        onClose: vi.fn(),
        onSuccess: vi.fn(),
    };

    beforeEach(() => {
        vi.clearAllMocks();
        vi.useFakeTimers();
    });

    afterEach(() => {
        vi.useRealTimers();
    });

    it('renders modal with loading state initially', () => {
        render(<ServicePaymentModal {...defaultProps} />);

        expect(screen.getByText('Thanh toán dịch vụ phát sinh - LAVISHSTAY_509999')).toBeInTheDocument();
        expect(screen.getByTestId('loading-icon')).toBeInTheDocument();
    });

    it('loads payment info and auto-selects pending/partial services', async () => {
        mockedAxios.get.mockResolvedValueOnce({
            data: { success: true, data: mockPaymentInfo },
        });

        render(<ServicePaymentModal {...defaultProps} />);

        await waitFor(() => {
            expect(screen.getByText('Chọn dịch vụ và số tiền thanh toán')).toBeInTheDocument();
        });

        // Check that pending and partial services are auto-selected
        const spaCheckbox = screen.getByRole('checkbox', { name: /dịch vụ spa và massage/i });
        const transferCheckbox = screen.getByRole('checkbox', { name: /xe đưa đón sân bay/i });
        const laundryCheckbox = screen.getByRole('checkbox', { name: /dịch vụ giặt ủi/i });

        expect(spaCheckbox).toBeChecked(); // pending service
        expect(transferCheckbox).toBeChecked(); // partial service
        expect(laundryCheckbox).not.toBeChecked(); // paid service should be disabled
        expect(laundryCheckbox).toBeDisabled();

        expect(mockedAxios.get).toHaveBeenCalledWith('/api/reception/bookings/23/services/payment-info');
    });

    it('completes happy path: select services → generate QR → check payment → success', async () => {
        // Mock API calls
        mockedAxios.get.mockResolvedValueOnce({
            data: { success: true, data: mockPaymentInfo },
        });
        mockedAxios.post
            .mockResolvedValueOnce({ data: { success: true, data: mockQRResponse } }) // Generate QR
            .mockResolvedValueOnce(mockPaymentCheckSuccess) // Check payment
            .mockResolvedValueOnce({ data: { success: true, data: mockPaymentInfo } }); // Refresh payment info

        render(<ServicePaymentModal {...defaultProps} />);

        // Wait for payment info to load
        await waitFor(() => {
            expect(screen.getByText('Chọn dịch vụ và số tiền thanh toán')).toBeInTheDocument();
        });

        // Step 1: Continue to next step
        const continueButton = screen.getByRole('button', { name: /tiếp tục/i });
        expect(continueButton).toBeEnabled();
        fireEvent.click(continueButton);

        // Step 2: Generate QR
        await waitFor(() => {
            expect(screen.getByText('Xác nhận thông tin thanh toán')).toBeInTheDocument();
        });

        const generateQRButton = screen.getByRole('button', { name: /tạo mã qr/i });
        fireEvent.click(generateQRButton);

        // Step 3: QR displayed
        await waitFor(() => {
            expect(screen.getByText('Quét mã QR để thanh toán')).toBeInTheDocument();
            expect(screen.getByAltText(/VietQR for payment/i)).toBeInTheDocument();
        });

        // Check payment manually
        const checkPaymentButton = screen.getByRole('button', { name: /kiểm tra thanh toán/i });
        fireEvent.click(checkPaymentButton);

        // Wait for success
        await waitFor(() => {
            expect(screen.getByText('Thanh toán thành công!')).toBeInTheDocument();
        });

        // Verify API calls
        expect(mockedAxios.post).toHaveBeenCalledWith(
            '/api/reception/bookings/23/services/payment/qr',
            expect.objectContaining({
                amount: 1150000, // Outstanding amount for selected services
                service_ids: [15, 16], // Auto-selected pending and partial services
            })
        );

        expect(mockedAxios.post).toHaveBeenCalledWith(
            '/api/reception/bookings/services/payment/check',
            expect.objectContaining({
                payment_id: 143,
                booking_code: 'LAVISHSTAY_509999',
                amount: 1150000,
                service_ids: [15, 16],
            })
        );

        // Wait for auto-close and success callback
        vi.advanceTimersByTime(2000);
        await waitFor(() => {
            expect(defaultProps.onSuccess).toHaveBeenCalledWith(mockPaymentCheckSuccess);
            expect(defaultProps.onClose).toHaveBeenCalled();
        });
    });

    it('handles partial payment allocation edge case', async () => {
        const partialAllocationResponse: PaymentCheckResponse = {
            success: true,
            payment_found: true,
            message: 'Payment found but partially allocated',
            payment: {
                payment_id: 143,
                amount: 50000, // Less than requested amount
                status: 'completed',
                transaction_id: 'CPAY_PARTIAL_123',
                confirmed_at: new Date().toISOString(),
            },
        };

        // Mock payment info with updated amounts after partial allocation
        const updatedPaymentInfo = {
            ...mockPaymentInfo,
            services: mockPaymentInfo.services.map(service =>
                service.booking_service_id === 15
                    ? { ...service, paid_amount_vnd: '50000.00', outstanding_amount_vnd: 950000, payment_status: 'partial' as const }
                    : service
            ),
        };

        mockedAxios.get.mockResolvedValueOnce({
            data: { success: true, data: mockPaymentInfo },
        });
        mockedAxios.post
            .mockResolvedValueOnce({ data: { success: true, data: mockQRResponse } })
            .mockResolvedValueOnce(partialAllocationResponse)
            .mockResolvedValueOnce({ data: { success: true, data: updatedPaymentInfo } });

        render(<ServicePaymentModal {...defaultProps} />);

        // Navigate through the flow
        await waitFor(() => {
            expect(screen.getByText('Chọn dịch vụ và số tiền thanh toán')).toBeInTheDocument();
        });

        fireEvent.click(screen.getByRole('button', { name: /tiếp tục/i }));

        await waitFor(() => {
            expect(screen.getByText('Xác nhận thông tin thanh toán')).toBeInTheDocument();
        });

        fireEvent.click(screen.getByRole('button', { name: /tạo mã qr/i }));

        await waitFor(() => {
            expect(screen.getByText('Quét mã QR để thanh toán')).toBeInTheDocument();
        });

        fireEvent.click(screen.getByRole('button', { name: /kiểm tra thanh toán/i }));

        // Should still show success even with partial allocation
        await waitFor(() => {
            expect(screen.getByText('Thanh toán thành công!')).toBeInTheDocument();
        });

        expect(message.success).toHaveBeenCalledWith('Thanh toán thành công!');
    });

    it('handles custom amount input', async () => {
        mockedAxios.get.mockResolvedValueOnce({
            data: { success: true, data: mockPaymentInfo },
        });

        render(<ServicePaymentModal {...defaultProps} />);

        await waitFor(() => {
            expect(screen.getByText('Chọn dịch vụ và số tiền thanh toán')).toBeInTheDocument();
        });

        // Input custom amount
        const customAmountInput = screen.getByPlaceholderText('Nhập số tiền');
        fireEvent.change(customAmountInput, { target: { value: '500000' } });

        // Continue button should be enabled with valid custom amount
        const continueButton = screen.getByRole('button', { name: /tiếp tục/i });
        expect(continueButton).toBeEnabled();
    });

    it('disables continue button when amount is below minimum', async () => {
        mockedAxios.get.mockResolvedValueOnce({
            data: { success: true, data: mockPaymentInfo },
        });

        render(<ServicePaymentModal {...defaultProps} />);

        await waitFor(() => {
            expect(screen.getByText('Chọn dịch vụ và số tiền thanh toán')).toBeInTheDocument();
        });

        // Uncheck all services
        const spaCheckbox = screen.getByRole('checkbox', { name: /dịch vụ spa và massage/i });
        const transferCheckbox = screen.getByRole('checkbox', { name: /xe đưa đón sân bay/i });

        fireEvent.click(spaCheckbox);
        fireEvent.click(transferCheckbox);

        // Input amount below minimum
        const customAmountInput = screen.getByPlaceholderText('Nhập số tiền');
        fireEvent.change(customAmountInput, { target: { value: '500' } });

        const continueButton = screen.getByRole('button', { name: /tiếp tục/i });
        expect(continueButton).toBeDisabled();
    });

    it('handles payment check timeout and allows regenerating QR', async () => {
        mockedAxios.get.mockResolvedValueOnce({
            data: { success: true, data: mockPaymentInfo },
        });
        mockedAxios.post
            .mockResolvedValueOnce({ data: { success: true, data: mockQRResponse } })
            .mockResolvedValue(mockPaymentCheckNotFound); // Always return not found

        render(<ServicePaymentModal {...defaultProps} />);

        // Navigate to QR step
        await waitFor(() => {
            expect(screen.getByText('Chọn dịch vụ và số tiền thanh toán')).toBeInTheDocument();
        });

        fireEvent.click(screen.getByRole('button', { name: /tiếp tục/i }));

        await waitFor(() => {
            expect(screen.getByText('Xác nhận thông tin thanh toán')).toBeInTheDocument();
        });

        fireEvent.click(screen.getByRole('button', { name: /tạo mã qr/i }));

        await waitFor(() => {
            expect(screen.getByText('Quét mã QR để thanh toán')).toBeInTheDocument();
        });

        // Simulate QR expiry (advance time by 15 minutes)
        vi.advanceTimersByTime(15 * 60 * 1000);

        await waitFor(() => {
            expect(screen.getByRole('button', { name: /tạo qr mới/i })).toBeInTheDocument();
        });

        // Click regenerate QR
        fireEvent.click(screen.getByRole('button', { name: /tạo qr mới/i }));

        // Should go back to step 2
        await waitFor(() => {
            expect(screen.getByText('Xác nhận thông tin thanh toán')).toBeInTheDocument();
        });
    });

    it('handles API errors gracefully', async () => {
        mockedAxios.get.mockRejectedValueOnce(new Error('Network error'));

        render(<ServicePaymentModal {...defaultProps} />);

        await waitFor(() => {
            expect(screen.getByText('Lỗi')).toBeInTheDocument();
            expect(screen.getByText('Network error')).toBeInTheDocument();
        });

        // Should show retry button
        expect(screen.getByRole('button', { name: /thử lại/i })).toBeInTheDocument();
    });

    it('shows message when booking has no services', async () => {
        const emptyPaymentInfo = { ...mockPaymentInfo, services: [] };
        mockedAxios.get.mockResolvedValueOnce({
            data: { success: true, data: emptyPaymentInfo },
        });

        render(<ServicePaymentModal {...defaultProps} />);

        await waitFor(() => {
            expect(screen.getByText('Không có dịch vụ')).toBeInTheDocument();
            expect(screen.getByText('Đơn đặt phòng này không có dịch vụ phát sinh nào.')).toBeInTheDocument();
        });
    });
});
