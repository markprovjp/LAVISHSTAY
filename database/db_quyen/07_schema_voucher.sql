-- ===== VOUCHER & LOYALTY SCHEMA =====
-- Hệ thống voucher, gói ưu đãi, loyalty program và dịch vụ bổ sung

-- Bảng gói ưu đãi/voucher
CREATE TABLE voucher_packages (
    package_id INT PRIMARY KEY AUTO_INCREMENT,
    package_name VARCHAR(100) NOT NULL, -- Tên gói
    package_type ENUM('discount', 'service_promo', 'member_special', 'compensation') NOT NULL, -- Loại voucher
    -- Giải thích các loại voucher:
    -- discount: Giảm giá trực tiếp cho dịch vụ hoặc phòng
    -- service_promo: Gói ưu đãi dịch vụ (miễn phí, giảm giá)
    -- member_special: Ưu đãi đặc biệt cho thành viên (thành viên Diamond, Platinum)
    -- compensation: Voucher đền bù cho sự cố dịch vụ
    -- Các loại voucher này sẽ được sử dụng để khuyến mãi, tri ân khách hàng hoặc đền bù sự cố dịch vụ.
    description TEXT, -- Mô tả gói
    price_per_person DECIMAL(10,2), -- Giá/người
    validity_start DATE, -- Ngày bắt đầu hiệu lực
    validity_end DATE, -- Ngày hết hiệu lực
    min_nights INT DEFAULT 1, -- Số đêm tối thiểu
    max_usage_per_customer INT DEFAULT 1, -- Số lần sử dụng tối đa/khách
    is_active BOOLEAN DEFAULT TRUE, -- Trạng thái hoạt động
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng chi tiết gói ưu đãi (các dịch vụ trong gói)
CREATE TABLE package_services (
    package_service_id INT PRIMARY KEY AUTO_INCREMENT,
    package_id INT NOT NULL, -- Mã gói
    service_type ENUM('accommodation', 'breakfast', 'meal', 'spa', 'discount', 'free_service') NOT NULL, -- Loại dịch vụ
    -- Giải thích các loại dịch vụ:
    -- accommodation: Dịch vụ lưu trú (phòng)
    -- breakfast: Bữa sáng (buffet, set menu)
    -- meal: Bữa ăn khác (trưa, tối)
    -- spa: Dịch vụ spa (giảm giá, miễn phí)
    -- discount: Giảm giá cho dịch vụ cụ thể (spa, ăn uống)
    -- free_service: Dịch vụ miễn phí (trẻ em, nâng cấp phòng)
    service_description VARCHAR(200) NOT NULL, -- Mô tả dịch vụ
    quantity INT DEFAULT 1, -- Số lượng
    discount_percent DECIMAL(5,2) DEFAULT 0, -- % giảm giá (nếu có)
    is_free BOOLEAN DEFAULT FALSE, -- Miễn phí hay không
    notes TEXT, -- Ghi chú đặc biệt
    FOREIGN KEY (package_id) REFERENCES voucher_packages(package_id)
);

-- Bảng voucher cá nhân (voucher được cấp cho khách hàng)
CREATE TABLE customer_vouchers (
    voucher_id INT PRIMARY KEY AUTO_INCREMENT,
    voucher_code VARCHAR(20) NOT NULL UNIQUE, -- Mã voucher
    user_id INT NOT NULL, -- Khách hàng sở hữu
    package_id INT NOT NULL, -- Gói ưu đãi
    issue_date DATE NOT NULL, -- Ngày cấp
    expiry_date DATE NOT NULL, -- Ngày hết hạn
    status ENUM('active', 'used', 'expired', 'cancelled') DEFAULT 'active', -- Trạng thái
    -- Giải thích các trạng thái:
    -- active: Voucher còn hiệu lực, có thể sử dụng
    -- used: Voucher đã được sử dụng
    -- expired: Voucher đã hết hạn
    -- cancelled: Voucher bị hủy (có thể do khách hàng hoặc quản lý)
    used_date DATE NULL, -- Ngày sử dụng
    booking_id INT NULL, -- Booking sử dụng voucher (nếu có)
    notes TEXT, -- Ghi chú
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (package_id) REFERENCES voucher_packages(package_id),
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id)
);

