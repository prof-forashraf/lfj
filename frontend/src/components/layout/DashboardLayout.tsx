
// src/components/layout/DashboardLayout.tsx
import React, { useState, ReactNode } from 'react';
import { Outlet } from 'react-router-dom';
import { useAuth } from '@/hooks/useAuth';
import { Button } from '@/components/ui/button';
import { LogOut, Menu } from 'lucide-react';
import DashboardSidebar from '@/components/layout/DashboardSidebar';

interface DashboardLayoutProps {
  children?: ReactNode;
}

const DashboardLayout: React.FC<DashboardLayoutProps> = ({ children }) => {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const { user, logout, isLoading } = useAuth();

  return (
    <div className="min-h-screen bg-slate-50 text-slate-900 flex">
      <DashboardSidebar isOpen={sidebarOpen} setIsOpen={setSidebarOpen} />

      <div className="flex-1 min-h-screen flex flex-col transition-all duration-300">
        <header className="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur-sm">
          <div className="max-w-7xl mx-auto flex items-center justify-between gap-4 px-4 py-4 sm:px-6">
            <div className="flex items-center gap-3">
              <Button
                variant="ghost"
                size="icon"
                className="lg:hidden"
                onClick={() => setSidebarOpen(!sidebarOpen)}
              >
                <Menu className="h-5 w-5" />
              </Button>
              <div>
                <p className="text-sm text-muted-foreground">Welcome back</p>
                <h1 className="text-lg font-semibold">{user?.name ?? 'Dashboard'}</h1>
              </div>
            </div>

            <div className="flex items-center gap-3">
              <div className="hidden sm:flex flex-col text-right">
                <span className="text-sm font-medium">{user?.name}</span>
                <span className="text-xs text-muted-foreground">{user?.roles?.join(', ') || 'Member'}</span>
              </div>
              <Button
                variant="secondary"
                size="sm"
                className="inline-flex items-center gap-2"
                onClick={logout}
                disabled={isLoading}
              >
                <LogOut className="h-4 w-4" />
                Logout
              </Button>
            </div>
          </div>
        </header>

        <main className="flex-1 p-4 sm:p-6 lg:p-8">
          {children || <Outlet />}
        </main>

        <footer className="border-t border-slate-200 bg-white py-4">
          <div className="max-w-7xl mx-auto px-4 text-center text-sm text-slate-500 sm:px-6">
            © {new Date().getFullYear()} Latest Fashion Jewellery. All rights reserved.
          </div>
        </footer>
      </div>
    </div>
  );
};

export default DashboardLayout;
