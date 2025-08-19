import React from 'react';
import { motion } from 'framer-motion';
import HeroSection2 from './components/HeroSection2';
import IntroductionSection from './components/IntroductionSection';
import ServicesSection from './components/ServicesSection';
import LoungeSection from './components/LoungeSection';
import PremiumRoomsSection from './components/PremiumRoomsSection';
import GallerySection from './components/GallerySection';

const TheLevelPage: React.FC = () => {
    return (
        <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            transition={{ duration: 0.8 }}
            style={{
                minHeight: '100vh',
                backgroundColor: '#ffffff',
                overflow: 'hidden'
            }}
        >
            {/* Hero Section */}
            <HeroSection2 />

            {/* Introduction Section */}
            <IntroductionSection />

            {/* Services Section */}
            <ServicesSection />

            {/* Lounge Section */}
            <LoungeSection />

            {/* Premium Rooms Section */}
            <PremiumRoomsSection />

            {/* Gallery Section */}
            <GallerySection />
        </motion.div>
    );
};

export default TheLevelPage;
