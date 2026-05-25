// src/lib/constants.ts
export const API_ENDPOINTS = {
  // Auth
  LOGIN: '/auth/login',
  LOGOUT: '/auth/logout',
  REGISTER: '/auth/register',
  USER: '/auth/user',

  // Posts
  POSTS: '/posts',
  POSTS_BY_SLUG: (slug: string) => `/posts/${slug}`,

  // Categories
  CATEGORIES: '/categories',

  // Events
  EVENTS: '/events',
  EVENTS_BY_SLUG: (slug: string) => `/events/${slug}`,

  // Products
  PRODUCTS: '/products',
  JEWELLERY_PRODUCTS: '/jewellery/products',
  AFFILIATE_PRODUCTS: '/affiliate-products',

  // Studio
  STUDIO_ITEMS: '/studio/items',

  // Videos
  VIDEOS: '/videos',

  // Tags
  TAGS: '/tags',
  SEO_PAGE: (page: string) => `/seo/page/${page}`,

  // Tools
  GOLD_PRICES: '/tools/gold-prices',
  CARAT_CONVERTER: '/tools/carat-converter',
  RING_SIZE_CONVERTER: '/tools/ring-size-converter',
} as const;

export const QUERY_KEYS = {
  POSTS: 'posts',
  POST: 'post',
  CATEGORIES: 'categories',
  EVENTS: 'events',
  EVENT: 'event',
  PRODUCTS: 'products',
  JEWELLERY_PRODUCTS: 'jewellery-products',
  AFFILIATE_PRODUCTS: 'affiliate-products',
  STUDIO_ITEMS: 'studio-items',
  VIDEOS: 'videos',
  TAGS: 'tags',
  SEO: 'seo',
  USER: 'user',
} as const;

export const STORAGE_KEYS = {
  USER_DATA: 'user_data',
  THEME: 'theme',
} as const;

export const IMAGE_PLACEHOLDER = '/images/placeholder.svg';

export const PAGINATION_DEFAULTS = {
  PAGE: 1,
  PER_PAGE: 12,
  MAX_PER_PAGE: 100,
} as const;

export const DEBOUNCE_DELAY = 300;

export const HTTP_STATUS = {
  OK: 200,
  CREATED: 201,
  NO_CONTENT: 204,
  BAD_REQUEST: 400,
  UNAUTHORIZED: 401,
  FORBIDDEN: 403,
  NOT_FOUND: 404,
  CONFLICT: 409,
  UNPROCESSABLE_ENTITY: 422,
  INTERNAL_SERVER_ERROR: 500,
} as const;
