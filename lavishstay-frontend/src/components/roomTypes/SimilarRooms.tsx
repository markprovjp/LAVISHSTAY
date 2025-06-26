import React from "react";
import { Typography, Button, Spin, Alert } from "antd";
import { ArrowRightOutlined } from "@ant-design/icons";
import RoomSwiper from "../ui/RoomSwiper";
import { useGetSimilarRooms } from "../../hooks/useApi";

const { Title } = Typography;

interface SimilarRoomsProps {
    currentRoomId: string;
}

const SimilarRooms: React.FC<SimilarRoomsProps> = ({
    currentRoomId,
}) => {    // Use hook to fetch similar rooms
    const { data: similarRoomsData, isLoading: loading } = useGetSimilarRooms(currentRoomId);
    const similarRooms = similarRoomsData?.similarRooms || [];

    if (loading) {
        return (
            <div className="similar-rooms">
                <Title level={3} className="mb-4">
                    Phòng tương tự
                </Title>
                <div className="text-center py-8">
                    <Spin size="large" />
                    <div className="mt-4">Đang tải phòng tương tự...</div>
                </div>
            </div>
        );
    }

    if (similarRooms.length === 0) {
        return (
            <div className="similar-rooms">
                <Title level={3} className="mb-4">
                    Phòng tương tự
                </Title>
                <Alert
                    message="Không có phòng tương tự"
                    description="Hiện tại không có phòng nào tương tự để hiển thị."
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
                    Phòng tương tự
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

            <RoomSwiper rooms={similarRooms} />
        </div>
    );
};

export default SimilarRooms;
