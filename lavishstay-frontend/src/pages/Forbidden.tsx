import React from "react";
import { Button, Result } from "antd";
import { Link } from "react-router-dom";

const Forbidden: React.FC = () => {
  return (
    <Result
      status="403"
      title="403"
      subTitle="Xin lỗi, bạn không có quyền truy cập trang này."
      extra={
        <Link to="/">
          <Button type="primary">Về trang chủ</Button>
        </Link>
      }
    />
  );
};

export default Forbidden;
