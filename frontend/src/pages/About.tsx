import React from "react";
import { Link } from "react-router-dom";
import { motion, Variants } from "framer-motion";

import {
  Gem,
  Sparkles,
  Medal,
  Heart,
  Search,
  Target,
  ArrowRight,
} from "lucide-react";


import SeoHead from "@/components/seo/SeoHead";
import Breadcrumbs from "@/components/seo/Breadcrumbs";
import { useSeo } from "@/hooks/useSeo";
import RandomProductSection from "@/components/products/RandomProductSection";
import { Card } from "@/components/ui/card";
import { Button } from "@/components/ui/button";
import {
  Breadcrumb,
  BreadcrumbItem,
  BreadcrumbLink,
  BreadcrumbList,
  BreadcrumbPage,
  BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";import { getPublicImageUrl } from '@/lib/imageUrl';
const About: React.FC = () => {
  const { seo } = useSeo("about");
  const sectionVariants: Variants = {
    hidden: { opacity: 0, y: 50 },
    visible: {
      opacity: 1,
      y: 0,
      transition: { duration: 0.6, ease: "easeOut" },
    },
  };

  return (
    <>
      <SeoHead seo={seo || undefined} breadcrumbs={[{ label: "Home", href: "/" }, { label: "About", href: "/about" }]} />
      <main className="mt-20 pb-16 bg-soft-cream text-dark-slate">
        <div className="container mx-auto px-4 py-12">
          <div className="mb-8">
            <Breadcrumbs crumbs={[{ label: "Home", href: "/" }, { label: "About", href: "/about" }]} />
            <Breadcrumb>
              <BreadcrumbList>
                <BreadcrumbItem>
                  <BreadcrumbLink asChild>
                    <Link to="/">Home</Link>
                  </BreadcrumbLink>
                </BreadcrumbItem>
                <BreadcrumbSeparator />
                <BreadcrumbItem>
                  <BreadcrumbPage>About Us</BreadcrumbPage>
                </BreadcrumbItem>
              </BreadcrumbList>
            </Breadcrumb>
          </div>

          <motion.section
            className="text-center mb-20"
            initial="hidden"
            animate="visible"
            variants={sectionVariants}
          >
            <h1 className="text-4xl md:text-6xl font-playfair font-bold text-dark-slate mb-4">
              Our Story
            </h1>
            <p className="text-lg md:text-xl text-muted-foreground max-w-3xl mx-auto font-lato">
              A journey fueled by a passion for elegance and a mission to make
              exceptional style accessible to everyone.
            </p>
          </motion.section>

          <motion.section
            className="mb-20"
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, amount: 0.3 }}
            variants={sectionVariants}
          >
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
              <div className="space-y-6">
                <h2 className="text-3xl font-playfair font-bold text-dark-slate">
                  Our Mission & Philosophy
                </h2>
                <p className="text-muted-foreground leading-relaxed">
                  At Latest Fashion Jewellery, our mission is to be your most
                  trusted guide in the world of affordable luxury. We bridge the
                  gap between fleeting trends and timeless elegance, connecting
                  you with exceptional pieces that reflect your personal style.
                </p>
                <p className="text-muted-foreground leading-relaxed">
                  As an affiliate marketing site partnered with world-class
                  retailers like Amazon, we combine our passion for curation
                  with a seamless and secure shopping experience, ensuring you
                  find pieces you'll cherish.
                </p>
              </div>
              <div className="grid grid-cols-2 gap-6">
                <ValueCard
                  icon={Medal}
                  title="Quality First"
                  description="We feature pieces celebrated for craftsmanship and positive customer experiences."
                />
                <ValueCard
                  icon={Gem}
                  title="Curated Style"
                  description="Our collections are handpicked to represent the best in current and timeless design."
                />
                <ValueCard
                  icon={Sparkles}
                  title="Accessible Luxury"
                  description="We believe beautiful jewelry should be attainable, focusing on value and style."
                />
                <ValueCard
                  icon={Heart}
                  title="Passionate Team"
                  description="Our work is driven by a genuine love for jewelry and helping you find your sparkle."
                />
              </div>
            </div>
          </motion.section>

          <motion.section
            className="mb-20"
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, amount: 0.3 }}
            variants={sectionVariants}
          >
            <div className="text-center mb-12">
              <h2 className="text-3xl font-playfair font-bold text-dark-slate mb-4">
                The Art of Curation
              </h2>
              <p className="text-muted-foreground max-w-2xl mx-auto">
                Our meticulous process for ensuring every piece we feature meets
                our high standards.
              </p>
            </div>
            <div className="relative grid grid-cols-1 md:grid-cols-3 gap-8">
              <div className="hidden md:block absolute top-10 left-0 w-full h-0.5 bg-gray-200 -z-10"></div>
              <ProcessStep
                num="1"
                icon={Search}
                title="Research & Discovery"
                description="Analyzing trends and customer feedback to find hidden gems and standout pieces."
              />
              <ProcessStep
                num="2"
                icon={Target}
                title="Rigorous Evaluation"
                description="Assessing each product on design, material quality, value, and user reviews."
              />
              <ProcessStep
                num="3"
                icon={Sparkles}
                title="Feature & Showcase"
                description="Only the best selections are presented, making your shopping experience delightful and simple."
              />
            </div>
          </motion.section>

          <motion.section
            className="mb-20"
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, amount: 0.3 }}
            variants={sectionVariants}
          >
            <div className="text-center mb-12">
              <h2 className="text-3xl font-playfair font-bold text-dark-slate mb-4">
                Meet Our Curators
              </h2>
              <p className="text-muted-foreground max-w-2xl mx-auto">
                The passionate experts behind our collections.
              </p>
            </div>
            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
              <TeamMember
                name="Sophia Chen"
                title="Founder & Lead Curator"
                imgSrc={getPublicImageUrl('/storage/team/sophia-chen.webp')}
              />
              <TeamMember
                name="Michael Rodriguez"
                title="Trend Analyst"
                imgSrc={getPublicImageUrl('/storage/team/michael-rodriguez.webp')}
              />
              <TeamMember
                name="Emma Johnson"
                title="Content Manager"
                imgSrc={getPublicImageUrl('/storage/team/emma-johnson.webp')}
              />
              <TeamMember
                name="David Kim"
                title="Digital Experience"
                imgSrc={getPublicImageUrl('/storage/team/david-kim.webp')}
              />
            </div>
          </motion.section>

          <motion.section
            className="mb-20"
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, amount: 0.2 }}
            variants={sectionVariants}
          >
            <RandomProductSection
              title="A Glimpse of Our Curation"
              limit={3} // Request 3 products. The 4th slot will be the "Explore More" card.
              sectionId="about-page-products"
            />
          </motion.section>

          <motion.section
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, amount: 0.5 }}
            variants={sectionVariants}
          >
            <div className="bg-dark-slate rounded-xl p-8 md:p-12 text-center">
              <h2 className="text-2xl md:text-3xl font-playfair font-bold text-white mb-3">
                Begin Your Style Journey
              </h2>
              <p className="text-gray-300 mb-6 max-w-xl mx-auto">
                Explore our curated collections and discover jewelry that truly
                speaks to you.
              </p>
              <Button
                size="lg"
                asChild
                className="group bg-primary-gold hover:bg-primary-gold/90 text-white"
              >
                <Link to="/shop">
                  Explore Collections{" "}
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

