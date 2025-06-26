-- ===== PRICING VIEWS & TRIGGERS =====
-- Views tổng hợp giá phòng và triggers tự động cập nhật

-- ===== VIEWS TỔNG HỢP GIÁ ĐỘNG =====

-- View giá phòng hiện tại (real-time pricing)
CREATE VIEW current_room_pricing AS
SELECT 
    rt.type_id,
    rt.type_name,
    rt.base_price_single,
    rt.base_price_double,
    
    -- Giá hiện tại (hôm nay)
    COALESCE(ph.final_price_single, rt.base_price_single) as current_price_single,
    COALESCE(ph.final_price_double, rt.base_price_double) as current_price_double,
    
    -- Các yếu tố ảnh hưởng giá
    COALESCE(ph.season_multiplier, 1.00) as season_multiplier,
    COALESCE(ph.weekend_surcharge, 0) as weekend_surcharge,
    COALESCE(ph.event_multiplier, 1.00) as event_multiplier,
    
    -- Thông tin occupancy
    COALESCE(do.occupancy_rate, 0.00) as occupancy_rate,
    COALESCE(do.available_rooms, rt.total_rooms_planned) as available_rooms,
    COALESCE(do.price_adjustment_factor, 1.00) as occupancy_adjustment,
    
    -- Giá dự kiến ngày mai
    COALESCE(ph_tomorrow.final_price_single, rt.base_price_single) as tomorrow_price_single,
    COALESCE(ph_tomorrow.final_price_double, rt.base_price_double) as tomorrow_price_double,
    
    -- Metadata
    ph.calculated_at as last_price_update,
    CASE 
        WHEN do.occupancy_rate >= 0.90 THEN 'Very High'
        WHEN do.occupancy_rate >= 0.80 THEN 'High'
        WHEN do.occupancy_rate >= 0.60 THEN 'Moderate'
        WHEN do.occupancy_rate >= 0.40 THEN 'Low'
        ELSE 'Very Low'
    END as occupancy_level,
    
    CASE 
        WHEN COALESCE(ph.final_price_single, rt.base_price_single) > rt.base_price_single THEN 'Above Base'
        WHEN COALESCE(ph.final_price_single, rt.base_price_single) < rt.base_price_single THEN 'Below Base'
        ELSE 'Base Price'
    END as pricing_status

FROM room_types rt
LEFT JOIN price_history ph ON rt.type_id = ph.room_type_id AND ph.date_applicable = CURDATE()
LEFT JOIN price_history ph_tomorrow ON rt.type_id = ph_tomorrow.room_type_id AND ph_tomorrow.date_applicable = DATE_ADD(CURDATE(), INTERVAL 1 DAY)
LEFT JOIN daily_occupancy do ON rt.type_id = do.room_type_id AND do.date_applicable = CURDATE()
ORDER BY rt.type_id;

-- View giá phòng theo khoảng thời gian (để booking engine sử dụng)
CREATE VIEW pricing_calendar AS
SELECT 
    rt.type_id,
    rt.type_name,
    ph.date_applicable,
    DAYNAME(ph.date_applicable) as day_of_week,
    ph.base_price_single,
    ph.base_price_double,
    ph.final_price_single,
    ph.final_price_double,
    
    -- Breakdown các adjustment
    ph.season_multiplier,
    ph.weekend_surcharge,
    ph.event_multiplier,
    ph.occupancy_rate,
    
    -- Thông tin về mùa giá
    ps.season_name,
    st.display_name as season_type,
    
    -- Thông tin về sự kiện (nếu có)
    ep.event_name,
    ep.event_type,
    
    -- Availability
    COALESCE(do.available_rooms, rt.total_rooms_planned) as available_rooms,
    COALESCE(do.occupancy_rate, 0.00) as current_occupancy,
    
    -- Pricing rules applied
    ph.pricing_rules_applied,
    ph.calculated_at
    
FROM room_types rt
JOIN price_history ph ON rt.type_id = ph.room_type_id
LEFT JOIN pricing_seasons ps ON ph.date_applicable BETWEEN ps.start_date AND ps.end_date 
    AND ps.is_active = TRUE
