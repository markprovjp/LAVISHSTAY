import React, { Suspense } from "react";
import { Spin } from "antd";

/**
 * Interface cho LazyLoad component
 */
interface LazyLoadProps {
  /**
   * Import động của component cần lazy load
   * @example
   * import('./path/to/Component')
   */
  component: React.LazyExoticComponent<React.ComponentType<any>>;

  /**
   * Props để truyền vào component
   */
  componentProps?: Record<string, any>;

  /**
   * Component fallback hiển thị khi đang tải
   */
  fallback?: React.ReactNode;
}

/**
 * HOC để lazy load components
 * Giúp tối ưu việc tải trang ban đầu
 */
const LazyLoad: React.FC<LazyLoadProps> = ({
  component: Component,
  componentProps = {},
  fallback = (
    <div className="flex justify-center items-center p-10">
      <Spin size="large" />
    </div>
  ),
}) => {
  return (
    <Suspense fallback={fallback}>
      <Component {...componentProps} />
    </Suspense>
  );
};

export default LazyLoad;
