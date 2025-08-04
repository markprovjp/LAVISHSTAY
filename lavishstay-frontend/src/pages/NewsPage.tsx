
// src/pages/NewsPage.tsx
import React from 'react';
import NewsList from '../components/news/NewsList';

// Trang chính hiển thị danh sách tin tức
const NewsPage: React.FC = () => {
  return (
    <div>
      <header className="bg-blue-600 text-white py-6">
        <div className="container mx-auto px-4">
          <h1 className="text-3xl font-bold">Tin tức khách sạn</h1>
          <p className="mt-2">Cập nhật tin tức, ưu đãi và sự kiện mới nhất từ khách sạn của bạn.</p>
        </div>
      </header>
      <NewsList />
    </div>
  );
};

export default NewsPage;
