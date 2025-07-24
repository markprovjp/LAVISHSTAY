-- ===== OPERATIONS SCHEMA =====
-- Cấu trúc quản lý vận hành (check-in/out, thanh toán, hủy phòng, bảo trì)

-- Bảng check-in/check-out chi tiết
CREATE TABLE check_inout (
    checkin_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã check-in/out',
    booking_id INT NOT NULL COMMENT 'Mã đặt phòng',
    
    -- Thời gian check-in/out
    actual_checkin_time TIMESTAMP COMMENT 'Thời gian nhận phòng thực tế',
    actual_checkout_time TIMESTAMP COMMENT 'Thời gian trả phòng thực tế',
    
    -- Phí phụ thu thời gian
    early_checkin_fee BIGINT DEFAULT 0 COMMENT 'Phí nhận phòng sớm (VND)',
    late_checkout_fee BIGINT DEFAULT 0 COMMENT 'Phí trả phòng muộn (VND)',
    
    -- Nhân viên xử lý
    staff_checkin_id INT COMMENT 'Nhân viên lễ tân xử lý check-in',
    staff_checkout_id INT COMMENT 'Nhân viên lễ tân xử lý check-out',
    
    -- Xác minh giấy tờ
    identity_verified BOOLEAN DEFAULT FALSE COMMENT 'Đã xác minh giấy tờ tùy thân',
    identity_document_type ENUM('cccd', 'cmnd', 'passport', 'other') COMMENT 'Loại giấy tờ: CCCD, CMND, hộ chiếu',
    identity_document_number VARCHAR(50) COMMENT 'Số giấy tờ tùy thân',
    marriage_certificate_required BOOLEAN DEFAULT FALSE COMMENT 'Yêu cầu giấy đăng ký kết hôn',
    marriage_certificate_verified BOOLEAN DEFAULT FALSE COMMENT 'Đã xác minh giấy đăng ký kết hôn',
    
    -- Tiền cọc và chìa khóa
    security_deposit_collected BIGINT DEFAULT 0 COMMENT 'Tiền cọc bảo đảm thu được (VND)',
    room_key_type ENUM('physical_key', 'key_card', 'mobile_key') DEFAULT 'key_card' COMMENT 'Loại chìa khóa: vật lý, thẻ từ, mobile',
    -- Giải thích các loại chìa khóa:
    -- physical_key: Chìa khóa vật lý truyền thống
    -- key_card: Thẻ từ (thẻ từ khách sạn)
    -- mobile_key: Chìa khóa di động (sử dụng ứng dụng trên điện thoại)
    room_key_number VARCHAR(20) COMMENT 'Số chìa khóa/thẻ từ',
    
    -- XỬ LÝ MẤT CHÌA KHÓA/THẺ TỪ
    key_replacement_count INT DEFAULT 0 COMMENT 'Số lần thay thế chìa khóa/thẻ',
    key_replacement_fee BIGINT DEFAULT 0 COMMENT 'Phí thay thế chìa khóa/thẻ (VND)',
    key_replacement_notes TEXT COMMENT 'Ghi chú về việc thay thế chìa khóa',
    
    -- Dịch vụ hỗ trợ
    luggage_assistance_provided BOOLEAN DEFAULT FALSE COMMENT 'Đã hỗ trợ hành lý lên phòng',
    room_orientation_given BOOLEAN DEFAULT FALSE COMMENT 'Đã hướng dẫn tiện ích khách sạn',
    
    -- Kiểm tra phòng
    room_condition_checkin TEXT COMMENT 'Tình trạng phòng khi nhận (JSON/text)',
    room_condition_checkout TEXT COMMENT 'Tình trạng phòng khi trả (JSON/text)',
    room_damage_charges BIGINT DEFAULT 0 COMMENT 'Phí bồi thường hư hỏng (VND)',
    
    -- XỬ LÝ HƯ HỎNG PHÒNG CHI TIẾT
    damage_reported BOOLEAN DEFAULT FALSE COMMENT 'Có báo cáo hư hỏng không',
    damage_description TEXT COMMENT 'Mô tả chi tiết hư hỏng',
    damage_photos TEXT COMMENT 'Đường dẫn ảnh chụp hư hỏng (JSON array)',
    damage_assessment_completed BOOLEAN DEFAULT FALSE COMMENT 'Đã đánh giá hư hỏng',
    damage_repair_cost BIGINT DEFAULT 0 COMMENT 'Chi phí sửa chữa ước tính (VND)',
    damage_guest_liable BOOLEAN DEFAULT FALSE COMMENT 'Khách hàng có trách nhiệm bồi thường',
    
    -- Ghi chú
    checkin_notes TEXT COMMENT 'Ghi chú check-in',
    checkout_notes TEXT COMMENT 'Ghi chú check-out',
    
    -- Timestamps
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối',
    
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id),
    FOREIGN KEY (staff_checkin_id) REFERENCES users(user_id),
    FOREIGN KEY (staff_checkout_id) REFERENCES users(user_id)
) COMMENT 'Bảng quản lý check-in/check-out chi tiết';