LEFT JOIN season_types st ON ps.season_type_id = st.season_type_id
LEFT JOIN event_pricing ep ON ph.date_applicable BETWEEN ep.start_date AND ep.end_date 
    AND ep.is_active = TRUE
LEFT JOIN daily_occupancy do ON rt.type_id = do.room_type_id AND do.date_applicable = ph.date_applicable

WHERE ph.date_applicable >= CURDATE()
AND ph.date_applicable <= DATE_ADD(CURDATE(), INTERVAL 90 DAY)
ORDER BY ph.date_applicable, rt.type_id;

-- View phân tích pricing trend
CREATE VIEW pricing_trend_analysis AS
SELECT 
    rt.type_id,
    rt.type_name,
    
    -- Giá trung bình 7 ngày qua
    AVG(CASE WHEN ph.date_applicable >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN ph.final_price_double END) as avg_price_7days,
    
    -- Giá trung bình 30 ngày qua
    AVG(CASE WHEN ph.date_applicable >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN ph.final_price_double END) as avg_price_30days,
    
    -- Giá cao nhất/thấp nhất 30 ngày qua
    MAX(CASE WHEN ph.date_applicable >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN ph.final_price_double END) as max_price_30days,
    MIN(CASE WHEN ph.date_applicable >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN ph.final_price_double END) as min_price_30days,
    
    -- Occupancy trung bình
    AVG(CASE WHEN ph.date_applicable >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN ph.occupancy_rate END) as avg_occupancy_30days,
    
    -- Số ngày có giá trên/dưới base price
    COUNT(CASE WHEN ph.final_price_double > rt.base_price_double AND ph.date_applicable >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN 1 END) as days_above_base,
    COUNT(CASE WHEN ph.final_price_double < rt.base_price_double AND ph.date_applicable >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN 1 END) as days_below_base,
    
    -- Tổng ngày có data
    COUNT(CASE WHEN ph.date_applicable >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN 1 END) as total_days_data,
    
    -- Price volatility (độ biến động)
    STDDEV(CASE WHEN ph.date_applicable >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) THEN ph.final_price_double END) as price_volatility_30days

FROM room_types rt
LEFT JOIN price_history ph ON rt.type_id = ph.room_type_id
GROUP BY rt.type_id, rt.type_name, rt.base_price_double
ORDER BY rt.type_id;

-- View ưu đãi hiện đang áp dụng
CREATE VIEW active_pricing_promotions AS
SELECT 
    'Season' as promotion_type,
    ps.season_name as promotion_name,
    ps.price_multiplier as discount_value,
    CONCAT(ps.price_multiplier * 100, '%') as discount_display,
    ps.start_date,
    ps.end_date,
    st.display_name as category,
    ps.description,
    ps.is_active,
    NULL as applicable_room_types,
    ps.priority

FROM pricing_seasons ps
JOIN season_types st ON ps.season_type_id = st.season_type_id
WHERE ps.is_active = TRUE
AND CURDATE() BETWEEN ps.start_date AND ps.end_date

UNION ALL

SELECT 
    'Weekend Surcharge' as promotion_type,
    ws.surcharge_name as promotion_name,
    ws.surcharge_value as discount_value,
    CONCAT('+', ws.surcharge_value, CASE WHEN ws.surcharge_type = 'percentage' THEN '%' ELSE ' VND' END) as discount_display,
    ws.start_date,
    ws.end_date,
    'Weekend' as category,
    ws.description,
    ws.is_active,
    ws.applicable_room_types,
    5 as priority

FROM weekend_surcharge ws
WHERE ws.is_active = TRUE
AND (ws.start_date IS NULL OR CURDATE() >= ws.start_date)
AND (ws.end_date IS NULL OR CURDATE() <= ws.end_date)

UNION ALL

SELECT 
    'Event Pricing' as promotion_type,
    ep.event_name as promotion_name,
    ep.price_multiplier as discount_value,
    CONCAT(ep.price_multiplier * 100, '%') as discount_display,
    ep.start_date,
    ep.end_date,
    ep.event_type as category,
    ep.description,
    ep.is_active,
    ep.applicable_room_types,
    8 as priority

