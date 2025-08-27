# Frontend Implementation Notes

## API Changes Summary

### 1. Room Availability API (`GET /api/rooms/available`)

**New Response Fields Added:**

```typescript
interface Room {
  room_id: number;
  room_name: string;
  floor_number: number;
  room_status: string;
  cleaning_ends_at: string | null; // New field
  is_cleaning: boolean; // New computed field
  cleaning_note: string | null; // New field
}
```

### 2. Reception Rooms API (`GET /api/reception/rooms`)

**New Response Fields Added:**

```typescript
interface ReceptionRoom {
  id: number;
  name: string;
  status: string;
  floor: number;
  cleaning_ends_at: string | null; // New field
  is_cleaning: boolean; // New computed field
  cleaning_note: string | null; // New field
  booking_info?: {
    guest_name: string;
    check_in: string;
    check_out: string;
    status: string;
    booking_id: number;
    booking_code: string;
  };
  booking_status?: string;
}
```

## Booking Flow Changes

### 1. POST /api/bookings (Pending Flow)

No changes to request format. Response remains the same, but:

- Booking created with `status = 'Pending'`
- **TTL: 15 minutes** - booking auto-deleted if payment not completed
- Frontend should handle race conditions where booking gets deleted during payment

### 2. Payment Completion

When payment completes successfully:

- Backend updates `status` from 'Pending' to 'Confirmed'
- No frontend changes needed

### 3. Handling Expired Bookings

**Error Scenario**: User tries to complete payment but booking already expired:

```typescript
// Payment API might return 404 or specific error
{
  "error": "Booking not found or expired",
  "code": "BOOKING_EXPIRED",
  "message": "This booking has expired. Please search for rooms again."
}
```

**Frontend Handling**:

```typescript
try {
  const payment = await completePayment(bookingId, paymentData);
} catch (error) {
  if (error.code === "BOOKING_EXPIRED" || error.status === 404) {
    // Redirect back to room search
    message.error("Booking has expired. Please search for rooms again.");
    router.push("/rooms/search");
  } else {
    // Handle other payment errors
    message.error("Payment failed. Please try again.");
  }
}
```

## Room Status Display

### 1. Room Status Badges

Update room status display logic to include cleaning state:

```typescript
const getRoomStatusBadge = (room: ReceptionRoom) => {
  // Priority: cleaning > booking status > room status
  if (room.is_cleaning) {
    return <Badge status="processing" text="Đang dọn" />;
  }

  if (room.booking_status) {
    switch (room.booking_status) {
      case "Operational":
        return <Badge status="success" text="Đang ở" />;
      case "Confirmed":
        return <Badge status="warning" text="Đã đặt" />;
      case "Cleaning":
        return <Badge status="processing" text="Đang dọn" />;
      default:
        return <Badge status="default" text={room.booking_status} />;
    }
  }

  switch (room.status) {
    case "available":
      return <Badge status="success" text="Trống" />;
    case "maintenance":
      return <Badge status="error" text="Bảo trì" />;
    case "out_of_service":
      return <Badge status="error" text="Ngừng phục vụ" />;
    default:
      return <Badge status="default" text={room.status} />;
  }
};
```

### 2. Room Availability Display

```typescript
const isRoomBookable = (room: ReceptionRoom) => {
  // Room is bookable if:
  // - Not cleaning (or cleaning finished)
  // - Status is available
  // - No active booking
  return (
    !room.is_cleaning && room.status === "available" && !room.booking_status
  );
};
```

### 3. Cleaning Time Display

```typescript
const getCleaningTimeInfo = (room: ReceptionRoom) => {
  if (!room.is_cleaning || !room.cleaning_ends_at) {
    return null;
  }

  const endTime = dayjs(room.cleaning_ends_at);
  const now = dayjs();
  const remainingMinutes = endTime.diff(now, "minute");

  if (remainingMinutes <= 0) {
    return "Sắp hoàn thành";
  }

  if (remainingMinutes < 60) {
    return `Còn ${remainingMinutes} phút`;
  }

  const hours = Math.floor(remainingMinutes / 60);
  const minutes = remainingMinutes % 60;
  return `Còn ${hours}h${minutes > 0 ? ` ${minutes}m` : ""}`;
};
```

## Configuration

Frontend may want to display hotel settings. Add to your config:

```typescript
// config/hotel.ts
export const HOTEL_CONFIG = {
  PENDING_TTL_MINUTES: 15,
  CLEANING_DURATION_MINUTES: 120,
  DEFAULT_CHECKIN_TIME: "14:00",
  DEFAULT_CHECKOUT_TIME: "12:00",
};
```

## Error Handling Best Practices

### 1. Booking Expiration

- Always show countdown timer during payment process
- Warn user when < 5 minutes remaining
- Handle graceful fallback when booking expires

### 2. Room State Updates

- Refresh room lists automatically when cleaning status might change
- Use WebSocket or polling for real-time updates in reception dashboard

### 3. User Communication

```typescript
// Show user-friendly messages
const BOOKING_MESSAGES = {
  EXPIRED: "Thời gian giữ phòng đã hết. Vui lòng tìm phòng mới.",
  PAYMENT_TIMEOUT: "Bạn có 15 phút để hoàn tất thanh toán.",
  CLEANING_IN_PROGRESS: "Phòng đang được dọn dẹp và sẽ sẵn sàng sau {time}.",
};
```

## Testing Checklist

### Backend Testing

- [ ] Expired bookings are deleted after 15 minutes
- [ ] Related data (booking_rooms, payments, etc.) are cleaned up
- [ ] Confirmed bookings are never deleted by scheduler
- [ ] Cleaning times are set correctly after checkout
- [ ] is_cleaning field is calculated correctly

### Frontend Testing

- [ ] Booking expiration is handled gracefully
- [ ] Room status badges display correctly
- [ ] Cleaning progress is shown when applicable
- [ ] Payment timeout warnings appear
- [ ] Room search works after booking expiration

### Integration Testing

- [ ] End-to-end booking flow with payment timeout
- [ ] Checkout → cleaning → room available flow
- [ ] Concurrent booking attempts on same room
- [ ] Race condition between payment and expiration

This implementation provides a clean, fast solution for managing pending bookings while maintaining good user experience and data consistency.
