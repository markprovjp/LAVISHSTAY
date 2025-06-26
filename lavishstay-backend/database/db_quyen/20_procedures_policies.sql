-- ===== BUSINESS POLICIES PROCEDURES & TRIGGERS =====
-- Các stored procedure và trigger cho logic nghiệp vụ thực tế

DELIMITER //

-- Function: Tính toán mức đặt cọc động theo điều kiện
CREATE FUNCTION fn_calculate_dynamic_deposit(
    p_booking_value BIGINT,
    p_check_in_date DATE,
    p_room_type_id INT,
    p_nights INT
) RETURNS DECIMAL(5,2)
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_deposit_percentage DECIMAL(5,2) DEFAULT 30.00;
    DECLARE v_event_multiplier DECIMAL(5,2) DEFAULT 1.00;
    DECLARE v_season_multiplier DECIMAL(5,2) DEFAULT 1.00;
    DECLARE v_room_multiplier DECIMAL(5,2) DEFAULT 1.00;
    DECLARE v_booking_multiplier DECIMAL(5,2) DEFAULT 1.00;
    DECLARE v_final_percentage DECIMAL(5,2);
    
    -- Validation đầu vào
    IF p_booking_value <= 0 OR p_check_in_date < CURDATE() OR p_room_type_id <= 0 OR p_nights <= 0 THEN
        RETURN 30.00; -- Default deposit percentage
    END IF;
    
    -- Kiểm tra sự kiện đặc biệt
    SELECT COALESCE(MAX(ep.price_multiplier), 1.00) INTO v_event_multiplier
    FROM event_pricing ep
    WHERE ep.is_active = TRUE 
    AND p_check_in_date BETWEEN ep.start_date AND ep.end_date;
    
    -- Kiểm tra mùa giá
    SELECT COALESCE(MAX(ps.price_multiplier), 1.00) INTO v_season_multiplier
    FROM pricing_seasons ps
    WHERE ps.is_active = TRUE
    AND p_check_in_date BETWEEN ps.start_date AND ps.end_date;
    
    -- Áp dụng policy đặt cọc theo sự kiện (ưu tiên cao nhất)
    IF v_event_multiplier > 1.00 THEN
        SELECT COALESCE(MAX(JSON_UNQUOTE(JSON_EXTRACT(p.policy_config, '$.event_deposit_percentage'))), 50.00) INTO v_deposit_percentage
        FROM policies p
        WHERE p.is_active = TRUE 
        AND p.auto_apply = TRUE
        AND p.policy_type = 'deposit'
        AND JSON_EXTRACT(p.policy_config, '$.policy_subtype') = 'event_based'
        AND (p.min_booking_value IS NULL OR p_booking_value >= p.min_booking_value)
        AND (p.min_stay_nights IS NULL OR p_nights >= p.min_stay_nights)
        AND (p.applicable_room_types IS NULL OR JSON_CONTAINS(p.applicable_room_types, CONCAT('"', p_room_type_id, '"')));
    
    -- Áp dụng policy đặt cọc theo mùa (ưu tiên trung bình)
    ELSEIF v_season_multiplier > 1.00 THEN
        SELECT COALESCE(MAX(JSON_UNQUOTE(JSON_EXTRACT(p.policy_config, '$.season_deposit_percentage'))), 40.00) INTO v_deposit_percentage
        FROM policies p
        WHERE p.is_active = TRUE 
        AND p.auto_apply = TRUE
        AND p.policy_type = 'deposit'
        AND JSON_EXTRACT(p.policy_config, '$.policy_subtype') = 'season_based'
        AND (JSON_EXTRACT(p.policy_config, '$.trigger_seasons') IS NULL OR JSON_CONTAINS(JSON_EXTRACT(p.policy_config, '$.trigger_seasons'), 
                CONCAT('"', MONTH(p_check_in_date), '"')));
    
    -- Áp dụng policy đặt cọc theo loại phòng (ưu tiên thấp)
    ELSE
        SELECT COALESCE(MAX(JSON_UNQUOTE(JSON_EXTRACT(p.policy_config, '$.base_deposit_percentage'))), 30.00) INTO v_deposit_percentage
        FROM policies p
        WHERE p.is_active = TRUE 
        AND p.auto_apply = TRUE
        AND p.policy_type = 'deposit'
        AND JSON_EXTRACT(p.policy_config, '$.policy_subtype') = 'room_type_based'
        AND (p.applicable_room_types IS NULL OR JSON_CONTAINS(p.applicable_room_types, CONCAT('"', p_room_type_id, '"')));
    END IF;
    
    -- Kiểm tra policy đặt cọc theo giá trị booking (có thể override)
    SELECT COALESCE(MAX(JSON_UNQUOTE(JSON_EXTRACT(p.policy_config, '$.booking_value_deposit_percentage'))), v_deposit_percentage) INTO v_final_percentage
    FROM policies p
    WHERE p.is_active = TRUE 
    AND p.auto_apply = TRUE
    AND p.policy_type = 'deposit'
    AND JSON_EXTRACT(p.policy_config, '$.policy_subtype') = 'booking_value_based'
    AND p.min_booking_value IS NOT NULL
    AND p_booking_value >= p.min_booking_value;
    
    -- Kiểm tra policy đặt cọc theo số đêm lưu trú (có thể tăng cho long stay)
    IF p_nights >= 7 THEN
        SELECT COALESCE(MAX(JSON_UNQUOTE(JSON_EXTRACT(p.policy_config, '$.long_stay_deposit_percentage'))), v_final_percentage) INTO v_final_percentage
        FROM policies p
        WHERE p.is_active = TRUE 
        AND p.auto_apply = TRUE
        AND p.policy_type = 'deposit'
        AND JSON_EXTRACT(p.policy_config, '$.policy_subtype') = 'long_stay_based'
        AND p.min_stay_nights IS NOT NULL
        AND p_nights >= p.min_stay_nights;
    END IF;
    
    -- Đảm bảo không vượt quá 100%
    SET v_final_percentage = LEAST(v_final_percentage, 100.00);
    
    RETURN v_final_percentage;
