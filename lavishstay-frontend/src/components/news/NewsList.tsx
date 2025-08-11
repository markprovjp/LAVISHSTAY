// src/components/news/NewsList.tsx
import React, { useState } from 'react';
import { Row, Col, Spin, Empty, Button, Select, Input, Space, Alert } from 'antd';
import { motion, AnimatePresence } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { FilterOutlined, SortAscendingOutlined, ReloadOutlined } from '@ant-design/icons';
import { useNewsList } from '../../hooks/useNews';
import { ApiParams } from '../../services/newsApi';
import NewsItem from './NewsItem';
import NewsCategoryFilter from './NewsCategoryFilter';
import NewsPagination from './NewsPagination';
import { useNavigate } from 'react-router-dom';

const { Search } = Input;
const { Option } = Select;

interface NewsListProps {
  category?: number;
  searchQuery?: string;
  className?: string;
}

const NewsList: React.FC<NewsListProps> = ({
  category,
  searchQuery,
  className = '',
}) => {
  const { t } = useTranslation();
  const navigate = useNavigate();
  
  // State for filters and pagination
  const [params, setParams] = useState<ApiParams>({
    per_page: 9,
    category_id: category,
    search_title: searchQuery,
    sort_by: 'published_at',
    sort_order: 'desc',
    page: 1,
  });

  // API query
  const { 
    data: newsResponse, 
    isLoading, 
    error, 
    refetch 
  } = useNewsList(params);

  // Handle filter changes
  const handleCategoryChange = (categoryId: number | undefined) => {
    setParams(prev => ({
      ...prev,
      category_id: categoryId,
      page: 1, // Reset to first page
    }));
  };

  const handleSearch = (value: string) => {
    setParams(prev => ({
      ...prev,
      search_title: value || undefined,
      page: 1, // Reset to first page
    }));
  };

  const handleSortChange = (value: string) => {
    const [sortBy, sortOrder] = value.split('_');
    setParams(prev => ({
      ...prev,
      sort_by: sortBy as 'published_at' | 'views' | 'created_at',
      sort_order: sortOrder as 'asc' | 'desc',
      page: 1, // Reset to first page
    }));
  };

  const handlePageChange = (page: number, pageSize?: number) => {
    setParams(prev => ({
      ...prev,
      page,
      per_page: pageSize || prev.per_page,
    }));
  };

  const handleNewsClick = (slug: string) => {
    navigate(`/news/${slug}`);
  };

  const handleRefresh = () => {
    refetch();
  };

  // Loading state
  if (isLoading) {
    return (
      <div className="flex justify-center items-center py-20">
        <Spin size="large" tip={t('news.loading', 'Đang tải tin tức...')} />
      </div>
    );
  }

  // Error state
  if (error) {
    return (
      <div className="py-8">
        <Alert
          message={t('news.error.title', 'Không thể tải tin tức')}
          description={t('news.error.description', 'Đã có lỗi xảy ra khi tải danh sách tin tức. Vui lòng thử lại.')}
          type="error"
          showIcon
          action={
            <Button size="small" danger onClick={handleRefresh}>
              {t('common.retry', 'Thử lại')}
            </Button>
          }
        />
      </div>
    );
  }

  const newsData = newsResponse?.data || [];
  const pagination = {
    current: newsResponse?.current_page || 1,
    total: newsResponse?.total || 0,
    pageSize: newsResponse?.per_page || 9,
  };

  return (
    <div className={`news-list ${className}`}>
      {/* Filters */}
      <div className="mb-6">
        <div className="flex flex-col lg:flex-row gap-4 items-start lg:items-center justify-between">
          <div className="flex flex-col sm:flex-row gap-3 flex-1">
            {/* Search */}
            <Search
              placeholder={t('news.search.placeholder', 'Tìm kiếm tin tức...')}
              allowClear
              onSearch={handleSearch}
              defaultValue={params.search_title}
              className="max-w-xs"
              enterButton
            />
            
            {/* Category Filter */}
            <NewsCategoryFilter
              value={params.category_id}
              onChange={handleCategoryChange}
              placeholder={t('news.filter.category', 'Tất cả chuyên mục')}
            />
          </div>

          <Space>
            {/* Sort */}
            <Select
              value={`${params.sort_by}_${params.sort_order}`}
              onChange={handleSortChange}
              style={{ width: 180 }}
              suffixIcon={<SortAscendingOutlined />}
            >
              <Option value="published_at_desc">{t('news.sort.newest', 'Mới nhất')}</Option>
              <Option value="published_at_asc">{t('news.sort.oldest', 'Cũ nhất')}</Option>
              <Option value="views_desc">{t('news.sort.mostViewed', 'Xem nhiều nhất')}</Option>
              <Option value="created_at_desc">{t('news.sort.latest', 'Vừa tạo')}</Option>
            </Select>
            
            {/* Refresh */}
            <Button 
              icon={<ReloadOutlined />} 
              onClick={handleRefresh}
              loading={isLoading}
              title={t('common.refresh', 'Làm mới')}
            />
            
            {/* Filter indicator */}
            {(params.category_id || params.search_title) && (
              <Button 
                icon={<FilterOutlined />} 
                type="primary" 
                ghost
                size="small"
                onClick={() => setParams(prev => ({ 
                  ...prev, 
                  category_id: undefined, 
                  search_title: undefined, 
                  page: 1 
                }))}
              >
                {t('news.filter.clear', 'Xóa bộ lọc')}
              </Button>
            )}
          </Space>
        </div>
      </div>

      {/* Results count */}
      {newsData.length > 0 && (
        <div className="mb-4">
          <p className="text-gray-600 text-sm">
            {t('news.results.count', 'Tìm thấy {{total}} bài viết', { 
              total: pagination.total.toLocaleString() 
            })}
            {params.search_title && (
              <span> {t('news.results.for', 'cho')} "<strong>{params.search_title}</strong>"</span>
            )}
          </p>
        </div>
      )}

      {/* News Grid */}
      <AnimatePresence mode="wait">
        {newsData.length === 0 ? (
          <motion.div
            key="empty"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="py-12"
          >
            <Empty
              description={
                params.search_title || params.category_id
                  ? t('news.empty.filtered', 'Không tìm thấy tin tức phù hợp với bộ lọc')
                  : t('news.empty.default', 'Chưa có tin tức nào')
              }
              image={Empty.PRESENTED_IMAGE_SIMPLE}
            />
          </motion.div>
        ) : (
          <motion.div
            key="content"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
          >
            <Row gutter={[24, 24]}>
              {newsData.map((news, index) => (
                <Col 
                  key={news.id} 
                  xs={24} 
                  sm={12} 
                  lg={8}
                  xl={8}
                >
                  <motion.div
                    initial={{ opacity: 0, y: 20 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: index * 0.1 }}
                  >
                    <NewsItem
                      news={news}
                      onClick={handleNewsClick}
                      showCategory={!params.category_id}
                      showAuthor={true}
                      showViews={true}
                      showTags={true}
                    />
                  </motion.div>
                </Col>
              ))}
            </Row>

            {/* Pagination */}
            <NewsPagination
              current={pagination.current}
              total={pagination.total}
              pageSize={pagination.pageSize}
              onChange={handlePageChange}
              showSizeChanger={true}
              showTotal={true}
              className="mt-8"
            />
          </motion.div>
        )}
      </AnimatePresence>
    </div>
  );
};

