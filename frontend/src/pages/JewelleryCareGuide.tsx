import React, { useState } from 'react';
import { Link } from 'react-router-dom';
import SEOMetaTags from '@/components/blog/SEOMetaTags';
import RelatedToolsCard from '@/components/tools/RelatedToolsCard';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion';
import { ArrowLeft, Gem, Shield, Droplets, AlertTriangle, CheckCircle, XCircle } from 'lucide-react';

const JewelleryCareGuide: React.FC = () => {
  const careGuides = {
    gold: {
      title: 'Gold Jewellery Care',
      icon: '🏆',
      dos: [
        'Clean with warm soapy water and soft brush',
        'Store in separate compartments or soft pouches',
        'Remove before swimming or exercising',
        'Polish with a jewelry polishing cloth',
        'Have professional cleaning annually'
      ],
      donts: [
        'Use harsh chemicals or bleach',
        'Wear while applying perfumes or lotions',
        'Store in humid environments',
        'Use abrasive materials for cleaning',
        'Expose to chlorine or salt water'
      ],
      cleaning: 'Mix warm water with mild dish soap. Soak for 10-15 minutes, gently scrub with a soft toothbrush, rinse thoroughly, and dry with a soft cloth.',
      storage: 'Store in a dry place, preferably in individual soft pouches or lined jewelry boxes to prevent scratching.',
      frequency: 'Clean monthly for regularly worn pieces, weekly for daily-wear items.'
    },
    silver: {
      title: 'Silver Jewellery Care',
      icon: '⚪',
      dos: [
        'Clean with silver polish or baking soda paste',
        'Store in anti-tarnish bags',
        'Wear regularly to prevent tarnishing',
        'Use a silver polishing cloth',
        'Keep in airtight containers'
      ],
      donts: [
        'Store in humid places',
        'Use paper towels or tissues to clean',
        'Expose to rubber, latex, or certain foods',
        'Use toothpaste as a cleaning agent',
        'Ignore early signs of tarnishing'
      ],
      cleaning: 'Use silver polish or make a paste with baking soda and water. Apply gently, rinse thoroughly, and buff with a soft cloth.',
      storage: 'Store in anti-tarnish strips or airtight containers with chalk or silica gel packets to absorb moisture.',
      frequency: 'Clean when tarnishing appears, typically every 2-3 months.'
    },
    diamonds: {
      title: 'Diamond Jewellery Care',
      icon: '💎',
      dos: [
        'Clean with ammonia solution (1:6 ratio with water)',
        'Use a soft toothbrush for detailed cleaning',
        'Have settings checked by professionals',
        'Store separately to prevent scratching other gems',
        'Remove before heavy manual work'
      ],
      donts: [
        'Use ultrasonic cleaners on treated diamonds',
        'Touch diamonds with bare hands frequently',
        'Store diamonds touching other jewelry',
        'Use harsh chemicals or chlorine bleach',
        'Ignore loose settings'
      ],
      cleaning: 'Soak in ammonia solution (1 part ammonia to 6 parts water) for 30 minutes. Scrub gently with a soft brush and rinse thoroughly.',
      storage: 'Store each piece separately in soft pouches or individual compartments to prevent scratching.',
      frequency: 'Clean weekly for everyday pieces, monthly for occasional wear.'
    },
    pearls: {
      title: 'Pearl Jewellery Care',
      icon: '🤍',
      dos: [
        'Wipe with damp cloth after each wear',
        'Restring every 2-3 years if worn regularly',
        'Apply cosmetics before putting on pearls',
        'Store flat, not hanging',
        'Allow pearls to breathe'
      ],
      donts: [
        'Submerge in water',
        'Use ultrasonic cleaners',
        'Store in airtight containers long-term',
        'Spray perfume directly on pearls',
        'Use harsh chemicals or abrasives'
      ],
      cleaning: 'Wipe gently with a damp, soft cloth. For deeper cleaning, use a cloth slightly dampened with mild soapy water, then wipe with clean damp cloth.',
      storage: 'Store flat in a soft pouch or lined box. Allow air circulation - avoid airtight containers.',
      frequency: 'Wipe after each wear, deep clean monthly.'
    },
    gemstones: {
      title: 'Gemstone Jewellery Care',
      icon: '🌈',
      dos: [
        'Research specific care for your gemstone type',
        'Use lukewarm water for most stones',
        'Check settings regularly',
        'Store according to hardness scale',
        'Seek professional advice for rare stones'
      ],
      donts: [
        'Assume all gemstones can be cleaned the same way',
        'Use heat on heat-sensitive stones',
        'Store hard and soft stones together',
        'Use chemicals without research',
        'Ignore color changes or cloudiness'
      ],
      cleaning: 'Most gemstones can be cleaned with mild soap and lukewarm water. Always research your specific stone\'s care requirements.',
      storage: 'Store based on hardness - softer stones need more protection. Use individual pouches or compartments.',
      frequency: 'Varies by stone type and wear frequency. Generally monthly for regular wear.'
    }
  };

  const emergencyTips = [
    {
      problem: 'Tarnished Silver',
      solution: 'Create a paste with baking soda and water. Apply gently, rinse thoroughly, and dry immediately.',
      icon: '⚪'
    },
    {
      problem: 'Cloudy Diamonds',
      solution: 'Soak in warm soapy water for 20 minutes, brush gently with soft toothbrush, rinse and dry.',
      icon: '💎'
    },
    {
      problem: 'Dull Gold',
      solution: 'Mix warm water with few drops of dish soap. Soak, brush gently, rinse, and polish with soft cloth.',
      icon: '🏆'
    },
    {
      problem: 'Stained Pearls',
      solution: 'Never soak! Wipe gently with barely damp cloth. For stubborn stains, consult a professional.',
      icon: '🤍'
    }
  ];

  return (
    <>
      <SEOMetaTags
        title="Complete Jewellery Care Guide | How to Clean & Maintain Jewelry | LatestFashionJewellery"
        description="Comprehensive guide for caring for gold, silver, diamond, pearl, and gemstone jewelry. Professional cleaning tips, storage advice, and maintenance schedules."
        canonical="/tools/care-guide"
        keywords="jewelry care guide, how to clean jewelry, gold jewelry care, silver jewelry maintenance, diamond cleaning, pearl care, gemstone maintenance"
        ogTitle="Complete Jewellery Care & Maintenance Guide"
        ogDescription="Professional jewelry care tips for gold, silver, diamonds, pearls, and gemstones. Learn proper cleaning, storage, and maintenance techniques."
        ogType="article"
      />

      <div className="min-h-screen bg-gradient-to-br from-pink-50 via-background to-rose-50 py-12">
        <div className="container mx-auto px-4 max-w-6xl">
          {/* Header */}
          <div className="mb-8">
            <Link to="/tools" className="inline-flex items-center text-muted-foreground hover:text-primary mb-6">
              <ArrowLeft className="mr-2 h-4 w-4" />
              Back to Tools
            </Link>
            
            <div className="text-center">
              <div className="flex justify-center mb-4">
                <div className="w-16 h-16 rounded-full bg-pink-100 flex items-center justify-center">
                  <Gem className="w-8 h-8 text-pink-600" />
                </div>
              </div>
              <h1 className="text-4xl md:text-5xl font-playfair font-bold text-dark-slate mb-4">
                Jewellery Care Guide
              </h1>
              <p className="text-muted-foreground text-lg max-w-2xl mx-auto">
                Complete guide for cleaning, maintaining, and preserving your precious jewelry
              </p>
            </div>
          </div>

          {/* Main Content */}
          <Tabs defaultValue="gold" className="mb-8">
            <TabsList className="grid w-full grid-cols-5 mb-8">
              <TabsTrigger value="gold">Gold</TabsTrigger>
              <TabsTrigger value="silver">Silver</TabsTrigger>
              <TabsTrigger value="diamonds">Diamonds</TabsTrigger>
              <TabsTrigger value="pearls">Pearls</TabsTrigger>
              <TabsTrigger value="gemstones">Gemstones</TabsTrigger>
            </TabsList>

            {Object.entries(careGuides).map(([key, guide]) => (
              <TabsContent key={key} value={key}>
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-8">
                  {/* Do's and Don'ts */}
                  <div className="space-y-6">
                    <Card className="bg-white/80 backdrop-blur-sm">
                      <CardHeader>
                        <CardTitle className="flex items-center gap-2 text-xl font-playfair text-green-700">
                          <CheckCircle className="h-5 w-5" />
                          Do's
                        </CardTitle>
                      </CardHeader>
                      <CardContent>
                        <ul className="space-y-3">
                          {guide.dos.map((item, index) => (
                            <li key={index} className="flex items-start gap-2">
                              <CheckCircle className="h-4 w-4 text-green-500 mt-0.5 flex-shrink-0" />
                              <span className="text-sm">{item}</span>
                            </li>
                          ))}
                        </ul>
                      </CardContent>
                    </Card>

                    <Card className="bg-white/80 backdrop-blur-sm">
                      <CardHeader>
                        <CardTitle className="flex items-center gap-2 text-xl font-playfair text-red-700">
                          <XCircle className="h-5 w-5" />
                          Don'ts
                        </CardTitle>
                      </CardHeader>
                      <CardContent>
                        <ul className="space-y-3">
                          {guide.donts.map((item, index) => (
                            <li key={index} className="flex items-start gap-2">
                              <XCircle className="h-4 w-4 text-red-500 mt-0.5 flex-shrink-0" />
                              <span className="text-sm">{item}</span>
                            </li>
                          ))}
                        </ul>
                      </CardContent>
                    </Card>
                  </div>

                  {/* Detailed Instructions */}
                  <div className="space-y-6">
                    <Card className="bg-white/80 backdrop-blur-sm">
                      <CardHeader>
                        <CardTitle className="flex items-center gap-2 text-xl font-playfair">
                          <Droplets className="h-5 w-5 text-blue-600" />
                          Cleaning Instructions
                        </CardTitle>
                      </CardHeader>
                      <CardContent>
                        <p className="text-sm leading-relaxed">{guide.cleaning}</p>
                      </CardContent>
                    </Card>

                    <Card className="bg-white/80 backdrop-blur-sm">
                      <CardHeader>
                        <CardTitle className="flex items-center gap-2 text-xl font-playfair">
                          <Shield className="h-5 w-5 text-purple-600" />
                          Storage Guidelines
                        </CardTitle>
                      </CardHeader>
                      <CardContent>
                        <p className="text-sm leading-relaxed">{guide.storage}</p>
                      </CardContent>
                    </Card>

                    <Card className="bg-white/80 backdrop-blur-sm">
                      <CardHeader>
                        <CardTitle className="text-xl font-playfair">Cleaning Frequency</CardTitle>
                      </CardHeader>
                      <CardContent>
                        <p className="text-sm leading-relaxed">{guide.frequency}</p>
                      </CardContent>
                    </Card>
                  </div>
                </div>
              </TabsContent>
            ))}
          </Tabs>

          {/* Emergency Care Tips */}
          <Card className="bg-white/80 backdrop-blur-sm mb-8">
            <CardHeader>
              <CardTitle className="flex items-center gap-2 text-2xl font-playfair text-center">
                <AlertTriangle className="h-6 w-6 text-orange-600" />
                Emergency Care Tips
              </CardTitle>
            </CardHeader>
            <CardContent>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                {emergencyTips.map((tip, index) => (
                  <div key={index} className="p-4 bg-orange-50 rounded-lg border border-orange-200">
                    <div className="flex items-center gap-2 mb-2">
                      <span className="text-2xl">{tip.icon}</span>
                      <h4 className="font-semibold text-orange-800">{tip.problem}</h4>
                    </div>
                    <p className="text-sm text-gray-700">{tip.solution}</p>
                  </div>
                ))}
              </div>
            </CardContent>
          </Card>

          {/* General Tips */}
          <Card className="bg-white/80 backdrop-blur-sm mb-8">
            <CardHeader>
              <CardTitle className="text-2xl font-playfair text-center">General Care Principles</CardTitle>
            </CardHeader>
            <CardContent>
              <Accordion type="single" collapsible className="w-full">
                <AccordionItem value="general-1">
                  <AccordionTrigger>When to Remove Jewelry</AccordionTrigger>
                  <AccordionContent>
                    Remove jewelry before swimming, exercising, cleaning, gardening, or applying cosmetics. 
                    This prevents damage from chemicals, impact, and buildup of lotions or perfumes.
                  </AccordionContent>
                </AccordionItem>
                
                <AccordionItem value="general-2">
                  <AccordionTrigger>Professional Maintenance</AccordionTrigger>
                  <AccordionContent>
                    Have your jewelry professionally inspected and cleaned at least once a year. 
                    This includes checking prong settings, clasps, and overall structural integrity.
                  </AccordionContent>
                </AccordionItem>
                
                <AccordionItem value="general-3">
                  <AccordionTrigger>Travel Care</AccordionTrigger>
                  <AccordionContent>
                    Use a padded jewelry travel case with separate compartments. Consider leaving valuable pieces 
                    at home or in a hotel safe. Pack jewelry in carry-on luggage when flying.
                  </AccordionContent>
                </AccordionItem>
                
                <AccordionItem value="general-4">
                  <AccordionTrigger>Insurance and Documentation</AccordionTrigger>
                  <AccordionContent>
                    Keep appraisals, certificates, and photos of your jewelry for insurance purposes. 
                    Update appraisals every 3-5 years to reflect current market values.
                  </AccordionContent>
                </AccordionItem>
              </Accordion>
            </CardContent>
          </Card>

          {/* Related Tools */}
          <div className="mb-8">
            <RelatedToolsCard currentPath="/tools/care-guide" />
          </div>

          {/* Disclaimer */}
          <div className="p-6 bg-pink-50 border border-pink-200 rounded-lg">
            <p className="text-sm text-muted-foreground text-center">
              <strong>Disclaimer:</strong> These care instructions are general guidelines. Some antique, treated, 
              or specialty jewelry may require specific care methods. When in doubt, consult with a professional 
              jeweler or gemologist. Always test cleaning methods on a small, inconspicuous area first.
            </p>
          </div>
        </div>
      </div>
    </>
  );
};

export default JewelleryCareGuide;