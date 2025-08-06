-- ===== MEDIA SCHEMA =====
-- Hệ thống quản lý media (ảnh, video) tổng quả cho toàn bộ ứng dụng

-- Bảng loại media
CREATE TABLE media_types (
    media_type_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã loại media',
    type_name VARCHAR(50) NOT NULL UNIQUE COMMENT 'Tên loại media: image, video, document',
    allowed_extensions JSON COMMENT 'Các extension được phép: ["jpg","jpeg","png","gif"] hoặc ["mp4","avi"]',
    max_file_size_mb INT DEFAULT 10 COMMENT 'Kích thước file tối đa (MB)',
    description TEXT COMMENT 'Mô tả loại media',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối'
) COMMENT 'Bảng phân loại các loại media được hỗ trợ';

-- Bảng danh mục media (để phân loại media theo mục đích sử dụng)
CREATE TABLE media_categories (
    category_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã danh mục',
    category_name VARCHAR(100) NOT NULL UNIQUE COMMENT 'Tên danh mục',
    category_code VARCHAR(50) NOT NULL UNIQUE COMMENT 'Mã danh mục: hotel_general, room_types, floors, services, user_avatars, reviews, etc.',
    description TEXT COMMENT 'Mô tả mục đích sử dụng danh mục này',
    is_public BOOLEAN DEFAULT TRUE COMMENT 'Danh mục này có được public không (cho FE hiển thị)',
    max_files_per_entity INT DEFAULT NULL COMMENT 'Số lượng file tối đa cho mỗi entity (NULL = không giới hạn)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối'
) COMMENT 'Bảng phân danh mục media theo mục đích sử dụng';

-- Bảng media chính
CREATE TABLE media (
    media_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã media',
    
    -- Thông tin file
    original_filename VARCHAR(255) NOT NULL COMMENT 'Tên file gốc do user upload',
    file_path VARCHAR(500) NOT NULL COMMENT 'Đường dẫn đầy đủ đến file',
    file_url VARCHAR(500) COMMENT 'URL public để truy cập file (có thể qua CDN)',
    
    -- Phân loại
    media_type_id INT NOT NULL COMMENT 'Loại media (image/video/document)',
    category_id INT NOT NULL COMMENT 'Danh mục media',
    
  
    
    -- Liên kết với entity
    entity_type VARCHAR(50) NOT NULL COMMENT 'Loại entity: rooms, room_types, floors, users, reviews, hotel_general, services, etc.',
    entity_id INT COMMENT 'ID của entity (có thể NULL cho media chung của khách sạn)',
    
    -- Metadata và SEO
    title VARCHAR(255) COMMENT 'Tiêu đề media (cho SEO và hiển thị)',
    alt_text VARCHAR(255) COMMENT 'Alt text cho ảnh (accessibility và SEO)',
    description TEXT COMMENT 'Mô tả chi tiết media',
    tags JSON COMMENT 'Các tag/nhãn cho media: ["luxury", "view", "bathroom"]',
    
    -- Sắp xếp và hiển thị
    sort_order INT DEFAULT 0 COMMENT 'Thứ tự hiển thị (số nhỏ hơn = ưu tiên cao hơn)',
    is_primary BOOLEAN DEFAULT FALSE COMMENT 'Có phải ảnh chính/đại diện không',
    is_featured BOOLEAN DEFAULT FALSE COMMENT 'Có phải ảnh nổi bật không (cho banner, showcase)',
    
    -- Trạng thái
    status ENUM('active', 'inactive', 'processing', 'error') DEFAULT 'active' COMMENT 'Trạng thái media',
    is_public BOOLEAN DEFAULT TRUE COMMENT 'Có được hiển thị công khai không',
    
    -- Thông tin upload
    uploaded_by INT COMMENT 'User ID người upload',
    upload_source VARCHAR(50) DEFAULT 'admin' COMMENT 'Nguồn upload: admin, api, customer_review, etc.',
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối',
    
    -- Foreign Keys
    FOREIGN KEY (media_type_id) REFERENCES media_types(media_type_id),
    FOREIGN KEY (category_id) REFERENCES media_categories(category_id),
    FOREIGN KEY (uploaded_by) REFERENCES users(user_id) ON DELETE SET NULL,
    
    -- Indexes
    INDEX idx_entity (entity_type, entity_id),
    INDEX idx_category (category_id),
    INDEX idx_status_public (status, is_public),
    INDEX idx_primary_featured (is_primary, is_featured),
    INDEX idx_sort_order (sort_order),
    INDEX idx_upload_date (created_at)
) COMMENT 'Bảng quản lý tất cả media (ảnh, video, tài liệu) trong hệ thống';

