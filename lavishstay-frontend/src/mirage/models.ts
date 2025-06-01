// models.ts - Định nghĩa các model dữ liệu cho MirageJS

import { RoomOption } from './roomoption';

export interface Room {
    id: number;
    name: string;
    image: string;
    priceVND: number; // Giá thấp nhất từ các options
    size: number;
    view: string;
    bedType: string | {
        default: string;
        options?: string[];
    };
    amenities: string[]; // Tất cả tiện ích của phòng
    mainAmenities?: string[]; // Tiện ích chính hiển thị ở home (4-5 tiện ích)
    discount?: number;
    isSale?: boolean;
    roomType: "deluxe" | "premium" | "suite" | "presidential" | "theLevel";
    rating?: number;
    maxGuests: number; // Số khách tối đa mà loại phòng này có thể chứa
    description: string;
    images: string[];
    totalRooms: number; // Tổng số phòng có sẵn của loại này
    availableRooms: number; // Số phòng còn lại
    options: RoomOption[]; // Các lựa chọn giá và dịch vụ cho loại phòng này
}

// Hotel Rating Details Interface
export interface HotelRatingDetails {
    overallRating: number; // Đánh giá tổng thể của khách sạn
    totalReviews: number; // Tổng số đánh giá
    ratingText: string; // Văn bản mô tả đánh giá
    location: string; // Vị trí của khách sạn
    ratings: {
        cleanliness: number; // Đánh giá về sự sạch sẽ
        location: number; // Đánh giá về vị trí
        facilities: number; // Đánh giá về cơ sở vật chất
        service: number; // Đánh giá về dịch vụ
        valueForMoney: number; // Đánh giá về giá trị đồng tiền
    };
}

