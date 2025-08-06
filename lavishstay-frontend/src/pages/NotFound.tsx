import React from "react";
import { Button, Result } from "antd";
import { Link } from "react-router-dom";

const NotFound: React.FC = () => {
  return (
    <Result
      status="404"
      title="404"
      subTitle="Xin lỗi, trang bạn đang tìm kiếm không tồn tại."
      extra={
        <Link to="/">
          <Button type="primary">Về trang chủ</Button>
        </Link>
      }
    />
  );
};

export default NotFound;
