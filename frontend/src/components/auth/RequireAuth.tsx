import React from 'react';
import { Navigate, useLocation } from 'react-router-dom';
import { useAuth } from '@/hooks/useAuth';
import { getFilamentRedirectUrl } from '@/lib/dashboardRoutes';

interface RequireAuthProps {
  children: React.ReactElement;
}

/**
 * Guard that protects authenticated routes
 * - If admin: redirects to /admin (Filament dashboard)
 * - If not authenticated: redirects to login
 * - If authenticated user with non-admin role: shows protected content
 */
const RequireAuth: React.FC<RequireAuthProps> = ({ children }) => {
  const { user, isLoading, isAuthenticated } = useAuth();
  const location = useLocation();
  
  // Determine if user should access Filament admin panel
  const isFilamentUser = !isLoading && isAuthenticated && user?.can_access_filament === true;
  const hasRedirectUrl = user?.redirect_to && user.redirect_to.startsWith('/admin');

  // Effect: Redirect Filament users to admin panel
  React.useEffect(() => {
    if (isFilamentUser) {
      const adminUrl = getFilamentRedirectUrl(user.redirect_to ?? '/admin');
      if (import.meta.env.DEV) {
        console.log('RequireAuth: Redirecting Filament user to', adminUrl);
      }
      // Use window.location to ensure full-page redirect to backend
      window.location.assign(adminUrl);
    }
  }, [isFilamentUser, hasRedirectUrl, user?.redirect_to]);

  // Show loading state while checking authentication
  if (isLoading) {
    return (
      <div className="min-h-screen bg-background flex items-center justify-center">
        <div className="text-center">
          <div className="w-8 h-8 border-4 border-primary border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
          <div className="text-sm text-muted-foreground">Verifying credentials…</div>
        </div>
      </div>
    );
  }

  // Not authenticated: redirect to login
  if (!isAuthenticated) {
    return <Navigate to="/login" state={{ from: location }} replace />;
  }

  // Filament user: show redirect message (actual redirect handled by effect)
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

  // Regular authenticated user: show protected content
  return children;
};

export default RequireAuth;
