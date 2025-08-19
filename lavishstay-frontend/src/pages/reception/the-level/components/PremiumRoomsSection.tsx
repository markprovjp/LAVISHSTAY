import React, { useState } from 'react';
import { Row, Col, Typography, Button, Card } from 'antd';
import { motion } from 'framer-motion';
import { LeftOutlined, RightOutlined, StarFilled } from '@ant-design/icons';

const { Title, Text } = Typography;

interface RoomItem {
    id: number;
    name: string;
    type: string;
    size: string;
    price: string;
    rating: number;
    image: string;
    features: string[];
    description: string;
}

const roomsData: RoomItem[] = [
    {
        id: 1,
        name: "Ocean View Suite",
        type: "Premium Suite",
        size: "120m²",
        price: "8,500,000 VNĐ",
        rating: 5,
        image: "https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        features: ["View biển panorama", "Jacuzzi riêng", "Butler 24/7", "Minibar premium"],
        description: "Suite cao cấp với tầm nhìn tuyệt đẹp ra biển, nội thất sang trọng và các tiện nghi đẳng cấp thế giới."
    },
    {
        id: 2,
        name: "Garden Villa",
        type: "Private Villa",
        size: "200m²",
        price: "12,800,000 VNĐ",
        rating: 5,
        image: "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        features: ["Hồ bơi riêng", "Vườn nhiệt đới", "Spa trong phòng", "Chef riêng"],
        description: "Villa riêng tư với không gian xanh mát, hồ bơi riêng và các dịch vụ cá nhân hóa cao cấp."
    },
    {
        id: 3,
        name: "Presidential Suite",
        type: "Luxury Suite",
        size: "300m²",
        price: "25,000,000 VNĐ",
        rating: 5,
        image: "https://images.unsplash.com/photo-1578683010236-d716f9a3f461?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        features: ["Penthouse tầng cao", "Phòng họp riêng", "Thang máy riêng", "Concierge 24/7"],
        description: "Căn hộ cao cấp nhất với không gian rộng rãi, thiết kế sang trọng và dịch vụ VIP đặc quyền."
    },
    {
        id: 4,
        name: "Beachfront Villa",
        type: "Exclusive Villa",
        size: "250m²",
        price: "18,500,000 VNĐ",
        rating: 5,
        image: "https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        features: ["Bãi biển riêng", "Bàn ăn ngoài trời", "Kayak miễn phí", "BBQ riêng"],
        description: "Villa bên bờ biển với lối ra trực tiếp xuống bãi cát trắng và các hoạt động thể thao nước."
    },
    {
        id: 5,
        name: "Sky Penthouse",
        type: "Premium Penthouse",
        size: "400m²",
        price: "35,000,000 VNĐ",
        rating: 5,
        image: "https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
        features: ["Sân thượng riêng", "Infinity pool", "Helipad", "Bar riêng"],
        description: "Penthouse đỉnh cao với sân thượng rộng lớn, hồ bơi vô cực và tầm nhìn 360 độ tuyệt đẹp."
    }
];

