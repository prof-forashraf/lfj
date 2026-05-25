import React from 'react';
import { Link } from 'react-router-dom';
import { Event as EventType } from '@/services/eventService';
import { Card, CardContent, CardFooter } from "@/components/ui/card";
import { AspectRatio } from '@/components/ui/aspect-ratio';
import { Badge } from "@/components/ui/badge";
import { MapPinIcon } from 'lucide-react';
import { isPast, parseISO } from 'date-fns';
import { getPlaceholderImageUrl, getPublicImageUrl } from '@/lib/imageUrl';

interface EventCardProps {
  event: EventType;
}

const EventCard: React.FC<EventCardProps> = ({ event }) => {
  const eventIsPast = event.start_datetime_iso ? isPast(parseISO(event.start_datetime_iso)) : false;

  return (
    <Link to={`/events/${event.slug}`} className="group block h-full">
      <Card className="overflow-hidden h-full flex flex-col group transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
        <AspectRatio ratio={16 / 9} className="bg-gray-100">
          {event.featured_image_url ? (
            <img
              src={getPublicImageUrl(event.featured_image_url)}
              alt={event.title}
              className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
              loading="lazy"
              width={640}
              height={360}
              onError={(error) => {
                error.currentTarget.src = getPlaceholderImageUrl();
              }}
            />
          ) : (
            <img
              src={getPlaceholderImageUrl()}
              alt=""
              className="w-full h-full object-cover"
              loading="lazy"
              width={640}
              height={360}
             onError={(event) => { event.currentTarget.src = "/images/placeholder.svg"; }} />
          )}
        </AspectRatio>
        <CardContent className="p-4 flex-grow">
          <p className="text-sm font-semibold text-primary-gold mb-1">{event.start_datetime_formatted || 'Date TBA'}</p>
          <h3 className="font-semibold text-dark-slate line-clamp-2 group-hover:text-primary-gold transition-colors">
            {event.title}
          </h3>
          <div className="flex items-center text-sm text-muted-foreground mt-2">
            <MapPinIcon className="w-4 h-4 mr-1.5" />
            <span>{event.location_name}</span>
          </div>
        </CardContent>
        <CardFooter className="p-4 pt-0">
            <Badge variant={eventIsPast ? 'secondary' : 'default'}>
              {eventIsPast ? 'Past Event' : 'Upcoming'}
            </Badge>
        </CardFooter>
      </Card>
    </Link>
  );
};

export default EventCard;