-- Bảng thanh toán
CREATE TABLE payments (
    payment_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã thanh toán',
    booking_id INT NOT NULL COMMENT 'Mã đặt phòng',
    payment_type ENUM('deposit', 'partial_payment', 'full_payment', 'refund', 'extra_charges', 'damage_compensation') COMMENT 'Loại thanh toán',
    -- Giải thích các loại thanh toán:
    -- deposit: Tiền cọc đặt phòng
    -- partial_payment: Thanh toán một phần (trước hoặc sau check-in)
    -- full_payment: Thanh toán toàn bộ tiền phòng
    -- refund: Hoàn tiền (hủy phòng, trả lại tiền cọc)
    -- extra_charges: Thanh toán các chi phí phát sinh (minibar, dịch vụ
    -- damage_compensation: Bồi thường hư hỏng phòng
    payment_amount BIGINT NOT NULL COMMENT 'Số tiền thanh toán (VND)',
    payment_method ENUM('cash', 'bank_transfer', 'credit_card', 'debit_card', 'vietqr', 'vnpay', 'momo', 'zalopay') COMMENT 'Phương thức thanh toán',
    -- Giải thích các phương thức thanh toán:
    -- cash: Tiền mặt
    -- bank_transfer: Chuyển khoản ngân hàng
    -- credit_card: Thẻ tín dụng
    -- debit_card: Thẻ ghi nợ
    -- vietqr: Thanh toán qua VietQR
    -- vnpay: Thanh toán qua VNPay
    -- momo: Thanh toán qua ví Momo
    -- zalopay: Thanh toán qua ZaloPay
    payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày giờ thanh toán',
    transaction_reference VARCHAR(100) COMMENT 'Mã tham chiếu giao dịch',
    gateway_transaction_id VARCHAR(100) COMMENT 'Mã giao dịch từ cổng thanh toán',
    payment_status ENUM('pending', 'processing', 'completed', 'failed', 'refunded', 'cancelled') DEFAULT 'pending' COMMENT 'Trạng thái thanh toán',
    -- Giải thích các trạng thái thanh toán:
    -- pending: Chờ xử lý (chưa thanh toán)
    -- processing: Đang xử lý (đã thanh toán nhưng chưa xác nhận)
    -- completed: Đã hoàn tất (thanh toán thành công)
    -- failed: Thanh toán thất bại (do lỗi kỹ thuật, không đủ tiền)
    -- refunded: Đã hoàn tiền (hoàn tiền thành công)
    -- cancelled: Đã hủy (không thực hiện thanh toán)

    staff_processed_by INT COMMENT 'Nhân viên xử lý thanh toán',
    vat_invoice_issued BOOLEAN DEFAULT FALSE COMMENT 'Đã xuất hóa đơn VAT',
    vat_invoice_number VARCHAR(50) COMMENT 'Số hóa đơn VAT',
    payment_notes TEXT COMMENT 'Ghi chú thanh toán',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối',
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id),
    FOREIGN KEY (staff_processed_by) REFERENCES users(user_id)
) COMMENT 'Bảng quản lý thanh toán';

