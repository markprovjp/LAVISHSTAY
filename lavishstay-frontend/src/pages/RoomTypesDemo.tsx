import React, { useState, useEffect } from 'react';
import { Spin, Alert, Button, Space } from 'antd';
import { ReloadOutlined } from '@ant-design/icons';
import RoomTypeShowcase from '../components/ui/RoomTypeShowcaseNew';

const RoomTypesDemo: React.FC = () => {
    const [roomTypes, setRoomTypes] = useState([]);
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const fetchRoomTypes = async () => {
        setLoading(true);
        setError(null);

        try {
            const response = await fetch(
                'http://localhost:8888/api/room-packages/search?check_in_date=2025-07-07&check_out_date=2025-07-10&guest_count=2'
            );

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success && data.data) {
                setRoomTypes(data.data);
            } else {
                throw new Error('Invalid response format');
            }
        } catch (err) {
            setError(err instanceof Error ? err.message : 'Failed to fetch room types');
            console.error('Error fetching room types:', err);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchRoomTypes();
    }, []);

    if (loading) {
        return (
            <div className="min-h-screen flex items-center justify-center">
                <div className="text-center">
                    <Spin size="large" />
                    <div className="mt-4 text-lg text-gray-600">
                        Đang tải thông tin các loại phòng...
                    </div>
                </div>
            </div>
        );
    }

    if (error) {
        return (
            <div className="min-h-screen flex items-center justify-center p-6">
                <Alert
                    message="Lỗi tải dữ liệu"
                    description={`Không thể tải thông tin các loại phòng: ${error}`}
                    type="error"
                    showIcon
                    action={
                        <Space>
                            <Button
                                size="small"
                                icon={<ReloadOutlined />}
                                onClick={fetchRoomTypes}
                                loading={loading}
                            >
                                Thử lại
                            </Button>
                        </Space>
                    }
                />
            </div>
        );
    }

    return (
        <div className="min-h-screen bg-gray-50">
            {/* Hero Section */}
            <div className="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white py-20">
                <div className="container mx-auto px-6 text-center">
                    <h1 className="text-5xl font-bold mb-6 bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">
                        LavishStay Hotel
                    </h1>
                    <p className="text-xl text-gray-300 max-w-2xl mx-auto">
                        Trải nghiệm nghỉ dưỡng đẳng cấp quốc tế với các loại phòng sang trọng
                    </p>
                </div>
            </div>

            {/* Room Types Showcase */}
            <div className="container mx-auto px-6 py-16">
                <RoomTypeShowcase searchResult={{ data: { room_types: roomTypes } }} />
            </div>

            {/* Footer */}
            <div className="bg-gray-800 text-white py-12">
                <div className="container mx-auto px-6 text-center">
                    <p className="text-gray-400">
                        © 2024 LavishStay Hotel. Được thiết kế với sự chuyên nghiệp và tình yêu.
                    </p>
                </div>
            </div>
        </div>
    );
};

export default RoomTypesDemo;
