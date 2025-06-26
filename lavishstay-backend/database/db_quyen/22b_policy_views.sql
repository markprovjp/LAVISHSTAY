-- ===== BUSINESS POLICIES VIEWS & REPORTS =====
-- Các view báo cáo cho business policies và real-world scenarios

-- View: Báo cáo đặt cọc và deposit policies  
CREATE OR REPLACE VIEW v_deposit_policy_report AS
SELECT 
    b.booking_id,
    b.guest_full_name,
    b.guest_email,
    b.check_in_date,
    b.total_nights,
    b.final_total,
    COALESCE(b.deposit_amount, 0) as deposit_amount,
    COALESCE(b.deposit_percentage, 30.00) as deposit_percentage,
    COALESCE(b.required_deposit_amount, b.final_total * 0.3) as required_deposit_amount,
    b.deposit_deadline,
    COALESCE(b.applied_deposit_policy, 'Chính sách mặc định') as applied_deposit_policy,
    b.booking_status,
    CASE 
        WHEN COALESCE(b.deposit_amount, 0) >= COALESCE(b.required_deposit_amount, b.final_total * 0.3) THEN 'Đủ cọc'
        WHEN b.deposit_deadline IS NOT NULL AND b.deposit_deadline < NOW() THEN 'Quá hạn'
        ELSE 'Thiếu cọc'
    END as deposit_status,
    CASE 
        WHEN COALESCE(b.required_deposit_amount, b.final_total * 0.3) > 0 THEN
            (COALESCE(b.deposit_amount, 0) / COALESCE(b.required_deposit_amount, b.final_total * 0.3)) * 100
        ELSE 0
    END as deposit_completion_percentage,
    ddp.policy_name,
    ddp.event_deposit_percentage,
    ddp.refund_policy,
    DATEDIFF(b.check_in_date, CURDATE()) as days_until_checkin
FROM bookings b
LEFT JOIN dynamic_deposit_policies ddp ON b.applied_deposit_policy = ddp.policy_name
WHERE b.booking_status IN ('pending', 'confirmed', 'partial_payment');

-- View: Báo cáo early/late checkout
CREATE OR REPLACE VIEW v_checkout_policy_report AS
SELECT 
    b.booking_id,
    b.guest_full_name,
    b.guest_email,
    b.check_in_date,
    b.check_out_date,
    b.early_checkout_date,
    COALESCE(b.early_checkout_fee, 0) as early_checkout_fee,
    COALESCE(b.early_checkout_refund, 0) as early_checkout_refund,
    b.late_checkout_time,
    COALESCE(b.late_checkout_fee, 0) as late_checkout_fee,
    b.booking_status,
    CASE 
        WHEN b.early_checkout_date IS NOT NULL THEN 'Early Checkout'
        WHEN b.late_checkout_time IS NOT NULL THEN 'Late Checkout'
        ELSE 'Normal Checkout'
    END as checkout_type,
    (COALESCE(b.early_checkout_fee, 0) + COALESCE(b.late_checkout_fee, 0)) as total_checkout_fees,
    DATEDIFF(b.check_out_date, b.check_in_date) as planned_nights,
    CASE 
        WHEN b.early_checkout_date IS NOT NULL THEN DATEDIFF(b.early_checkout_date, b.check_in_date)
        ELSE DATEDIFF(COALESCE(b.check_out_date, CURDATE()), b.check_in_date)
    END as actual_nights,
    -- Tính phần trăm thời gian lưu trú thực tế
    CASE 
        WHEN b.early_checkout_date IS NOT NULL AND DATEDIFF(b.check_out_date, b.check_in_date) > 0 THEN
            (DATEDIFF(b.early_checkout_date, b.check_in_date) / DATEDIFF(b.check_out_date, b.check_in_date)) * 100
        ELSE 100
    END as stay_percentage,
    -- Tác động tài chính
    CASE 
        WHEN b.early_checkout_date IS NOT NULL THEN
            COALESCE(b.early_checkout_refund, 0) - COALESCE(b.early_checkout_fee, 0)
        WHEN b.late_checkout_time IS NOT NULL THEN
            -COALESCE(b.late_checkout_fee, 0)
        ELSE 0
    END as financial_impact
