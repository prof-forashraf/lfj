import { useQuery, useInfiniteQuery, UseInfiniteQueryOptions, UseInfiniteQueryResult, UseQueryResult, UseQueryOptions } from '@tanstack/react-query';
import { eventService, type Event, type GetEventsParams, type PaginatedEventsResponse } from '@/services/eventService';

// --- HOOK #1: For fetching a SINGLE event (No changes needed) ---
export const useEvent = (
  slug: string | undefined,
  options?: Omit<UseQueryOptions<Event, Error>, 'queryKey' | 'queryFn'>
): UseQueryResult<Event, Error> => {
  return useQuery({
    queryKey: ['event', slug],
    queryFn: () => eventService.getEventBySlug(slug!),
    enabled: !!slug,
    ...options,
  });
};

// --- HOOK #2: For fetching a simple, paginated LIST of events (RESTORE THIS) ---
// This is needed by EventDetailPage for "related events".
export const useEvents = (
  params?: GetEventsParams,
  options?: Omit<UseQueryOptions<PaginatedEventsResponse, Error>, 'queryKey' | 'queryFn'>
): UseQueryResult<PaginatedEventsResponse, Error> => {
  return useQuery({
    queryKey: ['events', params],
    queryFn: () => eventService.getEvents(params),
    ...options,
  });
};


// --- HOOK #3: The new hook for INFINITE SCROLL on the main list page ---
type UseInfiniteEventsParams = Omit<GetEventsParams, 'page'>;

export const useInfiniteEvents = (
  params: UseInfiniteEventsParams,
  options?: Omit<
    UseInfiniteQueryOptions<
      PaginatedEventsResponse,
      Error,
      PaginatedEventsResponse,
      (string | UseInfiniteEventsParams)[],
      number
    >,
    'queryKey' | 'queryFn' | 'getNextPageParam' | 'initialPageParam'
  >
): UseInfiniteQueryResult<PaginatedEventsResponse, Error> => {
  return useInfiniteQuery({
    queryKey: ['infiniteEvents', params],
    queryFn: ({ pageParam }) => {
      const currentPage = typeof pageParam === 'number' ? pageParam : 1;
      return eventService.getEvents({ ...params, page: currentPage });
    },
    getNextPageParam: (lastPage: PaginatedEventsResponse) => {
      const currentPage = lastPage.meta.current_page;
      const lastPageNum = lastPage.meta.last_page;
      if (currentPage < lastPageNum) {
        return currentPage + 1;
      }
      return undefined;
    },
    initialPageParam: 1,
    ...options,
  });
};