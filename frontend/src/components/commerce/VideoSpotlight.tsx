import React from "react";
import { PlayCircle } from "lucide-react";
import { Skeleton } from "@/components/ui/skeleton";
import { useVideos } from "@/hooks/useVideos";

interface VideoSpotlightProps {
  title: string;
  description?: string;
  search?: string;
  placement: string;
}

const VideoSpotlight: React.FC<VideoSpotlightProps> = ({
  title,
  description,
  search = "",
  placement,
}) => {
  const { data, isLoading } = useVideos({ page: 1, perPage: 1, featured: !search, search });
  const video = data?.data?.[0];

  if (isLoading) {
    return (
      <section className="py-12" data-placement={placement}>
        <Skeleton className="h-64 rounded-lg" />
      </section>
    );
  }

  if (!video) return null;

  return (
    <section className="py-12" data-placement={placement}>
      <div className="grid gap-6 lg:grid-cols-[1.35fr_0.65fr] lg:items-center">
        <div className="aspect-video overflow-hidden rounded-lg bg-black shadow-lg">
          <iframe
            src={`${video.embed_url}?rel=0&modestbranding=1`}
            title={video.title}
            className="h-full w-full"
            loading="lazy"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowFullScreen
          />
        </div>
        <div>
          <div className="inline-flex items-center gap-2 text-sm font-semibold text-primary-gold mb-3">
            <PlayCircle className="h-4 w-4" />
            Watch the styling note
          </div>
          <h2 className="text-2xl md:text-3xl font-playfair font-bold text-dark-slate">
            {title || video.title}
          </h2>
          <p className="mt-3 text-muted-foreground leading-relaxed">
            {description || video.description}
          </p>
        </div>
      </div>
    </section>
  );
};

export default VideoSpotlight;
