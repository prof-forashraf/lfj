import React from 'react';
import { Link } from 'react-router-dom';
import { Helmet } from 'react-helmet-async';
import SEOMetaTags from '@/components/blog/SEOMetaTags';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion';
import { Calculator, Gem, Star, Diamond, Camera, Search } from 'lucide-react';

const tools = [
  {
    title: 'Gold Price Live Ticker',
    description: 'Real-time gold prices in multiple currencies and carats',
    icon: Star,
    path: '/tools/gold-prices',
    color: 'text-yellow-600',
    bgColor: 'bg-yellow-50 hover:bg-yellow-100',
    borderColor: 'hover:border-yellow-200'
  },
  {
    title: 'Carat Converter',
    description: 'Convert between carat, fineness, and percentage purity',
    icon: Gem,
    path: '/tools/carat-converter',
    color: 'text-emerald-600',
    bgColor: 'bg-emerald-50 hover:bg-emerald-100',
    borderColor: 'hover:border-emerald-200'
  },
  {
    title: 'Ring Size Converter',
    description: 'Convert ring sizes between international standards',
    icon: Diamond,
    path: '/tools/ring-size-converter',
    color: 'text-purple-600',
    bgColor: 'bg-purple-50 hover:bg-purple-100',
    borderColor: 'hover:border-purple-200'
  },
  {
    title: 'Old Gold Resale Calculator',
    description: 'Calculate the value of your old gold jewellery',
    icon: Calculator,
    path: '/tools/gold-resale-calculator',
    color: 'text-amber-600',
    bgColor: 'bg-amber-50 hover:bg-amber-100',
    borderColor: 'hover:border-amber-200'
  },
  {
    title: 'Diamond Price Estimator',
    description: 'Estimate diamond value based on the 4 Cs',
    icon: Diamond,
    path: '/tools/diamond-estimator',
    color: 'text-blue-600',
    bgColor: 'bg-blue-50 hover:bg-blue-100',
    borderColor: 'hover:border-blue-200'
  },
  {
    title: 'Advanced Product Search',
    description: 'Search jewellery with AI-powered suggestions, filters, and relevance scoring',
    icon: Search,
    path: '/shop/advanced-search',
    color: 'text-slate-700',
    bgColor: 'bg-slate-50 hover:bg-slate-100',
    borderColor: 'hover:border-slate-200'
  },
  {
    title: 'Virtual Try-On Studio',
    description: 'Preview jewellery live using your camera with overlay positioning controls',
    icon: Camera,
    path: '/tools/virtual-try-on',
    color: 'text-fuchsia-600',
    bgColor: 'bg-fuchsia-50 hover:bg-fuchsia-100',
    borderColor: 'hover:border-fuchsia-200'
  },
  {
    title: 'Custom Jewellery Cost Estimator',
    description: 'Calculate cost for custom-designed jewellery pieces',
    icon: Calculator,
    path: '/tools/custom-cost-estimator',
    color: 'text-indigo-600',
    bgColor: 'bg-indigo-50 hover:bg-indigo-100',
    borderColor: 'hover:border-indigo-200'
  },
  {
    title: 'Zakat Calculator for Gold',
    description: 'Calculate Zakat owed on gold holdings',
    icon: Star,
    path: '/tools/zakat-calculator',
    color: 'text-green-600',
    bgColor: 'bg-green-50 hover:bg-green-100',
    borderColor: 'hover:border-green-200'
  },
  {
    title: 'Jewellery Care Guide',
    description: 'Complete guide for cleaning and maintaining jewellery',
    icon: Gem,
    path: '/tools/care-guide',
    color: 'text-pink-600',
    bgColor: 'bg-pink-50 hover:bg-pink-100',
    borderColor: 'hover:border-pink-200'
  }
];

