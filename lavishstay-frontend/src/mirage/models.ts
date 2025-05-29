// models.ts - Định nghĩa các model dữ liệu cho MirageJS


export interface Room {
    id: number;
    name: string;
    image: string;
    priceVND: number;
    size: number;
    view: string;
    bedType: string | {
        default: string;
        options?: string[];
    }; //kiểu là 1 phòng có giường lớn hoặc 2 giường đơn(nếu có ) 2 là phòng chỉ có 1 hoặc 2 giường lớn
    amenities: string[]; // Tất cả tiện ích của phòng
    mainAmenities?: string[]; // Tiện ích chính hiển thị ở home (4-5 tiện ích)
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
        mainAmenities: ["hướng thành phố", "không hút thuốc", "bồn tắm/vòi sen riêng", "wifi miễn phí"],
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
        image: "../../public/images/room/Premium_Corner_Room/1.jpg",
        priceVND: 3000000,
        size: 55,
        view: "Tầm nhìn cảnh biển", bedType: {
            default: "Giường đôi",
            options: ["Giường đôi", "2 giường đơn"]
        },
        amenities: [
            "Wifi miễn phí trong tất cả các phòng!",
            "Truyền hình cáp/vệ tinh",
            "Điều hòa",
            "máy pha trà/cà phê",
            "tủ lạnh nhỏ trong phòng",
            "khăn tắm cao cấp",
            "máy sấy tóc",
            "gương",
            "phòng tắm đứng",
            "nước đóng chai miễn phí",
            "dép đi trong nhà",
            "dịch vụ báo thức",
            "đồng hồ báo thức",
            "cách âm",
            "rèm che ánh sáng",
            "ô cắm điện gần giường",
            "dọn phòng hằng ngày"
        ],
        mainAmenities: [
            "Wifi miễn phí trong tất cả các phòng!",
            "Phòng tắm đứng & bồn tắm",
            "Không hút thuốc",
            "Điều hòa"
        ],
        discount: 20,
        roomType: "premium",
        rating: 9.4,
        maxGuests: 2,
        description: "Phòng góc với không gian rộng rãi và view biển tuyệt đẹp. Thiết kế sang trọng với đầy đủ tiện nghi cao cấp.",
        images: [
            "../../public/images/room/Premium_Corner_Room/1.jpg",
            "../../public/images/room/Premium_Corner_Room/2.webp",
            "../../public/images/room/Premium_Corner_Room/3.jpg",
            "../../public/images/room/Premium_Corner_Room/4.jpg",
            "../../public/images/room/Premium_Corner_Room/5.jpg",
            "../../public/images/room/Premium_Corner_Room/6.webp",
            "../../public/images/room/Premium_Corner_Room/7.jpg",
            "../../public/images/room/Premium_Corner_Room/8.webp",
        ],

    }, {
        id: 3,
        name: "Phòng Suite (Suite Room)",
        image: "../../public/images/room/Suite/1.webp",
        priceVND: 6200000,
        size: 78,
        view: "Tầm nhìn toàn cảnh biển", bedType: {
            default: "Giường đôi",
            options: ["Giường đôi", "2 giường đơn"]
        }, amenities: ["wifi", "tv", "điều hoà", "phòng khách riêng", "view toàn cảnh biển", "minibar cao cấp", "két sắt", "máy pha cà phê", "khăn tắm cao cấp", "dịch vụ phòng 24/7"],
        mainAmenities: ["phòng khách riêng", "view toàn cảnh biển", "wifi miễn phí", "minibar cao cấp"],
        discount: 15,
        roomType: "suite",
        rating: 9.8,
        maxGuests: 3,
        description: "Suite rộng rãi với phòng khách riêng biệt và view toàn cảnh biển. Thiết kế sang trọng với các tiện nghi cao cấp.",
        images: [
            "../../public/images/room/Suite/1.webp",
            "../../public/images/room/Suite/2.webp",
            "../../public/images/room/Suite/3.webp",
            "../../public/images/room/Suite/4.webp",
            "../../public/images/room/Suite/5.jpg",
            "../../public/images/room/Suite/6.jpg",
            "../../public/images/room/Suite/7.webp",
        ],

    }, {
        id: 4,
        name: "Phòng The Level Cao cấp (The Level Premium Room)",
        image: "../../public/images/room/The_Level_Premium_Room/1.jpg",
        priceVND: 2953000,
        size: 33,
        view: "Hướng thành phố", bedType: {
            default: "Giường đôi",
            options: ["Giường đôi", "2 giường đơn"]
        }, amenities: [
            "Wifi miễn phí trong tất cả các phòng!",
            "Truyền hình cáp/vệ tinh",
            "TV màn hình phẳng",
            "Điều hòa",
            "view biển",
            "máy pha trà/cà phê",
            "tủ lạnh nhỏ trong phòng",
            "nước đóng chai miễn phí",
            "khăn tắm cao cấp",
            "máy sấy tóc",
            "gương",
            "phòng tắm đứng",
            "phòng tắm riêng",
            "áo choàng tắm",
            "vật dụng tắm rửa",
            "các loại khăn",
            "bàn trang điểm phù hợp cho người khuyết tật",
            "khả năng tiếp cận cho người khuyết tật",
            "bếp",
            "ấm nước điện",
            "điện thoại",
            "miễn phí sử dụng khu vực dịch vụ hành chánh",
            "cách âm",
            "dép đi trong nhà",
            "đồ dùng cho giấc ngủ thoải mái",
            "nhân viên chăm sóc khách hàng",
            "ô cắm điện gần giường",
            "bàn làm việc",
            "ghế sofa",
            "khu vực ăn uống riêng",
            "khu vực tiếp khách",
            "thảm",
            "thùng rác",
            "dọn phòng hằng ngày"
        ],
        mainAmenities: ["Phòng chờ thương gia", "Bể bơi riêng", "Bồn tắm/vòi sen riêng", "Không hút thuốc"],
        roomType: "theLevel",
        rating: 9.8,
        maxGuests: 2,
        description: "The Level Cao cấp sang trọng với những dịch vụ cao cấp như phòng chờ riêng, bể bơi riêng . Phòng có view thành phố tuyệt đẹp.",
        images: [
            "../../public/images/room/The_Level_Premium_Room/1.jpg",
            "../../public/images/room/The_Level_Premium_Room/2.jpg",
            "../../public/images/room/The_Level_Premium_Room/3.webp",
            "../../public/images/room/The_Level_Premium_Room/4.jpg",
            "../../public/images/room/The_Level_Premium_Room/5.webp",
            "../../public/images/room/The_Level_Premium_Room/6.webp",
        ],

    }, {
        id: 5, name: "Phòng The Level Hảo Hạng Ở Góc (The Level Premium Corner Room)",
        image: "../../public/images/room/The_Level_Premium_Corner_Room/1.webp",
        priceVND: 3244000,
        size: 45,
        view: "Hướng Thành phố", bedType: {
            default: "Giường đôi",
            options: ["Giường đôi", "2 giường đơn"]
        }, amenities: [
            "Wifi miễn phí trong tất cả các phòng!",
            "Truyền hình cáp/vệ tinh",
            "Điều hòa",
            "máy pha trà/cà phê",
            "tủ lạnh nhỏ trong phòng",
            "khăn tắm cao cấp",
            "máy sấy tóc",
            "gương",
            "phòng tắm đứng",
            "nước đóng chai miễn phí",
            "dép đi trong nhà",
            "dịch vụ báo thức",
            "đồng hồ báo thức",
            "cách âm",
            "rèm che ánh sáng",
            "ô cắm điện gần giường",
            "dọn phòng hằng ngày"
        ],
        mainAmenities: ["Bể bơi riêng", "Phòng chờ Thương Gia", "Không hút thuốc", "Bồn tắm/vòi sen riêng"],
        discount: 17,
        roomType: "theLevel",
        rating: 10,
        maxGuests: 2,
        description: "Phòng The Level Hảo Hạng Ở Góc với thiết kế sang trọng, đầy đủ tiện nghi cao cấp. View thành phố tuyệt đẹp từ cửa sổ lớn.",
        images: [
            ".../../public/images/room/The_Level_Premium_Corner_Room/1.webp",
            ".../../public/images/room/The_Level_Premium_Corner_Room/2.jpg",
            ".../../public/images/room/The_Level_Premium_Corner_Room/3.webp",
            ".../../public/images/room/The_Level_Premium_Corner_Room/4.jpg",
            ".../../public/images/room/The_Level_Premium_Corner_Room/5.webp",
            ".../../public/images/room/The_Level_Premium_Corner_Room/6.jpg"
        ],

    }, {
        id: 6, name: "Phòng Cao Cấp The Level cho 3 Người lớn (The Level Premium Room 3 Adults)",
        image: "../../public/images/room/The_Level_Premium_Room_3_Adults/1.webp",
        priceVND: 11803000,
        size: 45,
        view: "Hướng thành phố", bedType: {
            default: "Giường đôi",
            options: ["Giường đôi", "2 giường đơn"]
        }, amenities: [
            "Wifi miễn phí trong tất cả các phòng!",
            "Truyền hình cáp/vệ tinh",
            "Điều hòa",
            "máy pha trà/cà phê",
            "tủ lạnh nhỏ trong phòng",
            "khăn tắm cao cấp",
            "máy sấy tóc",
            "gương",
            "phòng tắm đứng",
            "nước đóng chai miễn phí",
            "dép đi trong nhà",
            "dịch vụ báo thức",
            "đồng hồ báo thức",
            "cách âm",
            "rèm che ánh sáng",
            "ô cắm điện gần giường",
            "dọn phòng hằng ngày"
        ],
        mainAmenities: ["Cho phép hút thuốc"],
        discount: 75,
        roomType: "theLevel",
        rating: 9.1,
        maxGuests: 3,
        description: "Phòng The Level Premium cho 3 Người lớn với không gian rộng rãi, đầy đủ tiện nghi cao cấp. Phù hợp cho gia đình hoặc nhóm bạn.",
        images: [
            "../../public/images/room/The_Level_Premium_Room_3_Adults/1.webp",
            "../../public/images/room/The_Level_Premium_Room_3_Adults/2.webp",
            "../../public/images/room/The_Level_Premium_Room_3_Adults/3.webp",
            "../../public/images/room/The_Level_Premium_Room_3_Adults/4.webp",
            "../../public/images/room/The_Level_Premium_Room_3_Adults/5.webp",
            "../../public/images/room/The_Level_Premium_Room_3_Adults/6.webp",
            "../../public/images/room/The_Level_Premium_Room_3_Adults/7.webp"
        ],

    },
    {
        id: 7, name: "Phòng The Level Hảo Hạng Ở Góc 3 Người Lớn (The Level Premium Corner Room 3 Adults)",
        image: "../../public/images/room/The_Level_Premium_Corner_Room_3_Adults/1.webp",
        priceVND: 12581000,
        size: 45,
        view: "Tầm nhìn thành phố", bedType: {
            default: "Giường đôi",
            options: ["Giường đôi", "2 giường đơn"]
        }, amenities: [
            "Wifi miễn phí trong tất cả các phòng!",
            "Truyền hình cáp/vệ tinh",
            "Điều hòa",
            "máy pha trà/cà phê",
            "tủ lạnh nhỏ trong phòng",
            "nước đóng chai miễn phí",
            "máy sấy tóc",
            "phòng tắm riêng",
            "vòi sen",
            "vật dụng tắm rửa",
            "điện thoại",
            "cách âm",
            "dép đi trong nhà",
            "rèm che ánh sáng",
            "sưởi",
            "dọn phòng hằng ngày",
            "bàn làm việc",
            "tiện nghi là/ủi",
            "tủ quần áo"
        ],
        mainAmenities: ["lounge riêng", "dịch vụ quản gia", "view thành phố", "minibar"],
        discount: 75,
        roomType: "theLevel",
        rating: 10,
        maxGuests: 3,
        description: "Phòng The Level Hảo Hạng Ở Góc 3 Người Lớn với thiết kế sang trọng, đầy đủ tiện nghi cao cấp. Phù hợp cho gia đình hoặc nhóm bạn với view thành phố tuyệt đẹp.",
        images: [
            "../../public/images/room/The_Level_Premium_Corner_Room_3_Adults/1.webp",
            "../../public/images/room/The_Level_Premium_Corner_Room_3_Adults/2.webp",
            "../../public/images/room/The_Level_Premium_Corner_Room_3_Adults/3.webp",
            "../../public/images/room/The_Level_Premium_Corner_Room_3_Adults/4.webp",
            "../../public/images/room/The_Level_Premium_Corner_Room_3_Adults/5.webp",
        ],

    },
    {
        id: 8, name: "Phòng Suite The Level (The Level Suite Room)",
        image: "../../public/images/room/The_Level_Suite_Room/1.webp",
        priceVND: 14681000,
        size: 93,
        view: "Hướng thành phố", bedType: {
            default: "Giường Lớn"
        }, amenities: [
            "Wifi miễn phí trong tất cả các phòng!",
            "Truyền hình cáp/vệ tinh",
            "Điều hòa",
            "máy pha trà/cà phê",
            "tủ lạnh nhỏ trong phòng",
            "nước đóng chai miễn phí",
            "máy sấy tóc",
            "phòng tắm riêng",
            "vòi sen",
            "vật dụng tắm rửa",
            "điện thoại",
            "cách âm",
            "dép đi trong nhà",
            "rèm che ánh sáng",
            "sưởi",
            "dọn phòng hằng ngày",
            "bàn làm việc",
            "tiện nghi là/ủi",
            "tủ quần áo"
        ],
        mainAmenities: ["Không hút thuốc", "Bồn tắm/vòi sen riêng", "Bể bơi riêng", "Phòng chờ Thương Gia"],
        discount: 75,
        roomType: "theLevel",
        rating: 10,
        maxGuests: 2,
        description: "Phòng Suite The Level với thiết kế sang trọng, đầy đủ tiện nghi cao cấp. Phòng có view thành phố tuyệt đẹp và các dịch vụ cao cấp như phòng chờ riêng.",
        images: [
            "../../public/images/room/The_Level_Suite_Room/1.webp",
            "../../public/images/room/The_Level_Suite_Room/1.webp",
            "../../public/images/room/The_Level_Suite_Room/1.webp",
            "../../public/images/room/The_Level_Suite_Room/1.webp",
            "../../public/images/room/The_Level_Suite_Room/1.webp",
        ],

    },
    {
        id: 9, name: "Phòng Suite The Level 3 Người Lớn (The Level Suite Room 3 Adults)",
        image: "../../public/images/room/The_Level_Suite_Room_3_Adults/1.webp",
        priceVND: 4558000,
        size: 93,
        view: "Hướng thành phố", bedType: {
            default: "Giường Lớn"
        }, amenities: [
            "Wifi miễn phí trong tất cả các phòng!",
            "Truyền hình cáp/vệ tinh",
            "Điều hòa",
            "máy pha trà/cà phê",
            "tủ lạnh nhỏ trong phòng",
            "khăn tắm cao cấp",
            "máy sấy tóc",
            "gương",
            "phòng tắm đứng",
            "nước đóng chai miễn phí",
            "dép đi trong nhà",
            "dịch vụ báo thức",
            "đồng hồ báo thức",
            "cách âm",
            "rèm che ánh sáng",
            "ô cắm điện gần giường",
            "dọn phòng hằng ngày"
        ],
        mainAmenities: ["Được hút thuốc"],
        discount: 10,
        roomType: "theLevel",
        rating: 10,
        maxGuests: 3,
        description: "Phòng Suite The Level 3 Người Lớn với thiết kế sang trọng, đầy đủ tiện nghi cao cấp. Phù hợp cho gia đình hoặc nhóm bạn với view thành phố tuyệt đẹp.",
        images: [
            "../../public/images/room/The_Level_Suite_Room_3_Adults/1.webp",
            "../../public/images/room/The_Level_Suite_Room_3_Adults/1.webp",
            "../../public/images/room/The_Level_Suite_Room_3_Adults/1.webp",
            "../../public/images/room/The_Level_Suite_Room_3_Adults/1.webp",
            "../../public/images/room/The_Level_Suite_Room_3_Adults/1.webp",
        ],
    },
    {
        id: 10, name: "Suite Hạng Tổng Thống (Presidential Suite)",
        image: "../../public/images/room/Presidential_Suite/1.webp",
        priceVND: 41338000,
        size: 270,
        view: "Hướng ngoài trời", bedType: {
            default: "1 giường lớn & 1 giường lớn"
        }, amenities: [
            "Wifi miễn phí trong tất cả các phòng!",
            "TV màn hình phẳng",
            "Truyền hình cáp/vệ tinh",
            "Điều hòa",
            "máy pha trà/cà phê",
            "tủ lạnh nhỏ trong phòng",
            "nước đóng chai miễn phí",
            "phòng tắm riêng",
            "phòng tắm đứng",
            "máy sấy tóc",
            "gương",
            "áo choàng tắm",
            "vật dụng tắm rửa",
            "các loại khăn",
            "bàn trang điểm phù hợp cho người khuyết tật",
            "khả năng tiếp cận cho người khuyết tật",
            "bếp",
            "ấm nước điện",
            "điện thoại",
            "miễn phí sử dụng khu vực dịch vụ hành chánh",
            "cách âm",
            "dép đi trong nhà",
            "đồ dùng cho giấc ngủ thoải mái",
            "nhân viên chăm sóc khách hàng",
            "ô cắm điện gần giường",
            "bàn làm việc",
            "ghế sofa",
            "khu vực ăn uống riêng",
            "khu vực tiếp khách",
            "thảm",
            "thùng rác"
        ],
        mainAmenities: ["Không hút thuốc", "2 phòng tắm", "Phòng tắm vòi sen & bồn tắm", "Bể bơi riêng", "2 phòng ngủ"],
        roomType: "presidential",
        rating: 10,
        maxGuests: 4,
        description: "Phòng Suite The Level 3 Người Lớn với thiết kế sang trọng, đầy đủ tiện nghi cao cấp. Phù hợp cho gia đình hoặc nhóm bạn với view thành phố tuyệt đẹp.",
        images: [
            "../../public/images/room/Presidential_Suite/1.webp",
            "../../public/images/room/Presidential_Suite/2.jpg",
            "../../public/images/room/Presidential_Suite/3.jpg",
            "../../public/images/room/Presidential_Suite/4.jpg",
            "../../public/images/room/Presidential_Suite/5.jpg",
            "../../public/images/room/Presidential_Suite/6.jpg",
            "../../public/images/room/Presidential_Suite/7.jpg",
            "../../public/images/room/Presidential_Suite/8.jpg",
            "../../public/images/room/Presidential_Suite/9.jpg",
            "../../public/images/room/Presidential_Suite/10.webp",
            "../../public/images/room/Presidential_Suite/11.jpg",
            "../../public/images/room/Presidential_Suite/12.webp",
            "../../public/images/room/Presidential_Suite/13.jpg",
            "../../public/images/room/Presidential_Suite/14.webp",
            "../../public/images/room/Presidential_Suite/15.jpg",
            "../../public/images/room/Presidential_Suite/16.jpg",
            "../../public/images/room/Presidential_Suite/17.jpg",
            "../../public/images/room/Presidential_Suite/18.jpg",
        ],
    },
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

