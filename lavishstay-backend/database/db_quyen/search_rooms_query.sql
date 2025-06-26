-- ===== HỆ THỐNG TÌM KIẾM PHÒNG THÔNG MINH LAVISHSTAY =====
-- Mục tiêu: Hiển thị TẤT CẢ loại phòng có sẵn, sắp xếp phù hợp lên đầu, không phù hợp xuống cuối
-- Tích hợp DYNAMIC PRICING với tính toán phụ thu chính xác từ database
-- Logic sức chứa: Phòng thường 4 người (2 người lớn + 2 trẻ em), Presidential Suite 10 người

-- ===== DYNAMIC PRICING SYSTEM =====
-- 1. Single Price: Giá single cho 1 người (room_types.base_price_single)
-- 2. Double Price: Giá double cho 2+ người (room_types.base_price_double)
-- 3. Seasonal Pricing: Hệ số theo mùa (pricing_seasons.price_multiplier) 
-- 4. Price History: Giá đã tính toán hoàn chỉnh (price_history.final_price_single/double)
-- 5. Priority: price_history > seasonal_pricing > base_price

-- ===== GUEST SURCHARGE SYSTEM (Cập nhật mới) =====
-- Standard Occupancy: 2 người/phòng (không phụ thu)
-- Extra Adult/Teen 12+: 670,000 VND/đêm/người
-- Child 6-12 with bed: 335,000 VND/đêm/người  
-- Child 6-12 no bed: 110,000 VND/đêm/người
-- Child under 6: 0 VND (miễn phí)
-- Extra bed: 1,080,000 VND/đêm

-- ===== STORED PROCEDURE: COMPREHENSIVE ROOM SEARCH WITH DETAILED CHILD AGES =====
DELIMITER //

DROP PROCEDURE IF EXISTS SmartRoomSearch //