// Dữ liệu mẫu cho phòng
export const sampleRooms: Room[] = [
    {
        id: 1,
        name: "Phòng Loại Sang (Deluxe Room)",
        image: "../../public/images/room/Deluxe_Room/1.jpg",
        priceVND: 1000000, // Giá thấp nhất từ các options
        size: 32,
        view: "Nhìn ra thành phố",
        bedType: {
            default: "1 giường đôi cực lớn  hoặc 2 giường đơn ",
            options: ["Giường đôi", "2 giường đơn"]
        },
        amenities: [
            "Đồ vệ sinh cá nhân miễn phí",
            "Áo choàng tắm",
            "Két an toàn",
            "Nhà vệ sinh",
            "Bồn tắm hoặc Vòi sen",
            "Khăn tắm",
            "Ổ điện gần giường",
            "Bàn làm việc",
            "Khu vực tiếp khách",
            "TV",
            "Dép",
            "Tủ lạnh",
            "Điện thoại",
            "Máy sấy tóc",
            "Sàn trải thảm",
            "Ấm đun nước điện",
            "Tủ hoặc phòng để quần áo",
            "Giấy vệ sinh"
        ],
        mainAmenities: [
            "Điều hòa không khí",
            "Phòng tắm riêng ",
            "TV màn hình phẳng",
            "Hệ thống cách âm",
            "Minibar",
            "WiFi miễn phí",
            "Có cung cấp nôi/cũi "
        ],
        discount: 1,
        roomType: "deluxe",
        rating: 9.5,
        maxGuests: 2,
        totalRooms: 23, // Tổng số phòng deluxe
        availableRooms: 18, // Số phòng còn lại
        description: "Phòng giường đôi rộng rãi này được bố trí máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng cùng bồn tắm. Phòng giường đôi có sàn trải thảm, khu vực ghế ngồi với TV màn hình phẳng, minibar, tủ để quần áo cũng như tầm nhìn ra thành phố.",
        images: [
            "../../public/images/room/Deluxe_Room/1.jpg",
            "../../public/images/room/Deluxe_Room/2.jpg",
            "../../public/images/room/Deluxe_Room/3.webp",
            "../../public/images/room/Deluxe_Room/4.webp",
        ],
        options: [
            {
                id: "deluxe_basic_1guest",
                name: "Cơ bản - 1 khách",
                pricePerNight: { vnd: 1470000 },
                maxGuests: 1,
                minGuests: 1,
                roomType: "deluxe",

                mealOptions: {
                    breakfast: {
                        included: true,
                        price: 260000,
                        description: "Bữa sáng Tuyệt hảo - VND 260.000"
                    }
                },

                cancellationPolicy: {
                    type: "non_refundable",
                    penalty: 100,
                    description: "Phí hủy: Toàn bộ tiền phòng"
                },

                paymentPolicy: {
                    type: "pay_at_hotel",
                    description: "Không cần thanh toán trước - thanh toán tại chỗ nghỉ"
                },

                availability: {
                    total: 8,
                    remaining: 2,
                    urgencyMessage: "Chỉ còn 2 phòng!"
                },

                additionalServices: [
                    { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
                    { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true }
                ]
            },
            {
                id: "deluxe_basic_1guest_v2",
                name: "Tiêu chuẩn - 1 khách",
                pricePerNight: { vnd: 1632000 },
                maxGuests: 1,
                minGuests: 1,
                roomType: "deluxe",

                mealOptions: {
                    breakfast: {
                        included: true,
                        description: "Bao gồm bữa sáng tuyệt hảo"
                    }
                },

                cancellationPolicy: {
                    type: "free",
                    freeUntil: "2025-12-15T00:00:00Z",
                    description: "Hủy miễn phí trước 15/12/2025"
                },

                paymentPolicy: {
                    type: "pay_at_hotel",
                    description: "Không cần thanh toán trước - thanh toán tại chỗ nghỉ"
                },

                availability: {
                    total: 8,
                    remaining: 5
                },

                additionalServices: [
                    { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
                    { icon: "CoffeeOutlined", name: "Bữa sáng", included: true },
                    { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true }
                ]
            },
            {
                id: "deluxe_standard_2guest",
                name: "Tiêu chuẩn - 2 khách",
                pricePerNight: { vnd: 1224000 },
                maxGuests: 2,
                minGuests: 1,
                roomType: "deluxe",

                mealOptions: {
                    breakfast: {
                        included: false,
                        price: 260000,
                        description: "Bữa sáng Tuyệt hảo - VND 260.000"
                    }
                },
                //chính sách hủy
                cancellationPolicy: {
                    type: "non_refundable", // không hoàn
                    penalty: 100,
                    description: "Phí hủy: Toàn bộ tiền phòng"
                },

                paymentPolicy: {
                    type: "pay_at_hotel",
                    description: "Không cần thanh toán trước - thanh toán tại chỗ nghỉ"
                },

                availability: {
                    total: 15,
                    remaining: 8
                },

                additionalServices: [
                    { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
                    { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true }
                ],

                mostPopular: true
            },
            {
                id: "deluxe_premium_2guest",
                name: "Cao cấp - 2 khách",
                pricePerNight: { vnd: 1360000 },
                maxGuests: 2,
                minGuests: 1,
                roomType: "deluxe",

                mealOptions: {
                    breakfast: {
                        included: false,
                        description: "Bữa sáng Tuyệt hảo - VND 260.000"
                    },
                },

                cancellationPolicy: {
                    type: "free",
                    freeUntil: "2025-12-10T00:00:00Z",
                    description: "Hủy miễn phí trước 10/12/2025"
                },

                paymentPolicy: {
                    type: "pay_at_hotel",
                    description: "Không cần thanh toán trước - thanh toán tại chỗ nghỉ"
                },

                availability: {
                    total: 15,
                    remaining: 12
                },

                additionalServices: [
                    { icon: "WifiOutlined", name: "Wi-Fi miễn phí", included: true },
                    { icon: "CoffeeOutlined", name: "Bữa sáng", included: true },
                    { icon: "CarOutlined", name: "Đỗ xe miễn phí", included: true }
                ],

                recommended: true
            },
            {
                id: "deluxe_luxury_2guest",
                name: "Sang trọng - 2 khách",
                pricePerNight: { vnd: 2531000 },
                maxGuests: 2,
                minGuests: 1,
                roomType: "deluxe",

                mealOptions: {
                    breakfast: {
                        included: true,
                        description: "Bao bữa sáng cao cấp"
                    },
                    dinner: {
                        included: true,
                        description: "Bao bữa tối cao cấp"
                    }
                },

                cancellationPolicy: {
                    type: "free",
                    freeUntil: "2025-12-05T00:00:00Z",
                    description: "Hủy miễn phí trước 05/12/2025"
                },

                paymentPolicy: {
                    type: "pay_now_with_vietQR",
                    description: "Thanh toán với VietQR"
                },

                availability: {
                    total: 15,
                    remaining: 6
                },

                additionalServices: [
                    { icon: "WifiOutlined", name: "Wi-Fi tốc độ cao", included: true },
                    { icon: "CoffeeOutlined", name: "Bữa sáng & tối cao cấp", included: true },
                    { icon: "CarOutlined", name: "Đỗ xe VIP", included: true },
                    { icon: "RestOutlined", name: "Spa miễn phí", included: true }
                ],

                promotion: {
                    type: "hot",
                    message: "Ưu đãi đặc biệt",
                    discount: 10
                }
            },
            {
                id: "deluxe_suite_2guest",
                name: "Suite Deluxe - 2 khách",
                pricePerNight: { vnd: 2812000 },
                maxGuests: 2,
                minGuests: 1,
                roomType: "deluxe",

                mealOptions: {
                    breakfast: {
                        included: true,
                        description: "Bao bữa sáng VIP"
                    },
                    dinner: {
                        included: true,
                        description: "Bao bữa tối VIP"
                    }
                },

                cancellationPolicy: {
                    type: "free",
                    freeUntil: "2025-12-01T00:00:00Z",
                    description: "Hủy miễn phí trước 01/12/2025"
                },

                paymentPolicy: {
                    type: "pay_now_with_vietQR",
                    description: "Thanh toán với VietQR "
                },

                availability: {
                    total: 15,
                    remaining: 3,
                    urgencyMessage: "Chỉ còn 3 phòng cuối cùng!"
                },

                additionalServices: [
                    { icon: "WifiOutlined", name: "Wi-Fi cao cấp", included: true },
                    { icon: "CoffeeOutlined", name: "Dịch vụ ăn uống VIP", included: true },
                    { icon: "CarOutlined", name: "Đưa đón sân bay", included: true },
                    { icon: "RestOutlined", name: "Spa & Gym miễn phí", included: true },
                    { icon: "UserOutlined", name: "Dịch vụ quản gia", included: true }
                ],

                promotion: {
                    type: "limited",
                    message: "Phiên bản giới hạn",
                    discount: 15
                }
            }
        ]
    },
    {
        id: 2,
        name: "Phòng cao cấp trong góc (Premium Corner Room)",
        image: "../../public/images/room/Premium_Corner_Room/1.jpg",
        priceVND: 3000000,
        size: 55,
        view: "Tầm nhìn thành phố",
        bedType: {
            default: "1 giường đôi cực lớn  hoặc 2 giường đơn",
            options: ["Giường đôi", "2 giường đơn"]
        },
        amenities: [
            "Đồ vệ sinh cá nhân miễn phí",
            "Áo choàng tắm",
            "Két an toàn",
            "Nhà vệ sinh",
            "Bồn tắm hoặc Vòi sen",
            "Khăn tắm",
            "Ổ điện gần giường",
            "Bàn làm việc",
            "Khu vực tiếp khách",
            "TV",
            "Dép",
            "Tủ lạnh",
            "Điện thoại",
            "Máy sấy tóc",
            "Sàn trải thảm",
            "Ấm đun nước điện",
            "Tủ hoặc phòng để quần áo",
            "Giấy vệ sinh"
        ],
        mainAmenities: [
            "Điều hòa không khí",
            "Phòng tắm riêng ",
            "TV màn hình phẳng",
            "Hệ thống cách âm",
            "Minibar",
            "WiFi miễn phí",
            "Có cung cấp nôi/cũi "

        ],
        discount: 2,
        roomType: "premium",
        rating: 9.4,
        maxGuests: 2,
        totalRooms: 8, // Tổng số phòng premium
        availableRooms: 15, // Số phòng còn lại
        description: "Phòng giường đôi rộng rãi này có máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng và bồn tắm.",
        images: [
            "../../public/images/room/Premium_Corner_Room/1.jpg",
            "../../public/images/room/Premium_Corner_Room/2.jpg",
            "../../public/images/room/Premium_Corner_Room/3.webp",
            "../../public/images/room/Premium_Corner_Room/4.jpg",
            "../../public/images/room/Premium_Corner_Room/5.webp",
            "../../public/images/room/Premium_Corner_Room/6.webp",
        ],
        options: [
            {
                id: "premium_corner_basic_1guest",
                name: "The premium Cơ bản - 1 khách",
                pricePerNight: { vnd: 1700000 },
                maxGuests: 1,
                minGuests: 1,
                roomType: "premium",

                mealOptions: {
                    breakfast: {
                        included: true,
                        price: 260000,
                        description: "Bữa sáng Tuyệt hảo - VND 260.000"
                    }
                },

                cancellationPolicy: {
                    type: "free",
                    freeUntil: "2025-12-15T00:00:00Z",
                    description: "Hủy miễn phí trước 15/12/2025"
                },

                paymentPolicy: {
                    type: "pay_at_hotel",
                    description: "Thanh toán tại khách sạn"
                },

                availability: {
                    total: 8,
                    remaining: 4
                },

                additionalServices: [
                    { icon: "HomeOutlined", name: "Truy cập The premium Lounge", included: true },
                    { icon: "UserOutlined", name: "Check-in/out ưu tiên", included: true },
                    { icon: "WifiOutlined", name: "Wi-Fi cao cấp", included: true }
                ]
            },
            {
                id: "premium_corner_basic_1guest_v2",
                name: "The premium Cơ bản - 1 khách",
                pricePerNight: { vnd: 1900000 },
                maxGuests: 1,
                minGuests: 1,
                roomType: "premium",

                mealOptions: {
                    breakfast: {
                        included: true,
                        price: 260000,
                        description: "Bữa sáng Tuyệt hảo - VND 260.000"
                    }
                },

                cancellationPolicy: {
                    type: "free",
                    freeUntil: "2025-12-15T00:00:00Z",
                    description: "Hủy miễn phí trước 15/12/2025"
                },

                paymentPolicy: {
                    type: "pay_now_with_vietQR",
                    description: "Thanh toán với VietQR"
                },

                availability: {
                    total: 8,
                    remaining: 4
                },

                additionalServices: [
                    { icon: "HomeOutlined", name: "Truy cập The premium Lounge", included: true },
                    { icon: "UserOutlined", name: "Check-in/out ưu tiên", included: true },
                    { icon: "WifiOutlined", name: "Wi-Fi cao cấp", included: true }
                ]
            },
            {
                id: "premium_corner_basic_2guest",
                name: "The Premium Corner Basic - 2 khách",
                pricePerNight: { vnd: 1469000 },
                maxGuests: 2,
                minGuests: 1,
                roomType: "premium",

                mealOptions: {
                    breakfast: {
                        included: true,
                        price: 260000,
                        description: "Bữa sáng Tuyệt hảo - VND 260.000"
                    },
                },

                cancellationPolicy: {
                    type: "non_refundable",
                    penalty: 100,
                    description: "Phí hủy: Toàn bộ tiền phòng"
                },

                paymentPolicy: {
                    type: "pay_now_with_vietQR",
                    description: "Thanh toán với VietQR"
                },

                availability: {
                    total: 0,
                    remaining: 0
                },

                additionalServices: [
                    { icon: "HomeOutlined", name: "Truy cập toàn bộ The premium", included: true },
                    { icon: "UserOutlined", name: "Dịch vụ quản gia riêng", included: true },
                    { icon: "RestOutlined", name: "Spa The premium miễn phí", included: true },
                    { icon: "CarOutlined", name: "Đưa đón sân bay", included: true }
                ],

                recommended: true
            },
            {
                id: "premium_corner_basic_2guest_v2",
                name: "The Premium Corner Basic - 2 khách",
                pricePerNight: { vnd: 1632000 },
                maxGuests: 2,
                minGuests: 1,
                roomType: "premium",

                mealOptions: {
                    breakfast: {
                        included: true,
                        price: 260000,
                        description: "Bữa sáng Tuyệt hảo - VND 260.000"
                    },
                },

                cancellationPolicy: {
                    type: "free",
                    freeUntil: "2025-12-08T00:00:00Z",
                    description: "Hủy miễn phí trước 08/12/2025"
                },

                paymentPolicy: {
                    type: "pay_now_with_vietQR",
                    description: "Thanh toán với VietQR - VIP"
                },

                availability: {
                    total: 8,
                    remaining: 2,
                    urgencyMessage: "Chỉ còn 2 phòng view đẹp nhất!"
                },

                additionalServices: [
                    { icon: "RestOutlined", name: "View panoramic độc quyền", included: true },
                    { icon: "HomeOutlined", name: "The premium Lounge VIP", included: true },
                    { icon: "UserOutlined", name: "Quản gia 24/7", included: true },
                    { icon: "CarOutlined", name: "Xe riêng đưa đón", included: true }
                ],

                promotion: {
                    type: "limited",
                    message: "View độc quyền",
                    discount: 20
                }
            },
            {
                id: "premium_corner_vip_2guest",
                name: "The Premium Corner VIP LUXURY - 2 khách",
                pricePerNight: { vnd: 2778000 },
                maxGuests: 2,
                minGuests: 2,
                roomType: "premium",

                mealOptions: {
                    breakfast: {
                        included: true,
                        description: "Bao bữa sáng corner"
                    },
                    dinner: {
                        included: true,
                        description: "Bao bữa tối corner"
                    }
                },

                cancellationPolicy: {
                    type: "non_refundable",
                    penalty: 100,
                    description: "Phí hủy: Toàn bộ tiền phòng"
                },

                paymentPolicy: {
                    type: "pay_now_with_vietQR",
                    description: "Thanh toán với VietQR "
                },

                availability: {
                    total: 2,
                    remaining: 1,
                    urgencyMessage: "Phòng cuối cùng!"
                },

                additionalServices: [
                    { icon: "HomeOutlined", name: "Penthouse riêng biệt", included: true },
                    { icon: "UserOutlined", name: "Đội ngũ phục vụ riêng", included: true },
                    { icon: "RestOutlined", name: "Hồ bơi riêng", included: true },
                    { icon: "CarOutlined", name: "Limousine đưa đón", included: true }
                ],

                promotion: {
                    type: "hot",
                    message: "Trải nghiệm đỉnh cao",
                    discount: 25
                },

                mostPopular: true
            },
            {
                id: "premium_corner_vip_2guest_v2",
                name: "The Premium Corner VIP LUXURY - 2 khách",
                pricePerNight: { vnd: 3084000 },
                maxGuests: 2,
                minGuests: 2,
                roomType: "premium",

                mealOptions: {
                    breakfast: {
                        included: true,
                        description: "Bao bữa sáng corner"
                    },
                    dinner: {
                        included: true,
                        description: "Bao bữa tối corner"
                    }
                },

                cancellationPolicy: {
                    type: "free",
                    freeUntil: "2025-12-05T00:00:00Z",
                    description: "Hủy miễn phí trước 05/12/2025"
                },

                paymentPolicy: {
                    type: "pay_now_with_vietQR",
                    description: "Thanh toán với VietQR "
                },

                availability: {
                    total: 2,
                    remaining: 1,
                    urgencyMessage: "Phòng cuối cùng!"
                },

                additionalServices: [
                    { icon: "HomeOutlined", name: "Penthouse riêng biệt", included: true },
                    { icon: "UserOutlined", name: "Đội ngũ phục vụ riêng", included: true },
                    { icon: "RestOutlined", name: "Hồ bơi riêng", included: true },
                    { icon: "CarOutlined", name: "Limousine đưa đón", included: true }
                ],

                promotion: {
                    type: "hot",
                    message: "Trải nghiệm đỉnh cao",
                    discount: 25
                },

                mostPopular: true
            }
        ]
    },
    {
        id: 3,
        name: "Phòng The Level Cao cấp (The Level Premium Room)",
        image: "../../public/images/room/The_Level_Premium_Room/1.jpg",
        priceVND: 2500000, // lấy Giá thấp nhất từ các options
        size: 33,
        view: "Nhìn ra thành phố",
        bedType: {
            default: "1 giường đôi cực lớn  hoặc 2 giường đơn",
            options: ["Giường đôi", "2 giường đơn"]
        },
        amenities: [
            "Đồ vệ sinh cá nhân miễn phí",
            "Áo choàng tắm",
            "Két an toàn",
            "Nhà vệ sinh",
            "Bồn tắm hoặc Vòi sen",
            "Khăn tắm",
            "Ổ điện gần giường",
            "Bàn làm việc",
            "Khu vực tiếp khách",
            "TV",
            "Dép",
            "Tủ lạnh",
            "Điện thoại",
            "Máy sấy tóc",
            "Sàn trải thảm",
            "Ấm đun nước điện",
            "Tủ hoặc phòng để quần áo",
            "Giấy vệ sinh"
        ],
        mainAmenities: [
            "Điều hòa không khí",
            "Phòng tắm riêng ",
            "TV màn hình phẳng",
            "Hệ thống cách âm",
            "Minibar",
            "WiFi miễn phí",
            "Máy pha cà phê",
        ],
        discount: 7,
        roomType: "theLevel",
        rating: 9.8,
        maxGuests: 4,
        totalRooms: 8, // Tổng số phòng The Level
        availableRooms: 13, // Số phòng còn lại
        description: "Phòng giường đôi này được bố trí máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng và bồn tắm. Phòng giường đôi có sàn trải thảm, khu vực ghế ngồi với TV màn hình phẳng, minibar, máy pha cà phê cũng như tầm nhìn ra thành phố.",
        images: [
            "../../public/images/room/The_Level_Premium_Room/1.jpg",
            "../../public/images/room/The_Level_Premium_Room/2.webp",
            "../../public/images/room/The_Level_Premium_Room/3.jpg",
            "../../public/images/room/The_Level_Premium_Room/4.jpg",
            "../../public/images/room/The_Level_Premium_Room/5.jpg",
            "../../public/images/room/The_Level_Premium_Room/6.webp",
            "../../public/images/room/The_Level_Premium_Room/7.jpg",
            "../../public/images/room/The_Level_Premium_Room/8.webp",
        ],
        options: [
            {
                id: "level_basic_1guest",
                name: "The Level Cơ bản - 1 khách",
                pricePerNight: { vnd: 2204000 },
                maxGuests: 1,
                minGuests: 1,
                roomType: "theLevel",

                mealOptions: {
                    breakfast: {
                        included: true,
                        description: "Bữa sáng Tuyệt hảo "
                    }
                },

                cancellationPolicy: {
                    type: "non_refundable",
                    penalty: 100,
                    description: "Phí hủy: Toàn bộ tiền phòng"
                },

                paymentPolicy: {
                    type: "pay_now_with_vietQR",
                    description: "Thanh toán với VietQR"
                },

                availability: {
                    total: 8,
                    remaining: 4
                },

                additionalServices: [
                    { icon: "HomeOutlined", name: "Truy cập The Level Lounge", included: true },
                    { icon: "UserOutlined", name: "Check-in/out ưu tiên", included: true },
                    { icon: "WifiOutlined", name: "Wi-Fi cao cấp", included: true }
                ]
            },
            {
                id: "level_premium_1guest_v2",
                name: "The Level Cao cấp - 1 khách",
                pricePerNight: { vnd: 2448000 },
                maxGuests: 1,
                minGuests: 1,
                roomType: "theLevel",

                mealOptions: {
                    breakfast: {
                        included: true,
                        description: "Bao gồm bữa sáng tuyệt hảo"
                    },
                },

                cancellationPolicy: {
                    type: "free",
                    freeUntil: "2025-12-10T00:00:00Z",
                    description: "Hủy miễn phí trước 10/12/2025"
                },

                paymentPolicy: {
                    type: "pay_now_with_vietQR",
                    description: "Thanh toán với VietQR"
                },

                availability: {
                    total: 8,
                    remaining: 6
                },

                additionalServices: [
                    { icon: "HomeOutlined", name: "Truy cập toàn bộ The Level", included: true },
                    { icon: "UserOutlined", name: "Dịch vụ quản gia riêng", included: true },
                    { icon: "RestOutlined", name: "Spa The Level miễn phí", included: true },
                    { icon: "CarOutlined", name: "Đưa đón sân bay", included: true }
                ],

                recommended: true
            },
            {
                id: "level_premium_2guest",
                name: "The Level Panoramic - 2 khách",
                pricePerNight: { vnd: 3200000 },
                maxGuests: 2,
                minGuests: 1,
                roomType: "theLevel",

                mealOptions: {
                    breakfast: {
                        included: true,
                        description: "Bữa sáng tuyệt hảo"
                    },
                },

                cancellationPolicy: {
                    type: "non_refundable",
                    penalty: 100,
                    description: "Phí hủy: Toàn bộ tiền phòng"
                },

                paymentPolicy: {
                    type: "pay_now_with_vietQR",
                    description: "Thanh toán với VietQR - VIP"
                },

                availability: {
                    total: 8,
                    remaining: 2,
                    urgencyMessage: "Nhanh lên , chúng tôi chỉ còn 2 phòng!"
                },

                additionalServices: [
                    { icon: "RestOutlined", name: "View premium độc quyền", included: true },
                    { icon: "HomeOutlined", name: "The Level Lounge VIP", included: true },
                    { icon: "UserOutlined", name: "Quản gia 24/7", included: true },
                    { icon: "CarOutlined", name: "Xe riêng đưa đón", included: true }
                ],

                promotion: {
                    type: "limited",
                    message: "View độc quyền",
                    discount: 20
                }
            },
            {
                id: "level_penthouse_2guest_v2",
                name: "The Level Penthouse - 2 khách",
                pricePerNight: { vnd: 4500000 },
                maxGuests: 2,
                minGuests: 2,
                roomType: "theLevel",

                mealOptions: {
                    breakfast: {
                        included: true,
                        description: "Bao gồm bữa sáng tuyệt hảo"
                    },
                },

                cancellationPolicy: {
                    type: "free",
                    freeUntil: "2025-12-10T00:00:00Z",
                    description: "Hủy miễn phí trước 10/12/2025"
                },

                paymentPolicy: {
                    type: "pay_now_with_vietQR",
                    description: "Thanh toán với VietQR"
                },

                availability: {
                    total: 8,
                    remaining: 1,
                    urgencyMessage: "Phòng cuối cùng!"
                },

                additionalServices: [
                    { icon: "HomeOutlined", name: "Penthouse riêng biệt", included: true },
                    { icon: "UserOutlined", name: "Đội ngũ phục vụ riêng", included: true },
                    { icon: "RestOutlined", name: "Hồ bơi riêng", included: true },
                    { icon: "CarOutlined", name: "Limousine đưa đón", included: true }
                ],

                promotion: {
                    type: "hot",
                    message: "Trải nghiệm đỉnh cao",
                    discount: 25
                },

                mostPopular: true
            }
        ]
    }
];

// Dữ liệu đánh giá tổng quan khách sạn
export const hotelRatingDetails: HotelRatingDetails = {
    overallRating: 9.2,
    totalReviews: 3122,
    ratingText: "Trên cả tuyệt vời",
    location: "Thanh Hoá / Bãi biển Sầm Sơn",
    ratings: {
        cleanliness: 9.5,
        location: 9.5,
        facilities: 9.4,
        service: 9.4,
        valueForMoney: 9.4
    }
};
