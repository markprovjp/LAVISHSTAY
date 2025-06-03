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
    // thông báo sắp cháy phòng
    urgencyRoomMessage?: string; // Thông báo nếu phòng sắp hết
    maxGuests: number; // Số khách tối đa mà loại phòng này có thể chứa
    description: string;
    images: string[];
    totalRooms: number; // Tổng số phòng có sẵn của loại này
    availableRooms: number; // Số phòng còn lại
    options: RoomOption[]; // Các lựa chọn giá và dịch vụ cho loại phòng này
    lavishPlusDiscount?: number; // Giảm giá cho thành viên LavishPlus

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
export const sampleRooms: Room[] = [{
    id: 1,
    name: "Phòng Loại Sang (Deluxe Room)",
    image: "../../public/images/room/Deluxe_Room/1.jpg",
    priceVND: 2000000, // TĂNG GIÁ GỐC LÊN 2.5M// Giá thấp nhất từ các options
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
    urgencyRoomMessage: "Chỉ còn 7 phòng . Hãy nhanh tay đặt  !",
    lavishPlusDiscount: 10, // Giảm 10% cho thành viên LavishPlus
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

    ]
}, {
    id: 2,
    name: "Phòng cao cấp trong góc (Premium Corner Room)",
    image: "../../public/images/room/Premium_Corner_Room/1.jpg",
    priceVND: 1600000,
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
    urgencyRoomMessage: "Chỉ còn 5 phòng . Hãy nhanh tay đặt  !",
    lavishPlusDiscount: 15, // Giảm 15% cho thành viên LavishPlus
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

    ]
}, {
    id: 3,
    name: "Phòng The Level Cao cấp (The Level Premium Room)",
    image: "../../public/images/room/The_Level_Premium_Room/1.jpg",
    priceVND: 3900000,
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
    urgencyRoomMessage: "Chỉ còn 6 phòng . Hãy nhanh tay đặt  !",
    lavishPlusDiscount: 15, // Giảm 15% cho thành viên LavishPlus
    maxGuests: 2,
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

    ]
},
{
        id: 4,
        name: "Phòng Suite (Suite Room)",
        image: "../../public/images/room/Suite/1.webp",
        priceVND: 6200000,
        size: 78,
        view: "Tầm nhìn toàn cảnh biển",
        bedType: {
            default: "1 giường đôi cực lớn  ",
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
            "Phòng tắm riêng trong phòng",
            "TV màn hình phẳng",
            "Hệ thống cách âm",
            "Minibar",
            "WiFi miễn phí",
            "Suite riêng tư",
            "Có cung cấp nôi/cũi theo yêu cầu"

        ],
        discount: 15,
        roomType: "suite",
        rating: 9.8,
                  urgencyRoomMessage: "Chỉ còn 6 phòng . Hãy nhanh tay đặt  !",
    lavishPlusDiscount: 15, // Giảm 15% cho thành viên LavishPlus
    maxGuests: 2,
    totalRooms: 11, // Tổng số phòng The Level
    availableRooms: 9, // Số phòng còn lại
        description: "Suite rộng rãi này được bố trí 1 phòng ngủ, khu vực ghế ngồi và 1 phòng tắm với buồng tắm đứng cùng bồn tắm. Suite này có máy điều hòa, TV màn hình phẳng, tường cách âm, minibar, khu vực ăn uống cũng như tầm nhìn ra thành phố. Căn này được trang bị 1 giường.",
        images: [
            "../../public/images/room/Suite/1.webp",
            "../../public/images/room/Suite/2.webp",
            "../../public/images/room/Suite/3.webp",
            "../../public/images/room/Suite/4.webp",
            "../../public/images/room/Suite/5.jpg",
            "../../public/images/room/Suite/6.jpg",
            "../../public/images/room/Suite/7.webp",
        ],
        options: [

        ]
    }, 
{
        id: 5,
        name: "Phòng The Level Hảo Hạng Ở Góc (The Level Premium Corner Room)",
        image: "../../public/images/room/The_Level_Premium_Corner_Room/1.webp",
        priceVND: 3244000,
        size: 45,
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
            "Phòng tắm riêng trong phòng",
            "TV màn hình phẳng",
            "Hệ thống cách âm",
            "Minibar",
            "WiFi miễn phí",
            "Máy pha cà phê",

        ],
        discount: 17,
        roomType: "theLevel",
        rating: 10,
            urgencyRoomMessage: "Chỉ còn 6 phòng . Hãy nhanh tay đặt  !",
    lavishPlusDiscount: 15, // Giảm 15% cho thành viên LavishPlus
    maxGuests: 2,
    totalRooms: 13, // Tổng số phòng The Level
    availableRooms: 7, // Số phòng còn lại
        description: "Phòng giường đôi này được bố trí máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng và bồn tắm. Phòng giường đôi có sàn trải thảm, khu vực ghế ngồi với TV màn hình phẳng, minibar, máy pha cà phê cũng như tầm nhìn ra thành phố.",
        images: [
            "../../public/images/room/The_Level_Premium_Corner_Room/1.webp",
            "../../public/images/room/The_Level_Premium_Corner_Room/2.jpg",
            "../../public/images/room/The_Level_Premium_Corner_Room/3.webp",
            "../../public/images/room/The_Level_Premium_Corner_Room/4.jpg",
            "../../public/images/room/The_Level_Premium_Corner_Room/5.webp",
            "../../public/images/room/The_Level_Premium_Corner_Room/6.jpg"
        ],
        options: [

        ],
    },
  {
        id: 6,
        name: "Phòng Suite The Level (The Level Suite Room)",
        image: "../../public/images/room/The_Level_Suite_Room/1.webp",
        priceVND: 14681000,
        size: 93,
        view: "Nhìn ra thành phố",
        bedType: {
            default: "1 giường đôi cực lớn  "
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
            "Phòng tắm riêng trong phòng",
            "TV màn hình phẳng",
            "Hệ thống cách âm",
            "Minibar",
            "WiFi miễn phí",
            "Máy pha cà phê",
        ],
        discount: 75,
        roomType: "theLevel",
        rating: 10,
            urgencyRoomMessage: "Chỉ còn 8 phòng . Hãy nhanh tay đặt  !",
    lavishPlusDiscount: 15, // Giảm 15% cho thành viên LavishPlus
    maxGuests: 2,
    totalRooms: 12, // Tổng số phòng The Level
    availableRooms: 13, // Số phòng còn lại
        description: "Phòng giường đôi này được bố trí máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng và bồn tắm. Phòng giường đôi có sàn trải thảm, khu vực ghế ngồi với TV màn hình phẳng, minibar, máy pha cà phê cũng như tầm nhìn ra thành phố. Căn này được trang bị 1 giường.",
        images: [
            "../../public/images/room/The_Level_Suite_Room/1.webp",
            "../../public/images/room/The_Level_Suite_Room/2.webp",
            "../../public/images/room/The_Level_Suite_Room/3.webp",
            "../../public/images/room/The_Level_Suite_Room/4.jpg",
            "../../public/images/room/The_Level_Suite_Room/5.jpg",
            "../../public/images/room/The_Level_Suite_Room/6.jpg",
            "../../public/images/room/The_Level_Suite_Room/7.webp",
            "../../public/images/room/The_Level_Suite_Room/8.webp",
        ],
        options: [

        ]
    },
{
    id: 7,
    name: "Suite Hạng Tổng Thống (Presidential Suite)",
    image: "../../public/images/room/Presidential_Suite/1.webp",
    priceVND: 50000000,
    size: 270,
    view: "Hướng ngoài trời",
    bedType: {
        default: "Mỗi phòng có 1 giường cực lớn",
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
        "Phòng tắm riêng trong phòng",
        "TV màn hình phẳng",
        "Hệ thống cách âm",
        "Minibar",
        "WiFi miễn phí",
        "Suite riêng tư",
        "Máy pha cà phê",
        "Có cung cấp nôi/cũi theo yêu cầu"

    ],
    discount: 5,
    roomType: "presidential",
    rating: 10,
    urgencyRoomMessage: "Chỉ còn 6 phòng . Hãy nhanh tay đặt  !",
    lavishPlusDiscount: 15, // Giảm 15% cho thành viên LavishPlus
    maxGuests: 4,
    totalRooms: 8,
    availableRooms: 13, // Số phòng còn lại
    description: "Suite rộng rãi này được bố trí 1 phòng khách, 2 phòng ngủ riêng biệt và 2 phòng tắm với buồng tắm đứng cùng đồ vệ sinh cá nhân miễn phí. Suite này có máy điều hòa, khu vực ghế ngồi với TV màn hình phẳng, tường cách âm, minibar, máy pha cà phê cũng như khu vực ăn uống. Căn này được trang bị 2 giường.",
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
    options: [

    ]
},
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
