import React from "react";
import { Link } from "react-router-dom";
import { motion } from "framer-motion";

// --- UPGRADED: More thematic icons ---
import {
  Crown,
  ShieldCheck,
  Medal,
  Sparkles,
  Star,
  Gem,
  Microscope,
  Scale,
} from "lucide-react";

// --- Reusable Components ---
import SEOMetaTags from "@/components/blog/SEOMetaTags";
import RandomProductSection from "@/components/products/RandomProductSection";
import { Button } from "@/components/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/components/ui/tabs"; // For interactive section
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";

const Quality: React.FC = () => {
  const sectionVariants = {
    hidden: { opacity: 0, y: 50 },
    visible: {
      opacity: 1,
      y: 0,
      transition: { duration: 0.7, ease: "easeOut" as const },
    },
  };

  return (
    <>
      <SEOMetaTags
        title="Our Quality Promise | Latest Fashion Jewellery"
        description="Discover our unwavering commitment to quality. Learn about our premium materials, rigorous curation process, and the standards that define every piece we feature."
        keywords="quality jewelry, premium materials, jewelry standards, curation process"
      />

      {/* --- UPGRADED: Themed background and layout --- */}
      <main className="mt-20 pb-16 bg-soft-cream text-dark-slate">
        <div className="container mx-auto px-4 py-12">
          <div className="mb-8">
            <Breadcrumb>{/* ... Breadcrumb code ... */}</Breadcrumb>
          </div>

          {/* --- UPGRADED: Hero Section --- */}
          <motion.section
            className="text-center mb-20"
            initial="hidden"
            animate="visible"
            variants={sectionVariants}
          >
            <Crown className="w-16 h-16 text-primary-gold mx-auto mb-4" />
            <h1 className="text-5xl md:text-6xl font-playfair font-bold text-dark-slate mb-6">
              Our Philosophy of Quality
            </h1>
            <p className="text-xl text-muted-foreground max-w-3xl mx-auto leading-relaxed font-lato">
              For us, quality is not a feature—it's the foundation. It's the
              unwavering commitment that guides every piece we select and every
              recommendation we make.
            </p>
          </motion.section>

          {/* --- UPGRADED: The Pillars of Our Promise --- */}
          <motion.section
            className="mb-20"
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, amount: 0.3 }}
            variants={sectionVariants}
          >
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
              <PillarCard
                icon={Microscope}
                title="Material Integrity"
                description="We prioritize pieces made from durable, skin-friendly, and beautiful materials like sterling silver, gold plating, and high-grade gemstones."
              />
              <PillarCard
                icon={Medal}
                title="Proven Craftsmanship"
                description="Our curation focuses on items with a track record of excellent artisanship, ensuring your jewelry is made to last."
              />
              <PillarCard
                icon={ShieldCheck}
                title="Verified Trust"
                description="We feature products from highly-rated sellers with established histories of customer satisfaction and reliable service."
              />
              <PillarCard
                icon={Sparkles}
                title="Lasting Style"
                description="True quality extends to design. We select pieces that are not just trendy but have timeless appeal and versatility."
              />
            </div>
          </motion.section>

          {/* --- NEW: Interactive "Spotlight on Materials" Section --- */}
          <motion.section
            className="mb-20"
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, amount: 0.3 }}
            variants={sectionVariants}
          >
            <div className="text-center mb-12">
              <h2 className="text-3xl font-playfair font-bold text-dark-slate mb-4">
                Spotlight on Materials
              </h2>
              <p className="text-muted-foreground max-w-2xl mx-auto">
                A closer look at the quality materials you'll find in our
                curated collections.
              </p>
            </div>
            <Tabs defaultValue="gold" className="w-full max-w-4xl mx-auto">
              <TabsList className="grid w-full grid-cols-3">
                <TabsTrigger value="gold">Gold & Gold Plated</TabsTrigger>
                <TabsTrigger value="silver">Sterling Silver</TabsTrigger>
                <TabsTrigger value="gemstones">Gemstones</TabsTrigger>
              </TabsList>
              <MaterialTabContent
                value="gold"
                title="The Radiance of Gold"
                description="We feature a range of gold options, from solid gold investment pieces to high-quality gold plating (often over sterling silver or brass) that provides a luxurious look and durable finish for everyday wear. We look for thick plating (1-3 microns) to ensure longevity."
              />
              <MaterialTabContent
                value="silver"
                title="The Luster of Sterling Silver"
                description="Our silver selections are primarily 925 Sterling Silver, an alloy of 92.5% pure silver. This standard ensures a beautiful shine, strength, and is generally hypoallergenic. We often feature rhodium-plated silver for enhanced tarnish resistance."
              />
              <MaterialTabContent
                value="gemstones"
                title="The Brilliance of Gemstones"
                description="From natural, earth-mined stones to high-quality, precision-cut lab-created gems (like cubic zirconia), we value brilliance and clarity. Each featured gemstone is chosen for its vibrant color and ability to capture light beautifully."
              />
            </Tabs>
          </motion.section>

          {/* --- UPGRADED: Customer Reviews Section --- */}
          <motion.section
            className="py-20 bg-dark-slate text-white rounded-xl"
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, amount: 0.3 }}
            variants={sectionVariants}
          >
            <div className="container mx-auto px-6">
              <h2 className="text-4xl font-playfair font-bold text-center mb-12">
                Voices of Trust
              </h2>
              <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                <ReviewCard
                  name="Sarah M."
                  comment="The quality truly stands out. You can feel the difference. I'm so glad I found this site!"
                />
                <ReviewCard
                  name="Jessica L."
                  comment="I've purchased multiple recommended items, and the quality is consistently amazing. My go-to for jewelry inspiration!"
                />
                <ReviewCard
                  name="Amanda R."
                  comment="Finally, a place that curates beautiful jewelry that is also high-quality and built to last. I trust their selections completely."
                />
              </div>
            </div>
          </motion.section>

          {/* --- NEW: Connecting Quality to Products --- */}
          <motion.section
            className="my-20"
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, amount: 0.2 }}
            variants={sectionVariants}
          >
            <RandomProductSection
              title="Experience Our Quality Promise"
              limit={4}
              sectionId="quality-page-products"
            />
          </motion.section>
        </div>
      </main>
    </>
  );
};

