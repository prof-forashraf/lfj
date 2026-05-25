<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\Navigation\NavigationGroup;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/**
 * Filament Admin Panel Provider Configuration
 *
 * Configures the admin panel at /admin with:
 * - 20 CRUD Resources for business entities
 * - Professional navigation with 8 organized groups
 * - SEO health monitoring dashboard
 * - User & role management via Spatie Permissions
 * - Clean, professional UI using Amber color scheme
 *
 * Resources are auto-discovered and grouped by business domain.
 * All authentication is handled by Filament's built-in guards.
 */
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            // Core Configuration
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            
            // Auto-discover all resources and pages in the Filament directories
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            
            // Widgets: auto-discover custom widgets, then add default + SEO monitor
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Filament built-in widgets (available on dashboard)
                Widgets\AccountWidget::class,        // User account menu
                Widgets\FilamentInfoWidget::class,   // Filament version info
                
                // Custom dashboard widgets
                \App\Filament\Widgets\AdminOverviewWidget::class,
                \App\Filament\Widgets\QuickReportsWidget::class,
                \App\Filament\Widgets\ActionItemsWidget::class,
                \App\Filament\Widgets\SeoHealthWidget::class,
            ])
            
            // Professional Navigation Groups (organized by business domain)
            ->navigationGroups([
                // Content & Publishing (3 resources: Post, Event, Video)
                NavigationGroup::make()
                    ->label('📝 Content Management')
                    ->collapsed(false),
                
                // E-Commerce Products (2 resources: Affiliate, ContentPlacement)
                NavigationGroup::make()
                    ->label('🏪 Products & Affiliate')
                    ->collapsed(false),
                
                // Market Data & Pricing (6 resources)
                NavigationGroup::make()
                    ->label('💰 Pricing & Market Data')
                    ->collapsed(false),

                // Jewelry & Custom Design (4 resources: CustomJewelryDesign, JewelryMatch, JewelryHoroscope, JewelryStyleQuiz)
                NavigationGroup::make()
                    ->label('💍 Jewelry & Design')
                    ->collapsed(true),

                // Tags & Categories (2 resources: Category, Tag)
                NavigationGroup::make()
                    ->label('🏷️ Organization')
                    ->collapsed(true),
                
                // User Management & Permissions (4 resources: User, AuditLog, Role, Permission)
                NavigationGroup::make()
                    ->label('👥 Admin & Users')
                    ->collapsed(true),
                
                // System Settings and Configuration (1 resource: Setting)
                NavigationGroup::make()
                    ->label('⚙️ System & Settings')
                    ->collapsed(true),
                
                // AI & Automation Tools (3 resources: AiPricing, PredictiveAnalytics, MarketingAutomation)
                NavigationGroup::make()
                    ->label('🤖 AI Tools')
                    ->collapsed(true),
                
                // Analytics & Engagement (1 resource: AnalyticsDashboard)
                NavigationGroup::make()
                    ->label('📊 Analytics & Engagement')
                    ->collapsed(true),
                
                // Social & Community (2 resources: TrendingLook, ContactSubmission)
                NavigationGroup::make()
                    ->label('🌟 Community & Social')
                    ->collapsed(true),
            ])
            
            // Security Middleware Stack
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            
            // Authentication Middleware (guards)
            ->authMiddleware([
                Authenticate::class,  // Requires admin role via User::canAccessPanel()
            ]);
    }
}
