// Anti-spam booking service
import { message } from 'antd';
import { ApiService, API_ENDPOINTS } from './apiService';

// Interface definitions
interface BookingSession {
    id: string;
    roomsHash: string;
    searchHash: string;
    timestamp: number;
    bookingCode: string;
    expiresAt: number;
}

interface RateLimitConfig {
    maxAttempts: number;
    timeWindow: number; // in milliseconds
    cooldownPeriod: number; // in milliseconds
}

class BookingAntiSpamService {
    private readonly STORAGE_KEY = 'lavishstay_booking_session';
    private readonly RATE_LIMIT_KEY = 'lavishstay_rate_limit';

    // Rate limiting configuration
    private readonly rateLimitConfig: RateLimitConfig = {
        maxAttempts: 5, // Max 5 booking attempts
        timeWindow: 5 * 60 * 1000, // Within 5 minutes
        cooldownPeriod: 15 * 60 * 1000, // 15 minutes cooldown
    };

    // Generate hash for rooms and search data to detect changes
    private generateHash(data: any): string {
        return btoa(JSON.stringify(data)).replace(/[^a-zA-Z0-9]/g, '').substring(0, 16);
    }

    // Check if we're in rate limit cooldown
    private checkRateLimit(): boolean {
        try {
            const rateLimitData = localStorage.getItem(this.RATE_LIMIT_KEY);
            if (!rateLimitData) return false;

            const { attempts, firstAttempt } = JSON.parse(rateLimitData);
            const now = Date.now();

            // Reset if time window has passed
            if (now - firstAttempt > this.rateLimitConfig.timeWindow) {
                localStorage.removeItem(this.RATE_LIMIT_KEY);
                return false;
            }

            // Check if exceeded max attempts
            if (attempts >= this.rateLimitConfig.maxAttempts) {
                const timeRemaining = this.rateLimitConfig.cooldownPeriod - (now - firstAttempt);
                if (timeRemaining > 0) {
                    const minutes = Math.ceil(timeRemaining / (60 * 1000));
                    message.warning(`Bạn đã thực hiện quá nhiều yêu cầu. Vui lòng thử lại sau ${minutes} phút.`);
                    return true;
                }
                // Cooldown period has passed
                localStorage.removeItem(this.RATE_LIMIT_KEY);
                return false;
            }

            return false;
        } catch (error) {
            console.error('Error checking rate limit:', error);
            return false;
        }
    }

    // Update rate limit counter
    private updateRateLimit(): void {
        try {
            const now = Date.now();
            const rateLimitData = localStorage.getItem(this.RATE_LIMIT_KEY);

            if (!rateLimitData) {
                localStorage.setItem(this.RATE_LIMIT_KEY, JSON.stringify({
                    attempts: 1,
                    firstAttempt: now
                }));
            } else {
                const { attempts, firstAttempt } = JSON.parse(rateLimitData);
                localStorage.setItem(this.RATE_LIMIT_KEY, JSON.stringify({
                    attempts: attempts + 1,
                    firstAttempt
                }));
            }
        } catch (error) {
            console.error('Error updating rate limit:', error);
        }
    }

    // Get existing session
    private getSession(): BookingSession | null {
        try {
            const sessionData = localStorage.getItem(this.STORAGE_KEY);
            if (!sessionData) return null;

            const session: BookingSession = JSON.parse(sessionData);

            // Check if session has expired
            if (Date.now() > session.expiresAt) {
                localStorage.removeItem(this.STORAGE_KEY);
                return null;
            }

            return session;
        } catch (error) {
            console.error('Error getting session:', error);
            localStorage.removeItem(this.STORAGE_KEY);
            return null;
        }
    }

    // Save session
    private saveSession(session: BookingSession): void {
        try {
            localStorage.setItem(this.STORAGE_KEY, JSON.stringify(session));
        } catch (error) {
            console.error('Error saving session:', error);
        }
    }

