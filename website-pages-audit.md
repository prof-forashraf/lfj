# Website Pages Audit

This file documents the public website pages, route paths, page purpose, visible content, data behavior, suggested improvements, and future enhancement ideas.

## Public Store Pages

### 1. `/` — `Landing`
- Description: Main homepage for the jewelry affiliate store.
- Shows: hero messaging, category preview, featured affiliate products, journal highlights, jewellery try-on feature, brand trust messaging, product discovery sections.
- Static content: headline copy, value propositions, category section, lifestyle imagery, trust statements, navigation links.
- Dynamic content: featured affiliate products feed, latest blog/post cards, category showcase, lazy-loaded sections via React Suspense.
- Improvements: strengthen the above-the-fold CTA to a single priority action, improve product preview clarity for affiliate links, and ensure SEO metadata is fully optimized for homepage search intent.
- Future enhancements: personalization based on user interests, dynamic seasonal collections, homepage experiment variants, homepage recommendations powered by browsing behavior.

### 2. `/shop` — `Shop`
- Description: Primary shop landing page for curated jewellery and affiliate product search.
- Shows: search bar, featured products carousel, category discovery, collection links, product grid, affiliate product cards.
- Static content: category labels, introductory copy, section headings, search UI.
- Dynamic content: categories from API, featured affiliate products, product search results, loading skeletons.
- Improvements: add search suggestions/autocomplete, improve mobile filtering, show product availability or affiliate destination badges.
- Future enhancements: saved searches, intent-based recommendations, richer affiliate product sorting by price/popularity.

### 3. `/shop/collection/new-arrivals` — `NewArrivals`
- Description: Curated new arrivals collection.
- Shows: newest jewellery products, collection narrative, product cards.
- Static content: section headings, collection intro copy.
- Dynamic content: product list for the new arrivals collection.
- Improvements: include release date or freshness badges, add a “shop by mood” filter.
- Future enhancements: email alerts for new arrivals, dynamic trending new products, AI-driven similarity suggestions.

### 4. `/shop/collection/:slug` — `ShopCollection`
- Description: Collection detail page for a specific jewelry collection.
- Shows: collection hero, featured products, collection description, related tools or links.
- Static content: collection headings, category UI framework.
- Dynamic content: collection content loaded from slug, product cards, related discovery.
- Improvements: clarify collection goals and best use cases, highlight top-selling items.
- Future enhancements: add collection reviews, fit/occasion filters, dynamic collection scoring.

### 5. `/shop/category/:slug` — `ShopCategory`
- Description: Category landing page for a jewelry product category.
- Shows: category hero, product cards, category description, recommended products.
- Static content: category page structure, placeholder copy.
- Dynamic content: category collection items fetched from API for that slug.
- Improvements: display category-specific filters, sort by occasion or material.
- Future enhancements: customer inspiration galleries, category comparison guidance, curated buying paths.

### 6. `/shop/advanced-search` — `AdvancedSearchPage`
- Description: Advanced product search with filters and search controls.
- Shows: search input, filter controls, product results, search suggestions.
- Static content: search interface layout, filter labels.
- Dynamic content: results based on search query and filters, category data, live search status.
- Improvements: improve filter clarity, add sort order controls, show result counts more prominently.
- Future enhancements: saved search presets, voice search, AI keyword suggestions.

## Tools & Calculators

### 7. `/tools` — `Tools`
- Description: Tools hub for jewellery calculators, sizing, and pricing utilities.
- Shows: tool cards, short descriptions, navigation to specialized tools.
- Static content: tool card titles, descriptions, layout.
- Dynamic content: none significant; page is mostly a static tools directory.
- Improvements: add usage examples for each tool and stronger value messaging.
- Future enhancements: tool popularity metrics, interactive hero callouts, recommendations based on user journey.

