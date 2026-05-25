import React, { Suspense } from "react";
import { Routes, Route } from "react-router-dom"; // Outlet is now used within layout components

import { Toaster as ShadcnToaster } from "@/components/ui/toaster";
import { Toaster as SonnerToaster } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import MainPublicLayout from "@/components/layout/MainPublicLayout";
import DashboardLayout from "@/components/layout/DashboardLayout";
import RequireAuth from "@/components/auth/RequireAuth";
import RedirectIfAuthenticated from "@/components/auth/RedirectIfAuthenticated";
import { ConsentBanner } from "@/components/privacy/ConsentBanner";

const Landing = React.lazy(() => import("./pages/Landing"));
const Shop = React.lazy(() => import("./pages/Shop"));
const ShopCollection = React.lazy(() => import("./pages/ShopCollection"));
const ShopCategory = React.lazy(() => import("./pages/ShopCategory"));
const NewArrivals = React.lazy(() => import("./pages/NewArrivals"));
const About = React.lazy(() => import("./pages/About"));
const Contact = React.lazy(() => import("./pages/Contact"));
const EventsPage = React.lazy(() => import("./pages/EventsPage"));
const EventDetailPage = React.lazy(() => import("./pages/EventDetailPage"));
const Terms = React.lazy(() => import("./pages/Terms"));
const Privacy = React.lazy(() => import("./pages/Privacy"));
const Shipping = React.lazy(() => import("./pages/Shipping"));
const JewelleryStudioPage = React.lazy(() => import("./pages/JewelleryStudioPage"));
const JewelleryVideosPage = React.lazy(() => import("./pages/JewelleryVideosPage"));
const BlogHome = React.lazy(() => import("./pages/BlogHome"));
const BlogPostPage = React.lazy(() => import("./pages/BlogPost"));
const NotFound = React.lazy(() => import("./pages/NotFound"));
const Quality = React.lazy(() => import("./pages/Quality"));
const Trends = React.lazy(() => import("./pages/Trends"));
const CategoryArchivePage = React.lazy(() => import("./pages/CategoryArchivePage"));
const TagArchivePage = React.lazy(() => import("./pages/TagArchivePage"));
const Tools = React.lazy(() => import("./pages/Tools"));
const AdvancedSearchPage = React.lazy(() => import("./pages/AdvancedSearchPage"));
const VirtualTryOnPage = React.lazy(() => import("./pages/VirtualTryOn"));
const GoldPrices = React.lazy(() => import("./pages/GoldPrices"));
const CaratConverter = React.lazy(() => import("./pages/CaratConverter"));
const RingSizeConverter = React.lazy(() => import("./pages/RingSizeConverter"));
const GoldResaleCalculator = React.lazy(() => import("./pages/GoldResaleCalculator"));
const DiamondEstimator = React.lazy(() => import("./pages/DiamondEstimator"));
const CustomCostEstimator = React.lazy(() => import("./pages/CustomCostEstimator"));
const ZakatCalculator = React.lazy(() => import("./pages/ZakatCalculator"));
const JewelleryCareGuide = React.lazy(() => import("./pages/JewelleryCareGuide"));
const Dashboard = React.lazy(() => import("./pages/Dashboard"));
const WondersOfGold = React.lazy(() => import("./pages/WondersOfGold"));
const CosmicOrigins = React.lazy(() => import("./pages/CosmicOrigins"));
const ModernTechnology = React.lazy(() => import("./pages/ModernTechnology"));
const FutureFrontiers = React.lazy(() => import("./pages/FutureFrontiers"));
const Wishlist = React.lazy(() => import("./pages/Wishlist"));
const Login = React.lazy(() => import("./pages/Login"));
const Register = React.lazy(() => import("./pages/Register"));
const ForgotPassword = React.lazy(() => import("./pages/ForgotPassword"));

