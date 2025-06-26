/**
 * Room Allocation Logic for Smart Room Suggestions
 * Tính toán phân bổ phòng thông minh - LUÔN HIỂN THỊ TẤT CẢ PHÒNG
 * Khách có thể chọn nhiều phòng để đủ số người
 */

export interface GuestDetails {
    adults: number;
    children: number;
}

export interface RoomAllocationSuggestion {
    roomType: string;
    suggestedQuantity: number; // Số phòng gợi ý
    maxGuestsPerRoom: number;
    reasonCode: 'PERFECT_FIT' | 'CHILD_FRIENDLY' | 'UPGRADE_OPTION' | 'MULTIPLE_ROOMS_OPTION';
    reason: string;
    priority: number; // 1-5, càng thấp càng ưu tiên hiển thị lên đầu
    isRecommended: boolean; // Có nên highlight không
}

export interface AllocationResult {
    totalGuests: number;
    totalAdults: number;
    totalChildren: number;
    suggestions: RoomAllocationSuggestion[];
    minimumRoomsNeeded: number;
    notes: string[];
}

/**
 * Thông tin về capacity của từng loại phòng (theo models.ts)
 */
const ROOM_CAPACITIES = {
    deluxe: { maxGuests: 2, canAddChild: true, basePrice: 1300000 }, // 2 người lớn + 1 trẻ em
    premium: { maxGuests: 3, canAddChild: false, basePrice: 1400000 }, // 3 người lớn
    suite: { maxGuests: 4, canAddChild: true, basePrice: 2650000 }, // 4 người + trẻ em
    theLevelPremium: { maxGuests: 2, canAddChild: true, basePrice: 3200000 }, // 2 người lớn + 1 trẻ em
    theLevelPremiumCorner: { maxGuests: 3, canAddChild: false, basePrice: 3400000 }, // 3 người lớn
    theLevelSuite: { maxGuests: 4, canAddChild: true, basePrice: 4600000 }, // 4 người + trẻ em
    presidential: { maxGuests: 4, canAddChild: true, basePrice: 47000000 } // 4 người + trẻ em
};

/**
 * Tính toán gợi ý phòng thông minh - LUÔN HIỂN THỊ TẤT CẢ PHÒNG
 * Logic: Khách có thể chọn nhiều phòng để đủ số người
 */
