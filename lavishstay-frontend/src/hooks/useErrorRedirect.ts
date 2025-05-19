import { useNavigate } from "react-router-dom";
import { ErrorType } from "../pages/ErrorPage";

export const useErrorRedirect = () => {
  const navigate = useNavigate();

  const redirectToError = (errorType: ErrorType) => {
    navigate("/error", { state: { errorType }, replace: true });
  };

  return {
    redirectToError,
    redirectTo404: () => redirectToError("404"),
    redirectTo403: () => redirectToError("403"),
    redirectTo500: () => redirectToError("500"),
  };
};

export default useErrorRedirect;
