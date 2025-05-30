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