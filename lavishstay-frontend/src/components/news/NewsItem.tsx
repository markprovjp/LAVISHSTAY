// src/components/NewsItem.tsx
import React from 'react';
import { Link } from 'react-router-dom';
import { News } from '../../types/News';

// Props cho component NewsItem
interface NewsItemProps {
  news: News;
}

// Component hiển thị một bài viết trong danh sách
const NewsItem: React.FC<NewsItemProps> = ({ news }) => {
  return (
    <div className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
      {/* Hình ảnh đại diện của bài viết */}
      <img
        src={news.thumbnail?.filepath || '/images/no-image.png'} // Ảnh mặc định nếu không có thumbnail
        alt={news.thumbnail?.alt_text || news.meta_title} // Alt text chuẩn SEO
        className="w-full h-48 object-cover"
      />
      <div className="p-4">
        {/* Tiêu đề bài viết, liên kết đến trang chi tiết */}
        <h3 className="text-lg font-semibold text-gray-800 hover:text-blue-600">
          <Link to={`/tin-tuc/${news.slug}`}>{news.meta_title}</Link>
        </h3>
        {/* Mô tả ngắn, giới hạn 2 dòng */}
        <p className="text-gray-600 text-sm mt-2 line-clamp-2">{news.meta_description}</p>
        {/* Danh mục và ngày đăng */}
        <div className="mt-3 text-sm text-gray-500 flex justify-between">
          <span>{news.category?.name || 'Chưa có danh mục'}</span>
          <span>{new Date(news.published_at).toLocaleDateString('vi-VN')}</span>
        </div>
      </div>
    </div>
  );
};

export default NewsItem;