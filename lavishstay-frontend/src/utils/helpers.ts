// src/utils/helpers.ts

/**
 * Format a date to display in a user-friendly format
 */
export const formatDate = (date: string | Date): string => {
  const dateObj = typeof date === 'string' ? new Date(date) : date;
  return dateObj.toLocaleDateString('vi-VN', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
  });
};

/**
 * Format currency to VND format
 */
export const formatCurrency = (amount: number): string => {
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND',
  }).format(amount);
};

/**
 * Format price to USD format
 */
export const formatPrice = (price: number): string => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 0,
  }).format(price);
};

/**
 * Calculate number of nights between two dates
 */
export const calculateNights = (checkIn: string | Date, checkOut: string | Date): number => {
  const checkInDate = typeof checkIn === 'string' ? new Date(checkIn) : checkIn;
  const checkOutDate = typeof checkOut === 'string' ? new Date(checkOut) : checkOut;

  const diffTime = Math.abs(checkOutDate.getTime() - checkInDate.getTime());
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  return diffDays;
};

/**
 * Format rating to display with decimal places
 */
export const formatRating = (rating: number): string => {
  return rating.toFixed(1);
};

/**
 * Truncate text with ellipsis
 */
export const truncateText = (text: string, maxLength: number): string => {
  if (text.length <= maxLength) return text;
  return `${text.slice(0, maxLength)}...`;
};

/**
 * Tạo URL hình ảnh giữ chỗ cho các thuộc tính
 */
export const getPlaceholderImage = (seed = 'lavishstay'): string => {
  return `https://picsum.photos/seed/${seed}/600/400`;
};

/**
 * Check if a date is in the past
 */
export const isDateInPast = (date: string | Date): boolean => {
  const checkDate = typeof date === 'string' ? new Date(date) : date;
  const today = new Date();
  today.setHours(0, 0, 0, 0);

  return checkDate < today;
};

/**
 * Get background color class based on booking status
 */
export const getStatusColorClass = (status: string): string => {
  const statusMap: Record<string, string> = {
    confirmed: 'bg-green-100 text-green-800',
    pending: 'bg-yellow-100 text-yellow-800',
    cancelled: 'bg-red-100 text-red-800',
    completed: 'bg-blue-100 text-blue-800',
  };

  return statusMap[status.toLowerCase()] || 'bg-gray-100 text-gray-800';
};

/**
 * Calculate total price for room options with nights and quantity
 */
export const calculateTotalPrice = (pricePerNight: number, nights: number, roomQuantity: number = 1): number => {
  return pricePerNight * nights * roomQuantity;
};

/**
 * Calculate nights from date range (Dayjs objects)
 */
export const calculateNightsFromRange = (dateRange: [any, any] | null): number => {
  if (!dateRange || !dateRange[0] || !dateRange[1]) return 0;

  const [checkIn, checkOut] = dateRange;
  return checkOut.diff(checkIn, 'day');
};

/**
 * Format VND currency (consistent across app)
 */
export const formatVND = (amount: number): string => {
  return new Intl.NumberFormat('vi-VN', {
    style: 'currency',
    currency: 'VND',
  }).format(amount);
};

/**
 * Format price breakdown text
 */
export const formatPriceBreakdown = (
  roomQuantity: number,
  nights: number,
  pricePerNight: number
): string => {
  if (roomQuantity === 1 && nights === 1) {
    return `${formatVND(pricePerNight)}/đêm`;
  } else if (roomQuantity === 1) {
    return `${formatVND(pricePerNight)} × ${nights} đêm`;
  } else if (nights === 1) {
    return `${roomQuantity} phòng × ${formatVND(pricePerNight)}`;
  } else {
    return `${roomQuantity} phòng × ${nights} đêm × ${formatVND(pricePerNight)}`;
  }
};

/**
 * Calculate and format dynamic pricing summary
 */
export interface PricingSummary {
  subtotal: number;
  totalNights: number;
  totalRooms: number;
  pricePerNight: number;
  formattedSubtotal: string;
  formattedBreakdown: string;
}

export const calculatePricingSummary = (
  roomOptions: Array<{
    pricePerNight: number;
    quantity: number;
  }>,
  nights: number
): PricingSummary => {
  const totalRooms = roomOptions.reduce((sum, option) => sum + option.quantity, 0);
  const subtotal = roomOptions.reduce((sum, option) =>
    sum + calculateTotalPrice(option.pricePerNight, nights, option.quantity), 0
  );

  // Average price per night across all selected rooms
  const avgPricePerNight = totalRooms > 0 ? subtotal / (totalRooms * nights) : 0;

  return {
    subtotal,
    totalNights: nights,
    totalRooms,
    pricePerNight: avgPricePerNight,
    formattedSubtotal: formatVND(subtotal),
    formattedBreakdown: formatPriceBreakdown(totalRooms, nights, avgPricePerNight)
  };
};
