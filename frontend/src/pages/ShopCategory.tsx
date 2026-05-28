// src/pages/shop/ShopCategory.tsx

import React from "react";
import { useParams, Link } from "react-router-dom";
import { Helmet } from "react-helmet-async";
import { useQuery } from "@tanstack/react-query";
// ✅ 1. IMPORT THE NEW HOOK AND REMOVE UNUSED SERVICE
import { useAffiliateProductsByCategory } from "@/hooks/useAffiliateProducts";
import { jewelleryService, Category } from "@/services/jewelleryService";
import AffiliateProductCard from "@/components/shop/AffiliateProductCard";
import ProductCardSkeleton from "@/components/shop/ProductCardSkeleton";
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";
import { InfoIcon } from "lucide-react";

const ShopCategory: React.FC = () => {
  const { slug } = useParams<{ slug: string }>();

  // ✅ 2. USE THE NEW, CORRECTED HOOK
  const {
    data: productsResponse,
    isLoading,
    isError,
    error,
  } = useAffiliateProductsByCategory(slug);

  // Extract the products array from the paginated response
  const products = productsResponse?.data || [];

  // This part for fetching category details remains the same
  const { data: categories = [] } = useQuery<Category[]>({
    queryKey: ["categories"],
    queryFn: jewelleryService.getCategories,
  });

  const currentCategory = categories.find((cat) => cat.slug === slug);
  const categoryName =
    currentCategory?.name ||
    slug?.replace(/-/g, " ").replace(/\b\w/g, (l) => l.toUpperCase()) ||
    "Category";

  const categoryIntroText =
    currentCategory?.description ||
    `Our ${categoryName.toLowerCase()} edit brings together pieces chosen for effortless polish, thoughtful detail and reliable shine. It is designed to work as well for daily wear as for thoughtful gifting, with styles that layer beautifully and feel elevated without being overstated. Look for pieces that suit your personal palette and the occasion—whether it’s a quiet weekday, a dinner out, or a special celebration. Explore matching collections, size advice and styling ideas that make your choice feel clear and intentional.`;

  return (
    <>
      <Helmet>
        <title>{`${categoryName} | LatestFashionJewellery`}</title>
        <meta
          name="description"
          content={
            currentCategory?.description ||
            `Browse our selection of ${categoryName}.`
          }
        />
        <link
          rel="canonical"
          href={`${window.location.origin}/shop/category/${slug}`}
        />
      </Helmet>

      <main className="container mx-auto px-4 py-8 mt-24">
        <Breadcrumb className="mb-8">
          <BreadcrumbList>
            <BreadcrumbItem>
              <BreadcrumbLink asChild>
                <Link to="/">Home</Link>
              </BreadcrumbLink>
            </BreadcrumbItem>
            <BreadcrumbSeparator />
            <BreadcrumbItem>
              <BreadcrumbLink asChild>
                <Link to="/shop">Shop</Link>
              </BreadcrumbLink>
            </BreadcrumbItem>
            <BreadcrumbSeparator />
            <BreadcrumbItem>
              <BreadcrumbPage>{categoryName}</BreadcrumbPage>
            </BreadcrumbItem>
          </BreadcrumbList>
        </Breadcrumb>

        <header className="text-center mb-12">
          <h1 className="text-4xl lg:text-5xl font-playfair font-bold text-dark-slate">
            {categoryName}
          </h1>
          <div className="mt-6 max-w-3xl mx-auto">
            <p className="text-lg text-gray-600 leading-relaxed font-lato">
              {categoryIntroText}
            </p>
            <p className="mt-4 text-sm text-gray-500">
              This edit is built for people who want jewellery that feels considered, wearable and quietly distinctive.
            </p>
            <div className="mt-4 text-sm text-gray-500">
              We curate products from trusted Amazon partners, and checkout is completed on the retailer’s site for secure payment and shipping.
            </div>
            <div className="mt-6 flex flex-wrap justify-center gap-3">
              <Link to="/blog" className="text-sm font-medium text-primary hover:text-primary-700 transition-colors">
                Read style guides
              </Link>
              <Link to="/shop" className="text-sm font-medium text-primary hover:text-primary-700 transition-colors">
                Browse all collections
              </Link>
            </div>
          </div>
        </header>

        {isLoading ? (
          <div className="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            {Array.from({ length: 8 }).map((_, i) => (
              <ProductCardSkeleton key={`skel-${i}`} />
            ))}
          </div>
        ) : isError ? (
          <div className="text-center py-10 text-red-500 bg-red-50 p-6 rounded-lg">
            <h3 className="font-semibold text-lg mb-2">
              Oops! Something went wrong.
            </h3>
            <p>Error loading products: {(error as Error)?.message}</p>
          </div>  
        ) : products.length > 0 ? (
          <div className="grid grid-cols-1 xs:grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
            {products.map((product) => (
              <AffiliateProductCard
                key={product.asin || product.id}
                product={product}
              />
            ))}
          </div>
        ) : (
          <div className="text-center py-16 border border-dashed rounded-lg">
            <InfoIcon className="mx-auto h-12 w-12 text-gray-400 mb-4" />
            <h3 className="text-xl font-medium">No Products Found</h3>
            <p className="mt-1 text-sm text-gray-500">
              There are currently no products in the {categoryName} collection. Please check our broader curated categories below for similar pieces.
            </p>
          </div>
        )}

        <section className="mt-16 bg-gray-50 rounded-3xl border border-gray-200 p-8">
          <div className="max-w-4xl mx-auto">
            <h2 className="text-2xl font-semibold text-dark-slate mb-4">Need a quick answer?</h2>
            <div className="grid gap-6 md:grid-cols-3">
              <div>
                <p className="font-semibold text-gray-800 mb-2">How do I choose the right piece?</p>
                <p className="text-sm text-gray-600 leading-relaxed">
                  Focus on metal tone, scale and how the piece will layer with what you already wear.
                </p>
              </div>
              <div>
                <p className="font-semibold text-gray-800 mb-2">What does affiliate mean?</p>
                <p className="text-sm text-gray-600 leading-relaxed">
                  We earn a small commission when you buy through our links, at no extra cost to you.
                </p>
              </div>
              <div>
                <p className="font-semibold text-gray-800 mb-2">Can I return items?</p>
                <p className="text-sm text-gray-600 leading-relaxed">
                  Yes. Returns are handled by the retailer, so please review their policy during checkout.
                </p>
              </div>
            </div>
          </div>
        </section>
      </main>
    </>
  );
};

export default ShopCategory;
