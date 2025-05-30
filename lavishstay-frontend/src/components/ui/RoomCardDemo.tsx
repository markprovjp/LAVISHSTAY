import React from "react";
import { Row, Col } from "antd";
import RoomCard, { RoomProps } from "./RoomCard";

const RoomCardDemo: React.FC = () => {
    const sampleRooms: RoomProps[] = [
        {
            id: 1,
            name: "Phòng Loại Sang (Deluxe Room)",
            image: "https://images.unsplash.com/photo-1590490360182-c33d57733427?w=400",
            priceVND: 3000000,
            size: 32,
            view: "Hướng thành phố",
            bedType: "Giường đôi",
            amenities: ["Wi-Fi miễn phí", "Điều hòa", "TV màn hình phẳng", "Minibar"],
            mainAmenities: ["Wi-Fi miễn phí", "Điều hòa", "TV màn hình phẳng", "Minibar"],
            discount: 15,
            roomType: "deluxe",
            rating: 4.5,
        },
        {
            id: 2,
            name: "Phòng Cao Cấp (Premium Room)",
            image: "https://images.unsplash.com/photo-1578774204375-1527aa2b4003?w=400",
            priceVND: 4500000,
            size: 45,
            view: "Hướng biển",
            bedType: "Giường King",
            amenities: ["Wi-Fi miễn phí", "Bồn tắm", "Ban công", "Tủ lạnh"],
            mainAmenities: ["Wi-Fi miễn phí", "Bồn tắm", "Ban công", "Tủ lạnh"],
            discount: 20,
            roomType: "premium",
            rating: 4.7,
        },
        {
            id: 3,
            name: "Phòng Suite (Suite Room)",
            image: "https://images.unsplash.com/photo-1566073771259-6a8506099945?w=400",
            priceVND: 7500000,
            size: 75,
            view: "Hướng biển",
            bedType: "Giường King + Sofa bed",
            amenities: ["Phòng khách riêng", "Bếp nhỏ", "Máy giặt", "Ban công lớn"],
            mainAmenities: ["Phòng khách riêng", "Bếp nhỏ", "Máy giặt", "Ban công lớn"],
            roomType: "suite",
            rating: 4.8,
        },
        {
            id: 4,
            name: "The Level Panoramic Suite",
            image: "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?w=400",
            priceVND: 15000000,
            size: 120,
            view: "Toàn cảnh biển",
            bedType: "Giường King + Phòng ngủ phụ",
            amenities: ["Butler service", "Hồ bơi riêng", "Spa trong phòng", "Khách sạn cao cấp"],
            mainAmenities: ["Butler service", "Hồ bơi riêng", "Spa trong phòng", "Khách sạn cao cấp"],
            roomType: "theLevel",
            rating: 4.9,
        },
    ];

    return (
        <div style={{ padding: "24px", backgroundColor: "#f5f5f5", minHeight: "100vh" }}>
            <h1 style={{ textAlign: "center", marginBottom: "32px", color: "#1890ff" }}>
                RoomCard Component Demo - Redesigned Version
            </h1>
            <Row gutter={[24, 24]}>
                {sampleRooms.map((room) => (
                    <Col key={room.id} xs={24} sm={12} lg={6}>
                        <RoomCard {...room} />
                    </Col>
                ))}
            </Row>
        </div>
    );
};

export default RoomCardDemo;