END //

-- Function: Kiểm tra cooldown cho guest
CREATE FUNCTION fn_check_guest_cooldown(
    p_guest_identifier VARCHAR(100),
    p_guest_id_number VARCHAR(50),
    p_requested_room_id INT
) RETURNS JSON
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_result JSON DEFAULT JSON_OBJECT('allowed', true, 'cooldown_end', null, 'message', '');
    DECLARE v_cooldown_end DATE;
    DECLARE v_cooldown_type VARCHAR(50);
    DECLARE v_last_room_id INT;
    DECLARE v_loyalty_level VARCHAR(20);
    DECLARE v_is_active BOOLEAN DEFAULT FALSE;
    
    -- Validation đầu vào
    IF p_guest_identifier IS NULL OR TRIM(p_guest_identifier) = '' THEN
        SET v_result = JSON_OBJECT('allowed', false, 'message', 'Thông tin guest không hợp lệ');
        RETURN v_result;
    END IF;
    
    -- Lấy thông tin cooldown hiện tại
    SELECT 
        cooldown_end_date, cooldown_type, last_room_id, 
        loyalty_level, is_cooldown_active
    INTO 
        v_cooldown_end, v_cooldown_type, v_last_room_id,
        v_loyalty_level, v_is_active
    FROM guest_cooldown_tracking
    WHERE guest_identifier = p_guest_identifier 
    AND guest_id_number = p_guest_id_number
    LIMIT 1;
    
    -- Nếu không có record thì cho phép
    IF v_cooldown_end IS NULL THEN
        RETURN v_result;
    END IF;
    
    -- Nếu cooldown đã hết thì cho phép
    IF CURDATE() >= v_cooldown_end OR v_is_active = FALSE THEN
        RETURN v_result;
    END IF;
    
    -- Kiểm tra miễn cooldown cho loyalty member
    IF v_loyalty_level IN ('gold', 'platinum', 'diamond') THEN
        RETURN v_result;
    END IF;
    
    -- Kiểm tra cooldown còn hiệu lực
    IF v_is_active = TRUE AND CURDATE() < v_cooldown_end THEN
        SET v_result = JSON_OBJECT(
            'allowed', false,
            'cooldown_end', v_cooldown_end,
            'cooldown_type', v_cooldown_type,
            'message', CONCAT('Cần chờ đến ', v_cooldown_end, ' để có thể đặt phòng lại.')
        );
        
        -- Thêm thông tin đặc biệt nếu cùng phòng
        IF p_requested_room_id = v_last_room_id THEN
            SET v_result = JSON_SET(v_result, '$.message', 
                CONCAT(JSON_UNQUOTE(JSON_EXTRACT(v_result, '$.message')), 
                ' (Cùng phòng cũ có thời gian chờ lâu hơn)')
            );
        END IF;
    END IF;
    
    RETURN v_result;
