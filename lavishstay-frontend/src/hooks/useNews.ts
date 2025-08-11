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
        mutationFn: (commentId: number) => newsApi.toggleCommentLike(commentId),
        onSuccess: () => {
            queryClient.invalidateQueries({ queryKey: ['comments'] });
        },
        onError: () => {
            message.error('Không thể thích bình luận');
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