const App: React.FC = () => {
  return (
    <TooltipProvider delayDuration={0}>
      <ShadcnToaster />
      <SonnerToaster />
      <Suspense fallback={<div className="min-h-screen bg-background" />}>
      <Routes>
        {/* Public Jewelry Store routes - now use the MainPublicLayout */}
        <Route element={<MainPublicLayout />}>
          {" "}
          {/* Correctly uses imported layout */}
          <Route path="/" element={<Landing />} />
          <Route
            path="/category/:categorySlug"
            element={<CategoryArchivePage />}
          />
          <Route path="/tag/:tagSlug" element={<TagArchivePage />} />
          <Route path="/tools/gold-prices" element={<GoldPrices />} />
          <Route path="/shop" element={<Shop />} />
          <Route path="/shop/collection/:slug" element={<ShopCollection />} />
          <Route path="/shop/category/:slug" element={<ShopCategory />} />
          <Route
            path="/shop/collection/new-arrivals"
            element={<NewArrivals />}
          />
          <Route path="/about" element={<About />} />
          <Route path="/contact" element={<Contact />} />
          <Route path="/events" element={<EventsPage />} />
          <Route path="/events/:eventSlug" element={<EventDetailPage />} />{" "}
          {/* Changed :id to :eventSlug */}
          <Route path="/terms" element={<Terms />} />
          <Route path="/privacy" element={<Privacy />} />
          <Route path="/shipping" element={<Shipping />} />
          {/* --- THIS IS THE FIX: Use the correctly imported component in the route definition --- */}
          <Route path="/jewellery-studio" element={<JewelleryStudioPage />} />
          <Route path="/videos" element={<JewelleryVideosPage />} />
          <Route path="/blog" element={<BlogHome />} />
          <Route path="/blog/:slug" element={<BlogPostPage />} />
          <Route path="/quality" element={<Quality />} />
          <Route path="/trends" element={<Trends />} />
          <Route path="/tools" element={<Tools />} />
          <Route path="/tools/virtual-try-on" element={<VirtualTryOnPage />} />
          <Route path="/shop/advanced-search" element={<AdvancedSearchPage />} />
          <Route path="/tools/carat-converter" element={<CaratConverter />} />
          <Route
            path="/tools/ring-size-converter"
            element={<RingSizeConverter />}
          />
          <Route
            path="/tools/gold-resale-calculator"
            element={<GoldResaleCalculator />}
          />
          <Route
            path="/tools/diamond-estimator"
            element={<DiamondEstimator />}
          />
          <Route
            path="/tools/custom-cost-estimator"
            element={<CustomCostEstimator />}
          />
          <Route path="/tools/zakat-calculator" element={<ZakatCalculator />} />
          <Route path="/tools/care-guide" element={<JewelleryCareGuide />} />
          <Route path="/wonders-of-gold" element={<WondersOfGold />} />
          <Route path="/cosmic-origins" element={<CosmicOrigins />} />
          <Route path="/modern-technology" element={<ModernTechnology />} />
          <Route path="/future-frontiers" element={<FutureFrontiers />} />
          <Route path="/wishlist" element={<Wishlist />} />
          <Route
            path="/login"
            element={
              <RedirectIfAuthenticated>
                <Login />
              </RedirectIfAuthenticated>
            }
          />
          <Route
            path="/register"
            element={
              <RedirectIfAuthenticated>
                <Register />
              </RedirectIfAuthenticated>
            }
          />
          <Route
            path="/forgot-password"
            element={
              <RedirectIfAuthenticated>
                <ForgotPassword />
              </RedirectIfAuthenticated>
            }
          />
          <Route path="*" element={<NotFound />} />{" "}
          {/* Catch-all within main layout */}
        </Route>

        {/* Dashboard routes */}
        <Route
          path="/dashboard"
          element={
            <RequireAuth>
              <DashboardLayout />
            </RequireAuth>
          }
        >
          <Route index element={<Dashboard />} />
        </Route>
      </Routes>
      </Suspense>
      <ConsentBanner />
    </TooltipProvider>
  );
};

export default App;