END //

-- Function: Tính phí early checkout
CREATE FUNCTION fn_calculate_early_checkout_fee(
    p_booking_id INT,
    p_early_checkout_date DATE
) RETURNS JSON
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_result JSON DEFAULT JSON_OBJECT('fee', 0, 'refund', 0, 'message', '');
    DECLARE v_total_nights INT;
    DECLARE v_actual_nights INT;
    DECLARE v_room_total BIGINT;
    DECLARE v_check_in DATE;
    DECLARE v_check_out DATE;
    DECLARE v_stay_percentage DECIMAL(5,2);
    DECLARE v_fee_amount BIGINT DEFAULT 0;
    DECLARE v_refund_amount BIGINT DEFAULT 0;
    DECLARE v_policy_min_percentage DECIMAL(5,2) DEFAULT 60.00;
    DECLARE v_policy_fee_percentage DECIMAL(5,2) DEFAULT 20.00;
    DECLARE v_policy_min_fee BIGINT DEFAULT 500000;
    
    -- Validation đầu vào
    IF p_booking_id IS NULL OR p_booking_id <= 0 OR p_early_checkout_date IS NULL THEN
        SET v_result = JSON_OBJECT('fee', 0, 'refund', 0, 'message', 'Thông tin không hợp lệ');
        RETURN v_result;
    END IF;
    
    -- Lấy thông tin booking với validation
    SELECT 
        check_in_date, check_out_date, total_nights, room_subtotal
    INTO 
        v_check_in, v_check_out, v_total_nights, v_room_total
    FROM bookings 
    WHERE booking_id = p_booking_id;
    
    IF v_check_in IS NULL THEN
        SET v_result = JSON_OBJECT('fee', 0, 'refund', 0, 'message', 'Booking không tồn tại');
        RETURN v_result;
    END IF;
    
    -- Validation logic nghiệp vụ
    IF p_early_checkout_date < v_check_in THEN
        SET v_result = JSON_OBJECT('fee', 0, 'refund', 0, 'message', 'Ngày checkout không thể trước ngày checkin');
        RETURN v_result;
    END IF;
    
    IF p_early_checkout_date >= v_check_out THEN
        SET v_result = JSON_OBJECT('fee', 0, 'refund', 0, 'message', 'Không phải early checkout');
        RETURN v_result;
    END IF;
    
    -- Tính số đêm thực tế đã ở
    SET v_actual_nights = DATEDIFF(p_early_checkout_date, v_check_in);
    SET v_stay_percentage = (v_actual_nights / v_total_nights) * 100;
    
    -- Lấy policy early checkout
    SELECT 
        CAST(JSON_UNQUOTE(JSON_EXTRACT(p.policy_config, '$.min_stay_percentage')) AS DECIMAL(5,2)), 
        CAST(JSON_UNQUOTE(JSON_EXTRACT(p.policy_config, '$.early_checkout_fee_percentage')) AS DECIMAL(5,2)), 
        CAST(JSON_UNQUOTE(JSON_EXTRACT(p.policy_config, '$.early_checkout_min_fee')) AS UNSIGNED)
    INTO 
        v_policy_min_percentage, v_policy_fee_percentage, v_policy_min_fee
    FROM policies p
    WHERE p.policy_type = 'checkout' 
    AND JSON_EXTRACT(p.policy_config, '$.checkout_type') = 'early_checkout' 
    AND p.is_active = TRUE
    LIMIT 1;
    
    -- Kiểm tra điều kiện cho phép early checkout với refund
    IF v_stay_percentage >= v_policy_min_percentage THEN
        -- Được refund một phần, có phí
        SET v_refund_amount = v_room_total * (v_total_nights - v_actual_nights) / v_total_nights;
        SET v_fee_amount = GREATEST(v_refund_amount * v_policy_fee_percentage / 100, v_policy_min_fee);
        SET v_refund_amount = v_refund_amount - v_fee_amount;
        
        SET v_result = JSON_OBJECT(
            'fee', v_fee_amount,
            'refund', v_refund_amount,
            'stay_percentage', v_stay_percentage,
            'message', CONCAT('Đã ở ', v_stay_percentage, '% thời gian. Hoàn tiền: ', 
                            FORMAT(v_refund_amount, 0), ' VND, Phí: ', FORMAT(v_fee_amount, 0), ' VND')
        );
    ELSE
        -- Không được refund
        SET v_result = JSON_OBJECT(
            'fee', 0,
            'refund', 0,
            'stay_percentage', v_stay_percentage,
            'message', CONCAT('Chỉ ở ', v_stay_percentage, '% thời gian (tối thiểu ', 
                            v_policy_min_percentage, '%). Không được hoàn tiền.')
        );
    END IF;
    
    RETURN v_result;
