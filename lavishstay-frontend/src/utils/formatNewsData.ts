
import { NewsItem } from '../services/newsApi';

export const formatNewsData = (news: NewsItem) => {
    // Fallback for image: thumbnail.filepath (API), featured_image (interface), or default
    let imageUrl = '/images/default-news.jpg';
    if (news && typeof news === 'object' && (news as any).thumbnail && typeof (news as any).thumbnail === 'object' && (news as any).thumbnail.filepath) {
        imageUrl = (news as any).thumbnail.filepath;
    } else if (news && typeof news === 'object' && news.featured_image) {
        imageUrl = news.featured_image;
    }

    // Fallback for author avatar: avatar_url (API), or null
    let authorAvatar = (news && typeof news === 'object' && news.author && typeof news.author === 'object' && news.author.avatar_url) ? news.author.avatar_url : null;

    // Fallback for meta fields and average_rating
    const metaTitle = (news && typeof news === 'object' && (news as any).meta_title) ? (news as any).meta_title : '';
    const metaDescription = (news && typeof news === 'object' && (news as any).meta_description) ? (news as any).meta_description : '';
    const averageRating = (news && typeof news === 'object' && (news as any).average_rating !== undefined) ? (news as any).average_rating : (news && typeof news === 'object' && news.rating !== undefined ? news.rating : 0);

    // Ensure tags is always array
    const tags = (news && typeof news === 'object' && Array.isArray(news.tags)) ? news.tags : [];
    // Ensure author is always object
    const author = (news && typeof news === 'object' && news.author && typeof news.author === 'object')
        ? { name: news.author.name || 'Admin', avatar: authorAvatar }
        : { name: 'Admin', avatar: null };
    // Ensure category is always object
    const category = (news && typeof news === 'object' && news.category && typeof news.category === 'object')
        ? news.category
        : { name: 'Khác', slug: 'other' };

    return {
        id: news && typeof news.id !== 'undefined' && news.id !== null && news.id.toString ? news.id.toString() : '',
        slug: news && typeof news.slug === 'string' ? news.slug : '',
        title: news && typeof news.title === 'string' ? news.title : '',
        summary: news && typeof news.summary === 'string' ? news.summary : '',
        content: news && typeof news.content === 'string' ? news.content : '',
        imageUrl,
        category: category.name,
        categorySlug: category.slug,
        publishedAt: new Date((news && typeof news.published_at === 'string') ? news.published_at : (news && typeof news.created_at === 'string' ? news.created_at : Date.now())),
        author,
        views: news && typeof news.views === 'number' ? news.views : 0,
        tags,
        isBookmarked: news && typeof news.is_bookmarked === 'boolean' ? news.is_bookmarked : false,
        isLiked: news && typeof news.is_liked === 'boolean' ? news.is_liked : false,
        likesCount: news && typeof (news as any).likes_count === 'number' ? (news as any).likes_count : 0,
        rating: typeof averageRating === 'number' ? averageRating : 0,
        userRating: news && typeof news.user_rating === 'number' ? news.user_rating : null,
        metaTitle,
        metaDescription,
    };
};
