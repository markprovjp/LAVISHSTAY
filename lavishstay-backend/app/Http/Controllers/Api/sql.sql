

    -- Bảng news_categories: Danh mục tin tức phân loại nội dung
CREATE TABLE `booking` (
  `booking_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã đặt phòng',
  `booking_code` varchar(55) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL COMMENT 'Khóa ngoại, mã người dùng (nếu có)',
  `option_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Khóa ngoại, mã tùy chọn phòng',
  `check_in_date` date NOT NULL COMMENT 'Ngày nhận phòng',
  `check_out_date` date NOT NULL COMMENT 'Ngày trả phòng',
  `total_price_vnd` decimal(15,2) NOT NULL COMMENT 'Tổng giá (VND)',
  `guest_count` int DEFAULT NULL COMMENT 'Số khách',
  `status` enum('Pending','Confirmed','Operational','Completed','Cancelled','Cancelled With Penalty','Unsuccessful') COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Trạng thái đặt phòng',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `quantity` int DEFAULT NULL,
  `room_type_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật',
  `guest_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Tên khách',
  `guest_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Email khách',
  `guest_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Số điện thoại khách',
  `room_id` int DEFAULT NULL,
  `children` int DEFAULT NULL,
  `children_age` json DEFAULT NULL,
  PRIMARY KEY (`booking_id`),
  KEY `option_id` (`option_id`),
  KEY `idx_check_in_out` (`check_in_date`,`check_out_date`),
  KEY `fk_booking_room` (`room_id`),
  KEY `room_type_id` (`room_type_id`),
  CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`option_id`) REFERENCES `room_option` (`option_id`) ON DELETE RESTRICT,
  CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`room_type_id`) REFERENCES `room_types` (`room_type_id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `fk_booking_room` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin đặt phòng'

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
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu tùy chọn giá và dịch vụ của phòng'


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
) ENGINE=InnoDB AUTO_INCREMENT=296 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin phòng'
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
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu danh sách ảnh của phòng'
CREATE TABLE `booking_room_children` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `booking_room_id` int unsigned NOT NULL,
  `age` int NOT NULL,
  `child_index` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci

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
  PRIMARY KEY (`id`),
  KEY `booking_id` (`booking_id`),
  KEY `room_id` (`room_id`),
  CONSTRAINT `representatives_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`),
  CONSTRAINT `representatives_ibfk_2` FOREIGN KEY (`room_id`) REFERENCES `room` (`room_id`)
) ENGINE=InnoDB AUTO_INCREMENT=115 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

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
) ENGINE=InnoDB AUTO_INCREMENT=296 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin phòng'

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
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Bảng quản lý các tầng của khách sạn'

CREATE TABLE `payment` (
  `payment_id` int NOT NULL AUTO_INCREMENT COMMENT 'Khóa chính, mã thanh toán',
  `booking_id` int DEFAULT NULL COMMENT 'Khóa ngoại, mã đặt phòng',
  `amount_vnd` decimal(15,2) NOT NULL COMMENT 'Số tiền thanh toán (VND)',
  `payment_type` enum('deposit','full','qr_code','at_hotel','vietqr') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Loại thanh toán',
  `status` enum('pending','completed','failed','refunded') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Trạng thái thanh toán',
  `transaction_id` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Mã giao dịch (từ cổng thanh toán)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật',
  PRIMARY KEY (`payment_id`),
  KEY `idx_booking_status` (`booking_id`,`status`),
  CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`booking_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Lưu thông tin thanh toán'

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci

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
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
-- Bảng news: Lưu bài viết, tags dạng JSON
CREATE TABLE news (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY COMMENT 'Khóa chính, mã bài viết',
  slug VARCHAR(255) NOT NULL UNIQUE COMMENT 'Đường dẫn không dấu, duy nhất cho mỗi bài viết (SEO)',
  title VARCHAR(255) NOT NULL COMMENT 'Tiêu đề bài viết',
  summary TEXT COMMENT 'Tóm tắt ngắn nội dung bài viết',
  content LONGTEXT COMMENT 'Nội dung chi tiết bài viết (HTML)',
  tags JSON COMMENT 'Danh sách tag (mảng string, phục vụ tìm kiếm, phân loại)',
  thumbnail_id BIGINT UNSIGNED COMMENT 'ID ảnh đại diện (liên kết media_files)',
  author_id BIGINT UNSIGNED COMMENT 'ID tác giả (liên kết users)',
  category_id BIGINT UNSIGNED COMMENT 'ID chuyên mục/danh mục (liên kết news_categories)',
  meta_title VARCHAR(255) COMMENT 'Tiêu đề SEO (meta title)',
  meta_description TEXT COMMENT 'Mô tả SEO (meta description)',
  meta_keywords VARCHAR(255) COMMENT 'Từ khóa SEO (meta keywords)',
  canonical_url VARCHAR(255) COMMENT 'URL chuẩn SEO (canonical)',
  schema_json JSON COMMENT 'Dữ liệu cấu trúc SEO (schema.org, dạng JSON)',
  views INT DEFAULT 0 COMMENT 'Số lượt xem bài viết',
  status TINYINT DEFAULT 1 COMMENT 'Trạng thái bài viết (1: hiển thị, 0: ẩn, nháp...)',
  published_at DATETIME COMMENT 'Thời điểm xuất bản',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm tạo',
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật',
  FOREIGN KEY (thumbnail_id) REFERENCES media_files(id),
  FOREIGN KEY (author_id) REFERENCES users(id),
  FOREIGN KEY (category_id) REFERENCES news_categories(id)
);

-- Bảng news_categories: Danh mục
CREATE TABLE `news_categories` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'Khóa chính chuyên mục',
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên chuyên mục (ví dụ: Ưu đãi, Tin tức, Hướng dẫn...)',
  `slug` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Slug URL của chuyên mục (không dấu)',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Mô tả ngắn giúp định nghĩa mục đích chuyên mục',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm tạo',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Danh mục tin tức phân loại nội dung';


-- Bảng user actions: like, bookmark, rating
CREATE TABLE news_user_actions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    news_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    is_liked TINYINT DEFAULT 0,
    is_bookmarked TINYINT DEFAULT 0,
    rating FLOAT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY (news_id, user_id),
    FOREIGN KEY (news_id) REFERENCES news(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Bảng bình luận
CREATE TABLE news_comments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    news_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    likes INT DEFAULT 0,
    parent_id BIGINT UNSIGNED DEFAULT NULL,
    FOREIGN KEY (news_id) REFERENCES news(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (parent_id) REFERENCES news_comments(id)
);

CREATE TABLE `media_files` (
  `id` bigint UNSIGNED NOT NULL COMMENT 'Khóa chính của file ảnh/media',
  `filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên file gốc (ví dụ: khachsan1.jpg)',
  `filepath` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Đường dẫn file (ví dụ: /storage/media/khachsan1.jpg)',
  `alt_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Thuộc tính ALT – giúp SEO hình ảnh',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tiêu đề ảnh hiển thị khi hover',
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Loại file (ví dụ: image/jpeg, image/webp...)',
  `size` int DEFAULT NULL COMMENT 'Dung lượng file tính bằng byte',
  `used_in` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Ngữ cảnh sử dụng (ví dụ: news, banner, home)',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời điểm upload',
    -- Bảng media_files: Quản lý file media (ảnh đại diện, ảnh nội dung...) hỗ trợ SEO hình ảnh
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời điểm cập nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Quản lý file media (ảnh đại diện, ảnh nội dung...) hỗ trợ SEO hình ảnh';