END //

-- Function: Tính phí late checkout
CREATE FUNCTION fn_calculate_late_checkout_fee(
    p_booking_id INT,
    p_late_checkout_time TIME
) RETURNS JSON
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_result JSON DEFAULT JSON_OBJECT('fee', 0, 'hours', 0, 'message', '');
    DECLARE v_standard_checkout TIME DEFAULT '12:00:00';
    DECLARE v_grace_hours INT DEFAULT 2;
    DECLARE v_hourly_rate BIGINT DEFAULT 100000;
    DECLARE v_max_hours INT DEFAULT 6;
    DECLARE v_full_day_rate_percentage DECIMAL(5,2) DEFAULT 50.00;
    DECLARE v_late_hours INT;
    DECLARE v_billable_hours INT;
    DECLARE v_fee_amount BIGINT DEFAULT 0;
    DECLARE v_room_price_per_night BIGINT;
    
    -- Validation đầu vào
    IF p_booking_id IS NULL OR p_booking_id <= 0 OR p_late_checkout_time IS NULL THEN
        SET v_result = JSON_OBJECT('fee', 0, 'hours', 0, 'message', 'Thông tin không hợp lệ');
        RETURN v_result;
    END IF;
    
    -- Lấy giá phòng mỗi đêm với validation
    SELECT room_price_per_night INTO v_room_price_per_night
    FROM bookings WHERE booking_id = p_booking_id;
    
    IF v_room_price_per_night IS NULL THEN
        SET v_result = JSON_OBJECT('fee', 0, 'hours', 0, 'message', 'Booking không tồn tại');
        RETURN v_result;
    END IF;
    
    -- Lấy policy late checkout
    SELECT 
        CAST(JSON_UNQUOTE(JSON_EXTRACT(p.policy_config, '$.grace_hours')) AS UNSIGNED), 
        CAST(JSON_UNQUOTE(JSON_EXTRACT(p.policy_config, '$.hourly_rate')) AS UNSIGNED), 
        CAST(JSON_UNQUOTE(JSON_EXTRACT(p.policy_config, '$.max_hours')) AS UNSIGNED), 
        CAST(JSON_UNQUOTE(JSON_EXTRACT(p.policy_config, '$.full_day_rate_percentage')) AS DECIMAL(5,2))
    INTO 
        v_grace_hours, v_hourly_rate, v_max_hours, v_full_day_rate_percentage
    FROM policies p
    WHERE p.policy_type = 'checkout' 
    AND JSON_EXTRACT(p.policy_config, '$.checkout_type') = 'late_checkout' 
    AND p.is_active = TRUE
    LIMIT 1;
    
    -- Tính số giờ muộn
    SET v_late_hours = TIMESTAMPDIFF(HOUR, v_standard_checkout, p_late_checkout_time);
    
    IF v_late_hours <= 0 THEN
        SET v_result = JSON_OBJECT('fee', 0, 'hours', 0, 'message', 'Checkout đúng giờ');
        
    ELSEIF v_late_hours <= v_grace_hours THEN
        SET v_result = JSON_OBJECT('fee', 0, 'hours', v_late_hours, 
                                 'message', CONCAT('Trong thời gian gia hạn miễn phí (', v_grace_hours, 'h)'));
        
    ELSEIF v_late_hours > v_max_hours THEN
        -- Tính phí cả ngày
        SET v_fee_amount = v_room_price_per_night * v_full_day_rate_percentage / 100;
        SET v_result = JSON_OBJECT('fee', v_fee_amount, 'hours', v_late_hours, 
                                 'message', CONCAT('Quá ', v_max_hours, 'h, tính phí cả ngày: ', 
                                                FORMAT(v_fee_amount, 0), ' VND'));
    ELSE
        -- Tính phí theo giờ
        SET v_billable_hours = v_late_hours - v_grace_hours;
        SET v_fee_amount = v_billable_hours * v_hourly_rate;
        SET v_result = JSON_OBJECT('fee', v_fee_amount, 'hours', v_late_hours, 
                                 'message', CONCAT('Phí ', v_billable_hours, 'h x ', 
                                                FORMAT(v_hourly_rate, 0), ' = ', 
                                                FORMAT(v_fee_amount, 0), ' VND'));
    END IF;
    
    RETURN v_result;