export default NewsList;
//             title: 'LavishStay được vinh danh "Khách sạn tốt nhất năm 2024"',
//             summary: 'Giải thưởng danh giá từ Hiệp hội Du lịch Quốc tế khẳng định chất lượng dịch vụ xuất sắc của chúng tôi.',
//             imageUrl: 'https://images.unsplash.com/photo-1551218808-94e220e084d2?w=400&h=250&fit=crop',
//             category: 'Giải thưởng',
//             publishedAt: new Date('2024-01-08'),
//             author: { name: 'Ban biên tập' },
//             views: 8765,
//             tags: ['giải thưởng', 'vinh danh', '2024'],
//             isBookmarked: false,
//             isLiked: true,
//             likesCount: 234
//         },
//         {
//             id: '9',
//             title: 'Workshop pha chế cocktail miễn phí cho khách hàng',
//             summary: 'Học cách pha chế những ly cocktail tuyệt vời từ bartender chuyên nghiệp của chúng tôi.',
//             imageUrl: 'https://images.unsplash.com/photo-1551538827-9c037cb4f32a?w=400&h=250&fit=crop',
//             category: 'Sự kiện',
//             publishedAt: new Date('2024-01-07'),
//             author: { name: 'Nguyễn Mixer' },
//             views: 3456,
//             tags: ['workshop', 'cocktail', 'miễn phí'],
//             isBookmarked: true,
//             isLiked: false,
//             likesCount: 89
//         },
//         {
//             id: '10',
//             title: 'Chương trình âm nhạc cuối tuần tại LavishLounge',
//             summary: 'Thưởng thức những giai điệu tuyệt vời từ các nghệ sĩ tài năng trong không gian sang trọng.',
//             imageUrl: 'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=400&h=250&fit=crop',
//             category: 'Giải trí',
//             publishedAt: new Date('2024-01-06'),
//             author: { name: 'Music Team' },
//             views: 5678,
//             tags: ['âm nhạc', 'cuối tuần', 'lounge'],
//             isBookmarked: false,
//             isLiked: true,
//             likesCount: 156
//         },
//         {
//             id: '11',
//             title: 'Khóa học nấu ăn với đầu bếp Michelin Star',
//             summary: 'Cơ hội hiếm có để học nấu ăn từ đầu bếp đạt sao Michelin ngay tại khách sạn.',
//             imageUrl: 'https://images.unsplash.com/photo-1556909114-b7a93d48d766?w=400&h=250&fit=crop',
//             category: 'Ẩm thực',
//             publishedAt: new Date('2024-01-05'),
//             author: { name: 'Chef Martin' },
//             views: 9876,
//             tags: ['nấu ăn', 'michelin', 'khóa học'],
//             isBookmarked: true,
//             isLiked: true,
//             likesCount: 567
//         },
//         {
//             id: '12',
//             title: 'Gói nghỉ dưỡng kết hợp yoga và meditation',
//             summary: 'Tìm lại sự cân bằng trong cuộc sống với chương trình yoga và thiền định chuyên nghiệp.',
//             imageUrl: 'https://images.unsplash.com/photo-1506629905607-ea9a6a27a9eb?w=400&h=250&fit=crop',
//             category: 'Wellness',
//             publishedAt: new Date('2024-01-04'),
//             author: { name: 'Yoga Master' },
//             views: 4321,
//             tags: ['yoga', 'meditation', 'wellness'],
//             isBookmarked: false,
//             isLiked: false,
//             likesCount: 123
//         },
//         {
//             id: '13',
//             title: 'Triển lãm nghệ thuật "Vẻ đẹp Việt Nam" tại lobby',
//             summary: 'Khám phá vẻ đẹp đất nước qua triển lãm tranh và tác phẩm nghệ thuật độc đáo.',
//             imageUrl: 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=400&h=250&fit=crop',
//             category: 'Văn hóa',
//             publishedAt: new Date('2024-01-03'),
//             author: { name: 'Art Curator' },
//             views: 2345,
//             tags: ['triển lãm', 'nghệ thuật', 'việt nam'],
//             isBookmarked: false,
//             isLiked: true,
//             likesCount: 78
//         }
//     ];