CREATE PROCEDURE SmartRoomSearch(
    IN p_checkin_date DATE,                    -- Ngày nhận phòng
    IN p_checkout_date DATE,                   -- Ngày trả phòng
    IN p_adults INT,                           -- Số người lớn
    IN p_children INT,                         -- Số trẻ em tổng
    IN p_children_under_6 INT,                 -- Số trẻ em dưới 6 tuổi (miễn phí)
    IN p_children_6_12_no_bed INT,             -- Số trẻ em 6-12 tuổi không giường phụ
    IN p_children_6_12_with_bed INT,           -- Số trẻ em 6-12 tuổi có giường phụ
    IN p_extra_beds INT,                       -- Số giường phụ cần thêm
    IN p_min_price BIGINT,                     -- Giá tối thiểu (VND/đêm)
    IN p_max_price BIGINT,                     -- Giá tối đa (VND/đêm) 
    IN p_room_type VARCHAR(50),                -- Loại phòng mong muốn (optional)
    IN p_view_type VARCHAR(20),                -- Loại view mong muốn (optional)
    IN p_floor_preference VARCHAR(20),         -- Ưu tiên tầng: 'low', 'mid', 'high' (optional)
    IN p_max_rooms INT,                        -- Số phòng tối đa muốn đặt
    IN p_search_mode VARCHAR(20)               -- Chế độ tìm: 'optimal', 'cheapest', 'luxury'
)
BEGIN
    DECLARE total_guests INT DEFAULT p_adults + p_children;
    DECLARE nights INT DEFAULT DATEDIFF(p_checkout_date, p_checkin_date);
    
    -- Tính toán phụ thu mới
    DECLARE adult_surcharge_total BIGINT DEFAULT 0;
    DECLARE child_surcharge_total BIGINT DEFAULT 0;
    DECLARE extra_bed_surcharge_total BIGINT DEFAULT 0;
    
    -- Set default values
    SET p_max_rooms = COALESCE(p_max_rooms, 10);
    SET p_search_mode = COALESCE(p_search_mode, 'optimal');
    SET p_children_under_6 = COALESCE(p_children_under_6, 0);
    SET p_children_6_12_no_bed = COALESCE(p_children_6_12_no_bed, 0);
    SET p_children_6_12_with_bed = COALESCE(p_children_6_12_with_bed, 0);
    SET p_extra_beds = COALESCE(p_extra_beds, 0);
    
    -- Validate child numbers consistency
    IF p_children != (p_children_under_6 + p_children_6_12_no_bed + p_children_6_12_with_bed) THEN
        SET p_children_6_12_no_bed = GREATEST(0, p_children - p_children_under_6 - p_children_6_12_with_bed);
    END IF;
    
    -- ===== HIỂN THỊ TẤT CẢ ROOM TYPES CÓ SẴN =====
    -- Mục tiêu: Luôn hiển thị đầy đủ các loại phòng, sắp xếp phù hợp lên đầu
    
    SELECT 
        -- ===== THÔNG TIN PHÒNG CƠ BẢN =====
        rt.type_id as 'ID Loại Phòng',
        rt.type_name as 'Tên Loại Phòng',
        rt.description as 'Mô Tả Phòng',
        
        -- ===== THÔNG TIN SỨC CHỨA =====
        rt.max_occupancy as 'Sức Chứa Tối Đa (người)',
        rt.standard_occupancy as 'Sức Chứa Tiêu Chuẩn (người)',
        
        -- ===== SỐ LƯỢNG PHÒNG AVAILABLE =====
        COUNT(CASE WHEN r.status = 'available' 
                   AND r.room_id NOT IN (
                       SELECT DISTINCT room_id FROM bookings 
                       WHERE booking_status IN ('confirmed', 'checked_in')
                         AND NOT (check_out_date <= p_checkin_date OR check_in_date >= p_checkout_date)
                   ) THEN 1 END) as 'Số Phòng Trống',
        COUNT(r.room_id) as 'Tổng Số Phòng Loại Này',
        
        -- ===== THÔNG TIN GIÁ DYNAMIC =====
        rt.base_price_single as 'Giá Single Gốc (VND/đêm)',
        rt.base_price_double as 'Giá Double Gốc (VND/đêm)',
        COALESCE(ps.price_multiplier, 1.0) as 'Hệ Số Mùa',
        
        -- ===== THÔNG TIN PHỤ THU =====
        rt.surcharge_adult as 'Phụ Thu Người Lớn (VND/đêm/người)',
        rt.surcharge_child_6_12 as 'Phụ Thu Trẻ Em 6-12 (VND/đêm/người)',
        rt.surcharge_child_under_6 as 'Phụ Thu Trẻ Em <6 (VND/đêm/người)',
        rt.surcharge_extra_bed as 'Phụ Thu Giường Phụ (VND/đêm)',
        
        -- ===== THÔNG TIN PHÒNG CHI TIẾT =====
        rt.area_sqm as 'Diện Tích (m²)',
        rt.view_type as 'Loại View',
        rt.total_rooms_planned as 'Tổng Số Phòng Kế Hoạch',
        
        -- ===== THÔNG TIN TIỆN ÍCH AMENITIES =====
        (SELECT GROUP_CONCAT(DISTINCT a.name ORDER BY a.name SEPARATOR ', ')
         FROM room_type_amenities rta 
         JOIN amenities a ON rta.amenity_id = a.amenity_id 
         WHERE rta.room_type_id = rt.type_id AND rta.is_main_amenity = TRUE
        ) as 'Tiện Ích Chính',
        
        (SELECT GROUP_CONCAT(DISTINCT a.name ORDER BY a.name SEPARATOR ', ')
         FROM room_type_amenities rta 
         JOIN amenities a ON rta.amenity_id = a.amenity_id 
         WHERE rta.room_type_id = rt.type_id AND rta.is_main_amenity = FALSE
        ) as 'Tiện Ích Bổ Sung',
        
        (SELECT COUNT(DISTINCT a.amenity_id)
         FROM room_type_amenities rta 
         JOIN amenities a ON rta.amenity_id = a.amenity_id 
         WHERE rta.room_type_id = rt.type_id
        ) as 'Tổng Số Tiện Ích',
        
        -- Giá hiện tại theo số khách: 1 người dùng single, 2+ người dùng double
        CASE 
            WHEN p_adults = 1 AND p_children = 0 THEN 
                COALESCE(ph.final_price_single, 
                        rt.base_price_single * COALESCE(ps.price_multiplier, 1.0), 
                        rt.base_price_single)
            ELSE 
                COALESCE(ph.final_price_double, 
                        rt.base_price_double * COALESCE(ps.price_multiplier, 1.0), 
                        rt.base_price_double)
        END as 'Giá Hiện Tại (VND/đêm)',
        
        -- ===== CHI PHÍ CƠ BẢN (Giá phòng không gồm phụ thu) =====
        CASE 
            WHEN p_adults = 1 AND p_children = 0 THEN 
                (COALESCE(ph.final_price_single, 
                         rt.base_price_single * COALESCE(ps.price_multiplier, 1.0), 
                         rt.base_price_single) * nights)
            ELSE 
                (COALESCE(ph.final_price_double, 
                         rt.base_price_double * COALESCE(ps.price_multiplier, 1.0), 
                         rt.base_price_double) * nights)
        END as 'Chi Phí Cơ Bản (VND)',
                  
        -- ===== PHỤ THU NGƯỜI (Logic mới với phân loại chi tiết) =====
        CASE 
            WHEN total_guests <= rt.standard_occupancy THEN 0  -- Trong sức chứa tiêu chuẩn
            WHEN total_guests <= rt.max_occupancy THEN 
                -- Tính phụ thu theo quy định mới
                (GREATEST(0, p_adults - rt.standard_occupancy) * 670000 +  -- Adult surcharge: 670,000 VND
                 p_children_6_12_with_bed * 335000 +                       -- Child 6-12 with bed: 335,000 VND
                 p_children_6_12_no_bed * 110000 +                         -- Child 6-12 no bed: 110,000 VND  
                 p_children_under_6 * 0 +                                  -- Child under 6: Free
                 p_extra_beds * 1080000                                    -- Extra bed: 1,080,000 VND
                ) * nights
            ELSE 
                -- Vượt sức chứa tối đa với phụ thu cao hơn
                (GREATEST(0, p_adults - rt.standard_occupancy) * 670000 +
                 p_children_6_12_with_bed * 335000 +
                 p_children_6_12_no_bed * 110000 +
                 p_children_under_6 * 0 +
                 p_extra_beds * 1080000 + 
                 GREATEST(0, total_guests - rt.max_occupancy) * 100000     -- Penalty for exceeding max
                ) * nights
        END as 'Phụ Thu Người (VND)',
        
        -- ===== TỔNG CHI PHÍ CUỐI CÙNG =====
        (CASE 
            WHEN p_adults = 1 AND p_children = 0 THEN 
                (COALESCE(ph.final_price_single, 
                         rt.base_price_single * COALESCE(ps.price_multiplier, 1.0), 
                         rt.base_price_single) * nights)
            ELSE 
                (COALESCE(ph.final_price_double, 
                         rt.base_price_double * COALESCE(ps.price_multiplier, 1.0), 
                         rt.base_price_double) * nights)
        END) +
        (CASE 
            WHEN total_guests <= rt.standard_occupancy THEN 0
            WHEN total_guests <= rt.max_occupancy THEN 
                (GREATEST(0, p_adults - rt.standard_occupancy) * 670000 +
                 p_children_6_12_with_bed * 335000 +
                 p_children_6_12_no_bed * 110000 +
                 p_children_under_6 * 0 +
                 p_extra_beds * 1080000) * nights
            ELSE 
                (GREATEST(0, p_adults - rt.standard_occupancy) * 670000 +
                 p_children_6_12_with_bed * 335000 +
                 p_children_6_12_no_bed * 110000 +
                 p_children_under_6 * 0 +
                 p_extra_beds * 1080000 + 
                 GREATEST(0, total_guests - rt.max_occupancy) * 100000) * nights
        END) as 'Tổng Chi Phí Cuối Cùng (VND)',
        
        -- ===== PHÒNG CỤ THỂ CÓ SẴN =====
        CASE 
            WHEN COUNT(CASE WHEN r.status = 'available' 
                             AND r.room_id NOT IN (
                                 SELECT DISTINCT room_id FROM bookings 
                                 WHERE booking_status IN ('confirmed', 'checked_in')
                                   AND NOT (check_out_date <= p_checkin_date OR check_in_date >= p_checkout_date)
                             ) THEN 1 END) = 0 THEN 'Khong con phong trong'
            WHEN COUNT(CASE WHEN r.status = 'available' 
                             AND r.room_id NOT IN (
                                 SELECT DISTINCT room_id FROM bookings 
                                 WHERE booking_status IN ('confirmed', 'checked_in')
                                   AND NOT (check_out_date <= p_checkin_date OR check_in_date >= p_checkout_date)
                             ) THEN 1 END) <= 3 THEN 
                GROUP_CONCAT(
                    CASE WHEN r.status = 'available' 
                         AND r.room_id NOT IN (
                             SELECT DISTINCT room_id FROM bookings 
                             WHERE booking_status IN ('confirmed', 'checked_in')
                               AND NOT (check_out_date <= p_checkin_date OR check_in_date >= p_checkout_date)
                         ) 
                    THEN CONCAT('Phong ', r.room_number, ' (', r.bed_type_fixed, ', tang ', f.floor_number, ')')
                    END SEPARATOR ' | ')
            ELSE 
                CONCAT(
                    SUBSTRING_INDEX(
                        GROUP_CONCAT(
                            CASE WHEN r.status = 'available' 
                                 AND r.room_id NOT IN (
                                     SELECT DISTINCT room_id FROM bookings 
                                     WHERE booking_status IN ('confirmed', 'checked_in')
                                       AND NOT (check_out_date <= p_checkin_date OR check_in_date >= p_checkout_date)
                                 ) 
                            THEN CONCAT('Phong ', r.room_number)
                            END SEPARATOR ', '), ', ', 3),
                    ' va ', 
                    (COUNT(CASE WHEN r.status = 'available' 
                                 AND r.room_id NOT IN (
                                     SELECT DISTINCT room_id FROM bookings 
                                     WHERE booking_status IN ('confirmed', 'checked_in')
                                       AND NOT (check_out_date <= p_checkin_date OR check_in_date >= p_checkout_date)
                                 ) THEN 1 END) - 3),
                    ' phong khac'
                )
        END as 'Phong Cu The Co San',
        
        -- ===== ĐIỂM PHÙ HỢP (Logic mới) =====
        CASE 
            WHEN COUNT(CASE WHEN r.status = 'available' 
                             AND r.room_id NOT IN (
                                 SELECT DISTINCT room_id FROM bookings 
                                 WHERE booking_status IN ('confirmed', 'checked_in')
                                   AND NOT (check_out_date <= p_checkin_date OR check_in_date >= p_checkout_date)
                             ) THEN 1 END) = 0 THEN 0                                              -- Hết phòng
            WHEN total_guests <= rt.standard_occupancy THEN 100                                   -- Hoàn hảo: trong sức chứa tiêu chuẩn
            WHEN total_guests <= rt.max_occupancy THEN 90                                         -- Rất tốt: trong sức chứa tối đa
            WHEN total_guests <= rt.max_occupancy + 2 THEN 70                                     -- Khá tốt: chấp nhận được với phụ thu
            ELSE GREATEST(50 - (total_guests - rt.max_occupancy) * 10, 10)                       -- Giảm điểm theo số người vượt
        END as 'Điểm Phù Hợp (0-100)',
        
        -- ===== PHÂN LOẠI KHÁCH =====
        CASE 
            WHEN total_guests = 1 THEN 'Khách đơn lẻ (1 người)'
            WHEN total_guests = 2 AND p_children = 0 THEN 'Cặp đôi (2 người lớn)'
            WHEN total_guests = 2 AND p_children > 0 THEN 'Gia đình nhỏ (1 người lớn + trẻ em)'
            WHEN total_guests = 3 THEN 'Gia đình nhỏ (3 người)'
            WHEN total_guests = 4 THEN 'Gia đình tiêu chuẩn (4 người)'
            WHEN total_guests BETWEEN 5 AND 6 THEN 'Gia đình lớn (5-6 người)'
            WHEN total_guests BETWEEN 7 AND 10 THEN 'Nhóm bạn/đồng nghiệp (7-10 nguoi)'
            ELSE 'Nhóm lớn/Doanh nghiệp (>10 nguoi)'
        END as 'Phan Loai Khach',
        
        -- ===== LỜI KHUYÊN CHI TIẾT =====
        CASE 
            WHEN COUNT(CASE WHEN r.status = 'available' 
                             AND r.room_id NOT IN (
                                 SELECT DISTINCT room_id FROM bookings 
                                 WHERE booking_status IN ('confirmed', 'checked_in')
                                   AND NOT (check_out_date <= p_checkin_date OR check_in_date >= p_checkout_date)
                             ) THEN 1 END) = 0 THEN 
                'HET PHONG: Khong con phong trong trong ngay nay'
            WHEN total_guests <= rt.standard_occupancy THEN 
                CONCAT('HOAN HAO: Trong suc chua tieu chuan (', total_guests, ' nguoi) - Khong phu thu')
            WHEN total_guests <= rt.max_occupancy THEN
                CONCAT('TOT: Trong suc chua toi da (', total_guests, '/', rt.max_occupancy, ' nguoi) - Co phu thu cho nguoi them')
            ELSE 
                CONCAT('PHU THU CAO: Vuot ', total_guests - rt.max_occupancy, ' nguoi - Phu thu cao hon')
        END as 'Loi Khuyen Chi Tiet',
        
        -- ===== TRẠNG THÁI SỨC CHỨA =====
        CASE 
            WHEN total_guests <= rt.standard_occupancy THEN 'Trong suc chua tieu chuan'
            WHEN total_guests <= rt.max_occupancy THEN 
                CONCAT('Phu thu cho ', total_guests - rt.standard_occupancy, ' nguoi')
            ELSE 
                CONCAT('Vuot qua ', total_guests - rt.max_occupancy, ' nguoi - phu thu cao')
        END as 'Trang Thai Suc Chua',
        
        -- ===== MỨC ĐỘ PHÙ HỢP =====
        CASE 
            WHEN COUNT(CASE WHEN r.status = 'available' 
                             AND r.room_id NOT IN (
                                 SELECT DISTINCT room_id FROM bookings 
                                 WHERE booking_status IN ('confirmed', 'checked_in')
                                   AND NOT (check_out_date <= p_checkin_date OR check_in_date >= p_checkout_date)
                             ) THEN 1 END) = 0 THEN 'Het phong'
            WHEN total_guests <= rt.standard_occupancy THEN 'Hoan hao'
            WHEN total_guests <= rt.max_occupancy THEN 'Rat phu hop'
            WHEN total_guests <= rt.max_occupancy + 2 THEN 'Kha phu hop'
            ELSE 'Co the xem xet'
        END as 'Muc Do Phu Hop',
        
        -- ===== THÔNG TIN ĐẶT PHÒNG =====
        nights as 'Tong So Dem',
        CONCAT(
            'Nhan phong: ', DATE_FORMAT(p_checkin_date, '%d/%m/%Y (%W)'),
            ' | Tra phong: ', DATE_FORMAT(p_checkout_date, '%d/%m/%Y (%W)')
        ) as 'Thong Tin Dat Phong',
        
        -- ===== CHI TIẾT PHỤ THU (Logic mới chi tiết) =====
        CASE 
            WHEN total_guests <= rt.standard_occupancy THEN 
                'Khong phu thu (trong suc chua tieu chuan)'
            WHEN total_guests <= rt.max_occupancy THEN 
                CONCAT('Phu thu chi tiet: ',
                       -- Adult surcharge
                       CASE WHEN p_adults > rt.standard_occupancy THEN 
                           CONCAT(p_adults - rt.standard_occupancy, ' nguoi lon × 670,000d × ', nights, ' dem = ', 
                                  FORMAT((p_adults - rt.standard_occupancy) * 670000 * nights, 0), 'd')
                       ELSE '' END,
                       -- Child 6-12 with bed
                       CASE WHEN p_children_6_12_with_bed > 0 THEN 
                           CONCAT(CASE WHEN p_adults > rt.standard_occupancy THEN ' + ' ELSE '' END,
                                  p_children_6_12_with_bed, ' tre em 6-12 (co giuong) × 335,000d × ', nights, ' dem = ', 
                                  FORMAT(p_children_6_12_with_bed * 335000 * nights, 0), 'd')
                       ELSE '' END,
                       -- Child 6-12 no bed
                       CASE WHEN p_children_6_12_no_bed > 0 THEN 
                           CONCAT(CASE WHEN (p_adults > rt.standard_occupancy OR p_children_6_12_with_bed > 0) THEN ' + ' ELSE '' END,
                                  p_children_6_12_no_bed, ' tre em 6-12 (khong giuong) × 110,000d × ', nights, ' dem = ', 
                                  FORMAT(p_children_6_12_no_bed * 110000 * nights, 0), 'd')
                       ELSE '' END,
                       -- Extra beds
                       CASE WHEN p_extra_beds > 0 THEN 
                           CONCAT(CASE WHEN (p_adults > rt.standard_occupancy OR p_children_6_12_with_bed > 0 OR p_children_6_12_no_bed > 0) THEN ' + ' ELSE '' END,
                                  p_extra_beds, ' giuong phu × 1,080,000d × ', nights, ' dem = ', 
                                  FORMAT(p_extra_beds * 1080000 * nights, 0), 'd')
                       ELSE '' END,
                       -- Child under 6 - free
                       CASE WHEN p_children_under_6 > 0 THEN 
                           CONCAT(' | ', p_children_under_6, ' tre em <6 tuoi: MIEN PHI')
                       ELSE '' END)
            ELSE 
                CONCAT('Vuot suc chua toi da - Phu thu cao: ', 
                       FORMAT((GREATEST(0, p_adults - rt.standard_occupancy) * 670000 +
                               p_children_6_12_with_bed * 335000 +
                               p_children_6_12_no_bed * 110000 +
                               p_extra_beds * 1080000 + 
                               GREATEST(0, total_guests - rt.max_occupancy) * 100000) * nights, 0), 'd')
        END as 'Chi Tiet Phu Thu'

    FROM room_types rt
    LEFT JOIN rooms r ON rt.type_id = r.room_type_id
    LEFT JOIN floors f ON r.floor_id = f.floor_id
    LEFT JOIN (
        -- Chỉ lấy 1 pricing season để tránh duplicate
        SELECT ps1.* FROM pricing_seasons ps1
        WHERE ps1.is_active = TRUE 
        AND p_checkin_date BETWEEN ps1.start_date AND ps1.end_date
        AND ps1.season_id = (
            SELECT ps2.season_id FROM pricing_seasons ps2
            WHERE ps2.is_active = TRUE 
            AND p_checkin_date BETWEEN ps2.start_date AND ps2.end_date
            ORDER BY ps2.price_multiplier DESC, ps2.season_id DESC
            LIMIT 1
        )
    ) ps ON 1=1
    LEFT JOIN price_history ph ON rt.type_id = ph.room_type_id 
        AND ph.date_applicable = p_checkin_date
    
    -- Filters (áp dụng filter chỉ khi có giá trị cụ thể)
    WHERE (p_room_type IS NULL OR rt.type_name LIKE CONCAT('%', p_room_type, '%'))
        AND (p_view_type IS NULL OR rt.view_type = p_view_type)
        AND (p_min_price IS NULL OR 
             CASE 
                WHEN p_adults = 1 AND p_children = 0 THEN 
                    COALESCE(ph.final_price_single, rt.base_price_single * COALESCE(ps.price_multiplier, 1.0), rt.base_price_single) >= p_min_price
                ELSE 
                    COALESCE(ph.final_price_double, rt.base_price_double * COALESCE(ps.price_multiplier, 1.0), rt.base_price_double) >= p_min_price
             END)
        AND (p_max_price IS NULL OR 
             CASE 
                WHEN p_adults = 1 AND p_children = 0 THEN 
                    COALESCE(ph.final_price_single, rt.base_price_single * COALESCE(ps.price_multiplier, 1.0), rt.base_price_single) <= p_max_price
                ELSE 
                    COALESCE(ph.final_price_double, rt.base_price_double * COALESCE(ps.price_multiplier, 1.0), rt.base_price_double) <= p_max_price
             END)
        AND (p_floor_preference IS NULL OR f.floor_number IS NULL OR
             CASE p_floor_preference
                 WHEN 'low' THEN f.floor_number BETWEEN 1 AND 10
                 WHEN 'mid' THEN f.floor_number BETWEEN 11 AND 20
                 WHEN 'high' THEN f.floor_number BETWEEN 21 AND 34
                 ELSE TRUE
             END)
    
    GROUP BY rt.type_id, rt.type_name, rt.description, rt.max_occupancy, rt.standard_occupancy,
             rt.area_sqm, rt.view_type, rt.total_rooms_planned,
             rt.base_price_single, rt.base_price_double, rt.surcharge_adult, rt.surcharge_child_6_12,
             ps.price_multiplier, ph.final_price_single, ph.final_price_double
    
    -- ===== SẮP XẾP THEO MỨC ĐỘ PHÙ HỢP =====
    ORDER BY 
        -- 1. Ưu tiên phòng có thể đặt được và có phòng trống
        CASE WHEN COUNT(CASE WHEN r.status = 'available' 
                        AND r.room_id NOT IN (
                            SELECT DISTINCT room_id FROM bookings 
                            WHERE booking_status IN ('confirmed', 'checked_in')
                              AND NOT (check_out_date <= p_checkin_date OR check_in_date >= p_checkout_date)
                        ) THEN 1 END) > 0 THEN 0 ELSE 1 END,
        
        -- 2. Sắp xếp theo search mode
        CASE p_search_mode
            WHEN 'cheapest' THEN 
                -- Tổng chi phí cuối cùng cho cheapest
                (CASE 
                    WHEN p_adults = 1 AND p_children = 0 THEN 
                        COALESCE(ph.final_price_single, rt.base_price_single * COALESCE(ps.price_multiplier, 1.0), rt.base_price_single)
                    ELSE 
                        COALESCE(ph.final_price_double, rt.base_price_double * COALESCE(ps.price_multiplier, 1.0), rt.base_price_double)
                END) * nights +
                (CASE 
                    WHEN total_guests <= rt.standard_occupancy THEN 0
                    WHEN total_guests <= rt.max_occupancy THEN 
                        (GREATEST(0, p_adults - rt.standard_occupancy) * 670000 +
                         p_children_6_12_with_bed * 335000 +
                         p_children_6_12_no_bed * 110000 +
                         p_children_under_6 * 0 +
                         p_extra_beds * 1080000) * nights
                    ELSE 
                        (GREATEST(0, p_adults - rt.standard_occupancy) * 670000 +
                         p_children_6_12_with_bed * 335000 +
                         p_children_6_12_no_bed * 110000 +
                         p_children_under_6 * 0 +
                         p_extra_beds * 1080000 + 
                         GREATEST(0, total_guests - rt.max_occupancy) * 100000) * nights
                END)
            WHEN 'luxury' THEN 
                -- Giá cao nhất lên đầu cho luxury
                -(CASE 
                    WHEN p_adults = 1 AND p_children = 0 THEN 
                        COALESCE(ph.final_price_single, rt.base_price_single * COALESCE(ps.price_multiplier, 1.0), rt.base_price_single)
                    ELSE 
                        COALESCE(ph.final_price_double, rt.base_price_double * COALESCE(ps.price_multiplier, 1.0), rt.base_price_double)
                END)
            ELSE 
                -- Optimal: Ưu tiên theo điểm phù hợp
                -(CASE 
                      WHEN total_guests <= rt.standard_occupancy THEN 100
                      WHEN total_guests <= rt.max_occupancy THEN 90
                      WHEN total_guests <= rt.max_occupancy + 2 THEN 70
                      ELSE GREATEST(50 - (total_guests - rt.max_occupancy) * 10, 10)
                  END)
        END,
        
        -- 3. Phụ: Sắp xếp theo tên loại phòng
        rt.type_name ASC;

