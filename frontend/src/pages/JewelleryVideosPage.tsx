// src/pages/JewelleryVideosPage.tsx
import React, { useState, useEffect } from "react";
import { useSearchParams } from "react-router-dom";
import { motion, AnimatePresence } from "framer-motion";

// Hooks and Services

import { useInfiniteVideos } from "@/hooks/useVideos";
// ✅ FIX #1: Import the PaginatedVideosResponse type alongside the VideoType
import {
  Video as VideoType,
  PaginatedVideosResponse,
} from "@/services/videoService";
import { useDebounce } from "@/hooks/useDebounce";

// --- UI & Other Components ---
import RecentPostsSection from "@/components/blog/RecentPostsSection";
import RandomProductSection from "@/components/products/RandomProductSection";
import { Card, CardContent, CardTitle } from "@/components/ui/card";
import { AspectRatio } from "@/components/ui/aspect-ratio";
import { Skeleton } from "@/components/ui/skeleton";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { PlayCircleIcon, Search, X, LoaderCircle, Film } from "lucide-react";
import SeoHead from "@/components/seo/SeoHead";
import Breadcrumbs from "@/components/seo/Breadcrumbs";
import { useSeo } from "@/hooks/useSeo";

//===============================================
// 1. Featured Video Component
//===============================================
const FeaturedVideo = ({
  video,
  onPlay,
}: {
  video: VideoType;
  onPlay: (video: VideoType) => void;
}) => (
  <motion.div
    className="mb-12 md:mb-16 rounded-2xl overflow-hidden shadow-2xl shadow-slate-900/10"
    initial={{ opacity: 0, y: 30 }}
    animate={{ opacity: 1, y: 0 }}
    transition={{ duration: 0.7, ease: "easeOut" }}
  >
    <Card className="grid md:grid-cols-2 items-center bg-gradient-to-br from-gray-50 to-white border-none">
      <div className="p-8 md:p-12 order-2 md:order-1">
        <Badge variant="destructive" className="mb-4">
          Featured Video
        </Badge>
        <CardTitle className="text-3xl lg:text-4xl font-bold font-playfair mb-4 text-slate-800 leading-tight">
          {video.title}
        </CardTitle>
        <p className="text-slate-600 font-lato text-lg mb-8 line-clamp-3">
          {video.description ||
            "Discover the craftsmanship and beauty behind this stunning piece."}
        </p>
        <Button size="lg" onClick={() => onPlay(video)} className="group">
          <PlayCircleIcon className="w-5 h-5 mr-2 group-hover:scale-110 transition-transform" />
          Watch Now
        </Button>
      </div>
      <AspectRatio
        ratio={16 / 10}
        className="relative group cursor-pointer order-1 md:order-2"
        onClick={() => onPlay(video)}
      >
        <img
          src={video.thumbnail_url}
          alt={video.title}
          className="w-full h-full object-cover"
         loading="lazy" width={800} height={600} onError={(event) => { event.currentTarget.src = "/images/placeholder.svg"; }} />
        <div className="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/40 transition-all duration-300">
          <PlayCircleIcon className="w-20 h-20 text-white/80 group-hover:text-white group-hover:scale-110 transition-all duration-300 drop-shadow-lg" />
        </div>
      </AspectRatio>
    </Card>
  </motion.div>
);

