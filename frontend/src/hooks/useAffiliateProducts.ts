import { useQuery, UseQueryResult, UseQueryOptions } from '@tanstack/react-query';
import {
  // 👇 1. CORRECTED: Import the service object, not the individual functions
  affiliateProductService,
  // Import types as usual
  AffiliateProduct,
  PaginatedAffiliateProductsResponse,
  GetAffiliateProductsParams
} from '@/services/affiliateProductService';

// Helper function to generate a random rating between 4.0 and 5.0
const getRandomRating = (): number => {
  return parseFloat((Math.random() * (5.0 - 4.0) + 4.0).toFixed(1));
};

export const useAffiliateProducts = (
  params: GetAffiliateProductsParams = { page: 1, perPage: 12 },
  options?: Omit<UseQueryOptions<PaginatedAffiliateProductsResponse, Error>, 'queryKey' | 'queryFn' | 'select'>
): UseQueryResult<PaginatedAffiliateProductsResponse, Error> => {
  return useQuery<PaginatedAffiliateProductsResponse, Error, PaginatedAffiliateProductsResponse>({
    queryKey: ['affiliateProducts', params],
    // 👇 2. CORRECTED: Call the method from the service object
    queryFn: () => affiliateProductService.getAffiliateProducts(params),
    select: (data) => {
      const transformedData = data.data.map(product => ({
        ...product,
        rating: getRandomRating(),
      }));
      return {
        ...data,
        data: transformedData,
      };
    },
    ...options,
  });
};

export const useFeaturedAffiliateProducts = (
  limit: number = 4,
  queryIdentifier: string | number = 'default',
  options?: Omit<UseQueryOptions<AffiliateProduct[], Error>, 'queryKey' | 'queryFn' | 'select'>
): UseQueryResult<AffiliateProduct[], Error> => {
  return useQuery({
    queryKey: ['featuredAffiliateProducts', limit, queryIdentifier],
    // 👇 2. CORRECTED: Call the method from the service object
    queryFn: () => affiliateProductService.getRandomAffiliateProducts(limit),
    select: (data) => {
      return data.map(product => ({
        ...product,
        rating: getRandomRating(),
      }));
    },
    ...options,
  });
};

export const useAffiliateProductByAsin = (
  asin: string | undefined,
  options?: Omit<UseQueryOptions<AffiliateProduct, Error>, 'queryKey' | 'queryFn' | 'select'>
): UseQueryResult<AffiliateProduct, Error> => {
  return useQuery({
    queryKey: ['affiliateProduct', asin],
    // 👇 2. CORRECTED: Call the method from the service object
    queryFn: () => affiliateProductService.getAffiliateProductByAsin(asin!),
    enabled: !!asin,
    select: (data) => ({
      ...data,
      rating: getRandomRating(),
    }),
    ...options,
  });
};

export const useAffiliateProductsByCategory = (
  slug: string | undefined,
  page: number = 1,
  perPage: number = 12
): UseQueryResult<PaginatedAffiliateProductsResponse, Error> => {
  return useQuery<PaginatedAffiliateProductsResponse, Error, PaginatedAffiliateProductsResponse>({
    queryKey: ['affiliateProducts', 'category', slug, { page, perPage }],
    // 👇 2. CORRECTED: Call the method from the service object
    queryFn: () => affiliateProductService.getProductsByCategory(slug!, page, perPage),
    enabled: !!slug,
    select: (data) => {
      const transformedData = data.data.map(product => ({
        ...product,
        rating: getRandomRating(),
      }));
      return {
        ...data,
        data: transformedData,
      };
    },
  });
};

export const useSearchProducts = (
  query: string,
  page: number = 1
): UseQueryResult<PaginatedAffiliateProductsResponse, Error> => {
  return useQuery<PaginatedAffiliateProductsResponse, Error, PaginatedAffiliateProductsResponse>({
    queryKey: ['searchAffiliateProducts', query, page],
    // This one was already correct!
    queryFn: () => affiliateProductService.searchProducts(query, page),
    enabled: query.length >= 2,
    select: (data) => {
      const transformedData = data.data.map(product => ({
        ...product,
        rating: getRandomRating(),
      }));
      return {
        ...data,
        data: transformedData,
      };
    },
  });
};