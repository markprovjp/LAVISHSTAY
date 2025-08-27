// src/utils/normalizeNewsData.ts
import { NewsItem } from '../services/newsApi';

/**
 * Safely checks if a value is a plain object (not array, not null, not primitive)
 */
function isPlainObject(obj: any): obj is Record<string, any> {
    return obj !== null && typeof obj === 'object' && !Array.isArray(obj);
}

/**
 * Safely checks if a value is an array
 */
function isArray(arr: any): arr is any[] {
    return Array.isArray(arr);
}

/**
 * Safely converts a value to a number, with fallback
 */
function toNumber(value: any, fallback: number = 0): number {
    const num = Number(value);
    return isNaN(num) ? fallback : num;
}

/**
 * Safely converts a value to a string, with fallback
 */
function toString(value: any, fallback: string = ''): string {
    if (value === null || value === undefined) return fallback;
    return String(value);
}

/**
 * Safely converts a value to a boolean
 */
function toBoolean(value: any, fallback: boolean = false): boolean {
    if (typeof value === 'boolean') return value;
    if (value === null || value === undefined) return fallback;
    if (typeof value === 'string') {
        return value.toLowerCase() === 'true' || value === '1';
    }
    if (typeof value === 'number') {
        return value !== 0;
    }
    return fallback;
}

/**
 * Normalizes tags data to always return an array of strings
 */
function normalizeTags(tags: any): string[] {
    if (isArray(tags)) {
        return tags.map(tag => {
            if (isPlainObject(tag) && tag.name) {
                return toString(tag.name);
            }
            return toString(tag);
        }).filter(tag => tag.length > 0);
    }

    if (typeof tags === 'string') {
        // Handle comma-separated string tags
        return tags.split(',').map(tag => tag.trim()).filter(tag => tag.length > 0);
    }

    return [];
}

/**
 * Normalizes author data to always return a valid author object
 */
function normalizeAuthor(author: any): { name: string; avatar: string | null; id?: number } {
    if (isPlainObject(author)) {
        return {
            id: toNumber(author.id, undefined),
            name: toString(author.name, 'Admin'),
            avatar: author.avatar_url || author.avatar || null
        };
    }

    return {
        name: 'Admin',
        avatar: null
    };
}

/**
 * Normalizes category data to always return a valid category object
 */
function normalizeCategory(category: any): { name: string; slug: string; id?: number } {
    if (isPlainObject(category)) {
        return {
            id: toNumber(category.id, undefined),
            name: toString(category.name, 'Khác'),
            slug: toString(category.slug, 'other')
        };
    }

    return {
        name: 'Khác',
        slug: 'other'
    };
}

/**
 * Normalizes thumbnail/image data to return a safe image URL
 */
function normalizeImageUrl(news: any): string {
    // Priority: thumbnail.filepath > featured_image > imageUrl > default
    if (isPlainObject(news?.thumbnail) && news.thumbnail.filepath) {
        return toString(news.thumbnail.filepath);
    }

    if (news?.featured_image) {
        return toString(news.featured_image);
    }

    if (news?.imageUrl) {
        return toString(news.imageUrl);
    }

    return '/images/default-news.jpg';
}

/**
 * Normalizes user actions from API response (user_action field)
 */
function normalizeUserActions(userAction: any): {
    is_liked: boolean;
    is_bookmarked: boolean;
    user_rating: number | undefined;
} {
    if (isPlainObject(userAction)) {
        return {
            is_liked: toBoolean(userAction.is_liked, false),
            is_bookmarked: toBoolean(userAction.is_bookmarked, false),
            user_rating: userAction.rating !== null && userAction.rating !== undefined
                ? toNumber(userAction.rating, undefined)
                : undefined
        };
    }

    return {
        is_liked: false,
        is_bookmarked: false,
        user_rating: undefined
    };
}

/**
 * Normalizes stats from API response (stats field)
 */
function normalizeStats(stats: any): {
    likes_count: number;
    comments_count: number;
    bookmarks_count: number;
    views: number;
    average_rating: number;
} {
    if (isPlainObject(stats)) {
        return {
            likes_count: toNumber(stats.likes || stats.likes_count, 0),
            comments_count: toNumber(stats.comments || stats.comments_count, 0),
            bookmarks_count: toNumber(stats.bookmarks || stats.bookmarks_count, 0),
            views: toNumber(stats.views, 0),
            average_rating: toNumber(stats.average_rating, 0)
        };
    }

    return {
        likes_count: 0,
        comments_count: 0,
        bookmarks_count: 0,
        views: 0,
        average_rating: 0
    };
}

/**
 * Normalizes date to always return a valid Date object
 */
function normalizeDate(dateValue: any): Date {
    if (!dateValue) return new Date();

    const date = new Date(dateValue);
    return isNaN(date.getTime()) ? new Date() : date;
}

// src/utils/normalizeNewsData.ts

/**
 * Extended NewsItem type with frontend-friendly fields
 */
export interface NormalizedNewsItem {
    // Core NewsItem fields
    id: number;
    title: string;
    slug: string;
    summary: string;
    content: string;
    featured_image?: string;
    published_at: string;
    created_at: string;
    updated_at: string;
    views: number;
    likes_count: number;
    comments_count: number;
    is_featured: boolean;
    is_liked: boolean;
    is_bookmarked: boolean;
    rating?: number;
    user_rating?: number;
    status: 'published' | 'draft' | 'archived';
    category?: { name: string; slug: string; id?: number };
    tags?: string[];
    author?: { name: string; avatar: string | null; id?: number };

    // Extended fields for frontend convenience
    imageUrl: string;
    categoryName: string;
    categorySlug: string;
    authorName: string;
    authorAvatar: string | null;
    formattedTags: string[];
    publishedDate: Date;
    createdDate: Date;
    updatedDate: Date;
}

