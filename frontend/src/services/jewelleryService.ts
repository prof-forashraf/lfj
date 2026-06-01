import apiClient from '@/lib/apiClient';
import type { PaginatedAffiliateProductsResponse } from '@/services/affiliateProductService';

// --- TYPE DEFINITIONS ---
// These should match your API resources exactly.

export interface AffiliateProduct {
  id: number;
  asin: string;
  name: string;
  affiliate_url: string;
  image_url: string | null;
  notes: string;
  is_featured: boolean;
  // Legacy properties for compatibility
  title?: string;
  description?: string;
  price?: number;
  image?: string;
  amazonUrl?: string;
  isBestSeller?: boolean;
  isFeatured?: boolean;
  isTrending?: boolean;
  isAffordable?: boolean;
  isLuxury?: boolean;
  rating?: number;
}

export interface Category {
  id: number;
  name: string;
  slug: string;
  image?: string;
  description?: string;
  productCount?: number; // This is the crucial field
}
export interface Post {
  id: number;
  title: string;
  slug: string;
  excerpt: string;
  featured_image_url: string | null;
  published_at_formatted: string;
  content_html: string;
  categories?: Category[];
}

export interface PaginatedPostsResponse {
  data: Post[];
  meta: {
    current_page: number;
    last_page: number;
    // ... add other meta fields if you need them for pagination
  };
}
// --- SERVICE FUNCTIONS ---

export const jewelleryService = {
  /**
   * Fetches the list of all categories WITH their live product counts.
   * This function calls the /api/categories endpoint you fixed in Laravel.
   */
  getCategories: async (): Promise<Category[]> => {
    try {
      const response = await apiClient.get<{ data: Category[] }>('/categories');
      // The Laravel controller wraps the result in a "data" key. We unwrap it here.
      return response.data.data;
    } catch (error) {
      console.error("Error fetching categories:", error);
      return []; // Return an empty array on error to prevent crashes
    }
  },
    getPostsByCategory: async (slug: string): Promise<PaginatedPostsResponse> => {
    if (!slug) throw new Error("Category slug is required.");
    try {
      const response = await apiClient.get<PaginatedPostsResponse>(`/posts?category=${slug}`);
      return response.data;
    } catch (error) {
      console.error(`Error fetching posts for category '${slug}':`, error);
      throw error;
    }
  },  

  getProductsByCategory: async (slug: string): Promise<AffiliateProduct[]> => {
    if (!slug) return []; // Return empty if no slug is provided
    try {
      const response = await apiClient.get<{ data: AffiliateProduct[] }>(`/categories/${slug}/products`);
      return response.data.data; // Unwrap the paginated data
    } catch (error) {
      console.error(`Error fetching products for category '${slug}':`, error);
      return [];
    }
  },
  /**
   * Fetches products for a specific collection (e.g., 'featured').
   * This calls the /api/products/featured endpoint.
   */
  getProductsByCollection: async (collectionType: string): Promise<AffiliateProduct[]> => {
    try {
      const normalized = collectionType?.toLowerCase();

      if (normalized === 'featured') {
        const response = await apiClient.get<{ data: AffiliateProduct[] }>(`/products/featured?limit=20`);
        const featuredProducts = response?.data?.data || [];

        if (featuredProducts.length > 0) {
          return featuredProducts;
        }

        console.warn("Featured collection is empty. Falling back to latest arrivals.");
        const fallback = await apiClient.get<PaginatedAffiliateProductsResponse>(`/products`, {
          params: {
            per_page: 20,
            sort_by: 'created_at',
            sort_order: 'desc',
          },
        });
        return fallback.data.data || [];
      }

      let response;

      if (normalized === 'random') {
        response = await apiClient.get<{ data: AffiliateProduct[] }>(`/products/random?limit=20`);
      } else if (normalized === 'studio') {
        response = await apiClient.get<{ data: AffiliateProduct[] }>(`/products/studio?limit=20&collection=1`);
      } else if (normalized === 'new-arrivals') {
        const paginated = await apiClient.get<PaginatedAffiliateProductsResponse>(`/products`, {
          params: {
            per_page: 20,
            sort_by: 'created_at',
            sort_order: 'desc',
          },
        });
        return paginated.data.data || [];
      } else {
        response = await apiClient.get<{ data: AffiliateProduct[] }>(`/products/featured?limit=20`);
      }

      return response?.data?.data || [];
    } catch (error) {
      console.error(`Error fetching collection '${collectionType}':`, error);
      return []; // Return an empty array on error
    }
  },
};