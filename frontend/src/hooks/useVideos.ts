// src/hooks/useVideos.ts

import {
  useQuery,
  useInfiniteQuery,
  UseQueryResult,
  UseInfiniteQueryResult,
  UseQueryOptions,
  keepPreviousData,
} from '@tanstack/react-query';

import {
  getVideos,
  getVideo,
  Video,
  PaginatedVideosResponse,
  GetVideosParams,
} from '@/services/videoService';

//===============================================
// ✨ 1. Infinite Scroll Hook (for "Load More")
//===============================================

/**
 * Fetches videos in an infinite paginated format (for "Load More" buttons).
 */
export const useInfiniteVideos = (
  params: Omit<GetVideosParams, 'page'>
): UseInfiniteQueryResult<PaginatedVideosResponse, Error> => {
  // ✅ FIX: Removed the incorrect explicit generics from the useInfiniteQuery call.
  // TypeScript will now correctly infer the return type.
  return useInfiniteQuery({
    queryKey: ['videos', 'infinite', params],
    queryFn: ({ pageParam = 1 }) => getVideos({ ...params, page: pageParam }),
    getNextPageParam: (lastPage) => {
      if (lastPage.meta.current_page < lastPage.meta.last_page) {
        return lastPage.meta.current_page + 1;
      }
      return undefined;
    },
    initialPageParam: 1,
  });
};

//===============================================
// ✅ 2. Standard Pagination Hook (No changes needed)
//===============================================

export const useVideos = (
  params: GetVideosParams = { page: 1, perPage: 12, featured: false },
  options?: Omit<
    UseQueryOptions<
      PaginatedVideosResponse,
      Error,
      PaginatedVideosResponse,
      readonly [string, GetVideosParams]
    >,
    'queryKey' | 'queryFn'
  >
): UseQueryResult<PaginatedVideosResponse, Error> => {
  return useQuery({
    queryKey: ['videos', params] as const,
    queryFn: () => getVideos(params),
    placeholderData: keepPreviousData,
    ...options,
  });
};

//===============================================
// 📺 3. Optional: Fetch a Single Video (No changes needed)
//===============================================

export const useVideo = (
  identifier: string | number | undefined,
  options?: Omit<
    UseQueryOptions<Video, Error, Video, readonly [string, string | number | undefined]>,
    'queryKey' | 'queryFn'
  >
) => {
  return useQuery({
    queryKey: ['video', identifier] as const,
    queryFn: () => getVideo(identifier!),
    enabled: !!identifier,
    ...options,
  });
};