FROM event_pricing ep
WHERE ep.is_active = TRUE
AND CURDATE() BETWEEN ep.start_date AND ep.end_date

UNION ALL

SELECT 
    'Long Stay' as promotion_type,
    lsp.policy_name as promotion_name,
    lsp.discount_value as discount_value,
    CONCAT('-', lsp.discount_value, CASE WHEN lsp.discount_type = 'percentage' THEN '%' ELSE ' VND/night' END) as discount_display,
    lsp.valid_from as start_date,
    lsp.valid_to as end_date,
    CONCAT(lsp.min_nights, '+ nights') as category,
    lsp.description,
    lsp.is_active,
    lsp.applicable_room_types,
    3 as priority

FROM long_stay_pricing lsp
WHERE lsp.is_active = TRUE
AND (lsp.valid_from IS NULL OR CURDATE() >= lsp.valid_from)
AND (lsp.valid_to IS NULL OR CURDATE() <= lsp.valid_to)

ORDER BY priority DESC, start_date;

-- View hợp đồng doanh nghiệp đang hiệu lực
CREATE VIEW active_corporate_contracts AS
SELECT 
    gc.contract_id,
    gc.contract_name,
    gc.contract_type,
    gc.company_name,
    gc.contact_person,
    gc.contact_email,
    
    -- Điều kiện ưu đãi
    gc.min_rooms_per_booking,
    gc.min_nights_per_booking,
    gc.min_bookings_per_year,
    gc.min_revenue_per_year,
    
    -- Mức ưu đãi
    gc.discount_type,
    gc.discount_value,
    gc.max_discount_amount,
    
    -- Thời gian hiệu lực
    gc.start_date,
    gc.end_date,
    DATEDIFF(gc.end_date, CURDATE()) as days_remaining,
    
    -- Điều kiện áp dụng
    gc.applicable_room_types,
    gc.applicable_seasons,
    gc.blackout_dates,
    
    gc.auto_apply,
    gc.special_terms

FROM group_contracts gc
WHERE gc.is_active = TRUE
AND CURDATE() BETWEEN gc.start_date AND gc.end_date
ORDER BY gc.discount_value DESC, gc.end_date;

-- ===== TRIGGERS TỰ ĐỘNG CẬP NHẬT =====

-- Trigger tự động cập nhật occupancy khi có booking mới
DELIMITER //
CREATE TRIGGER tr_booking_update_occupancy
AFTER INSERT ON bookings
FOR EACH ROW
BEGIN
    DECLARE v_current_date DATE;
    DECLARE v_end_date DATE;
    
    SET v_current_date = NEW.check_in_date;
    SET v_end_date = NEW.check_out_date;
    
    -- Cập nhật occupancy cho từng ngày trong khoảng thời gian booking
    WHILE v_current_date < v_end_date DO
        CALL sp_update_daily_occupancy(v_current_date);
        SET v_current_date = DATE_ADD(v_current_date, INTERVAL 1 DAY);
    END WHILE;
END//
DELIMITER ;

-- Trigger tự động cập nhật occupancy khi booking bị hủy hoặc thay đổi
DELIMITER //
CREATE TRIGGER tr_booking_cancel_update_occupancy
AFTER UPDATE ON bookings
FOR EACH ROW
BEGIN
    DECLARE v_current_date DATE;
    DECLARE v_end_date DATE;
    
    -- Chỉ xử lý khi status thay đổi
    IF OLD.booking_status != NEW.booking_status THEN
        -- Cập nhật cho ngày booking cũ
        SET v_current_date = OLD.check_in_date;
        SET v_end_date = OLD.check_out_date;
        
        WHILE v_current_date < v_end_date DO
            CALL sp_update_daily_occupancy(v_current_date);
            SET v_current_date = DATE_ADD(v_current_date, INTERVAL 1 DAY);
        END WHILE;
        
        -- Nếu booking bị thay đổi ngày, cập nhật cho ngày mới
        IF OLD.check_in_date != NEW.check_in_date OR OLD.check_out_date != NEW.check_out_date THEN
            SET v_current_date = NEW.check_in_date;
            SET v_end_date = NEW.check_out_date;
            
            WHILE v_current_date < v_end_date DO
                CALL sp_update_daily_occupancy(v_current_date);
                SET v_current_date = DATE_ADD(v_current_date, INTERVAL 1 DAY);
            END WHILE;
        END IF;
    END IF;
