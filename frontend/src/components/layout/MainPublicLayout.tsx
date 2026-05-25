
import React from 'react';
import { Outlet } from 'react-router-dom';
import Header from '@/components/landing/Header';
import Footer from '@/components/landing/Footer';
import PublicRouteSeo from '@/components/seo/PublicRouteSeo';

const MainPublicLayout: React.FC = () => {
  return (
    <div className="flex flex-col min-h-screen bg-background">
      <PublicRouteSeo />
      <Header />
      <main className="flex-1">
        <Outlet />
      </main>
      <Footer />
    </div>
  );
};

export default MainPublicLayout;
