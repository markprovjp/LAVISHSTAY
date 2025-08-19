// src/pages/NewsDetailPage.tsx
import React from 'react';
import { Layout } from 'antd';
import NewsDetail from '../components/news/NewsDetail';

const { Content } = Layout;

const NewsDetailPage: React.FC = () => {
    return (
        <Layout>
            <Content>
                <NewsDetail />
            </Content>
        </Layout>
    );
};

export default NewsDetailPage;
