# ServicePaymentModal

A comprehensive React component for handling service payment workflows in the reception UI. This component provides a 3-step modal interface for staff to generate VietQR codes and verify payments via the CPay integration.

## Features

- **3-Step Payment Flow**: Service selection → Payment confirmation → QR generation & verification
- **Auto-selection**: Automatically selects pending and partial payment services
- **VietQR Integration**: Generates QR codes for bank transfers with real-time countdown
- **CPay Verification**: Automatic polling and manual payment verification
- **Real-time Updates**: Live countdown timer and payment status updates
- **Accessibility**: Screen reader support and keyboard navigation
- **Error Handling**: Comprehensive error states with retry functionality

## API Dependencies

This component requires the following backend endpoints:

- `GET /api/reception/bookings/{bookingId}/services/payment-info`
- `POST /api/reception/bookings/{bookingId}/services/payment/qr`
- `POST /api/reception/bookings/services/payment/check`
- `GET /api/reception/bookings/{bookingId}/services/payment-history` (optional)

## Usage

### Basic Usage

```tsx
import { ServicePaymentModal } from "@/components/ServicePaymentModal";

function ReceptionBookingDetail() {
  const [paymentModalVisible, setPaymentModalVisible] = useState(false);

  const handlePaymentSuccess = (result) => {
    console.log("Payment successful:", result);
    // Refresh booking data or show success notification
  };

  return (
    <div>
      <Button onClick={() => setPaymentModalVisible(true)}>
        Thanh toán dịch vụ phát sinh
      </Button>

      <ServicePaymentModal
        bookingId={23}
        bookingCode="LAVISHSTAY_509999"
        visible={paymentModalVisible}
        onClose={() => setPaymentModalVisible(false)}
        onSuccess={handlePaymentSuccess}
      />
    </div>
  );
}
```

### Props

| Prop          | Type                                     | Required | Description                                   |
| ------------- | ---------------------------------------- | -------- | --------------------------------------------- |
| `bookingId`   | `number`                                 | Yes      | The booking ID to process payments for        |
| `bookingCode` | `string`                                 | Yes      | The booking code for display and verification |
| `visible`     | `boolean`                                | Yes      | Controls modal visibility                     |
| `onClose`     | `() => void`                             | Yes      | Called when modal is closed                   |
| `onSuccess`   | `(result: PaymentCheckResponse) => void` | No       | Called after successful payment verification  |

## Workflow

### Step 1: Service Selection

- Displays all services for the booking with their payment status
- Auto-selects services with 'pending' or 'partial' payment status
- Shows service details: quantity, unit price, total, paid amount, outstanding
- Allows custom amount input or auto-fill outstanding amount
- Validates minimum payment amount (1,000 ₫)

### Step 2: Payment Confirmation

- Shows selected services and total payment amount
- Displays payment summary before generating QR
- Allows going back to modify selection

### Step 3: QR Generation & Verification

- Generates VietQR code with bank details
- Shows 15-minute countdown timer
- Provides manual "Check Payment" button
- Auto-polls for payment every 5 seconds (max 12 attempts)
- Handles payment confirmation and service allocation
- Shows success state with transaction details

## Features in Detail

### Auto-polling

- Polls payment status every 5 seconds
- Maximum 12 attempts (1 minute total)
- Stops on successful payment or timeout
- Can be supplemented with manual checks

### QR Code Expiry

- 15-minute expiry countdown
- Visual progress indicator
- "Generate New QR" option when expired

### Error Handling

- Network error recovery with retry buttons
- Validation error display
- Service availability checks
- Graceful degradation for edge cases

### Accessibility

- Screen reader announcements for payment status
- Keyboard navigation support
- Alt text for QR images
- ARIA live regions for dynamic updates

## Development

### Running Tests

```bash
npm run test ServicePaymentModal
```

### Test Coverage

The component includes comprehensive tests covering:

- **Happy Path**: Complete payment flow from service selection to success
- **Edge Cases**: Partial payment allocation, timeout handling, custom amounts
- **Error Handling**: Network errors, validation errors, empty services
- **User Interactions**: Service selection, amount input, QR regeneration

### Dependencies

- React 18+
- TypeScript 4.5+
- Ant Design 5.x
- Axios for HTTP requests
- Vitest + React Testing Library for tests

## Performance Considerations

- Automatic cleanup of intervals on component unmount
- Debounced API calls for payment checking
- Efficient re-renders with React.memo and useCallback
- Lazy loading of QR images

## Customization

### Styling

The component uses Ant Design classes and can be customized via CSS modules or styled-components:

```css
.service-payment-modal .ant-modal-body {
  padding: 24px;
}

.service-payment-qr {
  max-width: 200px;
  margin: 0 auto;
}
```

### Localization

All text strings can be extracted for i18n:

```tsx
const texts = {
  title: "Thanh toán dịch vụ phát sinh",
  selectServices: "Chọn dịch vụ và số tiền thanh toán",
  // ... other strings
};
```

## Integration Notes

### CheckoutInfoModal Integration

To add this to the existing checkout flow:

```tsx
// In CheckoutInfoModal.tsx
import { ServicePaymentModal } from '@/components/ServicePaymentModal';

// Add state
const [servicePaymentVisible, setServicePaymentVisible] = useState(false);

// Add button in the action area
<Button
  type="primary"
  icon={<DollarOutlined />}
  onClick={() => setServicePaymentVisible(true)}
>
  Thanh toán dịch vụ phát sinh
</Button>

// Add modal component
<ServicePaymentModal
  bookingId={bookingDetail.booking_id}
  bookingCode={bookingDetail.booking_code}
  visible={servicePaymentVisible}
  onClose={() => setServicePaymentVisible(false)}
  onSuccess={() => {
    // Refresh checkout info
    fetchCheckoutInfo();
  }}
/>
```

## Troubleshooting

### Common Issues

1. **QR not generating**: Check network connectivity and backend API availability
2. **Payment not detected**: Verify CPay integration and Google Apps Script URL
3. **Services not loading**: Ensure booking has services and proper permissions
4. **Countdown not working**: Check system time and timezone settings

### Debug Mode

Enable debug logging by setting localStorage:

```javascript
localStorage.setItem("DEBUG_SERVICE_PAYMENT", "true");
```

## Changelog

### Version 1.0.0

- Initial implementation with 3-step payment flow
- VietQR integration with countdown timer
- CPay payment verification with auto-polling
- Comprehensive test suite
- TypeScript support with full type definitions
- Accessibility features and error handling

## API Response Examples

### Payment Info Response

```json
{
  "success": true,
  "data": {
    "booking_id": 23,
    "booking_code": "LAVISHSTAY_509999",
    "services": [
      {
        "booking_service_id": 15,
        "service_name": "Dịch vụ spa và massage",
        "quantity": 2,
        "total_price_vnd": 1000000,
        "paid_amount_vnd": "0.00",
        "outstanding_amount_vnd": 1000000,
        "payment_status": "pending"
      }
    ],
    "summary": {
      "total_service_amount": 1750000,
      "total_paid_amount": 600000,
      "total_outstanding": 1150000
    }
  }
}
```

### QR Generation Response

```json
{
  "success": true,
  "data": {
    "payment_id": 143,
    "qr_url": "https://img.vietqr.io/image/MBBank-0335920306-print.png",
    "amount": 100000,
    "formatted_amount": "100.000 ₫",
    "payment_content": "LVSS LAVISHSTAY_509999 143",
    "expires_at": "2025-08-21T14:04:11.385885Z"
  }
}
```