END //

-- Procedure: Xử lý early checkout
CREATE PROCEDURE sp_process_early_checkout(
    IN p_booking_id INT,
    IN p_early_checkout_date DATE,
    IN p_processed_by INT,
    IN p_reason TEXT,
    OUT p_result JSON
)
proc_early_checkout: BEGIN
    DECLARE v_fee_calculation JSON;
    DECLARE v_fee_amount BIGINT;
    DECLARE v_refund_amount BIGINT;
    DECLARE v_booking_status VARCHAR(20);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET p_result = JSON_OBJECT('success', false, 'message', 'Lỗi xử lý early checkout');
    END;
    
    -- Validation đầu vào
    IF p_booking_id IS NULL OR p_booking_id <= 0 OR p_early_checkout_date IS NULL THEN
        SET p_result = JSON_OBJECT('success', false, 'message', 'Tham số đầu vào không hợp lệ');
        LEAVE proc_early_checkout;
    END IF;
    
    START TRANSACTION;
    
    -- Kiểm tra trạng thái booking
    SELECT booking_status INTO v_booking_status FROM bookings WHERE booking_id = p_booking_id;
    
    IF v_booking_status != 'checked_in' THEN
        SET p_result = JSON_OBJECT('success', false, 'message', 'Booking chưa checkin hoặc đã checkout');
        ROLLBACK;
        LEAVE proc_early_checkout;
    END IF;
    
    -- Tính phí early checkout
    SET v_fee_calculation = fn_calculate_early_checkout_fee(p_booking_id, p_early_checkout_date);
    SET v_fee_amount = JSON_UNQUOTE(JSON_EXTRACT(v_fee_calculation, '$.fee'));
    SET v_refund_amount = JSON_UNQUOTE(JSON_EXTRACT(v_fee_calculation, '$.refund'));
    
    -- Cập nhật booking
    UPDATE bookings 
    SET 
        check_out_date = p_early_checkout_date,
        booking_status = 'checked_out',
        updated_at = NOW()
    WHERE booking_id = p_booking_id;
    
    -- Log thay đổi
    INSERT INTO booking_changes_log (
        booking_id, change_type, old_values, new_values, change_reason,
        financial_impact_amount, fee_applied, refund_amount,
        processed_by, change_source, notes
    ) VALUES (
        p_booking_id, 'early_checkout',
        JSON_OBJECT('checkout_date', (SELECT check_out_date FROM bookings WHERE booking_id = p_booking_id)),
        JSON_OBJECT('checkout_date', p_early_checkout_date),
        p_reason,
        -(v_refund_amount - v_fee_amount), v_fee_amount, v_refund_amount,
        p_processed_by, 'guest_request', 
        JSON_UNQUOTE(JSON_EXTRACT(v_fee_calculation, '$.message'))
    );
    
    -- Cập nhật guest cooldown tracking
    CALL sp_update_guest_cooldown(p_booking_id, 'early_checkout');
    
    COMMIT;
    
    SET p_result = JSON_OBJECT(
        'success', true,
        'fee_amount', v_fee_amount,
        'refund_amount', v_refund_amount,
        'calculation_details', v_fee_calculation
    );
    
