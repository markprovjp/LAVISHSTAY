// models.ts - Định nghĩa các model dữ liệu cho MirageJS


export interface Room {
    id: number;
    name: string;
    image: string;
    priceVND: number;
    size: number;
    view: string; bedType: string | {
        default: string;
        options?: string[];
    }; //kiểu là 1 phòng có giường lớn hoặc 2 giường đơn(nếu có ) 2 là phòng chỉ có 1 hoặc 2 giường lớn
    amenities: string[];
    discount?: number;
    isSale?: boolean;
    roomType: "deluxe" | "premium" | "suite" | "presidential" | "theLevel";
    rating?: number;
    maxGuests: number;
    description: string;
    images: string[];
}

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


export interface Booking {
    id: number;
    roomId: number;
    roomName: string;

    hotelName: string;
    userId?: number;
    userName?: string;
    checkInDate: string;
    checkOutDate: string;
    guests: number;
    totalPrice: number;
    optionId: string;
    status: "pending" | "confirmed" | "cancelled" | "completed";
    createdAt: string;
    paymentStatus: "unpaid" | "paid" | "refunded";
    specialRequests?: string;
}

// Reviews interface
export interface Review {
    id: number;
    roomId: number;
    userName: string;
    userAvatar?: string;
    rating: number; // Thang điểm 1-10
    title: string;
    comment: string;
    stayDate: string; // Ngày thực tế khách ở
    reviewDate: string; // Ngày viết review
    helpful: number;
    notHelpful: number; roomType: string; // Loại phòng đã ở
    travelType: "business" | "couple" | "solo" | "family_young" | "family_teen" | "group";
    adminReply?: {
        content: string;
        date: string;
        adminName: string;
    };
}

// Hotel Rating Details Interface
export interface HotelRatingDetails {
    overallRating: number;
    totalReviews: number;
    ratingText: string;
    location: string;
    ratings: {
        cleanliness: number;
        location: number;
        facilities: number;
        service: number;
        valueForMoney: number;
    };
}


