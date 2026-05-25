import apiClient from "@/lib/apiClient";

// --- Reusable TypeScript Interfaces ---

export interface PaginationLinks {
  first: string | null;
  last: string | null;
  prev: string | null;
  next: string | null;
}

export interface PaginationMeta {
  current_page: number;
  from?: number | null;
  last_page: number;
  links?: { url: string | null; label: string; active: boolean }[];
  path?: string;
  per_page?: number;
  to?: number | null;
  total: number;
}

export interface AffiliateProduct {
  id: number;
  asin: string;
  name: string;
  affiliate_url: string | null;
  image_url: string | null;
  image_urls: {
    small: string | null;
    medium: string | null;
    large: string | null;
  } | null;
  try_on_image_url: string | null;
  notes: string | null;
  item_type: string | null;
  status: string;
  is_featured: boolean;
  rating?: number;
  category?: { id: number; name: string; slug: string };
}

export interface PaginatedAffiliateProductsResponse {
  data: AffiliateProduct[];
  links?: PaginationLinks;
  meta: PaginationMeta;
}

interface SingleAffiliateProductResponse {
  data: AffiliateProduct;
}

export interface GetAffiliateProductsParams {
  page?: number;
  perPage?: number;
  featured?: boolean;
  category?: number | string;
}

// --- API Service Functions as an Object ---

export const affiliateProductService = {
  /**
   * Fetches all affiliate products (paginated).
   */
  getAffiliateProducts: async (
    params: GetAffiliateProductsParams = {}
  ): Promise<PaginatedAffiliateProductsResponse> => {
    try {
      const response = await apiClient.get<PaginatedAffiliateProductsResponse>(
        "/products",
        {
          params: { per_page: 12, ...params },
        }
      );
      return response.data;
    } catch (error) {
      console.error("AFFILIATE SERVICE: Error fetching affiliate products.", error);
      throw error;
    }
  },

  /**
   * Fetches featured affiliate products.
   */
  getFeaturedAffiliateProducts: async (
    limit: number = 4
  ): Promise<AffiliateProduct[]> => {
    try {
      const response = await apiClient.get<{ data: AffiliateProduct[] }>(
        `/products/featured?limit=${limit}`
      );
      return response.data.data || [];
    } catch (error) {
      console.error("AFFILIATE SERVICE: Error fetching featured products.", error);
      throw error;
    }
  },

  /**
   * Fetches random affiliate products.
   */
  getRandomAffiliateProducts: async (
    limit: number = 4
  ): Promise<AffiliateProduct[]> => {
    try {
      const response = await apiClient.get<{ data: AffiliateProduct[] }>(
        `/products/random?limit=${limit}`
      );
      return response.data.data || [];
    } catch (error) {
      console.error("AFFILIATE SERVICE: Error fetching random products.", error);
      throw error;
    }
  },

  /**
   * Fetches products by category slug.
   */
  getProductsByCategory: async (
    slug: string,
    page: number = 1,
    perPage: number = 12
  ): Promise<PaginatedAffiliateProductsResponse> => {
    try {
      const response = await apiClient.get<PaginatedAffiliateProductsResponse>(
        `/categories/${slug}/products`,
        { params: { page, per_page: perPage } }
      );
      return response.data;
    } catch (error) {
      console.error(`AFFILIATE SERVICE: Error fetching products for category ${slug}.`, error);
      throw error;
    }
  },

  /**
   * Fetches a single affiliate product by ASIN.
   */
  getAffiliateProductByAsin: async (
    asin: string
  ): Promise<AffiliateProduct> => {
    try {
      const response = await apiClient.get<SingleAffiliateProductResponse>(
        `/products/${asin}`
      );
      return response.data.data;
    } catch (error) {
      console.error(`AFFILIATE SERVICE: Error fetching product ${asin}.`, error);
      throw error;
    }
  },

  /**
   * Searches affiliate products by query string.
   */
  searchProducts: async (
    query: string,
    page: number = 1
  ): Promise<PaginatedAffiliateProductsResponse> => {
    if (!query) {
      return {
        data: [],
        meta: { current_page: 1, last_page: 1, total: 0 },
      };
    }

    try {
      const response = await apiClient.get<PaginatedAffiliateProductsResponse>(
        "/products/search",
        { params: { query, page } }
      );
      return response.data;
    } catch (error) {
      console.error(`Error searching for products with query '${query}':`, error);
      throw error;
    }
  },
};