END //

-- Procedure: Xử lý late checkout
CREATE PROCEDURE sp_process_late_checkout(
    IN p_booking_id INT,
    IN p_late_checkout_time TIME,
    IN p_processed_by INT,
    IN p_reason TEXT,
    OUT p_result JSON
)
proc_late_checkout: BEGIN
    DECLARE v_fee_calculation JSON;
    DECLARE v_fee_amount BIGINT;
    
    DECLARE v_booking_status VARCHAR(20);
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SET p_result = JSON_OBJECT('success', false, 'message', 'Lỗi xử lý late checkout');
    END;
    
    START TRANSACTION;
    
    -- Kiểm tra trạng thái booking
    SELECT booking_status INTO v_booking_status FROM bookings WHERE booking_id = p_booking_id;
    
    IF v_booking_status != 'checked_in' THEN
        SET p_result = JSON_OBJECT('success', false, 'message', 'Booking chưa checkin');
        ROLLBACK;
        LEAVE proc_late_checkout;
    END IF;
    
    -- Tính phí late checkout
    SET v_fee_calculation = fn_calculate_late_checkout_fee(p_booking_id, p_late_checkout_time);
    SET v_fee_amount = JSON_UNQUOTE(JSON_EXTRACT(v_fee_calculation, '$.fee'));
    
    -- Cập nhật booking
    UPDATE bookings 
    SET 
        booking_status = 'checked_out',
        updated_at = NOW()
    WHERE booking_id = p_booking_id;
    
    -- Log thay đổi
    INSERT INTO booking_changes_log (
        booking_id, change_type, old_values, new_values, change_reason,
        financial_impact_amount, fee_applied, additional_charge,
        processed_by, change_source, notes
    ) VALUES (
        p_booking_id, 'late_checkout',
        JSON_OBJECT('checkout_time', '12:00:00'),
        JSON_OBJECT('checkout_time', p_late_checkout_time),
        p_reason,
        v_fee_amount, v_fee_amount, v_fee_amount,
        p_processed_by, 'guest_request',
        JSON_UNQUOTE(JSON_EXTRACT(v_fee_calculation, '$.message'))
    );
    
    COMMIT;
    
    SET p_result = JSON_OBJECT(
        'success', true,
        'fee_amount', v_fee_amount,
        'calculation_details', v_fee_calculation
    );
    
