-- ===== DYNAMIC PRICING PROCEDURES =====
-- Các stored procedures và functions để tính giá động

-- Function tính giá cuối cùng cho 1 phòng trong 1 ngày cụ thể
DELIMITER //
CREATE FUNCTION fn_calculate_dynamic_price(
    p_room_type_id INT,
    p_check_in_date DATE,
    p_check_out_date DATE,
    p_guest_count INT,
    p_booking_type VARCHAR(20),
    p_contract_id INT
) RETURNS JSON
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_base_price BIGINT DEFAULT 0;
    DECLARE v_final_price BIGINT DEFAULT 0;
    DECLARE v_total_nights INT DEFAULT 0;
    DECLARE v_season_multiplier DECIMAL(4,2) DEFAULT 1.00;
    DECLARE v_weekend_surcharge BIGINT DEFAULT 0;
    DECLARE v_event_multiplier DECIMAL(4,2) DEFAULT 1.00;
    DECLARE v_occupancy_adjustment DECIMAL(4,2) DEFAULT 1.00;
    DECLARE v_contract_discount DECIMAL(4,2) DEFAULT 0.00;
    DECLARE v_long_stay_discount DECIMAL(4,2) DEFAULT 0.00;
    DECLARE v_final_multiplier DECIMAL(4,2) DEFAULT 1.00;
    DECLARE v_pricing_breakdown JSON;
    
    -- Set defaults for parameters
    IF p_booking_type IS NULL OR p_booking_type = '' THEN
        SET p_booking_type = 'individual';
    END IF;
    
    -- Validation đầu vào
    IF p_check_in_date >= p_check_out_date THEN
        RETURN JSON_OBJECT('error', 'Invalid date range', 'check_in', p_check_in_date, 'check_out', p_check_out_date);
    END IF;
    
    IF p_guest_count <= 0 OR p_guest_count > 10 THEN
        RETURN JSON_OBJECT('error', 'Invalid guest count', 'guest_count', p_guest_count);
    END IF;
    
    -- Tính số đêm
    SET v_total_nights = DATEDIFF(p_check_out_date, p_check_in_date);
    
    -- Lấy giá gốc với validation
    SELECT CASE WHEN p_guest_count = 1 THEN base_price_single ELSE base_price_double END
    INTO v_base_price
    FROM room_types 
    WHERE type_id = p_room_type_id;
    
    -- Kiểm tra room type tồn tại
    IF v_base_price IS NULL OR v_base_price = 0 THEN
        RETURN JSON_OBJECT('error', 'Invalid room type or price not found', 'room_type_id', p_room_type_id);
    END IF;
    
    -- Tính hệ số mùa giá (lấy mùa có priority cao nhất trong khoảng thời gian)
    SELECT COALESCE(MAX(price_multiplier), 1.00)
    INTO v_season_multiplier
    FROM pricing_seasons 
    WHERE is_active = TRUE 
    AND p_check_in_date >= start_date 
    AND p_check_in_date <= end_date
    ORDER BY priority DESC
    LIMIT 1;
    
    -- Tính phụ thu cuối tuần (tính trung bình cho toàn bộ kỳ nghỉ)
    SELECT COALESCE(AVG(
        CASE 
            WHEN DAYOFWEEK(check_date) IN (6,7) THEN (v_base_price * ws.surcharge_value / 100)  -- Fri, Sat
            WHEN DAYOFWEEK(check_date) = 1 THEN (v_base_price * ws.surcharge_value / 100)       -- Sunday
            ELSE 0 
        END
    ), 0)
    INTO v_weekend_surcharge
    FROM (
        SELECT DATE_ADD(p_check_in_date, INTERVAL seq.seq DAY) as check_date
        FROM (
            SELECT 0 as seq UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 
            UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7
            UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11
            UNION SELECT 12 UNION SELECT 13 UNION SELECT 14 UNION SELECT 15
            UNION SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19
            UNION SELECT 20 UNION SELECT 21 UNION SELECT 22 UNION SELECT 23
            UNION SELECT 24 UNION SELECT 25 UNION SELECT 26 UNION SELECT 27
            UNION SELECT 28 UNION SELECT 29 UNION SELECT 30
        ) seq
        WHERE DATE_ADD(p_check_in_date, INTERVAL seq.seq DAY) < p_check_out_date
    ) date_range
    CROSS JOIN weekend_surcharge ws
    WHERE ws.is_active = TRUE 
    AND (ws.applicable_room_types = 'ALL' OR JSON_CONTAINS(ws.applicable_room_types, CONCAT('"', (SELECT type_name FROM room_types WHERE type_id = p_room_type_id), '"')));
    
    -- Tính hệ số sự kiện
    SELECT COALESCE(MAX(price_multiplier), 1.00)
    INTO v_event_multiplier
    FROM event_pricing 
    WHERE is_active = TRUE 
    AND p_check_in_date >= start_date 
    AND p_check_in_date <= end_date
    AND auto_apply = TRUE;
    
    -- Tính điều chỉnh theo công suất (lấy trung bình)
    SELECT COALESCE(AVG(price_adjustment_factor), 1.00)
    INTO v_occupancy_adjustment
    FROM daily_occupancy 
    WHERE room_type_id = p_room_type_id 
    AND date_applicable BETWEEN p_check_in_date AND DATE_SUB(p_check_out_date, INTERVAL 1 DAY);
    
    -- Tính chiết khấu hợp đồng
    IF p_contract_id IS NOT NULL THEN
        SELECT CASE 
            WHEN discount_type = 'percentage' THEN discount_value
            ELSE 0 
        END
        INTO v_contract_discount
        FROM group_contracts 
        WHERE contract_id = p_contract_id 
        AND is_active = TRUE 
        AND CURDATE() BETWEEN start_date AND end_date;
    END IF;
    
    -- Tính chiết khấu lưu trú dài ngày
    IF p_booking_type = 'long_stay' OR v_total_nights >= 7 THEN
        SELECT COALESCE(MAX(discount_value), 0)
        INTO v_long_stay_discount
        FROM long_stay_pricing 
        WHERE is_active = TRUE
        AND v_total_nights >= min_nights 
        AND (max_nights IS NULL OR v_total_nights <= max_nights)
        AND (valid_from IS NULL OR CURDATE() >= valid_from)
        AND (valid_to IS NULL OR CURDATE() <= valid_to);
    END IF;
    
    -- Tính giá cuối cùng (sửa logic conflict giữa season và event)
    -- Chọn multiplier cao nhất thay vì cộng dồn để tránh giá quá cao
    SET v_final_multiplier = GREATEST(v_season_multiplier, v_event_multiplier);
    
    SET v_final_price = v_base_price * v_final_multiplier * v_occupancy_adjustment;
    SET v_final_price = v_final_price + v_weekend_surcharge;
    
    -- Áp dụng chiết khấu
    IF v_contract_discount > 0 THEN
        SET v_final_price = v_final_price * (1 - v_contract_discount / 100);
    END IF;
    
    IF v_long_stay_discount > 0 THEN
        SET v_final_price = v_final_price * (1 - v_long_stay_discount / 100);
    END IF;
    
    -- Tạo JSON breakdown
    SET v_pricing_breakdown = JSON_OBJECT(
        'base_price', v_base_price,
        'final_price_per_night', v_final_price,
        'total_nights', v_total_nights,
        'total_price', v_final_price * v_total_nights,
        'adjustments', JSON_OBJECT(
            'season_multiplier', v_season_multiplier,
            'weekend_surcharge_per_night', v_weekend_surcharge,
            'event_multiplier', v_event_multiplier,
            'occupancy_adjustment', v_occupancy_adjustment,
            'contract_discount_percent', v_contract_discount,
            'long_stay_discount_percent', v_long_stay_discount
        ),
        'calculated_at', NOW()
    );
    
    RETURN v_pricing_breakdown;
