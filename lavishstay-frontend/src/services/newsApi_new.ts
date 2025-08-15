// src/services/newsApi.ts
import { ApiService } from './apiService';

// TypeScript interfaces
export interface ApiParams {
    page?: number;
    per_page?: number;
    category_id?: number;
    search_title?: string;
    search_content?: string;
    sort_by?: 'published_at' | 'views' | 'created_at' | 'title';
    sort_order?: 'asc' | 'desc';
    published_from?: string;
    published_to?: string;
    author_id?: number;
    is_featured?: boolean;
    status?: 'published' | 'draft' | 'archived';
}

export interface NewsCategory {
    id: number;
    name: string;
    slug: string;
    description?: string;
    news_count?: number;
}

export interface NewsTag {
    id: number;
    name: string;
    slug: string;
}

export interface NewsAuthor {
    id: number;
    name: string;
    email?: string;
    avatar_url?: string;
    bio?: string;
}

export interface NewsItem {
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
    category?: NewsCategory;
    tags?: NewsTag[];
    author?: NewsAuthor;
}

export interface NewsListResponse {
    data: NewsItem[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
}

export interface NewsDetailResponse {
    data: NewsItem;
}

export interface Comment {
    id: number;
    content: string;
    created_at: string;
    updated_at: string;
    likes_count: number;
    is_liked: boolean;
    user?: NewsAuthor;
    replies?: Comment[];
}

export interface CommentListResponse {
    data: Comment[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
}

// News API functions
export const newsApi = {
    // Lấy danh sách tin tức
    async getNewsList(params?: ApiParams): Promise<NewsListResponse> {
        const query = params ? '?' + new URLSearchParams(params as any).toString() : '';
        return await ApiService.get<NewsListResponse>(`news${query}`);
    },

    // Lấy chi tiết tin tức theo slug
    async getNewsDetail(slug: string): Promise<NewsDetailResponse> {
        return await ApiService.get<NewsDetailResponse>(`news/${slug}`);
    },

    // Lấy danh mục tin tức
    async getCategories(): Promise<{ data: NewsCategory[] }> {
        return await ApiService.get<{ data: NewsCategory[] }>('news-categories');
    },

    // Lấy bình luận cho bài viết
    async getComments(newsId: number, params?: { page?: number; per_page?: number }): Promise<CommentListResponse> {
        const query = params ? '?' + new URLSearchParams(params as any).toString() : '';
        return await ApiService.get<CommentListResponse>(`news/${newsId}/comments${query}`);
    },

    // Tạo bình luận
    async createComment(newsId: number, data: { content: string; parent_id?: number | null }): Promise<Comment> {
        const res = await ApiService.post<{ data: Comment }>(`news/${newsId}/comments`, data);
        return res.data;
    },

    // Like bài viết
    async toggleLike(newsId: number): Promise<{ is_liked: boolean; likes_count: number }> {
        const res = await ApiService.post<{ data: { is_liked: boolean; likes_count: number } }>(`news/${newsId}/toggle-like`, {});
        return res.data;
    },

    // Bookmark bài viết
    async toggleBookmark(newsId: number): Promise<{ is_bookmarked: boolean }> {
        const res = await ApiService.post<{ data: { is_bookmarked: boolean } }>(`news/${newsId}/toggle-bookmark`, {});
        return res.data;
    },

    // Đánh giá bài viết
    async rateNews(newsId: number, rating: number): Promise<{ rating: number; user_rating: number }> {
        const res = await ApiService.post<{ data: { rating: number; user_rating: number } }>(`news/${newsId}/rate`, { rating });
        return res.data;
    },

    // Like bình luận
    async toggleCommentLike(commentId: number): Promise<{ is_liked: boolean; likes_count: number }> {
        const res = await ApiService.post<{ data: { is_liked: boolean; likes_count: number } }>(`news-comments/${commentId}/toggle-like`, {});
        return res.data;
    },

    // Get news by category
    async getNewsByCategory(categoryId: number, params?: ApiParams): Promise<NewsListResponse> {
        const queryObj = { ...params, category_id: categoryId };
        const query = '?' + new URLSearchParams(queryObj as any).toString();
        return await ApiService.get<NewsListResponse>(`news${query}`);
    },

    // Get featured news
    async getFeaturedNews(params?: ApiParams): Promise<NewsListResponse> {
        const query = params ? '?' + new URLSearchParams(params as any).toString() : '';
        return await ApiService.get<NewsListResponse>(`news/featured${query}`);
    },

    // Search news
    async searchNews(query: string, params?: ApiParams): Promise<NewsListResponse> {
        const queryObj = { ...params, q: query };
        const queryStr = '?' + new URLSearchParams(queryObj as any).toString();
        return await ApiService.get<NewsListResponse>(`news/search${queryStr}`);
    },

    // Get related news
    async getRelatedNews(newsId: number, limit = 5): Promise<{ data: NewsItem[] }> {
        const query = '?limit=' + limit;
        return await ApiService.get<{ data: NewsItem[] }>(`news/${newsId}/related${query}`);
    },

    // Get trending news
    async getTrendingNews(params?: ApiParams): Promise<NewsListResponse> {
        const query = params ? '?' + new URLSearchParams(params as any).toString() : '';
        return await ApiService.get<NewsListResponse>(`news/trending${query}`);
    },

    // Search by tags
    async searchByTags(tags: string[], params?: ApiParams): Promise<NewsListResponse> {
        const queryObj = { ...params, tags: tags.join(',') };
        const query = '?' + new URLSearchParams(queryObj as any).toString();
        return await ApiService.get<NewsListResponse>(`news/search-by-tags${query}`);
    }
};

export default newsApi;
