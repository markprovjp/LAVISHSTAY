# Anti-Spam Booking System

## Tính năng chính

### 1. Session Reuse

- **Mục đích**: Tránh tạo multiple booking codes cho cùng một request
- **Cách hoạt động**:
  - Khi user nhập thông tin và tiến hành thanh toán, hệ thống tạo booking code
  - Nếu user quay lại step trước rồi lại tiến hành, hệ thống sẽ reuse booking code cũ nếu:
    - Rooms selection không thay đổi
    - Search data (ngày, số khách) không thay đổi
    - Session chưa hết hạn (30 phút)

### 2. Rate Limiting

- **Giới hạn**: 5 booking attempts trong 5 phút
- **Cooldown**: 15 phút khi vượt quá giới hạn
- **Reset**: Tự động reset sau time window hoặc cooldown period

### 3. Smart Notifications

- Cảnh báo khi gần đạt giới hạn (3+ attempts)
- Thông báo reuse booking code
- Countdown timer khi bị khóa
- Rate limit notification với thời gian còn lại

### 4. Debug Panel (Development only)

- Hiển thị session info
- Hiển thị rate limit status
- Buttons để clear data
- Real-time updates

## Cách sử dụng

### Trong component Payment.tsx

```tsx
const {
  bookingCode,
  isProcessing,
  canProceed,
  cooldownInfo,
  createBooking,
  resetBooking,
  getSessionInfo,
} = useBookingManager();

// Sử dụng trong form submit
const handleSubmit = async (values: any) => {
  if (!canProceed) {
    message.error("Không thể tiến hành đặt phòng lúc này");
    return;
  }

  await createBooking(customerData);
};
```

### Anti-spam logic flow

```
1. User clicks "Tiếp tục thanh toán"
2. System checks eligibility:
   - Rate limit check
   - Session existence check
   - Data comparison check
3. If eligible:
   - Reuse existing booking OR create new booking
   - Update session data
   - Record attempt
4. If not eligible:
   - Show appropriate error/warning
   - Block action
```

## Configuration

### Rate Limit Config (trong bookingAntiSpamService.ts)

```typescript
private readonly rateLimitConfig: RateLimitConfig = {
    maxAttempts: 5, // Max 5 booking attempts
    timeWindow: 5 * 60 * 1000, // Within 5 minutes
    cooldownPeriod: 15 * 60 * 1000, // 15 minutes cooldown
};
```

### Session Config

- **Session timeout**: 30 minutes
- **Storage**: localStorage
- **Keys**:
  - `lavishstay_booking_session` - Session data
  - `lavishstay_rate_limit` - Rate limit data

## Testing

### Manual Testing Scenarios

1. **Normal Flow**

   - Chọn phòng → Nhập info → Thanh toán
   - Should work normally

2. **Session Reuse**

   - Chọn phòng → Nhập info → Back → Nhập info again
   - Should reuse same booking code

3. **Data Change**

   - Chọn phòng → Nhập info → Back → Thay đổi phòng → Nhập info
   - Should create new booking code

4. **Rate Limiting**

   - Thực hiện 5+ lần booking nhanh chóng
   - Should block after 5 attempts
   - Should show cooldown timer

5. **Cooldown Reset**
   - Wait 15 minutes OR use debug panel to clear
   - Should allow booking again

### Debug Commands (Dev Console)

```javascript
// Check current session
console.log(bookingAntiSpamService.getSessionInfo());

// Clear session
bookingAntiSpamService.clearSession();

// Clear rate limit
bookingAntiSpamService.clearRateLimit();

// Check cooldown
console.log(bookingAntiSpamService.getCooldownInfo());
```

## Benefits

1. **Prevents Spam**: Rate limiting stops automated attacks
2. **Better UX**: Session reuse prevents accidental duplicate bookings
3. **Performance**: Reduces unnecessary API calls
4. **Debugging**: Easy to debug with debug panel
5. **Flexibility**: Easy to configure limits and timeouts

## Security Notes

- Data stored in localStorage (client-side only)
- Server-side validation still required
- Consider adding CAPTCHA for additional security
- Monitor server logs for suspicious patterns
- Consider IP-based rate limiting on server side
