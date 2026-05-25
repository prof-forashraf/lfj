import React from 'react';
import { useLocation } from 'react-router-dom';
import SeoHead from '@/components/seo/SeoHead';

const titleize = (path: string) =>
  path
    .replace(/^\/+/, '')
    .replace(/[-/]+/g, ' ')
    .replace(/\b\w/g, (character) => character.toUpperCase()) || 'Home';

const schemaFor = (title: string, description: string, canonical: string) => [
  {
    '@context': 'https://schema.org',
    '@type': 'WebPage',
    name: title,
    description,
    url: canonical,
  },
];

const PublicRouteSeo: React.FC = () => {
  const location = useLocation();
  const origin = typeof window !== 'undefined' ? window.location.origin : '';
  const canonical = `${origin}${location.pathname}`;
  const pageTitle = titleize(location.pathname);
  const title = `${pageTitle} | Latest Fashion Jewellery`;
  const description = `Explore ${pageTitle.toLowerCase()} from Latest Fashion Jewellery, including jewellery guides, tools, events, videos, trends, and curated product inspiration.`;
  const isNotFound = false;

  return (
    <SeoHead
      seo={{
        title,
        meta_description: description,
        keywords: '',
        canonical,
        robots: isNotFound ? 'noindex,follow' : 'index,follow',
        og: {
          title,
          description,
          type: 'website',
          url: canonical,
        },
        twitter: {
          card: 'summary_large_image',
          title,
          description,
        },
        schema: schemaFor(title, description, canonical),
      }}
    />
  );
};

export default PublicRouteSeo;
