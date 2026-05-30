

// src/components/landing/Footer.tsx
import React from 'react';
import { Link } from "react-router-dom";
import { Instagram, Facebook, Twitter, Mail, Phone, MapPin } from "lucide-react";
import { Button } from "@/components/ui/button";

const Footer: React.FC = () => {
  const currentYear = new Date().getFullYear();

  return (
    <footer className="bg-gradient-to-br from-slate-950 via-gray-900 to-black text-white relative overflow-hidden">
      {/* Enhanced Background with vibrant multiple layers */}
      <div className="absolute inset-0 bg-gradient-to-br from-amber-400/10 via-orange-500/8 to-yellow-400/12"></div>
      <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_hsl(43_96%_56%_/_15%),_transparent_60%)]"></div>
      <div className="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_right,_hsl(24_95%_70%_/_10%),_transparent_60%)]"></div>
      <div className="absolute inset-0 bg-[linear-gradient(45deg,_transparent_45%,_hsl(43_96%_56%_/_6%)_50%,_transparent_55%)]"></div>
      
      {/* Vibrant Decorative elements */}
      <div className="absolute top-0 left-0 w-full h-px bg-gradient-to-r from-transparent via-amber-400/60 to-transparent"></div>
      <div className="absolute top-8 right-8 w-40 h-40 bg-gradient-to-br from-amber-400/20 to-orange-500/15 rounded-full blur-3xl"></div>
      <div className="absolute bottom-8 left-8 w-32 h-32 bg-gradient-to-tr from-yellow-400/20 to-amber-500/15 rounded-full blur-2xl"></div>
      <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-amber-400/5 via-orange-500/8 to-yellow-400/5 rounded-full blur-3xl"></div>
      
      <div className="container mx-auto px-6 py-20 relative z-10">
        {/* WhatsApp Channel Section */}
        <div className="bg-gradient-to-r from-green-500/20 to-emerald-500/15 rounded-2xl p-8 mb-16 border border-green-400/30 backdrop-blur-sm">
          <div className="max-w-2xl mx-auto text-center">
            <h3 className="text-2xl font-playfair font-bold text-green-300 mb-3">Join Our WhatsApp Channel</h3>
            <p className="text-gray-100 mb-6 font-inter">Stay updated with the latest jewellery trends, exclusive offers, and new arrivals.</p>
            <div className="flex justify-center">
              <a 
                href="https://whatsapp.com/channel/0029VaLvdPVLtOjIjiEziK0O"
                target="_blank"
                rel="noopener noreferrer"
                className="inline-flex items-center space-x-3 bg-gradient-to-r from-green-500 to-emerald-500 text-white font-semibold px-8 py-4 rounded-lg hover:from-green-600 hover:to-emerald-600 transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-green-500/25"
              >
                <svg viewBox="0 0 24 24" className="w-6 h-6 fill-current">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.893 3.106"/>
                </svg>
                <span>Join Channel</span>
              </a>
            </div>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
          
          {/* Brand Section */}
          <div className="space-y-6 lg:col-span-1">
            <Link to="/" className="group inline-block">
              <h2 className="text-3xl font-playfair font-bold bg-gradient-to-r from-amber-300 to-orange-400 bg-clip-text text-transparent group-hover:from-orange-400 group-hover:to-amber-300 transition-all duration-300">
                Latest Fashion Jewellery
              </h2>
            </Link>
            <p className="text-gray-100 font-inter leading-relaxed text-sm">
              Curated fashion jewellery for the modern icon. Discover trending pieces, explore celebrity styles, and define your shine with timeless elegance.
            </p>
            <div className="flex space-x-3">
              <Button variant="ghost" size="icon" className="text-gray-200 hover:text-amber-300 hover:bg-amber-400/20 rounded-full border border-transparent hover:border-amber-400/30 transition-all duration-300 group">
                <Instagram className="h-5 w-5 group-hover:scale-110 transition-transform" />
              </Button>
              <Button variant="ghost" size="icon" className="text-gray-200 hover:text-amber-300 hover:bg-amber-400/20 rounded-full border border-transparent hover:border-amber-400/30 transition-all duration-300 group">
                <Facebook className="h-5 w-5 group-hover:scale-110 transition-transform" />
              </Button>
              <Button variant="ghost" size="icon" className="text-gray-200 hover:text-amber-300 hover:bg-amber-400/20 rounded-full border border-transparent hover:border-amber-400/30 transition-all duration-300 group">
                <Twitter className="h-5 w-5 group-hover:scale-110 transition-transform" />
              </Button>
            </div>
          </div>

          {/* Explore Section */}
          <div className="space-y-6">
            <h3 className="text-xl font-playfair font-semibold bg-gradient-to-r from-amber-300 to-orange-400 bg-clip-text text-transparent">Explore</h3>
            <ul className="space-y-3 font-inter">
              <li><Link to="/shop" className="text-gray-200 hover:text-amber-300 transition-all duration-300 hover:translate-x-1 inline-block relative after:content-[''] after:absolute after:w-0 after:h-px after:bottom-0 after:left-0 after:bg-amber-300 after:transition-all after:duration-300 hover:after:w-full">Collections</Link></li>
              <li><Link to="/tools" className="text-gray-200 hover:text-amber-300 transition-all duration-300 hover:translate-x-1 inline-block relative after:content-[''] after:absolute after:w-0 after:h-px after:bottom-0 after:left-0 after:bg-amber-300 after:transition-all after:duration-300 hover:after:w-full">Tools & Calculators</Link></li>
              <li><Link to="/wonders-of-gold" className="text-gray-200 hover:text-amber-300 transition-all duration-300 hover:translate-x-1 inline-block relative after:content-[''] after:absolute after:w-0 after:h-px after:bottom-0 after:left-0 after:bg-amber-300 after:transition-all after:duration-300 hover:after:w-full">Wonders of Gold</Link></li>
              <li><Link to="/blog" className="text-gray-200 hover:text-amber-300 transition-all duration-300 hover:translate-x-1 inline-block relative after:content-[''] after:absolute after:w-0 after:h-px after:bottom-0 after:left-0 after:bg-amber-300 after:transition-all after:duration-300 hover:after:w-full">The Journal</Link></li>
              <li><Link to="/videos" className="text-gray-200 hover:text-amber-300 transition-all duration-300 hover:translate-x-1 inline-block relative after:content-[''] after:absolute after:w-0 after:h-px after:bottom-0 after:left-0 after:bg-amber-300 after:transition-all after:duration-300 hover:after:w-full">Videos</Link></li>
              <li><Link to="/events" className="text-gray-200 hover:text-amber-300 transition-all duration-300 hover:translate-x-1 inline-block relative after:content-[''] after:absolute after:w-0 after:h-px after:bottom-0 after:left-0 after:bg-amber-300 after:transition-all after:duration-300 hover:after:w-full">Events</Link></li>
              <li><Link to="/about" className="text-gray-200 hover:text-amber-300 transition-all duration-300 hover:translate-x-1 inline-block relative after:content-[''] after:absolute after:w-0 after:h-px after:bottom-0 after:left-0 after:bg-amber-300 after:transition-all after:duration-300 hover:after:w-full">About Us</Link></li>
              <li><Link to="/jewellery-studio" className="text-gray-200 hover:text-amber-300 transition-all duration-300 hover:translate-x-1 inline-block relative after:content-[''] after:absolute after:w-0 after:h-px after:bottom-0 after:left-0 after:bg-amber-300 after:transition-all after:duration-300 hover:after:w-full">Virtual Try-On</Link></li>
            </ul>
          </div>

          {/* Customer Service Section */}
          <div className="space-y-6">
            <h3 className="text-xl font-playfair font-semibold bg-gradient-to-r from-amber-300 to-orange-400 bg-clip-text text-transparent">Customer Service</h3>
            <ul className="space-y-3 font-inter">
              <li><Link to="/contact" className="text-gray-200 hover:text-amber-300 transition-all duration-300 hover:translate-x-1 inline-block relative after:content-[''] after:absolute after:w-0 after:h-px after:bottom-0 after:left-0 after:bg-amber-300 after:transition-all after:duration-300 hover:after:w-full">Contact Us</Link></li>
              <li><Link to="/shipping" className="text-gray-200 hover:text-amber-300 transition-all duration-300 hover:translate-x-1 inline-block relative after:content-[''] after:absolute after:w-0 after:h-px after:bottom-0 after:left-0 after:bg-amber-300 after:transition-all after:duration-300 hover:after:w-full">Shipping & Returns</Link></li>
              <li><Link to="/quality" className="text-gray-200 hover:text-amber-300 transition-all duration-300 hover:translate-x-1 inline-block relative after:content-[''] after:absolute after:w-0 after:h-px after:bottom-0 after:left-0 after:bg-amber-300 after:transition-all after:duration-300 hover:after:w-full">Quality Standards</Link></li>
              <li><Link to="/terms" className="text-gray-200 hover:text-amber-300 transition-all duration-300 hover:translate-x-1 inline-block relative after:content-[''] after:absolute after:w-0 after:h-px after:bottom-0 after:left-0 after:bg-amber-300 after:transition-all after:duration-300 hover:after:w-full">Terms of Service</Link></li>
              <li><Link to="/privacy" className="text-gray-200 hover:text-amber-300 transition-all duration-300 hover:translate-x-1 inline-block relative after:content-[''] after:absolute after:w-0 after:h-px after:bottom-0 after:left-0 after:bg-amber-300 after:transition-all after:duration-300 hover:after:w-full">Privacy Policy</Link></li>
            </ul>
          </div>

          {/* Contact Section */}
          <div className="space-y-6">
            <h3 className="text-xl font-playfair font-semibold bg-gradient-to-r from-amber-300 to-orange-400 bg-clip-text text-transparent">Get in Touch</h3>
            <div className="space-y-5 font-inter">
              <div className="flex items-center space-x-3 text-gray-200 group">
                <div className="p-2 rounded-lg bg-amber-400/15 group-hover:bg-amber-400/25 transition-colors">
                  <Mail className="h-4 w-4 text-amber-300" />
                </div>
                <span className="group-hover:text-white transition-colors text-sm">hello@latestfashionjewellery.com</span>
              </div>
              <div className="flex items-center space-x-3 text-gray-200 group">
                <div className="p-2 rounded-lg bg-amber-400/15 group-hover:bg-amber-400/25 transition-colors">
                  <Phone className="h-4 w-4 text-amber-300" />
                </div>
                <span className="group-hover:text-white transition-colors text-sm">+1 (555) 123-4567</span>
              </div>
              <div className="flex items-start space-x-3 text-gray-200 group">
                <div className="p-2 rounded-lg bg-amber-400/15 group-hover:bg-amber-400/25 transition-colors mt-0.5">
                  <MapPin className="h-4 w-4 text-amber-300" />
                </div>
                <p className="group-hover:text-white transition-colors text-sm leading-relaxed">123 Fashion Avenue<br />New York, NY 10001</p>
              </div>
            </div>
          </div>
        </div>

        {/* Bottom Section */}
        <div className="border-t border-amber-400/20 pt-8 mt-8">
          <div className="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <p className="text-gray-300 font-inter text-sm">© {currentYear} Latest Fashion Jewellery. All rights reserved.</p>
            <div className="flex flex-col sm:flex-row items-center gap-3 text-sm text-gray-300">
              <button
                type="button"
                onClick={() => window.dispatchEvent(new Event('openCookieConsentBanner'))}
                className="rounded-full border border-amber-400/30 bg-amber-400/10 px-4 py-2 text-amber-100 transition hover:border-amber-300 hover:bg-amber-400/20"
              >
                Cookie Settings
              </button>
              <div className="flex items-center space-x-3">
                <span>Crafted with</span>
                <div className="w-4 h-4 bg-gradient-to-r from-orange-400 to-amber-400 rounded-full animate-pulse"></div>
                <span>for jewelry lovers</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
  );
};

export default Footer;
