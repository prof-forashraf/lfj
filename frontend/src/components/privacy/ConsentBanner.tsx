import { useState, useEffect } from 'react';
import { useCookieConsent } from '@/hooks/useConsent';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { CheckCircle2, Settings, X } from 'lucide-react';

export const ConsentBanner = () => {
  const {
    consent,
    consentLoading,
    consentBannerHidden,
    dismissConsentBanner,
    handleAcceptAll,
    handleRejectAll,
    handleConsentChange,
  } = useCookieConsent();

  const [showDetails, setShowDetails] = useState(false);
  const [selectedConsents, setSelectedConsents] = useState({
    essential: true,
    analytics: false,
    marketing: false,
    preferences: false,
  });

  useEffect(() => {
    if (!consent) {
      setSelectedConsents((current) => ({
        ...current,
        analytics: false,
        marketing: false,
        preferences: false,
      }));
      return;
    }

    setSelectedConsents({
      essential: true,
      analytics: consent.analytics ?? false,
      marketing: consent.marketing ?? false,
      preferences: consent.preferences ?? false,
    });
  }, [consent]);

  // Don't show banner if consent already exists or if hidden for the session
  if (consentLoading || consent || consentBannerHidden) {
    return null;
  }

  const handleAcceptSelected = async () => {
    await handleAcceptAll();
  };

  const handleRejectSelected = async () => {
    await handleRejectAll();
  };

  const handleCustomize = async () => {
    const consentData = {
      essential: true,
      analytics: selectedConsents.analytics,
      marketing: selectedConsents.marketing,
      preferences: selectedConsents.preferences,
    };

    dismissConsentBanner();

    try {
      await handleConsentChange(consentData);
    } catch (error) {
      console.error('Failed to save custom consent:', error);
    }
  };

  return (
    <div className="fixed bottom-0 left-0 right-0 z-50">
      <div className="animate-slide-up">
        <Card className="mx-4 mb-4 border-l-4 border-l-blue-500 bg-white shadow-lg dark:bg-slate-900">
          <div className="p-6">
            {!showDetails ? (
              <>
                {/* Main Consent Message */}
                <div className="flex items-start justify-between gap-4">
                  <div className="flex-1">
                    <h3 className="mb-2 font-semibold text-lg text-slate-900 dark:text-white">
                      Cookie & Privacy Settings
                    </h3>
                    <p className="text-sm text-slate-600 dark:text-slate-300">
                      We use cookies and similar technologies to improve your experience, personalize
                      content, and analyze traffic. By clicking "Accept All", you consent to our use
                      of cookies. You can customize your preferences below.
                    </p>
                  </div>
                  <button
                    onClick={dismissConsentBanner}
                    className="flex-shrink-0 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300"
                  >
                    <X className="h-5 w-5" />
                  </button>
                </div>

                {/* Action Buttons */}
                <div className="mt-4 flex flex-wrap gap-3">
                  <Button
                    onClick={handleAcceptSelected}
                    className="bg-blue-600 text-white hover:bg-blue-700"
                  >
                    <CheckCircle2 className="mr-2 h-4 w-4" />
                    Accept All
                  </Button>
                  <Button
                    onClick={handleRejectSelected}
                    variant="outline"
                    className="border-slate-300 text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-800"
                  >
                    Reject All
                  </Button>
                  <Button
                    onClick={() => setShowDetails(true)}
                    variant="ghost"
                    className="text-slate-600 hover:text-blue-600 dark:text-slate-400"
                  >
                    <Settings className="mr-2 h-4 w-4" />
                    Customize
                  </Button>
                </div>
              </>
            ) : (
              <>
                {/* Detailed Consent Settings */}
                <div className="mb-4">
                  <h4 className="mb-4 font-semibold text-slate-900 dark:text-white">
                    Cookie Preferences
                  </h4>

                  {/* Essential Cookies */}
                  <div className="mb-4 rounded-lg border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800">
                    <div className="flex items-center justify-between">
                      <div>
                        <h5 className="font-medium text-slate-900 dark:text-white">
                          Essential Cookies
                        </h5>
                        <p className="text-xs text-slate-600 dark:text-slate-400">
                          Required for site functionality. Always enabled.
                        </p>
                      </div>
                      <div className="flex h-6 w-11 items-center rounded-full bg-green-500">
                        <div className="ml-1 h-5 w-5 rounded-full bg-white" />
                      </div>
                    </div>
                  </div>

                  {/* Analytics Cookies */}
                  <div className="mb-4 rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                    <div className="flex items-center justify-between">
                      <div>
                        <h5 className="font-medium text-slate-900 dark:text-white">
                          Analytics Cookies
                        </h5>
                        <p className="text-xs text-slate-600 dark:text-slate-400">
                          Help us understand how you use our site to improve it.
                        </p>
                      </div>
                      <button
                        onClick={() =>
                          setSelectedConsents({
                            ...selectedConsents,
                            analytics: !selectedConsents.analytics,
                          })
                        }
                        className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors ${
                          selectedConsents.analytics ? 'bg-blue-600' : 'bg-slate-300'
                        }`}
                      >
                        <span
                          className={`inline-block h-5 w-5 transform rounded-full bg-white transition-transform ${
                            selectedConsents.analytics ? 'translate-x-5' : 'translate-x-1'
                          }`}
                        />
                      </button>
                    </div>
                  </div>

                  {/* Marketing Cookies */}
                  <div className="mb-4 rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                    <div className="flex items-center justify-between">
                      <div>
                        <h5 className="font-medium text-slate-900 dark:text-white">
                          Marketing Cookies
                        </h5>
                        <p className="text-xs text-slate-600 dark:text-slate-400">
                          Used for targeted advertising and marketing campaigns.
                        </p>
                      </div>
                      <button
                        onClick={() =>
                          setSelectedConsents({
                            ...selectedConsents,
                            marketing: !selectedConsents.marketing,
                          })
                        }
                        className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors ${
                          selectedConsents.marketing ? 'bg-blue-600' : 'bg-slate-300'
                        }`}
                      >
                        <span
                          className={`inline-block h-5 w-5 transform rounded-full bg-white transition-transform ${
                            selectedConsents.marketing ? 'translate-x-5' : 'translate-x-1'
                          }`}
                        />
                      </button>
                    </div>
                  </div>

                  {/* Preference Cookies */}
                  <div className="mb-4 rounded-lg border border-slate-200 p-4 dark:border-slate-700">
                    <div className="flex items-center justify-between">
                      <div>
                        <h5 className="font-medium text-slate-900 dark:text-white">
                          Preference Cookies
                        </h5>
                        <p className="text-xs text-slate-600 dark:text-slate-400">
                          Remember your preferences to personalize your experience.
                        </p>
                      </div>
                      <button
                        onClick={() =>
                          setSelectedConsents({
                            ...selectedConsents,
                            preferences: !selectedConsents.preferences,
                          })
                        }
                        className={`relative inline-flex h-6 w-11 items-center rounded-full transition-colors ${
                          selectedConsents.preferences ? 'bg-blue-600' : 'bg-slate-300'
                        }`}
                      >
                        <span
                          className={`inline-block h-5 w-5 transform rounded-full bg-white transition-transform ${
                            selectedConsents.preferences ? 'translate-x-5' : 'translate-x-1'
                          }`}
                        />
                      </button>
                    </div>
                  </div>
                </div>

                {/* Action Buttons */}
                <div className="flex gap-3">
                  <Button
                    onClick={handleCustomize}
                    className="flex-1 bg-blue-600 text-white hover:bg-blue-700"
                  >
                    Save Preferences
                  </Button>
                  <Button
                    onClick={() => setShowDetails(false)}
                    variant="outline"
                    className="border-slate-300 text-slate-700 hover:bg-slate-50 dark:border-slate-600 dark:text-slate-300"
                  >
                    Back
                  </Button>
                </div>
              </>
            )}
          </div>
        </Card>
      </div>
    </div>
  );
};

export default ConsentBanner;
