import React from "react";
import { Link } from "react-router-dom";
import { ArrowRight } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Skeleton } from "@/components/ui/skeleton";
import AffiliateProductCard from "@/components/shop/AffiliateProductCard";
import { AffiliateProduct } from "@/services/affiliateProductService";
import { useAffiliateProducts, useFeaturedAffiliateProducts } from "@/hooks/useAffiliateProducts";

interface ContextualProductRailProps {
  title: string;
  description?: string;
  products?: AffiliateProduct[];
  categoryId?: number;
  limit?: number;
  placement: string;
  viewAllHref?: string;
  tone?: "light" | "editorial";
}

const ContextualProductRail: React.FC<ContextualProductRailProps> = ({
  title,
  description,
  products,
  categoryId,
  limit = 4,
  placement,
  viewAllHref = "/shop",
  tone = "light",
}) => {
  const shouldFetchByCategory = !products?.length && !!categoryId;
  const shouldFetchFeatured = !products?.length && !categoryId;

  const categoryQuery = useAffiliateProducts(
    { category: categoryId, perPage: limit },
    { enabled: shouldFetchByCategory }
  );
  const featuredQuery = useFeaturedAffiliateProducts(limit, placement, {
    enabled: shouldFetchFeatured,
  });

  const resolvedProducts =
    products?.slice(0, limit) ||
    categoryQuery.data?.data?.slice(0, limit) ||
    featuredQuery.data?.slice(0, limit) ||
    [];

  const isLoading = categoryQuery.isLoading || featuredQuery.isLoading;
  const sectionTone =
    tone === "editorial"
      ? "bg-soft-cream border-y border-primary-gold/10"
      : "bg-white";

  if (!isLoading && resolvedProducts.length === 0) return null;

  return (
    <section className={`py-12 md:py-14 ${sectionTone}`} data-placement={placement}>
      <div className="container mx-auto px-4 md:px-6">
        <div className="flex flex-col gap-4 md:flex-row md:items-end md:justify-between mb-8">
          <div className="max-w-2xl">
            <p className="text-xs font-semibold uppercase text-primary-gold mb-2">
              Curated picks
            </p>
            <h2 className="text-2xl md:text-3xl font-playfair font-bold text-dark-slate">
              {title}
            </h2>
            {description && (
              <p className="mt-2 text-sm md:text-base text-muted-foreground leading-relaxed">
                {description}
              </p>
            )}
          </div>
          <Button asChild variant="outline" className="self-start md:self-auto">
            <Link to={viewAllHref}>
              Explore more <ArrowRight className="ml-2 h-4 w-4" />
            </Link>
          </Button>
        </div>

        {isLoading ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            {Array.from({ length: limit }).map((_, index) => (
              <Skeleton key={index} className="h-80 rounded-lg" />
            ))}
          </div>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            {resolvedProducts.map((product) => (
              <AffiliateProductCard
                key={product.asin || product.id}
                product={product}
                placement={placement}
              />
            ))}
          </div>
        )}
      </div>
    </section>
  );
};

export default ContextualProductRail;
