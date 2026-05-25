
import React from 'react';
import SeoHead from '@/components/seo/SeoHead';

interface SEOMetaTagsProps {
  title: string;
  description: string;
  ogTitle?: string;
  ogDescription?: string;
  ogImage?: string;
  ogType?: 'website' | 'article';
  canonical?: string;
  keywords?: string;
  author?: string;
  authorName?: string;
  publishedTime?: string;
  modifiedTime?: string;
}

const SEOMetaTags: React.FC<SEOMetaTagsProps> = ({
  title,
  description,
  ogTitle,
  ogDescription,
  ogImage,
  ogType = 'article',
  canonical,
  keywords,
  author = 'Laravel Blog'
}) => {
  const siteUrl = typeof window !== 'undefined' ? window.location.origin : '';
  const finalOgTitle = ogTitle || title;
  const finalOgDescription = ogDescription || description;
  const finalCanonicalUrl = canonical
    ? (canonical.startsWith('http') ? canonical : `${siteUrl}${canonical}`)
    : (typeof window !== 'undefined' ? window.location.href : siteUrl);

  return (
    <SeoHead
      seo={{
        title,
        meta_description: description,
        keywords: keywords || '',
        canonical: finalCanonicalUrl,
        robots: 'index,follow',
        og: {
          title: finalOgTitle,
          description: finalOgDescription,
          image: ogImage,
          type: ogType,
          url: finalCanonicalUrl,
        },
        twitter: {
          card: ogImage ? 'summary_large_image' : 'summary',
          title: finalOgTitle,
          description: finalOgDescription,
          image: ogImage,
        },
        schema: ogType === 'article' ? [{
          '@context': 'https://schema.org',
          '@type': 'Article',
          headline: title,
          description,
          image: ogImage || '',
          author: { '@type': 'Person', name: author },
          publisher: { '@type': 'Organization', name: 'Latest Fashion Jewellery' },
          url: finalCanonicalUrl,
          mainEntityOfPage: finalCanonicalUrl,
        }] : [{
          '@context': 'https://schema.org',
          '@type': 'WebPage',
          name: title,
          description,
          url: finalCanonicalUrl,
        }],
      }}
      heroImageUrl={ogImage}
    />
  );
};

export default SEOMetaTags;