FROM bookings b
WHERE b.booking_status IN ('checked_out', 'completed');

-- View: Báo cáo đổi phòng và room changes
CREATE OR REPLACE VIEW v_room_change_report AS
SELECT 
    b.booking_id,
    b.guest_full_name,
    b.guest_email,
    b.room_id as current_room_id,
    COALESCE(b.room_changes_count, 0) as room_changes_count,
    b.last_room_change_date,
    COALESCE(b.room_change_fee_total, 0) as room_change_fee_total,
    bcl.log_id,
    bcl.change_type,
    bcl.old_values,
    bcl.new_values,
    bcl.change_reason,
    bcl.financial_impact_amount,
    bcl.fee_applied,
    bcl.processing_time,
    bcl.change_source,
    bcl.policy_applied,
    u.full_name as processed_by_name,
    u2.full_name as approved_by_name,
    -- Phân tích impact
    CASE 
        WHEN bcl.financial_impact_amount > 0 THEN 'Phí thêm'
        WHEN bcl.financial_impact_amount < 0 THEN 'Hoàn tiền'
        ELSE 'Không phí'
    END as financial_impact_type,
    -- Tính thời gian xử lý
    CASE 
        WHEN bcl.change_source = 'guest_request' AND bcl.processing_time IS NOT NULL THEN
            TIMESTAMPDIFF(MINUTE, bcl.created_at, bcl.processing_time)
        ELSE NULL
    END as processing_time_minutes
FROM bookings b
JOIN booking_changes_log bcl ON b.booking_id = bcl.booking_id
LEFT JOIN users u ON bcl.processed_by = u.user_id
LEFT JOIN users u2 ON bcl.approved_by = u2.user_id
WHERE bcl.change_type IN ('room_change', 'upgrade', 'downgrade')
ORDER BY bcl.processing_time DESC;

-- View: Báo cáo cooldown tracking
CREATE OR REPLACE VIEW v_guest_cooldown_report AS
SELECT 
    gct.guest_identifier,
    gct.guest_id_number,
    gct.last_checkout_date,
    gct.last_room_id,
    gct.cooldown_end_date,
    gct.cooldown_type,
    gct.total_stays,
    gct.total_cancellations,
    gct.total_no_shows,
    gct.loyalty_level,
    gct.is_cooldown_active,
    gct.exemption_granted,
    gct.exemption_reason,
    CASE 
        WHEN gct.exemption_granted = TRUE THEN 'Miễn cooldown'
        WHEN gct.is_cooldown_active = FALSE THEN 'Có thể đặt phòng'
        WHEN gct.cooldown_end_date <= CURDATE() THEN 'Cooldown hết hạn'
        WHEN gct.cooldown_end_date IS NULL THEN 'Không cooldown'
        ELSE CONCAT('Còn ', DATEDIFF(gct.cooldown_end_date, CURDATE()), ' ngày')
    END as cooldown_status,
    r.room_number as last_room_number,
    rt.type_name as last_room_type,
    -- Tính toán risk score chi tiết
    CASE 
        WHEN gct.total_no_shows > 2 THEN 'High Risk'
        WHEN gct.total_cancellations > 3 THEN 'Medium Risk'
        WHEN (gct.total_stays > 0 AND (gct.total_no_shows + gct.total_cancellations) / gct.total_stays > 0.3) THEN 'Medium Risk'
        WHEN gct.total_stays < 2 THEN 'New Guest'
        WHEN gct.loyalty_level IN ('gold', 'platinum', 'diamond') THEN 'VIP'
        ELSE 'Low Risk'
    END as guest_risk_level,
    -- Reliability score (0-100)
    CASE 
        WHEN gct.total_stays = 0 THEN 50
        ELSE GREATEST(0, 100 - ((gct.total_no_shows * 30 + gct.total_cancellations * 20) / gct.total_stays))
    END as reliability_score,
    DATEDIFF(CURDATE(), gct.last_checkout_date) as days_since_last_stay