//     // Filter by category
//     let filteredNews = category === 'all'
//         ? mockNews
//         : mockNews.filter(news => news.category.toLowerCase().includes(category.toLowerCase()));

//     // Filter by search query
//     if (searchQuery) {
//         filteredNews = filteredNews.filter(news =>
//             news.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
//             news.summary.toLowerCase().includes(searchQuery.toLowerCase()) ||
//             news.tags.some(tag => tag.toLowerCase().includes(searchQuery.toLowerCase()))
//         );
//     }

//     // Sort news
//     if (sortBy === 'publishedAt') {
//         filteredNews.sort((a, b) => new Date(b.publishedAt).getTime() - new Date(a.publishedAt).getTime());
//     } else if (sortBy === 'views') {
//         filteredNews.sort((a, b) => b.views - a.views);
//     } else if (sortBy === 'likes') {
//         filteredNews.sort((a, b) => b.likesCount - a.likesCount);
//     }

//     const pageSize = 6;
//     const startIndex = (pageParam - 1) * pageSize;
//     const endIndex = startIndex + pageSize;
//     const paginatedNews = filteredNews.slice(startIndex, endIndex);

//     return {
//         data: paginatedNews,
//         total: filteredNews.length,
//         hasNextPage: endIndex < filteredNews.length,
//         nextPage: endIndex < filteredNews.length ? pageParam + 1 : undefined
//     };
// };

