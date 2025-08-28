import React, { useState, useMemo } from 'react';
import { Row, Col, Typography, Button, Spin, Alert } from 'antd';
import { motion } from 'framer-motion';
import { LeftOutlined, RightOutlined } from '@ant-design/icons';
import { useRoomTypes } from '../../../../hooks/useRoomTypesOverview';
import RoomTypeOverviewCard from '../../../../components/ui/RoomTypeOverviewCard';
import { RoomType } from '../../../../types/roomTypes';

const { Title, Text } = Typography;

const PremiumRoomsSection: React.FC = () => {
    const [currentIndex, setCurrentIndex] = useState(0);
    const itemsPerPage = 3;

    // Fetch room types data from backend and filter for "the level" rooms
    const { data: allRoomTypes, loading, error } = useRoomTypes({ limit: 100 });

    // Filter room types that contain "the_level" in their slug
    const theLevelRooms = useMemo(() => {
        return allRoomTypes.filter(room =>
            room.slug.includes('the_level')
        );
    }, [allRoomTypes]);

    const maxIndex = Math.max(0, theLevelRooms.length - itemsPerPage);

    const nextSlide = () => {
        setCurrentIndex(prev => Math.min(prev + 1, maxIndex));
    };

    const prevSlide = () => {
        setCurrentIndex(prev => Math.max(prev - 1, 0));
    };

    const visibleRooms = theLevelRooms.slice(currentIndex, currentIndex + itemsPerPage);

    const handleRoomClick = (roomType: RoomType) => {
        // Navigate to room details page in same tab
        window.location.href = `/room-types/${roomType.slug}`;
    };

    if (loading) {
        return (
            <section style={{
                padding: '120px 0',
                backgroundColor: '#f8f9fa',
                display: 'flex',
                justifyContent: 'center',
                alignItems: 'center'
            }}>
                <Spin size="large" />
            </section>
        );
    }

    if (error) {
        return (
            <section style={{
                padding: '120px 0',
                backgroundColor: '#f8f9fa'
            }}>
                <div style={{
                    maxWidth: '1400px',
                    margin: '0 auto',
                    padding: '0 24px'
                }}>
                    <Alert
                        message="Không thể tải dữ liệu phòng"
                        description={error}
                        type="error"
                        showIcon
                    />
                </div>
            </section>
        );
    }

    if (theLevelRooms.length === 0) {
        return (
            <section style={{
                padding: '120px 0',
                backgroundColor: '#f8f9fa'
            }}>
                <div style={{
                    maxWidth: '1400px',
                    margin: '0 auto',
                    padding: '0 24px',
                    textAlign: 'center'
                }}>
                    <Title level={2}>Phòng The Level Đặc Quyền</Title>
                    <Text>Hiện tại không có phòng The Level nào khả dụng.</Text>
                </div>
            </section>
        );
    }

    return (
        <section style={{
            padding: '120px 0',
            backgroundColor: '#f8f9fa',
            overflow: 'hidden'
        }}>
            <div style={{
                maxWidth: '1400px',
                margin: '0 auto',
                padding: '0 24px'
            }}>
                {/* Section Header */}
                <motion.div
                    initial={{ opacity: 0, y: 30 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true }}
                    transition={{ duration: 0.8 }}
                    style={{ textAlign: 'center', marginBottom: '80px' }}
                >
                    <Title
                        level={2}
                        style={{
                            fontSize: '2.5rem',
                            fontWeight: 300,
                            color: '#1a1a1a',
                            marginBottom: '24px',
                            letterSpacing: '1px'
                        }}
                    >
                        Phòng The Level Đặc Quyền
                    </Title>
                    <Text style={{
                        fontSize: '1.2rem',
                        color: '#666',
                        lineHeight: 1.6,
                        maxWidth: '600px',
                        margin: '0 auto',
                        display: 'block'
                    }}>
                        Khám phá các phòng The Level cao cấp The Level được thiết kế với tiêu chuẩn quốc tế
                    </Text>
                </motion.div>

                {/* Rooms Grid */}
                <div style={{ position: 'relative' }}>
                    <motion.div
                        key={currentIndex}
                        initial={{ opacity: 0, x: 50 }}
                        animate={{ opacity: 1, x: 0 }}
                        transition={{ duration: 0.6 }}
                    >
                        <Row gutter={[32, 32]}>
                            {visibleRooms.map((room, index) => (
                                <Col xs={24} lg={8} key={room.room_type_id}>
                                    <motion.div
                                        initial={{ opacity: 0, y: 30 }}
                                        whileInView={{ opacity: 1, y: 0 }}
                                        viewport={{ once: true }}
                                        transition={{ duration: 0.6, delay: index * 0.1 }}
                                    >
                                        <RoomTypeOverviewCard
                                            roomType={room}
                                            onClick={handleRoomClick}
                                            className="the-level-room-card"
                                        />
                                    </motion.div>
                                </Col>
                            ))}
                        </Row>
                    </motion.div>

                    {/* Navigation Arrows */}
                    {currentIndex > 0 && (
                        <Button
                            icon={<LeftOutlined />}
                            onClick={prevSlide}
                            style={{
                                position: 'absolute',
                                left: '-70px',
                                top: '50%',
                                transform: 'translateY(-50%)',
                                backgroundColor: 'white',
                                border: '2px solid #f0f0f0',
                                color: '#1a1a1a',
                                width: '48px',
                                height: '48px',
                                borderRadius: '50%',
                                boxShadow: '0 4px 15px rgba(0, 0, 0, 0.1)',
                                transition: 'all 0.3s ease',
                                zIndex: 10
                            }}
                            onMouseEnter={(e) => {
                                const target = e.currentTarget;
                                target.style.backgroundColor = '#d4af37';
                                target.style.borderColor = '#d4af37';
                                target.style.color = 'white';
                                target.style.transform = 'translateY(-50%) scale(1.1)';
                            }}
                            onMouseLeave={(e) => {
                                const target = e.currentTarget;
                                target.style.backgroundColor = 'white';
                                target.style.borderColor = '#f0f0f0';
                                target.style.color = '#1a1a1a';
                                target.style.transform = 'translateY(-50%) scale(1)';
                            }}
                        />
                    )}

                    {currentIndex < maxIndex && (
                        <Button
                            icon={<RightOutlined />}
                            onClick={nextSlide}
                            style={{
                                position: 'absolute',
                                right: '-70px',
                                top: '50%',
                                transform: 'translateY(-50%)',
                                backgroundColor: 'white',
                                border: '2px solid #f0f0f0',
                                color: '#1a1a1a',
                                width: '48px',
                                height: '48px',
                                borderRadius: '50%',
                                boxShadow: '0 4px 15px rgba(0, 0, 0, 0.1)',
                                transition: 'all 0.3s ease',
                                zIndex: 10
                            }}
                            onMouseEnter={(e) => {
                                const target = e.currentTarget;
                                target.style.backgroundColor = '#d4af37';
                                target.style.borderColor = '#d4af37';
                                target.style.color = 'white';
                                target.style.transform = 'translateY(-50%) scale(1.1)';
                            }}
                            onMouseLeave={(e) => {
                                const target = e.currentTarget;
                                target.style.backgroundColor = 'white';
                                target.style.borderColor = '#f0f0f0';
                                target.style.color = '#1a1a1a';
                                target.style.transform = 'translateY(-50%) scale(1)';
                            }}
                        />
                    )}
                </div>

                {/* Progress Indicator */}
                {theLevelRooms.length > itemsPerPage && (
                    <div style={{
                        display: 'flex',
                        justifyContent: 'center',
                        alignItems: 'center',
                        marginTop: '60px',
                        gap: '8px'
                    }}>
                        {Array.from({ length: maxIndex + 1 }, (_, index) => (
                            <button
                                key={index}
                                onClick={() => setCurrentIndex(index)}
                                style={{
                                    width: currentIndex === index ? '32px' : '8px',
                                    height: '4px',
                                    borderRadius: '2px',
                                    border: 'none',
                                    backgroundColor: currentIndex === index ? '#d4af37' : '#ddd',
                                    cursor: 'pointer',
                                    transition: 'all 0.3s ease'
                                }}
                            />
                        ))}
                    </div>
                )}
            </div>
        </section>
    );
};

export default PremiumRoomsSection;
