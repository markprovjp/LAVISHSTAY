
import React from 'react';
import {
    Card,
    Typography,
} from 'antd';

const { Text } = Typography;

const InvoiceTab: React.FC = () => {
    return (
        <Card>
            <div style={{ textAlign: 'center', padding: '40px 0' }}>
                <Text type="secondary">Tính năng hóa đơn sẽ được phát triển trong phiên bản tiếp theo</Text>
            </div>
        </Card>
    );
};

export default InvoiceTab;
