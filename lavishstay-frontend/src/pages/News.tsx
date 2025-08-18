import { useNewsList } from '../hooks/useNews';

// src/pages/News.tsx
import React, { Suspense } from 'react';
import { Layout, Spin } from 'antd';
import { motion } from 'framer-motion';
import { Helmet } from 'react-helmet-async';
import { useTranslation } from 'react-i18next';
import NewsHeader from '../components/news/NewsHeader';
import NewsMainHighlight from '../components/news/NewsMainHighlight';
import NewsHighlights from '../components/news/NewsHighlights';
import NewsCategoryTabs from '../components/news/NewsCategoryTabs';
import NewsList from '../components/news/NewsList';
import NewsSidebar from '../components/news/NewsSidebar';
// import NewsModal from '../components/news/NewsModal';

const { Content } = Layout;


const NewsPage: React.FC = () => {

    const { t } = useTranslation();
    // Lấy toàn bộ response của API news list để debug
    const { data: newsListResponse, isLoading, error } = useNewsList({ per_page: 10 });

    // Hiển thị debug JSON ở đầu trang
    // const debugBlock = (
    //     <div style={{ background: '#f6f8fa', color: '#333', fontSize: 13, padding: 16, marginBottom: 24, borderRadius: 8, border: '1px solid #eee', wordBreak: 'break-all' }}>
    //         <b>DEBUG API Response:</b>
    //         <pre style={{ whiteSpace: 'pre-wrap', margin: 0 }}>
    //             {isLoading ? 'Đang tải...' : error ? String(error) : JSON.stringify(newsListResponse, null, 2)}
    //         </pre>
    //     </div>
    // );

    return (
        <>
            <Helmet>
                <title>{t('news.pageTitle', 'Tin tức - LavishStay')}</title>
                <meta name="description" content={t('news.pageDescription', 'Cập nhật tin tức mới nhất về khách sạn, du lịch và các ưu đãi hấp dẫn từ LavishStay')} />
                <meta name="keywords" content="tin tức, khách sạn, du lịch, ưu đãi, LavishStay" />
                <meta property="og:title" content={t('news.pageTitle', 'Tin tức - LavishStay')} />
                <meta property="og:description" content={t('news.pageDescription', 'Cập nhật tin tức mới nhất về khách sạn, du lịch và các ưu đãi hấp dẫn từ LavishStay')} />
                <meta property="og:type" content="website" />
            </Helmet>

            {/* DEBUG BLOCK */}
            {/* {debugBlock} */}

            <Layout className="">
                <NewsHeader />

                <Content className="pt-16">
                    <motion.div
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.6 }}
                        className="container mx-auto px-4 py-6"
                    >
                        {/* Main Highlight Section */}
                        <div className="mb-8">
                            <Suspense fallback={<Spin size="large" className="flex justify-center py-12" />}>
                                <NewsMainHighlight />
                            </Suspense>
                        </div>

                        {/* Sub Highlights Section */}
                        <div className="mb-8">
                            <Suspense fallback={<Spin size="large" className="flex justify-center py-8" />}>
                                <NewsHighlights />
                            </Suspense>
                        </div>

                        {/* Category Tabs */}
                        <div className="mb-8">
                            <NewsCategoryTabs />
                        </div>

                        {/* Main Content & Sidebar */}
                        <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
                            {/* News List */}
                            <div className="lg:col-span-3">
                                <Suspense fallback={<Spin size="large" className="flex justify-center py-12" />}>
                                    <NewsList />
                                </Suspense>
                            </div>

                            {/* Sidebar */}
                            <div className="lg:col-span-1">
                                <Suspense fallback={<Spin size="large" className="flex justify-center py-8" />}>
                                    <NewsSidebar />
                                </Suspense>
                            </div>
                        </div>
                    </motion.div>
                </Content>

                {/* <NewsFooter /> */}
                {/* <NewsModal /> */}
            </Layout>
        </>
    );
};

export default NewsPage;