const PremiumRoomsSection: React.FC = () => {
    const [currentIndex, setCurrentIndex] = useState(0);
    const itemsPerPage = 3;
    const maxIndex = Math.max(0, roomsData.length - itemsPerPage);

    const nextSlide = () => {
        setCurrentIndex(prev => Math.min(prev + 1, maxIndex));
    };

    const prevSlide = () => {
        setCurrentIndex(prev => Math.max(prev - 1, 0));
    };

    const visibleRooms = roomsData.slice(currentIndex, currentIndex + itemsPerPage);

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
                        Phòng Suite Đặc Quyền
                    </Title>
                    <Text style={{
                        fontSize: '1.2rem',
                        color: '#666',
                        lineHeight: 1.6,
                        maxWidth: '600px',
                        margin: '0 auto',
                        display: 'block'
                    }}>
                        Khám phá các phòng suite cao cấp được thiết kế với tiêu chuẩn quốc tế
                    </Text>
                </motion.div>

                {/* Rooms Slider */}
                <div style={{ position: 'relative' }}>
                    <motion.div
                        key={currentIndex}
                        initial={{ opacity: 0, x: 50 }}
                        animate={{ opacity: 1, x: 0 }}
                        transition={{ duration: 0.6 }}
                    >
                        <Row gutter={[32, 32]}>
                            {visibleRooms.map((room, index) => (
                                <Col xs={24} lg={8} key={room.id}>
                                    <motion.div
                                        initial={{ opacity: 0, y: 30 }}
                                        whileInView={{ opacity: 1, y: 0 }}
                                        viewport={{ once: true }}
                                        transition={{ duration: 0.6, delay: index * 0.1 }}
                                    >
                                        <Card
                                            hoverable
                                            bordered={false}
                                            style={{
                                                height: '100%',
                                                borderRadius: '16px',
                                                overflow: 'hidden',
                                                background: 'white',
                                                boxShadow: '0 8px 30px rgba(0, 0, 0, 0.08)',
                                                transition: 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)'
                                            }}
                                            bodyStyle={{ padding: 0 }}
                                            onMouseEnter={(e) => {
                                                const target = e.currentTarget as HTMLElement;
                                                target.style.transform = 'translateY(-8px)';
                                                target.style.boxShadow = '0 20px 50px rgba(0, 0, 0, 0.15)';
                                            }}
                                            onMouseLeave={(e) => {
                                                const target = e.currentTarget as HTMLElement;
                                                target.style.transform = 'translateY(0)';
                                                target.style.boxShadow = '0 8px 30px rgba(0, 0, 0, 0.08)';
                                            }}
                                        >
                                            {/* Room Image */}
                                            <div style={{ position: 'relative', height: '280px', overflow: 'hidden' }}>
                                                <img
                                                    src={room.image}
                                                    alt={room.name}
                                                    style={{
                                                        width: '100%',
                                                        height: '100%',
                                                        objectFit: 'cover',
                                                        transition: 'transform 0.6s ease'
                                                    }}
                                                    onMouseEnter={(e) => {
                                                        const target = e.target as HTMLElement;
                                                        target.style.transform = 'scale(1.1)';
                                                    }}
                                                    onMouseLeave={(e) => {
                                                        const target = e.target as HTMLElement;
                                                        target.style.transform = 'scale(1)';
                                                    }}
                                                />

                                                {/* Price Badge */}
                                                <div style={{
                                                    position: 'absolute',
                                                    top: '20px',
                                                    right: '20px',
                                                    background: 'linear-gradient(135deg, #d4af37, #f4d03f)',
                                                    color: '#1a1a1a',
                                                    padding: '8px 16px',
                                                    borderRadius: '25px',
                                                    fontSize: '0.9rem',
                                                    fontWeight: 600,
                                                    boxShadow: '0 4px 15px rgba(212, 175, 55, 0.4)'
                                                }}>
                                                    {room.price}
                                                </div>

                                                {/* Rating */}
                                                <div style={{
                                                    position: 'absolute',
                                                    top: '20px',
                                                    left: '20px',
                                                    display: 'flex',
                                                    alignItems: 'center',
                                                    gap: '4px',
                                                    background: 'rgba(0, 0, 0, 0.7)',
                                                    padding: '6px 12px',
                                                    borderRadius: '20px',
                                                    backdropFilter: 'blur(10px)'
                                                }}>
                                                    {[...Array(room.rating)].map((_, i) => (
                                                        <StarFilled key={i} style={{ color: '#ffd700', fontSize: '12px' }} />
                                                    ))}
                                                </div>
                                            </div>

                                            {/* Room Content */}
                                            <div style={{ padding: '24px' }}>
                                                <div style={{ marginBottom: '16px' }}>
                                                    <Title
                                                        level={4}
                                                        style={{
                                                            fontSize: '1.4rem',
                                                            fontWeight: 500,
                                                            color: '#1a1a1a',
                                                            marginBottom: '8px',
                                                            lineHeight: 1.3
                                                        }}
                                                    >
                                                        {room.name}
                                                    </Title>
                                                    <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
                                                        <Text style={{ color: '#d4af37', fontWeight: 500 }}>
                                                            {room.type}
                                                        </Text>
                                                        <Text style={{ color: '#666', fontSize: '0.9rem' }}>
                                                            {room.size}
                                                        </Text>
                                                    </div>
                                                </div>

                                                <Text style={{
                                                    fontSize: '0.95rem',
                                                    color: '#666',
                                                    lineHeight: 1.6,
                                                    marginBottom: '20px',
                                                    display: 'block'
                                                }}>
                                                    {room.description}
                                                </Text>

                                                {/* Features */}
                                                <div style={{ marginBottom: '24px' }}>
                                                    {room.features.slice(0, 2).map((feature, i) => (
                                                        <div key={i} style={{
                                                            display: 'flex',
                                                            alignItems: 'center',
                                                            marginBottom: '8px'
                                                        }}>
                                                            <div style={{
                                                                width: '4px',
                                                                height: '4px',
                                                                borderRadius: '50%',
                                                                backgroundColor: '#d4af37',
                                                                marginRight: '12px'
                                                            }} />
                                                            <Text style={{ fontSize: '0.9rem', color: '#666' }}>
                                                                {feature}
                                                            </Text>
                                                        </div>
                                                    ))}
                                                </div>

                                                <Button
                                                    block
                                                    size="large"
                                                    style={{
                                                        background: 'linear-gradient(135deg, #1a1a1a 0%, #333 100%)',
                                                        border: 'none',
                                                        borderRadius: '8px',
                                                        color: 'white',
                                                        fontWeight: 500,
                                                        height: '44px',
                                                        transition: 'all 0.3s ease'
                                                    }}
                                                    onMouseEnter={(e) => {
                                                        const target = e.target as HTMLElement;
                                                        target.style.background = 'linear-gradient(135deg, #d4af37 0%, #f4d03f 100%)';
                                                        target.style.color = '#1a1a1a';
                                                        target.style.transform = 'translateY(-1px)';
                                                    }}
                                                    onMouseLeave={(e) => {
                                                        const target = e.target as HTMLElement;
                                                        target.style.background = 'linear-gradient(135deg, #1a1a1a 0%, #333 100%)';
                                                        target.style.color = 'white';
                                                        target.style.transform = 'translateY(0)';
                                                    }}
                                                >
                                                    Xem Chi Tiết
                                                </Button>
                                            </div>
                                        </Card>
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
                                const target = e.target as HTMLElement;
                                target.style.backgroundColor = '#d4af37';
                                target.style.borderColor = '#d4af37';
                                target.style.color = 'white';
                                target.style.transform = 'translateY(-50%) scale(1.1)';
                            }}
                            onMouseLeave={(e) => {
                                const target = e.target as HTMLElement;
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
                                const target = e.target as HTMLElement;
                                target.style.backgroundColor = '#d4af37';
                                target.style.borderColor = '#d4af37';
                                target.style.color = 'white';
                                target.style.transform = 'translateY(-50%) scale(1.1)';
                            }}
                            onMouseLeave={(e) => {
                                const target = e.target as HTMLElement;
                                target.style.backgroundColor = 'white';
                                target.style.borderColor = '#f0f0f0';
                                target.style.color = '#1a1a1a';
                                target.style.transform = 'translateY(-50%) scale(1)';
                            }}
                        />
                    )}
                </div>

                {/* Progress Indicator */}
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
            </div>
        </section>
    );
};

export default PremiumRoomsSection;
