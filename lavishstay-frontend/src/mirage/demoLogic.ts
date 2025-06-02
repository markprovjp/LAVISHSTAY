// Demo logic nghiệp vụ Room Booking
// Chạy file này để test logic mới

import { generateRoomOptions, getUrgencyMessage, calculateDaysDifference } from './roomOptionGenerator';

// Demo data
const roomTypes = ["deluxe", "premium", "suite"] as const;
const basePrice = 1500000; // VND

console.log("=== DEMO LOGIC NGHIỆP VỤ ROOM BOOKING ===\n");

// Test case 1: Đặt phòng hôm nay (same day)
console.log("🔥 TEST CASE 1: Đặt phòng hôm nay");
const today = new Date().toISOString().split('T')[0];
console.log(`Ngày check-in: ${today} (hôm nay)`);
console.log(`Số ngày chênh lệch: ${calculateDaysDifference(today)}`);
console.log("Urgency message:", getUrgencyMessage(today));

const todayOptions = generateRoomOptions("deluxe", today, basePrice);
console.log(`Số lượng options: ${todayOptions.length}`);
todayOptions.forEach((option, index) => {
    console.log(`  ${index + 1}. ${option.name} - ${option.pricePerNight.vnd.toLocaleString()} VND`);
    console.log(`     Hủy: ${option.cancellationPolicy.description}`);
    console.log(`     Thanh toán: ${option.paymentPolicy.description}`);
});
console.log("\n" + "=".repeat(50) + "\n");

// Test case 2: Đặt phòng ngày mai  
console.log("⚡ TEST CASE 2: Đặt phòng ngày mai");
const tomorrow = new Date();
tomorrow.setDate(tomorrow.getDate() + 1);
const tomorrowStr = tomorrow.toISOString().split('T')[0];
console.log(`Ngày check-in: ${tomorrowStr} (ngày mai)`);
console.log(`Số ngày chênh lệch: ${calculateDaysDifference(tomorrowStr)}`);
console.log("Urgency message:", getUrgencyMessage(tomorrowStr));

const tomorrowOptions = generateRoomOptions("deluxe", tomorrowStr, basePrice);
console.log(`Số lượng options: ${tomorrowOptions.length}`);
tomorrowOptions.forEach((option, index) => {
    console.log(`  ${index + 1}. ${option.name} - ${option.pricePerNight.vnd.toLocaleString()} VND`);
    console.log(`     Hủy: ${option.cancellationPolicy.description}`);
    console.log(`     Thanh toán: ${option.paymentPolicy.description}`);
});
console.log("\n" + "=".repeat(50) + "\n");

// Test case 3: Đặt phòng 3 ngày sau (advance booking)
console.log("✨ TEST CASE 3: Đặt phòng 3 ngày sau");
const future = new Date();
future.setDate(future.getDate() + 3);
const futureStr = future.toISOString().split('T')[0];
console.log(`Ngày check-in: ${futureStr} (3 ngày sau)`);
console.log(`Số ngày chênh lệch: ${calculateDaysDifference(futureStr)}`);
console.log("Urgency message:", getUrgencyMessage(futureStr) || "Không có thông báo khẩn cấp");

const futureOptions = generateRoomOptions("deluxe", futureStr, basePrice);
console.log(`Số lượng options: ${futureOptions.length}`);
futureOptions.forEach((option, index) => {
    console.log(`  ${index + 1}. ${option.name} - ${option.pricePerNight.vnd.toLocaleString()} VND`);
    console.log(`     Hủy: ${option.cancellationPolicy.description}`);
    console.log(`     Thanh toán: ${option.paymentPolicy.description}`);
    console.log(`     Bữa sáng: ${option.mealOptions.breakfast?.included ? 'Bao gồm' : 'Không bao gồm'}`);
});
console.log("\n" + "=".repeat(50) + "\n");

// Test case 4: Đặt phòng 1 tuần sau
console.log("🌟 TEST CASE 4: Đặt phòng 1 tuần sau");
const nextWeek = new Date();
nextWeek.setDate(nextWeek.getDate() + 7);
const nextWeekStr = nextWeek.toISOString().split('T')[0];
console.log(`Ngày check-in: ${nextWeekStr} (1 tuần sau)`);
console.log(`Số ngày chênh lệch: ${calculateDaysDifference(nextWeekStr)}`);
console.log("Urgency message:", getUrgencyMessage(nextWeekStr) || "Không có thông báo khẩn cấp");

const weekOptions = generateRoomOptions("premium", nextWeekStr, basePrice * 1.2);
console.log(`Số lượng options: ${weekOptions.length}`);
weekOptions.forEach((option, index) => {
    console.log(`  ${index + 1}. ${option.name} - ${option.pricePerNight.vnd.toLocaleString()} VND`);
    console.log(`     Hủy: ${option.cancellationPolicy.description}`);
    console.log(`     Thanh toán: ${option.paymentPolicy.description}`);
    console.log(`     Bữa sáng: ${option.mealOptions.breakfast?.included ? 'Bao gồm' : 'Không bao gồm'}`);
    if (option.mostPopular) console.log("     🏆 PHỔ BIẾN NHẤT");
});

console.log("\n" + "=".repeat(60));
console.log("📊 TỔNG KẾT LOGIC NGHIỆP VỤ:");
console.log("- Đặt phòng cùng ngày/ngày mai: CHỈ CÓ 1 OPTION không hoàn tiền");
console.log("- Đặt phòng trước >= 2 ngày: CÓ 3 OPTIONS linh hoạt");
console.log("  + Option 1: Hủy miễn phí (giá cao +15%)");
console.log("  + Option 2: Không hoàn tiền (giá rẻ -15%)");
console.log("  + Option 3: Bao gồm bữa sáng + hủy miễn phí (giá cao +30%)");
console.log("- Tất cả đều: THANH TOÁN TẠI KHÁCH SẠN");
console.log("=".repeat(60));
