import React from 'react';
import { VirtualTryOn } from '@/components/VirtualTryOn';
import SEOMetaTags from '@/components/blog/SEOMetaTags';

const VirtualTryOnPage: React.FC = () => {
  return (
    <>
      <SEOMetaTags
        title="Virtual Try-On | Latest Fashion Jewellery"
        description="Try jewellery virtually with live camera overlay and sample product previews for rings, necklaces, earrings and bracelets."
        keywords="virtual try-on, jewellery try on, augmented reality jewellery, ring try-on, necklace try-on"
      />
      <main className="py-16 bg-gray-50 min-h-screen">
        <div className="container mx-auto px-4">
          <div className="max-w-6xl mx-auto space-y-8">
            <div className="text-center">
              <h1 className="text-4xl md:text-5xl font-bold text-slate-900 mb-4">
                Virtual Jewellery Try-On
              </h1>
              <p className="text-lg text-slate-600 max-w-3xl mx-auto">
                Use your camera to preview jewellery styles live. Adjust overlay position and size for a realistic try-on experience.
              </p>
            </div>

            <VirtualTryOn
              jewelryType="necklace"
              productName="Gilded Signature Necklace"
              productImage="https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=800&q=80"
            />

            <div className="grid gap-4 md:grid-cols-3">
              <div className="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <h2 className="text-xl font-semibold mb-2">How it works</h2>
                <p className="text-slate-600">Allow camera access, position your face in the frame, then fine-tune the overlay for the best fit.</p>
              </div>
              <div className="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <h2 className="text-xl font-semibold mb-2">Best results</h2>
                <p className="text-slate-600">Use good lighting and a neutral background. Try both front and side positions for rings and necklaces.</p>
              </div>
              <div className="rounded-3xl bg-white p-6 shadow-sm border border-slate-200">
                <h2 className="text-xl font-semibold mb-2">Need support?</h2>
                <p className="text-slate-600">If camera access is blocked, enable permissions in your browser settings and refresh the page.</p>
              </div>
            </div>
          </div>
        </div>
      </main>
    </>
  );
};

export default VirtualTryOnPage;
