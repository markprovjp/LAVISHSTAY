-- ===== MEDIA DATA =====
-- Dữ liệu cơ bản cho hệ thống media

-- Thêm các loại media được hỗ trợ
INSERT INTO media_types (type_name, allowed_extensions, max_file_size_mb, description) VALUES 
('image', '["jpg","jpeg","png","gif","webp"]', 10, 'Ảnh các loại: JPEG, PNG, GIF, WebP'),
('video', '["mp4","avi","mov","wmv","flv"]', 100, 'Video các loại: MP4, AVI, MOV, WMV, FLV'),
('document', '["pdf","doc","docx","txt"]', 5, 'Tài liệu: PDF, Word, Text');

-- Thêm các danh mục media
INSERT INTO media_categories (category_name, category_code, description, is_public, max_files_per_entity) VALUES 

-- MEDIA KHÁCH SẠN TỔNG QUAN
('Ảnh tổng quan khách sạn', 'hotel_general', 'Ảnh bên ngoài, lobby, kiến trúc tổng thể của khách sạn', TRUE, NULL),
('Video quảng cáo khách sạn', 'hotel_video', 'Video giới thiệu, quảng cáo khách sạn', TRUE, 10),
('Ảnh logo và thương hiệu', 'hotel_branding', 'Logo, ảnh thương hiệu, certificate', TRUE, 20),

-- MEDIA CÁC TẦNG VÀ TIỆN ÍCH
('Ảnh tầng và hành lang', 'floors', 'Ảnh các tầng, hành lang, khu vực chung', TRUE, 20),
('Ảnh nhà hàng và bar', 'restaurant_bar', 'Ảnh nhà hàng Orchid, SkyView Bar, Lotus Restaurant', TRUE, 30),
('Ảnh spa và gym', 'spa_fitness', 'Ảnh spa YHI, phòng gym, hồ bơi', TRUE, 30),
('Ảnh hội nghị và sự kiện', 'meeting_events', 'Ảnh Ballroom, phòng họp, sảnh sự kiện', TRUE, 25),
('Ảnh The Level Lounge', 'level_lounge', 'Ảnh Panoramic Lounge VIP tầng 33', TRUE, 15),

-- MEDIA LOẠI PHÒNG
('Ảnh phòng theo loại', 'room_types', 'Ảnh đại diện cho từng loại phòng (Deluxe, Premium, Suite, etc.)', TRUE, 50),
('Video tour phòng', 'room_tour_videos', 'Video tour 360° hoặc walkthrough các loại phòng', TRUE, 5),

-- MEDIA PHÒNG CỤ THỂ (nếu cần)
('Ảnh phòng cụ thể', 'individual_rooms', 'Ảnh của từng phòng cụ thể (khi có đặc biệt)', FALSE, 20),

-- MEDIA DỊCH VỤ
('Ảnh dịch vụ', 'services', 'Ảnh minh họa cho các dịch vụ (massage, room service, etc.)', TRUE, 10),

-- MEDIA NGƯỜI DÙNG
('Avatar người dùng', 'user_avatars', 'Ảnh đại diện của users (khách hàng, nhân viên)', TRUE, 1),
('Ảnh profile nhân viên', 'staff_profiles', 'Ảnh profile chi tiết của nhân viên', FALSE, 5),

-- MEDIA ĐÁNH GIÁ VÀ FEEDBACK
('Ảnh đánh giá của khách', 'customer_reviews', 'Ảnh khách gửi kèm đánh giá sau khi ở', TRUE, 10),
('Ảnh feedback dịch vụ', 'service_feedback', 'Ảnh feedback về dịch vụ cụ thể', TRUE, 5),

-- MEDIA QUẢNG CÁO VÀ MARKETING
('Ảnh banner quảng cáo', 'marketing_banners', 'Banner quảng cáo cho website, social media', TRUE, NULL),
('Ảnh seasonal/events', 'seasonal_events', 'Ảnh theo mùa, sự kiện đặc biệt', TRUE, NULL),
('Ảnh social media', 'social_media', 'Ảnh cho Facebook, Instagram, etc.', TRUE, NULL),

-- MEDIA TÀI LIỆU
('Tài liệu chính sách', 'policy_documents', 'PDF các chính sách, quy định của khách sạn', FALSE, NULL),
('Menu nhà hàng', 'restaurant_menus', 'PDF/ảnh menu các nhà hàng', TRUE, 20),
('Brochure và catalog', 'brochures', 'Brochure giới thiệu, catalog dịch vụ', TRUE, 20);

-- ===== SAMPLE MEDIA DATA =====
-- Một số ảnh mẫu để test (giả sử đã có file trong storage)