-- Bảng hủy đặt phòng
CREATE TABLE booking_cancellations (
    cancellation_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã hủy đặt phòng',
    booking_id INT NOT NULL COMMENT 'Mã đặt phòng bị hủy',
    cancellation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày giờ hủy đặt phòng',
    cancellation_reason TEXT COMMENT 'Lý do hủy đặt phòng',
    cancelled_by_guest BOOLEAN DEFAULT TRUE COMMENT 'Hủy bởi khách (TRUE) hay khách sạn (FALSE)',
    cancellation_policy_applied VARCHAR(100) COMMENT 'Chính sách hủy được áp dụng',
    penalty_percentage DECIMAL(5,2) DEFAULT 0 COMMENT 'Phần trăm phí phạt (%)',
    penalty_amount BIGINT DEFAULT 0 COMMENT 'Số tiền phí phạt (VND)',
    refund_amount BIGINT DEFAULT 0 COMMENT 'Số tiền được hoàn lại (VND)',
    refund_method ENUM('cash', 'bank_transfer', 'credit_card', 'original_method', 'voucher') COMMENT 'Phương thức hoàn tiền',
    -- Giải thích các phương thức hoàn tiền:
    -- cash: Hoàn tiền bằng tiền mặt
    -- bank_transfer: Hoàn tiền qua chuyển khoản ngân hàng
    -- credit_card: Hoàn tiền vào thẻ tín dụng đã sử dụng
    -- original_method: Hoàn tiền theo phương thức ban đầu (nếu có)
    -- voucher: Cấp voucher hoàn tiền cho lần đặt phòng sau
    -- refund_status: Trạng thái hoàn tiền
    refund_status ENUM('pending', 'processing', 'completed', 'failed', 'not_applicable') DEFAULT 'pending' COMMENT 'Trạng thái hoàn tiền',
    -- Giải thích các trạng thái hoàn tiền:
    -- pending: Chờ xử lý (chưa hoàn tiền)
    -- processing: Đang xử lý (đã bắt đầu hoàn tiền)
    -- completed: Đã hoàn tiền thành công
    -- failed: Hoàn tiền thất bại (do lỗi kỹ thuật, không đủ tiền
    -- not_applicable: Không áp dụng hoàn tiền (ví dụ: hủy trước thời gian cho phép)
    refund_processed_date TIMESTAMP NULL COMMENT 'Ngày xử lý hoàn tiền',
    staff_processed_by INT COMMENT 'Nhân viên xử lý hủy đặt phòng',
    cancellation_notes TEXT COMMENT 'Ghi chú hủy đặt phòng',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối',
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id),
    FOREIGN KEY (staff_processed_by) REFERENCES users(user_id)
) COMMENT 'Bảng quản lý hủy đặt phòng';

-- Bảng bảo trì phòng
CREATE TABLE room_maintenance (
    maintenance_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã bảo trì',
    room_id INT NOT NULL COMMENT 'Mã phòng bảo trì',
    maintenance_type ENUM('cleaning', 'repair', 'upgrade', 'inspection', 'deep_cleaning') COMMENT 'Loại bảo trì',
    -- Giải thích các loại bảo trì:
    -- cleaning: Dọn dẹp thường xuyên
    -- repair: Sửa chữa các hư hỏng
    -- upgrade: Nâng cấp tiện nghi hoặc trang thiết bị
    -- inspection: Kiểm tra định kỳ
    -- deep_cleaning: Dọn dẹp sâu (ví dụ: sau khi khách trả phòng)
    description TEXT NOT NULL COMMENT 'Mô tả công việc bảo trì',
    start_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày bắt đầu bảo trì',
    end_date TIMESTAMP NULL COMMENT 'Ngày kết thúc bảo trì',
    status ENUM('scheduled', 'in_progress', 'completed', 'cancelled') DEFAULT 'scheduled' COMMENT 'Trạng thái bảo trì',
    -- Giải thích các trạng thái bảo trì:
    -- scheduled: Đã lên lịch bảo trì
    -- in_progress: Đang tiến hành bảo trì
    -- completed: Đã hoàn thành bảo trì
    -- cancelled: Bảo trì bị hủy
    assigned_to VARCHAR(100) COMMENT 'Người/đội phụ trách',
    cost BIGINT DEFAULT 0 COMMENT 'Chi phí bảo trì (VND)',
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium' COMMENT 'Độ ưu tiên',
    -- Giải thích các độ ưu tiên:
    -- low: Ưu tiên thấp (có thể trì hoãn)
    -- medium: Ưu tiên trung bình (cần xử lý trong thời gian tới )
    -- high: Ưu tiên cao (cần xử lý sớm)
    -- urgent: Ưu tiên khẩn cấp (cần xử lý ngay lập tức)
    notes TEXT COMMENT 'Ghi chú bảo trì',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối',
    FOREIGN KEY (room_id) REFERENCES rooms(room_id)
) COMMENT 'Bảng quản lý bảo trì phòng';

