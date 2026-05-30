# Link Navigation Audit

## Route Definitions
- `/` => component `Landing`
- `/category/:categorySlug` => component `CategoryArchivePage`
- `/tag/:tagSlug` => component `TagArchivePage`
- `/tools/gold-prices` => component `GoldPrices`
- `/shop` => component `Shop`
- `/shop/collection/:slug` => component `ShopCollection`
- `/shop/category/:slug` => component `ShopCategory`
- `/shop/collection/new-arrivals` => component `NewArrivals`
- `/about` => component `About`
- `/contact` => component `Contact`
- `/events` => component `EventsPage`
- `/events/:eventSlug` => component `EventDetailPage`
- `/terms` => component `Terms`
- `/privacy` => component `Privacy`
- `/shipping` => component `Shipping`
- `/jewellery-studio` => component `JewelleryStudioPage`
- `/videos` => component `JewelleryVideosPage`
- `/blog` => component `BlogHome`
- `/blog/:slug` => component `BlogPost`
- `/quality` => component `Quality`
- `/trends` => component `Trends`
- `/tools` => component `Tools`
- `/tools/virtual-try-on` => component `VirtualTryOn`
- `/shop/advanced-search` => component `AdvancedSearchPage`
- `/tools/carat-converter` => component `CaratConverter`
- `/tools/ring-size-converter` => component `RingSizeConverter`
- `/tools/gold-resale-calculator` => component `GoldResaleCalculator`
- `/tools/diamond-estimator` => component `DiamondEstimator`
- `/tools/custom-cost-estimator` => component `CustomCostEstimator`
- `/tools/zakat-calculator` => component `ZakatCalculator`
- `/tools/care-guide` => component `JewelleryCareGuide`
- `/wonders-of-gold` => component `WondersOfGold`
- `/cosmic-origins` => component `CosmicOrigins`
- `/modern-technology` => component `ModernTechnology`
- `/future-frontiers` => component `FutureFrontiers`
- `/wishlist` => component `Wishlist`
- `/login` => component `RedirectIfAuthenticate`
- `/register` => component `RedirectIfAuthenticate`
- `/forgot-password` => component `RedirectIfAuthenticate`
- `*` => component `NotFound`
- `/dashboard` => component `RequireAut`

## Broken / Unknown Internal Link Targets
- None found.

## Orphan Routes (no incoming internal links found in source)
- `/shop/collection/new-arrivals` (component: `NewArrivals`)
- `/tools/virtual-try-on` (component: `VirtualTryOn`)
- `/shop/advanced-search` (component: `AdvancedSearchPage`)
- `/tools/carat-converter` (component: `CaratConverter`)
- `/tools/gold-resale-calculator` (component: `GoldResaleCalculator`)
- `/tools/diamond-estimator` (component: `DiamondEstimator`)
- `/tools/custom-cost-estimator` (component: `CustomCostEstimator`)
- `/tools/zakat-calculator` (component: `ZakatCalculator`)
- `/tools/care-guide` (component: `JewelleryCareGuide`)

## Routes with no outgoing internal links in their component file
- `/contact` (component: `Contact`)
- `/events` (component: `EventsPage`)
- `/videos` (component: `JewelleryVideosPage`)
- `/quality` (component: `Quality`)
- `/tools/virtual-try-on` (component: `VirtualTryOn`)
- `/shop/advanced-search` (component: `AdvancedSearchPage`)
- `/login` (component: `RedirectIfAuthenticate`)
- `/register` (component: `RedirectIfAuthenticate`)
- `/forgot-password` (component: `RedirectIfAuthenticate`)
- `/dashboard` (component: `RequireAut`)