import React, { useState } from 'react';
import { Typography, Button } from 'antd';
import { motion } from 'framer-motion';
import { LeftOutlined, RightOutlined } from '@ant-design/icons';

const { Title, Paragraph } = Typography;

interface LoungeSectionProps { }

interface CarouselItem {
    id: number;
    title: string;
    description: string;
    image: string;
    features: string[];
}

const loungeData: CarouselItem[] = [
    {
        id: 1,
        title: "The Level Lounge",
        description: "Không gian nghỉ dưỡng riêng tư với tầm nhìn panorama tuyệt đẹp ra biển. Thưởng thức các món ăn nhẹ cao cấp và đồ uống premium trong không gian sang trọng.",
        image: "https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
        features: ["Không gian riêng tư", "View biển panorama", "Thức ăn & đồ uống premium", "Wifi tốc độ cao"]
    },
    {
        id: 2,
        title: "Sky Bar Premium",
        description: "Quầy bar trên tầng thượng với cocktail signature được pha chế bởi bartender chuyên nghiệp. Tận hưởng sunset cocktail trong không gian lãng mạn.",
        image: "https://images.unsplash.com/photo-1569949381669-ecf31ae8e613?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
        features: ["Cocktail signature", "Bartender chuyên nghiệp", "View sunset tuyệt đẹp", "Âm nhạc thư giãn"]
    },
    {
        id: 3,
        title: "Business Center",
        description: "Trung tâm kinh doanh hiện đại với đầy đủ tiện nghi cho các cuộc họp và sự kiện quan trọng. Phòng họp riêng và dịch vụ thư ký chuyên nghiệp.",
        image: "https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
        features: ["Phòng họp hiện đại", "Dịch vụ thư ký", "Thiết bị A/V cao cấp", "Catering doanh nghiệp"]
    }
];

