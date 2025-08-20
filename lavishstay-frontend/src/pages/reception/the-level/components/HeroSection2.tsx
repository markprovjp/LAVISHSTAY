import React from 'react';
import { Typography, Button } from 'antd';
import { motion } from 'framer-motion';

const { Title, Paragraph } = Typography;

const HeroSection: React.FC = () => {
    return (
        <section style={{
            position: 'relative',
            height: '100vh',
            minHeight: '600px',
            overflow: 'hidden'
        }}>
            <div style={{
                position: 'absolute',
                top: 0,
                left: 0,
                width: '100%',
                height: '100%',
                background: 'linear-gradient(135deg, #1a1a1a 0%, #2d2d2d 100%)',
                backgroundImage: `url('https://images.unsplash.com/photo-1564013799919-ab600027ffc6?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')`,
                backgroundSize: 'cover',
                backgroundPosition: 'center'
            }}>
                <motion.div
                    style={{
                        position: 'relative',
                        height: '100%',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        zIndex: 2
                    }}
                    initial={{ opacity: 0, y: 50 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 1.2, delay: 0.3 }}
                >
                    <div style={{
                        textAlign: 'center',
                        color: 'white',
                        maxWidth: '800px',
                        padding: '0 20px'
                    }}>
                        <motion.div
                            initial={{ opacity: 0, x: -50 }}
                            animate={{ opacity: 1, x: 0 }}
                            transition={{ duration: 1, delay: 0.5 }}
                        >
                            <Title level={1} style={{
                                fontSize: '4rem',
                                fontWeight: 300,
                                letterSpacing: '8px',
                                marginBottom: 0,
                                color: 'white',
                                textShadow: '2px 2px 4px rgba(0, 0, 0, 0.5)'
                            }}>
                                THE LEVEL
                            </Title>
                            <Title level={2} style={{
                                fontSize: '2rem',
                                fontWeight: 400,
                                margin: '20px 0',
                                color: '#f0f0f0'
                            }}>
                                Trải nghiệm đẳng cấp vượt trội
                            </Title>
                            <Paragraph style={{
                                fontSize: '1.2rem',
                                lineHeight: 1.8,
                                margin: '30px 0',
                                color: 'rgba(255, 255, 255, 0.9)'
                            }}>
                                Khám phá thế giới sang trọng và tiện nghi độc quyền tại The Level -
                                nơi mỗi khoảnh khắc được tạo nên để mang đến trải nghiệm đáng nhớ nhất.
                            </Paragraph>
                            <div style={{
                                marginTop: 40,
                                display: 'flex',
                                gap: 20,
                                justifyContent: 'center',
                                flexWrap: 'wrap'
                            }}>
                                <Button type="primary" size="large" style={{
                                    background: 'linear-gradient(45deg, #d4af37, #f4e7aa)',
                                    border: 'none',
                                    height: 50,
                                    padding: '0 40px',
                                    fontSize: 16,
                                    fontWeight: 500,
                                    color: '#1a1a1a',
                                    borderRadius: 25,
                                    boxShadow: '0 4px 15px rgba(212, 175, 55, 0.3)'
                                }}>
                                    Khám phá ngay
                                </Button>
                                <Button size="large" style={{
                                    height: 50,
                                    padding: '0 40px',
                                    fontSize: 16,
                                    color: 'white',
                                    border: '2px solid rgba(255, 255, 255, 0.5)',
                                    background: 'rgba(255, 255, 255, 0.1)',
                                    borderRadius: 25,
                                    backdropFilter: 'blur(10px)'
                                }}>
                                    Xem thư viện
                                </Button>
                            </div>
                        </motion.div>
                    </div>
                </motion.div>

                <motion.div
                    style={{
                        position: 'absolute',
                        top: 0,
                        left: 0,
                        width: '100%',
                        height: '100%',
                        background: 'rgba(0, 0, 0, 0.4)',
                        zIndex: 1
                    }}
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ duration: 1.5 }}
                />
            </div>
        </section>
    );
};

export default HeroSection;
