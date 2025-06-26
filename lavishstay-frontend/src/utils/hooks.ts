// src/utils/hooks.ts
import { useEffect } from "react";
import { useLocation } from "react-router-dom";

/**
 * Hook tùy chỉnh để cuộn về đầu trang khi pathname thay đổi
 * Sử dụng trong các component cần kiểm soát việc cuộn trang
 */
export const useScrollToTop = () => {
    const { pathname } = useLocation();

    useEffect(() => {
        window.scrollTo({
            top: 0,
            left: 0,
            behavior: "instant",
        });
    }, [pathname]);
};

/**
 * Hook để cuộn về đầu trang với các tùy chọn
 */
export const useScrollTo = () => {
    const scrollToTop = (behavior: ScrollBehavior = "smooth") => {
        window.scrollTo({
            top: 0,
            left: 0,
            behavior,
        });
    };

    const scrollToElement = (elementId: string, behavior: ScrollBehavior = "smooth") => {
        const element = document.getElementById(elementId);
        if (element) {
            element.scrollIntoView({ behavior });
        }
    };

    return { scrollToTop, scrollToElement };
};
