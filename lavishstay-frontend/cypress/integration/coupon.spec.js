// cypress/integration/coupon.spec.js
describe("Coupon Integration", () => {
  beforeEach(() => {
    // Setup intercepts for API calls
    cy.intercept("POST", "/api/coupons/check-code", {
      fixture: "couponCheckValid.json",
    }).as("checkCode");
    cy.intercept("POST", "/api/coupons/validate", {
      fixture: "couponValidateValid.json",
    }).as("validateCoupon");
    cy.intercept("POST", "/api/payments/create-booking", {
      fixture: "bookingCreated.json",
    }).as("createBooking");

    // Mock localStorage with sample booking data
    cy.window().then((win) => {
      win.localStorage.setItem(
        "persist:root",
        JSON.stringify({
          booking: JSON.stringify({
            selectedRoomsSummary: [
              {
                room: {
                  id: 1,
                  name: "Deluxe Room",
                  room_type_id: 1,
                },
                option: {
                  name: "Standard Package",
                  id: "pkg-1",
                },
                totalPrice: 1000000,
                pricePerNight: 500000,
                optionId: "pkg-1",
              },
            ],
            totals: {
              roomsTotal: 1000000,
              serviceFee: 50000,
              taxAmount: 100000,
              discountAmount: 0,
              finalTotal: 1150000,
              nights: 2,
            },
          }),
          searchData: JSON.stringify({
            checkIn: "2024-09-01",
            checkOut: "2024-09-03",
            rooms: [{ adults: 2, children: 0, childrenAges: [] }],
          }),
        })
      );
    });
  });

  it("should complete booking flow with valid coupon", () => {
    // Visit payment page
    cy.visit("/payment");

    // Wait for page to load
    cy.contains("Thông tin khách hàng").should("be.visible");

    // Fill in customer information
    cy.get(
      'input[placeholder="Nhập họ và tên người đại diện cho tất cả phòng"]'
    ).type("John Doe");

    cy.get('input[placeholder="Nhập email"]').type("john@example.com");

    cy.get('input[placeholder="Nhập số điện thoại"]').type("+84123456789");

    // Enter coupon code
    cy.get('input[placeholder="Nhập mã giảm giá"]').type("WELCOME2024");

    // Check coupon
    cy.get("button").contains("Kiểm tra").click();

    // Wait for validation
    cy.wait("@checkCode");
    cy.wait("@validateCoupon");

    // Verify success message
    cy.contains("Mã giảm giá hợp lệ!").should("be.visible");

    // Apply coupon
    cy.get("button").contains("Áp dụng").click();

    // Verify applied coupon display
    cy.contains("WELCOME2024").should("be.visible");
    cy.contains("Đã áp dụng").should("be.visible");

    // Agree to terms
    cy.get('input[type="checkbox"]').check();

    // Proceed to payment
    cy.get("button").contains("Tiếp tục thanh toán").click();

    // Wait for booking creation
    cy.wait("@createBooking").then((interception) => {
      // Verify coupon_code was sent in request
      expect(interception.request.body).to.have.property(
        "coupon_code",
        "WELCOME2024"
      );
    });

    // Verify booking success (should be on completion step)
    cy.url().should("include", "/payment");
    cy.contains("Đã tạo đơn đặt phòng thành công!").should("be.visible");
  });

  it("should handle invalid coupon gracefully", () => {
    // Setup intercept for invalid coupon
    cy.intercept("POST", "/api/coupons/check-code", {
      statusCode: 200,
      body: {
        exists: false,
        message: "Mã giảm giá không tồn tại",
      },
    }).as("checkCodeInvalid");

    cy.visit("/payment");

    // Fill basic info
    cy.get(
      'input[placeholder="Nhập họ và tên người đại diện cho tất cả phòng"]'
    ).type("John Doe");

    cy.get('input[placeholder="Nhập email"]').type("john@example.com");

    cy.get('input[placeholder="Nhập số điện thoại"]').type("+84123456789");

    // Enter invalid coupon
    cy.get('input[placeholder="Nhập mã giảm giá"]').type("INVALID123");

    cy.get("button").contains("Kiểm tra").click();

    cy.wait("@checkCodeInvalid");

    // Verify error message
    cy.contains("Mã giảm giá không tồn tại").should("be.visible");

    // Agree to terms and proceed without coupon
    cy.get('input[type="checkbox"]').check();
    cy.get("button").contains("Tiếp tục thanh toán").click();

    // Verify booking creation without coupon
    cy.wait("@createBooking").then((interception) => {
      expect(interception.request.body.coupon_code).to.be.null;
    });
  });

  it("should handle expired coupon", () => {
    // Setup intercepts for expired coupon
    cy.intercept("POST", "/api/coupons/check-code", {
      fixture: "couponCheckValid.json",
    }).as("checkCode");
    cy.intercept("POST", "/api/coupons/validate", {
      statusCode: 200,
      body: {
        valid: false,
        reason: "expired",
        message: "Mã giảm giá đã hết hạn",
      },
    }).as("validateExpired");

    cy.visit("/payment");

    // Fill basic info
    cy.get(
      'input[placeholder="Nhập họ và tên người đại diện cho tất cả phòng"]'
    ).type("John Doe");

    cy.get('input[placeholder="Nhập email"]').type("john@example.com");

    cy.get('input[placeholder="Nhập số điện thoại"]').type("+84123456789");

    // Enter expired coupon
    cy.get('input[placeholder="Nhập mã giảm giá"]').type("EXPIRED10");

    cy.get("button").contains("Kiểm tra").click();

    cy.wait("@checkCode");
    cy.wait("@validateExpired");

    // Verify error message for expired coupon
    cy.contains("Mã giảm giá đã hết hạn").should("be.visible");
  });

  it("should allow removing applied coupon", () => {
    cy.visit("/payment");

    // Fill basic info
    cy.get(
      'input[placeholder="Nhập họ và tên người đại diện cho tất cả phòng"]'
    ).type("John Doe");

    cy.get('input[placeholder="Nhập email"]').type("john@example.com");

    cy.get('input[placeholder="Nhập số điện thoại"]').type("+84123456789");

    // Apply coupon
    cy.get('input[placeholder="Nhập mã giảm giá"]').type("WELCOME2024");

    cy.get("button").contains("Kiểm tra").click();

    cy.wait("@checkCode");
    cy.wait("@validateCoupon");

    cy.get("button").contains("Áp dụng").click();

    // Verify coupon is applied
    cy.contains("WELCOME2024").should("be.visible");
    cy.contains("Đã áp dụng").should("be.visible");

    // Remove coupon
    cy.get("button").contains("Gỡ mã").click();

    // Verify coupon is removed
    cy.contains("Đã áp dụng").should("not.exist");
    cy.get('input[placeholder="Nhập mã giảm giá"]').should("have.value", "");
  });

  it("should handle server errors during coupon validation", () => {
    // Setup intercept for server error
    cy.intercept("POST", "/api/coupons/check-code", {
      statusCode: 500,
      body: { message: "Internal server error" },
    }).as("checkCodeError");

    cy.visit("/payment");

    // Fill basic info
    cy.get(
      'input[placeholder="Nhập họ và tên người đại diện cho tất cả phòng"]'
    ).type("John Doe");

    // Try to check coupon
    cy.get('input[placeholder="Nhập mã giảm giá"]').type("TESTCODE");

    cy.get("button").contains("Kiểm tra").click();

    cy.wait("@checkCodeError");

    // Verify error handling
    cy.contains("Lỗi server. Vui lòng thử lại sau").should("be.visible");
  });

  it("should handle rate limiting", () => {
    // Setup intercept for rate limiting
    cy.intercept("POST", "/api/coupons/check-code", {
      statusCode: 429,
      body: { message: "Too many requests" },
    }).as("checkCodeRateLimit");

    cy.visit("/payment");

    // Fill basic info
    cy.get(
      'input[placeholder="Nhập họ và tên người đại diện cho tất cả phòng"]'
    ).type("John Doe");

    // Try to check coupon
    cy.get('input[placeholder="Nhập mã giảm giá"]').type("TESTCODE");

    cy.get("button").contains("Kiểm tra").click();

    cy.wait("@checkCodeRateLimit");

    // Verify rate limit handling
    cy.contains("Quá nhiều yêu cầu. Vui lòng đợi một chút").should(
      "be.visible"
    );
  });

  it("should reject coupon at server during booking creation", () => {
    // Setup intercept for booking creation with coupon rejection
    cy.intercept("POST", "/api/payments/create-booking", {
      statusCode: 400,
      body: {
        success: false,
        message: "Mã giảm giá không hợp lệ hoặc đã hết hạn",
      },
    }).as("createBookingCouponError");

    cy.visit("/payment");

    // Fill info and apply coupon
    cy.get(
      'input[placeholder="Nhập họ và tên người đại diện cho tất cả phòng"]'
    ).type("John Doe");

    cy.get('input[placeholder="Nhập email"]').type("john@example.com");

    cy.get('input[placeholder="Nhập số điện thoại"]').type("+84123456789");

    cy.get('input[placeholder="Nhập mã giảm giá"]').type("WELCOME2024");

    cy.get("button").contains("Kiểm tra").click();

    cy.wait("@checkCode");
    cy.wait("@validateCoupon");

    cy.get("button").contains("Áp dụng").click();

    // Proceed to booking
    cy.get('input[type="checkbox"]').check();
    cy.get("button").contains("Tiếp tục thanh toán").click();

    cy.wait("@createBookingCouponError");

    // Verify error is shown and booking is not created
    cy.contains("Mã giảm giá không hợp lệ hoặc đã hết hạn").should(
      "be.visible"
    );
    cy.url().should("include", "/payment"); // Should stay on payment page
  });
});
