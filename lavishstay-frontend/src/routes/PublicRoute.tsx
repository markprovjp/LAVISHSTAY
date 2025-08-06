import React from "react";
import { Navigate, Outlet, useLocation } from "react-router-dom";
import { useSelector } from "react-redux";
import { RootState } from "../store";

interface PublicRouteProps {
  redirectPath?: string;
  restricted?: boolean;
}

/**
 * Public route component
 * @param redirectPath - path to redirect to if user is authenticated and route is restricted
 * @param restricted - whether this route should be restricted for authenticated users
 */
const PublicRoute: React.FC<PublicRouteProps> = ({
  redirectPath = "/dashboard",
  restricted = false,
}) => {
  const { isAuthenticated } = useSelector((state: RootState) => state.auth);
  const location = useLocation();

  // If user is authenticated and the route is restricted, redirect to dashboard
  if (isAuthenticated && restricted) {
    const from = location.state?.from?.pathname || redirectPath;
    return <Navigate to={from} replace />;
  }

  // Otherwise render the route
  return <Outlet />;
};

export default PublicRoute;
