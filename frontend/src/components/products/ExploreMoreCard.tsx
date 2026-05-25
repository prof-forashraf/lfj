import React from "react";
import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { ArrowRight, Sparkles } from "lucide-react";

export const ExploreMoreCard: React.FC = () => {
  return (
    <motion.div
      className="h-full"
      initial={{ opacity: 0, y: 30 }}
      whileInView={{ opacity: 1, y: 0 }}
      viewport={{ once: true }}
      transition={{ duration: 0.5, delay: 0.3 }}
    >
      <Link to="/shop" className="group block h-full">
        {/* Card with a background image */}
        <div className="relative h-full flex flex-col items-center justify-center p-6 text-center bg-gray-900 rounded-xl overflow-hidden shadow-lg border border-transparent hover:border-primary-gold transition-all duration-300">
          {/* Background Image with Overlay */}
          <img
            src="https://images.unsplash.com/photo-1599395359489-94b62ced2a55?q=80&w=800&auto=format&fit=crop"
            alt="Explore more jewellery"
            className="absolute inset-0 w-full h-full object-cover opacity-20 group-hover:opacity-30 group-hover:scale-110 transition-all duration-500"
           loading="lazy" width={800} height={600} onError={(event) => { event.currentTarget.src = "/images/placeholder.svg"; }} />
          <div className="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>

          {/* Content */}
          <div className="relative z-10">
            <div className="mb-4">
              <Sparkles className="w-12 h-12 text-primary-gold transition-transform duration-300 group-hover:scale-110" />
            </div>
            <h3 className="text-xl font-playfair font-semibold text-white mb-2">
              Find Your Next Treasure
            </h3>
            <p className="text-gray-300 mb-4 text-sm">
              Our full collection awaits.
            </p>
            <div className="inline-flex items-center text-primary-gold font-semibold transition-transform duration-300 group-hover:translate-x-1">
              Explore Now <ArrowRight className="ml-2 h-4 w-4" />
            </div>
          </div>
        </div>
      </Link>
    </motion.div>
  );
};
