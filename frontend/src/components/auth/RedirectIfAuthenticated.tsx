import React from 'react';
import { Navigate } from 'react-router-dom';
import { useAuth } from '@/hooks/useAuth';
import { getFilamentRedirectUrl } from '@/lib/dashboardRoutes';

interface RedirectIfAuthenticatedProps {
  children: React.ReactElement;
}

/**
 * Guard that prevents authenticated users from accessing auth pages (login, register, etc)
 * - If authenticated: redirects to dashboard/admin
 * - If not authenticated: shows the auth page
 * - No loading state shown since auth is checked on app mount, not on page load
 */
const RedirectIfAuthenticated: React.FC<RedirectIfAuthenticatedProps> = ({ children }) => {
  const { user, isAuthenticated, isLoading } = useAuth();
  
  // Determine if user is a Filament admin
  const isFilamentUser = isAuthenticated && user?.can_access_filament === true;
  const hasRedirectUrl = user?.redirect_to && user.redirect_to.startsWith('/admin');

  // Effect: Redirect authenticated Filament users to admin panel
  React.useEffect(() => {
    if (isFilamentUser) {
      const adminUrl = getFilamentRedirectUrl(user.redirect_to ?? '/admin');
      if (import.meta.env.DEV) {
        console.log('RedirectIfAuthenticated: Redirecting Filament user to', adminUrl);
      }
      window.location.assign(adminUrl);
    }
  }, [isFilamentUser, hasRedirectUrl, user?.redirect_to]);

  // If still loading auth state, show children (login page appears immediately)
  if (isLoading) {
    return children;
  }

  // If not authenticated at all, show the auth page
  if (!isAuthenticated || !user) {
    return children;
  }

  // If authenticated as Filament user, show redirect message
  if (isFilamentUser) {
    return (
      <div className="min-h-screen bg-background flex items-center justify-center">
        <div className="text-center">
          <div className="w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin mx-auto mb-4" />
          <p className="text-sm text-muted-foreground">Redirecting to admin panel…</p>
        </div>
      </div>
    );
  }

  // If authenticated as regular user, redirect to dashboard
  const redirectTo = '/dashboard';
  return <Navigate to={redirectTo} replace />;
};

export default RedirectIfAuthenticated;