END//
DELIMITER ;

-- Trigger tự động tính lại giá khi occupancy thay đổi đáng kể
DELIMITER //
CREATE TRIGGER tr_occupancy_recalculate_price
AFTER UPDATE ON daily_occupancy
FOR EACH ROW
BEGIN
    -- Chỉ tính lại khi occupancy thay đổi > 5%
    IF ABS(NEW.occupancy_rate - OLD.occupancy_rate) > 0.05 THEN
        -- Tính lại giá cho ngày đó
        CALL sp_calculate_price_range(NEW.room_type_id, NEW.date_applicable, NEW.date_applicable);
    END IF;
END//
DELIMITER ;

-- ===== STORED PROCEDURES BỔ SUNG =====

-- Procedure tự động cập nhật giá cho các ngày sắp tới
DELIMITER //
CREATE PROCEDURE sp_auto_update_future_pricing(
    IN p_days_ahead INT
)
BEGIN
    DECLARE v_end_date DATE;
    
    -- Handle default value
    IF p_days_ahead IS NULL THEN
        SET p_days_ahead = 30;
    END IF;
    
    SET v_end_date = DATE_ADD(CURDATE(), INTERVAL p_days_ahead DAY);
    
    -- Cập nhật occupancy cho các ngày sắp tới
    CALL sp_update_daily_occupancy(CURDATE());
    
    -- Tính giá cho tất cả loại phòng trong khoảng thời gian
    INSERT INTO price_history (room_type_id, date_applicable, base_price_single, base_price_double, final_price_single, final_price_double, calculated_at)
    SELECT 
        rt.type_id,
        d.calendar_date,
        rt.base_price_single,
        rt.base_price_double,
        JSON_UNQUOTE(JSON_EXTRACT(
            fn_calculate_dynamic_price(rt.type_id, d.calendar_date, DATE_ADD(d.calendar_date, INTERVAL 1 DAY), 1, 'individual', NULL), 
            '$.final_price_per_night'
        )) as calc_price_single,
        JSON_UNQUOTE(JSON_EXTRACT(
            fn_calculate_dynamic_price(rt.type_id, d.calendar_date, DATE_ADD(d.calendar_date, INTERVAL 1 DAY), 2, 'individual', NULL), 
            '$.final_price_per_night'
        )) as calc_price_double,
        NOW() as calc_time
    FROM room_types rt
    CROSS JOIN (
        SELECT DATE_ADD(CURDATE(), INTERVAL seq.seq DAY) as calendar_date
        FROM (
            SELECT 0 as seq UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6
            UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10 UNION SELECT 11 UNION SELECT 12 UNION SELECT 13
            UNION SELECT 14 UNION SELECT 15 UNION SELECT 16 UNION SELECT 17 UNION SELECT 18 UNION SELECT 19 UNION SELECT 20
            UNION SELECT 21 UNION SELECT 22 UNION SELECT 23 UNION SELECT 24 UNION SELECT 25 UNION SELECT 26 UNION SELECT 27
            UNION SELECT 28 UNION SELECT 29 UNION SELECT 30
        ) seq
        WHERE DATE_ADD(CURDATE(), INTERVAL seq.seq DAY) <= v_end_date
    ) d
    WHERE NOT EXISTS (
        SELECT 1 FROM price_history ph 
        WHERE ph.room_type_id = rt.type_id 
        AND ph.date_applicable = d.calendar_date
    );
END//
DELIMITER ;

