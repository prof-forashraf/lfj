// src/services/videoService.ts
import apiClient from '@/lib/apiClient';

// Video interface remains the same
export interface Video {
  id: number;
  title: string;
  youtube_video_id: string;
  description: string | null;
  duration_seconds: number | null;
  status: string;
  is_featured: boolean;
  thumbnail_url: string;
  embed_url: string;
  created_at_human: string;
}

// ✅ FIX: Define a proper type for the meta object
export interface VideoMeta {
  current_page: number;
  from: number | null;
  last_page: number;
  path: string;
  per_page: number;
  to: number | null;
  total: number;
}

// ✅ FIX: Use the new VideoMeta type
export interface PaginatedVideosResponse {
  data: Video[];
  links: any; // This is okay for now if not used
  meta: VideoMeta;
}

export interface GetVideosParams {
  page?: number;
  perPage?: number;
  featured?: boolean;
  search?: string;
}

export const getVideos = async ({
  page = 1,
  perPage = 12,
  featured = false,
  search = '',
}: GetVideosParams): Promise<PaginatedVideosResponse> => {
  const params = new URLSearchParams({
    page: page.toString(),
    per_page: perPage.toString(),
  });

  if (featured) {
    params.append('featured', 'true');
  }
  
  if (search.trim() !== '') {
    params.append('search', search.trim());
  }

  const response = await apiClient.get<PaginatedVideosResponse>(`/videos?${params.toString()}`);
  return response.data;
};

export const getVideo = async (identifier: string | number): Promise<Video> => {
  const response = await apiClient.get<{data: Video}>(`/videos/${identifier}`);
  return response.data.data;
};