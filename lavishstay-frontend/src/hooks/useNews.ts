// src/hooks/useNews.ts

import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { newsApi, ApiParams } from '../services/newsApi';
import { message } from 'antd';

// News list
export const useNewsList = (params: ApiParams = {}) => {
    return useQuery({
        queryKey: ['newsList', params],
        queryFn: () => newsApi.getNewsList(params),
        staleTime: 5 * 60 * 1000,
        gcTime: 10 * 60 * 1000,
    });
};

// Featured news (tin nổi bật banner)
export const useFeaturedNews = (params: ApiParams = {}) => {
    // Đảm bảo is_featured là number
    const fixedParams = { ...params, is_featured: params.is_featured === true ? 1 : params.is_featured === false ? 0 : params.is_featured };
    return useQuery({
        queryKey: ['featuredNews', fixedParams],
        queryFn: () => newsApi.getFeaturedNews(fixedParams),
        staleTime: 10 * 60 * 1000,
        gcTime: 30 * 60 * 1000,
    });
};

// Trending news (tin thịnh hành)
export const useTrendingNews = (params: ApiParams = {}) => {
    return useQuery({
        queryKey: ['trendingNews', params],
        queryFn: () => newsApi.getTrendingNews(params),
        staleTime: 5 * 60 * 1000,
        gcTime: 15 * 60 * 1000,
    });
};

// News by category
export const useNewsByCategory = (categoryId: number, params: ApiParams = {}) => {
    return useQuery({
        queryKey: ['newsByCategory', categoryId, params],
        queryFn: () => newsApi.getNewsByCategory(categoryId, params),
        enabled: !!categoryId,
        staleTime: 5 * 60 * 1000,
        gcTime: 10 * 60 * 1000,
    });
};

// Related news
export const useRelatedNews = (newsId: number, limit = 5) => {
    return useQuery({
        queryKey: ['relatedNews', newsId, limit],
        queryFn: () => newsApi.getRelatedNews(newsId, limit),
        enabled: !!newsId,
        staleTime: 15 * 60 * 1000,
        gcTime: 30 * 60 * 1000,
    });
};

// News detail
export const useNewsDetail = (slug: string) => {
    return useQuery({
        queryKey: ['newsDetail', slug],
        queryFn: () => newsApi.getNewsDetail(slug),
        enabled: !!slug,
        staleTime: 10 * 60 * 1000,
        gcTime: 30 * 60 * 1000,
    });
};

// News categories
export const useNewsCategories = () => {
    return useQuery({
        queryKey: ['newsCategories'],
        queryFn: () => newsApi.getCategories(),
        staleTime: 15 * 60 * 1000,
        gcTime: 30 * 60 * 1000,
    });
};

// Comments for a news article
export const useComments = (newsId: number, params?: { page?: number; per_page?: number }) => {
    return useQuery({
        queryKey: ['comments', newsId, params],
        queryFn: () => newsApi.getComments(newsId, params),
        enabled: !!newsId,
        staleTime: 2 * 60 * 1000,
        gcTime: 5 * 60 * 1000,
    });
};

// Create comment
export const useCreateComment = (newsId: number) => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: (data: { content: string; parent_id?: number | null }) => newsApi.createComment(newsId, data),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['comments', newsId] });
            queryClient.invalidateQueries({ queryKey: ['newsDetail'] });
            message.success('Bình luận thành công!');
        },
        onError: () => {
            message.error('Không thể gửi bình luận');
        },
    });
};

// Toggle like on comment
export const useToggleCommentLike = () => {
    const queryClient = useQueryClient();
    return useMutation({
        // accept object {newsId, commentId} to call nested endpoint
        mutationFn: (args: { newsId: number; commentId: number }) => {
            const { newsId, commentId } = args as { newsId: number; commentId: number };
            if (!newsId) throw new Error('Missing newsId');
            return newsApi.toggleCommentLike(newsId, commentId);
        },
        // Optimistic update: update specific comment in cache
        onMutate: async (variables) => {
            const { newsId, commentId } = variables as { newsId: number; commentId: number };
            await queryClient.cancelQueries({ queryKey: ['comments', newsId] });

            const previous = queryClient.getQueryData<any>(['comments', newsId]);

            // Update cache optimistically for all queries matching ['comments', newsId]
            queryClient.setQueriesData({ queryKey: ['comments', newsId] }, (old: any) => {
                if (!old) return old;
                // old may be the paginated shape or array; normalize to object with data
                const snapshot = old?.data ? { ...old } : { data: Array.isArray(old) ? old : [] };
                if (!snapshot.data) return old;

                const newData = { ...snapshot };
                newData.data = newData.data.map((c: any) => {
                    if (c.id === commentId) {
                        const likes = (c.likes ?? c.likes_count ?? 0) + 1;
                        return { ...c, likes, likes_count: likes, is_liked: true };
                    }
                    // update replies if present
                    if (c.replies && Array.isArray(c.replies)) {
                        c.replies = c.replies.map((r: any) => {
                            if (r.id === commentId) {
                                const likes = (r.likes ?? r.likes_count ?? 0) + 1;
                                return { ...r, likes, likes_count: likes, is_liked: true };
                            }
                            return r;
                        });
                    }
                    return c;
                });

                // return same shape as original
                if (old?.data) {
                    return { ...old, data: newData.data };
                }
                return newData.data;
            });

            return { previous };
        },
        onError: (_err, variables, context: any) => {
            const newsId = (variables as { newsId: number }).newsId;
            if (context?.previous) {
                queryClient.setQueryData(['comments', newsId], context.previous);
            }
            message.error('Không thể thích bình luận');
        },
        onSuccess: (_data, variables) => {
            const newsId = (variables as { newsId: number }).newsId;
            // ensure final state by invalidating specific comments query
            queryClient.invalidateQueries({ queryKey: ['comments', newsId] });
        },
    });
};

// Toggle like on news
export const useToggleLike = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: (newsId: number) => newsApi.toggleLike(newsId),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['newsList'] });
            queryClient.invalidateQueries({ queryKey: ['newsDetail'] });
        },
        onError: () => {
            message.error('Không thể thích bài viết');
        },
    });
};

// Toggle bookmark on news
export const useToggleBookmark = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: (newsId: number) => newsApi.toggleBookmark(newsId),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['newsList'] });
            queryClient.invalidateQueries({ queryKey: ['newsDetail'] });
        },
        onError: () => {
            message.error('Không thể lưu bài viết');
        },
    });
};

// Rate news
export const useRateNews = () => {
    const queryClient = useQueryClient();
    return useMutation({
        mutationFn: ({ newsId, rating }: { newsId: number; rating: number }) => newsApi.rateNews(newsId, rating),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['newsDetail'] });
            message.success('Đánh giá thành công!');
        },
        onError: () => {
            message.error('Không thể gửi đánh giá');
        },
    });
};
