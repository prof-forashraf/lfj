
import React from 'react';
import {
  Carousel,
  CarouselContent,
  CarouselItem,
  CarouselNext,
  CarouselPrevious,
} from "@/components/ui/carousel";
import { Card, CardContent } from "@/components/ui/card";
import { cn } from "@/lib/utils";

interface StandardCarouselProps {
  items: Array<{
    id: string | number;
    image: string;
    title: string;
    description?: string;
    price?: number;
    badge?: string;
    onClick?: () => void;
  }>;
  className?: string;
  itemClassName?: string;
  theme?: 'light' | 'dark' | 'elegant';
  showPricing?: boolean;
  autoPlay?: boolean;
}

const StandardCarousel: React.FC<StandardCarouselProps> = ({
  items,
  className,
  itemClassName,
  theme = 'light',
  showPricing = true,
  autoPlay = false
}) => {
  const themeStyles = {
    light: {
      container: "bg-white",
      card: "bg-white border-gray-200 hover:shadow-lg",
      title: "text-navy-800",
      description: "text-gray-600",
      price: "text-navy-800"
    },
    dark: {
      container: "bg-navy-900",
      card: "bg-navy-800 border-navy-700 hover:shadow-xl",
      title: "text-white",
      description: "text-gray-300",
      price: "text-teal-400"
    },
    elegant: {
      container: "bg-gradient-to-br from-gray-50 to-gray-100",
      card: "bg-white/80 backdrop-blur-sm border-gray-200/50 hover:shadow-xl hover:bg-white",
      title: "text-navy-800",
      description: "text-gray-600",
      price: "text-teal-600"
    }
  };

  const currentTheme = themeStyles[theme];

  const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-US', {
      style: 'currency',
      currency: 'USD',
      minimumFractionDigits: 2
    }).format(price);
  };

  return (
    <div className={cn("w-full py-8", currentTheme.container, className)}>
      <Carousel
        opts={{
          align: "start",
          loop: autoPlay,
        }}
        className="w-full"
      >
        <CarouselContent className="-ml-2 md:-ml-4">
          {items.map((item) => (
            <CarouselItem key={item.id} className="pl-2 md:pl-4 basis-full sm:basis-1/2 md:basis-1/3 lg:basis-1/4">
              <Card 
                className={cn(
                  "overflow-hidden group transition-all duration-300 cursor-pointer border",
                  currentTheme.card,
                  itemClassName
                )}
                onClick={item.onClick}
              >
                <div className="relative h-[250px] overflow-hidden">
                  <img 
                    src={item.image} 
                    alt={item.title}
                    className="w-full h-full object-cover object-center transition-transform duration-500 group-hover:scale-105"
                   loading="lazy" width={800} height={600} onError={(event) => { event.currentTarget.src = "/images/placeholder.svg"; }} />
                  {item.badge && (
                    <span className="absolute top-2 left-2 bg-teal-500 text-white text-xs px-2 py-1 rounded-full font-medium">
                      {item.badge}
                    </span>
                  )}
                  <div className="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300"></div>
                </div>
                <CardContent className="p-4">
                  <h3 className={cn(
                    "font-medium line-clamp-2 mb-1 group-hover:text-teal-600 transition-colors",
                    currentTheme.title
                  )}>
                    {item.title}
                  </h3>
                  {item.description && (
                    <p className={cn("text-sm line-clamp-2 mb-2", currentTheme.description)}>
                      {item.description}
                    </p>
                  )}
                  {showPricing && item.price && (
                    <span className={cn("font-semibold", currentTheme.price)}>
                      {formatPrice(item.price)}
                    </span>
                  )}
                </CardContent>
              </Card>
            </CarouselItem>
          ))}
        </CarouselContent>
        <CarouselPrevious className="hidden md:flex -left-4 bg-white/90 border-gray-200 hover:bg-white hover:border-teal-500 text-navy-800" />
        <CarouselNext className="hidden md:flex -right-4 bg-white/90 border-gray-200 hover:bg-white hover:border-teal-500 text-navy-800" />
      </Carousel>
    </div>
  );
};

export default StandardCarousel;