END //

-- Procedure: Cập nhật guest cooldown tracking
-- Fixed sp_update_guest_cooldown procedure
CREATE PROCEDURE sp_update_guest_cooldown(
    IN p_booking_id INT,
    IN p_cooldown_type VARCHAR(50)
)
BEGIN
    DECLARE v_guest_identifier VARCHAR(100);
    DECLARE v_guest_id_number VARCHAR(50);
    DECLARE v_room_id INT;
    DECLARE v_checkout_date DATE;
    DECLARE v_cooldown_days INT DEFAULT 0;
    DECLARE v_cooldown_hours INT DEFAULT 0;
    DECLARE v_cooldown_end_date DATE;
    
    -- Validation đầu vào
    IF p_booking_id IS NULL OR p_booking_id <= 0 OR p_cooldown_type IS NULL OR TRIM(p_cooldown_type) = '' THEN
        RETURN;
    END IF;
    
    -- Lấy thông tin booking với validation
    SELECT guest_email, guest_id_number, room_id, check_out_date
    INTO v_guest_identifier, v_guest_id_number, v_room_id, v_checkout_date
    FROM bookings WHERE booking_id = p_booking_id;
    
    IF v_guest_identifier IS NULL THEN
        RETURN;
    END IF;
    
    -- Lấy policy cooldown
    SELECT 
        CAST(JSON_UNQUOTE(JSON_EXTRACT(p.policy_config, '$.cooldown_days')) AS UNSIGNED), 
        CAST(JSON_UNQUOTE(JSON_EXTRACT(p.policy_config, '$.cooldown_hours')) AS UNSIGNED)
    INTO v_cooldown_days, v_cooldown_hours
    FROM policies p
    WHERE p.policy_type = 'cooldown' 
    AND JSON_EXTRACT(p.policy_config, '$.trigger_event') = p_cooldown_type 
    AND p.is_active = TRUE
    LIMIT 1;
    
    -- Tính ngày kết thúc cooldown
    SET v_cooldown_end_date = DATE_ADD(DATE_ADD(v_checkout_date, INTERVAL v_cooldown_days DAY), 
                                      INTERVAL v_cooldown_hours HOUR);
    
    -- Cập nhật hoặc tạo record tracking
    INSERT INTO guest_cooldown_tracking (
        guest_identifier, guest_id_number, last_checkout_date, last_booking_id,
        last_room_id, cooldown_end_date, cooldown_type, total_stays,
        is_cooldown_active
    ) VALUES (
        v_guest_identifier, v_guest_id_number, v_checkout_date, p_booking_id,
        v_room_id, v_cooldown_end_date, p_cooldown_type, 1, TRUE
    )
    ON DUPLICATE KEY UPDATE
        last_checkout_date = v_checkout_date,
        last_booking_id = p_booking_id,
        last_room_id = v_room_id,
        cooldown_end_date = v_cooldown_end_date,
        cooldown_type = p_cooldown_type,
        total_stays = total_stays + 1,
        is_cooldown_active = TRUE,
        updated_at = NOW();
        
END //

-- Trigger: Tự động áp dụng chính sách đặt cọc khi tạo booking
CREATE TRIGGER tr_auto_apply_deposit_policy
    BEFORE INSERT ON bookings
    FOR EACH ROW