FROM guest_cooldown_tracking gct
LEFT JOIN rooms r ON gct.last_room_id = r.room_id
LEFT JOIN room_types rt ON r.room_type_id = rt.room_type_id
ORDER BY gct.cooldown_end_date DESC, gct.reliability_score ASC;

-- View: Báo cáo emergency overrides
CREATE OR REPLACE VIEW v_emergency_override_report AS
SELECT 
    eo.override_id,
    eo.booking_id,
    eo.override_type,
    eo.original_rule,
    eo.override_reason,
    eo.emergency_level,
    eo.status,
    eo.valid_from,
    eo.valid_until,
    u1.full_name as requested_by_name,
    u2.full_name as approved_by_name,
    eo.approval_timestamp,
    eo.customer_impact_description,
    eo.business_justification,
    b.guest_full_name,
    b.guest_email,
    CASE 
        WHEN eo.status = 'pending' THEN 'Chờ phê duyệt'
        WHEN eo.status = 'approved' AND eo.valid_until > NOW() THEN 'Đang hiệu lực'
        WHEN eo.status = 'approved' AND eo.valid_until <= NOW() THEN 'Hết hạn'
        WHEN eo.status = 'rejected' THEN 'Đã từ chối'
        ELSE 'Không xác định'
    END as override_status_text,
    -- Thời gian xử lý
    CASE 
        WHEN eo.approval_timestamp IS NOT NULL THEN
            TIMESTAMPDIFF(MINUTE, eo.created_at, eo.approval_timestamp)
        ELSE NULL
    END as approval_time_minutes,
    -- Thời gian còn lại
    CASE 
        WHEN eo.status = 'approved' AND eo.valid_until > NOW() THEN
            TIMESTAMPDIFF(HOUR, NOW(), eo.valid_until)
        ELSE 0
    END as hours_remaining,
    -- Priority score
    CASE eo.emergency_level
        WHEN 'critical' THEN 4
        WHEN 'high' THEN 3
        WHEN 'medium' THEN 2
        WHEN 'low' THEN 1
        ELSE 0
    END as priority_score
FROM emergency_overrides eo
LEFT JOIN users u1 ON eo.requested_by = u1.user_id
LEFT JOIN users u2 ON eo.approved_by = u2.user_id
LEFT JOIN bookings b ON eo.booking_id = b.booking_id
ORDER BY priority_score DESC, eo.created_at DESC;

-- View: Báo cáo tổng hợp booking changes
CREATE OR REPLACE VIEW v_booking_changes_summary AS
SELECT 
    bcl.booking_id,
    b.guest_full_name,
    b.guest_email,
    b.check_in_date,
    b.check_out_date,
    b.booking_status,
    COUNT(bcl.log_id) as total_changes,
    GROUP_CONCAT(DISTINCT bcl.change_type ORDER BY bcl.processing_time) as change_types,
    SUM(bcl.financial_impact_amount) as total_financial_impact,
    SUM(bcl.fee_applied) as total_fees_applied,
    SUM(COALESCE(bcl.refund_amount, 0)) as total_refunds,
    SUM(COALESCE(bcl.additional_charge, 0)) as total_additional_charges,
    MAX(bcl.processing_time) as last_change_time,
    MIN(bcl.processing_time) as first_change_time,
    COUNT(DISTINCT bcl.processed_by) as staff_involved_count,
    COUNT(DISTINCT bcl.change_source) as change_source_count,
    -- Phân loại complexity
    CASE 
        WHEN COUNT(bcl.log_id) > 5 THEN 'High Complexity'
        WHEN COUNT(bcl.log_id) > 2 THEN 'Medium Complexity'
        ELSE 'Low Complexity'
    END as change_complexity,
    -- Customer satisfaction risk
    CASE 
        WHEN SUM(bcl.financial_impact_amount) > 1000000 THEN 'High Risk'
        WHEN COUNT(bcl.log_id) > 3 THEN 'Medium Risk'
        ELSE 'Low Risk'
    END as customer_satisfaction_risk,
    -- Net financial impact
    (SUM(bcl.financial_impact_amount) + SUM(bcl.fee_applied) - SUM(COALESCE(bcl.refund_amount, 0))) as net_financial_impact
