import React from 'react';
import { Card, Affix, Anchor } from 'antd';
import { MenuOutlined } from '@ant-design/icons';

interface AnchorNavigationProps {
    rooms: any[];
    getRoomTypeDisplayName: (roomType: string) => string;
}

const AnchorNavigation: React.FC<AnchorNavigationProps> = ({
    rooms,
    getRoomTypeDisplayName
}) => {
    const getAnchorItems = () => {
        const roomTypes = Array.from(new Set(rooms.map(room => room.roomType)));
        return roomTypes.map(type => ({
            key: type,
            href: `#room-type-${type}`,
            title: getRoomTypeDisplayName(type)
        }));
    };

    return (
        <div className="fixed right-1 top-1/2 transform -translate-y-10 z-40 hidden lg:block">
            <Affix offsetTop={100}>
                <Card
                    title={
                        <div className="flex items-center gap-2">
                            <MenuOutlined className="text-blue-500" />
                            <span className="text-sm font-semibold ">
                                Loại phòng
                            </span>
                        </div>
                    }
                    size="small"
                    className="shadow-lg border "
                    bodyStyle={{ padding: '8px' }}
                    style={{
                        width: '200px',
                        borderRadius: '8px'
                    }}
                >
                    <Anchor
                        offsetTop={120}
                        items={getAnchorItems()}
                        className="text-sm"
                        targetOffset={80}
                    />
                </Card>
            </Affix>
        </div>
    );
};

export default AnchorNavigation;
