// src/components/news/NewsPagination.tsx
import React from 'react';
import { Pagination, ConfigProvider } from 'antd';
import { LeftOutlined, RightOutlined } from '@ant-design/icons';

interface NewsPaginationProps {
    current: number;
    total: number;
    pageSize: number;
    onChange: (page: number, pageSize?: number) => void;
    showSizeChanger?: boolean;
    showQuickJumper?: boolean;
    showTotal?: boolean;
    className?: string;
}

const NewsPagination: React.FC<NewsPaginationProps> = ({
    current,
    total,
    pageSize,
    onChange,
    showSizeChanger = true,
    showQuickJumper = false,
    showTotal = true,
    className = '',
}) => {
    const showTotalText = (total: number, range: [number, number]) => (
        <span className="text-gray-600 text-sm">
            Hiển thị {range[0]}-{range[1]} trong {total} bài viết
        </span>
    );

    if (total <= pageSize) {
        return null; // Don't show pagination if all items fit on one page
    }

    return (
        <div className={`flex justify-center py-8 ${className}`}>
            <ConfigProvider
                theme={{
                    components: {
                        Pagination: {
                            itemActiveBg: '#1890ff',
                            itemLinkBg: '#ffffff',
                            itemInputBg: '#ffffff',
                        },
                    },
                }}
            >
                <Pagination
                    current={current}
                    total={total}
                    pageSize={pageSize}
                    onChange={onChange}
                    showSizeChanger={showSizeChanger}
                    showQuickJumper={showQuickJumper}
                    showTotal={showTotal ? showTotalText : undefined}
                    pageSizeOptions={['9', '18', '36', '72']}
                    itemRender={(_, type, originalElement) => {
                        if (type === 'prev') {
                            return (
                                <div className="flex items-center px-2 py-1 hover:bg-blue-50 rounded transition-colors">
                                    <LeftOutlined className="text-sm" />
                                    <span className="ml-1 hidden sm:inline">Trước</span>
                                </div>
                            );
                        }
                        if (type === 'next') {
                            return (
                                <div className="flex items-center px-2 py-1 hover:bg-blue-50 rounded transition-colors">
                                    <span className="mr-1 hidden sm:inline">Sau</span>
                                    <RightOutlined className="text-sm" />
                                </div>
                            );
                        }
                        return originalElement;
                    }}
                    className="custom-pagination"
                />
            </ConfigProvider>

            <style>{`
        .custom-pagination .ant-pagination-item {
          border-radius: 6px;
          border: 1px solid #d9d9d9;
          transition: all 0.3s ease;
        }
        
        .custom-pagination .ant-pagination-item:hover {
          border-color: #1890ff;
          transform: translateY(-1px);
        }
        
        .custom-pagination .ant-pagination-item-active {
          background: #1890ff;
          border-color: #1890ff;
          box-shadow: 0 2px 4px rgba(24, 144, 255, 0.2);
        }
        
        .custom-pagination .ant-pagination-item-active a {
          color: white;
        }
        
        .custom-pagination .ant-pagination-jump-prev,
        .custom-pagination .ant-pagination-jump-next {
          border-radius: 6px;
        }
      `}</style>
        </div>
    );
};

export default NewsPagination;