FROM booking_changes_log bcl
JOIN bookings b ON bcl.booking_id = b.booking_id
GROUP BY bcl.booking_id, b.guest_full_name, b.guest_email, b.check_in_date, b.check_out_date, b.booking_status
ORDER BY total_changes DESC, net_financial_impact DESC;

-- View: Policy effectiveness analysis
CREATE OR REPLACE VIEW v_policy_effectiveness AS
SELECT 
    'Deposit Policies' as policy_category,
    ddp.policy_name,
    ddp.policy_type,
    COUNT(b.booking_id) as applications_count,
    AVG(COALESCE(b.deposit_percentage, 30.00)) as avg_deposit_percentage,
    SUM(COALESCE(b.deposit_amount, 0)) as total_deposits_collected,
    COUNT(CASE WHEN b.booking_status = 'cancelled' THEN 1 END) as cancellations_count,
    CASE 
        WHEN COUNT(b.booking_id) > 0 THEN
            (COUNT(CASE WHEN b.booking_status = 'cancelled' THEN 1 END) / COUNT(b.booking_id)) * 100
        ELSE 0
    END as cancellation_rate,
    AVG(b.final_total) as avg_booking_value,
    COUNT(CASE WHEN COALESCE(b.deposit_amount, 0) >= COALESCE(b.required_deposit_amount, b.final_total * 0.3) THEN 1 END) as full_deposits_count,
    ddp.is_active,
    ddp.created_at as policy_created_date
FROM dynamic_deposit_policies ddp
LEFT JOIN bookings b ON ddp.policy_name = b.applied_deposit_policy
WHERE ddp.is_active = TRUE
GROUP BY ddp.policy_id, ddp.policy_name, ddp.policy_type, ddp.is_active, ddp.created_at

UNION ALL

SELECT 
    'Checkout Policies' as policy_category,
    crp.policy_name,
    crp.policy_type,
    COUNT(bcl.log_id) as applications_count,
    AVG(COALESCE(bcl.fee_applied, 0)) as avg_fee_applied,
    SUM(COALESCE(bcl.fee_applied, 0)) as total_fees_collected,
    COUNT(CASE WHEN bcl.change_source = 'guest_request' THEN 1 END) as guest_initiated_count,
    CASE 
        WHEN COUNT(bcl.log_id) > 0 THEN
            (COUNT(CASE WHEN bcl.change_source = 'guest_request' THEN 1 END) / COUNT(bcl.log_id)) * 100
        ELSE 0
    END as guest_initiated_rate,
    AVG(CASE WHEN bcl.change_type IN ('early_checkout', 'late_checkout') THEN 
            ABS(COALESCE(bcl.financial_impact_amount, 0)) ELSE NULL END) as avg_impact_amount,
    COUNT(CASE WHEN bcl.change_type = 'early_checkout' THEN 1 END) as early_checkout_count,
    crp.is_active,
    crp.created_at as policy_created_date
FROM checkout_refund_policies crp
LEFT JOIN booking_changes_log bcl ON bcl.policy_applied = crp.policy_name
WHERE crp.is_active = TRUE
GROUP BY crp.policy_id, crp.policy_name, crp.policy_type, crp.is_active, crp.created_at

UNION ALL

SELECT 
    'Room Change Policies' as policy_category,
    rcp.policy_name,
    rcp.change_type,
    COUNT(bcl.log_id) as applications_count,
    AVG(COALESCE(bcl.fee_applied, 0)) as avg_fee_applied,
    SUM(COALESCE(bcl.fee_applied, 0)) as total_fees_collected,
    COUNT(CASE WHEN bcl.change_source = 'guest_request' THEN 1 END) as guest_initiated_count,
    CASE 
        WHEN COUNT(bcl.log_id) > 0 THEN
            (COUNT(CASE WHEN bcl.change_source = 'guest_request' THEN 1 END) / COUNT(bcl.log_id)) * 100
        ELSE 0
    END as guest_initiated_rate,
    AVG(COALESCE(bcl.financial_impact_amount, 0)) as avg_impact_amount,
    COUNT(CASE WHEN bcl.approved_by IS NOT NULL THEN 1 END) as manager_approved_count,
    rcp.is_active,
    rcp.created_at as policy_created_date
