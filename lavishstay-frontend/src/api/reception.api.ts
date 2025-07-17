// src/api/reception.api.ts
import { axiosClient } from './axios-client';

export const receptionAPI = {
    // Existing APIs...

    // Get available rooms for a booking
    getAvailableRoomsForBooking: (bookingId: number) => {
        return axiosClient.get(`/api/reception/bookings/${bookingId}/available-rooms`);
    },

    // Assign rooms to a booking
    assignRoomsToBooking: (bookingId: number, roomIds: number[]) => {
        return axiosClient.post(`/api/reception/bookings/${bookingId}/assign-rooms`, {
            room_ids: roomIds
        });
    },

    // Get expanded booking details
    getBookingDetails: (bookingId: number) => {
        return axiosClient.get(`/api/reception/bookings/${bookingId}/details`);
    },
};
