
// src/lib/apiClient.ts
import axios, { AxiosInstance, AxiosResponse, AxiosError, InternalAxiosRequestConfig } from 'axios';
import { ApiError, NetworkError, AuthenticationError, handleApiError } from './errors';

const apiClient: AxiosInstance = axios.create({
  baseURL: (import.meta as any).env?.VITE_API_BASE_URL || '/api',
  withCredentials: true,
  timeout: 30000, // 30 seconds
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
});

// Request interceptor
apiClient.interceptors.request.use(
  (config: InternalAxiosRequestConfig) => {
    // Add request ID for debugging
    if (config.headers) {
      config.headers['X-Request-ID'] = crypto.randomUUID();
    }

    return config;
  },
  (error: AxiosError) => {
    return Promise.reject(handleApiError(error));
  }
);

// Response interceptor
apiClient.interceptors.response.use(
  (response: AxiosResponse) => {
    return response;
  },
  (error: AxiosError) => {
    return Promise.reject(handleApiError(error));
  }
);

export default apiClient;

// Type-safe API methods
export const api = {
  get: <T = any>(url: string, config?: any) =>
    apiClient.get<T>(url, config).then(res => res.data),

  post: <T = any>(url: string, data?: any, config?: any) =>
    apiClient.post<T>(url, data, config).then(res => res.data),

  put: <T = any>(url: string, data?: any, config?: any) =>
    apiClient.put<T>(url, data, config).then(res => res.data),

  patch: <T = any>(url: string, data?: any, config?: any) =>
    apiClient.patch<T>(url, data, config).then(res => res.data),

  delete: <T = any>(url: string, config?: any) =>
    apiClient.delete<T>(url, config).then(res => res.data),
};