FROM room_change_policies rcp
LEFT JOIN booking_changes_log bcl ON bcl.policy_applied = rcp.policy_name 
    AND bcl.change_type = rcp.change_type
WHERE rcp.is_active = TRUE
GROUP BY rcp.policy_id, rcp.policy_name, rcp.change_type, rcp.is_active, rcp.created_at;

-- View: Guest behavior analysis
CREATE OR REPLACE VIEW v_guest_behavior_analysis AS
SELECT 
    gct.guest_identifier,
    gct.guest_id_number,
    gct.loyalty_level,
    gct.total_stays,
    gct.total_cancellations,
    gct.total_no_shows,
    gct.last_checkout_date,
    gct.is_cooldown_active,
    
    -- Tính toán metrics với validation
    CASE 
        WHEN gct.total_stays > 0 THEN ROUND((gct.total_cancellations / gct.total_stays) * 100, 2)
        ELSE 0 
    END as cancellation_rate,
    
    CASE 
        WHEN gct.total_stays > 0 THEN ROUND((gct.total_no_shows / gct.total_stays) * 100, 2)
        ELSE 0 
    END as no_show_rate,
    
    -- Booking patterns từ bookings
    COUNT(b.booking_id) as total_bookings,
    ROUND(AVG(COALESCE(b.final_total, 0)), 0) as avg_booking_value,
    SUM(COALESCE(b.final_total, 0)) as total_revenue,
    ROUND(AVG(COALESCE(b.total_nights, 1)), 1) as avg_stay_duration,
    
    -- Change patterns từ booking_changes_log
    COALESCE(changes.total_changes, 0) as total_changes_made,
    COALESCE(changes.total_change_fees, 0) as total_change_fees_paid,
    COALESCE(changes.avg_processing_time, 0) as avg_change_processing_time,
    
    -- Advanced behavior metrics
    DATEDIFF(CURDATE(), MAX(b.check_out_date)) as days_since_last_booking,
    COUNT(CASE WHEN b.booking_status = 'completed' THEN 1 END) as successful_stays,
    COUNT(CASE WHEN b.check_in_date > CURDATE() THEN 1 END) as future_bookings,
    
    -- Risk categorization enhanced
    CASE 
        WHEN gct.total_no_shows > 2 OR (gct.total_stays > 0 AND (gct.total_no_shows / gct.total_stays) > 0.2) THEN 'High Risk'
        WHEN gct.total_cancellations > 3 OR (gct.total_stays > 0 AND (gct.total_cancellations / gct.total_stays) > 0.3) THEN 'Medium Risk'
        WHEN (gct.total_stays + COUNT(b.booking_id)) < 2 THEN 'New Guest'
        WHEN gct.loyalty_level IN ('gold', 'platinum', 'diamond') THEN 'VIP'
        WHEN COUNT(CASE WHEN b.booking_status = 'completed' THEN 1 END) >= 5 THEN 'Reliable'
        ELSE 'Low Risk'
    END as guest_risk_category,
    
    -- Value categorization enhanced
    CASE 
        WHEN SUM(COALESCE(b.final_total, 0)) > 50000000 THEN 'High Value'
        WHEN SUM(COALESCE(b.final_total, 0)) > 20000000 THEN 'Medium Value'
        WHEN SUM(COALESCE(b.final_total, 0)) > 5000000 THEN 'Regular Value'
        WHEN COUNT(b.booking_id) = 0 THEN 'No Revenue'
        ELSE 'Low Value'
    END as guest_value_category,
    
    -- Loyalty progression potential
    CASE 
        WHEN gct.loyalty_level = 'diamond' THEN 'Max Level'
        WHEN COUNT(b.booking_id) >= 10 AND SUM(COALESCE(b.final_total, 0)) > 30000000 THEN 'Diamond Potential'
        WHEN COUNT(b.booking_id) >= 5 AND SUM(COALESCE(b.final_total, 0)) > 15000000 THEN 'Platinum Potential'
        WHEN COUNT(b.booking_id) >= 3 THEN 'Gold Potential'
        ELSE 'Member Level'
    END as loyalty_potential

