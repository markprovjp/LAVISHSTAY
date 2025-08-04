// src/components/NewsDetail.tsx
import React, { useState, useEffect } from 'react';
import { useParams } from 'react-router-dom';
import axios from 'axios';
import { Helmet } from 'react-helmet-async';
import { News } from '../../types/news';

// Component hiển thị chi tiết bài viết
const NewsDetail: React.FC = () => {
  const { slug } = useParams<{ slug: string }>(); // Lấy slug từ URL
  const [news, setNews] = useState<News | null>(null); // Thông tin bài viết
  const [loading, setLoading] = useState<boolean>(false); // Trạng thái tải

  // Hàm lấy chi tiết bài viết từ API
  const fetchNewsDetail = async () => {
    setLoading(true);
    try {
      const response = await axios.get<News>(`http://localhost:8888/api/news/${slug}`);
      setNews(response.data);
    } catch (error) {
      console.error('Lỗi khi lấy chi tiết bài viết:', error);
    }
    setLoading(false);
  };

  // Gọi API khi component được tải
  useEffect(() => {
    fetchNewsDetail();
  }, [slug]);

  // Structured data cho SEO
  const structuredData = news
    ? {
        '@context': 'https://schema.org',
        '@type': 'NewsArticle',
        headline: news.meta_title,
        description: news.meta_description,
        image: news.thumbnail?.filepath || '/images/no-image.png',
        datePublished: news.published_at,
        author: {
          '@type': 'Person',
          name: news.author?.name || 'Unknown',
        },
        publisher: {
          '@type': 'Organization',
          name: 'Your Hotel Name',
          logo: {
            '@type': 'ImageObject',
            url: '/images/logo.png',
          },
        },
      }
    : null;

  return (
    <div className="container mx-auto px-4 py-8">
      {loading ? (
        <p className="text-center text-gray-600">Đang tải...</p>
      ) : news ? (
        <>
          {/* Meta tags cho SEO */}
          <Helmet>
            <title>{news.meta_title}</title>
            <meta name="description" content={news.meta_description} />
            <meta name="keywords" content={news.meta_keywords} />
            <link rel="canonical" href={`http://localhost:8888/api/news/${news.slug}`} />
            {structuredData && (
              <script type="application/ld+json">{JSON.stringify(structuredData)}</script>
            )}
          </Helmet>
          {/* Nội dung bài viết */}
          <div className="bg-white rounded-lg shadow-md p-6">
            <img
              src={news.thumbnail?.filepath || '/images/no-image.png'}
              alt={news.thumbnail?.alt_text || news.meta_title}
              className="w-full h-64 object-cover rounded-md mb-4"
            />
            <h1 className="text-3xl font-bold text-gray-800 mb-4">{news.meta_title}</h1>
            <div className="text-sm text-gray-500 mb-4 flex justify-between">
              <span>{news.category?.name || 'Chưa có danh mục'}</span>
              <span>{new Date(news.published_at).toLocaleDateString('vi-VN')}</span>
            </div>
            <div
              className="prose max-w-none"
              dangerouslySetInnerHTML={{ __html: news.content }} // Hiển thị nội dung HTML từ CKEditor
            />
          </div>
        </>
      ) : (
        <p className="text-center text-gray-600">Bài viết không tồn tại.</p>
      )}
    </div>
  );
};

export default NewsDetail;