//===============================================
// 2. Video Card Component (for the grid)
//===============================================
const VideoCard = ({
  video,
  onPlay,
  index,
}: {
  video: VideoType;
  onPlay: (video: VideoType) => void;
  index: number;
}) => (
  <motion.div
    initial={{ opacity: 0, y: 20 }}
    animate={{ opacity: 1, y: 0 }}
    transition={{ duration: 0.5, delay: index * 0.05 }}
    className="h-full"
  >
    <Card
      className="overflow-hidden h-full flex flex-col shadow-md hover:shadow-primary-gold/20 transition-all duration-300 rounded-xl group cursor-pointer bg-white"
      onClick={() => onPlay(video)}
    >
      <AspectRatio ratio={16 / 9} className="relative bg-black/5">
        <img
          src={video.thumbnail_url}
          alt={video.title}
          className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
         loading="lazy" width={800} height={600} onError={(event) => { event.currentTarget.src = "/images/placeholder.svg"; }} />
        <div className="absolute inset-0 flex items-center justify-center bg-black/10 group-hover:bg-black/30 transition-all duration-300">
          <PlayCircleIcon className="w-12 h-12 text-white/90 opacity-0 group-hover:opacity-100 scale-90 group-hover:scale-100 transition-all duration-300 drop-shadow-md" />
        </div>
      </AspectRatio>
      <CardContent className="p-4 flex-grow">
        <CardTitle className="text-base font-medium font-lato line-clamp-2 leading-snug text-slate-700 group-hover:text-primary-gold">
          {video.title}
        </CardTitle>
      </CardContent>
    </Card>
  </motion.div>
);

//===============================================
// 3. Video Modal Component (Player)
//===============================================
const VideoModal = ({
  video,
  onClose,
}: {
  video: VideoType;
  onClose: () => void;
}) => (
  <div
    className="fixed inset-0 bg-black/80 backdrop-blur-sm z-[100] flex items-center justify-center p-4 animate-fade-in"
    onClick={onClose}
  >
    <motion.div
      initial={{ scale: 0.8, opacity: 0 }}
      animate={{ scale: 1, opacity: 1 }}
      exit={{ scale: 0.8, opacity: 0 }}
      transition={{ type: "spring", stiffness: 300, damping: 30 }}
      className="bg-slate-900 p-2 rounded-lg shadow-2xl w-full max-w-4xl"
      onClick={(e) => e.stopPropagation()}
    >
      <AspectRatio ratio={16 / 9} className="mb-4">
        <iframe
          width="100%"
          height="100%"
          src={`${video.embed_url}?autoplay=1&rel=0&modestbranding=1&showinfo=0`}
          title={video.title}
          frameBorder="0"
          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
          allowFullScreen
          className="rounded-md"
        ></iframe>
      </AspectRatio>
      <div className="px-4 pb-4">
        <h3 className="text-xl font-bold text-white font-playfair mb-2">
          {video.title}
        </h3>
        <p className="text-slate-400 font-lato text-sm line-clamp-2">
          {video.description}
        </p>
      </div>
      <button
        onClick={onClose}
        className="absolute -top-3 -right-3 bg-white text-gray-700 rounded-full w-9 h-9 flex items-center justify-center shadow-lg hover:bg-gray-200 hover:scale-110 transition-all z-[110]"
        aria-label="Close video player"
      >
        <X className="w-5 h-5" />
      </button>
    </motion.div>
  </div>
);