-- Bảng thumbnails (ảnh thumb tự động tạo)
CREATE TABLE media_thumbnails (
    thumbnail_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã thumbnail',
    media_id INT NOT NULL COMMENT 'Media gốc',
    
    -- Thông tin thumbnail
    size_name VARCHAR(50) NOT NULL COMMENT 'Tên kích thước: thumb_small, thumb_medium, thumb_large',
    width INT NOT NULL COMMENT 'Chiều rộng thumbnail',
    height INT NOT NULL COMMENT 'Chiều cao thumbnail',
    
    -- File info
    stored_filename VARCHAR(255) NOT NULL COMMENT 'Tên file thumbnail',
    file_path VARCHAR(500) NOT NULL COMMENT 'Đường dẫn file thumbnail',
    file_url VARCHAR(500) COMMENT 'URL public thumbnail',
    file_size_bytes INT NOT NULL COMMENT 'Kích thước file thumbnail',
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo thumbnail',
    
    FOREIGN KEY (media_id) REFERENCES media(media_id) ON DELETE CASCADE,
    UNIQUE KEY unique_media_size (media_id, size_name),
    INDEX idx_media_size (media_id, size_name)
) COMMENT 'Bảng lưu trữ các thumbnail tự động tạo từ ảnh gốc';

-- Bảng metadata mở rộng (cho các thông tin đặc biệt)
CREATE TABLE media_metadata (
    metadata_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính',
    media_id INT NOT NULL COMMENT 'Media ID',
    
    -- Metadata key-value
    meta_key VARCHAR(100) NOT NULL COMMENT 'Tên metadata: exif_camera, gps_lat, gps_lng, color_palette, etc.',
    meta_value TEXT COMMENT 'Giá trị metadata (có thể là JSON)',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    
    FOREIGN KEY (media_id) REFERENCES media(media_id) ON DELETE CASCADE,
    UNIQUE KEY unique_media_key (media_id, meta_key),
    INDEX idx_meta_key (meta_key)
) COMMENT 'Bảng lưu trữ metadata mở rộng cho media (EXIF, GPS, màu sắc, etc.)';

-- View để lấy media kèm thông tin đầy đủ
CREATE VIEW v_media_complete AS
SELECT 
    m.*,
    mt.type_name as media_type_name,
    mt.allowed_extensions,
    mc.category_name,
    mc.category_code,
    mc.is_public as category_is_public,
    u.full_name as uploaded_by_name,
    u.email as uploaded_by_email,
    
    -- Đếm số lượng thumbnails
    (SELECT COUNT(*) FROM media_thumbnails mt2 WHERE mt2.media_id = m.media_id) as thumbnail_count,
    
    -- Lấy URL thumbnail chính (medium nếu có, không thì small)
    COALESCE(
        (SELECT file_url FROM media_thumbnails mt3 WHERE mt3.media_id = m.media_id AND mt3.size_name = 'thumb_medium' LIMIT 1),
        (SELECT file_url FROM media_thumbnails mt4 WHERE mt4.media_id = m.media_id AND mt4.size_name = 'thumb_small' LIMIT 1),
        m.file_url
    ) as thumbnail_url
    
FROM media m
LEFT JOIN media_types mt ON m.media_type_id = mt.media_type_id
LEFT JOIN media_categories mc ON m.category_id = mc.category_id
LEFT JOIN users u ON m.uploaded_by = u.user_id;