BEGIN
    DECLARE v_deposit_percentage DECIMAL(5,2);
    DECLARE v_required_deposit BIGINT;
    DECLARE v_room_type_id INT;
    DECLARE v_policy_name VARCHAR(100) DEFAULT 'Chính sách cơ bản';
    
    -- Validation đầu vào
    IF NEW.room_id IS NULL OR NEW.check_in_date IS NULL OR NEW.final_total <= 0 THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Thông tin booking không hợp lệ';
    END IF;
    
    -- Lấy room_type_id từ room_id
    SELECT room_type_id INTO v_room_type_id 
    FROM rooms WHERE room_id = NEW.room_id;
    
    IF v_room_type_id IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Phòng không tồn tại';
    END IF;
    
    -- Tính mức đặt cọc động
    SET v_deposit_percentage = fn_calculate_dynamic_deposit(
        NEW.final_total, NEW.check_in_date, v_room_type_id, NEW.total_nights
    );
    
    -- Tính số tiền đặt cọc yêu cầu
    SET v_required_deposit = NEW.final_total * v_deposit_percentage / 100;
    
    -- Áp dụng minimum deposit amount
    IF v_required_deposit < 500000 THEN
        SET v_required_deposit = 500000;
    END IF;
    
    -- Cập nhật thông tin deposit
    SET NEW.deposit_percentage = v_deposit_percentage;
    SET NEW.required_deposit_amount = v_required_deposit;
    SET NEW.applied_deposit_policy = v_policy_name;
    SET NEW.deposit_deadline = DATE_ADD(NOW(), INTERVAL 24 HOUR);
    
    -- Cập nhật deposit_amount nếu chưa có
    IF NEW.deposit_amount = 0 THEN
        SET NEW.deposit_amount = v_required_deposit;
    END IF;
    
END //

-- Trigger: Log các thay đổi booking status
CREATE TRIGGER tr_log_booking_status_change
    AFTER UPDATE ON bookings
    FOR EACH ROW
BEGIN
    IF OLD.booking_status != NEW.booking_status THEN
        INSERT INTO booking_changes_log (
            booking_id, change_type, old_values, new_values, change_reason,
            processed_by, change_source, notes
        ) VALUES (
            NEW.booking_id, 'status_change',
            JSON_OBJECT('status', OLD.booking_status),
            JSON_OBJECT('status', NEW.booking_status),
            'Automatic status change',
            1, 'system_automated',
            CONCAT('Status changed from ', OLD.booking_status, ' to ', NEW.booking_status)
        );
        
        -- Cập nhật cooldown tracking khi checkout
        IF NEW.booking_status = 'checked_out' AND OLD.booking_status = 'checked_in' THEN
            CALL sp_update_guest_cooldown(NEW.booking_id, 'checkout');
        END IF;
        
        -- Cập nhật cooldown tracking khi cancel
        IF NEW.booking_status = 'cancelled' THEN
            CALL sp_update_guest_cooldown(NEW.booking_id, 'cancellation');
        END IF;
        
        -- Cập nhật cooldown tracking khi no-show
        IF NEW.booking_status = 'no_show' THEN
            CALL sp_update_guest_cooldown(NEW.booking_id, 'no_show');
        END IF;
    END IF;
END //

DELIMITER ;

-- Tạo các event để tự động xử lý
-- Event: Tự động cập nhật cooldown status hết hạn
CREATE EVENT IF NOT EXISTS ev_update_expired_cooldowns
ON SCHEDULE EVERY 1 HOUR
DO
UPDATE guest_cooldown_tracking 
SET is_cooldown_active = FALSE 
WHERE is_cooldown_active = TRUE 
AND cooldown_end_date <= CURDATE();

-- Event: Tự động xử lý no-show bookings
CREATE EVENT IF NOT EXISTS ev_process_no_show_bookings
ON SCHEDULE EVERY 2 HOUR
DO
UPDATE bookings 
SET booking_status = 'no_show',
    no_show_processed = TRUE,
    no_show_fee = final_total
WHERE booking_status = 'confirmed'
AND check_in_date < CURDATE()
AND (no_show_deadline IS NULL OR no_show_deadline < NOW())
AND no_show_processed = FALSE;
