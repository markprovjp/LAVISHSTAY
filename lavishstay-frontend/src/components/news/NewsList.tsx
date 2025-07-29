// src/components/NewsList.tsx
import React, { useState, useEffect } from 'react';
import axios from 'axios';
import NewsItem from './NewsItem';
import CategoryFilter from './CategoryFilter';
import { News, NewsCategory, PaginatedResponse } from '../../types/News';

// Component hiển thị danh sách bài viết
const NewsList: React.FC = () => {
  const [news, setNews] = useState<News[]>([]); // Danh sách bài viết
  const [categories, setCategories] = useState<NewsCategory[]>([]); // Danh sách danh mục
  const [selectedCategory, setSelectedCategory] = useState<number | null>(null); // Danh mục đang chọn
  const [page, setPage] = useState<number>(1); // Trang hiện tại
  const [totalPages, setTotalPages] = useState<number>(1); // Tổng số trang
  const [loading, setLoading] = useState<boolean>(false); // Trạng thái tải dữ liệu

  // Hàm lấy danh sách bài viết từ API
  const fetchNews = async () => {
    setLoading(true);
    try {
      const response = await axios.get<PaginatedResponse<News>>('http://localhost:8888/api/news', {
        params: {
          category_id: selectedCategory, // Lọc theo danh mục
          page, // Trang hiện tại
        },
      });
      setNews(response.data.data);
      setTotalPages(response.data.last_page);
    } catch (error) {
      console.error('Lỗi khi lấy tin tức:', error);
    }
    setLoading(false);
  };

  // Hàm lấy danh sách danh mục từ API
  const fetchCategories = async () => {
    try {
      const response = await axios.get('http://localhost:8888/api/news/categories');
setCategories(response.data.data ?? []); // ✅ lấy đúng mảng

      console.log('Dữ liệu danh mục:', response.data);
    } catch (error) {
      console.error('Lỗi khi lấy danh mục:', error);
    }
  };

  // Gọi API khi thay đổi danh mục hoặc trang
  useEffect(() => {
    fetchNews();
    fetchCategories();
  }, [selectedCategory, page]);

  return (
    <div className="container mx-auto px-4 py-8">
      {/* Bộ lọc danh mục */}
      <CategoryFilter
        categories={categories}
        selectedCategory={selectedCategory}
        onCategoryChange={setSelectedCategory}
      />
      {/* Hiển thị trạng thái tải */}
      {loading ? (
        <p className="text-center text-gray-600">Đang tải...</p>
      ) : (
        <>
          {/* Danh sách bài viết */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {news.length > 0 ? (
              news.map((item) => <NewsItem key={item.id} news={item} />)
            ) : (
              <p className="text-center text-gray-600 col-span-3">Không có bài viết nào.</p>
            )}
          </div>
          {/* Phân trang */}
          <div className="mt-6 flex justify-center gap-2">
            <button
              className="px-4 py-2 bg-blue-600 text-white rounded disabled:opacity-50"
              disabled={page === 1}
              onClick={() => setPage(page - 1)}
            >
              Trang trước
            </button>
            <span className="px-4 py-2 text-gray-800">Trang {page} / {totalPages}</span>
            <button
              className="px-4 py-2 bg-blue-600 text-white rounded disabled:opacity-50"
              disabled={page === totalPages}
              onClick={() => setPage(page + 1)}
            >
              Trang sau
            </button>
          </div>
        </>
      )}
    </div>
  );
};

export default NewsList;