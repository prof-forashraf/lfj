// src/services/categoryService.ts
import apiClient from '@/lib/apiClient';

// Define or Import the Category Type from a shared types file or postService.ts
// Ensure this matches your Laravel CategoryResource output exactly
export interface Category {
  id: number;
  image:string;
  name: string;
  slug: string;
  description?: string | null;
  is_featured?: boolean; // Ensure this is in your CategoryResource if you use it
  posts_count?: number;  // From withCount in Laravel API
  seo?: {
    title?: string | null;
    meta_description?: string | null;
  };
  // parent?: Category | null; // If you have parent/child categories
}
// For the index /api/categories endpoint (list of categories)
export interface PaginatedCategoriesResponse {
  data: Category[];
  links: {
    first: string | null;
    last: string | null;
    prev: string | null;
    next: string | null;
  };
  meta: {
    current_page: number;
    from: number | null;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
    path: string;
    per_page: number;
    to: number | null;
    total: number;
  };
}

// For the show /api/categories/{slug} endpoint (single category details)
// Laravel API Resource for a single item typically wraps it in a 'data' key.
interface SingleCategoryResponse {
  data: Category;
}

/**
 * Fetches a paginated list of all categories.
 */
export const getCategories = async (page: number = 1, perPage: number = 100): Promise<PaginatedCategoriesResponse> => {
  const response = await apiClient.get<PaginatedCategoriesResponse>(`/categories?page=${page}&per_page=${perPage}`);
  return response.data;
};

/**
 * Fetches a single category's details by its slug.
 */
export const getCategoryBySlug = async (slug: string): Promise<Category> => {
  const response = await apiClient.get<SingleCategoryResponse>(`/categories/${slug}`);
  if (!response.data || !response.data.data) {
    throw new Error("Category data structure from API is not as expected.");
  }
  return response.data.data;
};
