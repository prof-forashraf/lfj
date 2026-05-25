import { useInfiniteQuery, UseInfiniteQueryOptions, UseInfiniteQueryResult } from '@tanstack/react-query';
import { eventService, type Event, type GetEventsParams, type PaginatedEventsResponse } from '@/services/eventService';

// Your existing useEvent and useEvents hooks can remain as they are.

// --- CORRECTED HOOK FOR INFINITE SCROLL ---

// Define a type for the hook's parameters for better clarity.
type UseInfiniteEventsParams = Omit<GetEventsParams, 'page'>;

export const useInfiniteEvents = (
  // Use the clear type we just defined.
  params: UseInfiniteEventsParams,
  // Correctly type the options parameter for useInfiniteQuery.
  // We specify all the generic types it needs.
  options?: Omit<
    UseInfiniteQueryOptions<
      PaginatedEventsResponse, // TQueryFnData: The data type returned by queryFn
      Error,                   // TError: The error type
      PaginatedEventsResponse, // TData: The final data type after select (if any)
      (string | UseInfiniteEventsParams)[], // TQueryKey: The type of the query key array
      number                   // TPageParam: The type of the page parameter (it's a number)
    >,
    'queryKey' | 'queryFn' | 'getNextPageParam' | 'initialPageParam'
  >
): UseInfiniteQueryResult<PaginatedEventsResponse, Error> => {
  return useInfiniteQuery({
    // The query key needs to be asserted 'as const' or typed correctly
    // to match the TQueryKey generic.
    queryKey: ['infiniteEvents', params],
    
    // The query function receives a context object with pageParam.
    queryFn: ({ pageParam }) => {
      // The pageParam can be undefined initially, so we default to 1.
      const currentPage = typeof pageParam === 'number' ? pageParam : 1;
      return eventService.getEvents({ ...params, page: currentPage });
    },
    
    // This function tells React Query how to get the page number for the *next* page.
    getNextPageParam: (lastPage: PaginatedEventsResponse) => {
      const currentPage = lastPage.meta.current_page;
      const lastPageNum = lastPage.meta.last_page;
      
      // If the current page is less than the last page, return the next page number.
      if (currentPage < lastPageNum) {
        return currentPage + 1;
      }
      
      // If we're on the last page, return undefined or null to signal there's no next page.
      return undefined;
    },

    // The initial page number to start from.
    initialPageParam: 1,

    // Spread any additional options passed to the hook.
    ...options,
  });
};