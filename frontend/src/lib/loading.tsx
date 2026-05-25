// src/lib/loading.ts
import { useState, useCallback } from 'react';

export interface LoadingState {
  isLoading: boolean;
  error: string | null;
  isSuccess: boolean;
}

export const useLoadingState = (initialState: Partial<LoadingState> = {}) => {
  const [state, setState] = useState<LoadingState>({
    isLoading: false,
    error: null,
    isSuccess: false,
    ...initialState,
  });

  const startLoading = useCallback(() => {
    setState({ isLoading: true, error: null, isSuccess: false });
  }, []);

  const stopLoading = useCallback((error?: string | null, success: boolean = false) => {
    setState({
      isLoading: false,
      error: error || null,
      isSuccess: success,
    });
  }, []);

  const reset = useCallback(() => {
    setState({ isLoading: false, error: null, isSuccess: false });
  }, []);

  return {
    ...state,
    startLoading,
    stopLoading,
    reset,
  };
};

// Higher-order component for loading states
export const withLoadingState = <T extends unknown[], R>(
  asyncFn: (...args: T) => Promise<R>
) => {
  return async (...args: T): Promise<R> => asyncFn(...args);
};

// Loading spinner component
export const LoadingSpinner = ({ size = 'md', className = '' }: {
  size?: 'sm' | 'md' | 'lg';
  className?: string;
}) => {
  const sizeClasses = {
    sm: 'w-4 h-4',
    md: 'w-6 h-6',
    lg: 'w-8 h-8',
  };

  return (
    <div className={`animate-spin rounded-full border-2 border-gray-300 border-t-blue-600 ${sizeClasses[size]} ${className}`} />
  );
};

// Skeleton loader
export const SkeletonLoader = ({ className = '', lines = 3 }: {
  className?: string;
  lines?: number;
}) => (
  <div className={`space-y-2 ${className}`}>
    {Array.from({ length: lines }).map((_, i) => (
      <div key={i} className="h-4 bg-gray-200 rounded animate-pulse" />
    ))}
  </div>
);
