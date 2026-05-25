import axios from 'axios';

export interface CookieConsentData {
  visitor_id?: string;
  session_id?: string;
  essential: boolean;
  analytics: boolean;
  marketing: boolean;
  preferences: boolean;
  meta?: Record<string, any>;
}

export interface VisitorPreferenceData {
  visitor_id?: string;
  theme: 'light' | 'dark' | 'system';
  language: string;
  layout: 'standard' | 'compact' | 'comfortable';
  favorite_categories?: string[];
  favorite_tags?: string[];
  recently_viewed_products?: Array<{ product_id: string; title: string }>;
  recently_viewed_posts?: Array<{ post_id: string; title: string }>;
  saved_preferences?: Record<string, any>;
}

const API_BASE = import.meta.env.VITE_API_BASE_URL || import.meta.env.VITE_API_URL || '/api';
const axiosInstance = axios.create({
  baseURL: API_BASE,
  withCredentials: true,
});

const COOKIE_CONSENT_STORAGE_KEY = 'cookie_consent';
const VISITOR_ID_STORAGE_KEY = 'visitor_id';

class ConsentService {
  private visitorId: string | null = null;
  private sessionId: string | null = null;

  constructor() {
    this.loadStoredIds();
  }

  private loadStoredIds() {
    try {
      const storedVisitorId = localStorage.getItem(VISITOR_ID_STORAGE_KEY);
      if (storedVisitorId) {
        this.visitorId = storedVisitorId;
      }
    } catch {
      // ignore localStorage access issues
    }

    try {
      const cookieVisitorId = this.getCookie('visitor_id');
      if (cookieVisitorId) {
        this.visitorId = cookieVisitorId;
      }
    } catch {
      // ignore cookie access issues
    }
  }

  private getCookie(name: string): string | null {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop()?.split(';').shift() || null;
    return null;
  }

  private setCookie(name: string, value: string, days = 365) {
    const date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    const expires = `expires=${date.toUTCString()}`;
    document.cookie = `${name}=${value};${expires};path=/`;
  }

  private getStoredConsent(visitorId?: string): CookieConsentData | null {
    const consent = localStorage.getItem(COOKIE_CONSENT_STORAGE_KEY);
    if (!consent) return null;

    try {
      const parsed = JSON.parse(consent);
      if (visitorId && parsed.visitor_id && parsed.visitor_id !== visitorId) {
        return null;
      }
      return parsed;
    } catch {
      localStorage.removeItem(COOKIE_CONSENT_STORAGE_KEY);
      return null;
    }
  }

  private persistConsent(consent: CookieConsentData) {
    const visitorId = consent.visitor_id || this.getVisitorId();
    const storedConsent = {
      ...consent,
      visitor_id: visitorId,
      essential: true,
      updated_at: new Date().toISOString(),
    };

    try {
      localStorage.setItem(COOKIE_CONSENT_STORAGE_KEY, JSON.stringify(storedConsent));
    } catch {
      // ignore localStorage access issues
    }

    try {
      this.setCookie('visitor_id', visitorId);
    } catch {
      // ignore cookie write errors
    }

    return storedConsent;
  }

