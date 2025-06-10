import React from "react";
import { Row, Col } from "antd";
import RoomCard, { RoomProps } from "./RoomCard";

interface RoomCardsGridProps {
    rooms: RoomProps[];
    loading?: boolean;
    className?: string;
}

const RoomCardsGrid: React.FC<RoomCardsGridProps> = ({
    rooms,
    loading = false,
    className = "",
}) => {
    return (
        <div className={`room-cards-container ${className}`}>
            <Row gutter={[24, 24]} className="w-full">
                {rooms.map((room) => (
                    <Col
                        key={room.id}
                        xs={24}
                        sm={12}
                        md={8}
                        lg={6}
                        xl={6}
                        className="flex"
                    >
                        <div className="w-full">
                            <RoomCard {...room} />
                        </div>
                    </Col>
                ))}
            </Row>
        </div>
    );
};

RoomCardsGrid.displayName = 'RoomCardsGrid';

export default React.memo(RoomCardsGrid);
