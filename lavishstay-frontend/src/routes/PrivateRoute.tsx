import React from "react";
import { Navigate, Outlet, useLocation } from "react-router-dom";
import { useSelector } from "react-redux";
import { RootState } from "../store";

interface PrivateRouteProps {
  redirectPath?: string;
}

const PrivateRoute: React.FC<PrivateRouteProps> = ({
  redirectPath = "/login",
}) => {
  const { isAuthenticated } = useSelector((state: RootState) => state.auth);
  const location = useLocation();

  if (!isAuthenticated) {
    // Chuyển hướng đến trang đăng nhập nếu chưa xác thực
    // Lưu URL hiện tại để có thể quay lại sau khi đăng nhập
    return <Navigate to={redirectPath} state={{ from: location }} replace />;
  }

  // Nếu đã xác thực, render các route con
  return <Outlet />;
};

export default PrivateRoute;
