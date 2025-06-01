// models.ts - Định nghĩa các model dữ liệu cho MirageJS

import { RoomOption } from './ro        maxGuests: 2,
        totalRooms: 23, // Tổng số phòng deluxe
        availableRooms: 18, // Số phòng còn lại
        description: "Phòng giường đôi rộng rãi này được bố trí máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng cùng bồn tắm. Phòng giường đôi có sàn trải thảm, khu vực ghế ngồi với TV màn hình phẳng, minibar, tủ để quần áo cũng như tầm nhìn ra thành phố.",option';


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
        image: "../../public/images/room/Deluxe_Room/1.jpg",        priceVND: 1000000, // Giá thấp nhất từ các options
        size: 32,
        view: "Nhìn ra thành phố",
        bedType: {
            default: "1 giường đôi cực lớn  hoặc 2 giường đơn ",
            options: ["Giường đôi", "2 giường đơn"]
        }, amenities: [
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
            "Có cung cấp nôi/cũi theo yêu cầu"

        ],
        discount: 64,
        roomType: "deluxe",
        rating: 9.5,
        maxGuests: 2,
        description: "Phòng giường đôi rộng rãi này được bố trí máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng cùng bồn tắm. Phòng giường đôi có sàn trải thảm, khu vực ghế ngồi với TV màn hình phẳng, minibar, tủ để quần áo cũng như tầm nhìn ra thành phố.",
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
        view: "Tầm nhìn cảnh biển",
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
            "Có cung cấp nôi/cũi theo yêu cầu"

        ],
        discount: 20,
        roomType: "premium",
        rating: 9.4,
        maxGuests: 2,
        description: "Phòng giường đôi rộng rãi này có máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng và bồn tắm.",
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
        maxGuests: 3,
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

    }, {
        id: 4,
        name: "Phòng The Level Cao cấp (The Level Premium Room)",
        image: "../../public/images/room/The_Level_Premium_Room/1.jpg",
        priceVND: 2953000,
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
            "Phòng tắm riêng trong phòng",
            "TV màn hình phẳng",
            "Hệ thống cách âm",
            "Minibar",
            "WiFi miễn phí",
            "Máy pha cà phê",

        ],
        discount: 17,
        roomType: "theLevel",
        rating: 9.8,
        maxGuests: 2,
        description: "Phòng giường đôi này được bố trí máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng và bồn tắm. Phòng giường đôi có sàn trải thảm, khu vực ghế ngồi với TV màn hình phẳng, minibar, máy pha cà phê cũng như tầm nhìn ra thành phố.",
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

    }, {
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
        maxGuests: 2,
        description: "Phòng giường đôi này được bố trí máy điều hòa, tường cách âm cũng như phòng tắm riêng với buồng tắm đứng và bồn tắm. Phòng giường đôi có sàn trải thảm, khu vực ghế ngồi với TV màn hình phẳng, minibar, máy pha cà phê cũng như tầm nhìn ra thành phố.",
        images: [
            "../../public/images/room/The_Level_Premium_Corner_Room/1.webp",
            "../../public/images/room/The_Level_Premium_Corner_Room/2.jpg",
            "../../public/images/room/The_Level_Premium_Corner_Room/3.webp",
            "../../public/images/room/The_Level_Premium_Corner_Room/4.jpg",
            "../../public/images/room/The_Level_Premium_Corner_Room/5.webp",
            "../../public/images/room/The_Level_Premium_Corner_Room/6.jpg"
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
        maxGuests: 2,
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

    },
    {
        id: 7,
        name: "Suite Hạng Tổng Thống (Presidential Suite)",
        image: "../../public/images/room/Presidential_Suite/1.webp",
        priceVND: 41338000,
        size: 270,
        view: "Hướng ngoài trời",
        bedType: {
            default: "2 giường lớn"
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
        roomType: "presidential",
        rating: 10,
        maxGuests: 4,
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

