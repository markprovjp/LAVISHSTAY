import React, { useState } from 'react';
import { Typography, Button, Modal } from 'antd';
import { motion } from 'framer-motion';
import { EyeOutlined, ExpandOutlined, CloseOutlined } from '@ant-design/icons';

const { Title, Text } = Typography;

interface GalleryImage {
    id: number;
    src: string;
    title: string;
    category: string;
    description: string;
}

const galleryImages: GalleryImage[] = [
    {
        id: 1,
        src: "https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
        title: "Ocean View Suite",
        category: "Suite Phòng",
        description: "Tầm nhìn panorama tuyệt đẹp ra biển"
    },
    {
        id: 2,
        src: "https://images.unsplash.com/photo-1590490360182-c33d57733427?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
        title: "The Level Lounge",
        category: "Tiện Nghi",
        description: "Không gian thư giãn cao cấp"
    },
    {
        id: 3,
        src: "https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
        title: "Premium Spa",
        category: "Wellness",
        description: "Trung tâm chăm sóc sức khỏe đẳng cấp"
    },
    {
        id: 4,
        src: "https://images.unsplash.com/photo-1566665797739-1674de7a421a?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
        title: "Sky Restaurant",
        category: "Nhà Hàng",
        description: "Nhà hàng cao cấp với view tuyệt đẹp"
    },
    {
        id: 5,
        src: "https://images.unsplash.com/photo-1578683010236-d716f9a3f461?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
        title: "Presidential Suite",
        category: "Suite Phòng",
        description: "Căn hộ tổng thống với thiết kế sang trọng"
    },
    {
        id: 6,
        src: "https://images.unsplash.com/photo-1569949381669-ecf31ae8e613?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
        title: "Infinity Pool",
        category: "Hồ Bơi",
        description: "Hồ bơi vô cực với tầm nhìn tuyệt đẹp"
    },
    {
        id: 7,
        src: "https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
        title: "Conference Room",
        category: "Kinh Doanh",
        description: "Phòng họp hiện đại và chuyên nghiệp"
    },
    {
        id: 8,
        src: "https://images.unsplash.com/photo-1584132967334-10e028bd69f7?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80",
        title: "Garden Terrace",
        category: "Sân Vườn",
        description: "Không gian xanh mát và yên tĩnh"
    }
];