CREATE TABLE member_levels (
    level_id INT PRIMARY KEY AUTO_INCREMENT,
    level_code ENUM('member', 'gold', 'platinum', 'diamond') NOT NULL UNIQUE,
        -- giải thích các mức ưu tiên:
    -- member: Khách hàng thành viên cơ bản (miễn phí)
    -- gold: Khách hàng Gold (10-50 triệu/5-25 đêm)
    -- platinum: Khách hàng Platinum (50-100 triệu/25-50 đêm)
    -- diamond: Khách hàng Diamond (>100 triệu/>50 đêm)
    level_name VARCHAR(50),
    min_revenue DECIMAL(15,2),
    min_nights INT,
    benefit_description TEXT
);


-- Bảng chương trình thành viên LavishStayRewards
CREATE TABLE loyalty_program (
    member_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã thành viên',
    user_id INT NOT NULL UNIQUE COMMENT 'Khách hàng',
    membership_number VARCHAR(20) NOT NULL UNIQUE COMMENT 'Số thành viên',
    level_id INT NOT NULL COMMENT 'Tham chiếu đến cấp độ trong bảng member_levels',
    
    -- Theo dõi doanh thu theo năm cho thuật toán nâng hạng
    current_year_revenue DECIMAL(15,2) DEFAULT 0 COMMENT 'Doanh thu năm hiện tại',
    previous_year_revenue DECIMAL(15,2) DEFAULT 0 COMMENT 'Doanh thu năm trước',
    current_year_nights INT DEFAULT 0 COMMENT 'Số đêm lưu trú năm hiện tại',
    previous_year_nights INT DEFAULT 0 COMMENT 'Số đêm lưu trú năm trước',
    last_tier_review_date DATE COMMENT 'Lần cuối xét hạng (31/12 hàng năm)',
    tier_activation_date DATE COMMENT 'Ngày kích hoạt thẻ',
    
    -- LPoint tracking (chỉ Gold+ mới tích điểm)
    total_points INT DEFAULT 0 COMMENT 'Tổng LPoint tích lũy (vô thời hạn)',
    available_points INT DEFAULT 0 COMMENT 'LPoint khả dụng',
    
    -- Legacy tracking (giữ để báo cáo)
    total_nights INT DEFAULT 0 COMMENT 'Tổng số đêm lưu trú (tất cả thời gian)',
    total_spent DECIMAL(15,2) DEFAULT 0 COMMENT 'Tổng chi tiêu (tất cả thời gian)',
    
    join_date DATE NOT NULL COMMENT 'Ngày gia nhập',
    last_activity_date DATE COMMENT 'Ngày hoạt động cuối',
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active' COMMENT 'Trạng thái thành viên',
    -- Giải thích các trạng thái:
    -- active: Thành viên đang hoạt động, có thể tích lũy điểm
    -- inactive: Thành viên không hoạt động trong thời gian dài (có thể do không đặt phòng trong 6 tháng)
    -- suspended: Thành viên bị tạm ngưng (có thể do vi phạm quy định, gian lận)
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (level_id) REFERENCES member_levels(level_id)
);

-- Bảng lịch sử điểm thưởng
CREATE TABLE points_history (
    history_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã lịch sử điểm',
    member_id INT NOT NULL COMMENT 'Thành viên',
    booking_id INT NULL COMMENT 'Booking liên quan (nếu có)',
    transaction_type ENUM('earn', 'redeem', 'expire', 'bonus', 'adjustment' , 'birthday' , 'welcome') NOT NULL COMMENT 'Loại giao dịch',
    -- Giải thích các loại giao dịch:
    -- earn: Tích lũy điểm từ booking hoặc hoạt động khác
    -- redeem: Sử dụng điểm để đổi voucher hoặc dịch vụ
    -- expire: Điểm hết hạn (theo quy định)
    -- bonus: Điểm thưởng từ chương trình khuyến mãi, sinh nhật, chào mừng
    -- adjustment: Điều chỉnh điểm (có thể do lỗi hệ thống, hoặc nhân viên điều chỉnh)
    points_change INT NOT NULL COMMENT 'Số điểm thay đổi (+/-)',
    description VARCHAR(200) COMMENT 'Mô tả giao dịch',
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    expiry_date DATE NULL COMMENT 'Ngày hết hạn điểm (nếu có)',
    FOREIGN KEY (member_id) REFERENCES loyalty_program(member_id),
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id)
);

