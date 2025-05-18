// src/types/models.ts
export interface PropertyLocation {
  city: string;
  country: string;
  address?: string;
  coordinates?: {
    latitude: number;
    longitude: number;
  };
}

export interface PropertyAmenity {
  id: number;
  name: string;
  icon?: string;
  category?: 'essential' | 'luxury' | 'safety' | 'accessibility';
}

export interface PropertyReview {
  id: number;
  userId: number;
  userName: string;
  userAvatar?: string;
  rating: number;
  comment: string;
  date: string;
  location?: string;
  images?: string[];
  response?: {
    text: string;
    date: string;
    name: string;
  };
}

export type PropertyCategory = 
  | 'hotel' 
  | 'resort' 
  | 'villa' 
  | 'apartment' 
  | 'cottage' 
  | 'cabin'
  | 'guest-house';

export type PropertyStatus = 
  | 'available' 
  | 'booked' 
  | 'maintenance' 
  | 'pending-approval';

export interface Property {
  id: number;
  title: string;
  slug?: string;
  description?: string;
  shortDescription?: string;
  location: string;
  fullLocation?: PropertyLocation;
  price: number;
  discountedPrice?: number;
  rating: number;
  reviewCount?: number;
  image: string;
  images?: string[];
  category: string;
  status?: PropertyStatus;
  features?: string[];
  amenities?: PropertyAmenity[];
  maxGuests?: number;
  bedrooms?: number;
  beds?: number;
  baths?: number;
  host?: {
    id: number;
    name: string;
    avatar?: string;
    isSuperhost?: boolean;
    rating?: number;
    responseRate?: number;
  };
  reviews?: PropertyReview[];
  checkInTime?: string;
  checkOutTime?: string;
  cancellationPolicy?: string;
  highlights?: string[];
  hasPromotion?: boolean;
  isFeatured?: boolean;
  createdAt?: string;
  updatedAt?: string;
}

export interface BookingDetails {
  id: number;
  propertyId: number;
  userId: number;
  startDate: string;
  endDate: string;
  guests: number;
  totalPrice: number;
  status: 'pending' | 'confirmed' | 'cancelled' | 'completed';
  paymentStatus: 'pending' | 'paid' | 'refunded';
  createdAt: string;
}

export interface SearchFilters {
  destination?: string;
  checkIn?: string;
  checkOut?: string;
  guests?: number;
  priceMin?: number;
  priceMax?: number;
  amenities?: number[];
  propertyType?: PropertyCategory[];
  rating?: number;
}