-- Function lấy giá tốt nhất cho booking
DELIMITER //
CREATE FUNCTION fn_get_best_price_for_booking(
    p_user_id INT,
    p_room_type_id INT,
    p_check_in_date DATE,
    p_check_out_date DATE,
    p_guest_count INT,
    p_total_rooms INT
) RETURNS JSON
READS SQL DATA
DETERMINISTIC
BEGIN
    DECLARE v_standard_price JSON;
    DECLARE v_contract_price JSON;
    DECLARE v_best_contract JSON;
    DECLARE v_final_result JSON;
    
    -- Tính giá tiêu chuẩn
    SET v_standard_price = fn_calculate_dynamic_price(
        p_room_type_id, p_check_in_date, p_check_out_date, p_guest_count, 'individual', NULL
    );
    
    -- Kiểm tra ưu đãi hợp đồng
    SET v_best_contract = fn_check_contract_eligibility(
        p_user_id, p_total_rooms, DATEDIFF(p_check_out_date, p_check_in_date), 
        JSON_UNQUOTE(JSON_EXTRACT(v_standard_price, '$.total_price'))
    );
    
    -- Nếu có hợp đồng tốt hơn
    IF JSON_UNQUOTE(JSON_EXTRACT(v_best_contract, '$.eligible')) != 'FALSE' THEN
        SET v_contract_price = fn_calculate_dynamic_price(
            p_room_type_id, p_check_in_date, p_check_out_date, p_guest_count, 'corporate', 
            JSON_UNQUOTE(JSON_EXTRACT(v_best_contract, '$.contract_id'))
        );
        
        -- So sánh và chọn giá tốt nhất
        IF JSON_UNQUOTE(JSON_EXTRACT(v_contract_price, '$.total_price')) < JSON_UNQUOTE(JSON_EXTRACT(v_standard_price, '$.total_price')) THEN
            SET v_final_result = JSON_MERGE_PATCH(v_contract_price, JSON_OBJECT('contract_applied', v_best_contract));
        ELSE
            SET v_final_result = JSON_MERGE_PATCH(v_standard_price, JSON_OBJECT('contract_applied', JSON_OBJECT('eligible', FALSE)));
        END IF;
    ELSE
        SET v_final_result = JSON_MERGE_PATCH(v_standard_price, JSON_OBJECT('contract_applied', JSON_OBJECT('eligible', FALSE)));
    END IF;
    
    RETURN v_final_result;
END//
DELIMITER ;

-- ===== EVENT SCHEDULER JOBS =====
-- Tự động cập nhật giá mỗi ngày vào 00:30

-- SET GLOBAL event_scheduler = ON;

-- CREATE EVENT IF NOT EXISTS ev_daily_pricing_update
-- ON SCHEDULE EVERY 1 DAY
-- STARTS CONCAT(CURDATE() + INTERVAL 1 DAY, ' 00:30:00')
-- DO
-- BEGIN
--     CALL sp_auto_update_future_pricing(30);
-- END;

-- ===== INDEXES FOR PERFORMANCE =====

-- Indexes cho bảng price_history
CREATE INDEX idx_price_room_date ON price_history(room_type_id, date_applicable);
CREATE INDEX idx_price_date_range ON price_history(date_applicable);
CREATE INDEX idx_price_calculated ON price_history(calculated_at);

-- Indexes cho bảng daily_occupancy
CREATE INDEX idx_occupancy_room_date ON daily_occupancy(room_type_id, date_applicable);
CREATE INDEX idx_occupancy_rate ON daily_occupancy(occupancy_rate);
CREATE INDEX idx_occupancy_updated ON daily_occupancy(last_updated);

-- Indexes cho pricing_seasons
CREATE INDEX idx_season_dates ON pricing_seasons(start_date, end_date);
CREATE INDEX idx_season_active ON pricing_seasons(is_active, priority);

-- Indexes cho event_pricing
CREATE INDEX idx_event_dates ON event_pricing(start_date, end_date);
CREATE INDEX idx_event_active ON event_pricing(is_active, auto_apply);

-- Indexes cho group_contracts
CREATE INDEX idx_contract_dates ON group_contracts(start_date, end_date);
CREATE INDEX idx_contract_active ON group_contracts(is_active, auto_apply);
