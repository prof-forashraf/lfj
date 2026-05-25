import React from "react";
import { Link } from "react-router-dom";
import { Camera, Gamepad2, Sparkles, Wand2 } from "lucide-react";

const items = [
  { title: "Find your style", href: "/quiz/jewelry-style", icon: Sparkles },
  { title: "Try pieces on", href: "/tools/virtual-try-on", icon: Camera },
  { title: "Build a design", href: "/design/jewelry-builder", icon: Wand2 },
  { title: "Play and save", href: "/games/jewelry-match", icon: Gamepad2 },
];

const DiscoveryBand: React.FC = () => (
  <section className="py-12 bg-white border-y border-gray-100">
    <div className="container mx-auto px-6">
      <div className="grid gap-4 md:grid-cols-4">
        {items.map((item) => {
          const Icon = item.icon;
          return (
            <Link
              key={item.href}
              to={item.href}
              className="group flex items-center gap-4 rounded-lg border border-gray-100 bg-white p-5 shadow-sm transition hover:-translate-y-0.5 hover:border-primary-gold/40 hover:shadow-md"
            >
              <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-primary-gold/10 text-primary-gold">
                <Icon className="h-5 w-5" />
              </span>
              <span className="font-playfair text-lg font-semibold text-dark-slate group-hover:text-primary-gold">
                {item.title}
              </span>
            </Link>
          );
        })}
      </div>
    </div>
  </section>
);

export default DiscoveryBand;
