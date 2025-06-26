/**
 * ===== BẢNG CHUẨN AVAILABILITY THEO SƠ ĐỒ TẦNG =====
 * 
 * 1. Deluxe Room: 90 phòng (tầng 2-5, 8-9: 15 phòng/tầng) ✅ ĐÃ CẬP NHẬT
 * 2. Premium Corner: 96 phòng (tầng 10-17: 12 phòng/tầng) ✅ ĐÃ CẬP NHẬT  
 * 3. The Level Premium: 36 phòng (tầng 18,19,22: 12 phòng/tầng) ✅ ĐÃ CẬP NHẬT
 * 4. The Level Premium Corner: 32 phòng (tầng 20-23: 8 phòng/tầng) ✅ ĐÃ CẬP NHẬT
 * 5. The Level Suite: 20 phòng (tầng 25-27: 7+7+6 phòng) ✅ ĐÃ CẬP NHẬT
 * 6. Suite: 20 phòng (tầng 28-31: 5 phòng/tầng) ✅ ĐÃ CẬP NHẬT
 * 7. Presidential Suite: 1 phòng (tầng 32) ✅ ĐÃ CẬP NHẬT
 * 
 * 🎯 TỔNG: 295 phòng
 * 
 * ===== LOGIC REMAINING =====
 * - Basic options: remaining cao (70-85% total)
 * - Standard options: remaining trung bình (60-75% total)  
 * - Premium options: remaining thấp hơn (40-60% total)
 * - Luxury options: remaining thấp nhất (20-40% total)
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