-- ===== BẢNG XỬ LÝ CÁC TRƯỜNG HỢP ĐẶC BIỆT =====

-- Bảng quản lý overbooking và giải pháp
CREATE TABLE overbooking_management (
    overbooking_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã overbooking',
    booking_id INT NOT NULL COMMENT 'Mã đặt phòng bị overbooking',
    overbooking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày phát hiện overbooking',
    overbooking_reason TEXT COMMENT 'Lý do gây ra overbooking',
    
    -- Giải pháp xử lý
    solution_type ENUM('room_upgrade', 'alternative_room', 'partner_hotel', 'compensation', 'reschedule') COMMENT 'Loại giải pháp',
    -- Giải thích các giải pháp:
    -- room_upgrade: Nâng cấp phòng miễn phí
    -- alternative_room: Chuyển sang phòng khác cùng hạng
    -- partner_hotel: Chuyển sang khách sạn đối tác
    -- compensation: Bồi thường tiền mặt
    -- reschedule: Dời lịch đặt phòng
    
    alternative_room_id INT COMMENT 'Mã phòng thay thế (nếu có)',
    upgrade_room_type_id INT COMMENT 'Mã loại phòng nâng cấp (nếu có)',
    partner_hotel_name VARCHAR(200) COMMENT 'Tên khách sạn đối tác (nếu có)',
    compensation_amount BIGINT DEFAULT 0 COMMENT 'Số tiền bồi thường (VND)',
    additional_benefits TEXT COMMENT 'Các lợi ích bổ sung (voucher, dịch vụ miễn phí)',
    
    -- Trạng thái xử lý
    resolution_status ENUM('pending', 'offered', 'accepted', 'declined', 'completed') DEFAULT 'pending' COMMENT 'Trạng thái giải quyết',
    -- Giải thích các trạng thái giải quyết:
    -- pending: Chưa xử lý
    -- offered: Đã đề xuất giải pháp
    -- accepted: Khách hàng đã đồng ý giải pháp
    -- declined: Khách hàng từ chối giải pháp
    -- completed: Giải pháp đã được thực hiện xong
    customer_acceptance BOOLEAN DEFAULT FALSE COMMENT 'Khách hàng đồng ý giải pháp',
    resolution_notes TEXT COMMENT 'Ghi chú về quá trình giải quyết',
    
    staff_handled_by INT COMMENT 'Nhân viên xử lý',
    manager_approved_by INT COMMENT 'Quản lý phê duyệt',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối',
    
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id),
    FOREIGN KEY (alternative_room_id) REFERENCES rooms(room_id),
    FOREIGN KEY (upgrade_room_type_id) REFERENCES room_types(type_id),
    FOREIGN KEY (staff_handled_by) REFERENCES users(user_id),
    FOREIGN KEY (manager_approved_by) REFERENCES users(user_id)
) COMMENT 'Bảng quản lý overbooking và giải pháp xử lý';