export function calculateRoomAllocation(guestDetails: GuestDetails): AllocationResult {
    const { adults, children } = guestDetails;
    const totalGuests = adults + children;
    const suggestions: RoomAllocationSuggestion[] = [];
    const notes: string[] = [];

    // Tính số phòng tối thiểu cần (dựa trên số người lớn, 2 người lớn/phòng)
    const minimumRoomsNeeded = Math.ceil(adults / 2);

    // Tạo suggestions cho TẤT CẢ loại phòng với logic gợi ý thông minh
    Object.entries(ROOM_CAPACITIES).forEach(([roomType, capacity]) => {
        let priority = 5; // Default priority
        let reasonCode: 'PERFECT_FIT' | 'CHILD_FRIENDLY' | 'UPGRADE_OPTION' | 'MULTIPLE_ROOMS_OPTION' = 'UPGRADE_OPTION';
        let reason = '';
        let suggestedQuantity = 1;
        let isRecommended = false;

        // Logic gợi ý dựa trên số khách và loại phòng
        if (roomType === 'deluxe') {
            if (adults <= 2 && children <= 1) {
                priority = 1;
                reasonCode = children > 0 ? 'CHILD_FRIENDLY' : 'PERFECT_FIT';
                reason = children > 0 
                    ? 'Phù hợp cho gia đình có trẻ em, giá tốt nhất' 
                    : 'Phù hợp cho 2 người, giá tốt nhất';
                isRecommended = true;
            } else {
                suggestedQuantity = minimumRoomsNeeded;
                reasonCode = 'MULTIPLE_ROOMS_OPTION';
                reason = `Gợi ý ${suggestedQuantity} phòng Deluxe cho ${adults} người`;
                priority = 3;
            }
        }
        
        else if (roomType === 'premium') {
            if (adults === 3 && children === 0) {
                priority = 1;
                reasonCode = 'PERFECT_FIT';
                reason = 'Phòng Premium chứa vừa 3 người lớn';
                isRecommended = true;
            } else if (adults <= 3) {
                priority = 2;
                reasonCode = 'UPGRADE_OPTION';
                reason = 'Phòng rộng rãi hơn cho nhóm nhỏ';
            } else {
                suggestedQuantity = Math.ceil(adults / 3);
                reasonCode = 'MULTIPLE_ROOMS_OPTION';
                reason = `Gợi ý ${suggestedQuantity} phòng Premium cho ${adults} người`;
                priority = 4;
            }
        }

        else if (roomType === 'theLevelPremium') {
            if (adults <= 2 && children <= 1) {
                priority = 2;
                reasonCode = 'UPGRADE_OPTION';
                reason = 'Phòng The Level cao cấp với tiện ích VIP';
                isRecommended = totalGuests <= 3;
            } else {
                suggestedQuantity = minimumRoomsNeeded;
                reasonCode = 'MULTIPLE_ROOMS_OPTION';
                reason = `Gợi ý ${suggestedQuantity} phòng The Level Premium`;
                priority = 4;
            }
        }

        else if (roomType === 'theLevelPremiumCorner') {
            if (adults === 3 && children === 0) {
                priority = 2;
                reasonCode = 'UPGRADE_OPTION';
                reason = 'The Level Premium Corner view đẹp cho 3 người';
                isRecommended = true;
            } else if (adults <= 3) {
                priority = 3;
                reasonCode = 'UPGRADE_OPTION';
                reason = 'Phòng góc với view đẹp';
            } else {
                suggestedQuantity = Math.ceil(adults / 3);
                reasonCode = 'MULTIPLE_ROOMS_OPTION';
                reason = `Gợi ý ${suggestedQuantity} phòng The Level Corner`;
                priority = 5;
            }
        }

        else if (roomType === 'suite' || roomType === 'theLevelSuite') {
            if (adults <= 4) {
                priority = roomType === 'suite' ? 2 : 3;
                reasonCode = totalGuests <= 4 ? 'PERFECT_FIT' : 'UPGRADE_OPTION';
                reason = totalGuests <= 4 
                    ? `${roomType === 'suite' ? 'Suite' : 'The Level Suite'} rộng rãi cho gia đình lớn`
                    : `${roomType === 'suite' ? 'Suite' : 'The Level Suite'} sang trọng`;
                isRecommended = totalGuests >= 4;
            } else {
                suggestedQuantity = Math.ceil(adults / 4);
                reasonCode = 'MULTIPLE_ROOMS_OPTION';
                reason = `Gợi ý ${suggestedQuantity} ${roomType === 'suite' ? 'Suite' : 'The Level Suite'}`;
                priority = 4;
            }
        }

        else if (roomType === 'presidential') {
            if (adults <= 4) {
                priority = 4;
                reasonCode = 'UPGRADE_OPTION';
                reason = 'Presidential Suite - Đẳng cấp tối thượng';
                isRecommended = totalGuests >= 4;
            } else {
                suggestedQuantity = Math.ceil(adults / 4);
                reasonCode = 'MULTIPLE_ROOMS_OPTION';
                reason = `Gợi ý ${suggestedQuantity} Presidential Suite`;
                priority = 5;
            }
        }

        suggestions.push({
            roomType,
            suggestedQuantity,
            maxGuestsPerRoom: capacity.maxGuests,
            reasonCode,
            reason,
            priority,
            isRecommended
        });
    });

    // Add helpful notes
    if (children > 0) {
        notes.push(`${children} trẻ em có thể ở cùng phòng với bố mẹ (không tính phí)`);
    }
    
    if (adults > 4) {
        notes.push(`Với ${adults} người lớn, bạn cần chọn nhiều phòng. Hệ thống sẽ kiểm tra tổng capacity khi đặt phòng.`);
    }

    notes.push('💡 Bạn có thể chọn nhiều phòng khác nhau để đủ số người');
    notes.push('🔒 Hệ thống sẽ kiểm tra đủ chỗ cho tất cả khách trước khi thanh toán');

    return {
        totalGuests,
        totalAdults: adults,
        totalChildren: children,
        suggestions: suggestions.sort((a, b) => a.priority - b.priority),
        minimumRoomsNeeded,
        notes
    };
}

