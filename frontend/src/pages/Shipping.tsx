import React from "react";
import { Link } from "react-router-dom";
import { motion } from "framer-motion";

// --- UPGRADED: Thematic icons ---
import {
  Handshake,
  Truck,
  Package,
  PackageSearch,
  Undo2,
  ArrowRight,
} from "lucide-react";

// --- Reusable Components ---
import SEOMetaTags from "@/components/blog/SEOMetaTags";
import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";

const Shipping: React.FC = () => {
  // Animation variants for sections
  const sectionVariants = {
    hidden: { opacity: 0, y: 40 },
    visible: {
      opacity: 1,
      y: 0,
      transition: { duration: 0.6, ease: "easeOut" as const },
    },
  };

  return (
    <>
      <SEOMetaTags
        title="Shipping & Affiliate Information | Latest Fashion Jewellery"
        description="Learn how shipping, delivery, and returns work for products purchased through our affiliate partners like Amazon. Our commitment to a transparent process."
        keywords="shipping policy, affiliate disclosure, delivery, order tracking, amazon shipping"
      />

      {/* --- UPGRADED: Themed background --- */}
      <main className="mt-20 pb-16 bg-soft-cream text-dark-slate">
        <div className="container mx-auto px-4 py-12">
          <div className="mb-8">
            <Breadcrumb>{/* ... Breadcrumb code ... */}</Breadcrumb>
          </div>

          <header className="text-center mb-16">
            <motion.h1
              className="text-4xl md:text-5xl font-playfair font-bold text-dark-slate mb-4"
              initial={{ opacity: 0, y: -20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.5 }}
            >
              Shipping & Our Process
            </motion.h1>
            <motion.p
              className="text-lg text-muted-foreground max-w-3xl mx-auto font-lato"
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              transition={{ duration: 0.5, delay: 0.2 }}
            >
              Understanding how your curated pieces get from the click to your
              doorstep.
            </motion.p>
          </header>

          {/* --- NEW: Prominent Affiliate Promise Section --- */}
          <motion.section
            className="mb-16"
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, amount: 0.5 }}
            variants={sectionVariants}
          >
            <Card className="bg-white shadow-md">
              <CardHeader className="flex flex-col md:flex-row items-center gap-4 text-center md:text-left">
                <div className="flex-shrink-0 w-16 h-16 bg-primary-gold/10 rounded-full flex items-center justify-center">
                  <Handshake className="h-8 w-8 text-primary-gold" />
                </div>
                <div>
                  <CardTitle className="text-2xl font-playfair text-dark-slate">
                    Our Affiliate Promise
                  </CardTitle>
                  <p className="text-muted-foreground mt-1">
                    Transparency and trust are at the heart of what we do.
                  </p>
                </div>
              </CardHeader>
              <CardContent>
                <p className="text-muted-foreground leading-relaxed">
                  Latest Fashion Jewellery operates on an affiliate marketing
                  model. We hand-select the best jewelry from trusted global
                  retailers like Amazon. When you choose a piece on our site, we
                  seamlessly redirect you to our partner's website to complete
                  your purchase. This means you get our expert curation combined
                  with the world-class security, shipping, and customer service
                  of our partners.
                </p>
              </CardContent>
            </Card>
          </motion.section>

          {/* --- UPGRADED: Main content in a structured grid --- */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <InfoCard
              icon={Truck}
              title="Shipping & Delivery"
              points={[
                "All shipping is handled by our partners (e.g., Amazon).",
                "Options like Amazon Prime (Two-Day, Same-Day) are available for eligible items.",
                "Shipping costs and delivery estimates are shown at partner checkout.",
                "International shipping and duties are subject to partner policies.",
              ]}
            />
            <InfoCard
              icon={PackageSearch}
              title="Order Tracking"
              points={[
                "You will receive a confirmation email directly from the retailer (e.g., Amazon).",
                "Track your order status by logging into your account on the retailer's website.",
                "We do not have access to your order details or tracking information.",
              ]}
            />
            <InfoCard
              icon={Undo2}
              title="Returns & Support"
              points={[
                "Returns and exchanges are processed according to our partner's policy (e.g., Amazon's 30-day return window).",
                "For any issues with delivery, damaged items, or returns, please contact the retailer's customer service directly.",
                "Their teams are equipped to provide you with the fastest and most effective support.",
              ]}
            />
          </div>

          {/* --- UPGRADED: CTA Section --- */}
          <motion.section
            className="mt-20"
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, amount: 0.5 }}
            variants={sectionVariants}
          >
            <div className="bg-white rounded-xl p-8 md:p-12 text-center border">
              <h2 className="text-2xl md:text-3xl font-playfair font-bold text-dark-slate mb-3">
                Your Curation Journey Starts Here
              </h2>
              <p className="text-muted-foreground mb-6 max-w-xl mx-auto">
                Now that you know how it works, you can shop with confidence.
                Let's find your next favorite piece.
              </p>
              <Button
                size="lg"
                asChild
                className="group bg-primary-gold hover:bg-primary-gold/90 text-white"
              >
                <Link to="/shop">
                  Browse Our Collections{" "}
                  <ArrowRight className="ml-2 h-5 w-5 transition-transform group-hover:translate-x-1" />
                </Link>
              </Button>
            </div>
          </motion.section>
        </div>
      </main>

    </>
  );
};

// --- Reusable Sub-Component for this page ---
const InfoCard: React.FC<{
  icon: React.ElementType;
  title: string;
  points: string[];
}> = ({ icon: Icon, title, points }) => (
  <motion.div
    initial={{ opacity: 0, scale: 0.95 }}
    whileInView={{ opacity: 1, scale: 1 }}
    viewport={{ once: true, amount: 0.5 }}
    transition={{ duration: 0.5, delay: 0.1 }}
  >
    <Card className="h-full bg-white shadow-sm hover:shadow-lg transition-shadow duration-300">
      <CardHeader className="flex flex-row items-center gap-4">
        <div className="flex-shrink-0 w-12 h-12 bg-primary-gold/10 rounded-full flex items-center justify-center">
          <Icon className="h-6 w-6 text-primary-gold" />
        </div>
        <CardTitle className="text-xl font-playfair text-dark-slate">
          {title}
        </CardTitle>
      </CardHeader>
      <CardContent>
        <ul className="space-y-3 list-disc list-inside text-muted-foreground">
          {points.map((point, index) => (
            <li key={index}>{point}</li>
          ))}
        </ul>
      </CardContent>
    </Card>
  </motion.div>
);

export default Shipping;
