// src/lib/errors.ts
export class ApiError extends Error {
  constructor(
    message: string,
    public status: number,
    public code?: string,
    public details?: any
  ) {
    super(message);
    this.name = 'ApiError';
  }
}

export class ValidationError extends Error {
  constructor(
    message: string,
    public field: string,
    public value?: any
  ) {
    super(message);
    this.name = 'ValidationError';
  }
}

export class NetworkError extends Error {
  constructor(message: string = 'Network request failed') {
    super(message);
    this.name = 'NetworkError';
  }
}

export class AuthenticationError extends Error {
  constructor(message: string = 'Authentication required') {
    super(message);
    this.name = 'AuthenticationError';
  }
}

export const isApiError = (error: any): error is ApiError => {
  return error instanceof ApiError;
};

export const isValidationError = (error: any): error is ValidationError => {
  return error instanceof ValidationError;
};

export const isNetworkError = (error: any): error is NetworkError => {
  return error instanceof NetworkError;
};

export const isAuthenticationError = (error: any): error is AuthenticationError => {
  return error instanceof AuthenticationError;
};

export const handleApiError = (error: any): never => {
  if (isApiError(error)) {
    throw error;
  }

  if (error.response) {
    // Server responded with error status
    const { status, data } = error.response;
    const message = data?.message || `Request failed with status ${status}`;

    if (status === 401) {
      throw new AuthenticationError(message);
    }

    if (status === 422) {
      throw new ValidationError(message, data?.errors || 'Validation failed');
    }

    throw new ApiError(message, status, data?.code, data);
  }

  if (error.request) {
    // Network error
    throw new NetworkError('Network request failed. Please check your connection.');
  }

  // Unknown error
  throw new ApiError(error.message || 'An unexpected error occurred', 500);
};