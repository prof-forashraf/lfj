// src/hooks/useCategories.ts
import { useQuery, UseQueryResult, UseQueryOptions } from '@tanstack/react-query';
import { getCategories, PaginatedCategoriesResponse } from '@/services/categoryService'; // Ensure PaginatedCategoriesResponse is exported

// If you want to pass params to this hook for pagination, define an interface
interface UseCategoriesParams {
  page?: number;
  perPage?: number;
}

export const useCategories = (
  params: UseCategoriesParams = { page: 1, perPage: 100 }, // Default to fetching up to 100 categories
  options?: Omit<UseQueryOptions<PaginatedCategoriesResponse, Error, PaginatedCategoriesResponse, readonly (string | UseCategoriesParams)[]>, 'queryKey' | 'queryFn'>
): UseQueryResult<PaginatedCategoriesResponse, Error> => {
  return useQuery<PaginatedCategoriesResponse, Error, PaginatedCategoriesResponse, readonly (string | UseCategoriesParams)[]>({
    queryKey: ['categories', params] as const,
    queryFn: () => getCategories(params.page, params.perPage), // <-- Call getCategories with params
    // Consider a long staleTime if categories don't change often
    // staleTime: 1000 * 60 * 60, // 1 hour
    ...options,
  });
};