    // Main booking method with anti-spam protection
    public async createBooking(
        selectedRooms: any,
        searchData: any,
        customerData?: any
    ): Promise<{ bookingCode: string; isReused: boolean }> {
        // Check rate limiting first
        if (this.checkRateLimit()) {
            throw new Error('Đã vượt quá giới hạn thử nghiệm. Vui lòng thử lại sau.');
        }

        // Generate hashes for rooms and search data
        const roomsHash = this.generateHash(selectedRooms);
        const searchHash = this.generateHash({
            checkIn: searchData.checkIn,
            checkOut: searchData.checkOut,
            guests: searchData.guests,
            roomType: searchData.roomType
        });

        // Check existing session
        const existingSession = this.getSession();

        if (existingSession &&
            existingSession.roomsHash === roomsHash &&
            existingSession.searchHash === searchHash) {

            console.log('Reusing existing booking session:', existingSession.bookingCode);
            message.info('Đang sử dụng lại phiên đặt phòng hiện tại.');

            return {
                bookingCode: existingSession.bookingCode,
                isReused: true
            };
        }

        // Update rate limit counter
        this.updateRateLimit();

        // Create new booking
        try {
            const bookingCode = await this.createNewBooking(selectedRooms, searchData, customerData);

            // Save new session
            const newSession: BookingSession = {
                id: Date.now().toString(),
                roomsHash,
                searchHash,
                timestamp: Date.now(),
                bookingCode,
                expiresAt: Date.now() + (30 * 60 * 1000) // 30 minutes
            };

            this.saveSession(newSession);

            return {
                bookingCode,
                isReused: false
            };
        } catch (error) {
            console.error('Booking creation failed:', error);
            throw error;
        }
    }

    // Create new booking (actual API call)
    private async createNewBooking(
        selectedRooms: any,
        searchData: any,
        customerData?: any
    ): Promise<string> {
        try {
            // Simulate API delay
            await new Promise(resolve => setTimeout(resolve, 1000));

            // Generate booking code
            const bookingCode = `LAVISH${Date.now().toString().slice(-8)}`;

            // Make API call using ApiService (bypasses Mirage for payment)
            const responseData = await ApiService.post(API_ENDPOINTS.PAYMENT.CREATE_BOOKING, {
                booking_code: bookingCode,
                customer_name: customerData?.fullName || 'Guest User',
                customer_email: customerData?.email || 'guest@example.com',
                customer_phone: customerData?.phone || '0000000000',
                rooms_data: JSON.stringify({
                    rooms: selectedRooms,
                    searchData: searchData
                }),
                total_amount: customerData?.totalAmount || 0,
                payment_method: 'vietqr',
                check_in: searchData.checkIn,
                check_out: searchData.checkOut,
            });

            console.log('✅ Booking created successfully:', responseData);
            return bookingCode;
        } catch (error) {
            console.error('Failed to create booking:', error);
            throw new Error('Không thể tạo đặt phòng. Vui lòng thử lại.');
        }
    }

    // Clear current session
    public clearSession(): void {
        try {
            localStorage.removeItem(this.STORAGE_KEY);
        } catch (error) {
            console.error('Error clearing session:', error);
        }
    }

    // Clear rate limit
    public clearRateLimit(): void {
        try {
            localStorage.removeItem(this.RATE_LIMIT_KEY);
        } catch (error) {
            console.error('Error clearing rate limit:', error);
        }
    }

    // Get current session info (for debugging)
    public getSessionInfo(): { session: BookingSession | null; rateLimit: any } {
        try {
            const session = this.getSession();
            const rateLimitData = localStorage.getItem(this.RATE_LIMIT_KEY);

            return {
                session,
                rateLimit: rateLimitData ? JSON.parse(rateLimitData) : null
            };
        } catch (error) {
            return { session: null, rateLimit: null };
        }
    }

    // Check cooldown status
    public getCooldownInfo(): { inCooldown: boolean; remainingTime?: number } {
        try {
            const rateLimitData = localStorage.getItem(this.RATE_LIMIT_KEY);
            if (!rateLimitData) return { inCooldown: false };

            const { attempts, firstAttempt } = JSON.parse(rateLimitData);
            const now = Date.now();

            if (attempts >= this.rateLimitConfig.maxAttempts) {
                const timeRemaining = this.rateLimitConfig.cooldownPeriod - (now - firstAttempt);
                if (timeRemaining > 0) {
                    return { inCooldown: true, remainingTime: timeRemaining };
                }
            }

            return { inCooldown: false };
        } catch (error) {
            return { inCooldown: false };
        }
    }

    // Force rate limit (for testing)
    public forceRateLimit(): void {
        if (import.meta.env.DEV) {
            localStorage.setItem(this.RATE_LIMIT_KEY, JSON.stringify({
                attempts: this.rateLimitConfig.maxAttempts,
                firstAttempt: Date.now()
            }));
        }
    }
}

// Export singleton instance
export const bookingAntiSpamService = new BookingAntiSpamService();
export default bookingAntiSpamService;
