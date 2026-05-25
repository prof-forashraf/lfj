import type { User } from '@/services/authService';

const authPaths = ['/login', '/register', '/forgot-password'];

export const getBackendBaseUrl = (): string => {
  const configuredBackendUrl = import.meta.env.VITE_BACKEND_URL?.replace(/\/$/, '');
  const currentOrigin = window.location.origin;

  if (configuredBackendUrl) {
    const isLocalHostOrigin = /^https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?$/i.test(currentOrigin);
    const isConfiguredRemote = !/^https?:\/\/(localhost|127\.0\.0\.1)(:\d+)?$/i.test(configuredBackendUrl);

    if (isLocalHostOrigin && isConfiguredRemote) {
      return currentOrigin;
    }

    return configuredBackendUrl;
  }

  const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || '/api';
  if (/^https?:\/\//i.test(apiBaseUrl)) {
    return apiBaseUrl.replace(/\/api\/?$/, '').replace(/\/$/, '');
  }

  return currentOrigin;
};

export const getDashboardRedirectPath = (user: User | null, from?: string): string => {
  if (user?.can_access_filament) {
    return user.redirect_to ?? '/admin';
  }

  const normalizedFrom = from?.trim();
  const shouldUseFrom =
    normalizedFrom &&
    normalizedFrom !== '/' &&
    !authPaths.includes(normalizedFrom) &&
    !normalizedFrom.startsWith('/admin');

  if (shouldUseFrom) {
    return normalizedFrom;
  }

  return user?.redirect_to ?? '/dashboard';
};

export const isFilamentRedirectPath = (path: string): boolean => path.startsWith('/admin');

export const getFilamentRedirectUrl = (path: string): string => {
  if (/^https?:\/\//i.test(path)) {
    return path;
  }

  return `${getBackendBaseUrl()}${path.startsWith('/') ? path : `/${path}`}`;
};
