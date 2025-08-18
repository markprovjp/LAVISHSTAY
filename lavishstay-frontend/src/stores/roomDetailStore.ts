// src/stores/roomDetailStore.ts
import { create } from 'zustand';
import { persist } from 'zustand/middleware';
import { RoomActionState, RoomBookingInfo } from '../types/roomDetail';

interface RoomDetailState {
    // Actions state (like, bookmark)
    roomActions: Record<string, RoomActionState>;

    // Booking info
    bookingInfo: RoomBookingInfo;

    // UI state
    selectedImageIndex: number;
    isGalleryModalOpen: boolean;
    isPolicyModalOpen: boolean;
    activeTab: string;

    // Actions
    toggleLike: (roomId: string) => void;
    toggleBookmark: (roomId: string) => void;
    updateBookingInfo: (info: Partial<RoomBookingInfo>) => void;
    setSelectedImageIndex: (index: number) => void;
    setGalleryModalOpen: (open: boolean) => void;
    setPolicyModalOpen: (open: boolean) => void;
    setActiveTab: (tab: string) => void;

    // Initialize room action state
    initializeRoomAction: (roomId: string) => void;
}

export const useRoomDetailStore = create<RoomDetailState>()(
    persist(
        (set, get) => ({
            roomActions: {},
            bookingInfo: {
                guests: 2,
                availableRooms: 0,
                isAvailable: false,
            },
            selectedImageIndex: 0,
            isGalleryModalOpen: false,
            isPolicyModalOpen: false,
            activeTab: 'overview',

            toggleLike: (roomId: string) => {
                const current = get().roomActions[roomId];
                if (current) {
                    set((state) => ({
                        roomActions: {
                            ...state.roomActions,
                            [roomId]: {
                                ...current,
                                isLiked: !current.isLiked,
                                likeCount: current.isLiked
                                    ? current.likeCount - 1
                                    : current.likeCount + 1,
                            },
                        },
                    }));
                }
            },

            toggleBookmark: (roomId: string) => {
                const current = get().roomActions[roomId];
                if (current) {
                    set((state) => ({
                        roomActions: {
                            ...state.roomActions,
                            [roomId]: {
                                ...current,
                                isBookmarked: !current.isBookmarked,
                                bookmarkCount: current.isBookmarked
                                    ? current.bookmarkCount - 1
                                    : current.bookmarkCount + 1,
                            },
                        },
                    }));
                }
            },

            updateBookingInfo: (info: Partial<RoomBookingInfo>) => {
                set((state) => ({
                    bookingInfo: { ...state.bookingInfo, ...info },
                }));
            },

            setSelectedImageIndex: (index: number) => {
                set({ selectedImageIndex: index });
            },

            setGalleryModalOpen: (open: boolean) => {
                set({ isGalleryModalOpen: open });
            },

            setPolicyModalOpen: (open: boolean) => {
                set({ isPolicyModalOpen: open });
            },

            setActiveTab: (tab: string) => {
                set({ activeTab: tab });
            },

            initializeRoomAction: (roomId: string) => {
                const current = get().roomActions[roomId];
                if (!current) {
                    set((state) => ({
                        roomActions: {
                            ...state.roomActions,
                            [roomId]: {
                                roomId,
                                isLiked: false,
                                isBookmarked: false,
                                likeCount: 0,
                                bookmarkCount: 0,
                            },
                        },
                    }));
                }
            },
        }),
        {
            name: 'room-detail-storage',
            partialize: (state) => ({
                roomActions: state.roomActions,
            }),
        }
    )
);
