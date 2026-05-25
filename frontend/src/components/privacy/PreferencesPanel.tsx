import { useEffect, useState } from 'react';
import { useVisitorPreferences } from '@/hooks/useConsent';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Settings, Moon, Sun, Globe, Layout } from 'lucide-react';

export const PreferencesPanel = () => {
  const { preferences, prefsLoading, handlePreferencesChange } = useVisitorPreferences();

  const [localTheme, setLocalTheme] = useState<'light' | 'dark' | 'system'>(
    preferences?.theme || 'system'
  );
  const [localLanguage, setLocalLanguage] = useState(preferences?.language || 'en');
  const [localLayout, setLocalLayout] = useState<'standard' | 'compact' | 'comfortable'>(
    preferences?.layout || 'standard'
  );

  useEffect(() => {
    if (preferences) {
      if (preferences.theme) setLocalTheme(preferences.theme);
      if (preferences.language) setLocalLanguage(preferences.language);
      if (preferences.layout) setLocalLayout(preferences.layout);
    }
  }, [preferences]);
  const [isSaving, setIsSaving] = useState(false);

  const handleSaveDisplay = async () => {
    setIsSaving(true);
    try {
      await handlePreferencesChange({
        theme: localTheme,
        language: localLanguage,
        layout: localLayout,
      });
    } catch (error) {
      console.error('Failed to save preferences:', error);
    } finally {
      setIsSaving(false);
    }
  };

  if (prefsLoading) {
    return (
      <Card className="p-6">
        <div className="flex items-center justify-center py-8">
          <div className="text-sm text-slate-500">Loading preferences...</div>
        </div>
      </Card>
    );
  }

  return (
    <div className="space-y-6">
      <Card className="p-6">
        <div className="mb-4 flex items-center gap-2">
          <Settings className="h-5 w-5 text-slate-600 dark:text-slate-400" />
          <h2 className="text-xl font-semibold text-slate-900 dark:text-white">
            Preferences
          </h2>
        </div>

        <Tabs defaultValue="display" className="w-full">
          <TabsList className="grid w-full grid-cols-3">
            <TabsTrigger value="display">Display</TabsTrigger>
            <TabsTrigger value="content">Content</TabsTrigger>
            <TabsTrigger value="privacy">Privacy</TabsTrigger>
          </TabsList>

          {/* Display Preferences */}
          <TabsContent value="display" className="space-y-4 pt-4">
            {/* Theme Selection */}
            <div className="space-y-2">
              <label className="text-sm font-medium text-slate-900 dark:text-white">
                Theme
              </label>
              <div className="grid grid-cols-3 gap-2">
                {[
                  { value: 'light' as const, label: 'Light', icon: Sun },
                  { value: 'dark' as const, label: 'Dark', icon: Moon },
                  { value: 'system' as const, label: 'System', icon: Settings },
                ].map(({ value, label, icon: Icon }) => (
                  <button
                    key={value}
                    onClick={() => setLocalTheme(value)}
                    className={`flex flex-col items-center gap-2 rounded-lg border-2 p-3 transition-all ${
                      localTheme === value
                        ? 'border-blue-600 bg-blue-50 dark:border-blue-500 dark:bg-blue-900/20'
                        : 'border-slate-200 bg-slate-50 hover:border-slate-300 dark:border-slate-700 dark:bg-slate-800'
                    }`}
                  >
                    <Icon className="h-5 w-5" />
                    <span className="text-xs font-medium">{label}</span>
                  </button>
                ))}
              </div>
            </div>

            {/* Language Selection */}
            <div className="space-y-2">
              <label htmlFor="language" className="text-sm font-medium text-slate-900 dark:text-white">
                Language
              </label>
              <select
                id="language"
                value={localLanguage}
                onChange={(e) => setLocalLanguage(e.target.value)}
                className="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
              >
                <option value="en">English</option>
                <option value="es">Español</option>
                <option value="fr">Français</option>
                <option value="de">Deutsch</option>
              </select>
            </div>

            {/* Layout Selection */}
            <div className="space-y-2">
              <label className="text-sm font-medium text-slate-900 dark:text-white">
                Layout Density
              </label>
              <div className="grid grid-cols-3 gap-2">
                {[
                  { value: 'compact' as const, label: 'Compact' },
                  { value: 'standard' as const, label: 'Standard' },
                  { value: 'comfortable' as const, label: 'Comfortable' },
                ].map(({ value, label }) => (
                  <button
                    key={value}
                    onClick={() => setLocalLayout(value)}
                    className={`rounded-lg border-2 p-3 text-sm font-medium transition-all ${
                      localLayout === value
                        ? 'border-blue-600 bg-blue-50 text-blue-900 dark:border-blue-500 dark:bg-blue-900/20 dark:text-blue-200'
                        : 'border-slate-200 bg-slate-50 text-slate-700 hover:border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300'
                    }`}
                  >
                    <Layout className="mb-1 h-4 w-4" />
                    {label}
                  </button>
                ))}
              </div>
            </div>

            <Button
              onClick={handleSaveDisplay}
              disabled={isSaving}
              className="w-full bg-blue-600 text-white hover:bg-blue-700"
            >
              {isSaving ? 'Saving...' : 'Save Display Preferences'}
            </Button>
          </TabsContent>

          {/* Content Preferences */}
          <TabsContent value="content" className="space-y-4 pt-4">
            <Alert>
              <AlertDescription>
                Your favorite categories and tags will help us personalize content recommendations.
                These are automatically updated as you browse.
              </AlertDescription>
            </Alert>

            {preferences?.favorite_categories && preferences.favorite_categories.length > 0 && (
              <div className="space-y-2">
                <h4 className="text-sm font-medium text-slate-900 dark:text-white">
                  Favorite Categories
                </h4>
                <div className="flex flex-wrap gap-2">
                  {preferences.favorite_categories.map((cat) => (
                    <span
                      key={cat}
                      className="rounded-full bg-blue-100 px-3 py-1 text-sm text-blue-700 dark:bg-blue-900/30 dark:text-blue-200"
                    >
                      {cat}
                    </span>
                  ))}
                </div>
              </div>
            )}

            {preferences?.favorite_tags && preferences.favorite_tags.length > 0 && (
              <div className="space-y-2">
                <h4 className="text-sm font-medium text-slate-900 dark:text-white">
                  Favorite Tags
                </h4>
                <div className="flex flex-wrap gap-2">
                  {preferences.favorite_tags.map((tag) => (
                    <span
                      key={tag}
                      className="rounded-full bg-slate-200 px-3 py-1 text-sm text-slate-700 dark:bg-slate-700 dark:text-slate-200"
                    >
                      #{tag}
                    </span>
                  ))}
                </div>
              </div>
            )}

            {(!preferences?.favorite_categories || preferences.favorite_categories.length === 0) &&
              (!preferences?.favorite_tags || preferences.favorite_tags.length === 0) && (
                <p className="text-sm text-slate-500 dark:text-slate-400">
                  No preferences recorded yet. Browse content to build your profile.
                </p>
              )}
          </TabsContent>

          {/* Privacy Preferences */}
          <TabsContent value="privacy" className="space-y-4 pt-4">
            <Alert>
              <Globe className="h-4 w-4" />
              <AlertDescription>
                Privacy settings are managed from the Cookie & Consent banner. Click the settings
                icon at the bottom of the page to adjust your cookie preferences.
              </AlertDescription>
            </Alert>
          </TabsContent>
        </Tabs>
      </Card>
    </div>
  );
};

export default PreferencesPanel;