-- Bảng quản lý no-show và xử lý
CREATE TABLE no_show_management (
    no_show_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã no-show (khách không đến)',
    booking_id INT NOT NULL COMMENT 'Mã đặt phòng no-show',
    expected_checkin_time TIMESTAMP NOT NULL COMMENT 'Thời gian check-in dự kiến',
    no_show_declared_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tuyên bố no-show',
    grace_period_hours INT DEFAULT 6 COMMENT 'Thời gian gia hạn chờ đợi (giờ)',
    
    -- Xử lý phí
    no_show_fee_applied BIGINT DEFAULT 0 COMMENT 'Phí no-show áp dụng (VND)',
    deposit_forfeited BIGINT DEFAULT 0 COMMENT 'Tiền cọc bị tịch thu (VND)',
    room_released BOOLEAN DEFAULT FALSE COMMENT 'Đã giải phóng phòng chưa',
    
    -- Liên hệ khách hàng
    contact_attempts INT DEFAULT 0 COMMENT 'Số lần liên hệ khách hàng',
    last_contact_time TIMESTAMP NULL COMMENT 'Lần liên hệ cuối cùng',
    customer_response TEXT COMMENT 'Phản hồi từ khách hàng',
    
    -- Xử lý cuối cùng
    final_action ENUM('fee_charged', 'full_refund', 'partial_refund', 'rescheduled', 'cancelled') COMMENT 'Hành động cuối cùng',
    -- Giải thích các hành động cuối cùng:
    -- fee_charged: Đã tính phí no-show
    -- full_refund: Hoàn tiền toàn bộ (nếu không tính phí)
    -- partial_refund: Hoàn tiền một phần (nếu có)
    -- rescheduled: Đặt lại lịch (nếu khách đồng ý)
    -- cancelled: Hủy đặt phòng (nếu khách không liên hệ)
    refund_amount BIGINT DEFAULT 0 COMMENT 'Số tiền hoàn lại (VND)',
    reschedule_date DATE NULL COMMENT 'Ngày đặt lại (nếu có)',
    
    staff_handled_by INT COMMENT 'Nhân viên xử lý',
    notes TEXT COMMENT 'Ghi chú xử lý',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối',
    
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id),
    FOREIGN KEY (staff_handled_by) REFERENCES users(user_id)
) COMMENT 'Bảng quản lý no-show và xử lý';

-- Bảng quản lý khiếu nại khách hàng
CREATE TABLE guest_complaints (
    complaint_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã khiếu nại',
    booking_id INT COMMENT 'Mã đặt phòng (nếu có)',
    guest_name VARCHAR(100) NOT NULL COMMENT 'Tên khách khiếu nại',
    guest_contact VARCHAR(100) COMMENT 'Thông tin liên hệ',
    
    complaint_type ENUM('room_condition', 'service_quality', 'billing_issue', 'staff_behavior', 'facility_problem', 'noise', 'cleanliness', 'other') COMMENT 'Loại khiếu nại',
    -- Giải thích các loại khiếu nại:
    -- room_condition: Tình trạng phòng (hư hỏng, không sạch sẽ)
    -- service_quality: Chất lượng dịch vụ (nhân viên, tiện nghi)
    -- billing_issue: Vấn đề hóa đơn (tính sai, phí không rõ ràng)
    -- staff_behavior: Hành vi nhân viên (thái độ, phục vụ)
    -- facility_problem: Vấn đề tiện ích (hồ bơi, phòng tập)
    -- noise: Tiếng ồn (từ phòng khác, bên ngoài)
    -- cleanliness: Vấn đề vệ sinh (phòng, nhà hàng)
    -- other: Các vấn đề khác không thuộc các loại trên
    complaint_description TEXT NOT NULL COMMENT 'Mô tả chi tiết khiếu nại',
    complaint_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Ngày khiếu nại',
    
    -- Xử lý khiếu nại
    assigned_to INT COMMENT 'Nhân viên được phân công xử lý',
    priority_level ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium' COMMENT 'Mức độ ưu tiên',
    -- Giải thích các mức độ ưu tiên:
    -- low: Ưu tiên thấp (có thể xử lý sau)
    -- medium: Ưu tiên trung bình (cần xử lý trong thời gian tới)
    -- high: Ưu tiên cao (cần xử lý sớm)
    -- urgent: Ưu tiên khẩn cấp (cần xử lý ngay lập tức)
    status ENUM('received', 'investigating', 'resolved', 'escalated', 'closed') DEFAULT 'received' COMMENT 'Trạng thái xử lý',
    -- Giải thích các trạng thái xử lý:
    -- received: Đã nhận khiếu nại
    -- investigating: Đang điều tra khiếu nại
    -- resolved: Đã giải quyết khiếu nại
    -- escalated: Đã chuyển lên cấp quản lý (nếu cần)
    -- closed: Đã đóng khiếu nại (không còn xử lý)
    resolution_description TEXT COMMENT 'Mô tả giải pháp',
    compensation_offered TEXT COMMENT 'Bồi thường đề xuất',
    compensation_amount BIGINT DEFAULT 0 COMMENT 'Số tiền bồi thường (VND)',
    guest_satisfaction ENUM('very_unsatisfied', 'unsatisfied', 'neutral', 'satisfied', 'very_satisfied') COMMENT 'Mức độ hài lòng sau xử lý',
    -- Giải thích các mức độ hài lòng:
    -- very_unsatisfied: Rất không hài lòng
    -- unsatisfied: Không hài lòng
    -- neutral: Bình thường
    -- satisfied: Hài lòng
    -- very_satisfied: Rất hài lòng
    follow_up_required BOOLEAN DEFAULT FALSE COMMENT 'Cần theo dõi thêm',
    follow_up_date DATE COMMENT 'Ngày theo dõi',
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối',
    
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id),
    FOREIGN KEY (assigned_to) REFERENCES users(user_id)
) COMMENT 'Bảng quản lý khiếu nại khách hàng';

