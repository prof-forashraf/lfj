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
          {currentCategory?.description && (
            <p className="mt-3 text-lg text-gray-600 max-w-2xl mx-auto font-lato">
              {currentCategory.description}
            </p>
          )}
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
              There are currently no products in the {categoryName} collection.
            </p>
          </div>
        )}
      </main>
    </>
  );
};

export default ShopCategory;