-- Bảng phí dịch vụ khác
CREATE TABLE additional_fees (
    fee_id INT PRIMARY KEY AUTO_INCREMENT,
    fee_name VARCHAR(100) NOT NULL COMMENT 'Tên phí',
    fee_type ENUM('change_guest_info', 'airport_transfer', 'deposit', 'cancellation', 'other') NOT NULL COMMENT 'Loại phí',
    -- Giải thích các loại phí:
    -- change_guest_info: Phí thay đổi thông tin khách (trong vòng 30 ngày từ ngày nhận phòng)
    -- airport_transfer: Phí dịch vụ đưa đón sân bay
    -- deposit: Phí đặt cọc phòng (theo loại phòng)
    -- cancellation: Phí hủy phòng (theo chính sách hủy) 
    -- other: Các loại phí khác (nếu có)
    amount DECIMAL(10,2) NOT NULL COMMENT 'Số tiền',
    description TEXT COMMENT 'Mô tả',
    applicable_room_types TEXT COMMENT 'Loại phòng áp dụng (JSON)',
    effective_from DATE COMMENT 'Ngày bắt đầu áp dụng',
    effective_to DATE COMMENT 'Ngày kết thúc áp dụng',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng dịch vụ đưa đón sân bay
CREATE TABLE airport_transfers (
    transfer_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã dịch vụ đưa đón',
    booking_id INT NOT NULL COMMENT 'Khóa chính, mã đặt phòng liên quan',
    transfer_type ENUM('pickup', 'dropoff', 'both') NOT NULL COMMENT 'Loại đưa đón',
    -- Giải thích các loại đưa đón:
    -- pickup: Chỉ đón khách từ sân bay
    -- dropoff: Chỉ trả khách tại sân bay
    -- both: Cả hai (đón và trả khách)
    flight_number VARCHAR(20) COMMENT 'Số hiệu chuyến bay',
    flight_date DATE NOT NULL COMMENT 'Ngày bay',
    flight_time TIME COMMENT 'Giờ bay',
    pickup_location VARCHAR(200) COMMENT 'Địa điểm đón',
    dropoff_location VARCHAR(200) COMMENT 'Địa điểm trả',
    passenger_count INT DEFAULT 1 COMMENT 'Số hành khách',
    vehicle_type VARCHAR(50) COMMENT 'Loại xe',
    fee DECIMAL(10,2) DEFAULT 0 COMMENT 'Phí dịch vụ',
    status ENUM('requested', 'confirmed', 'cancelled', 'completed') DEFAULT 'requested' COMMENT 'Trạng thái dịch vụ',
    -- Giải thích các trạng thái:
    -- requested: Đã yêu cầu dịch vụ, chờ xác nhận
    -- confirmed: Dịch vụ đã được xác nhận, chờ thực hiện
    -- cancelled: Dịch vụ bị hủy (có thể do khách hàng hoặc quản lý)
    -- completed: Dịch vụ đã hoàn thành
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    notes TEXT,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id)
);

-- ===== BẢNG VOUCHER ĐẶC BIỆT VÀ COMBO MÙA HÈ =====

