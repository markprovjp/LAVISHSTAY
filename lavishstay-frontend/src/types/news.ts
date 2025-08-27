// export interface News {
//   id: number;
//   slug: string;
//   title: string;
//   meta_description: string | null;
//   meta_keywords: string | null;
//   content: string;
//   thumbnail: { url: string; alt_text: string } | null;
//   category: { id: number; name: string; slug: string } | null;
//   publish_date: string | null;
//   views: number;
//   meta_title?: string | null;
//   canonical_url?: string | null;
//   schema_json?: any | null;
// }
export interface News {
  id: number;
  slug: string;
  title: string;
  meta_description: string | null;
  meta_keywords: string | null;
  content: string;
  thumbnail: {
    filepath: string;
    alt_text: string;
  } | null;
  category: {
    id: number;
    name: string;
    slug: string;
  } | null;
  publish_date: string | null;
  views: number;
  meta_title?: string | null;
  canonical_url?: string | null;
  schema_json?: any | null;
}

export interface NewsCategory {
  id: number;
  name: string;
  slug: string;
}

export interface PaginatedResponse<T> {
  data: T[];
  current_page: number;
  last_page: number;
  total: number;
}

// Additional types for enhanced functionality
export interface NewsStatistics {
  total_articles: number;
  total_views: number;
  today_articles: number;
  today_views: number;
  categories_count: number;
  authors_count: number;
}

export interface TrendingNewsItem extends Pick<News, 'id' | 'title' | 'slug' | 'views' | 'publish_date' | 'thumbnail'> {
  rank?: number;
}

export interface NewsAuthor {
  id: number;
  name: string;
  email?: string;
  avatar_url?: string;
  bio?: string;
}

// Enhanced NewsItem with more fields for compatibility
export interface EnhancedNews extends News {
  featured_image?: string;
  image?: string;
  image_url?: string;
  summary?: string;
  excerpt?: string;
  published_at?: string;
  created_at?: string;
  updated_at?: string;
  likes_count?: number;
  comments_count?: number;
  is_featured?: boolean;
  is_liked?: boolean;
  is_bookmarked?: boolean;
  rating?: number;
  user_rating?: number;
  status?: 'published' | 'draft' | 'archived';
  tags?: Array<{ id: number; name: string; slug: string }>;
  author?: NewsAuthor;
}