END //

DELIMITER ;


-- =====================================================================
-- MẪU SEARCH THỰC TẾ MỚI - CÓ CHI TIẾT TUỔI TRẺ EM
-- =====================================================================

-- 1. Khách đơn lẻ (1 người) - Giá rẻ nhất
-- CALL SmartRoomSearch('2024-02-15', '2024-02-17', 1, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'cheapest');

-- 2. Cặp đôi (2 người lớn) - Tối ưu  
-- CALL SmartRoomSearch('2024-02-15', '2024-02-17', 2, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'optimal');

-- 3. Gia đình nhỏ (2 người lớn + 1 trẻ em 4 tuổi) - Miễn phí trẻ em
-- CALL SmartRoomSearch('2024-02-15', '2024-02-17', 2, 1, 1, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'optimal');

-- 4. Gia đình (2 người lớn + 1 trẻ em 8 tuổi không giường phụ) - Phụ thu 110k
-- CALL SmartRoomSearch('2024-02-15', '2024-02-17', 2, 1, 0, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'optimal');

-- 5. Gia đình (2 người lớn + 1 trẻ em 10 tuổi có giường phụ) - Phụ thu 335k + giường phụ 1.08M
-- CALL SmartRoomSearch('2024-02-15', '2024-02-17', 2, 1, 0, 0, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'optimal');