-- Ảnh tổng quan khách sạn
-- Ảnh Deluxe Room
INSERT INTO media (
    original_filename, file_path, file_url,
    media_type_id, category_id,
    entity_type, entity_id,
    title, alt_text, description, tags,
    sort_order, is_primary, is_featured,
    status, is_public, uploaded_by, upload_source
) VALUES
('1.jpg', '/images/room/Deluxe_Room/1.jpg', '/api/media/room_deluxe_001.jpg',
 1, 9, 'room_types', 1,
 'Deluxe Room - Phòng Deluxe', 'Phòng Deluxe với giường đôi và view thành phố',
 'Phòng Deluxe rộng rãi với giường đôi lớn, view thành phố và đầy đủ tiện nghi hiện đại', NULL,
 1, TRUE, TRUE, 'active', TRUE, 1, 'admin'),

-- Ảnh Premium Corner
('premium_corner_main.jpg', '/storage/media/rooms/room_premium_001.jpg', '/api/media/room_premium_001.jpg',
 1, 9, 'room_types', 2,
 'Premium Corner - Phòng góc', 'Phòng Premium Corner với view panorama',
 'Phòng Premium Corner 42m² với thiết kế góc độc đáo và tầm nhìn panorama ra thành phố', NULL,
 1, TRUE, TRUE, 'active', TRUE, 1, 'admin'),

-- Ảnh nhà hàng
('orchid_restaurant.jpg', '/storage/media/facilities/restaurant_orchid_001.jpg', '/api/media/restaurant_orchid_001.jpg',
 1, 6, 'floors', 6,
 'Nhà hàng Orchid', 'Nhà hàng Orchid buffet Á-Âu tầng 6',
 'Nhà hàng Orchid tầng 6 với không gian rộng rãi, phục vụ buffet Á-Âu cho 260 khách', NULL,
 1, TRUE, TRUE, 'active', TRUE, 1, 'admin'),

-- Ảnh spa
('spa_yhi_main.jpg', '/storage/media/facilities/spa_yhi_001.jpg', '/api/media/spa_yhi_001.jpg',
 1, 7, 'floors', 7,
 'Spa YHI', 'Spa YHI với không gian thư giãn sang trọng',
 'Spa YHI tầng 7 với các liệu trình massage và chăm sóc sắc đẹp chuyên nghiệp', NULL,
 1, TRUE, TRUE, 'active', TRUE, 1, 'admin');

-- ===== SAMPLE THUMBNAILS =====
-- Tạo thumbnails mẫu cho các ảnh trên
INSERT INTO media_thumbnails (media_id, size_name, width, height, stored_filename, file_path, file_url, file_size_bytes) VALUES 
-- Thumbnails cho ảnh khách sạn chính
(1, 'thumb_small', 300, 169, 'hotel_20241224_001_thumb_small.jpg', '/storage/media/hotel/thumbs/hotel_20241224_001_thumb_small.jpg', '/api/media/thumbs/hotel_20241224_001_thumb_small.jpg', 25600),
(1, 'thumb_medium', 600, 338, 'hotel_20241224_001_thumb_medium.jpg', '/storage/media/hotel/thumbs/hotel_20241224_001_thumb_medium.jpg', '/api/media/thumbs/hotel_20241224_001_thumb_medium.jpg', 81920),
(1, 'thumb_large', 1200, 675, 'hotel_20241224_001_thumb_large.jpg', '/storage/media/hotel/thumbs/hotel_20241224_001_thumb_large.jpg', '/api/media/thumbs/hotel_20241224_001_thumb_large.jpg', 204800),

-- Thumbnails cho ảnh lobby
(2, 'thumb_small', 300, 169, 'hotel_20241224_002_thumb_small.jpg', '/storage/media/hotel/thumbs/hotel_20241224_002_thumb_small.jpg', '/api/media/thumbs/hotel_20241224_002_thumb_small.jpg', 22400),
(2, 'thumb_medium', 600, 338, 'hotel_20241224_002_thumb_medium.jpg', '/storage/media/hotel/thumbs/hotel_20241224_002_thumb_medium.jpg', '/api/media/thumbs/hotel_20241224_002_thumb_medium.jpg', 71680),
(2, 'thumb_large', 1200, 675, 'hotel_20241224_002_thumb_large.jpg', '/storage/media/hotel/thumbs/hotel_20241224_002_thumb_large.jpg', '/api/media/thumbs/hotel_20241224_002_thumb_large.jpg', 179200),

-- Thumbnails cho Deluxe Room
(3, 'thumb_small', 300, 200, 'room_deluxe_001_thumb_small.jpg', '/storage/media/rooms/thumbs/room_deluxe_001_thumb_small.jpg', '/api/media/thumbs/room_deluxe_001_thumb_small.jpg', 28800),
(3, 'thumb_medium', 600, 400, 'room_deluxe_001_thumb_medium.jpg', '/storage/media/rooms/thumbs/room_deluxe_001_thumb_medium.jpg', '/api/media/thumbs/room_deluxe_001_thumb_medium.jpg', 92160),
(3, 'thumb_large', 1200, 800, 'room_deluxe_001_thumb_large.jpg', '/storage/media/rooms/thumbs/room_deluxe_001_thumb_large.jpg', '/api/media/thumbs/room_deluxe_001_thumb_large.jpg', 230400);