-- Bảng voucher nghỉ dưỡng đặc biệt 
CREATE TABLE special_vacation_vouchers (
    voucher_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã voucher',
    voucher_code VARCHAR(50) NOT NULL UNIQUE COMMENT 'Mã voucher',
    voucher_name VARCHAR(200) NOT NULL COMMENT 'Tên voucher',
    voucher_type ENUM('tri_an_2n1d', 'he_ruc_ro_2n1d', 'vui_he_2n1d', 'he_bat_tan_3n2d') COMMENT 'Loại voucher',
    
    -- Thông tin giá
    price_per_person BIGINT NOT NULL COMMENT 'Giá trên người (VND): Tri ân 800k, Hè rực rỡ 900k',
    original_value BIGINT COMMENT 'Giá trị gốc trước ưu đãi',
    savings_amount BIGINT COMMENT 'Số tiền tiết kiệm được',
    
    -- Điều kiện áp dụng
    min_guests INT DEFAULT 1 COMMENT 'Số khách tối thiểu',
    max_guests INT DEFAULT 4 COMMENT 'Số khách tối đa',
    applicable_days VARCHAR(50) COMMENT 'Ngày áp dụng: all, weekends_only, weekdays_only',
    blackout_dates TEXT COMMENT 'Ngày không áp dụng (JSON array)',
    
    -- Bao gồm dịch vụ
    includes_accommodation BOOLEAN DEFAULT TRUE COMMENT 'Bao gồm lưu trú',
    nights_included INT DEFAULT 1 COMMENT 'Số đêm bao gồm: 01 đêm phòng Deluxe',
    room_type_included VARCHAR(100) DEFAULT 'Deluxe' COMMENT 'Loại phòng bao gồm',
    includes_breakfast BOOLEAN DEFAULT TRUE COMMENT 'Bao gồm ăn sáng',
    breakfast_type VARCHAR(100) DEFAULT 'Buffet Á-Âu' COMMENT 'Loại ăn sáng',
    includes_lunch BOOLEAN DEFAULT FALSE COMMENT 'Bao gồm ăn trưa (Hè rực rỡ có)',
    includes_dinner BOOLEAN DEFAULT FALSE COMMENT 'Bao gồm ăn tối (Hè rực rỡ có)',
    
    -- Ưu đãi trẻ em
    child_discount_percentage DECIMAL(5,2) DEFAULT 50.00 COMMENT 'Giảm 50% phí phụ thu trẻ em',
    free_children_age_limit INT DEFAULT 12 COMMENT 'Trẻ em miễn phí dưới 12 tuổi ngủ cùng bố mẹ',
    
    -- Thời gian hiệu lực
    valid_from DATE NOT NULL COMMENT 'Có hiệu lực từ',
    valid_to DATE NOT NULL COMMENT 'Có hiệu lực đến',
    
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Trạng thái hoạt động',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT 'Bảng voucher nghỉ dưỡng đặc biệt: Tri ân 800k, Hè rực rỡ 900k';



-- ===== CHƯƠNG TRÌNH MELIAREWARDS =====

-- Bảng cấp độ thành viên (4 cấp độ: Member, Gold, Platinum, Diamond)
CREATE TABLE member_tiers (
    tier_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã cấp độ',
    tier_name VARCHAR(50) NOT NULL UNIQUE COMMENT 'Member, Gold, Platinum, Diamond',
    tier_color VARCHAR(20) COMMENT 'Màu đại diện',
    min_points_required INT DEFAULT 0 COMMENT 'Điểm tối thiểu để đạt cấp độ',
    min_nights_required INT DEFAULT 0 COMMENT 'Số đêm tối thiểu để đạt cấp độ',
    min_spending_required BIGINT DEFAULT 0 COMMENT 'Chi tiêu tối thiểu để đạt cấp độ (VND)',
    
    -- Quyền lợi chung
    points_earning_rate DECIMAL(4,2) DEFAULT 10.00 COMMENT 'Tỷ lệ tích điểm: 10 điểm/đô la chi tiêu',
    max_rooms_for_points INT DEFAULT 2 COMMENT 'Tích điểm cho tối đa 2 phòng cùng lúc',
    
    -- Quyền lợi đặc biệt
    welcome_points INT DEFAULT 2000 COMMENT 'Điểm chào mừng 2.000 điểm khi đăng ký',
    birthday_surprise BOOLEAN DEFAULT TRUE COMMENT 'Sinh nhật surprise hàng năm',
    online_checkin BOOLEAN DEFAULT TRUE COMMENT 'Check-in trực tuyến trước khi đến',
    room_upgrade_priority INT DEFAULT 0 COMMENT 'Độ ưu tiên nâng hạng phòng',
    late_checkout_hours INT DEFAULT 0 COMMENT 'Số giờ check-out muộn miễn phí',
    
    description TEXT COMMENT 'Mô tả quyền lợi cấp độ',
    is_active BOOLEAN DEFAULT TRUE COMMENT 'Trạng thái hoạt động',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) COMMENT 'Bảng cấp độ thành viên LavishStayRewards với 14 triệu thành viên';



-- ==== TRIGGERS FOR LOYALTY PROGRAM ====

-- Trigger tự động tính điểm khi booking completed
DELIMITER //
CREATE TRIGGER tr_calculate_loyalty_points
    AFTER UPDATE ON bookings
    FOR EACH ROW
BEGIN
    -- Chỉ xử lý khi booking chuyển sang checked_out
    IF NEW.booking_status = 'checked_out' AND OLD.booking_status != 'checked_out' THEN
        -- Gọi procedure để cập nhật doanh thu và xử lý LPoint
        CALL sp_update_member_revenue(NEW.user_id, NEW.final_total, NEW.total_nights);
    END IF;
END//
DELIMITER ;

-- Trigger tự động cập nhật trạng thái voucher khi booking sử dụng voucher
DELIMITER //
CREATE TRIGGER tr_update_voucher_usage
    AFTER UPDATE ON bookings
    FOR EACH ROW
BEGIN
    -- Nếu có voucher_code và booking completed
    IF NEW.booking_status = 'completed' AND NEW.voucher_code IS NOT NULL THEN
        UPDATE customer_vouchers 
        SET 
            status = 'used',
            used_date = CURDATE(),
            booking_id = NEW.booking_id
        WHERE voucher_code = NEW.voucher_code AND status = 'active';
    END IF;
END//
DELIMITER ;

-- ==== STORED PROCEDURES ====

-- Procedure kiểm tra và nâng cấp membership level theo thuật toán LavishStayClub
DELIMITER //
CREATE PROCEDURE sp_check_membership_upgrade(IN p_member_id INT)
BEGIN
    DECLARE current_level VARCHAR(20);
    DECLARE current_year_revenue_val DECIMAL(15,2);
    DECLARE previous_year_revenue_val DECIMAL(15,2);
    DECLARE current_year_nights_val INT;
    DECLARE current_year INT;
    DECLARE calculated_revenue DECIMAL(15,2);
    DECLARE new_level VARCHAR(20);
    
    SET current_year = YEAR(CURDATE());
    
    -- Lấy thông tin hiện tại
    SELECT member_level, current_year_revenue, previous_year_revenue, current_year_nights
    INTO current_level, current_year_revenue_val, previous_year_revenue_val, current_year_nights_val
    FROM loyalty_program 
    WHERE member_id = p_member_id;
    
    -- Tính doanh thu theo công thức: Năm N + 30% Năm (N-1)
    SET calculated_revenue = current_year_revenue_val + (previous_year_revenue_val * 0.3);
    
    -- Xác định level mới dựa trên doanh thu tính toán hoặc số đêm
    SET new_level = 'member'; -- Default
    
    IF calculated_revenue >= 100000000 OR current_year_nights_val >= 50 THEN
        SET new_level = 'diamond';
    ELSEIF calculated_revenue >= 50000000 OR current_year_nights_val >= 25 THEN
        SET new_level = 'platinum';
    ELSEIF calculated_revenue >= 10000000 OR current_year_nights_val >= 5 THEN
        SET new_level = 'gold';
    END IF;
    
    -- Cập nhật nếu có thay đổi level
    IF new_level != current_level THEN
        UPDATE loyalty_program 
        SET 
            member_level = new_level,
            last_tier_review_date = CURDATE()
        WHERE member_id = p_member_id;
        
        -- Log thay đổi level
        INSERT INTO points_history (
            member_id, transaction_type, points_change, description
        ) VALUES (
            p_member_id, 'adjustment', 0,
            CONCAT('Tier review: ', current_level, ' → ', new_level, 
                   '. Revenue: ', calculated_revenue, ', Nights: ', current_year_nights_val)
        );
    END IF;
END//
DELIMITER ;

-- Procedure tạo voucher tự động cho thành viên Diamond
DELIMITER //
CREATE PROCEDURE sp_generate_diamond_vouchers()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE user_id_val INT;
    DECLARE member_level_val VARCHAR(20);
    DECLARE package_id_val INT;
    DECLARE voucher_code_val VARCHAR(20);
    
    DECLARE cur CURSOR FOR 
        SELECT user_id, member_level 
        FROM loyalty_program 
        WHERE member_level IN ('platinum', 'diamond') 
            AND status = 'active'
            AND last_activity_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH);
    
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    -- Tìm package VIP
    SELECT package_id INTO package_id_val
    FROM voucher_packages 
    WHERE package_type = 'member_special' AND is_active = TRUE
    LIMIT 1;
    
    IF package_id_val IS NOT NULL THEN
        OPEN cur;
        read_loop: LOOP
            FETCH cur INTO user_id_val, member_level_val;
            IF done THEN
                LEAVE read_loop;
            END IF;
            
            -- Tạo voucher code unique
            SET voucher_code_val = CONCAT('DIAMOND', YEAR(CURDATE()), LPAD(user_id_val, 4, '0'));
            
            -- Chỉ tạo nếu chưa có voucher active
            IF NOT EXISTS (
                SELECT 1 FROM customer_vouchers 
                WHERE user_id = user_id_val AND package_id = package_id_val AND status = 'active'
            ) THEN
                INSERT INTO customer_vouchers (
                    voucher_code, user_id, package_id, issue_date, expiry_date, status
                ) VALUES (
                    voucher_code_val, user_id_val, package_id_val, 
                    CURDATE(), DATE_ADD(CURDATE(), INTERVAL 6 MONTH), 'active'
                );
            END IF;
        END LOOP;
        CLOSE cur;
    END IF;
