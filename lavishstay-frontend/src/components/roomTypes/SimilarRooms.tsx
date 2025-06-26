import React from "react";
import { Typography, Button, Spin, Alert } from "antd";
import { ArrowRightOutlined } from "@ant-design/icons";
import RoomSwiper from "../ui/RoomSwiper";
import { useGetAllRoomTypes } from "../../hooks/useApi";

const { Title } = Typography;

interface SimilarRoomsProps {
    currentRoomId: string;
}

const SimilarRooms: React.FC<SimilarRoomsProps> = ({
    currentRoomId,
}) => {
    // Use hook to fetch all room types and filter out current one
    const { data: roomTypesData, isLoading: loading } = useGetAllRoomTypes();
    const allRooms = roomTypesData?.data || [];

    // Filter out current room
    const otherRooms = allRooms.filter((room: any) => room.id.toString() !== currentRoomId);

    if (loading) {
        return (
            <div className="similar-rooms">
                <Title level={3} className="mb-4">
                    Các loại phòng khác
                </Title>
                <div className="text-center py-8">
                    <Spin size="large" />
                    <div className="mt-4">Đang tải danh sách phòng...</div>
                </div>
            </div>
        );
    }

    if (otherRooms.length === 0) {
        return (
            <div className="similar-rooms">
                <Title level={3} className="mb-4">
                    Các loại phòng khác
                </Title>
                <Alert
                    message="Không có phòng khác"
                    description="Hiện tại không có loại phòng nào khác để hiển thị."
                    type="info"
                    showIcon
                />
            </div>
        );
    }

    return (
        <div className="similar-rooms">
            <div className="flex items-center justify-between mb-8">
                <Title level={3} className="mb-0">
                    Các loại phòng khác
                </Title>
                <Button
                    type="link"
                    icon={<ArrowRightOutlined />}
                    iconPosition="end"
                    size="large"
                    className="text-blue-600 hover:text-blue-700"
                >
                    Xem tất cả
                </Button>
            </div>

            <RoomSwiper roomTypes={otherRooms} />
        </div>
    );
};

export default SimilarRooms;
