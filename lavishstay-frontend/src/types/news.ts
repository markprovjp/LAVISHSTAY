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