END//
DELIMITER ;

-- Procedure cập nhật công suất hàng ngày
DELIMITER //
CREATE PROCEDURE sp_update_daily_occupancy(
    IN p_date DATE
)
BEGIN
    DECLARE v_target_date DATE;
    
    -- Mặc định là hôm nay nếu không truyền ngày
    SET v_target_date = COALESCE(p_date, CURDATE());
    
    -- Cập nhật occupancy cho từng loại phòng
    INSERT INTO daily_occupancy (room_type_id, date_applicable, total_rooms, occupied_rooms, available_rooms, occupancy_rate, price_adjustment_factor)
    SELECT 
        rt.type_id,
        v_target_date,
        rt.total_rooms_planned,
        COALESCE(booked.occupied_count, 0),
        rt.total_rooms_planned - COALESCE(booked.occupied_count, 0),
        COALESCE(booked.occupied_count, 0) / rt.total_rooms_planned,
        CASE 
            WHEN COALESCE(booked.occupied_count, 0) / rt.total_rooms_planned >= 0.90 THEN 1.25
            WHEN COALESCE(booked.occupied_count, 0) / rt.total_rooms_planned >= 0.80 THEN 1.15
            WHEN COALESCE(booked.occupied_count, 0) / rt.total_rooms_planned >= 0.70 THEN 1.05
            WHEN COALESCE(booked.occupied_count, 0) / rt.total_rooms_planned >= 0.60 THEN 1.00
            WHEN COALESCE(booked.occupied_count, 0) / rt.total_rooms_planned >= 0.40 THEN 0.95
            ELSE 0.90
        END
    FROM room_types rt
    LEFT JOIN (
        SELECT 
            r.room_type_id,
            COUNT(*) as occupied_count
        FROM bookings b
        JOIN rooms r ON b.room_id = r.room_id
        WHERE v_target_date >= b.check_in_date 
        AND v_target_date < b.check_out_date
        AND b.booking_status IN ('confirmed', 'checked_in')
        GROUP BY r.room_type_id
    ) booked ON rt.type_id = booked.room_type_id
    ON DUPLICATE KEY UPDATE
        occupied_rooms = VALUES(occupied_rooms),
        available_rooms = VALUES(available_rooms),
        occupancy_rate = VALUES(occupancy_rate),
        price_adjustment_factor = VALUES(price_adjustment_factor),
        last_updated = CURRENT_TIMESTAMP;
