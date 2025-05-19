import React from "react";
import { Navigate, useLocation } from "react-router-dom";
import NotFound from "./NotFound";
import Forbidden from "./Forbidden";
import ServerError from "./ServerError";

export type ErrorType = "404" | "403" | "500";

interface ErrorPageProps {
  type?: ErrorType;
}

const ErrorPage: React.FC<ErrorPageProps> = ({ type }) => {
  const location = useLocation();
  const errorType = type || (location.state as any)?.errorType || "404";

  switch (errorType) {
    case "403":
      return <Forbidden />;
    case "500":
      return <ServerError />;
    case "404":
    default:
      return <NotFound />;
  }
};

// Helper function to redirect to an error page
export const redirectToError = (type: ErrorType) => {
  return <Navigate to="/error" state={{ errorType: type }} replace />;
};

export default ErrorPage;