// --- Reusable Sub-Components (No changes needed) ---
const ValueCard: React.FC<{
  icon: React.ElementType;
  title: string;
  description: string;
}> = ({ icon: Icon, title, description }) => (
  <Card className="text-center p-6 bg-white shadow-sm hover:shadow-lg transition-shadow duration-300">
    {" "}
    <div className="mx-auto w-12 h-12 bg-primary-gold/10 rounded-full flex items-center justify-center mb-4">
      <Icon className="h-6 w-6 text-primary-gold" />
    </div>{" "}
    <h3 className="text-lg font-playfair font-semibold text-dark-slate mb-2">
      {title}
    </h3>{" "}
    <p className="text-muted-foreground text-sm">{description}</p>{" "}
  </Card>
);
const ProcessStep: React.FC<{
  num: string;
  icon: React.ElementType;
  title: string;
  description: string;
}> = ({ num, icon: Icon, title, description }) => (
  <div className="text-center">
    {" "}
    <div className="relative mb-4">
      <div className="w-20 h-20 bg-white border-2 border-primary-gold/20 rounded-full flex items-center justify-center mx-auto z-10 relative">
        <Icon className="h-8 w-8 text-primary-gold" />
      </div>
    </div>{" "}
    <h3 className="text-xl font-playfair font-semibold text-dark-slate mb-2">
      {title}
    </h3>{" "}
    <p className="text-muted-foreground">{description}</p>{" "}
  </div>
);
const TeamMember: React.FC<{ name: string; title: string; imgSrc: string }> = ({
  name,
  title,
  imgSrc,
}) => (
  <div className="text-center group">
    {" "}
    <div className="relative mb-4 w-40 h-40 mx-auto">
      {" "}
      <img
        src={imgSrc}
        alt={name}
        className="w-full h-full object-cover rounded-full shadow-md"
       loading="lazy" width={800} height={600} onError={(event) => { event.currentTarget.src = "/images/placeholder.svg"; }} />{" "}
      <div className="absolute inset-0 bg-primary-gold/20 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>{" "}
    </div>{" "}
    <h3 className="font-playfair font-semibold text-dark-slate text-lg">
      {name}
    </h3>{" "}
    <p className="text-primary-gold">{title}</p>{" "}
  </div>
);

export default About;
