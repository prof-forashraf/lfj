import { useState, useEffect, useCallback } from 'react';
import { toast } from 'sonner';
import consentService, { CookieConsentData, VisitorPreferenceData } from '@/services/consentService';
import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';

export interface CookieConsent {
  id?: number;
  visitor_id: string;
  analytics: boolean;
  marketing: boolean;
  preferences: boolean;
  essential: boolean;
  created_at?: string;
  updated_at?: string;
}

export interface VisitorPreference {
  id?: number;
  visitor_id: string;
  theme: 'light' | 'dark' | 'system';
  language: string;
  layout: 'standard' | 'compact' | 'comfortable';
  favorite_categories?: string[];
  favorite_tags?: string[];
  recently_viewed_products?: Array<{ product_id: string; title: string }>;
  recently_viewed_posts?: Array<{ post_id: string; title: string }>;
  created_at?: string;
  updated_at?: string;
}

export const useCookieConsent = () => {
  const queryClient = useQueryClient();
  const visitorId = consentService.getVisitorId();
  const COOKIE_CONSENT_DISMISSED_KEY = 'cookie_consent_dismissed';

  const getInitialDismissedState = useCallback(() => {
    try {
      return localStorage.getItem(COOKIE_CONSENT_DISMISSED_KEY) === 'true';
    } catch {
      return false;
    }
  }, []);

  const [consentBannerHidden, setConsentBannerHidden] = useState<boolean>(getInitialDismissedState);

  const setBannerHidden = useCallback((hidden: boolean) => {
    try {
      if (hidden) {
        localStorage.setItem(COOKIE_CONSENT_DISMISSED_KEY, 'true');
      } else {
        localStorage.removeItem(COOKIE_CONSENT_DISMISSED_KEY);
      }
    } catch {
      // ignore localStorage errors in private mode
    }
    setConsentBannerHidden(hidden);
  }, []);

  const openConsentBanner = useCallback(() => {
    setBannerHidden(false);
  }, [setBannerHidden]);

  const dismissConsentBanner = useCallback(() => {
    setBannerHidden(true);
  }, [setBannerHidden]);

  // Fetch current consent
  const { data: consent, isLoading: consentLoading } = useQuery({
    queryKey: ['cookieConsent', visitorId],
    queryFn: () => consentService.getConsent(visitorId),
    staleTime: 1000 * 60 * 30, // 30 minutes
  });

  // Save consent mutation
  const saveConsentMutation = useMutation({
    mutationFn: (data: CookieConsentData) => consentService.saveConsent(data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['cookieConsent', visitorId] });
      setBannerHidden(true);
      toast.success('Cookie preferences saved', {
        description: 'Your consent preferences have been recorded.',
        duration: 3000,
      });
    },
    onError: () => {
      toast.error('Failed to save preferences', {
        description: 'Please try again.',
        duration: 3000,
      });
    },
  });

  // Update consent mutation
  const updateConsentMutation = useMutation({
    mutationFn: ({ id, data }: { id: string; data: Partial<CookieConsentData> }) =>
      consentService.updateConsent(id, data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['cookieConsent', visitorId] });
      setBannerHidden(true);
      toast.success('Preferences updated', {
        description: 'Your cookie settings have been updated.',
        duration: 3000,
      });
    },
    onError: () => {
      toast.error('Failed to update preferences', {
        description: 'Please try again.',
        duration: 3000,
      });
    },
  });

  const handleConsentChange = useCallback(
    async (consentData: CookieConsentData) => {
      setBannerHidden(true);

      if (consent?.id) {
        await updateConsentMutation.mutateAsync({
          id: consent.id.toString(),
          data: consentData,
        });
      } else {
        await saveConsentMutation.mutateAsync(consentData);
      }
    },
    [consent, saveConsentMutation, updateConsentMutation, setBannerHidden]
  );

  const handleAcceptAll = useCallback(async () => {
    await handleConsentChange({
      essential: true,
      analytics: true,
      marketing: true,
      preferences: true,
    });
  }, [handleConsentChange]);

  const handleRejectAll = useCallback(async () => {
    await handleConsentChange({
      essential: true,
      analytics: false,
      marketing: false,
      preferences: false,
    });
  }, [handleConsentChange]);

  useEffect(() => {
    if (consentLoading) {
      return;
    }

    if (consent) {
      setBannerHidden(true);
      return;
    }
    setBannerHidden(getInitialDismissedState());
  }, [consent, consentLoading, getInitialDismissedState, setBannerHidden]);

  useEffect(() => {
    window.addEventListener('openCookieConsentBanner', openConsentBanner);
    return () => {
      window.removeEventListener('openCookieConsentBanner', openConsentBanner);
    };
  }, [openConsentBanner]);

  return {
    visitorId,
    consent: consent as CookieConsent | null,
    consentLoading,
    consentBannerHidden,
    setConsentBannerHidden,
    dismissConsentBanner,
    handleConsentChange,
    openConsentBanner,
    handleAcceptAll,
    handleRejectAll,
    hasAnalyticsConsent: consent?.analytics || false,
    hasMarketingConsent: consent?.marketing || false,
  };
};

export const useVisitorPreferences = () => {
  const queryClient = useQueryClient();
  const visitorId = consentService.getVisitorId();

  // Fetch current preferences
  const { data: preferences, isLoading: prefsLoading } = useQuery({
    queryKey: ['visitorPreferences', visitorId],
    queryFn: () => consentService.getPreferences(visitorId),
    staleTime: 1000 * 60 * 30, // 30 minutes
  });

  // Save preferences mutation
  const savePreferencesMutation = useMutation({
    mutationFn: (data: VisitorPreferenceData) => consentService.savePreferences(data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['visitorPreferences', visitorId] });
      toast.success('Display preferences saved', {
        description: 'Your preferences have been updated.',
        duration: 3000,
      });
    },
    onError: () => {
      toast.error('Failed to save preferences', {
        description: 'Please try again.',
        duration: 3000,
      });
    },
  });

  // Update preferences mutation
  const updatePreferencesMutation = useMutation({
    mutationFn: ({ id, data }: { id: string; data: Partial<VisitorPreferenceData> }) =>
      consentService.updatePreferences(id, data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['visitorPreferences', visitorId] });
      toast.success('Display preferences updated', {
        description: 'Your settings have been saved.',
        duration: 3000,
      });
    },
    onError: () => {
      toast.error('Failed to update preferences', {
        description: 'Please try again.',
        duration: 3000,
      });
    },
  });

  const handlePreferencesChange = useCallback(
    async (prefsData: Partial<VisitorPreferenceData>) => {
      if (preferences?.id) {
        await updatePreferencesMutation.mutateAsync({
          id: preferences.id.toString(),
          data: prefsData,
        });
      } else {
        const defaultPrefs: VisitorPreferenceData = {
          theme: 'system',
          language: 'en',
          layout: 'standard',
        };
        await savePreferencesMutation.mutateAsync({
          ...defaultPrefs,
          ...prefsData,
        });
      }
    },
    [preferences, savePreferencesMutation, updatePreferencesMutation]
  );

  return {
    visitorId,
    preferences: preferences as VisitorPreference | null,
    prefsLoading,
    handlePreferencesChange,
  };
};