const LoungeSection: React.FC<LoungeSectionProps> = () => {
    const [currentSlide, setCurrentSlide] = useState(0);

    const nextSlide = () => {
        setCurrentSlide((prev) => (prev + 1) % loungeData.length);
    };

    const prevSlide = () => {
        setCurrentSlide((prev) => (prev - 1 + loungeData.length) % loungeData.length);
    };

    const goToSlide = (index: number) => {
        setCurrentSlide(index);
    };

    return (
        <section style={{
            padding: '120px 0',
            backgroundColor: '#1a1a1a',
            color: 'white',
            overflow: 'hidden',
            position: 'relative'
        }}>
            {/* Background Image Overlay */}
            <div style={{
                position: 'absolute',
                top: 0,
                left: 0,
                right: 0,
                bottom: 0,
                backgroundImage: `url(${loungeData[currentSlide].image})`,
                backgroundSize: 'cover',
                backgroundPosition: 'center',
                opacity: 0.3,
                transition: 'all 0.8s ease'
            }} />

            <div style={{
                maxWidth: '1200px',
                margin: '0 auto',
                padding: '0 24px',
                position: 'relative',
                zIndex: 2
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
                            color: 'white',
                            marginBottom: '24px',
                            letterSpacing: '1px'
                        }}
                    >
                        Không Gian Đặc Quyền
                    </Title>
                    <Paragraph style={{
                        fontSize: '1.2rem',
                        color: 'rgba(255, 255, 255, 0.8)',
                        lineHeight: 1.6,
                        maxWidth: '600px',
                        margin: '0 auto'
                    }}>
                        Khám phá các không gian riêng tư được thiết kế đặc biệt cho The Level
                    </Paragraph>
                </motion.div>

                {/* Carousel Content */}
                <div style={{ position: 'relative' }}>
                    <motion.div
                        key={currentSlide}
                        initial={{ opacity: 0, x: 50 }}
                        animate={{ opacity: 1, x: 0 }}
                        exit={{ opacity: 0, x: -50 }}
                        transition={{ duration: 0.6 }}
                        style={{
                            display: 'grid',
                            gridTemplateColumns: '1fr 1fr',
                            gap: '60px',
                            alignItems: 'center',
                            minHeight: '400px'
                        }}
                    >
                        {/* Content */}
                        <div>
                            <Title
                                level={3}
                                style={{
                                    fontSize: '2rem',
                                    fontWeight: 400,
                                    color: 'white',
                                    marginBottom: '24px'
                                }}
                            >
                                {loungeData[currentSlide].title}
                            </Title>

                            <Paragraph style={{
                                fontSize: '1.1rem',
                                color: 'rgba(255, 255, 255, 0.9)',
                                lineHeight: 1.8,
                                marginBottom: '32px'
                            }}>
                                {loungeData[currentSlide].description}
                            </Paragraph>

                            {/* Features */}
                            <div style={{ marginBottom: '40px' }}>
                                {loungeData[currentSlide].features.map((feature, index) => (
                                    <motion.div
                                        key={index}
                                        initial={{ opacity: 0, x: -20 }}
                                        animate={{ opacity: 1, x: 0 }}
                                        transition={{ delay: index * 0.1 + 0.3 }}
                                        style={{
                                            display: 'flex',
                                            alignItems: 'center',
                                            marginBottom: '12px'
                                        }}
                                    >
                                        <div style={{
                                            width: '6px',
                                            height: '6px',
                                            borderRadius: '50%',
                                            backgroundColor: '#d4af37',
                                            marginRight: '16px'
                                        }} />
                                        <span style={{
                                            color: 'rgba(255, 255, 255, 0.8)',
                                            fontSize: '1rem'
                                        }}>
                                            {feature}
                                        </span>
                                    </motion.div>
                                ))}
                            </div>

                            <Button
                                size="large"
                                style={{
                                    background: 'linear-gradient(45deg, #d4af37, #f4d03f)',
                                    border: 'none',
                                    borderRadius: '30px',
                                    padding: '0 32px',
                                    height: '48px',
                                    fontSize: '1rem',
                                    fontWeight: 500,
                                    color: '#1a1a1a',
                                    boxShadow: '0 4px 15px rgba(212, 175, 55, 0.4)',
                                    transition: 'all 0.3s ease'
                                }}
                                onMouseEnter={(e) => {
                                    const target = e.target as HTMLElement;
                                    target.style.transform = 'translateY(-2px)';
                                    target.style.boxShadow = '0 8px 25px rgba(212, 175, 55, 0.6)';
                                }}
                                onMouseLeave={(e) => {
                                    const target = e.target as HTMLElement;
                                    target.style.transform = 'translateY(0)';
                                    target.style.boxShadow = '0 4px 15px rgba(212, 175, 55, 0.4)';
                                }}
                            >
                                Khám Phá Ngay
                            </Button>
                        </div>

                        {/* Image */}
                        <div style={{
                            borderRadius: '16px',
                            overflow: 'hidden',
                            boxShadow: '0 20px 60px rgba(0, 0, 0, 0.5)',
                            height: '400px'
                        }}>
                            <img
                                src={loungeData[currentSlide].image}
                                alt={loungeData[currentSlide].title}
                                style={{
                                    width: '100%',
                                    height: '100%',
                                    objectFit: 'cover'
                                }}
                            />
                        </div>
                    </motion.div>

                    {/* Navigation Arrows */}
                    <Button
                        icon={<LeftOutlined />}
                        onClick={prevSlide}
                        style={{
                            position: 'absolute',
                            left: '-60px',
                            top: '50%',
                            transform: 'translateY(-50%)',
                            backgroundColor: 'rgba(255, 255, 255, 0.1)',
                            border: '1px solid rgba(255, 255, 255, 0.3)',
                            color: 'white',
                            width: '48px',
                            height: '48px',
                            borderRadius: '50%',
                            backdropFilter: 'blur(10px)',
                            transition: 'all 0.3s ease'
                        }}
                        onMouseEnter={(e) => {
                            const target = e.target as HTMLElement;
                            target.style.backgroundColor = 'rgba(212, 175, 55, 0.8)';
                            target.style.borderColor = '#d4af37';
                        }}
                        onMouseLeave={(e) => {
                            const target = e.target as HTMLElement;
                            target.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
                            target.style.borderColor = 'rgba(255, 255, 255, 0.3)';
                        }}
                    />

                    <Button
                        icon={<RightOutlined />}
                        onClick={nextSlide}
                        style={{
                            position: 'absolute',
                            right: '-60px',
                            top: '50%',
                            transform: 'translateY(-50%)',
                            backgroundColor: 'rgba(255, 255, 255, 0.1)',
                            border: '1px solid rgba(255, 255, 255, 0.3)',
                            color: 'white',
                            width: '48px',
                            height: '48px',
                            borderRadius: '50%',
                            backdropFilter: 'blur(10px)',
                            transition: 'all 0.3s ease'
                        }}
                        onMouseEnter={(e) => {
                            const target = e.target as HTMLElement;
                            target.style.backgroundColor = 'rgba(212, 175, 55, 0.8)';
                            target.style.borderColor = '#d4af37';
                        }}
                        onMouseLeave={(e) => {
                            const target = e.target as HTMLElement;
                            target.style.backgroundColor = 'rgba(255, 255, 255, 0.1)';
                            target.style.borderColor = 'rgba(255, 255, 255, 0.3)';
                        }}
                    />
                </div>

                {/* Pagination */}
                <div style={{
                    display: 'flex',
                    justifyContent: 'center',
                    alignItems: 'center',
                    marginTop: '60px',
                    gap: '24px'
                }}>
                    <div style={{
                        display: 'flex',
                        gap: '12px'
                    }}>
                        {loungeData.map((_, index) => (
                            <button
                                key={index}
                                onClick={() => goToSlide(index)}
                                style={{
                                    width: currentSlide === index ? '40px' : '12px',
                                    height: '4px',
                                    borderRadius: '2px',
                                    border: 'none',
                                    backgroundColor: currentSlide === index ? '#d4af37' : 'rgba(255, 255, 255, 0.3)',
                                    cursor: 'pointer',
                                    transition: 'all 0.3s ease'
                                }}
                            />
                        ))}
                    </div>

                    <span style={{
                        color: 'rgba(255, 255, 255, 0.6)',
                        fontSize: '0.9rem',
                        fontWeight: 300
                    }}>
                        {currentSlide + 1} / {loungeData.length}
                    </span>
                </div>
            </div>

            {/* Responsive Styles */}
            <style>{`
        @media (max-width: 768px) {
          .carousel-content {
            grid-template-columns: 1fr !important;
            gap: 40px !important;
          }
          
          .carousel-navigation {
            left: 20px !important;
            right: 20px !important;
          }
        }
      `}</style>
        </section>
    );
};

export default LoungeSection;
