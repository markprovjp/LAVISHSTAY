import React from 'react';
import { Typography, Button } from 'antd';
import { motion } from 'framer-motion';

const { Title, Paragraph } = Typography;

const HeroSection: React.FC = () => {
    return (
        <section className={styles.heroSection}>
            <div className={styles.heroBackground}>
                <motion.div
                    className={styles.heroContent}
                    initial={{ opacity: 0, y: 50 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 1.2, delay: 0.3 }}
                >
                    <div className={styles.heroText}>
                        <motion.div
                            initial={{ opacity: 0, x: -50 }}
                            animate={{ opacity: 1, x: 0 }}
                            transition={{ duration: 1, delay: 0.5 }}
                        >
                            <Title level={1} className={styles.heroTitle}>
                                THE LEVEL
                            </Title>
                            <Title level={2} className={styles.heroSubtitle}>
                                Trải nghiệm đẳng cấp vượt trội
                            </Title>
                            <Paragraph className={styles.heroDescription}>
                                Khám phá thế giới sang trọng và tiện nghi độc quyền tại The Level -
                                nơi mỗi khoảnh khắc được tạo nên để mang đến trải nghiệm đáng nhớ nhất.
                            </Paragraph>
                            <div className={styles.heroActions}>
                                <Button type="primary" size="large" className={styles.ctaButton}>
                                    Khám phá ngay
                                </Button>
                                <Button size="large" className={styles.secondaryButton}>
                                    Xem thư viện
                                </Button>
                            </div>
                        </motion.div>
                    </div>
                </motion.div>

                <motion.div
                    className={styles.heroOverlay}
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 0.3 }}
                    transition={{ duration: 1.5 }}
                />
        </section>
    );
};

export default HeroSection;
