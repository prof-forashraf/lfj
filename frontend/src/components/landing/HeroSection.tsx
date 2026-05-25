
import React, { useState, useEffect } from 'react';
import { Button } from "@/components/ui/button";
import { Link } from "react-router-dom";
import { Sparkles, ChevronDown, Crown, Gem, Star } from "lucide-react";

const HeroSection: React.FC = () => {
  const [currentSlide, setCurrentSlide] = useState(0);
  const [mousePosition, setMousePosition] = useState({ x: 0, y: 0 });

  const heroContent = [
    { 
      title: "Define Your Shine", 
      subtitle: "Discover Exquisite Fashion Jewellery That Speaks to Your Soul", 
      cta: "Explore Collections", 
      link: "/shop" 
    },
    { 
      title: "Timeless Elegance", 
      subtitle: "Curated Pieces That Transcend Trends and Embrace Eternal Beauty", 
      cta: "View Elegant Styles", 
      link: "/shop" 
    },
    { 
      title: "Trending Now", 
      subtitle: "The Most Coveted Pieces That Fashion Icons Are Talking About", 
      cta: "See What's Hot", 
      link: "/shop" 
    }
  ];

  useEffect(() => {
    const handleMouseMove = (e: MouseEvent) => {
      setMousePosition({ x: e.clientX, y: e.clientY });
    };
    window.addEventListener('mousemove', handleMouseMove);
    return () => window.removeEventListener('mousemove', handleMouseMove);
  }, []);

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentSlide((prev) => (prev + 1) % heroContent.length);
    }, 6000);
    return () => clearInterval(timer);
  }, [heroContent.length]);

  const { title, subtitle, cta, link } = heroContent[currentSlide];

  return (
    <section className="relative h-screen flex items-center justify-center overflow-hidden">
      {/* Beautiful Curved Background with Gradients */}
      <div className="absolute inset-0">
        {/* Main gradient background */}
        <div className="absolute inset-0 bg-gradient-to-br from-rose-50 via-amber-50 to-orange-100"></div>
        
        {/* Curved overlays */}
        <div className="absolute inset-0">
          <svg viewBox="0 0 1200 800" className="w-full h-full absolute inset-0" preserveAspectRatio="xMidYMid slice">
            <defs>
              <linearGradient id="curve1" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stopColor="rgba(251, 191, 36, 0.1)" />
                <stop offset="100%" stopColor="rgba(245, 158, 11, 0.2)" />
              </linearGradient>
              <linearGradient id="curve2" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stopColor="rgba(236, 72, 153, 0.08)" />
                <stop offset="100%" stopColor="rgba(219, 39, 119, 0.15)" />
              </linearGradient>
              <linearGradient id="curve3" x1="0%" y1="0%" x2="100%" y2="100%">
                <stop offset="0%" stopColor="rgba(168, 85, 247, 0.05)" />
                <stop offset="100%" stopColor="rgba(147, 51, 234, 0.12)" />
              </linearGradient>
            </defs>
            
            {/* Beautiful curved shapes */}
            <path d="M0,400 C300,200 600,600 1200,300 L1200,800 L0,800 Z" fill="url(#curve1)" />
            <path d="M0,500 C400,250 800,700 1200,400 L1200,800 L0,800 Z" fill="url(#curve2)" />
            <path d="M0,600 C500,300 700,800 1200,500 L1200,800 L0,800 Z" fill="url(#curve3)" />
          </svg>
        </div>
        
        {/* Animated gradient orbs */}
        <div className="absolute top-1/4 left-1/4 w-96 h-96 bg-gradient-to-r from-pink-200 to-yellow-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-pulse"></div>
        <div className="absolute bottom-1/4 right-1/4 w-80 h-80 bg-gradient-to-r from-purple-200 to-pink-200 rounded-full mix-blend-multiply filter blur-xl opacity-60 animate-pulse" style={{ animationDelay: '2s' }}></div>
        <div className="absolute top-1/2 right-1/3 w-72 h-72 bg-gradient-to-r from-yellow-200 to-orange-200 rounded-full mix-blend-multiply filter blur-xl opacity-50 animate-pulse" style={{ animationDelay: '4s' }}></div>
      </div>
      
      {/* Floating Decorative Elements */}
      <div className="absolute inset-0 overflow-hidden pointer-events-none">
        {Array.from({ length: 15 }).map((_, i) => (
          <div
            key={i}
            className={`absolute animate-float ${
              i % 4 === 0 ? 'text-amber-400' : 
              i % 4 === 1 ? 'text-rose-400' : 
              i % 4 === 2 ? 'text-purple-400' : 'text-pink-400'
            }`}
            style={{
              top: `${Math.random() * 100}%`,
              left: `${Math.random() * 100}%`,
              animationDelay: `${Math.random() * 5}s`,
              animationDuration: `${4 + Math.random() * 3}s`
            }}
          >
            {i % 5 === 0 ? <Crown className="w-5 h-5 opacity-60" /> : 
             i % 5 === 1 ? <Gem className="w-4 h-4 opacity-50" /> :
             i % 5 === 2 ? <Sparkles className="w-4 h-4 opacity-70" /> :
             i % 5 === 3 ? <Star className="w-3 h-3 opacity-60" /> :
             <div className="w-2 h-2 bg-current rounded-full opacity-40"></div>}
          </div>
        ))}
      </div>
      
      {/* Main Content */}
      <div className="relative z-10 text-center max-w-6xl mx-auto px-6 animate-fade-in-up">
        <div className="mb-8">
          <span className="inline-block px-6 py-3 bg-white/30 backdrop-blur-sm text-amber-700 text-sm font-medium rounded-full border border-amber-200/50 shadow-lg">
            ✨ Premium Fashion Jewellery Collection
          </span>
        </div>
        
        <h1 className="text-4xl sm:text-6xl md:text-8xl lg:text-9xl font-playfair font-bold mb-8 leading-tight">
          <span className="bg-gradient-to-r from-amber-600 via-rose-500 to-purple-600 bg-clip-text text-transparent">
            {title}
          </span>
        </h1>
        
        <p className="text-base sm:text-xl md:text-2xl lg:text-3xl text-gray-700 mb-12 max-w-4xl mx-auto font-cormorant leading-relaxed px-2">
          {subtitle}
        </p>
        
        <div className="flex flex-col sm:flex-row gap-6 justify-center items-center mb-12">
          <Button 
            size="lg" 
            className="bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold px-12 py-4 text-lg rounded-full shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300" 
            asChild
          >
            <Link to={link}>
              {cta}
              <Sparkles className="w-5 h-5 ml-2" />
            </Link>
          </Button>
          
          <Button 
            size="lg" 
            className="bg-white/20 backdrop-blur-sm border-2 border-white/30 text-gray-700 hover:bg-white/30 font-semibold px-12 py-4 text-lg rounded-full shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300" 
            asChild
          >
            <Link to="/blog">
              Read Our Journal
              <span className="ml-2 group-hover:translate-x-1 transition-transform">→</span>
            </Link>
          </Button>
        </div>
        
        {/* Enhanced Trust Indicators */}
        <div className="flex flex-wrap justify-center items-center gap-4 sm:gap-8 md:space-x-12 text-xs sm:text-sm text-gray-600 bg-white/20 backdrop-blur-sm rounded-3xl sm:rounded-full px-4 sm:px-8 py-3 sm:py-4 border border-white/30">
          <Link to="/quality" className="flex items-center space-x-2 hover:text-amber-600 transition-colors group">
            <Crown className="w-5 h-5 text-amber-500 group-hover:animate-pulse" />
            <span>Premium Quality</span>
          </Link>
          <div className="hidden sm:block w-px h-6 bg-gray-300"></div>
          <Link to="/shop" className="flex items-center space-x-2 hover:text-rose-600 transition-colors group">
            <Sparkles className="w-5 h-5 text-rose-500 group-hover:animate-sparkle" />
            <span>Curated Selection</span>
          </Link>
          <div className="hidden sm:block w-px h-6 bg-gray-300"></div>
          <Link to="/trends" className="flex items-center space-x-2 hover:text-purple-600 transition-colors group">
            <Gem className="w-5 h-5 text-purple-500 group-hover:animate-bounce" />
            <span>Latest Trends</span>
          </Link>
        </div>
      </div>
      
      {/* Enhanced Scroll Indicator */}
      <div className="absolute bottom-8 left-1/2 -translate-x-1/2 animate-gentle-bounce">
        <a 
          href="#shop-by-category" 
          className="flex flex-col items-center text-gray-600 hover:text-amber-600 transition-colors group"
          onClick={(e) => {
            e.preventDefault();
            document.getElementById('shop-by-category')?.scrollIntoView({ behavior: 'smooth' });
          }}
        >
          <span className="text-sm font-medium mb-2 bg-white/30 backdrop-blur-sm px-4 py-2 rounded-full border border-white/30">Discover More</span>
          <ChevronDown className="w-6 h-6 group-hover:translate-y-1 transition-transform bg-white/20 rounded-full p-1" />
        </a>
      </div>
    </section>
  );
};

export default HeroSection;
