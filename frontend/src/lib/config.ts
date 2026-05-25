// src/lib/config.ts
interface AppConfig {
  // API Configuration
  api: {
    baseUrl: string;
    backendUrl: string;
    timeout: number;
  };

  // App Information
  app: {
    name: string;
    version: string;
    environment: 'development' | 'staging' | 'production';
  };

  // Feature Flags
  features: {
    enableAnalytics: boolean;
    enableErrorReporting: boolean;
    enableDebugLogging: boolean;
  };

  // External Services
  services: {
    googleAnalyticsId?: string;
    sentryDsn?: string;
  };
}

const getEnvVar = (key: string, defaultValue?: string): string => {
  const value = (import.meta as any).env?.[key] || defaultValue;
  if (!value && !defaultValue) {
    console.warn(`Environment variable ${key} is not set`);
  }
  return value || '';
};

const getEnvBool = (key: string, defaultValue: boolean = false): boolean => {
  const value = (import.meta as any).env?.[key];
  if (value === undefined) return defaultValue;
  return value === 'true' || value === '1';
};

export const config: AppConfig = {
  api: {
    baseUrl: getEnvVar('VITE_API_BASE_URL', '/api'),
    backendUrl: getEnvVar('VITE_BACKEND_URL', 'http://127.0.0.1:8000'),
    timeout: parseInt(getEnvVar('VITE_API_TIMEOUT', '30000')),
  },

  app: {
    name: getEnvVar('VITE_APP_NAME', 'Latest Fashion Jewellery'),
    version: getEnvVar('VITE_APP_VERSION', '1.0.0'),
    environment: (getEnvVar('VITE_NODE_ENV', 'development') as AppConfig['app']['environment']),
  },

  features: {
    enableAnalytics: getEnvBool('VITE_ENABLE_ANALYTICS', false),
    enableErrorReporting: getEnvBool('VITE_ENABLE_ERROR_REPORTING', false),
    enableDebugLogging: getEnvBool('VITE_ENABLE_DEBUG_LOGGING', true),
  },

  services: {
    googleAnalyticsId: getEnvVar('VITE_GA_ID'),
    sentryDsn: getEnvVar('VITE_SENTRY_DSN'),
  },
};

// Environment-specific configurations
export const isDevelopment = config.app.environment === 'development';
export const isProduction = config.app.environment === 'production';
export const isStaging = config.app.environment === 'staging';

// Debug logging utility
export const debugLog = (...args: any[]) => {
  if (config.features.enableDebugLogging) {
    console.log('[DEBUG]', ...args);
  }
};

export const debugError = (...args: any[]) => {
  if (config.features.enableDebugLogging) {
    console.error('[DEBUG ERROR]', ...args);
  }
};