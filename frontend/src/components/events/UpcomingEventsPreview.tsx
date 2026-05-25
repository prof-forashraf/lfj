import React from "react";
import { Link } from "react-router-dom";
import { useEvents } from "@/hooks/useEvents"; // Assuming you have a useEvents hook
import { Card, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import { ArrowRight, Calendar, MapPin } from "lucide-react";
import { Skeleton } from "@/components/ui/skeleton";

const UpcomingEventsPreview: React.FC = () => {
  // Fetch the next 3 upcoming events
  const { data: eventsResponse, isLoading } = useEvents({
    status: "upcoming",
    perPage: 3,
  });

  const events = eventsResponse?.data || [];

  return (
    <section className="bg-dark-slate rounded-xl p-8 md:p-12">
      <h2 className="text-3xl font-playfair font-bold text-white text-center mb-8">
        Upcoming Events
      </h2>

      {isLoading && (
        <div className="space-y-4">
          <Skeleton className="h-20 w-full bg-gray-700 rounded-lg" />
          <Skeleton className="h-20 w-full bg-gray-700 rounded-lg" />
          <Skeleton className="h-20 w-full bg-gray-700 rounded-lg" />
        </div>
      )}

      {!isLoading && events.length > 0 && (
        <div className="space-y-6">
          {events.map((event) => (
            <Card
              key={event.id}
              className="bg-gray-800 border-gray-700 text-white transition-colors hover:border-primary-gold"
            >
              <CardContent className="p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div className="flex-grow">
                  <h3 className="font-semibold font-playfair">{event.title}</h3>
                  <div className="flex flex-col sm:flex-row sm:items-center text-xs text-gray-400 mt-2 space-y-1 sm:space-y-0 sm:space-x-4">
                    <span className="flex items-center">
                      <Calendar className="mr-1.5 h-4 w-4" />
                      {event.start_datetime_formatted}
                    </span>
                    {event.location_name && (
                      <span className="flex items-center">
                        <MapPin className="mr-1.5 h-4 w-4" />
                        {event.location_name}
                      </span>
                    )}
                  </div>
                </div>
                <Button
                  asChild
                  size="sm"
                  variant="outline"
                  className="text-white border-primary-gold hover:bg-primary-gold hover:text-white shrink-0 w-full sm:w-auto"
                >
                  <Link to={`/events/${event.slug}`}>View Details</Link>
                </Button>
              </CardContent>
            </Card>
          ))}
        </div>
      )}

      <div className="text-center mt-8">
        <Button
          asChild
          variant="ghost"
          className="text-primary-gold hover:text-white group"
        >
          <Link to="/events">
            View All Events{" "}
            <ArrowRight className="ml-2 h-4 w-4 transition-transform group-hover:translate-x-1" />
          </Link>
        </Button>
      </div>
    </section>
  );
};

export default UpcomingEventsPreview;
