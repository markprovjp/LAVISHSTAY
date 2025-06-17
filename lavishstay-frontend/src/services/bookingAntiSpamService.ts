// Anti-spam booking service
import { message } from 'antd';

interface BookingSession {
    bookingCode: string;
    roomsHash: string;
    searchDataHash: string;
    preferencesHash: string; // Add preferences hash
    totalsHash: string; // Add totals hash
    timestamp: number;
    attempts: number;
    lastAttempt: number;
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
        return btoa(JSON.stringify(data)).slice(0, 16);
    }

    // Get current booking session
    private getCurrentSession(): BookingSession | null {
        try {
            const session = localStorage.getItem(this.STORAGE_KEY);
            return session ? JSON.parse(session) : null;
        } catch {
            return null;
        }
    }

    // Save booking session
    private saveSession(session: BookingSession): void {
        try {
            localStorage.setItem(this.STORAGE_KEY, JSON.stringify(session));
        } catch (error) {
            console.error('Failed to save booking session:', error);
        }
    }

    // Check rate limiting
    private checkRateLimit(): { allowed: boolean; remainingTime?: number } {
        try {
            const rateLimitData = localStorage.getItem(this.RATE_LIMIT_KEY);
            if (!rateLimitData) {
                return { allowed: true };
            }

            const { attempts, firstAttempt } = JSON.parse(rateLimitData);
            const now = Date.now();

            // Check if we're in cooldown period
            if (attempts >= this.rateLimitConfig.maxAttempts) {
                const timeSinceFirst = now - firstAttempt;
                if (timeSinceFirst < this.rateLimitConfig.cooldownPeriod) {
                    const remainingTime = this.rateLimitConfig.cooldownPeriod - timeSinceFirst;
                    return { allowed: false, remainingTime };
                }
                // Cooldown period passed, reset
                localStorage.removeItem(this.RATE_LIMIT_KEY);
                return { allowed: true };
            }

            // Check if we're within time window
            const timeWindow = now - firstAttempt;
            if (timeWindow > this.rateLimitConfig.timeWindow) {
                // Time window passed, reset
                localStorage.removeItem(this.RATE_LIMIT_KEY);
                return { allowed: true };
            }

            return { allowed: true };
        } catch {
            return { allowed: true };
        }
    }

    // Record booking attempt
    private recordAttempt(): void {
        try {
            const now = Date.now();
            const rateLimitData = localStorage.getItem(this.RATE_LIMIT_KEY);

            if (!rateLimitData) {
                localStorage.setItem(this.RATE_LIMIT_KEY, JSON.stringify({
                    attempts: 1,
                    firstAttempt: now,
                    lastAttempt: now
                }));
                return;
            }

            const data = JSON.parse(rateLimitData);
            data.attempts += 1;
            data.lastAttempt = now;

            localStorage.setItem(this.RATE_LIMIT_KEY, JSON.stringify(data));
        } catch (error) {
            console.error('Failed to record attempt:', error);
        }
    }

    // Check if user can create booking
    public checkBookingEligibility(
        selectedRooms: any,
        searchData: any,
        preferences?: any,
        totals?: any
    ): {
        canProceed: boolean;
        reason?: string;
        remainingTime?: number;
        existingBookingCode?: string;
    } {
        // Check rate limiting first
        const rateLimitCheck = this.checkRateLimit();
        if (!rateLimitCheck.allowed) {
            return {
                canProceed: false,
                reason: 'Bạn đã vượt quá giới hạn đặt phòng',
                remainingTime: rateLimitCheck.remainingTime
            };
        }

        // Generate hashes for current data - include ALL state components that affect pricing
        const roomsHash = this.generateHash(selectedRooms);
        const searchDataHash = this.generateHash({
            location: searchData.location,
            dateRange: searchData.dateRange,
            checkIn: searchData.checkIn,
            checkOut: searchData.checkOut,
            guests: searchData.guests,
            guestDetails: searchData.guestDetails,
            guestType: searchData.guestType
        });
        const preferencesHash = this.generateHash(preferences || {});
        const totalsHash = this.generateHash(totals || {});

        // Debug logging (dev only)
        if (import.meta.env.DEV) {
            console.log('🔍 Anti-spam hash check:', {
                roomsHash,
                searchDataHash,
                preferencesHash,
                totalsHash,
                selectedRooms,
                searchData,
                preferences,
                totals
            });
        }

        // Get current session
        const currentSession = this.getCurrentSession();

        // If no session exists, can proceed with new booking
        if (!currentSession) {
            return { canProceed: true };
        }

        // Check if session is expired (older than 30 minutes)
        const sessionAge = Date.now() - currentSession.timestamp;
        if (sessionAge > 30 * 60 * 1000) {
            this.clearSession();
            return { canProceed: true };
        }

        // Check if ANY data changed - rooms, search, preferences, or totals
        if (currentSession.roomsHash !== roomsHash ||
            currentSession.searchDataHash !== searchDataHash ||
            currentSession.preferencesHash !== preferencesHash ||
            currentSession.totalsHash !== totalsHash) {

            // Debug which part changed (dev only)
            if (import.meta.env.DEV) {
                console.log('🔄 Data changed, clearing old session:', {
                    roomsChanged: currentSession.roomsHash !== roomsHash,
                    searchChanged: currentSession.searchDataHash !== searchDataHash,
                    preferencesChanged: currentSession.preferencesHash !== preferencesHash,
                    totalsChanged: currentSession.totalsHash !== totalsHash,
                    oldSession: currentSession,
                    newHashes: { roomsHash, searchDataHash, preferencesHash, totalsHash }
                });
            }

            // Clear old session since data changed
            this.clearSession();
            return { canProceed: true };
        }

        // Same data - reuse existing booking
        return {
            canProceed: true,
            existingBookingCode: currentSession.bookingCode
        };
    }

    // Create or get booking code
    public async processBooking(
        selectedRooms: any,
        searchData: any,
        customerData?: any,
        preferences?: any,
        totals?: any
    ): Promise<{ bookingCode: string; isReused: boolean }> {
        const eligibility = this.checkBookingEligibility(selectedRooms, searchData, preferences, totals);

        if (!eligibility.canProceed) {
            throw new Error(eligibility.reason || 'Không thể tạo đặt phòng');
        }

        // Record this attempt
        this.recordAttempt();

        // If we can reuse existing booking
        if (eligibility.existingBookingCode) {
            message.info('Sử dụng lại thông tin đặt phòng trước đó');
            return {
                bookingCode: eligibility.existingBookingCode,
                isReused: true
            };
        }

        // Create new booking
        const bookingCode = await this.createNewBooking(selectedRooms, searchData, customerData);

        // Save session with all hash components
        const session: BookingSession = {
            bookingCode,
            roomsHash: this.generateHash(selectedRooms),
            searchDataHash: this.generateHash({
                location: searchData.location,
                dateRange: searchData.dateRange,
                checkIn: searchData.checkIn,
                checkOut: searchData.checkOut,
                guests: searchData.guests,
                guestDetails: searchData.guestDetails,
                guestType: searchData.guestType
            }),
            preferencesHash: this.generateHash(preferences || {}),
            totalsHash: this.generateHash(totals || {}),
            timestamp: Date.now(),
            attempts: 1,
            lastAttempt: Date.now()
        };

        this.saveSession(session);

        return {
            bookingCode,
            isReused: false
        };
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

            // Make actual API call to backend
            const response = await fetch('http://localhost:8888/api/payment/create-booking', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    booking_code: bookingCode,
                    customer_name: customerData?.fullName || 'Guest User',
                    customer_email: customerData?.email || 'guest@example.com',
                    customer_phone: customerData?.phone || '0000000000',
                    rooms_data: JSON.stringify({
                        rooms: selectedRooms,
                        searchData: searchData
                    }),
                    total_amount: customerData?.totalAmount || 0, // Use passed total amount
                    payment_method: 'vietqr',
                    check_in: searchData.checkIn,
                    check_out: searchData.checkOut,
                })
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
            }

            await response.json(); // Consume response but don't need to use it

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
            console.error('Failed to clear session:', error);
        }
    }

    // Clear rate limit data
    public clearRateLimit(): void {
        try {
            localStorage.removeItem(this.RATE_LIMIT_KEY);
        } catch (error) {
            console.error('Failed to clear rate limit:', error);
        }
    }

    // Get session info for debugging
    public getSessionInfo(): { session: BookingSession | null; rateLimit: any } {
        try {
            const session = this.getCurrentSession();
            const rateLimitData = localStorage.getItem(this.RATE_LIMIT_KEY);
            const rateLimit = rateLimitData ? JSON.parse(rateLimitData) : null;

            return { session, rateLimit };
        } catch {
            return { session: null, rateLimit: null };
        }
    }

    // Get cooldown info
    public getCooldownInfo(): { inCooldown: boolean; remainingTime?: number } {
        const rateLimitCheck = this.checkRateLimit();
        return {
            inCooldown: !rateLimitCheck.allowed,
            remainingTime: rateLimitCheck.remainingTime
        };
    }

    // Force rate limit for testing (dev only)
    public forceRateLimit(): void {
        if (import.meta.env.DEV) {
            const now = Date.now();
            localStorage.setItem(this.RATE_LIMIT_KEY, JSON.stringify({
                attempts: this.rateLimitConfig.maxAttempts,
                firstAttempt: now,
                lastAttempt: now
            }));
        }
    }

    // Simulate spam attempts for testing (dev only)
    public simulateSpamAttempts(count: number = 3): void {
        if (import.meta.env.DEV) {
            const now = Date.now();
            localStorage.setItem(this.RATE_LIMIT_KEY, JSON.stringify({
                attempts: count,
                firstAttempt: now - (this.rateLimitConfig.timeWindow / 2), // Half window ago
                lastAttempt: now
            }));
        }
    }
}

// Export singleton instance
export const bookingAntiSpamService = new BookingAntiSpamService();
