// Payment verification service using CPay Google Sheets
export interface PaymentTransaction {
    bank: string;
    transaction_date: string;
    account_number: string;
    content: string;
    type: string;
    amount: number;
    reference_code: string;
    order_code: string | null;
}

export interface PaymentCheckResponse {
    status: 'success' | 'error';
    data?: PaymentTransaction[];
    message?: string;
}

class PaymentService {
    private readonly API_BASE_URL = 'http://localhost:8888/api';

    /**
     * Check payment via backend API (which calls CPay)
     */
    async findPaymentByBookingCode(bookingCode: string, expectedAmount: number): Promise<{
        found: boolean;
        transaction?: PaymentTransaction;
        message: string;
    }> {
        try {
            console.log('🔍 Checking payment for booking:', bookingCode, 'Amount:', expectedAmount);

            const response = await fetch(`${this.API_BASE_URL}/payment/check-cpay`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    booking_code: bookingCode,
                    amount: expectedAmount
                })
            });

            console.log('📡 Payment check response status:', response.status);

            if (!response.ok) {
                // Get error details from response
                let errorMessage = `HTTP error! status: ${response.status}`;
                try {
                    const errorData = await response.json();
                    errorMessage = errorData.message || errorMessage;
                } catch {
                    // If response is not JSON, use default message
                }
                throw new Error(errorMessage);
            }

            const result = await response.json();

            console.log('🔍 CPay backend response:', result);

            if (result.success && result.transaction) {
                // Payment found and verified
                return {
                    found: true,
                    transaction: result.transaction,
                    message: result.message || 'Payment verified successfully'
                };
            } else {
                // Payment not found or not verified yet
                return {
                    found: false,
                    message: result.message || 'Payment not found'
                };
            }
        } catch (error) {
            console.error('Error checking payment:', error);
            return {
                found: false,
                message: 'Lỗi khi kiểm tra giao dịch thanh toán'
            };
        }
    }

    /**
     * Auto check payment with retry mechanism
     */
    async autoCheckPayment(
        bookingCode: string,
        expectedAmount: number,
        maxRetries: number = 5,
        retryInterval: number = 10000 // 10 seconds
    ): Promise<{
        found: boolean;
        transaction?: PaymentTransaction;
        message: string;
    }> {
        for (let attempt = 1; attempt <= maxRetries; attempt++) {
            console.log(`Checking payment attempt ${attempt}/${maxRetries} for booking ${bookingCode}`);

            const result = await this.findPaymentByBookingCode(bookingCode, expectedAmount);

            if (result.found) {
                return result;
            }

            // If not the last attempt, wait before retrying
            if (attempt < maxRetries) {
                await new Promise(resolve => setTimeout(resolve, retryInterval));
            }
        }

        return {
            found: false,
            message: `Không tìm thấy giao dịch thanh toán sau ${maxRetries} lần kiểm tra`
        };
    }
}

export const paymentService = new PaymentService();