FROM guest_cooldown_tracking gct
LEFT JOIN bookings b ON gct.guest_identifier = b.guest_email
LEFT JOIN (
    SELECT 
        b2.guest_email,
        COUNT(bcl2.log_id) as total_changes,
        SUM(COALESCE(bcl2.fee_applied, 0)) as total_change_fees,
        AVG(TIMESTAMPDIFF(MINUTE, bcl2.created_at, bcl2.processing_time)) as avg_processing_time
    FROM bookings b2
    JOIN booking_changes_log bcl2 ON b2.booking_id = bcl2.booking_id
    WHERE bcl2.processing_time IS NOT NULL
    GROUP BY b2.guest_email
) changes ON gct.guest_identifier = changes.guest_email

GROUP BY 
    gct.tracking_id, gct.guest_identifier, gct.guest_id_number, gct.loyalty_level,
    gct.total_stays, gct.total_cancellations, gct.total_no_shows, gct.last_checkout_date, gct.is_cooldown_active

ORDER BY total_revenue DESC, guest_risk_category, loyalty_potential DESC;

-- View: Daily operations dashboard
CREATE OR REPLACE VIEW v_daily_operations_dashboard AS
SELECT 
    CURDATE() as report_date,
    NOW() as last_updated,
    
    -- Checkout summary với validation
    COUNT(CASE WHEN b.booking_status = 'checked_out' AND DATE(b.updated_at) = CURDATE() THEN 1 END) as checkouts_today,
    COUNT(CASE WHEN b.early_checkout_date = CURDATE() THEN 1 END) as early_checkouts_today,
    COUNT(CASE WHEN b.late_checkout_time IS NOT NULL AND DATE(b.updated_at) = CURDATE() THEN 1 END) as late_checkouts_today,
    
    -- Fees collected với COALESCE
    SUM(CASE WHEN DATE(b.updated_at) = CURDATE() THEN COALESCE(b.early_checkout_fee, 0) ELSE 0 END) as early_checkout_fees_today,
    SUM(CASE WHEN DATE(b.updated_at) = CURDATE() THEN COALESCE(b.late_checkout_fee, 0) ELSE 0 END) as late_checkout_fees_today,
    
    -- Changes today
    (SELECT COUNT(*) FROM booking_changes_log WHERE DATE(processing_time) = CURDATE()) as booking_changes_today,
    (SELECT COALESCE(SUM(fee_applied), 0) FROM booking_changes_log WHERE DATE(processing_time) = CURDATE()) as change_fees_today,
    
    -- Emergency overrides 
    (SELECT COUNT(*) FROM emergency_overrides WHERE DATE(created_at) = CURDATE()) as emergency_overrides_today,
    (SELECT COUNT(*) FROM emergency_overrides WHERE status = 'pending') as pending_overrides,
    (SELECT COUNT(*) FROM emergency_overrides WHERE status = 'pending' AND emergency_level IN ('high', 'critical')) as urgent_pending_overrides,
    
    -- Cooldown status
    (SELECT COUNT(*) FROM guest_cooldown_tracking WHERE is_cooldown_active = TRUE) as active_cooldowns,
    (SELECT COUNT(*) FROM guest_cooldown_tracking WHERE cooldown_end_date = CURDATE()) as cooldowns_expiring_today,
    
    -- Deposit tracking
    COUNT(CASE WHEN b.booking_status IN ('pending', 'confirmed') 
                   AND COALESCE(b.deposit_amount, 0) < COALESCE(b.required_deposit_amount, b.final_total * 0.3) THEN 1 END) as pending_deposits,
    COUNT(CASE WHEN b.deposit_deadline IS NOT NULL AND b.deposit_deadline < NOW() 
                   AND b.booking_status = 'pending' THEN 1 END) as overdue_deposits,
    
    -- Financial summary
    (SELECT COALESCE(SUM(fee_applied), 0) FROM booking_changes_log WHERE DATE(processing_time) = CURDATE()) +
    SUM(CASE WHEN DATE(b.updated_at) = CURDATE() THEN COALESCE(b.early_checkout_fee, 0) + COALESCE(b.late_checkout_fee, 0) ELSE 0 END) 
    as total_policy_fees_today,
    
    -- Operational metrics
    COUNT(CASE WHEN b.check_in_date = CURDATE() AND b.booking_status = 'confirmed' THEN 1 END) as expected_checkins_today,
    COUNT(CASE WHEN b.check_out_date = CURDATE() AND b.booking_status = 'checked_in' THEN 1 END) as expected_checkouts_today,
    
    -- System health indicators
    CASE 
        WHEN (SELECT COUNT(*) FROM emergency_overrides WHERE status = 'pending' AND emergency_level = 'critical') > 0 THEN 'CRITICAL'
        WHEN (SELECT COUNT(*) FROM emergency_overrides WHERE status = 'pending' AND emergency_level = 'high') > 2 THEN 'WARNING'
        WHEN COUNT(CASE WHEN b.deposit_deadline < NOW() AND b.booking_status = 'pending' THEN 1 END) > 5 THEN 'WARNING'
        ELSE 'HEALTHY'
    END as system_status

