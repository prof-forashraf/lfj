import { useEffect, useState } from 'react';
import { api } from '@/lib/apiClient';

const seoCache = new Map();

export const useSeo = (pageType, slug, entity) => {
  const entitySeo = entity?.seo;
  const key = slug ? `${pageType}:${slug}` : pageType;
  const [state, setState] = useState({
    seo: entitySeo || seoCache.get(key) || null,
    loading: !entitySeo && !seoCache.has(key),
    error: null,
  });

  useEffect(() => {
    if (entitySeo) {
      seoCache.set(key, entitySeo);
      setState({ seo: entitySeo, loading: false, error: null });
      return;
    }

    if (!pageType || slug) return;
    if (seoCache.has(key)) {
      setState({ seo: seoCache.get(key), loading: false, error: null });
      return;
    }

    let active = true;
    setState((prev) => ({ ...prev, loading: true }));

    api.get(`/seo/page/${pageType}`)
      .then((seo) => {
        seoCache.set(key, seo);
        if (active) setState({ seo, loading: false, error: null });
      })
      .catch((error) => {
        if (active) setState({ seo: null, loading: false, error });
      });

    return () => {
      active = false;
    };
  }, [entitySeo, key, pageType, slug]);

  return state;
};
