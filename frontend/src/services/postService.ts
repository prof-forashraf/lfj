// src/services/postService.ts
import { api } from '@/lib/apiClient';
import { API_ENDPOINTS } from '@/lib/constants';
import type { PaginatedResponse } from '@/types';

export interface Author {
  id: number;
  name: string;
}

export interface Category {
  id: number;
  name: string;
  slug: string;
  posts_count?: number;
}

export interface Tag {
  id: number;
  name: string;
  slug: string;
}

export interface SeoData {
  title: string;
  meta_description: string;
  keywords?: string | null;
  canonical?: string | null;
  robots?: string;
  og?: {
    title?: string;
    description?: string;
    image?: string;
    type?: string;
    url?: string;
  };
  twitter?: {
    card?: string;
    title?: string;
    description?: string;
    image?: string;
  };
  schema?: unknown[];
  reading_time?: number | null;
  published_at?: string | null;
  modified_at?: string | null;
  site_name?: string;
  twitter_handle?: string;
  theme_color?: string;
}

export interface Post {
  id: number;
  title: string;
  slug: string;
  content_html: string;
  excerpt: string | null;
  featured_image_url: string | null;
  status: string;
  published_at_iso: string | null;
  published_at_formatted: string;
  author: Author | null;
  categories: Category[];
  tags: Tag[];
  seo: SeoData;
  created_at_human: string;
  updated_at_iso: string;
}

export interface GetPostsParams {
  page?: number;
  perPage?: number;
  category?: string;
  tag?: string;
  categorySlug?: string;
  exclude?: string | number;
  limit?: number;
  search?: string;
  status?: string;
  author?: number;
}

export type PaginatedPostsResponse = PaginatedResponse<Post>;

// API functions
export const getPosts = async (params: GetPostsParams = {}): Promise<PaginatedPostsResponse> => {
  const queryParams = new URLSearchParams();

  Object.entries(params).forEach(([key, value]) => {
    if (value !== undefined && value !== null && value !== '') {
      queryParams.append(key, String(value));
    }
  });

  const url = `${API_ENDPOINTS.POSTS}?${queryParams.toString()}`;
  return api.get<PaginatedPostsResponse>(url);
};

export const getPostBySlug = async (slug: string): Promise<Post> => {
  if (!slug?.trim()) {
    throw new Error('Post slug is required');
  }

  return api.get<Post>(API_ENDPOINTS.POSTS_BY_SLUG(slug));
};

export const createPost = async (data: Partial<Post>): Promise<Post> => {
  return api.post<Post>(API_ENDPOINTS.POSTS, data);
};

export const updatePost = async (id: number, data: Partial<Post>): Promise<Post> => {
  return api.put<Post>(`${API_ENDPOINTS.POSTS}/${id}`, data);
};

export const deletePost = async (id: number): Promise<void> => {
  return api.delete<void>(`${API_ENDPOINTS.POSTS}/${id}`);
};
