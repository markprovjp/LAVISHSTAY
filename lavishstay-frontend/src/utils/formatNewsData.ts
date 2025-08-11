import { NewsArticle } from '../services/newsApi';

export const formatNewsData = (news: NewsArticle) => {
    return {
        id: news.id.toString(),
        slug: news.slug,
        title: news.title,
        summary: news.summary || '',
        content: news.content || '',
        imageUrl: news.thumbnail?.filepath || '/images/default-news.jpg',
        category: news.category?.name || 'Khác',
        categorySlug: news.category?.slug || 'other',
        publishedAt: new Date(news.published_at || news.created_at),
        author: {
            name: news.author?.name || 'Admin',
            avatar: news.author?.avatar || null,
        },
        views: news.views || 0,
        tags: news.tags || [],
        isBookmarked: news.is_bookmarked || false,
        isLiked: news.is_liked || false,
        likesCount: news.likes_count || 0,
        rating: news.average_rating || 0,
        userRating: news.user_rating || null,
        metaTitle: news.meta_title,
        metaDescription: news.meta_description,
    };
};
