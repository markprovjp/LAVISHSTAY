/**
 * Scroll Manager - Centralized scroll control to prevent conflicts
 */

class ScrollManager {
    private static instance: ScrollManager;
    private isScrollDisabled = false;
    private scrollDisableTimeout?: ReturnType<typeof setTimeout>;

    private constructor() { }

    static getInstance(): ScrollManager {
        if (!ScrollManager.instance) {
            ScrollManager.instance = new ScrollManager();
        }
        return ScrollManager.instance;
    }

    /**
     * Temporarily disable scroll events to prevent conflicts
     * @param duration Duration in milliseconds to disable scroll
     */
    disableScrollTemporarily(duration: number = 500): void {
        this.isScrollDisabled = true;

        if (this.scrollDisableTimeout) {
            clearTimeout(this.scrollDisableTimeout);
        }

        this.scrollDisableTimeout = setTimeout(() => {
            this.isScrollDisabled = false;
        }, duration);
    }

    /**
     * Check if scroll is currently disabled
     */
    isScrollCurrentlyDisabled(): boolean {
        return this.isScrollDisabled;
    }

    /**
     * Safe scroll to top with conflict prevention
     */
    scrollToTop(behavior: ScrollBehavior = 'smooth'): void {
        if (this.isScrollDisabled) {
            return;
        }

        // Disable further scrolls temporarily
        this.disableScrollTemporarily(300);

        window.scrollTo({
            top: 0,
            left: 0,
            behavior,
        });
    }

    /**
     * Safe scroll to element with conflict prevention
     */
    scrollToElement(elementId: string, behavior: ScrollBehavior = 'smooth'): void {
        if (this.isScrollDisabled) {
            return;
        }

        const element = document.getElementById(elementId);
        if (element) {
            this.disableScrollTemporarily(300);
            element.scrollIntoView({ behavior, block: 'start' });
        }
    }

    /**
     * Enable scroll (force enable)
     */
    enableScroll(): void {
        this.isScrollDisabled = false;
        if (this.scrollDisableTimeout) {
            clearTimeout(this.scrollDisableTimeout);
        }
    }
}

export const scrollManager = ScrollManager.getInstance();