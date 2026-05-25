import React from "react";
import { motion } from "framer-motion";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { AffiliateProduct } from "@/services/affiliateProductService";
import { getPlaceholderImageUrl } from '@/lib/imageUrl';
import { Star, ExternalLink } from "lucide-react";

interface ProductCardProps {
  product: AffiliateProduct;
}

const ProductCard: React.FC<ProductCardProps> = ({ product }) => {
  const hasMultipleSizes =
    product.image_urls && product.image_urls.small && product.image_urls.medium;

  return (
    <motion.div
      className="bg-white rounded-xl overflow-hidden group shadow-sm hover:shadow-lg transition-shadow duration-300 h-full flex flex-col"
      initial={{ opacity: 0, y: 30 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true }}
      transition={{ duration: 0.5 }}
    >
      <a
        href={product.affiliate_url || "#"}
        target="_blank"
        rel="noopener sponsored"
        className="block"
      >
        <div className="relative aspect-square overflow-hidden bg-gray-50">
          {hasMultipleSizes ? (
            <picture>
              <source
                media="(min-width: 768px)"
                srcSet={product.image_urls.medium!}
                type="image/webp"
              />
              <source srcSet={product.image_urls.small!} type="image/webp" />
              <img
                src={product.image_urls.medium!}
                alt={product.name}
                className="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500 p-6"
                loading="lazy"
               width={800} height={600} onError={(event) => { event.currentTarget.src = "/images/placeholder.svg"; }} />
            </picture>
          ) : (
            <img
              src={product.image_url || getPlaceholderImageUrl()}
              alt={product.name}
              className="w-full h-full object-contain group-hover:scale-105 transition-transform duration-500 p-6"
              loading="lazy"
             width={800} height={600} onError={(event) => { event.currentTarget.src = "/images/placeholder.svg"; }} />
          )}
        </div>
      </a>
      <div className="p-4 text-center flex-grow flex flex-col justify-between">
        <div>
          <h4 className="text-dark-slate font-playfair text-lg mb-2 line-clamp-2 h-14">
            <a
              href={product.affiliate_url || "#"}
              target="_blank"
              rel="noopener sponsored"
              className="hover:text-primary-gold"
            >
              {product.name}
            </a>
          </h4>
          {product.rating && (
            <div className="flex items-center justify-center space-x-1 mb-3">
              {Array.from({ length: 5 }, (_, i) => (
                <Star
                  key={i}
                  className={`w-4 h-4 ${
                    i < Math.floor(product.rating ?? 0)
                      ? "text-yellow-400 fill-current"
                      : "text-gray-300"
                  }`}
                />
              ))}
              <span className="text-sm text-gray-500 ml-2">
                ({product.rating.toFixed(1)})
              </span>
            </div>
          )}
        </div>
        <Button
          asChild
          className="w-full mt-3 bg-primary-gold text-white font-semibold hover:bg-primary-gold/90"
        >
          <a
            href={product.affiliate_url || "#"}
            target="_blank"
            rel="noopener sponsored"
          >
            View Product <ExternalLink className="w-4 h-4 ml-2" />
          </a>
        </Button>
      </div>
    </motion.div>
  );
};

export default ProductCard;