//===============================================
// 4. Main Page Component
//===============================================
const JewelleryVideosPage: React.FC = () => {
  const { seo } = useSeo("videos");
  const [searchParams, setSearchParams] = useSearchParams();
  const [searchQuery, setSearchQuery] = useState(
    searchParams.get("search") || ""
  );
  const debouncedSearch = useDebounce(searchQuery, 500);
  const [playingVideo, setPlayingVideo] = useState<VideoType | null>(null);

  const {
    data,
    error,
    fetchNextPage,
    hasNextPage,
    isFetchingNextPage,
    isLoading,
    isError,
  } = useInfiniteVideos({ search: debouncedSearch, perPage: 12 });

  useEffect(() => {
    setSearchParams(debouncedSearch ? { search: debouncedSearch } : {}, {
      replace: true,
    });
  }, [debouncedSearch, setSearchParams]);

  // ✅ FIX #2: Explicitly type the `page` parameter. This removes the red line.
  const videos =
    (data as any)?.pages?.flatMap((page: PaginatedVideosResponse) => page.data) ?? [];
  const featuredVideo = videos[0];
  const gridVideos = videos.slice(1);

  const renderContent = () => {
    if (isLoading) {
      return (
        <>
          <Skeleton className="h-[400px] w-full mb-12 rounded-2xl" />
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {Array.from({ length: 8 }).map((_, i) => (
              <Skeleton key={i} className="h-56 w-full rounded-xl" />
            ))}
          </div>
        </>
      );
    }

    if (isError) {
      return (
        <p className="text-center text-red-500">
          Error loading videos: {(error as Error)?.message}
        </p>
      );
    }

    if (videos.length === 0) {
      return (
        <div className="text-center py-24 flex flex-col items-center">
          <Film className="w-16 h-16 text-slate-300 mb-6" />
          <h3 className="text-3xl font-semibold font-playfair text-slate-700">
            No Videos Found
          </h3>
          <p className="text-slate-500 mt-2 max-w-md">
            {debouncedSearch
              ? `We couldn't find any videos for "${debouncedSearch}". Try another search.`
              : "Our video library is currently empty. Please check back soon!"}
          </p>
        </div>
      );
    }

    return (
      <>
        {featuredVideo && (
          <FeaturedVideo video={featuredVideo} onPlay={setPlayingVideo} />
        )}

        {gridVideos.length > 0 && (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
            {gridVideos.map((video, index) => (
              <VideoCard
                key={video.id}
                video={video}
                onPlay={setPlayingVideo}
                index={index}
              />
            ))}
          </div>
        )}

        {hasNextPage && (
          <div className="text-center mt-12">
            <Button
              onClick={() => fetchNextPage()}
              disabled={isFetchingNextPage}
              size="lg"
              variant="outline"
            >
              {isFetchingNextPage ? (
                <>
                  <LoaderCircle className="mr-2 h-4 w-4 animate-spin" />
                  Loading...
                </>
              ) : (
                "Load More Videos"
              )}
            </Button>
          </div>
        )}
      </>
    );
  };

  return (
    <>
      <SeoHead seo={seo || undefined} breadcrumbs={[{ label: "Home", href: "/" }, { label: "Videos", href: "/videos" }]} />

      <div className="bg-slate-50">
        <div className="container mx-auto px-4 pt-12 md:pt-16 pb-16 md:pb-24">
          <Breadcrumbs crumbs={[{ label: "Home", href: "/" }, { label: "Videos", href: "/videos" }]} />
          <header className="text-center mb-12">
            <h1 className="text-4xl lg:text-5xl font-playfair font-bold text-slate-800">
              Jewellery Video Hub
            </h1>
            <p className="mt-3 text-lg text-slate-600 max-w-2xl mx-auto font-lato">
              Inspiration, tutorials, and stunning showcases from the world of
              jewellery.
            </p>
            <div className="mt-8 max-w-xl mx-auto relative">
              <Search className="absolute left-4 top-1/2 -translate-y-1/2 h-5 w-5 text-slate-400" />
              <Input
                type="search"
                placeholder="Search videos..."
                className="w-full h-12 pl-12 pr-4 text-base rounded-full shadow-inner bg-white"
                value={searchQuery}
                onChange={(e) => setSearchQuery(e.target.value)}
              />
            </div>
          </header>

          {renderContent()}
        </div>
      </div>

      <div className="container mx-auto px-4">
        <div className="my-16 md:my-24">
          <RandomProductSection
            title="Featured In Our Videos"
            limit={4}
            sectionId="video-page-products"
          />
        </div>
        <div className="my-16 md:my-24 pt-16 border-t border-gray-200">
          <RecentPostsSection title="From Our Journal" limit={3} />
        </div>
      </div>

      <AnimatePresence>
        {playingVideo && (
          <VideoModal
            video={playingVideo}
            onClose={() => setPlayingVideo(null)}
          />
        )}
      </AnimatePresence>
    </>
  );
};

export default JewelleryVideosPage;
