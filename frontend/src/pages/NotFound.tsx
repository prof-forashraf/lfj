import React from "react";
import { Link, useLocation } from "react-router-dom";
import { useEffect } from "react";
import { Home, ArrowLeft, Search } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import SeoHead from "@/components/seo/SeoHead";

const NotFound = () => {
  const location = useLocation();

  const suggestedPages = [
    { name: "Home", path: "/", description: "Return to our main page" },
    { name: "Collections", path: "/shop", description: "Browse our jewelry collections" },
    { name: "Tools", path: "/tools", description: "Jewelry calculators and tools" },
    { name: "Journal", path: "/blog", description: "Read our latest articles" },
    { name: "Events", path: "/events", description: "Discover upcoming jewelry events" },
    { name: "Contact", path: "/contact", description: "Get in touch with us" }
  ];

  return (
    <>
      <SeoHead
        seo={{
          title: "404 - Page Not Found | Latest Fashion Jewellery",
          meta_description: "The page you're looking for doesn't exist. Explore our jewelry collections, tools, and blog instead.",
          keywords: "",
          canonical: typeof window !== "undefined" ? `${window.location.origin}${location.pathname}` : "/404",
          robots: "noindex,follow",
          og: {
            title: "404 - Page Not Found",
            description: "This page could not be found.",
            type: "website",
            url: typeof window !== "undefined" ? `${window.location.origin}${location.pathname}` : "/404",
          },
          twitter: {
            card: "summary",
            title: "404 - Page Not Found",
            description: "This page could not be found.",
          },
          schema: [{
            "@context": "https://schema.org",
            "@type": "WebPage",
            name: "404 - Page Not Found",
            description: "This page could not be found.",
          }],
        }}
      />
      
      <main className="min-h-screen bg-gradient-to-br from-soft-cream via-background to-soft-cream flex items-center justify-center py-24">
        <div className="container mx-auto px-6 text-center">
          <div className="max-w-4xl mx-auto">
            {/* Error Display */}
            <div className="mb-12">
              <div className="text-8xl md:text-9xl font-bold text-primary mb-6 animate-bounce">
                404
              </div>
              <h1 className="text-4xl md:text-5xl font-playfair font-bold text-foreground mb-4">
                Oops! Page Not Found
              </h1>
              <p className="text-xl text-muted-foreground mb-8 max-w-2xl mx-auto">
                The page you're looking for seems to have wandered off like a lost earring. 
                Let's help you find something beautiful instead!
              </p>
            </div>

            {/* Action Buttons */}
            <div className="flex flex-col sm:flex-row gap-4 justify-center mb-16">
              <Button asChild size="lg" className="bg-primary hover:bg-primary/90">
                <Link to="/" className="flex items-center gap-2">
                  <Home size={20} />
                  Go Home
                </Link>
              </Button>
              <Button asChild variant="outline" size="lg">
                <Link to="/shop" className="flex items-center gap-2">
                  <Search size={20} />
                  Browse Collections
                </Link>
              </Button>
            </div>

            {/* Suggested Pages */}
            <div className="mb-12">
              <h2 className="text-2xl font-playfair font-semibold text-foreground mb-8">
                Popular Destinations
              </h2>
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {suggestedPages.map((page) => (
                  <Link key={page.path} to={page.path} className="group">
                    <Card className="h-full transition-all duration-300 hover:shadow-lg hover:scale-105 bg-white/80 backdrop-blur-sm border border-border/50">
                      <CardHeader className="pb-3">
                        <CardTitle className="text-lg font-playfair text-foreground group-hover:text-primary transition-colors">
                          {page.name}
                        </CardTitle>
                      </CardHeader>
                      <CardContent>
                        <p className="text-muted-foreground text-sm">
                          {page.description}
                        </p>
                      </CardContent>
                    </Card>
                  </Link>
                ))}
              </div>
            </div>

            {/* Help Text */}
            <div className="bg-white/60 backdrop-blur-sm rounded-2xl p-8 border border-border/50">
              <h3 className="text-xl font-playfair font-semibold text-foreground mb-4">
                Need Help?
              </h3>
              <p className="text-muted-foreground mb-6">
                If you believe this is an error or you were looking for something specific, 
                please don't hesitate to contact our support team.
              </p>
              <Button asChild variant="outline">
                <Link to="/contact">
                  Contact Support
                </Link>
              </Button>
            </div>
          </div>
        </div>
      </main>

    </>
  );
};

export default NotFound;
