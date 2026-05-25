import apiClient from '@/lib/apiClient';

// Define the Event Type (should match your Laravel EventResource)
export interface Event {
  id: number;
  title: string;
  slug: string;
  description_html: string;
  start_datetime_iso: string | null;
  start_datetime_formatted: string | null;
  end_datetime_formatted: string | null;
  location_name: string | null;
  location_address: string | null;
  event_url: string | null;
  featured_image_url: string | null;
  status: 'upcoming' | 'past' | 'cancelled';
  seo: {
    title?: string | null;
    meta_description?: string | null;
  };
}
export interface PaginationMeta {
  current_page: number;
  last_page: number;
  from: number | null;
  to: number | null;
  total: number;
  per_page: number;
  path: string;
  links: { url: string | null; label: string; active: boolean }[];
}

// For a paginated API response
export interface PaginatedEventsResponse {
  data: Event[];
  meta: any; // Add pagination meta fields if needed
}

// For a single event API response
interface SingleEventResponse {
  data: Event;
}

// All possible parameters for fetching events
export interface GetEventsParams {
  page?: number;
  perPage?: number;
  status?: 'upcoming' | 'past' | 'all';
  limit?: number;
  exclude?: string;
  // --- NEW PARAMETERS ---
  search?: string;
  start_date?: string; // Expects 'YYYY-MM-DD' format
  end_date?: string;   // Expects 'YYYY-MM-DD' format
  category?: string; // e.g., 'workshop', 'exhibition'
}

// --- SERVICE OBJECT ---

export const eventService = {
  /**
   * THIS IS THE SINGLE, CORRECTED FUNCTION
   * Fetches a list of events. Can be paginated or limited.
   */
  getEvents: async (params?: GetEventsParams): Promise<PaginatedEventsResponse> => {
    try {
      // The `params` object is automatically converted to a query string by axios
      // e.g., /events?limit=3&exclude=some-slug
      const response = await apiClient.get<PaginatedEventsResponse>('/events', { params });
      return response.data;
    } catch (error) {
      console.error("Error fetching events:", error);
      throw error;
    }
  },

  /**
   * Fetches a single event by its slug.
   */
  getEventBySlug: async (slug: string): Promise<Event> => {
    try {
      const response = await apiClient.get<SingleEventResponse>(`/events/${slug}`);
      return response.data.data;
    } catch (error) {
      console.error(`Error fetching event ${slug}:`, error);
      throw error;
    }
  }
};