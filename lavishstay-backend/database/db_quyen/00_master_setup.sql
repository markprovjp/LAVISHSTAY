-- ===== MASTER DATABASE SETUP =====
-- File chính để setup toàn bộ database khách sạn đã được tối ưu hóa


-- ===== EXECUTE ALL SETUP FILES =====

-- ===== MASTER SQL EXECUTION SCRIPT =====
-- Script thực thi đầy đủ database chuẩn hóa khách sạn 34 tầng, 295 phòng

SET FOREIGN_KEY_CHECKS = 0;
SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Drop database nếu tồn tại và tạo mới
DROP DATABASE IF EXISTS lavishstay_hotel;
CREATE DATABASE lavishstay_hotel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lavishstay_hotel;

-- Set charset cho session hiện tại
SET character_set_client = utf8mb4;
SET character_set_connection = utf8mb4;
SET character_set_results = utf8mb4;

-- ==== EXECUTE SCHEMA FILES ====

-- 1. Core Schema (Users, Rooms, Floors, Room Types)
SOURCE 01_schema_core.sql;

-- 2. Amenities Schema (Amenities, Room Type Amenities)
SOURCE 02_schema_amenities.sql;

-- 3. Booking & Services Schema (Bookings, Services, Payments)
SOURCE 03_schema_booking_services.sql;

-- 4. Operations Schema (Check-in/out, Maintenance, Reports)
SOURCE 04_schema_operations.sql;

-- 5. Unified Policies Schema (ALL policies: deposit, cancellation, checkout, etc.)
SOURCE 05_schema_policies.sql;

-- 6. Guest Reviews & Feedback System 
SOURCE 06_schema_guest_reviews.sql;

-- 7. Voucher & Loyalty Schema (voucher, gói ưu đãi, loyalty program)
SOURCE 07_schema_voucher.sql;

-- 8. Dynamic Pricing Schema (Seasons, Events, Contracts, Long-stay)
SOURCE 08_schema_pricing.sql;

-- 9. Media Schema (Images, Videos, Documents management)
SOURCE 09_schema_media.sql;

-- ==== EXECUTE DATA FILES ====

-- 10. Basic Data (Users, Floors, Room Types, Meal Plans, Services)
SOURCE 10_data_basic.sql;

-- 11. Complete Rooms Data (All 295 rooms with fixed bed types)
SOURCE 11_data_rooms_complete.sql;

-- 12. Amenities Sample Data
SOURCE 12_data_amenities.sql;

-- 13. Unified Policies Data (ALL policies data)
SOURCE 13_data_policies.sql;

-- 14. Guest Reviews Sample Data
SOURCE 14_data_guest_reviews.sql;

-- 15. Voucher & Loyalty Sample Data
SOURCE 15_data_voucher.sql;

-- 16. Dynamic Pricing Data (Season types, pricing seasons, surcharges)
SOURCE 16_data_pricing.sql;

-- 17. Sample Booking Data (để đảm bảo tính đồng bộ)
SOURCE 17_data_booking_samples.sql;

-- 18. Media Sample Data (Images, Videos, Documents)
SOURCE 18_data_media.sql;

-- ==== EXECUTE PROCEDURES & VIEWS ====

-- 20. Policy Procedures & Functions (temporarily disabled for testing)
-- SOURCE 20_procedures_policies.sql;

-- 21. Pricing Procedures (Functions và stored procedures)
SOURCE 21_procedures_pricing.sql;

-- 22. Views and Reports (All views)
SOURCE 22_views_reports.sql;

-- 23. Triggers & Validations
SOURCE 23_triggers_validations.sql;

-- 24. Smart Room Search Procedures
SOURCE search_rooms_query.sql;
