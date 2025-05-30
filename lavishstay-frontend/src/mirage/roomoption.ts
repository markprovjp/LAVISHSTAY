export interface RoomOption {
    id: string;
    name: string;
    price: {
        vnd: number;
    };
    originalPrice?: {
        vnd: number;
    };
    memberPrice?: {
        vnd: number;
    };
    discount?: number;
    recommended?: boolean;
    available: number;
    totalRooms: number;
    maxGuests: number;
    roomType: "deluxe" | "premium" | "suite" | "presidential" | "theLevel";
    services: {
        icon: string;
        name: string;
        price?: string;
        tooltip?: string;
    }[];
    policies: string[];
    promotion?: {
        type: "hot" | "limited" | "member" | "lowest" | "deal";
        message: string;
    };
    paymentType: "vietQR";
    cancellation: "free" | "non_refundable";
    bookingSpeed?: string;
}

// Dữ liệu mẫu cho room options
export const sampleRoomOptions: RoomOption[] = [
    // Deluxe room options
    {
        id: "deluxe_standard",
        name: "Tỷ lệ tiêu chuẩn test",
        price: { vnd: 3000000 },
        originalPrice: { vnd: 4000000 },
        discount: 25,
        available: 5,
        totalRooms: 10,
        maxGuests: 2,
        roomType: "deluxe",
        services: [
            { icon: "ClockCircleOutlined", name: "Đặt trong 2 phút" },
            { icon: "CoffeeOutlined", name: "Đã gồm bữa sáng" },
            { icon: "WifiOutlined", name: "Wi-Fi miễn phí" },
        ],
        policies: ["Mỗi đêm, đã gồm thuế và phí"],
        paymentType: "vietQR",
        cancellation: "free",
        bookingSpeed: "Đặt trong 2 phút",
    },
    {
        id: "deluxe_premium",
        name: "Gói cao cấp",
        price: { vnd: 3500000 },
        originalPrice: { vnd: 4500000 },
        discount: 22,
        recommended: true,
        available: 2,
        totalRooms: 5,
        maxGuests: 2,
        roomType: "deluxe",
        services: [
            { icon: "ClockCircleOutlined", name: "Đặt trong 2 phút" },
            { icon: "CoffeeOutlined", name: "Đã gồm bữa sáng" },
            { icon: "CarOutlined", name: "Đưa đón sân bay miễn phí" },
            { icon: "RestOutlined", name: "Vào hồ bơi miễn phí" },
        ],
        policies: ["Mỗi đêm, đã gồm thuế và phí"],
        promotion: {
            type: "hot",
            message: "Phổ biến nhất",
        },
        paymentType: "vietQR",
        cancellation: "free"
    },
    // Premium room options
    {
        id: "premium_standard",
        name: "Premium Standard",
        price: { vnd: 4500000 },
        originalPrice: { vnd: 5500000 },
        discount: 18,
        available: 3,
        totalRooms: 8,
        maxGuests: 2,
        roomType: "premium",
        services: [
            { icon: "ClockCircleOutlined", name: "Đặt trong 2 phút" },
            { icon: "CoffeeOutlined", name: "Đã gồm bữa sáng cao cấp" },
            { icon: "WifiOutlined", name: "Wi-Fi tốc độ cao" },
            { icon: "RestOutlined", name: "riêng với view biển" },
        ],
        policies: ["Mỗi đêm, đã gồm thuế và phí"],
        paymentType: "vietQR",
        cancellation: "free",
        recommended: true
    },
    // Suite room options
    {
        id: "suite_luxury",
        name: "Suite Luxury",
        price: { vnd: 6200000 },
        originalPrice: { vnd: 7500000 },
        discount: 17,
        available: 2,
        totalRooms: 4,
        maxGuests: 3,
        roomType: "suite",
        services: [
            { icon: "ClockCircleOutlined", name: "Đặt trong 2 phút" },
            { icon: "CoffeeOutlined", name: "Đã gồm bữa sáng VIP" },
            { icon: "HomeOutlined", name: "Phòng khách riêng biệt" },
            { icon: "RestOutlined", name: "View toàn cảnh biển" },
            { icon: "UserOutlined", name: "Dịch vụ quản gia" },
        ],
        policies: ["Mỗi đêm, đã gồm thuế và phí"],
        promotion: {
            type: "hot",
            message: "Phổ biến nhất",
        },
        paymentType: "vietQR",
        cancellation: "free",
        recommended: true
    },
    // Presidential room options
    {
        id: "presidential_standard",
        name: "Presidential Standard",
        price: { vnd: 18750000 },
        available: 1,
        totalRooms: 2,
        maxGuests: 4,
        roomType: "presidential",
        services: [
            { icon: "ClockCircleOutlined", name: "Đặt trong 2 phút" },
            { icon: "CoffeeOutlined", name: "Đã gồm bữa sáng cao cấp" },
            { icon: "CarOutlined", name: "Dịch vụ đưa đón sân bay hạng sang" },
            { icon: "UserOutlined", name: "Quản gia riêng 24/7" },
            { icon: "RestOutlined", name: "Tiếp cận tất cả khu vực cao cấp" },
        ],
        policies: ["Mỗi đêm, đã gồm thuế và phí"],
        paymentType: "vietQR",
        cancellation: "free",
        bookingSpeed: "Ưu tiên đặt phòng"
    },
    // The Level room options
    {
        id: "level_standard",
        name: "The Level Standard",
        price: { vnd: 9500000 },
        available: 2,
        totalRooms: 4,
        maxGuests: 2,
        roomType: "theLevel",
        services: [
            { icon: "ClockCircleOutlined", name: "Đặt trong 2 phút" },
            { icon: "CoffeeOutlined", name: "Đã gồm bữa sáng đặc biệt" },
            { icon: "HomeOutlined", name: "Truy cập The Level Lounge" },
            { icon: "UserOutlined", name: "Nhận phòng và trả phòng ưu tiên" },
        ],
        policies: ["Mỗi đêm, đã gồm thuế và phí"],
        paymentType: "vietQR",
        cancellation: "free",
        recommended: true
    },
    {
        id: "level_panoramic",
        name: "The Level Panoramic",
        price: { vnd: 10500000 },
        available: 1,
        totalRooms: 2,
        maxGuests: 2,
        roomType: "theLevel",
        services: [
            { icon: "ClockCircleOutlined", name: "Đặt trong 2 phút" },
            { icon: "CoffeeOutlined", name: "Đã gồm bữa sáng đặc biệt" },
            { icon: "HomeOutlined", name: "Truy cập The Level Lounge" },
            { icon: "UserOutlined", name: "Nhận phòng và trả phòng ưu tiên" },
            { icon: "RestOutlined", name: "View panorama độc quyền" },
        ],
        policies: ["Mỗi đêm, đã gồm thuế và phí"],
        promotion: {
            type: "limited",
            message: "Số lượng có hạn",
        },
        paymentType: "vietQR",
        cancellation: "free"
    }
];