// const NewsList: React.FC<NewsListProps> = ({
//     category = 'all',
//     searchQuery = '',
//     onNewsClick
// }) => {
//     const { t } = useTranslation();
//     const [currentPage, setCurrentPage] = useState(1);
//     const [sortBy, setSortBy] = useState<'publishedAt' | 'views' | 'likes'>('publishedAt');
//     const [localSearchQuery, setLocalSearchQuery] = useState(searchQuery);

//     const {
//         data,
//         isLoading,
//         error,
//         refetch,
//         isFetching
//     } = useQuery({
//         queryKey: ['newsList', category, sortBy, localSearchQuery, currentPage],
//         queryFn: () => fetchNewsList({
//             pageParam: currentPage,
//             category,
//             sortBy,
//             searchQuery: localSearchQuery
//         }),
//         staleTime: 2 * 60 * 1000, // 2 minutes
//     });

//     const handlePageChange = (page: number) => {
//         setCurrentPage(page);
//         window.scrollTo({ top: 0, behavior: 'smooth' });
//     };

//     const handleSortChange = (value: 'publishedAt' | 'views' | 'likes') => {
//         setSortBy(value);
//         setCurrentPage(1);
//     };

//     const handleSearch = (value: string) => {
//         setLocalSearchQuery(value);
//         setCurrentPage(1);
//     };

//     const handleRefresh = () => {
//         refetch();
//     };

//     if (isLoading) {
//         return (
//             <div className="space-y-6">
//                 <div className="flex justify-center py-12">
//                     <Spin size="large" />
//                 </div>
//             </div>
//         );
//     }

//     if (error) {
//         return (
//             <div className="text-center py-12">
//                 <Empty
//                     description={t('news.error', 'Có lỗi xảy ra khi tải tin tức')}
//                     image={Empty.PRESENTED_IMAGE_SIMPLE}
//                 >
//                     <Button type="primary" onClick={handleRefresh}>
//                         {t('news.retry', 'Thử lại')}
//                     </Button>
//                 </Empty>
//             </div>
//         );
//     }

//     return (
//         <motion.div
//             initial={{ opacity: 0 }}
//             animate={{ opacity: 1 }}
//             transition={{ duration: 0.5 }}
//             className="space-y-6"
//         >
//             {/* Header Controls */}
//             <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
//                 <div className="flex items-center space-x-3">
//                     <h3 className="text-lg font-semibold text-gray-900 dark:text-white">
//                         {t('news.list.title', 'Danh sách tin tức')}
//                     </h3>
//                     {isFetching && (
//                         <motion.div
//                             animate={{ rotate: 360 }}
//                             transition={{ duration: 1, repeat: Infinity, ease: "linear" }}
//                         >
//                             <ReloadOutlined className="text-blue-500" />
//                         </motion.div>
//                     )}
//                 </div>

//                 <div className="flex flex-col sm:flex-row items-start sm:items-center gap-3 w-full sm:w-auto">
//                     {/* Search */}
//                     <Search
//                         placeholder={t('news.search.placeholder', 'Tìm kiếm tin tức...')}
//                         allowClear
//                         enterButton
//                         size="middle"
//                         className="w-full sm:w-64"
//                         onSearch={handleSearch}
//                         defaultValue={localSearchQuery}
//                     />

