import React, { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

const Reception: React.FC = () => {
    const navigate = useNavigate();

    useEffect(() => {
        // Redirect to reception dashboard
        navigate('/reception/dashboard', { replace: true });
    }, [navigate]);

    return null;
};

export default Reception;
