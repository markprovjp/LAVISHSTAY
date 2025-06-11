import React from 'react';
import { Breadcrumb as AntBreadcrumb } from 'antd';
import { Link, useLocation } from 'react-router-dom';
import { HomeOutlined } from '@ant-design/icons';

const breadcrumbNameMap: Record<string, string> = {
  '/hotels': 'Khách sạn',
  '/hotels/:id': 'Chi tiết khách sạn',
  '/profile': 'Hồ sơ cá nhân',
  '/bookings': 'Đặt phòng của tôi',
  '/wishlist': 'Danh sách yêu thích',
  '/settings': 'Cài đặt',
  '/about': 'Về chúng tôi',
  '/contact': 'Liên hệ',
  '/destinations': 'Điểm đến',
  '/register': 'Đăng ký',
  '/notifications': 'Thông báo',

  '/payment': 'Thanh toán',
  // Add other routes here
};

const Breadcrumb: React.FC = () => {
  const location = useLocation();
  const pathSnippets = location.pathname.split('/').filter((i) => i);

  const extraBreadcrumbItems = pathSnippets.map((_, index) => {
    const url = `/${pathSnippets.slice(0, index + 1).join('/')}`;
    // Check if the current path segment is a dynamic parameter (e.g., :id)
    const isDynamic = pathSnippets[index].startsWith(':');
    let name = breadcrumbNameMap[url];

    // If the name is not found, try to find a more generic one (e.g. /hotels/:id -> /hotels/123)
    if (!name) {
      const genericUrl = `/${pathSnippets.slice(0, index).join('/')}/:${pathSnippets[index].split('-')[0]}`;
      if (breadcrumbNameMap[genericUrl]) {
        name = breadcrumbNameMap[genericUrl];
      } else if (isDynamic) {
        // Fallback for dynamic segments not explicitly mapped
        name = pathSnippets[index-1] ? breadcrumbNameMap[`/${pathSnippets[index-1]}`] + ' Chi tiết' : 'Chi tiết';
      }
       else {
        name = pathSnippets[index].charAt(0).toUpperCase() + pathSnippets[index].slice(1);
      }
    }

    return {
      key: url,
      title: <Link to={url}>{name}</Link>,
    };
  });

  const breadcrumbItems = [
    {
      title: <Link to="/"><HomeOutlined /></Link>,
      key: 'home',
    },
  ].concat(extraBreadcrumbItems);

  // Do not render breadcrumbs on the homepage
  if (location.pathname === '/') {
    return null;
  }

  return (
    <div className="bg-gray-50 dark:bg-gray-800 py-3 px-4 md:px-8 shadow-sm">
        <div className="container mx-auto">
            <AntBreadcrumb items={breadcrumbItems} className="font-bevietnam" />
        </div>
    </div>
  );
};

export default Breadcrumb;