### 8. `/tools/gold-prices` — `GoldPrices`
- Description: Live gold pricing and currency conversion tool.
- Shows: gold price ticker, historical price data, conversion inputs.
- Static content: page headers, explanatory copy, tool labels.
- Dynamic content: live gold price feed, conversion results, charts/data updates.
- Improvements: make currency and carat input UI more obvious, add a price-change alert or daily summary.
- Future enhancements: custom watchlist, price alert notifications, export price reports.

### 9. `/tools/carat-converter` — `CaratConverter`
- Description: Gold purity and carat conversion utility.
- Shows: conversion forms, explanation of carat vs purity.
- Static content: copy blocks explaining carat and gold purity.
- Dynamic content: conversion calculations and form state.
- Improvements: add example inputs, show results in multiple units by default.
- Future enhancements: save custom conversions, embed in product pages, shareable conversion links.

### 10. `/tools/ring-size-converter` — `RingSizeConverter`
- Description: Ring size conversion tool.
- Shows: ring size selector, conversion matrix, sizing guidance.
- Static content: explanatory copy, sizing chart layout.
- Dynamic content: size conversion results and user form interactions.
- Improvements: broaden international size coverage and add fit recommendations.
- Future enhancements: AR ring-fitting guidance, printable size guides, store locator integration.

### 11. `/tools/gold-resale-calculator` — `GoldResaleCalculator`
- Description: Old gold resale value calculator.
- Shows: weight input, purity selection, estimated resale value.
- Static content: value proposition copy, calculator form design.
- Dynamic content: calculation results, pricing logic, form inputs.
- Improvements: surface assumptions clearly and add example scenarios.
- Future enhancements: resale market trend charts, buyer comparison advice, local pricing references.

### 12. `/tools/diamond-estimator` — `DiamondEstimator`
- Description: Diamond value estimator based on the 4 Cs.
- Shows: input fields for cut, clarity, carat, color, estimated price guidance.
- Static content: copy explaining each diamond attribute.
- Dynamic content: estimation calculations, score outputs.
- Improvements: add validation to avoid unrealistic combinations, show ranges.
- Future enhancements: compare to real jewellery products, AI recommendation of diamond grades.

### 13. `/tools/custom-cost-estimator` — `CustomCostEstimator`
- Description: Cost estimator for bespoke jewellery design.
- Shows: custom design inputs, cost summary, material choices.
- Static content: copy describing custom design process.
- Dynamic content: estimated pricing calculation, interactive options.
- Improvements: make cost components more transparent, add step-by-step design stages.
- Future enhancements: quote request workflow, designer consultation booking, saved designs.

### 14. `/tools/zakat-calculator` — `ZakatCalculator`
- Description: Religious Zakat calculator for gold holdings.
- Shows: gold holdings inputs, Zakat amount output, calculation details.
- Static content: copy explaining Zakat rules and eligibility.
- Dynamic content: calculated Zakat values, user inputs.
- Improvements: add help text for each input and mobile-friendly layout.
- Future enhancements: multi-asset Zakat calculator, annual Zakat reminder, downloadable certificate.

### 15. `/tools/care-guide` — `JewelleryCareGuide`
- Description: Jewellery maintenance and care guide.
- Shows: step-by-step care tips, cleaning advice, wear/storage guidance.
- Static content: care guide copy, list of do’s and don’ts.
- Dynamic content: likely minimal; mostly educational content.
- Improvements: add quick anchor links and infographic summaries.
- Future enhancements: interactive care planner, video walkthroughs, product-specific care tips.

### 16. `/tools/virtual-try-on` — `VirtualTryOnPage`
- Description: Virtual try-on experience for jewellery.
- Shows: camera-based preview, overlay controls, product selection.
- Static content: feature explanation, UI shell.
- Dynamic content: live camera/overlay experience, product image layering.
- Improvements: ensure camera permission messaging is clear and fallback copy exists.
- Future enhancements: face detection improvements, try-on history, shareable previews.

## Editorial & Experience Pages