// Dữ liệu mẫu cho phòng
export const sampleRooms: Room[] = [
    {
        id: 1,
        name: "Phòng Loại Sang (Deluxe Room)",
        image: "../../public/images/room/Deluxe_Room/1.jpg",
        priceVND: 3000000,
        size: 32,
        view: "Hướng thành phố", bedType: {
            default: "Giường đôi",
            options: ["Giường đôi", "2 giường đơn"]
        }, amenities: ["wifi", "tv", "điều hoà", "minibar", "két sắt", "dép đi trong phòng", "khăn tắm cao cấp", "nước uống miễn phí", "máy sấy tóc", "bàn làm việc"],
        discount: 64, // sẽ khớp với giá trong room options
        roomType: "deluxe",
        rating: 9.5,
        maxGuests: 2,
        description: "Phòng được thiết kế hiện đại với đầy đủ tiện nghi cao cấp. Không gian rộng rãi, thoáng mát với view thành phố tuyệt đẹp.",
        images: [
            "../../public/images/room/Deluxe_Room/1.jpg",
            "../../public/images/room/Deluxe_Room/2.jpg",
            "../../public/images/room/Deluxe_Room/3.webp",
            "../../public/images/room/Deluxe_Room/4.webp",
        ],

    }, {
        id: 2,
        name: "Phòng cao cấp trong góc (Premium Corner Room)",
        image: "https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80",
        priceVND: 3000000,
        size: 55,
        view: "Tầm nhìn cảnh biển", bedType: {
            default: "Giường đôi",
            options: ["Giường đôi", "2 giường đơn"]
        }, amenities: ["wifi", "tv", "điều hoà", "view biển", "ban công riêng", "minibar", "két sắt", "máy pha cà phê", "khăn tắm cao cấp"],
        discount: 20,
        roomType: "premium",
        rating: 9.4,
        maxGuests: 2,
        description: "Phòng góc với không gian rộng rãi và view biển tuyệt đẹp. Thiết kế sang trọng với đầy đủ tiện nghi cao cấp.",
        images: [
            "../../public/images/room/Premium_Corner_Room/1.jpg",
            "../../public/images/room/Premium_Corner_Room/2.jpg",
            "../../public/images/room/Premium_Corner_Room/3.webp",
            "../../public/images/room/Premium_Corner_Room/4.jpg",
            "../../public/images/room/Premium_Corner_Room/5.webp",
            "../../public/images/room/Premium_Corner_Room/6.webp",
        ],

    }, {
        id: 3,
        name: "Phòng Suite (Suite Room)",
        image: "https://images.unsplash.com/photo-1611892440504-42a792e24d32?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80",
        priceVND: 6200000,
        size: 78,
        view: "Tầm nhìn toàn cảnh biển", bedType: {
            default: "Giường đôi",
            options: ["Giường đôi", "2 giường đơn"]
        }, amenities: ["wifi", "tv", "điều hoà", "phòng khách riêng", "view toàn cảnh biển", "minibar cao cấp", "két sắt", "máy pha cà phê", "khăn tắm cao cấp", "dịch vụ phòng 24/7"],
        discount: 15,
        roomType: "suite",
        rating: 9.8,
        maxGuests: 3,
        description: "Suite rộng rãi với phòng khách riêng biệt và view toàn cảnh biển. Thiết kế sang trọng với các tiện nghi cao cấp.",
        images: [
            "https://images.unsplash.com/photo-1611892440504-42a792e24d32?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80",
            "https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80",
            "https://images.unsplash.com/photo-1571508601891-ca5e7a713859?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
        ],

    }, {
        id: 10,
        name: "Royal Presidential Suite",
        image: "https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80",
        priceVND: 18750000,
        size: 180,
        view: "Tầm nhìn 360 độ thành phố", bedType: {
            default: "Giường đôi",
            options: ["Giường đôi", "2 giường đơn"]
        }, amenities: ["wifi", "tv", "điều hoà", "view thành phố", "phòng khách riêng", "phòng ăn riêng", "quản gia 24/7", "minibar cao cấp", "két sắt lớn", "máy pha cà phê espresso", "khăn tắm cao cấp", "dịch vụ phòng VIP", "ban công panorama"],
        roomType: "presidential",
        rating: 10,
        maxGuests: 4,
        description: "Suite Tổng Thống sang trọng với không gian rộng lớn và view 360 độ. Bao gồm phòng khách, phòng ăn và phòng ngủ riêng biệt.",
        images: [
            "https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1074&q=80",
            "https://images.unsplash.com/photo-1578683010236-d716f9a3f461?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80",
            "https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
        ],

    }, {
        id: 11, name: "The Level Suite",
        image: "https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80",
        priceVND: 9500000,
        size: 85,
        view: "Tầm nhìn biển",
        bedType: {
            default: "Giường đôi",
            options: ["Giường đôi", "2 giường đơn"]
        }, amenities: ["wifi", "tv", "điều hoà", "lounge riêng", "dịch vụ quản gia", "view biển", "minibar cao cấp", "két sắt", "máy pha cà phê", "khăn tắm cao cấp", "dịch vụ phòng VIP"],
        roomType: "theLevel",
        rating: 9.5,
        maxGuests: 2,
        description: "Suite hạng The Level với các đặc quyền riêng biệt: lounge riêng, dịch vụ quản gia, và các tiện ích cao cấp.",
        images: [
            "https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80",
            "https://images.unsplash.com/photo-1571508601891-ca5e7a713859?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80",
            "https://images.unsplash.com/photo-1578683010236-d716f9a3f461?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
        ],

    }, {
        id: 12, name: "The Level Panoramic Suite",
        image: "https://images.unsplash.com/photo-1611892440504-42a792e24d32?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80",
        priceVND: 10500000,
        size: 95,
        view: "Tầm nhìn panorama",
        bedType: {
            default: "Giường đôi",
            options: ["Giường đôi", "2 giường đơn"]
        }, amenities: ["wifi", "tv", "điều hoà", "cityView", "lounge riêng", "dịch vụ quản gia", "view panorama", "minibar cao cấp", "két sắt", "máy pha cà phê", "khăn tắm cao cấp", "dịch vụ phòng VIP"],
        roomType: "theLevel",
        rating: 9.5,
        maxGuests: 2,
        description: "Suite The Level với view panorama tuyệt đẹp. Bao gồm lounge riêng, dịch vụ quản gia, và các đặc quyền đặc biệt.",
        images: [
            "https://images.unsplash.com/photo-1611892440504-42a792e24d32?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80",
            "https://images.unsplash.com/photo-1596394516093-501ba68a0ba6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80",
            "https://images.unsplash.com/photo-1571508601891-ca5e7a713859?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
        ],

    },
    {
        id: 13, name: "The Level Premium Corner",
        image: "https://images.unsplash.com/photo-1578683010236-d716f9a3f461?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80",
        priceVND: 2500000,
        size: 32,
        view: "Tầm nhìn thành phố",
        bedType: {
            default: "Giường đôi",
            options: ["Giường đôi", "2 giường đơn"]
        }, amenities: ["wifi", "tv", "điều hoà", "lounge riêng", "dịch vụ quản gia", "view thành phố", "minibar", "két sắt", "máy pha cà phê", "khăn tắm cao cấp"],
        roomType: "theLevel",
        rating: 9.5,
        maxGuests: 2,
        description: "Phòng tiêu chuẩn với thiết kế hiện đại, đầy đủ tiện nghi cơ bản cho kỳ nghỉ thoải mái.",
        images: [
            "https://images.unsplash.com/photo-1578683010236-d716f9a3f461?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80",
            "https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80",
            "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1170&q=80"
        ],

    }
];

