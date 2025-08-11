// src/services/newsApi.ts
import axios from 'axios';

// API base URL - adjust according to your backend
const API_BASE_URL = 'http://localhost:8888';

// Create axios instance
const apiClient = axios.create({
    baseURL: API_BASE_URL,
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

// Request interceptor to add auth token
apiClient.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem('auth_token');
        if (token) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Response interceptor for error handling
apiClient.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            localStorage.removeItem('auth_token');
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

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
    // Get news list
    async getNewsList(params?: ApiParams): Promise<NewsListResponse> {
        const response = await apiClient.get<NewsListResponse>('/api/news', { params });
        return response.data;
    },

    // Get news detail by slug
    async getNewsDetail(slug: string): Promise<NewsDetailResponse> {
        const response = await apiClient.get<NewsDetailResponse>(`/api/news/${slug}`);
        return response.data;
    },

    // Get news categories
    async getCategories(): Promise<{ data: NewsCategory[] }> {
        const response = await apiClient.get<{ data: NewsCategory[] }>('/api/news-categories');
        return response.data;
    },

    // Get comments for a news article
    async getComments(newsId: number, params?: { page?: number; per_page?: number }): Promise<CommentListResponse> {
        const response = await apiClient.get<CommentListResponse>(`/api/news/${newsId}/comments`, { params });
        return response.data;
    },

    // Create a comment
    async createComment(newsId: number, data: { content: string; parent_id?: number | null }): Promise<Comment> {
        const response = await apiClient.post<{ data: Comment }>(`/api/news/${newsId}/comments`, data);
        return response.data.data;
    },

    // Toggle like on news
    async toggleLike(newsId: number): Promise<{ is_liked: boolean; likes_count: number }> {
        const response = await apiClient.post<{ data: { is_liked: boolean; likes_count: number } }>(`/api/news/${newsId}/toggle-like`);
        return response.data.data;
    },

    // Toggle bookmark on news
    async toggleBookmark(newsId: number): Promise<{ is_bookmarked: boolean }> {
        const response = await apiClient.post<{ data: { is_bookmarked: boolean } }>(`/api/news/${newsId}/toggle-bookmark`);
        return response.data.data;
    },

    // Rate news article
    async rateNews(newsId: number, rating: number): Promise<{ rating: number; user_rating: number }> {
        const response = await apiClient.post<{ data: { rating: number; user_rating: number } }>(`/api/news/${newsId}/rate`, { rating });
        return response.data.data;
    },

    // Toggle like on comment
    async toggleCommentLike(commentId: number): Promise<{ is_liked: boolean; likes_count: number }> {
        const response = await apiClient.post<{ data: { is_liked: boolean; likes_count: number } }>(`/api/news-comments/${commentId}/toggle-like`);
        return response.data.data;
    },

    // Get news by category
    async getNewsByCategory(categoryId: number, params?: ApiParams): Promise<NewsListResponse> {
        const response = await apiClient.get<NewsListResponse>('/api/news', {
            params: { ...params, category_id: categoryId }
        });
        return response.data;
    },

    // Get featured news
    async getFeaturedNews(params?: ApiParams): Promise<NewsListResponse> {
        const response = await apiClient.get<NewsListResponse>('/api/news/featured', { params });
        return response.data;
    },

    // Search news
    async searchNews(query: string, params?: ApiParams): Promise<NewsListResponse> {
        const response = await apiClient.get<NewsListResponse>('/api/news/search', {
            params: { ...params, q: query }
        });
        return response.data;
    },

    // Get related news
    async getRelatedNews(newsId: number, limit = 5): Promise<{ data: NewsItem[] }> {
        const response = await apiClient.get<{ data: NewsItem[] }>(`/api/news/${newsId}/related`, {
            params: { limit }
        });
        return response.data;
    },

    // Get trending news
    async getTrendingNews(params?: ApiParams): Promise<NewsListResponse> {
        const response = await apiClient.get<NewsListResponse>('/api/news/trending', { params });
        return response.data;
    },

    // Search by tags
    async searchByTags(tags: string[], params?: ApiParams): Promise<NewsListResponse> {
        const response = await apiClient.get<NewsListResponse>('/api/news/search-by-tags', {
            params: {
                ...params,
                tags: tags.join(',')
            }
        });
        return response.data;
    }
};