//                     {/* Sort */}
//                     <Select
//                         value={sortBy}
//                         onChange={handleSortChange}
//                         size="middle"
//                         className="w-full sm:w-40"
//                         suffixIcon={<SortAscendingOutlined />}
//                     >
//                         <Option value="publishedAt">{t('news.sort.latest', 'Mới nhất')}</Option>
//                         <Option value="views">{t('news.sort.popular', 'Phổ biến')}</Option>
//                         <Option value="likes">{t('news.sort.liked', 'Yêu thích')}</Option>
//                     </Select>

//                     {/* Refresh */}
//                     <Button
//                         type="text"
//                         icon={<FilterOutlined />}
//                         onClick={handleRefresh}
//                         loading={isFetching}
//                         className="w-full sm:w-auto"
//                     >
//                         {t('news.refresh', 'Làm mới')}
//                     </Button>
//                 </div>
//             </div>

//             {/* News Grid */}
//             <AnimatePresence mode="wait">
//                 {data?.data && data.data.length > 0 ? (
//                     <motion.div
//                         key={`${category}-${sortBy}-${currentPage}`}
//                         initial={{ opacity: 0, y: 20 }}
//                         animate={{ opacity: 1, y: 0 }}
//                         exit={{ opacity: 0, y: -20 }}
//                         transition={{ duration: 0.4 }}
//                     >
//                         <Row gutter={[24, 24]}>
//                             {data.data.map((news, index) => (
//                                 <Col xs={24} sm={12} lg={8} key={news.id}>
//                                     <motion.div
//                                         initial={{ opacity: 0, y: 30 }}
//                                         animate={{ opacity: 1, y: 0 }}
//                                         transition={{
//                                             duration: 0.5,
//                                             delay: index * 0.1,
//                                             ease: "easeOut"
//                                         }}
//                                         whileHover={{ y: -5 }}
//                                         className="h-full"
//                                     >
//                                         <NewsCard
//                                             news={news}
//                                             onClick={() => onNewsClick?.(news)}
//                                         />
//                                     </motion.div>
//                                 </Col>
//                             ))}
//                         </Row>

//                         {/* Pagination */}
//                         {data.total > 6 && (
//                             <motion.div
//                                 initial={{ opacity: 0 }}
//                                 animate={{ opacity: 1 }}
//                                 transition={{ delay: 0.3 }}
//                                 className="flex justify-center mt-8"
//                             >
//                                 <Pagination
//                                     current={currentPage}
//                                     total={data.total}
//                                     pageSize={6}
//                                     onChange={handlePageChange}
//                                     showSizeChanger={false}
//                                     showQuickJumper
//                                     showTotal={(total, range) =>
//                                         `${range[0]}-${range[1]} ${t('news.pagination.of', 'trong')} ${total} ${t('news.pagination.items', 'tin tức')}`
//                                     }
//                                     className="custom-pagination"
//                                 />
//                             </motion.div>
//                         )}
//                     </motion.div>
//                 ) : (
//                     <motion.div
//                         initial={{ opacity: 0 }}
//                         animate={{ opacity: 1 }}
//                         className="py-12"
//                     >
//                         <Empty
//                             description={t('news.empty', 'Không có tin tức nào')}
//                             image={Empty.PRESENTED_IMAGE_SIMPLE}
//                         />
//                     </motion.div>
//                 )}
//             </AnimatePresence>

//             {/* Stats */}
//             <motion.div
//                 initial={{ opacity: 0 }}
//                 animate={{ opacity: 1 }}
//                 transition={{ delay: 0.5 }}
//                 className="text-center text-sm text-gray-500 dark:text-gray-400"
//             >
//                 {data?.total && (
//                     <p>
//                         {t('news.stats.showing', 'Hiển thị')} {data.data.length} {t('news.stats.of', 'trong')} {data.total} {t('news.stats.articles', 'bài viết')}
//                     </p>
//                 )}
//             </motion.div>
//         </motion.div>
//     );
// };

// export default NewsList;
