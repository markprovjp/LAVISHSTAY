import React from "react";
import { Typography, Breadcrumb } from "antd";
import { Link } from "react-router-dom";
import { HomeOutlined } from "@ant-design/icons";

const { Title } = Typography;

interface BreadcrumbItem {
  title: string;
  path?: string;
  icon?: React.ReactNode;
}

interface PageHeaderProps {
  title: string;
  subtitle?: string;
  breadcrumbs?: BreadcrumbItem[];
  coverImage?: string;
  className?: string;
  contentClassName?: string;
}

const PageHeader: React.FC<PageHeaderProps> = ({
  title,
  subtitle,
  breadcrumbs = [],
  coverImage,
  className = "",
  contentClassName = "",
}) => {
  const defaultBreadcrumbs: BreadcrumbItem[] = [
    {
      title: "Trang chủ",
      path: "/",
      icon: <HomeOutlined />,
    },
  ];

  const allBreadcrumbs = [...defaultBreadcrumbs, ...breadcrumbs];

  return (
    <div
      className={`relative ${className}`}
      style={{
        backgroundImage: coverImage ? `url(${coverImage})` : undefined,
        backgroundSize: "cover",
        backgroundPosition: "center",
        backgroundColor: coverImage ? undefined : "#f0f5ff",
      }}
    >
      {coverImage && (
        <div
          className="absolute inset-0"
          style={{ backgroundColor: "rgba(0, 0, 0, 0.5)" }}
        ></div>
      )}

      <div
        className={`relative py-12 px-6 md:px-12 ${
          coverImage ? "text-white" : "text-gray-800"
        } ${contentClassName}`}
      >
        <div className="max-w-6xl mx-auto">
          <Breadcrumb className="mb-4">
            {allBreadcrumbs.map((item, index) => (
              <Breadcrumb.Item key={index}>
                {item.path ? (
                  <Link to={item.path} className="flex items-center">
                    {item.icon && <span className="mr-1">{item.icon}</span>}
                    <span
                      className={`font-bevietnam ${
                        coverImage ? "text-blue-100" : "text-gray-600"
                      }`}
                    >
                      {item.title}
                    </span>
                  </Link>
                ) : (
                  <span className="flex items-center font-bevietnam">
                    {item.icon && <span className="mr-1">{item.icon}</span>}
                    <span
                      className={coverImage ? "text-white" : "text-gray-800"}
                    >
                      {item.title}
                    </span>
                  </span>
                )}
              </Breadcrumb.Item>
            ))}
          </Breadcrumb>

          <Title
            level={1}
            className={`font-bevietnam font-bold mb-2 ${
              coverImage ? "text-white" : "text-blue-700"
            }`}
          >
            {title}
          </Title>

          {subtitle && (
            <p
              className={`font-bevietnam text-lg ${
                coverImage ? "text-blue-100" : "text-gray-600"
              }`}
            >
              {subtitle}
            </p>
          )}
        </div>
      </div>
    </div>
  );
};

export default PageHeader;
