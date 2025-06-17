import { useEffect } from "react";

/**
 * Hook để cuộn trang đến vị trí chỉ định
 * @param behavior - Kiểu cuộn: "smooth" hoặc "instant"
 * @param top - Vị trí cuộn đến (mặc định là 0 - đầu trang)
 * @param left - Vị trí cuộn ngang (mặc định là 0)
 */
export const useScrollTo = (
    behavior: ScrollBehavior = "smooth",
    top: number = 0,
    left: number = 0
) => {
    const scrollTo = (customTop?: number, customLeft?: number) => {
        window.scrollTo({
            top: customTop ?? top,
            left: customLeft ?? left,
            behavior,
        });
    };

    return scrollTo;
};

/**
 * Hook để tự động cuộn về đầu trang khi component mount
 * @param behavior - Kiểu cuộn: "smooth" hoặc "instant"
 */
export const useScrollToTop = (behavior: ScrollBehavior = "instant") => {
    const scrollToTop = useScrollTo(behavior);

    useEffect(() => {
        scrollToTop();
    }, []);

    return scrollToTop;
};

/**
 * Hook để cuộn đến một phần tử cụ thể
 * @param elementId - ID của phần tử cần cuộn đến
 * @param behavior - Kiểu cuộn: "smooth" hoặc "instant"
 */
export const useScrollToElement = (
    elementId: string,
    behavior: ScrollBehavior = "smooth"
) => {
    const scrollToElement = () => {
        const element = document.getElementById(elementId);
        if (element) {
            element.scrollIntoView({ behavior, block: "start" });
        }
    };

    return scrollToElement;
};
