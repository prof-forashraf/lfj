import React from 'react';
import { Link } from 'react-router-dom';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Calculator, Gem, Star, Diamond } from 'lucide-react';

interface Tool {
  title: string;
  description: string;
  icon: React.ComponentType<{ className?: string }>;
  path: string;
  color: string;
  bgColor: string;
  borderColor: string;
}

interface RelatedToolsCardProps {
  currentPath: string;
}

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

const RelatedToolsCard: React.FC<RelatedToolsCardProps> = ({ currentPath }) => {
  const relatedTools = tools.filter(tool => tool.path !== currentPath);

  return (
    <Card className="bg-white/80 backdrop-blur-sm border border-border/50">
      <CardHeader>
        <CardTitle className="text-2xl font-playfair text-center text-foreground">Related Tools</CardTitle>
      </CardHeader>
      <CardContent>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          {relatedTools.slice(0, 3).map((tool, index) => {
            const IconComponent = tool.icon;
            return (
              <Link key={tool.path} to={tool.path} className="group">
                <div className={`p-6 rounded-xl transition-all duration-300 ${tool.bgColor} ${tool.borderColor} border-2 border-transparent hover:shadow-lg hover:scale-105`}>
                  <div className={`w-12 h-12 rounded-full bg-white shadow-sm flex items-center justify-center mb-4 mx-auto group-hover:shadow-md transition-all duration-300 group-hover:scale-110`}>
                    <IconComponent className={`w-6 h-6 ${tool.color}`} />
                  </div>
                  <h4 className="font-semibold text-base text-center text-foreground mb-2 group-hover:text-primary transition-colors duration-300 leading-tight">
                    {tool.title}
                  </h4>
                  <p className="text-sm text-muted-foreground text-center leading-relaxed">
                    {tool.description}
                  </p>
                </div>
              </Link>
            );
          })}
        </div>
        <div className="mt-6 text-center">
          <Link 
            to="/tools" 
            className="inline-flex items-center px-6 py-3 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors duration-300 font-semibold"
          >
            View All Tools
          </Link>
        </div>
      </CardContent>
    </Card>
  );
};

export default RelatedToolsCard;