// --- Reusable Sub-Components for this page ---
const PillarCard: React.FC<{
  icon: React.ElementType;
  title: string;
  description: string;
}> = ({ icon: Icon, title, description }) => (
  <motion.div
    whileHover={{ y: -5, scale: 1.02 }}
    transition={{ type: "spring", stiffness: 300 }}
  >
    <Card className="h-full text-center p-6 bg-white shadow-sm hover:shadow-lg transition-shadow duration-300">
      <div className="mx-auto w-16 h-16 bg-primary-gold/10 rounded-full flex items-center justify-center mb-4">
        <Icon className="h-8 w-8 text-primary-gold" />
      </div>
      <h3 className="text-xl font-playfair font-semibold text-dark-slate mb-2">
        {title}
      </h3>
      <p className="text-muted-foreground text-sm leading-relaxed">
        {description}
      </p>
    </Card>
  </motion.div>
);

const MaterialTabContent: React.FC<{
  value: string;
  title: string;
  description: string;
}> = ({ value, title, description }) => (
  <TabsContent value={value} className="pt-8">
    <Card className="bg-white border-gray-200">
      <CardHeader>
        <CardTitle className="font-playfair text-2xl text-dark-slate">
          {title}
        </CardTitle>
      </CardHeader>
      <CardContent>
        <p className="text-muted-foreground leading-loose">{description}</p>
      </CardContent>
    </Card>
  </TabsContent>
);

const ReviewCard: React.FC<{ name: string; comment: string }> = ({
  name,
  comment,
}) => (
  <Card className="bg-white/5 border-white/20">
    <CardContent className="pt-6">
      <div className="flex items-center gap-1 mb-4">
        {[...Array(5)].map((_, i) => (
          <Star key={i} className="w-5 h-5 fill-yellow-400 text-yellow-400" />
        ))}
      </div>
      <p className="text-gray-200 italic mb-4">"{comment}"</p>
      <p className="font-semibold text-white text-right">- {name}</p>
    </CardContent>
  </Card>
);

export default Quality;