-- Bảng log thay đổi booking
CREATE TABLE booking_changes_log (
    log_id INT PRIMARY KEY AUTO_INCREMENT COMMENT 'Khóa chính, mã log',
    booking_id INT NOT NULL COMMENT 'Mã đặt phòng',
    change_type ENUM('status_change', 'room_change', 'dates_change', 'early_checkout', 'late_checkout', 'cancellation', 'payment_update', 'other') COMMENT 'Loại thay đổi',
    old_values JSON COMMENT 'Giá trị cũ (JSON)',
    new_values JSON COMMENT 'Giá trị mới (JSON)',
    change_reason TEXT COMMENT 'Lý do thay đổi',
    financial_impact_amount BIGINT DEFAULT 0 COMMENT 'Tác động tài chính (VND)',
    fee_applied BIGINT DEFAULT 0 COMMENT 'Phí áp dụng (VND)',
    refund_amount BIGINT DEFAULT 0 COMMENT 'Số tiền hoàn lại (VND)',
    additional_charge BIGINT DEFAULT 0 COMMENT 'Phí phát sinh thêm (VND)',
    processed_by INT COMMENT 'Người xử lý',
    change_source ENUM('guest_request', 'staff_initiated', 'system_automated', 'policy_enforcement') COMMENT 'Nguồn thay đổi',
    approval_required BOOLEAN DEFAULT FALSE COMMENT 'Cần phê duyệt',
    approved_by INT COMMENT 'Người phê duyệt',
    approval_date TIMESTAMP NULL COMMENT 'Ngày phê duyệt',
    notes TEXT COMMENT 'Ghi chú',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id),
    FOREIGN KEY (processed_by) REFERENCES users(user_id),
    FOREIGN KEY (approved_by) REFERENCES users(user_id)
) COMMENT 'Bảng log các thay đổi booking';

-- Bảng tracking cooldown khách hàng
CREATE TABLE guest_cooldown_tracking (
    guest_identifier VARCHAR(100) PRIMARY KEY COMMENT 'Định danh khách (email/phone)',
    guest_id_number VARCHAR(50) NOT NULL COMMENT 'Số CCCD/Hộ chiếu',
    last_checkout_date DATE COMMENT 'Ngày checkout cuối cùng',
    last_booking_id INT COMMENT 'Mã booking cuối cùng',
    last_room_id INT COMMENT 'Mã phòng cuối cùng',
    cooldown_end_date DATE COMMENT 'Ngày kết thúc cooldown',
    cooldown_type VARCHAR(50) COMMENT 'Loại cooldown (early_checkout, cancellation, no_show)',
    total_stays INT DEFAULT 0 COMMENT 'Tổng số lần lưu trú',
    
    level_id INT NOT NULL COMMENT 'Tham chiếu đến cấp độ trong bảng member_levels',

    is_cooldown_active BOOLEAN DEFAULT FALSE COMMENT 'Cooldown có đang hoạt động',
    cooldown_exemption_reason TEXT COMMENT 'Lý do miễn cooldown (nếu có)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Thời gian tạo',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Thời gian cập nhật cuối',
    FOREIGN KEY (last_booking_id) REFERENCES bookings(booking_id),
    FOREIGN KEY (last_room_id) REFERENCES rooms(room_id),
    FOREIGN KEY (level_id) REFERENCES member_levels(level_id),
    INDEX idx_guest_id_number (guest_id_number),
    INDEX idx_cooldown_active (is_cooldown_active, cooldown_end_date)
) COMMENT 'Bảng tracking cooldown khách hàng';