FROM bookings b;

-- Indexes cho performance optimization với IF NOT EXISTS
CREATE INDEX IF NOT EXISTS idx_bookings_deposit_policy ON bookings(applied_deposit_policy);
CREATE INDEX IF NOT EXISTS idx_bookings_checkout_dates ON bookings(early_checkout_date, late_checkout_time);
CREATE INDEX IF NOT EXISTS idx_bookings_status_updated ON bookings(booking_status, updated_at);
CREATE INDEX IF NOT EXISTS idx_bookings_deposit_deadline ON bookings(deposit_deadline, booking_status);
CREATE INDEX IF NOT EXISTS idx_booking_changes_processing_time ON booking_changes_log(processing_time);
CREATE INDEX IF NOT EXISTS idx_booking_changes_source_type ON booking_changes_log(change_source, change_type);
CREATE INDEX IF NOT EXISTS idx_guest_cooldown_active_end ON guest_cooldown_tracking(is_cooldown_active, cooldown_end_date);
CREATE INDEX IF NOT EXISTS idx_guest_cooldown_identifier_active ON guest_cooldown_tracking(guest_identifier, is_cooldown_active);
CREATE INDEX IF NOT EXISTS idx_emergency_overrides_status_valid ON emergency_overrides(status, valid_until);
CREATE INDEX IF NOT EXISTS idx_emergency_overrides_level_status ON emergency_overrides(emergency_level, status);

-- Thêm views performance monitoring
CREATE OR REPLACE VIEW v_system_performance_metrics AS
SELECT 
    'booking_changes_log' as table_name,
    COUNT(*) as total_records,
    COUNT(CASE WHEN DATE(processing_time) = CURDATE() THEN 1 END) as today_records,
    AVG(TIMESTAMPDIFF(SECOND, created_at, processing_time)) as avg_processing_seconds,
    MAX(processing_time) as last_activity
FROM booking_changes_log

UNION ALL

SELECT 
    'emergency_overrides' as table_name,
    COUNT(*) as total_records,
    COUNT(CASE WHEN DATE(created_at) = CURDATE() THEN 1 END) as today_records,
    AVG(CASE WHEN approval_timestamp IS NOT NULL THEN 
        TIMESTAMPDIFF(MINUTE, created_at, approval_timestamp) ELSE NULL END) as avg_processing_seconds,
    MAX(COALESCE(approval_timestamp, created_at)) as last_activity
FROM emergency_overrides

UNION ALL

SELECT 
    'guest_cooldown_tracking' as table_name,
    COUNT(*) as total_records,
    COUNT(CASE WHEN DATE(updated_at) = CURDATE() THEN 1 END) as today_records,
    NULL as avg_processing_seconds,
    MAX(updated_at) as last_activity
FROM guest_cooldown_tracking;