-- 6. Gia đình tiêu chuẩn (2 người lớn + 2 trẻ em: 1 em 5 tuổi + 1 em 9 tuổi) - Phụ thu 110k cho em 9 tuổi
-- CALL SmartRoomSearch('2024-02-15', '2024-02-17', 2, 2, 1, 1, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'optimal');

-- 7. Gia đình lớn (4 người lớn) - Phụ thu 2 người lớn x 670k
-- CALL SmartRoomSearch('2024-02-15', '2024-02-17', 4, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'optimal');

-- 8. Nhóm lớn (4 người lớn + 2 trẻ em 11 tuổi có giường) - Cần Presidential Suite  
-- CALL SmartRoomSearch('2024-02-15', '2024-02-17', 4, 2, 0, 0, 2, 2, NULL, NULL, NULL, NULL, NULL, NULL, 'optimal');

-- 9. Tìm phòng cao cấp (luxury mode)
-- CALL SmartRoomSearch('2024-02-15', '2024-02-17', 2, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 'luxury');

-- 10. Tìm phòng có view panorama
-- CALL SmartRoomSearch('2024-02-15', '2024-02-17', 2, 0, 0, 0, 0, 0, NULL, NULL, NULL, 'panorama', NULL, NULL, 'optimal');

-- 11. Tìm phòng theo ngân sách (1-3 triệu VND/đêm)
-- CALL SmartRoomSearch('2024-02-15', '2024-02-17', 2, 0, 0, 0, 0, 0, 1000000, 3000000, NULL, NULL, NULL, NULL, 'cheapest');