### 17. `/blog` — `BlogHome`
- Description: Main journal index with blog articles and category filtering.
- Shows: article cards, pagination, category filters, search.
- Static content: page headings, category UI, blog section structure.
- Dynamic content: posts list from API, category list, pagination state, search query state.
- Improvements: improve article preview metadata and add topic-level cards with clear browse paths.
- Future enhancements: related articles AI, saved reading lists, content personalization.

### 18. `/blog/:slug` — `BlogPostPage`
- Description: Single blog post detail page.
- Shows: full article content, author/date metadata, related posts or calls to action.
- Static content: template structure, headings, sidebar related components.
- Dynamic content: post content loaded by slug, related articles, comments or social links if present.
- Improvements: add rich article schema, table of contents for long posts, stronger post navigation.
- Future enhancements: article recommendations, inline glossary, guest author sections.

### 19. `/events` — `EventsPage`
- Description: Events landing page with filters, date range, and infinite loading.
- Shows: event cards, filter controls, tabs for statuses, calendar picker.
- Static content: tabs, filter labels, structural copy.
- Dynamic content: event data feed, infinite scroll, search parameters synced to URL.
- Improvements: build stronger event categories, highlight upcoming featured events.
- Future enhancements: event booking integration, offline event reminders, personalized event match.

### 20. `/events/:eventSlug` — `EventDetailPage`
- Description: Detail page for a single event.
- Shows: event description, dates, location, ticket/registration CTA.
- Static content: event layout and detail section headings.
- Dynamic content: event details retrieved by slug.
- Improvements: show urgency prompts, map/location view, related events.
- Future enhancements: add RSVP/book button, calendars integration, attendee reviews.

## Educational / Brand Pages

### 21. `/about` — `About`
- Description: Brand story and brand values page.
- Shows: company narrative, trust language, mission and affiliate transparency.
- Static content: copy-heavy brand story, trust credentials.
- Dynamic content: likely minimal; mostly static text.
- Improvements: add brand timeline or social proof sections.
- Future enhancements: team profiles, sustainability commitments, customer stories.

### 22. `/contact` — `Contact`
- Description: Contact page for customer support.
- Shows: contact information, form or inquiry guidance.
- Static content: address/phone/email, contact prompt.
- Dynamic content: form submission or contact channels if enabled.
- Improvements: make support pathways clearer and show response time expectations.
- Future enhancements: live chat widget, FAQ highlights, contact form validation improvements.

### 23. `/quality` — `Quality`
- Description: Jewellery quality and craftsmanship page.
- Shows: quality assurance messaging, product standards, materials explanations.
- Static content: copy about materials, design process, quality claims.
- Dynamic content: minimal.
- Improvements: add comparison or verification badges.
- Future enhancements: certification details, interactive material explainer.

### 24. `/trends` — `Trends`
- Description: Trend insights page for fashion jewellery.
- Shows: trend stories, style guidance, featured pieces.
- Static content: trend narratives, overview copy.
- Dynamic content: if present, maybe product recommendations or featured items.
- Improvements: add trend categories and shop-the-look links.
- Future enhancements: seasonal forecast dashboard, trend alerts by email.

### 25. `/wonders-of-gold` — `WondersOfGold`
- Description: Educational page on gold’s history and value.
- Shows: gold narrative, inspirational content, product highlights.
- Static content: educational copy, section visuals.
- Dynamic content: likely minimal.
- Improvements: enhance with clearer value propositions tied to products.
- Future enhancements: interactive timeline, gold facts quiz.

### 26. `/cosmic-origins` — `CosmicOrigins`
- Description: Story page connecting jewellery to cosmic origins.
- Shows: narrative storytelling, inspirational framing.
- Static content: branded copy and imagery.
- Dynamic content: minimal.
- Improvements: connect story to product use cases more directly.
- Future enhancements: immersive storytelling with motion or audio.

### 27. `/modern-technology` — `ModernTechnology`
- Description: Page focused on technology, craftsmanship, and innovation.
- Shows: copy around modern jewellery innovation.
- Static content: explanatory sections and value messaging.
- Dynamic content: minimal.
- Improvements: add specific examples or product tie-ins.
- Future enhancements: video explainers, interactive material demos.