END//
DELIMITER ;

-- Procedure tính giá và lưu lịch sử cho một khoảng thời gian
DELIMITER //
CREATE PROCEDURE sp_calculate_price_range(
    IN p_room_type_id INT,
    IN p_start_date DATE,
    IN p_end_date DATE
)
BEGIN
    DECLARE v_current_date DATE;
    DECLARE v_pricing_result JSON;
    
    SET v_current_date = p_start_date;
    
    WHILE v_current_date <= p_end_date DO
        -- Tính giá cho ngày hiện tại
        SET v_pricing_result = fn_calculate_dynamic_price(
            p_room_type_id, 
            v_current_date, 
            DATE_ADD(v_current_date, INTERVAL 1 DAY), 
            2, 
            'individual', 
            NULL
        );
        
        -- Lưu vào lịch sử giá
        INSERT INTO price_history (
            room_type_id, date_applicable, 
            base_price_single, base_price_double,
            final_price_single, final_price_double,
            season_multiplier, event_multiplier,
            occupancy_rate, pricing_rules_applied
        )
        SELECT 
            p_room_type_id,
            v_current_date,
            rt.base_price_single,
            rt.base_price_double,
            JSON_UNQUOTE(JSON_EXTRACT(v_pricing_result, '$.final_price_per_night')),
            JSON_UNQUOTE(JSON_EXTRACT(v_pricing_result, '$.final_price_per_night')),
            JSON_UNQUOTE(JSON_EXTRACT(v_pricing_result, '$.adjustments.season_multiplier')),
            JSON_UNQUOTE(JSON_EXTRACT(v_pricing_result, '$.adjustments.event_multiplier')),
            COALESCE(do.occupancy_rate, 0),
            v_pricing_result
        FROM room_types rt
        LEFT JOIN daily_occupancy do ON rt.type_id = do.room_type_id AND do.date_applicable = v_current_date
        WHERE rt.type_id = p_room_type_id
        ON DUPLICATE KEY UPDATE
            final_price_single = VALUES(final_price_single),
            final_price_double = VALUES(final_price_double),
            season_multiplier = VALUES(season_multiplier),
            event_multiplier = VALUES(event_multiplier),
            occupancy_rate = VALUES(occupancy_rate),
            pricing_rules_applied = VALUES(pricing_rules_applied),
            calculated_at = CURRENT_TIMESTAMP;
        
        SET v_current_date = DATE_ADD(v_current_date, INTERVAL 1 DAY);
    END WHILE;
END//
DELIMITER ;