END//
DELIMITER ;

-- Procedure xử lý cuối năm (31/12) - Reset và xét hạng
DELIMITER //
CREATE PROCEDURE sp_annual_tier_review()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE member_id_val INT;
    DECLARE current_year INT;
    
    -- Cursor để duyệt tất cả member active
    DECLARE member_cursor CURSOR FOR 
        SELECT member_id FROM loyalty_program WHERE status = 'active';
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    SET current_year = YEAR(CURDATE());
    
    -- Chuyển dữ liệu năm hiện tại thành năm trước
    UPDATE loyalty_program 
    SET 
        previous_year_revenue = current_year_revenue,
        previous_year_nights = current_year_nights,
        current_year_revenue = 0,
        current_year_nights = 0,
        last_tier_review_date = CURDATE()
    WHERE status = 'active';
    
    -- Mở cursor và xét hạng từng member
    OPEN member_cursor;
    
    review_loop: LOOP
        FETCH member_cursor INTO member_id_val;
        IF done THEN
            LEAVE review_loop;
        END IF;
        
        -- Gọi procedure xét hạng cho từng member
        CALL sp_check_membership_upgrade(member_id_val);
    END LOOP;
    
    CLOSE member_cursor;
    
    -- Log hoạt động cuối năm
    INSERT INTO points_history (member_id, transaction_type, points_change, description)
    SELECT member_id, 'adjustment', 0, CONCAT('Annual review completed for year ', current_year - 1)
    FROM loyalty_program WHERE status = 'active';