-- 12. Tìm phòng tầng cao
-- CALL SmartRoomSearch('2024-02-15', '2024-02-17', 2, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, 'high', NULL, 'optimal');

-- =====================================================================
-- CHÚ THÍCH VỀ LOGIC NGHIỆP VỤ
-- =====================================================================

/*
LOGIC SỨC CHỨA VÀ PHỤ THU MỚI:

1. SỨC CHỨA TIÊU CHUẨN:
   - Hầu hết phòng: 2 người lớn (không phụ thu)
   - Presidential Suite: 4 người lớn (không phụ thu)

2. SỨC CHỨA TỐI ĐA:
   - Phòng thường: 4 người (2 người lớn + 2 trẻ em)
   - Presidential Suite: 10 người (4 người lớn + 6 trẻ em)

3. PHỤ THU MỚI (Cập nhật 2025):
   - Người lớn/Trẻ em từ 12 tuổi trở lên: 670,000 VND/đêm/người
   - Trẻ em 6-12 tuổi có giường phụ: 335,000 VND/đêm/người
   - Trẻ em 6-12 tuổi không giường phụ: 110,000 VND/đêm/người
   - Trẻ em dưới 6 tuổi dùng chung giường: Miễn phí (0 VND)
   - Giường phụ: 1,080,000 VND/đêm

4. GIÁ ĐỘNG:
   - Single: Giá cho 1 người
   - Double: Giá cho 2+ người
   - Seasonal: Hệ số mùa x giá gốc
   - Price History: Giá đã được điều chỉnh cụ thể

5. TIỆN ÍCH AMENITIES:
   - Tiện ích chính: Các amenities quan trọng nhất của phòng
   - Tiện ích bổ sung: Các amenities khác
   - Tổng số tiện ích: Đếm tất cả amenities của room type

6. THAM SỐ MỚI:
   - p_children_under_6: Số trẻ em dưới 6 tuổi (miễn phí)
   - p_children_6_12_no_bed: Số trẻ em 6-12 tuổi không giường (110k)
   - p_children_6_12_with_bed: Số trẻ em 6-12 tuổi có giường (335k)
   - p_extra_beds: Số giường phụ cần thêm (1.08M)

7. ĐIỂM PHÙ HỢP:
   - 100: Hoàn hảo (trong sức chứa tiêu chuẩn)
   - 90: Rất tốt (trong sức chứa tối đa)
   - 70: Khá tốt (vượt 1-2 người)
   - 10-50: Có thể xem xét (vượt nhiều)
   - 0: Hết phòng

8. SEARCH MODES:
   - cheapest: Tổng chi phí thấp nhất lên đầu
   - luxury: Giá cao nhất lên đầu (phòng sang)
   - optimal: Điểm phù hợp cao nhất lên đầu (cân bằng)

9. CHI TIẾT PHỤ THU:
   - Hiển thị rõ ràng từng loại phụ thu
   - Tính toán chính xác theo độ tuổi và giường phụ
   - Trẻ em dưới 6 tuổi được ghi nhận là "MIỄN PHÍ"
*/


