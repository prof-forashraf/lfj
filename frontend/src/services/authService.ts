// src/services/authService.ts
import axios from 'axios';
import apiClient from '@/lib/apiClient';

export interface LoginData {
  email: string;
  password: string;
  remember?: boolean;
}

export interface RegisterData {
  name: string;
  email: string;
  password: string;
  password_confirmation: string;
}

export interface ForgotPasswordData {
  email: string;
}

export interface User {
  id: number;
  name: string;
  roles?: string[];
  can_access_filament?: boolean;
  redirect_to?: string;
}

export interface AuthResponse {
  message: string;
  user: User;
}

class AuthService {
  private getCsrfCookieUrl(): string {
    const apiBaseUrl = import.meta.env.VITE_API_BASE_URL || '/api';

    if (/^https?:\/\//i.test(apiBaseUrl)) {
      return `${apiBaseUrl.replace(/\/api\/?$/, '').replace(/\/$/, '')}/sanctum/csrf-cookie`;
    }

    return '/sanctum/csrf-cookie';
  }

  private async ensureCsrfCookie(): Promise<void> {
    await axios.get(this.getCsrfCookieUrl(), { withCredentials: true });
  }

  async login(data: LoginData): Promise<AuthResponse> {
    await this.ensureCsrfCookie();
    const response = await apiClient.post<AuthResponse>('/login', data);
    return response.data;
  }

  async register(data: RegisterData): Promise<AuthResponse> {
    await this.ensureCsrfCookie();
    const response = await apiClient.post<AuthResponse>('/register', data);
    return response.data;
  }

  async forgotPassword(data: ForgotPasswordData): Promise<{ message: string }> {
    await this.ensureCsrfCookie();
    const response = await apiClient.post<{ message: string }>('/forgot-password', data);
    return response.data;
  }

  async logout(): Promise<{ message: string }> {
    const response = await apiClient.post<{ message: string }>('/logout');
    return response.data;
  }

  async getUser(): Promise<User | null> {
    try {
      const response = await apiClient.get<User>('/user');
      return response.data || null;
    } catch (error: any) {
      // Silently handle 401 and no-content responses for anonymous users
      if (error.response?.status === 401 || error.response?.status === 204) {
        return null;
      }
      // Log other errors but don't throw
      console.warn('Auth check failed:', error);
      return null;
    }
  }
}

export const authService = new AuthService();