// Dữ liệu mẫu cho room options
export const sampleRoomOptions: RoomOption[] = [
    // Deluxe room options
    {
        id: "deluxe_standard",
        name: "Tỷ lệ tiêu chuẩn",
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

// Dữ liệu mẫu cho reviews
export const sampleReviews: Review[] = [
    {
        id: 1,
        roomId: 1,
        userName: "Nguyễn Văn Nam",
        userAvatar: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150",
        rating: 9,
        title: "Phòng tuyệt vời, dịch vụ hoàn hảo!",
        comment: "Tôi đã có một kỳ nghỉ tuyệt vời tại đây. Phòng rất sạch sẽ, view đẹp và nhân viên phục vụ rất tận tình. Chắc chắn sẽ quay lại!",
        stayDate: "2025-01-10",
        reviewDate: "2024-01-15",
        helpful: 12,
        notHelpful: 2,
        roomType: "Phòng Loại Sang (Deluxe Room)",
        travelType: "couple",
        adminReply: {
            content: "Cảm ơn anh Nguyễn đã chia sẻ trải nghiệm tuyệt vời! Chúng tôi rất vui khi anh hài lòng với dịch vụ. Hẹn gặp lại anh trong những chuyến đi tiếp theo!",
            date: "2024-01-16",
            adminName: "LavishStay Thanh Hoá"
        }
    },
    {
        id: 2,
        roomId: 1,
        userName: "Trần Thị Mai",
        userAvatar: "https://images.unsplash.com/photo-1494790108755-2616b612b786?w=150",
        rating: 8,
        title: "Kỳ nghỉ thoải mái",
        comment: "Phòng đẹp, tiện nghi đầy đủ. Chỉ có điều check-in hơi lâu. Nhìn chung khá hài lòng.",
        stayDate: "2024-01-08",
        reviewDate: "2025-01-10",
        helpful: 8,
        notHelpful: 1,
        roomType: "Phòng Loại Sang (Deluxe Room)",
        travelType: "family_young"
    },
    {
        id: 3,
        roomId: 10,
        userName: "Lê Minh Khôi",
        userAvatar: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150",
        rating: 10,
        title: "Presidential Suite đẳng cấp thế giới!",
        comment: "Không thể tin được có thể trải nghiệm sự sang trọng như vậy. Suite rộng lớn, view 360 độ tuyệt đẹp, dịch vụ quản gia chuyên nghiệp.",
        stayDate: "2024-01-18",
        reviewDate: "2024-01-20",
        helpful: 25,
        notHelpful: 0,
        roomType: "Royal Presidential Suite",
        travelType: "business",
        adminReply: {
            content: "Cảm ơn anh Lê đã lựa chọn Presidential Suite của chúng tôi! Chúng tôi rất vinh dự được phục vụ anh và hy vọng sẽ được đón tiếp anh trong những dịp đặc biệt khác.",
            date: "2024-01-21",
            adminName: "LavishStay Thanh Hoá"
        }
    },
    {
        id: 4,
        roomId: 11,
        userName: "Phạm Thúy Hằng",
        userAvatar: "https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150",
        rating: 9,
        title: "The Level - đẳng cấp khác biệt",
        comment: "Lounge riêng tuyệt vời, dịch vụ quản gia chu đáo. Đây là lần đầu tôi được trải nghiệm The Level và thực sự ấn tượng.",
        stayDate: "2024-01-16",
        reviewDate: "2024-01-18",
        helpful: 15,
        notHelpful: 1,
        roomType: "The Level Suite",
        travelType: "couple"
    },
    {
        id: 5,
        roomId: 2,
        userName: "Võ Thanh Hải",
        userAvatar: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150",
        rating: 8,
        title: "Premium Corner Room - view tuyệt đẹp",
        comment: "Phòng góc với view biển rất đẹp, đặc biệt là lúc hoàng hôriêng rất thoải mái để thư giãn.",
        stayDate: "2024-01-12",
        reviewDate: "2024-01-14",
        helpful: 10,
        notHelpful: 0,
        roomType: "Phòng cao cấp trong góc (Premium Corner Room)",
        travelType: "solo"
    },
    {
        id: 6,
        roomId: 3,
        userName: "Nguyễn Gia Đình",
        userAvatar: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150",
        rating: 9,
        title: "Suite hoàn hảo cho gia đình",
        comment: "Phòng khách riêng biệt rất tiện cho gia đình có trẻ nhỏ. Các bé có không gian chơi trong khi bố mẹ nghỉ ngơi.",
        stayDate: "2024-01-20",
        reviewDate: "2024-01-22",
        helpful: 18,
        notHelpful: 2,
        roomType: "Phòng Suite (Suite Room)",
        travelType: "family_teen",
        adminReply: {
            content: "Cảm ơn gia đình anh Nguyễn! Chúng tôi rất vui khi các bé và gia đình đã có kỳ nghỉ vui vẻ. Hy vọng sẽ được đón tiếp gia đình trong những chuyến du lịch tiếp theo!",
            date: "2024-01-23",
            adminName: "LavishStay Thanh Hoá"
        }
    },
    {
        id: 7,
        roomId: 1,
        userName: "Đoàn Du Lịch ABC",
        rating: 7,
        title: "Phù hợp cho nhóm bạn",
        comment: "Đặt nhiều phòng cạnh nhau rất tiện. Nhân viên hỗ trợ tốt trong việc sắp xếp phòng cho cả nhóm.",
        stayDate: "2024-01-25",
        reviewDate: "2024-01-27",
        helpful: 6,
        notHelpful: 3,
        roomType: "Phòng Loại Sang (Deluxe Room)",
        travelType: "group"
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

