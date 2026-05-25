// src/hooks/useCategories.ts
import { useQuery, UseQueryResult, UseQueryOptions } from '@tanstack/react-query';
import {
  getCategories,               // Service function for list of categories
  getCategoryBySlug,           // Service function for single category details
  PaginatedCategoriesResponse, // Type for paginated list
  Category                     // Type for a single category object
} from '@/services/categoryService';   // Adjust path if your service file is elsewhere


// src/hooks/useCategories.ts
// ...
export interface UseCategoriesParams { // <-- Make sure this is exported
  page?: number;
  perPage?: number;
}


// Interface for parameters when calling useCategories (for fetching a list)
export interface UseCategoriesParams {
  page?: number;
  perPage?: number;
  // Add other potential filter params here if your API supports them
  // e.g., sortBy?: string;
}

/**
 * Custom hook to fetch a paginated list of categories.
 * @param params - Parameters for pagination (page, perPage).
 * @param options - Optional React Query options.
 */
export const useCategories = (
  params: UseCategoriesParams = { page: 1, perPage: 100 }, // Default to fetching many for filters/lists
  options?: Omit<UseQueryOptions<PaginatedCategoriesResponse, Error, PaginatedCategoriesResponse, readonly (string | UseCategoriesParams)[]>, 'queryKey' | 'queryFn'>
): UseQueryResult<PaginatedCategoriesResponse, Error> => {
  return useQuery<PaginatedCategoriesResponse, Error, PaginatedCategoriesResponse, readonly (string | UseCategoriesParams)[]>({
    queryKey: ['categories', params] as const, // Query key includes params for unique caching
    queryFn: () => getCategories(params.page, params.perPage),
    // staleTime: 1000 * 60 * 5, // Example: Categories might not change very often
    ...options, // Allow overriding default query options
  });
};

/**
 * Custom hook to fetch details of a single category by its slug.
 * @param slug - The slug of the category to fetch. Can be undefined initially.
 * @param options - Optional React Query options.
 */
export const useCategory = (
  slug: string | undefined,
  options?: Omit<UseQueryOptions<Category, Error, Category, readonly (string | undefined)[]>, 'queryKey' | 'queryFn'>
): UseQueryResult<Category, Error> => {
  return useQuery<Category, Error, Category, readonly (string | undefined)[]>({
    queryKey: ['category', slug] as const, // Query key includes the slug
    queryFn: () => getCategoryBySlug(slug!), // slug! is safe due to 'enabled' option
    enabled: !!slug, // Query will only run if slug is a truthy value
    // staleTime: 1000 * 60 * 5, // Example: Single category data might not change often
    ...options,
  });
};