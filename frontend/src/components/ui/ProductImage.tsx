import React, { useEffect, useMemo, useState } from "react";
import { ChevronLeft, ChevronRight, Search, X } from "lucide-react";
import { Dialog, DialogClose, DialogContent } from "@/components/ui/dialog";
import { cn } from "@/lib/utils";
import { getPlaceholderImageUrl } from "@/lib/imageUrl";

interface ProductImageSource {
  src: string;
  alt: string;
}

export interface ProductImageProps {
  src?: string | null;
  imageUrls?: {
    small?: string | null;
    medium?: string | null;
    large?: string | null;
  } | null;
  alt: string;
  className?: string;
  ratio?: number;
  fallbackSrc?: string;
  showViewer?: boolean;
  onClick?: (event: React.MouseEvent<HTMLDivElement>) => void;
}

const buildImageSources = (
  src?: string | null,
  imageUrls?: ProductImageProps["imageUrls"]
): ProductImageSource[] => {
  const images: string[] = [];

  if (imageUrls?.large) images.push(imageUrls.large);
  if (imageUrls?.medium) images.push(imageUrls.medium);
  if (imageUrls?.small) images.push(imageUrls.small);
  if (src) images.push(src);

  return Array.from(new Set(images))
    .filter(Boolean)
    .map((url) => ({ src: url as string, alt: "" }));
};

const ProductImage: React.FC<ProductImageProps> = ({
  src,
  imageUrls,
  alt,
  className,
  ratio = 1,
  fallbackSrc,
  showViewer = true,
  onClick,
}) => {
  const sources = useMemo(() => buildImageSources(src, imageUrls), [src, imageUrls]);
  const [imageLoaded, setImageLoaded] = useState(false);
  const [open, setOpen] = useState(false);
  const [currentIndex, setCurrentIndex] = useState(0);

  useEffect(() => {
    if (!open) return;

    const handleKeyDown = (event: KeyboardEvent) => {
      if (event.key === "ArrowLeft") {
        setCurrentIndex((prev) => (prev - 1 + sources.length) % sources.length);
      }
      if (event.key === "ArrowRight") {
        setCurrentIndex((prev) => (prev + 1) % sources.length);
      }
    };

    window.addEventListener("keydown", handleKeyDown);
    return () => window.removeEventListener("keydown", handleKeyDown);
  }, [open, sources.length]);

  const imageSource = sources[0]?.src ?? fallbackSrc ?? getPlaceholderImageUrl();
  const lightboxImages = sources.length > 0 ? sources : [{ src: imageSource, alt }];

  const handleOpenViewer = (event: React.MouseEvent<HTMLButtonElement>) => {
    event.stopPropagation();
    setOpen(true);
  };

  const handleNext = () => {
    setCurrentIndex((prev) => (prev + 1) % lightboxImages.length);
  };

  const handlePrev = () => {
    setCurrentIndex((prev) =>
      (prev - 1 + lightboxImages.length) % lightboxImages.length
    );
  };

  return (
    <>
      <div
        className={cn(
          "group relative overflow-hidden rounded-3xl bg-muted/10",
          className
        )}
        style={{ aspectRatio: String(ratio) }}
        onClick={onClick}
      >
        {!imageLoaded && (
          <div className="absolute inset-0 animate-pulse bg-muted/20" />
        )}
        <img
          src={imageSource}
          alt={alt}
          className={cn(
            "w-full h-full object-cover object-center transition-all duration-500",
            imageLoaded ? "opacity-100" : "opacity-0",
            showViewer ? "cursor-zoom-in" : ""
          )}
          loading="lazy"
          decoding="async"
          onLoad={() => setImageLoaded(true)}
          onError={(event) => {
            event.currentTarget.src = fallbackSrc ?? getPlaceholderImageUrl();
            setImageLoaded(true);
          }}
        />

        {showViewer && lightboxImages.length > 0 && (
          <button
            type="button"
            className="absolute inset-0 z-10 bg-black/0 transition-colors duration-200 hover:bg-black/10"
            aria-label={`Open image viewer for ${alt}`}
            onClick={handleOpenViewer}
          />
        )}
      </div>

      <Dialog open={open} onOpenChange={setOpen}>
        <DialogContent className="!max-w-6xl !p-0 !bg-transparent shadow-none">
          <div className="relative rounded-3xl bg-slate-950 p-4 sm:p-6 text-white">
            <DialogClose className="absolute right-4 top-4 z-30 rounded-full bg-white/10 p-2 text-white hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-white/60">
              <X className="h-5 w-5" />
              <span className="sr-only">Close image viewer</span>
            </DialogClose>

            <div className="relative flex items-center justify-center overflow-hidden rounded-3xl bg-black px-3 py-10">
              <img
                src={lightboxImages[currentIndex].src}
                alt={alt}
                className="max-h-[75vh] max-w-full object-contain"
                loading="lazy"
                decoding="async"
                onError={(event) => {
                  event.currentTarget.src = fallbackSrc ?? getPlaceholderImageUrl();
                }}
              />

              {lightboxImages.length > 1 && (
                <>
                  <button
                    type="button"
                    onClick={handlePrev}
                    className="absolute left-3 top-1/2 -translate-y-1/2 rounded-full bg-white/10 p-2 text-white shadow-lg hover:bg-white/20"
                    aria-label="Previous image"
                  >
                    <ChevronLeft className="h-5 w-5" />
                  </button>
                  <button
                    type="button"
                    onClick={handleNext}
                    className="absolute right-3 top-1/2 -translate-y-1/2 rounded-full bg-white/10 p-2 text-white shadow-lg hover:bg-white/20"
                    aria-label="Next image"
                  >
                    <ChevronRight className="h-5 w-5" />
                  </button>
                </>
              )}
            </div>

            {lightboxImages.length > 1 && (
              <div className="mt-4 flex items-center justify-center gap-2 overflow-x-auto pb-2">
                {lightboxImages.map((image, imageIndex) => (
                  <button
                    key={image.src}
                    type="button"
                    onClick={() => setCurrentIndex(imageIndex)}
                    className={cn(
                      "relative h-20 w-20 overflow-hidden rounded-2xl border transition-all duration-200",
                      imageIndex === currentIndex
                        ? "border-white"
                        : "border-white/20"
                    )}
                  >
                    <img
                      src={image.src}
                      alt={alt}
                      className="h-full w-full object-cover"
                      loading="lazy"
                      decoding="async"
                      onError={(event) => {
                        event.currentTarget.src = fallbackSrc ?? getPlaceholderImageUrl();
                      }}
                    />
                  </button>
                ))}
              </div>
            )}
          </div>
        </DialogContent>
      </Dialog>
    </>
  );
};

export default ProductImage;
