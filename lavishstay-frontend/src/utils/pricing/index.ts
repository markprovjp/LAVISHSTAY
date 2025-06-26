/**
 * ===== BẢNG CHUẨN AVAILABILITY THEO SƠ ĐỒ TẦNG (CẬP NHẬT THEO MODELS.TS) =====
 * 
 * 1. Deluxe Room: 90 phòng (tầng 2-5, 8-9: 15 phòng/tầng) 
 * 2. Premium Corner: 96 phòng (tầng 10-17: 12 phòng/tầng)   
 * 3. The Level Premium: 36 phòng (tầng 18-20: 12 phòng/tầng) 
 * 4. The Level Premium Corner: 32 phòng (tầng 21-24: 8 phòng/tầng) 
 * 5. The Level Suite: 20 phòng (tầng 25-27: 7+7+6 phòng) 
 * 6. Suite: 20 phòng (tầng 28-31: 5 phòng/tầng) 
 * 7. Presidential Suite: 1 phòng (tầng 32) 
 * 
 * 🎯 TỔNG: 295 phòng 
 * 
 * CÁC TẦNG TIỆN ÍCH (KHÔNG CÓ PHÒNG)
•	Tầng 1: Lobby + Lobby Bar
•	Tầng 6: Nhà hàng Orchid (buffet Á-Âu, ~260 khách)
•	Tầng 7: Bể bơi trong nhà + Spa YHI + Gym
•	Tầng 33: Panoramic Lounge (~36 khách VIP)
•	Tầng 34: Nhà hàng Lotus + Skyview Bar (~95 khách)

 * ===== LOGIC REMAINING =====
 * - Basic options: remaining cao (70-85% total)
 * - Standard options: remaining trung bình (60-75% total)  
 * - Premium options: remaining thấp hơn (40-60% total)
 * - Luxury options: remaining thấp nhất (20-40% total)
 * 
 * ===== ROOM ALLOCATION LOGIC =====
 * - Logic thông minh để gợi ý phòng phù hợp
 * - Gia đình 2 người + 1 trẻ: có thể chọn phòng 2 người
 * - Nhóm 3 người lớn: bắt buộc chọn phòng 3 người hoặc 2 phòng
 * - Mỗi phòng 2 người có thể chứa thêm 1 trẻ em
 */

export { ROOM_PRICING, PRICING_MULTIPLIERS, ROOM_TYPE_MAPPING } from './roomPricing';
export { createDeluxeOptions } from './deluxeOptions';
export { createPremiumOptions } from './premiumOptions';
export { createSuiteOptions } from './suiteOptions';
export {
    createTheLevelPremiumOptions,
    createTheLevelPremiumCornerOptions,
    createTheLevelSuiteOptions
} from './theLevelOptions';
export { createPresidentialOptions } from './presidentialOptions';
