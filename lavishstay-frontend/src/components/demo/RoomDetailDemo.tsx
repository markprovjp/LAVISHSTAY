// src/components/demo/RoomDetailDemo.tsx
import React from 'react';
import { Button, Card } from 'antd';
import { useNavigate } from 'react-router-dom';
import { Building, Eye } from 'lucide-react';

const RoomDetailDemo: React.FC = () => {
    const navigate = useNavigate();

    const demoRooms = [
        {
            id: 'deluxe-ocean-view-suite',
            name: 'Deluxe Ocean View Suite',
            description: 'Phòng suite cao cấp với tầm nhìn ra đại dương',
            image: 'https://images.unsplash.com/photo-1611892440504-42a792e24d32?w=400',
        },
        {
            id: 'premium-city-view-suite',
            name: 'Premium City View Suite',
            description: 'Phòng suite cao cấp với tầm nhìn ra thành phố',
            image: 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=400',
        },
        {
            id: 'standard-garden-view',
            name: 'Standard Garden View',
            description: 'Phòng tiêu chuẩn với tầm nhìn ra vườn',
            image: 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=400',
        }
    ];

    const handleViewRoom = (roomId: string) => {
        navigate(`/room-types/${roomId}`);
    };

    return (
        <div className="min-h-screen bg-gray-50 dark:bg-gray-900 p-8">
            <div className="max-w-6xl mx-auto">
                <div className="text-center mb-8">
                    <h1 className="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                        Room Detail Page Demo
                    </h1>
                    <p className="text-lg text-gray-600 dark:text-gray-400">
                        Chọn một phòng để xem trang chi tiết được thiết kế theo phong cách Melia Vinpearl
                    </p>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    {demoRooms.map((room) => (
                        <Card
                            key={room.id}
                            hoverable
                            className="overflow-hidden"
                            cover={
                                <img
                                    alt={room.name}
                                    src={room.image}
                                    className="h-48 w-full object-cover"
                                />
                            }
                            actions={[
                                <Button
                                    type="primary"
                                    icon={<Eye size={16} />}
                                    onClick={() => handleViewRoom(room.id)}
                                    block
                                >
                                    Xem chi tiết
                                </Button>
                            ]}
                        >
                            <Card.Meta
                                title={
                                    <div className="flex items-center gap-2">
                                        <Building size={18} />
                                        {room.name}
                                    </div>
                                }
                                description={room.description}
                            />
                        </Card>
                    ))}
                </div>

                <div className="mt-12 text-center">
                    <Card className="bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800">
                        <h3 className="text-xl font-semibold text-blue-800 dark:text-blue-200 mb-4">
                            Tính năng được implement
                        </h3>
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                            <div className="text-left">
                                <h4 className="font-medium text-blue-700 dark:text-blue-300 mb-2">UI/UX</h4>
                                <ul className="space-y-1 text-blue-600 dark:text-blue-400">
                                    <li>• Gallery ảnh với Swiper</li>
                                    <li>• Responsive design</li>
                                    <li>• Dark mode support</li>
                                    <li>• Framer Motion animations</li>
                                    <li>• Ant Design 5.x components</li>
                                </ul>
                            </div>
                            <div className="text-left">
                                <h4 className="font-medium text-blue-700 dark:text-blue-300 mb-2">Tính năng</h4>
                                <ul className="space-y-1 text-blue-600 dark:text-blue-400">
                                    <li>• Like & Bookmark phòng</li>
                                    <li>• Chia sẻ social media</li>
                                    <li>• Đánh giá & bình luận</li>
                                    <li>• Thống kê rating</li>
                                    <li>• Booking form</li>
                                </ul>
                            </div>
                            <div className="text-left">
                                <h4 className="font-medium text-blue-700 dark:text-blue-300 mb-2">Công nghệ</h4>
                                <ul className="space-y-1 text-blue-600 dark:text-blue-400">
                                    <li>• React + TypeScript</li>
                                    <li>• Zustand state management</li>
                                    <li>• React Query</li>
                                    <li>• TailwindCSS</li>
                                    <li>• Day.js</li>
                                </ul>
                            </div>
                        </div>
                    </Card>
                </div>
            </div>
        </div>
    );
};

export default RoomDetailDemo;