/**
 * Kiểm tra xem một loại phòng có phù hợp với số lượng khách không
 * LUÔN RETURN TRUE vì hiển thị tất cả phòng
 */
export function isRoomSuitableForGuests(
    roomType: keyof typeof ROOM_CAPACITIES, 
    guestDetails: GuestDetails
): boolean {
    // Logic để tham khảo, nhưng luôn return true để hiển thị tất cả phòng
    const capacity = ROOM_CAPACITIES[roomType];
    const { adults, children } = guestDetails;
    
    const totalGuests = adults + children;
    const canAccommodate = capacity.canAddChild ? 
        (adults <= capacity.maxGuests && totalGuests <= capacity.maxGuests + 1) :
        totalGuests <= capacity.maxGuests;
    
    console.log(`Room ${roomType}: capacity=${capacity.maxGuests}, guests=${totalGuests}, suitable=${canAccommodate}`);
    
    return true; // LUÔN hiển thị tất cả phòng - khách có thể chọn nhiều phòng
}

/**
 * Tính toán số phòng tối thiểu cần thiết
 */
export function calculateMinimumRoomsNeeded(guestDetails: GuestDetails): number {
    const { adults } = guestDetails;
    // Giả định tối đa 2 người lớn/phòng cho tính toán an toàn
    return Math.ceil(adults / 2);
}

/**
 * Lấy gợi ý tốt nhất cho một loại phòng cụ thể
 */
export function getBestSuggestionForRoomType(
    roomType: string, 
    guestDetails: GuestDetails
): RoomAllocationSuggestion | null {
    const allocation = calculateRoomAllocation(guestDetails);
    return allocation.suggestions.find(s => s.roomType === roomType) || null;
}

/**
 * Format thông tin về capacity của phòng
 */
export function formatRoomCapacityInfo(
    roomType: keyof typeof ROOM_CAPACITIES,
    guestDetails: GuestDetails
): string {
    const capacity = ROOM_CAPACITIES[roomType];
    const suggestion = getBestSuggestionForRoomType(roomType, guestDetails);
    
    if (suggestion && suggestion.suggestedQuantity > 1) {
        return `Gợi ý ${suggestion.suggestedQuantity} phòng × ${capacity.maxGuests} khách/phòng`;
    }
    
    return `Tối đa ${capacity.maxGuests} khách${capacity.canAddChild ? ' (+ trẻ em)' : ''}`;
}

/**
 * Validation: Kiểm tra xem tổng số phòng đã chọn có đủ chỗ cho tất cả khách không
 */
export function validateRoomSelection(
    selectedRooms: { [roomId: string]: { [optionId: string]: number } },
    roomsData: any[],
    guestDetails: GuestDetails
): { 
    isValid: boolean; 
    totalCapacity: number; 
    message: string;
    missingCapacity?: number;
} {
    const { adults, children } = guestDetails;
    const totalGuests = adults + children;
    let totalCapacity = 0;

    // Tính tổng capacity của các phòng đã chọn
    Object.entries(selectedRooms).forEach(([roomId, options]) => {
        const room = roomsData.find(r => r.id.toString() === roomId);
        if (room) {
            Object.entries(options).forEach(([optionId, quantity]) => {
                const option = room.options.find((opt: any) => opt.id === optionId);
                if (option && quantity > 0) {
                    totalCapacity += option.maxGuests * quantity;
                }
            });
        }
    });

    const isValid = totalCapacity >= totalGuests;
    const missingCapacity = isValid ? 0 : totalGuests - totalCapacity;

    return {
        isValid,
        totalCapacity,
        message: isValid 
            ? `✅ Đủ chỗ cho ${totalGuests} khách (${totalCapacity} chỗ)` 
            : `❌ Thiếu ${missingCapacity} chỗ (có ${totalCapacity}/${totalGuests} chỗ)`,
        missingCapacity: isValid ? undefined : missingCapacity
    };
}
