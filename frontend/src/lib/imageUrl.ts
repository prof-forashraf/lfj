import { IMAGE_PLACEHOLDER } from './constants';

const normalizeLocalHostUrl = (url: string): string => {
  try {
    const parsed = new URL(url);
    const host = parsed.hostname.toLowerCase();

    if (host === 'localhost.local' || host.endsWith('.local')) {
      return `${window.location.origin}${parsed.pathname}${parsed.search}${parsed.hash}`;
    }
  } catch {
    return url;
  }

  return url;
};

export const getPublicImageUrl = (imagePath?: string | null): string => {
  if (!imagePath) {
    return IMAGE_PLACEHOLDER;
  }

  const trimmedPath = imagePath.trim();

  // If it's already a full URL, normalize any invalid local hostname and return it.
  if (trimmedPath.startsWith('http://') || trimmedPath.startsWith('https://')) {
    return normalizeLocalHostUrl(trimmedPath);
  }

  // If it's a protocol-relative URL, add current protocol and normalize it.
  if (trimmedPath.startsWith('//')) {
    return normalizeLocalHostUrl(`${window.location.protocol}${trimmedPath}`);
  }

  // Handle /storage/ paths
  if (trimmedPath.startsWith('/storage/')) {
    return trimmedPath; // already absolute path starting with /storage/
  }

  // Handle storage/ paths
  if (trimmedPath.startsWith('storage/')) {
    return `/${trimmedPath}`;
  }

  // Handle public/ paths
  if (trimmedPath.startsWith('public/')) {
    return `/storage/${trimmedPath.substring(7)}`;
  }

  // Handle other absolute paths
  if (trimmedPath.startsWith('/')) {
    return trimmedPath;
  }

  // Handle relative paths - assume they're in storage
  const sanitizedPath = trimmedPath.replace(/^\/?/, '');
  return `/storage/${sanitizedPath}`;
};

export const getPlaceholderImageUrl = (): string => IMAGE_PLACEHOLDER;
