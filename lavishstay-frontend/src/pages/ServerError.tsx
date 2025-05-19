import React from "react";
import { Button, Result } from "antd";
import { Link } from "react-router-dom";

const ServerError: React.FC = () => {
  return (
    <Result
      status="500"
      title="500"
      subTitle="Xin lỗi, máy chủ đang gặp sự cố."
      extra={
        <Link to="/">
          <Button type="primary">Về trang chủ</Button>
        </Link>
      }
    />
  );
};

export default ServerError;