END//
DELIMITER ;

-- Procedure cập nhật doanh thu khi booking completed
DELIMITER //
CREATE PROCEDURE sp_update_member_revenue(IN p_user_id INT, IN p_revenue DECIMAL(15,2), IN p_nights INT)
BEGIN
    DECLARE member_id_val INT;
    DECLARE current_level VARCHAR(20);
    DECLARE points_to_earn INT DEFAULT 0;
    
    -- Tìm member_id
    SELECT member_id, member_level INTO member_id_val, current_level
    FROM loyalty_program 
    WHERE user_id = p_user_id AND status = 'active'
    LIMIT 1;
    
    IF member_id_val IS NOT NULL THEN
        -- Cập nhật doanh thu và số đêm năm hiện tại
        UPDATE loyalty_program 
        SET 
            current_year_revenue = current_year_revenue + p_revenue,
            current_year_nights = current_year_nights + p_nights,
            total_spent = total_spent + p_revenue,
            total_nights = total_nights + p_nights,
            last_activity_date = CURDATE()
        WHERE member_id = member_id_val;
        
        -- Tính LPoint cho Gold+ (Member không tích điểm)
        IF current_level = 'gold' THEN
            SET points_to_earn = FLOOR(p_revenue * 0.01); -- 1%
        ELSEIF current_level = 'platinum' THEN
            SET points_to_earn = FLOOR(p_revenue * 0.015); -- 1.5%
        ELSEIF current_level = 'diamond' THEN
            SET points_to_earn = FLOOR(p_revenue * 0.02); -- 2%
        END IF;
        
        -- Cập nhật LPoint nếu có
        IF points_to_earn > 0 THEN
            UPDATE loyalty_program 
            SET 
                total_points = total_points + points_to_earn,
                available_points = available_points + points_to_earn
            WHERE member_id = member_id_val;
            
            -- Log tích LPoint
            INSERT INTO points_history (
                member_id, transaction_type, points_change, description
            ) VALUES (
                member_id_val, 'earn', points_to_earn,
                CONCAT('LPoint từ booking: ', p_revenue, ' VND (rate: ', 
                       CASE current_level 
                           WHEN 'gold' THEN '1%'
                           WHEN 'platinum' THEN '1.5%'
                           WHEN 'diamond' THEN '2%'
                       END, ')')
            );
        END IF;
        
        -- Kiểm tra nâng hạng ngay lập tức nếu đủ điều kiện
        CALL sp_check_membership_upgrade(member_id_val);
    END IF;
END//
DELIMITER ;
