// src/components/news/NewsList.tsx
import React, { useState } from 'react';
import { Row, Col, Spin, Empty, Button, Alert, Card, Image, Tag, Avatar, Pagination } from 'antd';
import { motion, AnimatePresence } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { Link } from 'react-router-dom';
import { useNewsList } from '../../hooks/useNews';
import { ApiParams } from '../../services/newsApi';
import { ClockCircleOutlined, EyeOutlined, UserOutlined, ArrowRightOutlined } from '@ant-design/icons';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import 'dayjs/locale/vi';

dayjs.extend(relativeTime);
dayjs.locale('vi');

interface NewsListProps {
  className?: string;
  categoryId?: number;
}

// Helper function to get image URL with fallback
const getImageUrl = (newsItem: any): string => {
  return newsItem.featured_image ||
    newsItem.image_url ||
    newsItem.image ||
    newsItem.thumbnail?.filepath ||
    '/images/placeholder-news.png';
};

// Helper function to get summary/excerpt
const getSummary = (newsItem: any): string => {
  return newsItem.summary ||
    newsItem.excerpt ||
    newsItem.meta_description ||
    'Không có mô tả';
};

const NewsList: React.FC<NewsListProps> = ({
  className = '',
  categoryId
}) => {
  const { t } = useTranslation();

  // State cho pagination
  const [params, setParams] = useState<ApiParams>({
    per_page: 9,
    page: 1,
    category_id: categoryId,
    sort_by: 'published_at',
    sort_order: 'desc'
  });

  // Sync categoryId prop into params when it changes
  React.useEffect(() => {
    setParams(prev => {
      const next = { ...prev, page: 1 } as any;
      if (categoryId != null) {
        next.category_id = categoryId;
      } else {
        // remove category_id so API returns all
        if ('category_id' in next) delete next.category_id;
      }
      return next;
    });
  }, [categoryId]);

  // API query
  const {
    data: newsResponse,
    isLoading,
    error,
    refetch
  } = useNewsList(params);

  // Dev-only: Log response để debug
  React.useEffect(() => {
    if (process.env.NODE_ENV !== 'production') {
      console.debug('NewsList - News Response:', newsResponse);
      console.debug('NewsList - Params:', params);
    }
  }, [newsResponse, params]);

  // Handle page change
  const handlePageChange = (page: number, pageSize?: number) => {
    setParams(prev => ({
      ...prev,
      page,
      per_page: pageSize || prev.per_page,
    }));
  };

  // Loading state
  if (isLoading) {
    return (
      <div className={`space-y-6 ${className}`}>
        <div className="mb-6">
          <h3 className="text-xl font-bold text-gray-900 dark:text-white">
            {t('news.list.title', 'Danh sách tin tức')}
          </h3>
        </div>

        <Row gutter={[16, 16]}>
          {Array.from({ length: 9 }, (_, index) => (
            <Col xs={24} sm={12} lg={8} key={index}>
              <Card loading />
            </Col>
          ))}
        </Row>
      </div>
    );
  }

  // Error state
  if (error) {
    return (
      <div className={className}>
        <Alert
          message={t('news.error.loadFailed', 'Không thể tải tin tức')}
          description={t('news.error.tryAgain', 'Vui lòng thử lại sau')}
          type="error"
          showIcon
          action={
            <Button size="small" onClick={() => refetch()}>
              {t('common.retry', 'Thử lại')}
            </Button>
          }
        />
      </div>
    );
  }

  // Empty state
  if (!newsResponse?.data || newsResponse.data.length === 0) {
    return (
      <div className={className}>
        <Empty
          image={Empty.PRESENTED_IMAGE_SIMPLE}
          description={t('news.empty.noNews', 'Chưa có tin tức')}
          className="py-12"
        />
      </div>
    );
  }

  const newsList = newsResponse.data;
  const pagination = {
    current: newsResponse.current_page || 1,
    pageSize: newsResponse.per_page || 9,
    total: newsResponse.total || 0,
    lastPage: newsResponse.last_page || 1
  };

  return (
    <div className={`space-y-6 ${className}`}>
      {/* Header */}
      <div className="flex items-center justify-between mb-6">
        <h3 className="text-xl font-bold text-gray-900 dark:text-white">
          {t('news.list.title', 'Danh sách tin tức')}
        </h3>
        <div className="text-sm text-gray-500">
          {t('news.list.showing', 'Hiển thị')} {newsResponse.from || 1} - {newsResponse.to || newsList.length} / {pagination.total} {t('news.list.articles', 'bài viết')}
        </div>
      </div>

      {/* News Grid */}
      <AnimatePresence mode="wait">
        <motion.div
          key={params.page}
          initial={{ opacity: 0, y: 20 }}
          animate={{ opacity: 1, y: 0 }}
          exit={{ opacity: 0, y: -20 }}
          transition={{ duration: 0.4 }}
        >
          <Row gutter={[16, 16]}>
            {newsList.map((newsItem: any, index: number) => (
              <Col xs={24} sm={12} lg={8} key={newsItem.id}>
                <motion.div
                  initial={{ opacity: 0, scale: 0.9 }}
                  animate={{ opacity: 1, scale: 1 }}
                  transition={{ delay: index * 0.1, duration: 0.3 }}
                  whileHover={{ y: -5 }}
                >
                  <Card
                    className="h-full hover:shadow-lg transition-all duration-300 border-0 shadow-sm"
                    cover={
                      <div className="relative h-48 overflow-hidden">
                        <Link to={`/news/${newsItem.slug}`}>
                          <Image
                            src={getImageUrl(newsItem)}
                            alt={newsItem.title}
                            className="w-full h-full object-cover transition-transform duration-300 hover:scale-110"
                            fallback="/images/placeholder-news.png"
                            preview={false}
                          />
                        </Link>

                        {/* Category badge */}
                        <div className="absolute top-2 left-2">
                          <Tag className="bg-blue-500 text-white border-blue-500 rounded">
                            {newsItem.category?.name || 'Tin tức'}
                          </Tag>
                        </div>

                        {/* Featured badge */}
                        {newsItem.is_featured && (
                          <div className="absolute top-2 right-2">
                            <Tag className="bg-red-500 text-white border-red-500 rounded">
                              {t('news.featured', 'Nổi bật')}
                            </Tag>
                          </div>
                        )}
                      </div>
                    }
                    actions={[
                      <Link to={`/news/${newsItem.slug}`} key="view">
                        <Button
                          type="text"
                          icon={<ArrowRightOutlined />}
                          className="text-blue-500 hover:text-blue-600"
                        >
                          {t('news.readMore', 'Xem chi tiết')}
                        </Button>
                      </Link>
                    ]}
                  >
                    <Card.Meta
                      title={
                        <Link
                          to={`/news/${newsItem.slug}`}
                          className="text-gray-900 hover:text-blue-600 transition-colors line-clamp-2"
                        >
                          {newsItem.title}
                        </Link>
                      }
                      description={
                        <div className="space-y-3">
                          <p className="text-gray-600 text-sm line-clamp-3">
                            {getSummary(newsItem)}
                          </p>

                          {/* Author & Date */}
                          <div className="flex items-center justify-between text-xs text-gray-500">
                            <div className="flex items-center space-x-2">
                              <Avatar
                                size="small"
                                icon={<UserOutlined />}
                                src={newsItem.author?.avatar_url}
                              />
                              <span>{newsItem.author?.name || 'Admin'}</span>
                            </div>

                            <div className="flex items-center space-x-1">
                              <ClockCircleOutlined />
                              <span>{dayjs(newsItem.published_at || newsItem.publish_date).fromNow()}</span>
                            </div>
                          </div>

                          {/* Stats & Tags */}
                          <div className="flex items-center justify-between text-xs text-gray-500">
                            <div className="flex items-center space-x-3">
                              <div className="flex items-center space-x-1">
                                <EyeOutlined />
                                <span>{(newsItem.views || 0).toLocaleString()}</span>
                              </div>

                              {newsItem.comments_count > 0 && (
                                <span>{newsItem.comments_count} bình luận</span>
                              )}

                              {newsItem.likes_count > 0 && (
                                <span>{newsItem.likes_count} lượt thích</span>
                              )}
                            </div>

                            {newsItem.tags && newsItem.tags.length > 0 && (
                              <div className="flex space-x-1">
                                {newsItem.tags.slice(0, 2).map((tag: any, tagIndex: number) => (
                                  <Tag key={tagIndex} className="text-xs">
                                    #{typeof tag === 'string' ? tag : tag.name}
                                  </Tag>
                                ))}
                              </div>
                            )}
                          </div>
                        </div>
                      }
                    />
                  </Card>
                </motion.div>
              </Col>
            ))}
          </Row>
        </motion.div>
      </AnimatePresence>

      {/* Pagination */}
      {pagination.total > pagination.pageSize && (
        <div className="flex justify-center mt-8">
          <Pagination
            current={pagination.current}
            pageSize={pagination.pageSize}
            total={pagination.total}
            onChange={handlePageChange}
            onShowSizeChange={handlePageChange}
            showSizeChanger
            showQuickJumper
            showTotal={(total, range) =>
              `${range[0]}-${range[1]} / ${total} ${t('news.list.articles', 'bài viết')}`
            }
            pageSizeOptions={['6', '9', '12', '18']}
            className="custom-pagination"
          />
        </div>
      )}
    </div>
  );
};

export default NewsList;