/**
 * Main normalization function that ensures all news data is safe to use
 * This function prevents all spreading/iteration errors by guaranteeing data types
 */
export function normalizeNewsItem(newsItem: any): NormalizedNewsItem {
    // Handle null/undefined input
    if (!newsItem || typeof newsItem !== 'object') {
        return createDefaultNewsItem();
    }

    // Normalize user actions and stats từ API response format
    const userActions = normalizeUserActions(newsItem.user_action);
    const stats = normalizeStats(newsItem.stats);

    // Normalize all fields with safe defaults
    const normalized: NormalizedNewsItem = {
        // Core fields
        id: toNumber(newsItem.id, 0),
        title: toString(newsItem.title, 'Tiêu đề không xác định'),
        slug: toString(newsItem.slug, 'undefined-slug'),
        summary: toString(newsItem.summary, 'Không có tóm tắt'),
        content: toString(newsItem.content, '<p>Không có nội dung</p>'),
        featured_image: newsItem.featured_image || undefined,

        // Dates
        published_at: toString(newsItem.published_at, new Date().toISOString()),
        created_at: toString(newsItem.created_at, new Date().toISOString()),
        updated_at: toString(newsItem.updated_at, new Date().toISOString()),

        // Numbers từ stats hoặc direct fields
        views: toNumber(newsItem.views, 0),
        likes_count: stats.likes_count || toNumber(newsItem.likes_count, 0),
        comments_count: stats.comments_count || toNumber(newsItem.comments_count, 0),

        // Booleans từ user_action hoặc direct fields
        is_featured: toBoolean(newsItem.is_featured, false),
        is_liked: userActions.is_liked || toBoolean(newsItem.is_liked, false),
        is_bookmarked: userActions.is_bookmarked || toBoolean(newsItem.is_bookmarked, false),

        // Ratings từ user_action và stats
        rating: stats.average_rating || (newsItem.rating !== null && newsItem.rating !== undefined ? toNumber(newsItem.rating, 0) : undefined),
        user_rating: userActions.user_rating || (newsItem.user_rating !== null && newsItem.user_rating !== undefined ? toNumber(newsItem.user_rating, 0) : undefined),

        // Status
        status: (['published', 'draft', 'archived'].includes(newsItem.status) ? newsItem.status : 'published') as 'published' | 'draft' | 'archived',

        // Relations (normalized)
        category: normalizeCategory(newsItem.category),
        tags: normalizeTags(newsItem.tags),
        author: normalizeAuthor(newsItem.author),

        // Extended fields for frontend convenience
        imageUrl: normalizeImageUrl(newsItem),
        categoryName: normalizeCategory(newsItem.category).name,
        categorySlug: normalizeCategory(newsItem.category).slug,
        authorName: normalizeAuthor(newsItem.author).name,
        authorAvatar: normalizeAuthor(newsItem.author).avatar,
        formattedTags: normalizeTags(newsItem.tags),
        publishedDate: normalizeDate(newsItem.published_at),
        createdDate: normalizeDate(newsItem.created_at),
        updatedDate: normalizeDate(newsItem.updated_at),
    };

    return normalized;
}

/**
 * Creates a default news item for error cases
 */
function createDefaultNewsItem(): NormalizedNewsItem {
    const now = new Date();
    return {
        id: 0,
        title: 'Không thể tải bài viết',
        slug: 'error-loading',
        summary: 'Đã có lỗi xảy ra khi tải bài viết này',
        content: '<p>Không thể tải nội dung bài viết</p>',
        featured_image: undefined,
        published_at: now.toISOString(),
        created_at: now.toISOString(),
        updated_at: now.toISOString(),
        views: 0,
        likes_count: 0,
        comments_count: 0,
        is_featured: false,
        is_liked: false,
        is_bookmarked: false,
        rating: undefined,
        user_rating: undefined,
        status: 'published' as const,
        category: { name: 'Lỗi', slug: 'error' },
        tags: [],
        author: { name: 'System', avatar: null },
        imageUrl: '/images/default-news.jpg',
        categoryName: 'Lỗi',
        categorySlug: 'error',
        authorName: 'System',
        authorAvatar: null,
        formattedTags: [],
        publishedDate: now,
        createdDate: now,
        updatedDate: now,
    };
}

/**
 * Normalizes an array of news items
 * Filters out invalid items and normalizes valid ones
 */
export function normalizeNewsArray(newsArray: any): NormalizedNewsItem[] {
    if (!isArray(newsArray)) {
        return [];
    }

    return newsArray
        .filter(item => item && typeof item === 'object') // Filter out nulls, primitives, etc.
        .map(item => normalizeNewsItem(item));
}

/**
 * Normalizes API response data
 */
export function normalizeNewsResponse(response: any): {
    data: NormalizedNewsItem[];
    pagination?: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        from: number;
        to: number;
    };
} {
    if (!isPlainObject(response)) {
        return { data: [] };
    }

    const normalizedData = normalizeNewsArray(response.data);

    const result: any = { data: normalizedData };

    // Add pagination if available
    if (response.current_page !== undefined) {
        result.pagination = {
            current_page: toNumber(response.current_page, 1),
            last_page: toNumber(response.last_page, 1),
            per_page: toNumber(response.per_page, 10),
            total: toNumber(response.total, 0),
            from: toNumber(response.from, 0),
            to: toNumber(response.to, 0),
        };
    }

    return result;
}

/**
 * Normalizes single news detail response
 */
export function normalizeNewsDetailResponse(response: any): {
    data: NormalizedNewsItem;
} {
    if (!isPlainObject(response) || !response.data) {
        return { data: createDefaultNewsItem() };
    }

    return {
        data: normalizeNewsItem(response.data)
    };
}
