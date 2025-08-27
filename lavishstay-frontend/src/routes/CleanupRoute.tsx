import React from 'react';
import { Routes, Route, Navigate } from 'react-router-dom';
import CleanupPanel from '../pages/reception/CleanupPanel';

const CleanupRoute: React.FC = () => {
    return (
        <Routes>
            <Route path="/cleanup" element={<CleanupPanel />} />
            <Route path="/" element={<Navigate to="/cleanup" replace />} />
        </Routes>
    );
};

export default CleanupRoute;
