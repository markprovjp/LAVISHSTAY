# News Module API Documentation

## Overview

This is a complete news management system for Laravel with full CRUD operations, comments, user actions (like, bookmark, rating), and advanced features.

## Features

-   **News Management**: Full CRUD operations for news articles
-   **Categories**: Manage news categories
-   **Comments**: Nested comments with replies
-   **User Actions**: Like, bookmark, and rating system
-   **Search & Filter**: Advanced search and filtering options
-   **SEO Optimized**: Meta tags, canonical URLs, schema markup
-   **Statistics**: View counts, likes, ratings analytics

## Installation

### 1. Run Migrations

```bash
php artisan migrate
```

### 2. Seed Sample Data

```bash
php artisan db:seed --class=NewsModuleSeeder
```

## API Endpoints

### News Articles

#### List News (GET `/api/news`)

```
Parameters:
- per_page: int (default: 9)
- search_title: string (search in title/content)
- category_id: int (filter by category)
- status: int (0=draft, 1=published, default: 1)
- tags: json string (filter by tags)
- author_id: int (filter by author)
- sort_by: string (published_at, views, created_at)
- sort_order: string (asc, desc, default: desc)

Example: /api/news?per_page=10&category_id=1&search_title=hotel
```

#### Get Single News (GET `/api/news/{slug}`)

```
Returns: News article with user actions and statistics
```

#### Create News (POST `/api/news`)

```json
{
    "title": "News Title",
    "slug": "news-title",
    "summary": "Short summary",
    "content": "<p>Full content HTML</p>",
    "tags": ["tag1", "tag2"],
    "thumbnail_id": 1,
    "category_id": 1,
    "meta_title": "SEO Title",
    "meta_description": "SEO Description",
    "status": 1,
    "published_at": "2024-12-01 10:00:00"
}
```

#### Update News (PUT `/api/news/{id}`)

#### Delete News (DELETE `/api/news/{id}`)

#### Get Popular News (GET `/api/news/popular`)

```
Parameters:
- limit: int (default: 10)
- period: string (week, month, year, all)
```

#### Get Related News (GET `/api/news/{slug}/related`)

```
Parameters:
- limit: int (default: 5)
```

### News Categories

#### List Categories (GET `/api/news-categories`)

```
Parameters:
- with_news_count: boolean (include news count)
```

#### Create Category (POST `/api/news-categories`)

```json
{
    "name": "Category Name",
    "slug": "category-slug",
    "description": "Category description"
}
```

#### Get Category (GET `/api/news-categories/{id}`)

#### Update Category (PUT `/api/news-categories/{id}`)

#### Delete Category (DELETE `/api/news-categories/{id}`)

#### Get News by Category (GET `/api/news-categories/{id}/news`)

### Comments

#### List Comments (GET `/api/news/{newsId}/comments`)

```
Parameters:
- per_page: int (default: 10)

Returns: Top-level comments with nested replies
```

#### Create Comment (POST `/api/news/{newsId}/comments`)

```json
{
    "content": "Comment content",
    "parent_id": null // or parent comment ID for replies
}
```

#### Update Comment (PUT `/api/news/{newsId}/comments/{id}`)

#### Delete Comment (DELETE `/api/news/{newsId}/comments/{id}`)

#### Like Comment (POST `/api/news/{newsId}/comments/{id}/like?action=like|unlike`)

### User Actions

#### Get User Actions (GET `/api/news-actions/{newsId}`)

```
Returns:
{
    "is_liked": true,
    "is_bookmarked": false,
    "rating": 4.5,
    "likes_count": 123,
    "bookmarks_count": 45,
    "average_rating": 4.2
}
```

#### Toggle Like (POST `/api/news-actions/{newsId}/like`)

#### Toggle Bookmark (POST `/api/news-actions/{newsId}/bookmark`)

#### Rate News (POST `/api/news-actions/{newsId}/rate`)

```json
{
    "rating": 4.5
}
```

#### Remove Rating (DELETE `/api/news-actions/{newsId}/rate`)

#### Get Statistics (GET `/api/news-actions/{newsId}/stats`)

#### Get User's Liked News (GET `/api/news-actions/user/liked`)

#### Get User's Bookmarked News (GET `/api/news-actions/user/bookmarked`)

## Models & Relationships

### News Model

```php
// Relationships
$news->category()      // BelongsTo NewsCategory
$news->author()        // BelongsTo User
$news->thumbnail()     // BelongsTo MediaFile
$news->comments()      // HasMany NewsComment
$news->userActions()   // HasMany NewsUserAction

// Scopes
$news->published()     // Only published news
$news->byCategory($id) // Filter by category
$news->search($term)   // Search in title/content

// Helper Methods
$news->getLikesCount()
$news->getBookmarksCount()
$news->getAverageRating()
$news->getUserAction($userId)
```

### NewsComment Model

```php
// Relationships
$comment->news()       // BelongsTo News
$comment->user()       // BelongsTo User
$comment->parent()     // BelongsTo NewsComment
$comment->replies()    // HasMany NewsComment
$comment->allReplies() // HasMany with nested replies

// Scopes
$comment->topLevel()   // Only parent comments
$comment->replies()    // Only reply comments
```

### NewsUserAction Model

```php
// Relationships
$action->news()        // BelongsTo News
$action->user()        // BelongsTo User

// Scopes
$action->liked()       // Only liked actions
$action->bookmarked()  // Only bookmarked actions
$action->rated()       // Only rated actions
$action->byUser($id)   // Filter by user
$action->byNews($id)   // Filter by news
```

## Sample Data

The seeders create:

-   6 news categories (Hotel News, Promotions, Travel Guide, Events, Cuisine, Tips)
-   8 sample media files
-   20 news articles with realistic content
-   Comments with replies for each article
-   User actions (likes, bookmarks, ratings) for testing

## Authentication

Most endpoints work without authentication for public access. For write operations (create, update, delete), you may want to add authentication middleware.

## Error Handling

All endpoints return consistent JSON responses:

```json
{
    "success": true|false,
    "message": "Status message",
    "data": {}, // Response data
    "errors": {} // Validation errors (if any)
}
```

## Search Features

-   **Text Search**: Search in title, content, meta_title
-   **Tag Search**: Search by JSON tags array
-   **Category Filter**: Filter by category ID
-   **Author Filter**: Filter by author ID
-   **Date Range**: Filter by publication date
-   **Status Filter**: Published/draft status
-   **Sorting**: By date, views, popularity

## SEO Features

-   Meta title, description, keywords
-   Canonical URLs
-   Schema.org JSON-LD markup
-   Friendly URLs (slugs)
-   Alt text for images

## Performance Considerations

-   Database indexes on commonly searched fields
-   Eager loading for relationships
-   Pagination for large datasets
-   Caching can be added for popular endpoints

## Testing

Use the seeded data to test all endpoints:

```bash
# Test news listing
GET /api/news

# Test single news
GET /api/news/kham-pha-khong-gian-sang-trong-tai-lavishstay-resort

# Test comments
GET /api/news/1/comments
POST /api/news/1/comments

# Test user actions
POST /api/news-actions/1/like
POST /api/news-actions/1/rate
```

## Future Enhancements

-   File upload for thumbnails
-   Advanced analytics dashboard
-   Email notifications for comments
-   Social media sharing
-   Multi-language support
-   Comment moderation system
-   Advanced search with Elasticsearch
-   Real-time notifications