  getVisitorId(): string {
    if (!this.visitorId) {
      this.visitorId = `visitor_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;
      try {
        localStorage.setItem(VISITOR_ID_STORAGE_KEY, this.visitorId);
      } catch {
        // ignore localStorage access issues
      }
      try {
        this.setCookie('visitor_id', this.visitorId);
      } catch {
        // ignore cookie write issues
      }
    }
    return this.visitorId;
  }

  async saveConsent(consent: CookieConsentData) {
    const visitorId = this.getVisitorId();
    const localConsent = this.persistConsent({
      ...consent,
      visitor_id: consent.visitor_id || visitorId,
      session_id: this.sessionId || consent.session_id,
    });

    try {
      const response = await axiosInstance.post('/cookie-consents', {
        ...consent,
        visitor_id: consent.visitor_id || visitorId,
        session_id: this.sessionId || consent.session_id,
      });

      try {
        localStorage.setItem(COOKIE_CONSENT_STORAGE_KEY, JSON.stringify(response.data));
      } catch {
        // ignore localStorage issues
      }

      try {
        this.setCookie('visitor_id', response.data.visitor_id || visitorId);
      } catch {
        // ignore cookie write issues
      }

      return response.data;
    } catch (error) {
      console.warn('Cookie consent saved locally but could not be synced to the API:', error);
      return localConsent;
    }
  }

  async getConsent(visitorId?: string) {
    const id = visitorId || this.getVisitorId();
    const storedConsent = this.getStoredConsent(id);

    try {
      const response = await axiosInstance.get(`/cookie-consents/${id}`);
      localStorage.setItem(COOKIE_CONSENT_STORAGE_KEY, JSON.stringify(response.data));
      this.setCookie('visitor_id', response.data.visitor_id || id);
      return response.data;
    } catch (error) {
      // Not found is expected for new visitors
      if (axios.isAxiosError(error) && error.response?.status === 404) {
        return storedConsent;
      }
      console.warn('Failed to get cookie consent from API; using local consent if available:', error);
      return storedConsent;
    }
  }

  async updateConsent(id: string, consent: Partial<CookieConsentData>) {
    const existingConsent = this.getStoredConsent();
    const localConsent = this.persistConsent({
      essential: true,
      analytics: false,
      marketing: false,
      preferences: false,
      ...existingConsent,
      ...consent,
      visitor_id: existingConsent?.visitor_id || this.getVisitorId(),
    });

    try {
      const response = await axiosInstance.put(`/cookie-consents/${id}`, consent);
      try {
        localStorage.setItem(COOKIE_CONSENT_STORAGE_KEY, JSON.stringify(response.data));
      } catch {
        // ignore localStorage issues
      }
      return response.data;
    } catch (error) {
      console.warn('Cookie consent updated locally but could not be synced to the API:', error);
      return localConsent;
    }
  }

  async savePreferences(prefs: VisitorPreferenceData) {
    try {
      const visitorId = this.getVisitorId();
      const response = await axiosInstance.post('/visitor-preferences', {
        ...prefs,
        visitor_id: prefs.visitor_id || visitorId,
      });

      localStorage.setItem('visitor_preferences', JSON.stringify(response.data));
      return response.data;
    } catch (error) {
      console.error('Failed to save visitor preferences:', error);
      throw error;
    }
  }

  async getPreferences(visitorId?: string) {
    try {
      const id = visitorId || this.getVisitorId();
      const response = await axiosInstance.get(`/visitor-preferences/${id}`);
      localStorage.setItem('visitor_preferences', JSON.stringify(response.data));
      return response.data;
    } catch (error) {
      // Not found is expected for new visitors
      if (axios.isAxiosError(error) && error.response?.status === 404) {
        return null;
      }
      console.error('Failed to get visitor preferences:', error);
      throw error;
    }
  }

  async updatePreferences(id: string, prefs: Partial<VisitorPreferenceData>) {
    try {
      const response = await axiosInstance.put(`/visitor-preferences/${id}`, prefs);
      localStorage.setItem('visitor_preferences', JSON.stringify(response.data));
      return response.data;
    } catch (error) {
      console.error('Failed to update visitor preferences:', error);
      throw error;
    }
  }

  hasAnalyticsConsent(): boolean {
    const consent = localStorage.getItem(COOKIE_CONSENT_STORAGE_KEY);
    if (!consent) return false;
    try {
      const parsed = JSON.parse(consent);
      return parsed.analytics === true;
    } catch {
      return false;
    }
  }

  hasMarketingConsent(): boolean {
    const consent = localStorage.getItem(COOKIE_CONSENT_STORAGE_KEY);
    if (!consent) return false;
    try {
      const parsed = JSON.parse(consent);
      return parsed.marketing === true;
    } catch {
      return false;
    }
  }
}

export default new ConsentService();