const Tools: React.FC = () => {
  return (
    <>
      <SEOMetaTags
        title="Jewellery Tools & Calculators | The Luster"
        description="Essential jewellery tools including gold price tracker, carat converter, ring size converter, and gold resale calculator for jewellery enthusiasts and professionals."
        canonical="/tools"
        keywords="jewellery tools, gold price calculator, carat converter, ring size converter, gold resale calculator, jewellery calculators"
        ogTitle="Professional Jewellery Tools & Calculators"
        ogDescription="Access professional-grade jewellery tools including live gold prices, carat conversion, ring sizing, and gold resale calculations."
        ogType="website"
      />
      
      <Helmet>
        <script type="application/ld+json">
          {JSON.stringify({
            "@context": "https://schema.org",
            "@type": "CollectionPage",
            "name": "Jewellery Tools & Calculators",
            "description": "Essential jewellery tools including gold price tracker, carat converter, ring size converter, and gold resale calculator for jewellery enthusiasts and professionals.",
            "url": typeof window !== 'undefined' ? `${window.location.origin}/tools` : '/tools',
            "mainEntity": tools.map(tool => ({
              "@type": "SoftwareApplication",
              "name": tool.title,
              "description": tool.description,
              "url": typeof window !== 'undefined' ? `${window.location.origin}${tool.path}` : tool.path,
              "applicationCategory": "UtilityApplication"
            }))
          })}
        </script>
      </Helmet>

      <div className="min-h-screen bg-gradient-to-br from-soft-cream via-background to-soft-cream py-12 md:py-20">
        <div className="container mx-auto px-4">
          {/* Header */}
          <div className="text-center mb-12 md:mb-16">
            <h1 className="text-4xl md:text-5xl lg:text-6xl font-playfair font-bold text-foreground mb-6">
              Jewellery Tools & Calculators
            </h1>
            <p className="text-lg md:text-xl text-muted-foreground max-w-3xl mx-auto leading-relaxed">
              Professional tools to help you make informed decisions about precious metals, 
              gemstones, and jewellery investments.
            </p>
          </div>

          {/* Tools Grid */}
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 max-w-7xl mx-auto">
            {tools.map((tool, index) => {
              const IconComponent = tool.icon;
              return (
                <Link key={tool.path} to={tool.path} className="group">
                  <Card className={`h-full transition-all duration-300 ${tool.bgColor} ${tool.borderColor} border-2 border-transparent hover:shadow-xl hover:scale-105 animate-fade-in`} style={{ animationDelay: `${index * 0.1}s` }}>
                    <CardHeader className="pb-4">
                      <div className={`w-16 h-16 rounded-full bg-white shadow-lg flex items-center justify-center mb-4 mx-auto group-hover:shadow-xl transition-all duration-300 group-hover:scale-110`}>
                        <IconComponent className={`w-8 h-8 ${tool.color} group-hover:animate-sparkle`} />
                      </div>
                      <CardTitle className="text-xl font-playfair text-center text-foreground group-hover:text-primary transition-colors duration-300 leading-tight">
                        {tool.title}
                      </CardTitle>
                    </CardHeader>
                    <CardContent>
                      <CardDescription className="text-center text-muted-foreground text-base leading-relaxed">
                        {tool.description}
                      </CardDescription>
                    </CardContent>
                  </Card>
                </Link>
              );
            })}
          </div>

          {/* Additional Info */}
          <div className="mt-20 text-center">
            <div className="bg-white/60 backdrop-blur-sm rounded-2xl p-8 max-w-4xl mx-auto shadow-lg border border-border/20">
              <h2 className="text-3xl font-playfair font-semibold text-foreground mb-6">
                Why Use Our Professional Tools?
              </h2>
              <p className="text-muted-foreground leading-relaxed text-lg mb-6">
                Our comprehensive suite of professional-grade calculators and converters are designed to provide accurate,
                real-time information to help jewellery enthusiasts, investors, and professionals
                make informed decisions about precious metals and gemstones.
              </p>
              <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
                <div className="flex flex-col items-center">
                  <div className="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mb-3">
                    <span className="text-2xl">⚡</span>
                  </div>
                  <h3 className="font-semibold text-foreground mb-2">Real-Time Data</h3>
                  <p className="text-sm text-muted-foreground">Live market prices and accurate calculations</p>
                </div>
                <div className="flex flex-col items-center">
                  <div className="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mb-3">
                    <span className="text-2xl">🎯</span>
                  </div>
                  <h3 className="font-semibold text-foreground mb-2">Precision Tools</h3>
                  <p className="text-sm text-muted-foreground">Professional-grade accuracy for all calculations</p>
                </div>
                <div className="flex flex-col items-center">
                  <div className="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center mb-3">
                    <span className="text-2xl">📱</span>
                  </div>
                  <h3 className="font-semibold text-foreground mb-2">Mobile Friendly</h3>
                  <p className="text-sm text-muted-foreground">Optimized for all devices and screen sizes</p>
                </div>
              </div>
            </div>
          </div>

          <div className="mt-16 grid gap-8 lg:grid-cols-2">
            <Card className="rounded-3xl border border-border/50 bg-white p-8 shadow-lg">
              <CardHeader>
                <CardTitle className="text-2xl font-playfair text-foreground mb-4">How to use these tools</CardTitle>
              </CardHeader>
              <CardContent className="space-y-4">
                <p className="text-muted-foreground leading-relaxed">
                  Start with the gold price tracker to orient the value of metal and refine your search by carat, size or resale value. Each tool is designed to answer a specific question before you shop.
                </p>
                <ul className="space-y-3 text-sm text-gray-600">
                  <li>• Use the ring size tool before selecting rings to avoid returns and ensure a comfortable fit.</li>
                  <li>• Check today’s gold rates before shopping for gold-plated or solid gold pieces.</li>
                  <li>• Compare resale value estimates alongside gifting choices for a confident purchase.</li>
                </ul>
                <div className="mt-6 flex flex-wrap gap-3">
                  <Button asChild variant="secondary" className="rounded-full px-6 py-3">
                    <Link to="/shop">Browse collections</Link>
                  </Button>
                  <Button asChild variant="outline" className="rounded-full px-6 py-3">
                    <Link to="/tools/ring-size-converter">Measure ring size</Link>
                  </Button>
                </div>
              </CardContent>
            </Card>

            <Card className="rounded-3xl border border-border/50 bg-white p-8 shadow-lg">
              <CardHeader>
                <CardTitle className="text-2xl font-playfair text-foreground mb-4">Tips for better results</CardTitle>
              </CardHeader>
              <CardContent className="space-y-4">
                <p className="text-muted-foreground leading-relaxed">
                  Use the tools as a guide rather than a final decision. The right piece also depends on proportion, styling and the emotion you want to carry.
                </p>
                <ul className="space-y-3 text-sm text-gray-600">
                  <li>• Measure without squeezing too tight. A ring should slide on snugly but still rotate.</li>
                  <li>• Match gold tone with your existing jewellery for a cohesive look.</li>
                  <li>• Pair inspiration from our journal with the tools to make a confident choice.</li>
                </ul>
                <div className="mt-6">
                  <Button asChild className="rounded-full bg-primary text-white px-6 py-3 hover:bg-primary/90">
                    <Link to="/blog">Read styling guides</Link>
                  </Button>
                </div>
              </CardContent>
            </Card>
          </div>

          <div className="mt-16 max-w-5xl mx-auto">
            <div className="mb-10 text-center">
              <p className="text-sm uppercase tracking-[0.2em] text-primary-600 mb-3">Tool guidance</p>
              <h2 className="text-3xl font-playfair font-semibold text-foreground">Common questions answered</h2>
            </div>
            <Accordion type="single" collapsible className="space-y-4">
              <AccordionItem value="size-guide">
                <AccordionTrigger className="text-left text-lg font-semibold">How do I use the ring size converter correctly?</AccordionTrigger>
                <AccordionContent className="text-sm text-gray-600 leading-relaxed">
                  Measure your ring size with a flexible tape or cord, then compare it to a standard ring chart. If you are between sizes, choose the larger size for comfort.
                </AccordionContent>
              </AccordionItem>
              <AccordionItem value="gold-price">
                <AccordionTrigger className="text-left text-lg font-semibold">Why should I check gold price before shopping?</AccordionTrigger>
                <AccordionContent className="text-sm text-gray-600 leading-relaxed">
                  Gold rates change daily, and even small shifts can affect the value of gold jewellery. Checking price first helps you choose pieces with better value and timing.
                </AccordionContent>
              </AccordionItem>
              <AccordionItem value="tools-slots">
                <AccordionTrigger className="text-left text-lg font-semibold">What should I do after using the tools?</AccordionTrigger>
                <AccordionContent className="text-sm text-gray-600 leading-relaxed">
                  Use your findings to narrow down categories, then explore our curated collections for solutions that match your size, metal preference and gifting intention.
                </AccordionContent>
              </AccordionItem>
            </Accordion>
          </div>
        </div>
      </div>
    </>
  );
};

export default Tools;