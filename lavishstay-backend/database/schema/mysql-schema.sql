/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `amenities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `amenities` (
  `amenity_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icon_lib` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `category` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`amenity_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `audit_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'Người thực hiện hành động',
  `session_id` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Session ID để track theo phiên',
  `action` enum('create','update','delete','restore','login','logout','bulk_update','bulk_delete','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Loại hành động',
  `model` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên model/bảng tác động',
  `model_id` bigint unsigned NOT NULL COMMENT 'ID bản ghi tác động',
  `old_values` json DEFAULT NULL COMMENT 'Dữ liệu trước khi thay đổi',
  `new_values` json DEFAULT NULL COMMENT 'Dữ liệu sau khi thay đổi',
  `changes_summary` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Tóm tắt thay đổi (human readable)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả hành động hoặc lý do',
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'IP address',
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'User agent string',
  `url` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL được truy cập',
  `method` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'HTTP method',
  `metadata` json DEFAULT NULL COMMENT 'Thông tin bổ sung (tags, categories, etc.)',
  `is_sensitive` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Có chứa dữ liệu nhạy cảm không',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`audit_id`),
  KEY `idx_user_time` (`user_id`,`created_at`),
  KEY `idx_model_record` (`model`,`model_id`),
  KEY `idx_action_time` (`action`,`created_at`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_session` (`session_id`),
  KEY `idx_ip` (`ip_address`),
  CONSTRAINT `audit_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=95 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lưu trữ lịch sử thay đổi dữ liệu hệ thống';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `bed_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bed_types` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính',
  `type_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên loại giường',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả loại giường',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Trạng thái hoạt động',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Danh sách các loại giường';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `booking`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking` (
  `booking_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã đặt phòng',
  `booking_code` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'Khóa ngoại, mã người dùng (nếu có)',
  `option_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Khóa ngoại, mã tùy chọn phòng',
  `check_in_date` date NOT NULL COMMENT 'Ngày nhận phòng',
  `check_out_date` date NOT NULL COMMENT 'Ngày trả phòng',
  `total_price_vnd` decimal(15,2) NOT NULL COMMENT 'Tổng giá (VND)',
  `guest_count` int DEFAULT NULL COMMENT 'Số khách',
  `status` enum('Pending','Confirmed','Operational','Completed','Cancelled','Cancelled With Penalty','Unsuccessful','Cleaning') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Trạng thái đặt phòng, bao gồm Cleaning để biểu thị phòng đang dọn dẹp',
  `booking_source` enum('website','phone','walk_in','agent','online_travel_agency') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Nguồn đặt',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `quantity` int DEFAULT NULL,
  `payment_policy` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `room_type_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật',
  `guest_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Tên khách',
  `guest_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Email khách',
  `guest_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Số điện thoại khách',
  `room_id` int DEFAULT NULL,
  `children` int DEFAULT NULL,
  `children_age` json DEFAULT NULL,
  `is_document_verified` tinyint DEFAULT '0' COMMENT 'Xác nhận giấy tờ của khách (0: chưa xác nhận, 1: đã xác nhận)',
  PRIMARY KEY (`booking_id`),
  KEY `option_id` (`option_id`),
  KEY `idx_check_in_out` (`check_in_date`,`check_out_date`),
  KEY `fk_booking_room` (`room_id`),
  KEY `room_type_id` (`room_type_id`),
  CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`option_id`) REFERENCES `room_option` (`option_id`) ON DELETE RESTRICT,
  CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_booking_room` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=175 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin đặt phòng';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `booking_extensions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_extensions` (
  `extension_id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int NOT NULL,
  `new_check_out_date` date NOT NULL,
  `additional_fee_vnd` decimal(15,2) DEFAULT '0.00',
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`extension_id`),
  KEY `booking_id` (`booking_id`),
  CONSTRAINT `booking_extensions_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `booking_reschedules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_reschedules` (
  `reschedule_id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int NOT NULL,
  `new_check_in_date` date DEFAULT NULL,
  `new_check_out_date` date DEFAULT NULL,
  `new_room_id` int DEFAULT NULL,
  `new_option_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `reschedule_policy_id` int DEFAULT NULL,
  `price_difference_vnd` decimal(15,2) DEFAULT NULL,
  `payment_id` int DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Cancelled') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Pending',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `suggested_rooms` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `processed_by` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`reschedule_id`),
  KEY `booking_id` (`booking_id`),
  KEY `new_room_id` (`new_room_id`),
  KEY `new_option_id` (`new_option_id`),
  KEY `reschedule_policy_id` (`reschedule_policy_id`),
  KEY `payment_id` (`payment_id`),
  KEY `processed_by` (`processed_by`),
  CONSTRAINT `booking_reschedules_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `booking_reschedules_ibfk_2` FOREIGN KEY (`new_room_id`) REFERENCES `room` (`room_id`) ON DELETE SET NULL,
  CONSTRAINT `booking_reschedules_ibfk_3` FOREIGN KEY (`new_option_id`) REFERENCES `room_option` (`option_id`) ON DELETE SET NULL,
  CONSTRAINT `booking_reschedules_ibfk_4` FOREIGN KEY (`reschedule_policy_id`) REFERENCES `reschedule_policies` (`policy_id`) ON DELETE SET NULL,
  CONSTRAINT `booking_reschedules_ibfk_5` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`payment_id`) ON DELETE SET NULL,
  CONSTRAINT `booking_reschedules_ibfk_6` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `booking_room_children`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_room_children` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_room_id` int unsigned NOT NULL,
  `age` int NOT NULL,
  `child_index` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `booking_rooms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_rooms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int NOT NULL,
  `booking_code` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `room_id` int DEFAULT NULL,
  `option_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `option_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `option_price` decimal(15,2) DEFAULT NULL,
  `representative_id` int DEFAULT NULL,
  `adults` int DEFAULT NULL,
  `children` int DEFAULT NULL,
  `children_age` json DEFAULT NULL,
  `price_per_night` bigint NOT NULL,
  `nights` int NOT NULL,
  `total_price` bigint NOT NULL,
  `check_in_date` date DEFAULT NULL,
  `check_out_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `room_id` (`room_id`),
  KEY `representative_id` (`representative_id`),
  KEY `booking_rooms_option_id_foreign` (`option_id`),
  CONSTRAINT `booking_rooms_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`),
  CONSTRAINT `booking_rooms_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`),
  CONSTRAINT `booking_rooms_ibfk_3` FOREIGN KEY (`representative_id`) REFERENCES `representatives` (`id`),
  CONSTRAINT `booking_rooms_option_id_foreign` FOREIGN KEY (`option_id`) REFERENCES `room_option` (`option_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=151 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `booking_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `booking_services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int NOT NULL,
  `service_id` int NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price_vnd` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `booking_services_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `booking_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin dịch vụ phát sinh cho mỗi booking';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cancellation_policies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cancellation_policies` (
  `policy_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `free_cancellation_days` int DEFAULT NULL COMMENT 'Số ngày trước check-in được hủy miễn phí',
  `penalty_days` int DEFAULT NULL COMMENT 'số ngày hủy sau khi đặt phòng sẽ bị phạt.',
  `penalty_percentage` decimal(5,2) DEFAULT NULL COMMENT 'Phạt hủy (%)',
  `penalty_fixed_amount_vnd` decimal(15,2) DEFAULT NULL COMMENT 'Phạt hủy cố định (VND)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `priority` int NOT NULL DEFAULT '0',
  `conditions` json DEFAULT NULL,
  `applies_to_weekend` tinyint(1) NOT NULL DEFAULT '0',
  `applies_to_holiday` tinyint(1) NOT NULL DEFAULT '0',
  `min_booking_amount` decimal(15,2) DEFAULT NULL,
  `max_booking_amount` decimal(15,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`policy_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `cancellation_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cancellation_requests` (
  `request_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã yêu cầu hủy',
  `booking_id` int NOT NULL COMMENT 'Khóa ngoại, mã đặt phòng',
  `cancellation_policy_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã chính sách hủy áp dụng',
  `status` enum('Pending','Approved','Rejected','Processed','Refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending' COMMENT 'Trạng thái yêu cầu hủy',
  `request_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian yêu cầu hủy',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Lý do hủy do khách hàng cung cấp',
  `cancellation_fee_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Phí hủy (VND), nếu có',
  `refund_amount_vnd` decimal(15,2) DEFAULT NULL COMMENT 'Số tiền hoàn lại (VND), nếu có',
  `refund_status` enum('NotApplicable','Pending','Completed','Failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'NotApplicable' COMMENT 'Trạng thái hoàn tiền',
  `refund_transaction_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Mã giao dịch hoàn tiền (nếu có)',
  `processed_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian xử lý yêu cầu',
  `processed_by` bigint unsigned DEFAULT NULL COMMENT 'Khóa ngoại, ID nhân viên xử lý (nếu có)',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Ghi chú bổ sung từ hệ thống hoặc nhân viên',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`request_id`),
  KEY `booking_id` (`booking_id`),
  KEY `cancellation_policy_id` (`cancellation_policy_id`),
  KEY `processed_by` (`processed_by`),
  CONSTRAINT `cancellation_requests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `cancellation_requests_ibfk_2` FOREIGN KEY (`cancellation_policy_id`) REFERENCES `cancellation_policies` (`policy_id`) ON DELETE SET NULL,
  CONSTRAINT `cancellation_requests_ibfk_3` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu trữ các yêu cầu hủy phòng';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `check_in_policies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `check_in_policies` (
  `policy_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã chính sách nhận phòng',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên chính sách',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả chính sách',
  `standard_check_in_time` time NOT NULL COMMENT 'Thời gian nhận phòng tiêu chuẩn',
  `early_check_in_fee_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Phí nhận phòng sớm (VND)',
  `early_check_in_max_hours` int DEFAULT NULL COMMENT 'Số giờ tối đa cho phép nhận phòng sớm',
  `late_check_in_fee_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Phí nhận phòng muộn (VND)',
  `late_check_in_max_hours` int DEFAULT NULL COMMENT 'Số giờ tối đa cho phép nhận phòng muộn',
  `applies_to_holiday` tinyint(1) DEFAULT '0' COMMENT 'Áp dụng cho ngày lễ (1: Có, 0: Không)',
  `applies_to_weekend` tinyint(1) DEFAULT '0' COMMENT 'Áp dụng cho cuối tuần (1: Có, 0: Không)',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Trạng thái hoạt động',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật',
  `conditions` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Điều kiện áp dụng chính sách (JSON hoặc text)',
  `action` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Hành động khi chính sách được áp dụng',
  `priority` int DEFAULT '0' COMMENT 'Mức độ ưu tiên (cao hơn được áp dụng trước)',
  PRIMARY KEY (`policy_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Danh sách chính sách nhận phòng';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `check_in_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `check_in_requests` (
  `request_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, tự động tăng',
  `booking_id` int NOT NULL COMMENT 'ID của booking liên quan',
  `policy_id` int DEFAULT NULL COMMENT 'Chính sách check-in được áp dụng',
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Loại yêu cầu check-in (VD: Standard, Early, Walk-in)',
  `requested_check_in_time` datetime NOT NULL COMMENT 'Thời gian yêu cầu check-in',
  `fee_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Phí bổ sung (VD: phí check-in sớm)',
  `special_requests` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Yêu cầu đặc biệt (JSON hoặc text, VD: tầng cao, giường phụ)',
  `total_amount_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Tổng chi phí cần thanh toán (bao gồm phí sớm nếu có)',
  `status` enum('Pending','Approved','Rejected','Awaiting Payment','Missing Document','Room Unavailable') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Pending' COMMENT 'Trạng thái yêu cầu check-in',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`request_id`),
  KEY `booking_id` (`booking_id`),
  KEY `policy_id` (`policy_id`),
  CONSTRAINT `check_in_requests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `check_in_requests_ibfk_2` FOREIGN KEY (`policy_id`) REFERENCES `check_in_policies` (`policy_id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Yêu cầu check-in từ khách';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `check_out_policies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `check_out_policies` (
  `policy_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `early_check_out_fee_vnd` decimal(15,2) DEFAULT NULL,
  `late_check_out_fee_vnd` decimal(15,2) DEFAULT NULL,
  `late_check_out_max_hours` int DEFAULT NULL COMMENT 'Số giờ tối đa trả phòng muộn',
  `early_check_out_max_hours` int DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `priority` int NOT NULL DEFAULT '0',
  `conditions` json DEFAULT NULL,
  `applies_to_weekend` tinyint(1) NOT NULL DEFAULT '0',
  `applies_to_holiday` tinyint(1) NOT NULL DEFAULT '0',
  `standard_check_out_time` time NOT NULL DEFAULT '12:00:00',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `action` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Hành động khi chính sách được áp dụng',
  PRIMARY KEY (`policy_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `check_out_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `check_out_requests` (
  `request_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, tự động tăng',
  `booking_id` int NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `requested_check_out_time` datetime NOT NULL,
  `fee_vnd` decimal(15,2) DEFAULT '0.00',
  `status` enum('Pending','Approved','Rejected','Awaiting Payment','Disputed','Incomplete') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Pending' COMMENT 'Trạng thái yêu cầu check-out',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `policy_id` int DEFAULT NULL COMMENT 'Chính sách check-out được áp dụng',
  `special_requests` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Yêu cầu đặc biệt (JSON hoặc text, ví dụ: lưu trữ hành lý, hóa đơn đa ngôn ngữ)',
  `total_amount_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Tổng chi phí cần thanh toán (phòng + dịch vụ + phí bổ sung)',
  PRIMARY KEY (`request_id`),
  KEY `booking_id` (`booking_id`),
  KEY `policy_id` (`policy_id`),
  CONSTRAINT `check_out_requests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `check_out_requests_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `check_out_requests_ibfk_3` FOREIGN KEY (`policy_id`) REFERENCES `check_out_policies` (`policy_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `children_surcharges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `children_surcharges` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `min_age` tinyint unsigned NOT NULL,
  `max_age` tinyint unsigned NOT NULL,
  `is_free` tinyint(1) NOT NULL DEFAULT '0',
  `count_as_adult` tinyint(1) NOT NULL DEFAULT '0',
  `requires_extra_bed` tinyint(1) NOT NULL DEFAULT '0',
  `surcharge_amount_vnd` int unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `booking_id` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `compensation_policies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compensation_policies` (
  `compensation_policy_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên chính sách bồi thường',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả chi tiết',
  `applies_to_room_type_id` int DEFAULT NULL COMMENT 'Loại phòng áp dụng, NULL nếu áp dụng cho tất cả',
  `condition_type` enum('room_damage','service_failure','overbooking','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Loại sự cố áp dụng',
  `discount_type` enum('percentage','fixed_amount') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Loại giảm giá: % hoặc số tiền cố định',
  `discount_value` decimal(15,2) NOT NULL COMMENT 'Giá trị giảm',
  `max_compensation_amount` decimal(15,2) DEFAULT NULL COMMENT 'Mức bồi thường tối đa (nếu có)',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Trạng thái hoạt động',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`compensation_policy_id`),
  KEY `applies_to_room_type_id` (`applies_to_room_type_id`),
  CONSTRAINT `compensation_policies_ibfk_1` FOREIGN KEY (`applies_to_room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `compensation_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compensation_requests` (
  `request_id` int NOT NULL AUTO_INCREMENT COMMENT 'ID yêu cầu bồi thường',
  `booking_id` int NOT NULL COMMENT 'ID booking liên quan',
  `requested_by` bigint unsigned NOT NULL COMMENT 'ID lễ tân gửi yêu cầu (users.id)',
  `policy_id` int DEFAULT NULL COMMENT 'ID chính sách trong compensation_policies, NULL nếu là yêu cầu khác',
  `custom_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Lý do nhập tay nếu không chọn policy có sẵn',
  `status` enum('pending','approved','rejected','applied') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'pending' COMMENT 'Trạng thái xử lý',
  `requested_amount` decimal(15,2) DEFAULT NULL COMMENT 'Số tiền lễ tân đề xuất bồi thường (nếu có)',
  `approved_amount` decimal(15,2) DEFAULT NULL COMMENT 'Số tiền quản lý duyệt cuối cùng',
  `approved_by` bigint unsigned NOT NULL COMMENT 'ID người duyệt (users.id)',
  `approved_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian duyệt',
  `attachments` json DEFAULT NULL COMMENT 'Danh sách file đính kèm (ảnh/video)',
  `admin_note` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Ghi chú của quản lý khi duyệt hoặc từ chối',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`request_id`),
  KEY `booking_id` (`booking_id`),
  KEY `policy_id` (`policy_id`),
  KEY `requested_by` (`requested_by`),
  KEY `approved_by` (`approved_by`),
  CONSTRAINT `compensation_requests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `compensation_requests_ibfk_2` FOREIGN KEY (`policy_id`) REFERENCES `compensation_policies` (`compensation_policy_id`) ON DELETE SET NULL,
  CONSTRAINT `compensation_requests_ibfk_3` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `compensation_requests_ibfk_4` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conversations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'Người dùng khởi tạo cuộc trò chuyện',
  `client_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Mã định danh của người không đăng nhập',
  `is_bot_only` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Chỉ là chat với bot',
  `handover_to_user_id` bigint unsigned DEFAULT NULL COMMENT 'Chuyển tiếp cho nhân viên nếu cần',
  `status` enum('open','active','closed','pending') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open' COMMENT 'Trạng thái hội thoại',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conversations_client_token_unique` (`client_token`),
  KEY `conversations_user_id_foreign` (`user_id`),
  KEY `conversations_handover_to_user_id_foreign` (`handover_to_user_id`),
  CONSTRAINT `conversations_handover_to_user_id_foreign` FOREIGN KEY (`handover_to_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `conversations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `currency`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `currency` (
  `currency_code` varchar(3) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Khóa chính, mã tiền tệ (VND, USD, v.v.)',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên tiền tệ',
  `exchange_rate` decimal(10,4) DEFAULT NULL COMMENT 'Tỷ giá so với VND',
  `symbol` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Ký hiệu tiền tệ (₫, $, v.v.)',
  `format` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Định dạng (ví dụ: {amount} ₫)',
  PRIMARY KEY (`currency_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin tiền tệ và tỷ giá';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `datafeeds`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `datafeeds` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` float DEFAULT NULL,
  `dataset_name` tinyint DEFAULT NULL,
  `data_type` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `deposit_policies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `deposit_policies` (
  `policy_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deposit_percentage` decimal(5,2) DEFAULT NULL,
  `deposit_fixed_amount_vnd` decimal(15,2) DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `priority` int NOT NULL DEFAULT '0',
  `conditions` json DEFAULT NULL,
  `applies_to_weekend` tinyint(1) NOT NULL DEFAULT '0',
  `applies_to_holiday` tinyint(1) NOT NULL DEFAULT '0',
  `min_days_before_checkin` int DEFAULT NULL,
  `min_booking_amount` decimal(15,2) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`policy_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `dynamic_pricing_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dynamic_pricing_rules` (
  `rule_id` int NOT NULL AUTO_INCREMENT,
  `room_type_id` int DEFAULT NULL,
  `occupancy_threshold` decimal(5,2) NOT NULL COMMENT 'Ngưỡng tỷ lệ lấp đầy (%)',
  `price_adjustment` decimal(5,2) NOT NULL COMMENT 'Tỷ lệ điều chỉnh giá (%)',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `priority` int NOT NULL DEFAULT '5' COMMENT 'Mức độ ưu tiên (1 là cao nhất)',
  `is_exclusive` tinyint(1) DEFAULT '0' COMMENT 'Quy tắc độc quyền khi bật flag',
  PRIMARY KEY (`rule_id`),
  KEY `room_type_id` (`room_type_id`),
  KEY `idx_dynamic_priority` (`priority`,`is_exclusive`),
  CONSTRAINT `dynamic_pricing_rules_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `events` (
  `event_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`event_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `extension_policies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `extension_policies` (
  `policy_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã chính sách gia hạn',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên chính sách',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả chính sách gia hạn',
  `max_extension_days` int DEFAULT NULL COMMENT 'Số ngày tối đa được phép gia hạn, NULL nếu không giới hạn',
  `extension_fee_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Phí gia hạn cố định (VND)',
  `extension_percentage` decimal(5,2) DEFAULT '0.00' COMMENT 'Phí gia hạn theo phần trăm giá phòng',
  `min_days_before_checkout` int DEFAULT NULL COMMENT 'Số ngày tối thiểu trước ngày trả phòng để gia hạn',
  `applies_to_holiday` tinyint(1) DEFAULT '0' COMMENT 'Áp dụng cho ngày lễ',
  `applies_to_weekend` tinyint(1) DEFAULT '0' COMMENT 'Áp dụng cho cuối tuần',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Trạng thái hoạt động',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`policy_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Danh sách chính sách gia hạn đặt phòng';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `extension_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `extension_requests` (
  `request_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã yêu cầu gia hạn',
  `booking_id` int NOT NULL COMMENT 'Khóa ngoại, mã đặt phòng',
  `extension_policy_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã chính sách gia hạn',
  `new_check_out_date` date NOT NULL COMMENT 'Ngày trả phòng mới',
  `extension_days` int NOT NULL COMMENT 'Số ngày gia hạn',
  `extension_fee_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Phí gia hạn (VND)',
  `status` enum('Pending','Approved','Rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Pending' COMMENT 'Trạng thái yêu cầu',
  `processed_by` bigint unsigned DEFAULT NULL COMMENT 'Khóa ngoại, mã người xử lý',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Ghi chú yêu cầu',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`request_id`),
  KEY `booking_id` (`booking_id`),
  KEY `extension_policy_id` (`extension_policy_id`),
  KEY `processed_by` (`processed_by`),
  CONSTRAINT `extension_requests_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `extension_requests_ibfk_2` FOREIGN KEY (`extension_policy_id`) REFERENCES `extension_policies` (`policy_id`) ON DELETE SET NULL,
  CONSTRAINT `extension_requests_ibfk_3` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu trữ yêu cầu gia hạn đặt phòng';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faqs` (
  `faq_id` int NOT NULL AUTO_INCREMENT,
  `question_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Câu hỏi (tiếng Anh)',
  `question_vi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Câu hỏi (tiếng Việt)',
  `answer_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Câu trả lời (tiếng Anh)',
  `answer_vi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Câu trả lời (tiếng Việt)',
  `sort_order` int DEFAULT '0' COMMENT 'Thứ tự sắp xếp câu hỏi',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Trạng thái hoạt động (0: không, 1: có)',
  `priority` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo câu hỏi',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật câu hỏi',
  PRIMARY KEY (`faq_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lưu trữ câu hỏi thường gặp và câu trả lời';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `flexible_pricing_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `flexible_pricing_rules` (
  `rule_id` int NOT NULL AUTO_INCREMENT,
  `room_type_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã loại phòng (NULL: áp dụng cho tất cả loại phòng)',
  `rule_type` enum('weekend','event','holiday','season') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Loại quy tắc: cuối tuần, sự kiện, ngày lễ, mùa',
  `days_of_week` json DEFAULT NULL COMMENT 'Danh sách ngày cuối tuần áp dụng (JSON, dùng cho rule_type=weekend)',
  `event_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã sự kiện (dùng cho rule_type=event)',
  `holiday_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã ngày lễ (dùng cho rule_type=holiday)',
  `season_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Tên mùa (dùng cho rule_type=season)',
  `start_date` date DEFAULT NULL COMMENT 'Ngày bắt đầu áp dụng (dùng cho rule_type=season hoặc giới hạn thời gian)',
  `end_date` date DEFAULT NULL COMMENT 'Ngày kết thúc áp dụng (dùng cho rule_type=season hoặc giới hạn thời gian)',
  `price_adjustment` decimal(5,2) NOT NULL COMMENT 'Tỷ lệ điều chỉnh giá (%, dương để tăng, âm để giảm)',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Trạng thái kích hoạt',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `priority` int NOT NULL DEFAULT '5' COMMENT 'Mức độ ưu tiên (1 là cao nhất)',
  `is_exclusive` tinyint(1) DEFAULT '0' COMMENT 'Quy tắc độc quyền khi bật flag',
  PRIMARY KEY (`rule_id`),
  KEY `flexible_pricing_room_type_id_index` (`room_type_id`),
  KEY `flexible_pricing_event_id_index` (`event_id`),
  KEY `flexible_pricing_holiday_id_index` (`holiday_id`),
  KEY `idx_priority` (`priority`,`is_exclusive`),
  CONSTRAINT `flexible_pricing_rules_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE SET NULL,
  CONSTRAINT `flexible_pricing_rules_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE SET NULL,
  CONSTRAINT `flexible_pricing_rules_ibfk_3` FOREIGN KEY (`holiday_id`) REFERENCES `holidays` (`holiday_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Quy tắc giá linh động cho cuối tuần, sự kiện, ngày lễ, mùa';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `floors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `floors` (
  `floor_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã tầng',
  `floor_number` int NOT NULL COMMENT 'Số tầng (1-34)',
  `floor_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên tầng (Tầng trệt, Tầng 1, etc.)',
  `floor_type` enum('ground','residential','service','special','penthouse') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'residential' COMMENT 'Loại tầng',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả tầng và tiện ích đặc biệt',
  `facilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Các tiện ích có trên tầng này',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Tầng có hoạt động không',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối',
  PRIMARY KEY (`floor_id`),
  UNIQUE KEY `floor_number` (`floor_number`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng quản lý các tầng của khách sạn';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `holidays`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `holidays` (
  `holiday_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`holiday_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `hotel`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hotel` (
  `hotel_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã khách sạn',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên khách sạn',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Địa chỉ khách sạn',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả khách sạn',
  PRIMARY KEY (`hotel_id`),
  KEY `idx_hotel_id` (`hotel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin khách sạn';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `invoice_id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int NOT NULL,
  `total_amount_vnd` decimal(15,2) NOT NULL,
  `issued_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Draft','Sent','Paid') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Draft',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`invoice_id`),
  KEY `booking_id` (`booking_id`),
  CONSTRAINT `invoices_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin hóa đơn';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `language`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `language` (
  `language_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Khóa chính, mã ngôn ngữ (vi, en, v.v.)',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên ngôn ngữ (Vietnamese, English, v.v.)',
  PRIMARY KEY (`language_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu danh sách ngôn ngữ hỗ trợ';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `meal_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `meal_types` (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính',
  `type_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên loại bữa ăn',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả bữa ăn',
  `base_price_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Giá cơ bản (VND)',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Trạng thái hoạt động',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Danh sách các loại bữa ăn';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `media_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính của file ảnh/media',
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên file gốc (ví dụ: khachsan1.jpg)',
  `filepath` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Đường dẫn file (ví dụ: /storage/media/khachsan1.jpg)',
  `alt_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thuộc tính ALT – giúp SEO hình ảnh',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tiêu đề ảnh hiển thị khi hover',
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại file (ví dụ: image/jpeg, image/webp...)',
  `size` int DEFAULT NULL COMMENT 'Dung lượng file tính bằng byte',
  `used_in` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ngữ cảnh sử dụng (ví dụ: news, banner, home)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm upload',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Quản lý file media (ảnh đại diện, ảnh nội dung...) hỗ trợ SEO hình ảnh';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint unsigned NOT NULL,
  `sender_type` enum('user','staff','bot','guest') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `sender_id` bigint unsigned DEFAULT NULL COMMENT 'user_id nếu là người dùng, staff_id nếu là nhân viên, null nếu là bot',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung tin nhắn',
  `is_from_bot` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_read` tinyint DEFAULT '0',
  `message_type` enum('text','image','file','system') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `messages_conversation_id_foreign` (`conversation_id`),
  CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã bài viết',
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Đường dẫn không dấu, duy nhất cho mỗi bài viết (SEO)',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tiêu đề bài viết',
  `summary` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Tóm tắt ngắn nội dung bài viết',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Nội dung chi tiết bài viết (HTML)',
  `tags` json DEFAULT NULL COMMENT 'Danh sách tag (mảng string, phục vụ tìm kiếm, phân loại)',
  `thumbnail_id` bigint unsigned DEFAULT NULL COMMENT 'ID ảnh đại diện (liên kết media_files)',
  `author_id` bigint unsigned DEFAULT NULL COMMENT 'ID tác giả (liên kết users)',
  `category_id` bigint unsigned DEFAULT NULL COMMENT 'ID chuyên mục/danh mục (liên kết news_categories)',
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Tiêu đề SEO (meta title)',
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả SEO (meta description)',
  `meta_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Từ khóa SEO (meta keywords)',
  `canonical_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'URL chuẩn SEO (canonical)',
  `schema_json` json DEFAULT NULL COMMENT 'Dữ liệu cấu trúc SEO (schema.org, dạng JSON)',
  `views` int DEFAULT '0' COMMENT 'Số lượt xem bài viết',
  `status` tinyint DEFAULT '1' COMMENT 'Trạng thái bài viết (1: hiển thị, 0: ẩn, nháp...)',
  `is_featured` tinyint(1) DEFAULT '1',
  `published_at` datetime DEFAULT NULL COMMENT 'Thời điểm xuất bản',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `thumbnail_id` (`thumbnail_id`),
  KEY `author_id` (`author_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `news_ibfk_1` FOREIGN KEY (`thumbnail_id`) REFERENCES `media_files` (`id`),
  CONSTRAINT `news_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
  CONSTRAINT `news_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `news_categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `news1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news1` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, định danh bài viết',
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug URL thân thiện SEO – không dấu, không trùng',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Nội dung đầy đủ bài viết (có thể chứa HTML, ảnh...)',
  `thumbnail_id` bigint unsigned DEFAULT NULL COMMENT 'ID ảnh đại diện – liên kết đến bảng media_files',
  `author_id` bigint unsigned DEFAULT NULL COMMENT 'ID người tạo bài viết – liên kết bảng users',
  `meta_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thẻ <title> tùy chỉnh cho SEO (nếu không để trống thì dùng thay title)',
  `meta_description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả bài viết hiển thị trong kết quả tìm kiếm Google',
  `meta_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Từ khóa SEO cách nhau bằng dấu phẩy (,)',
  `canonical_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'URL chuẩn để tránh trùng lặp nội dung',
  `schema_json` json DEFAULT NULL COMMENT 'Dữ liệu Schema.org ở dạng JSON-LD để Google hiểu nội dung',
  `views` int DEFAULT '0' COMMENT 'Số lượt xem bài viết',
  `status` tinyint DEFAULT '1' COMMENT '1: Hiển thị, 0: Ẩn bài viết',
  `published_at` datetime DEFAULT NULL COMMENT 'Ngày giờ bài viết được xuất bản',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật',
  `category_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `fk_news_thumbnail` (`thumbnail_id`),
  KEY `fk_news_author` (`author_id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `fk_news_author` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_news_thumbnail` FOREIGN KEY (`thumbnail_id`) REFERENCES `media_files` (`id`) ON DELETE SET NULL,
  CONSTRAINT `news1_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `news_categories` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng lưu bài viết tin tức chuẩn SEO cho website khách sạn có phân quyền người viết';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `news_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính chuyên mục',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên chuyên mục (ví dụ: Ưu đãi, Tin tức, Hướng dẫn...)',
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug URL của chuyên mục (không dấu)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả ngắn giúp định nghĩa mục đích chuyên mục',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh mục tin tức phân loại nội dung';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `news_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news_comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `news_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `likes` int DEFAULT '0',
  `parent_id` bigint unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `news_id` (`news_id`),
  KEY `user_id` (`user_id`),
  KEY `parent_id` (`parent_id`),
  CONSTRAINT `news_comments_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`),
  CONSTRAINT `news_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `news_comments_ibfk_3` FOREIGN KEY (`parent_id`) REFERENCES `news_comments` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `news_user_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news_user_actions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `news_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `is_liked` tinyint DEFAULT '0',
  `is_bookmarked` tinyint DEFAULT '0',
  `rating` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `news_id` (`news_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `news_user_actions_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`),
  CONSTRAINT `news_user_actions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `notification_id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `type` enum('cancellation','extension','reschedule','transfer','check_out') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('pending','sent','failed') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`),
  KEY `booking_id` (`booking_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE SET NULL,
  CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `payment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment` (
  `payment_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã thanh toán',
  `booking_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã đặt phòng',
  `amount_vnd` decimal(15,2) NOT NULL COMMENT 'Số tiền thanh toán (VND)',
  `payment_type` enum('deposit','full','qr_code','at_hotel','vietqr','refund','additional') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Loại thanh toán',
  `status` enum('pending','completed','failed','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Trạng thái thanh toán',
  `transaction_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Mã giao dịch (từ cổng thanh toán)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`payment_id`),
  KEY `idx_booking_status` (`booking_id`,`status`),
  CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=138 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin thanh toán';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `payment_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `type` enum('string','number','boolean','json') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `group_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `is_encrypted` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payment_settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permission_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permission_role` (
  `permission_id` int NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `permission_role_ibfk_1` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `parent_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `idx_permissions_parent_id` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `policy_applications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `policy_applications` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID auto increment',
  `room_type_id` int unsigned DEFAULT NULL COMMENT 'NULL = áp dụng toàn bộ loại phòng; khác NULL = áp dụng cho 1 loại phòng cụ thể',
  `policy_type` enum('cancellation','deposit','check_out') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Loại chính sách: hủy, đặt cọc, trả phòng',
  `policy_id` int unsigned NOT NULL COMMENT 'ID chính sách cụ thể trong bảng tương ứng (cancellation_policies, deposit_policies, check_out_policies)',
  `applies_to_holiday` tinyint(1) DEFAULT '0' COMMENT 'Áp dụng nếu là ngày lễ (theo holiday_events)?',
  `min_occupancy_percent` tinyint unsigned DEFAULT NULL COMMENT 'Áp dụng nếu tỉ lệ lấp đầy >= giá trị này (%)',
  `max_occupancy_percent` tinyint unsigned DEFAULT NULL COMMENT 'Áp dụng nếu tỉ lệ lấp đầy <= giá trị này (%)',
  `min_days_before_checkin` int unsigned DEFAULT NULL COMMENT 'Áp dụng nếu số ngày hủy >= giá trị này (chỉ dành cho cancellation)',
  `date_from` date DEFAULT NULL COMMENT 'Ngày bắt đầu hiệu lực (nếu áp dụng theo ngày cụ thể)',
  `date_to` date DEFAULT NULL COMMENT 'Ngày kết thúc hiệu lực',
  `priority` tinyint unsigned DEFAULT '1' COMMENT 'Ưu tiên khi có nhiều bản ghi cùng khớp điều kiện',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Có đang được bật không?',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_policy_match` (`room_type_id`,`policy_type`,`applies_to_holiday`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Ánh xạ chính sách theo điều kiện áp dụng như loại phòng, ngày lễ, occupancy, ngày cụ thể';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `pricing_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pricing_config` (
  `config_id` int NOT NULL AUTO_INCREMENT,
  `max_price_increase_percentage` decimal(5,2) DEFAULT '40.00' COMMENT 'Giới hạn tăng giá tối đa (%)',
  `max_absolute_price_vnd` decimal(15,2) DEFAULT '3000000.00' COMMENT 'Giới hạn giá trần tuyệt đối (VND)',
  `use_exclusive_rule` tinyint(1) DEFAULT '0' COMMENT 'Bật chế độ chỉ lấy quy tắc ưu tiên cao nhất',
  `exclusive_rule_type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Loại quy tắc độc quyền (event, holiday, season, weekend, occupancy)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`config_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `representatives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `representatives` (
  `id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int NOT NULL,
  `booking_code` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `room_id` int DEFAULT NULL,
  `full_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone_number` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `id_card` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `room_id` (`room_id`),
  KEY `fk_representative_user` (`user_id`),
  CONSTRAINT `fk_representative_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `representatives_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`),
  CONSTRAINT `representatives_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`)
) ENGINE=InnoDB AUTO_INCREMENT=121 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reschedule_policies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reschedule_policies` (
  `policy_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `room_type_id` int DEFAULT NULL,
  `min_days_before_checkin` int DEFAULT NULL COMMENT 'Số ngày tối thiểu trước check-in để được phép rời lịch',
  `reschedule_fee_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Phí rời lịch cố định (nếu có)',
  `reschedule_fee_percentage` decimal(5,2) DEFAULT '0.00' COMMENT 'Phí rời lịch theo phần trăm giá booking',
  `applies_to_holiday` tinyint(1) DEFAULT '0' COMMENT 'Áp dụng cho ngày lễ',
  `applies_to_weekend` tinyint(1) DEFAULT '0' COMMENT 'Áp dụng cho cuối tuần',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Trạng thái hoạt động',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`policy_id`),
  KEY `room_type_id` (`room_type_id`),
  CONSTRAINT `reschedule_policies_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `review_id` int NOT NULL AUTO_INCREMENT COMMENT 'Mã đánh giá',
  `booking_id` int NOT NULL COMMENT 'Liên kết đến booking',
  `rating` decimal(2,1) NOT NULL COMMENT 'Điểm đánh giá (0.0 – 5.0)',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `review_date` date NOT NULL,
  `helpful` int unsigned DEFAULT '0',
  `not_helpful` int unsigned DEFAULT '0',
  `travel_type` enum('business','couple','solo','family_young','group') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `admin_reply_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `admin_reply_date` date DEFAULT NULL,
  `admin_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `score_cleanliness` decimal(2,1) DEFAULT NULL COMMENT 'Độ sạch sẽ',
  `score_location` decimal(2,1) DEFAULT NULL COMMENT 'Vị trí',
  `score_facilities` decimal(2,1) DEFAULT NULL COMMENT 'Cơ sở vật chất',
  `score_service` decimal(2,1) DEFAULT NULL COMMENT 'Dịch vụ',
  `score_value` decimal(2,1) DEFAULT NULL COMMENT 'Đáng giá tiền',
  `status` enum('pending','approved','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`review_id`),
  KEY `booking_id` (`booking_id`),
  CONSTRAINT `fk_reviews_booking` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Đánh giá gắn với booking';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `role_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_user` (
  `user_id` bigint unsigned NOT NULL,
  `role_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  UNIQUE KEY `unique_user_role` (`user_id`),
  KEY `role_id` (`role_id`),
  CONSTRAINT `role_user_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_user_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room` (
  `room_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã phòng',
  `room_type_id` int NOT NULL COMMENT 'Khóa ngoại, mã loại phòng',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên phòng',
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Đường dẫn ảnh chính',
  `floor_id` int DEFAULT NULL COMMENT 'Tầng của phòng',
  `bed_type_fixed` int DEFAULT NULL COMMENT 'Loại giường mặc định',
  `status` enum('available','out_of_service') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả chi tiết phòng',
  `last_cleaned` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`room_id`),
  KEY `idx_room_type_id` (`room_type_id`),
  KEY `bed_type_fixed` (`bed_type_fixed`),
  KEY `floor_id` (`floor_id`),
  CONSTRAINT `room_ibfk_2` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `room_ibfk_3` FOREIGN KEY (`bed_type_fixed`) REFERENCES `bed_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `room_ibfk_4` FOREIGN KEY (`floor_id`) REFERENCES `floors` (`floor_number`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB AUTO_INCREMENT=296 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin phòng';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `room_bed_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_bed_types` (
  `room_id` int NOT NULL COMMENT 'Khóa chính',
  `bed_type_id` int NOT NULL COMMENT 'Khóa ngoại, mã loại giường',
  `quantity` int DEFAULT '1' COMMENT 'Số lượng giường loại này',
  `is_default` tinyint(1) DEFAULT '0' COMMENT 'Có phải tùy chọn mặc định',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`room_id`),
  UNIQUE KEY `room_bed_unique` (`bed_type_id`),
  KEY `idx_bed_type_id` (`bed_type_id`),
  CONSTRAINT `fk_room_bed_type` FOREIGN KEY (`bed_type_id`) REFERENCES `bed_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `room_bed_types_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Bảng trung gian: Phòng - Loại giường';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `room_meal_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_meal_types` (
  `room_id` int NOT NULL COMMENT 'Khóa chính, khóa ngoại',
  `is_default` tinyint(1) DEFAULT '0',
  `meal_type_id` int NOT NULL COMMENT 'Khóa ngoại, mã loại bữa ăn',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`room_id`),
  UNIQUE KEY `room_meal_unique` (`meal_type_id`),
  KEY `idx_meal_type_id` (`meal_type_id`),
  CONSTRAINT `fk_room_meal_type` FOREIGN KEY (`meal_type_id`) REFERENCES `meal_types` (`id`) ON DELETE CASCADE,
  CONSTRAINT `room_meal_types_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Bảng trung gian: Phòng - Loại bữa ăn';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `room_occupancy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_occupancy` (
  `occupancy_id` int NOT NULL AUTO_INCREMENT,
  `room_type_id` int NOT NULL,
  `date` date NOT NULL,
  `total_rooms` int NOT NULL COMMENT 'Tổng số phòng của loại phòng',
  `booked_rooms` int NOT NULL COMMENT 'Số phòng đã được đặt',
  `occupancy_rate` decimal(5,2) GENERATED ALWAYS AS (((`booked_rooms` / `total_rooms`) * 100)) STORED COMMENT 'Tỷ lệ lấp đầy (%)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`occupancy_id`),
  UNIQUE KEY `idx_room_type_date` (`room_type_id`,`date`),
  CONSTRAINT `room_occupancy_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `room_option`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_option` (
  `option_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Khóa chính, mã tùy chọn',
  `room_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã phòng',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên tùy chọn',
  `price_per_night_vnd` decimal(15,2) NOT NULL COMMENT 'Giá mỗi đêm (VND)',
  `max_guests` int NOT NULL COMMENT 'Số khách tối đa',
  `min_guests` int NOT NULL COMMENT 'Số khách tối thiểu',
  `urgency_message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Thông báo khan hiếm',
  `most_popular` tinyint(1) DEFAULT '0' COMMENT 'Tùy chọn phổ biến nhất',
  `recommended` tinyint(1) DEFAULT '0' COMMENT 'Tùy chọn được đề xuất',
  `meal_type` int DEFAULT NULL COMMENT 'Khóa ngoại, mã bữa ăn',
  `bed_type` int DEFAULT NULL COMMENT 'Khóa ngoại, mã tùy chọn giường',
  `recommendation_score` decimal(5,2) DEFAULT NULL,
  `deposit_policy_id` int DEFAULT NULL,
  `check_out_policy_id` int DEFAULT NULL,
  `policy_applied_reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `policy_applied_date` date DEFAULT NULL,
  `policy_snapshot_json` json DEFAULT NULL,
  `cancellation_policy_id` int DEFAULT NULL,
  `package_id` int DEFAULT NULL,
  `adjusted_price` decimal(15,2) DEFAULT NULL COMMENT 'Giá sau khi áp dụng các quy tắc',
  `created_at` date DEFAULT NULL,
  `updated_at` date DEFAULT NULL,
  PRIMARY KEY (`option_id`),
  KEY `idx_room_id` (`room_id`),
  KEY `bed_type` (`bed_type`),
  KEY `meal_type` (`meal_type`),
  KEY `fk_room_option_deposit_policy` (`deposit_policy_id`),
  KEY `fk_room_option_cancellation_policy` (`cancellation_policy_id`),
  KEY `package_id` (`package_id`),
  KEY `check_out_policy_id` (`check_out_policy_id`),
  CONSTRAINT `fk_room_option_cancellation_policy` FOREIGN KEY (`cancellation_policy_id`) REFERENCES `cancellation_policies` (`policy_id`) ON DELETE SET NULL,
  CONSTRAINT `fk_room_option_deposit_policy` FOREIGN KEY (`deposit_policy_id`) REFERENCES `deposit_policies` (`policy_id`) ON DELETE SET NULL,
  CONSTRAINT `room_option_ibfk_1` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE CASCADE,
  CONSTRAINT `room_option_ibfk_2` FOREIGN KEY (`bed_type`) REFERENCES `bed_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `room_option_ibfk_3` FOREIGN KEY (`meal_type`) REFERENCES `meal_types` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `room_option_ibfk_4` FOREIGN KEY (`package_id`) REFERENCES `room_type_package` (`package_id`) ON DELETE SET NULL,
  CONSTRAINT `room_option_ibfk_5` FOREIGN KEY (`check_out_policy_id`) REFERENCES `check_out_policies` (`policy_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu tùy chọn giá và dịch vụ của phòng';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `room_option_promotion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_option_promotion` (
  `promotion_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã khuyến mãi',
  `option_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Khóa ngoại, mã tùy chọn',
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Loại khuyến mãi (hot, limited)',
  `message` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Thông điệp khuyến mãi',
  `discount` decimal(5,2) DEFAULT NULL COMMENT 'Mức giảm giá (%)',
  PRIMARY KEY (`promotion_id`),
  KEY `option_id` (`option_id`),
  CONSTRAINT `room_option_promotion_ibfk_1` FOREIGN KEY (`option_id`) REFERENCES `room_option` (`option_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu khuyến mãi của tùy chọn phòng';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `room_price_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_price_history` (
  `price_history_id` int NOT NULL AUTO_INCREMENT,
  `room_type_id` int NOT NULL,
  `date` date NOT NULL,
  `base_price` decimal(15,2) NOT NULL COMMENT 'Giá cơ bản',
  `adjusted_price` decimal(15,2) NOT NULL COMMENT 'Giá sau điều chỉnh',
  `applied_rules` json DEFAULT NULL COMMENT 'Danh sách quy tắc áp dụng (ID và loại quy tắc)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`price_history_id`),
  UNIQUE KEY `idx_room_type_date` (`room_type_id`,`date`),
  CONSTRAINT `room_price_history_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=319 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `room_transfer_policies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_transfer_policies` (
  `policy_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên chính sách',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Mô tả chính sách',
  `transfer_fee_vnd` decimal(15,2) DEFAULT '0.00' COMMENT 'Phí chuyển phòng cố định (VND)',
  `transfer_fee_percentage` decimal(5,2) DEFAULT '0.00' COMMENT 'Phí chuyển phòng theo phần trăm giá phòng mới',
  `min_days_before_check_in` int DEFAULT NULL COMMENT 'Số ngày tối thiểu trước check-in để áp dụng chuyển phòng',
  `applies_to_holiday` tinyint(1) DEFAULT '0' COMMENT 'Áp dụng cho ngày lễ',
  `applies_to_weekend` tinyint(1) DEFAULT '0' COMMENT 'Áp dụng cho cuối tuần',
  `requires_guest_confirmation` tinyint(1) DEFAULT '1' COMMENT 'Yêu cầu xác nhận từ khách',
  `room_type_id` int DEFAULT NULL COMMENT 'Loại phòng áp dụng (NULL nếu áp dụng tất cả)',
  `is_active` tinyint(1) DEFAULT '1' COMMENT 'Trạng thái kích hoạt',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` date DEFAULT NULL,
  `applies_to_package_change` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`policy_id`),
  KEY `room_type_id` (`room_type_id`),
  CONSTRAINT `room_transfer_policies_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Chính sách chuyển phòng';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `room_transfers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_transfers` (
  `transfer_id` int NOT NULL AUTO_INCREMENT,
  `booking_id` int NOT NULL,
  `old_room_id` int NOT NULL,
  `new_room_id` int NOT NULL,
  `new_option_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Mã gói phòng mới',
  `transfer_policy_id` int DEFAULT NULL COMMENT 'Mã chính sách chuyển phòng',
  `status` enum('Pending','Approved','Rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'Pending' COMMENT 'Trạng thái yêu cầu',
  `price_difference_vnd` decimal(15,2) DEFAULT NULL COMMENT 'Chênh lệch giá (dương: bù tiền, âm: hoàn tiền)',
  `payment_id` int DEFAULT NULL COMMENT 'Mã hóa đơn liên quan (nếu có bù tiền)',
  `processed_by` bigint unsigned DEFAULT NULL COMMENT 'Mã nhân viên xử lý',
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci COMMENT 'Lý do chuyển phòng',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`transfer_id`),
  KEY `booking_id` (`booking_id`),
  KEY `old_room_id` (`old_room_id`),
  KEY `new_room_id` (`new_room_id`),
  KEY `new_option_id` (`new_option_id`),
  KEY `transfer_policy_id` (`transfer_policy_id`),
  KEY `payment_id` (`payment_id`),
  KEY `processed_by` (`processed_by`),
  CONSTRAINT `room_transfers_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE,
  CONSTRAINT `room_transfers_ibfk_2` FOREIGN KEY (`old_room_id`) REFERENCES `room` (`room_id`) ON DELETE RESTRICT,
  CONSTRAINT `room_transfers_ibfk_3` FOREIGN KEY (`new_room_id`) REFERENCES `room` (`room_id`) ON DELETE RESTRICT,
  CONSTRAINT `room_transfers_ibfk_4` FOREIGN KEY (`new_option_id`) REFERENCES `room_option` (`option_id`) ON DELETE SET NULL,
  CONSTRAINT `room_transfers_ibfk_5` FOREIGN KEY (`transfer_policy_id`) REFERENCES `room_transfer_policies` (`policy_id`) ON DELETE SET NULL,
  CONSTRAINT `room_transfers_ibfk_6` FOREIGN KEY (`payment_id`) REFERENCES `payment` (`payment_id`) ON DELETE SET NULL,
  CONSTRAINT `room_transfers_ibfk_7` FOREIGN KEY (`processed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `room_type_amenity`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_type_amenity` (
  `room_type_id` int NOT NULL,
  `amenity_id` int NOT NULL,
  `is_highlighted` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`room_type_id`,`amenity_id`),
  KEY `amenity_id` (`amenity_id`),
  CONSTRAINT `room_type_amenity_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `room_type_amenity_ibfk_2` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`amenity_id`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `room_type_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_type_image` (
  `image_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã ảnh',
  `room_type_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã phòng',
  `alt_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `image_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Đường dẫn ảnh thư mục gốc',
  `image_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Đường dẫn ảnh API',
  `is_main` tinyint(1) DEFAULT '0' COMMENT 'Ảnh chính',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`image_id`),
  KEY `room_image_ibfk_1` (`room_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu danh sách ảnh của phòng';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `room_type_package`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_type_package` (
  `package_id` int NOT NULL AUTO_INCREMENT,
  `room_type_id` int NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `price_modifier_vnd` decimal(15,2) DEFAULT '0.00',
  `include_all_services` tinyint(1) DEFAULT '0',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`package_id`),
  KEY `room_type_id` (`room_type_id`),
  CONSTRAINT `room_type_package_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `room_type_package_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_type_package_services` (
  `id` int NOT NULL AUTO_INCREMENT,
  `package_id` int NOT NULL,
  `service_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `package_id` (`package_id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `room_type_package_services_ibfk_1` FOREIGN KEY (`package_id`) REFERENCES `room_type_package` (`package_id`),
  CONSTRAINT `room_type_package_services_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `room_type_service`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_type_service` (
  `id` int NOT NULL AUTO_INCREMENT,
  `room_type_id` int NOT NULL,
  `service_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `room_type_id` (`room_type_id`),
  KEY `service_id` (`service_id`),
  CONSTRAINT `room_type_service_ibfk_1` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE CASCADE,
  CONSTRAINT `room_type_service_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`service_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `room_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `room_types` (
  `room_type_id` int NOT NULL AUTO_INCREMENT,
  `room_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `total_room` int NOT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `room_area` int NOT NULL,
  `view` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `rating` int DEFAULT '0',
  `max_guests` int DEFAULT '0',
  `is_active` tinyint DEFAULT '1',
  PRIMARY KEY (`room_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `service_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `price_vnd` decimal(15,2) NOT NULL DEFAULT '0.00',
  `unit` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Ví dụ: lần, ngày, giờ, kg',
  `included_services` tinyint(1) DEFAULT '0',
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`service_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `payload` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `table_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `table_translation` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `table_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `display_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_translation_table_name_unique` (`table_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `translation` (
  `translation_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã bản dịch',
  `table_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên bảng (room, hotel, v.v.)',
  `column_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Tên cột (name, description, v.v.)',
  `record_id` int NOT NULL COMMENT 'Mã bản ghi',
  `language_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Mã ngôn ngữ (vi, en, v.v.)',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Giá trị bản dịch',
  PRIMARY KEY (`translation_id`),
  KEY `idx_translation` (`table_name`,`column_name`,`record_id`,`language_code`),
  KEY `language_code` (`language_code`),
  CONSTRAINT `translation_ibfk_1` FOREIGN KEY (`language_code`) REFERENCES `language` (`language_code`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu bản dịch cho các trường văn bản';
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` json NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`),
  KEY `user_notifications_read_at_index` (`read_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `google_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(12) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Số điện thoại',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Địa chỉ',
  `identity_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Số CCCD hoặc số hộ chiếu',
  `role` enum('guest','receptionist','manager','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Vai trò',
  `two_factor_secret` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `two_factor_recovery_codes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `current_team_id` bigint unsigned DEFAULT NULL,
  `profile_photo_path` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_google_id_index` (`google_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `weekend_days`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `weekend_days` (
  `id` int NOT NULL AUTO_INCREMENT,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
