// src/hooks/usePosts.ts
import { useQuery, UseQueryOptions, UseQueryResult } from '@tanstack/react-query';
import { getPosts, getPostBySlug, Post, PaginatedPostsResponse, GetPostsParams } from '@/services/postService';
import { QUERY_KEYS } from '@/lib/constants';

export const usePosts = (
  params: GetPostsParams = {},
  options?: Omit<UseQueryOptions<PaginatedPostsResponse, Error, PaginatedPostsResponse>, 'queryKey' | 'queryFn'>
): UseQueryResult<PaginatedPostsResponse, Error> => {
  return useQuery<PaginatedPostsResponse, Error, PaginatedPostsResponse>({
    queryKey: [QUERY_KEYS.POSTS, params],
    queryFn: () => getPosts(params),
    ...options,
  });
};

export const usePost = (
  slug: string | undefined,
  options?: Omit<UseQueryOptions<Post, Error>, 'queryKey' | 'queryFn'>
): UseQueryResult<Post, Error> => {
  return useQuery<Post, Error>({
    queryKey: [QUERY_KEYS.POST, slug],
    queryFn: () => getPostBySlug(slug!),
    enabled: !!slug,
    ...options,
  });
};