const GallerySection: React.FC = () => {
    const [selectedImage, setSelectedImage] = useState<GalleryImage | null>(null);
    const [isModalVisible, setIsModalVisible] = useState(false);

    const openModal = (image: GalleryImage) => {
        setSelectedImage(image);
        setIsModalVisible(true);
    };

    const closeModal = () => {
        setIsModalVisible(false);
        setSelectedImage(null);
    };

    const containerVariants = {
        hidden: { opacity: 0 },
        visible: {
            opacity: 1,
            transition: {
                staggerChildren: 0.1
            }
        }
    };

    const itemVariants = {
        hidden: { opacity: 0, scale: 0.9 },
        visible: {
            opacity: 1,
            scale: 1,
            transition: {
                duration: 0.6,
                ease: "easeOut"
            }
        }
    };

    return (
        <section style={{
            padding: '120px 0 0 0',
            backgroundColor: '#ffffff',
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
                        Khám Phá The Level
                    </Title>
                    <Text style={{
                        fontSize: '1.2rem',
                        color: '#666',
                        lineHeight: 1.6,
                        maxWidth: '600px',
                        margin: '0 auto',
                        display: 'block'
                    }}>
                        Hành trình khám phá những khoảnh khắc đẳng cấp và trải nghiệm độc quyền
                    </Text>
                </motion.div>

                {/* Gallery Grid */}
                <motion.div
                    variants={containerVariants}
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true }}
                    style={{
                        display: 'grid',
                        gridTemplateColumns: 'repeat(auto-fit, minmax(350px, 1fr))',
                        gap: '20px',
                        marginBottom: '80px'
                    }}
                >
                    {galleryImages.map((image, index) => (
                        <motion.div
                            key={image.id}
                            variants={itemVariants}
                            style={{
                                position: 'relative',
                                borderRadius: '12px',
                                overflow: 'hidden',
                                height: index % 5 === 0 ? '400px' : index % 3 === 0 ? '300px' : '250px',
                                cursor: 'pointer',
                                boxShadow: '0 4px 20px rgba(0, 0, 0, 0.1)',
                                transition: 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)'
                            }}
                            whileHover={{ y: -8 }}
                            onClick={() => openModal(image)}
                        >
                            {/* Image */}
                            <img
                                src={image.src}
                                alt={image.title}
                                style={{
                                    width: '100%',
                                    height: '100%',
                                    objectFit: 'cover',
                                    transition: 'transform 0.6s ease'
                                }}
                            />

                            {/* Overlay */}
                            <div style={{
                                position: 'absolute',
                                top: 0,
                                left: 0,
                                right: 0,
                                bottom: 0,
                                background: 'linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.7) 100%)',
                                opacity: 0,
                                transition: 'opacity 0.3s ease',
                                display: 'flex',
                                flexDirection: 'column',
                                justifyContent: 'flex-end',
                                padding: '24px'
                            }}
                                onMouseEnter={(e) => {
                                    const target = e.currentTarget as HTMLElement;
                                    target.style.opacity = '1';
                                    const img = target.previousSibling as HTMLElement;
                                    if (img) img.style.transform = 'scale(1.1)';
                                }}
                                onMouseLeave={(e) => {
                                    const target = e.currentTarget as HTMLElement;
                                    target.style.opacity = '0';
                                    const img = target.previousSibling as HTMLElement;
                                    if (img) img.style.transform = 'scale(1)';
                                }}
                            >
                                <div style={{
                                    color: 'white',
                                    transform: 'translateY(20px)',
                                    transition: 'transform 0.3s ease'
                                }}>
                                    <div style={{
                                        display: 'inline-block',
                                        backgroundColor: 'rgba(212, 175, 55, 0.9)',
                                        color: '#1a1a1a',
                                        padding: '4px 12px',
                                        borderRadius: '12px',
                                        fontSize: '0.8rem',
                                        fontWeight: 500,
                                        marginBottom: '8px'
                                    }}>
                                        {image.category}
                                    </div>
                                    <Title level={5} style={{ color: 'white', marginBottom: '4px', fontSize: '1.1rem' }}>
                                        {image.title}
                                    </Title>
                                    <Text style={{ color: 'rgba(255, 255, 255, 0.8)', fontSize: '0.9rem' }}>
                                        {image.description}
                                    </Text>
                                </div>

                                {/* View Icon */}
                                <div style={{
                                    position: 'absolute',
                                    top: '50%',
                                    left: '50%',
                                    transform: 'translate(-50%, -50%)',
                                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                                    borderRadius: '50%',
                                    width: '60px',
                                    height: '60px',
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    opacity: 0,
                                    transition: 'all 0.3s ease',
                                    backdropFilter: 'blur(10px)'
                                }}
                                    onMouseEnter={(e) => {
                                        const target = e.currentTarget as HTMLElement;
                                        target.style.opacity = '1';
                                        target.style.transform = 'translate(-50%, -50%) scale(1.1)';
                                    }}
                                >
                                    <EyeOutlined style={{ fontSize: '1.5rem', color: '#1a1a1a' }} />
                                </div>
                            </div>
                        </motion.div>
                    ))}
                </motion.div>

                {/* View Gallery Button */}
                <motion.div
                    initial={{ opacity: 0, y: 30 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true }}
                    transition={{ duration: 0.8, delay: 0.3 }}
                    style={{ textAlign: 'center', paddingBottom: '60px' }}
                >
                    <Button
                        size="large"
                        icon={<ExpandOutlined />}
                        style={{
                            background: 'linear-gradient(45deg, #d4af37, #f4d03f)',
                            border: 'none',
                            borderRadius: '30px',
                            padding: '0 40px',
                            height: '56px',
                            fontSize: '1.1rem',
                            fontWeight: 500,
                            color: '#1a1a1a',
                            boxShadow: '0 8px 30px rgba(212, 175, 55, 0.4)',
                            transition: 'all 0.3s ease',
                            display: 'inline-flex',
                            alignItems: 'center',
                            gap: '12px'
                        }}
                        onMouseEnter={(e) => {
                            const target = e.target as HTMLElement;
                            target.style.transform = 'translateY(-4px)';
                            target.style.boxShadow = '0 12px 40px rgba(212, 175, 55, 0.6)';
                        }}
                        onMouseLeave={(e) => {
                            const target = e.target as HTMLElement;
                            target.style.transform = 'translateY(0)';
                            target.style.boxShadow = '0 8px 30px rgba(212, 175, 55, 0.4)';
                        }}
                    >
                        XEM THƯ VIỆN ẢNH
                    </Button>
                </motion.div>
            </div>

            {/* Modal for full-size image view */}
            <Modal
                open={isModalVisible}
                onCancel={closeModal}
                footer={null}
                width="90vw"
                style={{ top: 20 }}
                bodyStyle={{ padding: 0 }}
                closeIcon={<CloseOutlined style={{ color: 'white', fontSize: '1.5rem' }} />}
                styles={{
                    mask: { backgroundColor: 'rgba(0, 0, 0, 0.9)' }
                }}
            >
                {selectedImage && (
                    <div style={{ position: 'relative' }}>
                        <img
                            src={selectedImage.src}
                            alt={selectedImage.title}
                            style={{
                                width: '100%',
                                height: 'auto',
                                maxHeight: '80vh',
                                objectFit: 'contain',
                                borderRadius: '8px'
                            }}
                        />

                        {/* Image Info */}
                        <div style={{
                            position: 'absolute',
                            bottom: 0,
                            left: 0,
                            right: 0,
                            background: 'linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.8) 100%)',
                            padding: '40px 30px 30px 30px',
                            color: 'white',
                            borderRadius: '0 0 8px 8px'
                        }}>
                            <div style={{
                                display: 'inline-block',
                                backgroundColor: 'rgba(212, 175, 55, 0.9)',
                                color: '#1a1a1a',
                                padding: '6px 16px',
                                borderRadius: '15px',
                                fontSize: '0.9rem',
                                fontWeight: 500,
                                marginBottom: '12px'
                            }}>
                                {selectedImage.category}
                            </div>
                            <Title level={3} style={{ color: 'white', marginBottom: '8px' }}>
                                {selectedImage.title}
                            </Title>
                            <Text style={{ color: 'rgba(255, 255, 255, 0.9)', fontSize: '1.1rem' }}>
                                {selectedImage.description}
                            </Text>
                        </div>
                    </div>
                )}
            </Modal>

            {/* Responsive Styles */}
            <style>{`
        @media (max-width: 768px) {
          .gallery-grid {
            grid-template-columns: 1fr !important;
            gap: 16px !important;
          }
          
          .gallery-item {
            height: 250px !important;
          }
        }
      `}</style>
        </section>
    );
};

export default GallerySection;