### 28. `/future-frontiers` — `FutureFrontiers`
- Description: Forward-looking page about future jewellery trends.
- Shows: inspiration, vision, future-focused copy.
- Static content: editorial storytelling.
- Dynamic content: minimal.
- Improvements: add future-proof styling suggestions and related tools.
- Future enhancements: future trend dashboard or subscriber preview.

## Category / Tag Pages

### 29. `/category/:categorySlug` — `CategoryArchivePage`
- Description: Category archive page for blog or product categories.
- Shows: category-specific archive listings, featured content.
- Static content: page structure, archive heading.
- Dynamic content: category data fetched by slug, list of posts/products.
- Improvements: improve SEO title generation and category descriptions.
- Future enhancements: cross-category recommendations, category landing promotions.

### 30. `/tag/:tagSlug` — `TagArchivePage`
- Description: Tag-based archive page for related content.
- Shows: tagged items, related content lists.
- Static content: tag page layout.
- Dynamic content: items filtered by tag slug.
- Improvements: make tags more discoverable and add tag breadcrumbs.
- Future enhancements: tag clouds, trending tags, auto-tag suggestions.

## Policy & Support Pages

### 31. `/terms` — `Terms`
- Description: Terms of service page.
- Shows: legal terms, use policy, affiliate disclosures.
- Static content: legal copy and policy text.
- Dynamic content: none.
- Improvements: simplify headings and make the key points scannable.
- Future enhancements: interactive FAQ version, downloadable policy PDF.

### 32. `/privacy` — `Privacy`
- Description: Privacy policy page.
- Shows: data usage, cookie disclosures, privacy commitments.
- Static content: legal and privacy copy.
- Dynamic content: none.
- Improvements: add summary bullets and permission details.
- Future enhancements: privacy dashboard, consent-managed settings.

### 33. `/shipping` — `Shipping`
- Description: Shipping and delivery information page.
- Shows: shipping policy, delivery times, packaging details.
- Static content: copy about shipping, returns, and timelines.
- Dynamic content: none.
- Improvements: highlight shipping exceptions and affiliate shipping notes.
- Future enhancements: real-time shipping estimates, delivery tracking integration.

## Authentication & Dashboard

### 34. `/login` — `Login`
- Description: User login page for registered customers or dashboard access.
- Shows: login form, password link, authentication CTA.
- Static content: login form copy and labels.
- Dynamic content: authentication handling, redirect after login.
- Improvements: add clear error messaging and social login options if available.
- Future enhancements: magic link support, multi-factor authentication, account recovery flow.

### 35. `/register` — `Register`
- Description: New user registration page.
- Shows: registration form, email/password fields, sign-up CTA.
- Static content: registration copy.
- Dynamic content: form validation and account creation handling.
- Improvements: improve password guidance and reduce friction.
- Future enhancements: social registration, progress wizard, referral incentives.

### 36. `/forgot-password` — `ForgotPassword`
- Description: Password reset request page.
- Shows: email reset form and instructions.
- Static content: reset instructions.
- Dynamic content: password reset request behavior.
- Improvements: clarify recovery steps and confirmation flow.
- Future enhancements: OTP-based reset, password strength meter.

### 37. `/dashboard` — `Dashboard`
- Description: Protected dashboard landing page for authenticated users.
- Shows: dashboard summary, account links, admin or affiliate controls.
- Static content: dashboard layout.
- Dynamic content: authenticated user data, protected route content.
- Improvements: expose relevant user stats and quick actions.
- Future enhancements: better analytics, account management, saved searches, affiliate performance.

## Utility Pages

### 38. `*` — `NotFound`
- Description: 404 page for unmatched routes.
- Shows: error messaging, navigation links back to home, shop, tools, blog, events, contact.
- Static content: 404 copy and button links.
- Dynamic content: none.
- Improvements: add search input or link to most popular pages.
- Future enhancements: suggested pages based on referrer, mini sitemap.
