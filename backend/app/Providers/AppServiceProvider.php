<?php

namespace App\Providers;
use Illuminate\Support\Facades\Event; // Add this
use Illuminate\Auth\Events\Failed as AuthFailed; // Add this
use Illuminate\Support\Facades\Log; // Add this
use App\Models\JewelryMaterial;
use App\Models\GoldPrice;
use App\Policies\JewelryMaterialPolicy;
use App\Policies\GoldPricePolicy;
use App\Domain\Pricing\Contracts\JewelryPricingServiceInterface;
use App\Domain\Pricing\Contracts\ResalePricingServiceInterface;
use App\Domain\Pricing\Contracts\ZakatPricingServiceInterface;
use App\Domain\Pricing\Contracts\MetalPriceServiceInterface;
use App\Domain\Pricing\Services\JewelryPricingService;
use App\Domain\Pricing\Services\ResalePricingService;
use App\Domain\Pricing\Services\ZakatPricingService;
use App\Domain\Pricing\Services\MetalPriceService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(MetalPriceServiceInterface::class, MetalPriceService::class);
        $this->app->bind(JewelryPricingServiceInterface::class, JewelryPricingService::class);
        $this->app->bind(ResalePricingServiceInterface::class, ResalePricingService::class);
        $this->app->bind(ZakatPricingServiceInterface::class, ZakatPricingService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix for MySQL key length issue with InnoDB
        Schema::defaultStringLength(191);

        if (!App::runningInConsole()) {
            $this->logMissingSanctumStatefulDomain();
        }

        Event::listen(function (AuthFailed $event) {
            $attemptedCredentials = $event->credentials;
            unset($attemptedCredentials['password']); // Remove password before logging

            Log::warning('Authentication failed:', [
                'guard' => $event->guard,
                'user_found' => $event->user ? 'Yes (ID: ' . $event->user->id . ')' : 'No (User not found by identifier)',
                'attempted_identifier' => $attemptedCredentials, // Logs email/username without password
                'ip_address' => request()->ip(), // Good to log IP for failed attempts
            ]);
        });

        Gate::policy(JewelryMaterial::class, JewelryMaterialPolicy::class);
        Gate::policy(GoldPrice::class, GoldPricePolicy::class);

        // If you decide to unguard models (as per Filament demo suggestion for brevity)
        // \Illuminate\Database\Eloquent\Model::unguard();
    }

    protected function logMissingSanctumStatefulDomain(): void
    {
        $request = request();

        if (!$request->header('origin') && !$request->header('referer') && !$request->is('sanctum/*')) {
            return;
        }

        $requestHost = Str::lower($request->getHost());
        $requestHostWithPort = Str::lower($request->getHttpHost());
        $originHost = $this->extractHostFromUrl($request->headers->get('origin'));
        $refererHost = $this->extractHostFromUrl($request->headers->get('referer'));

        $statefulDomains = collect(config('sanctum.stateful', []))
            ->map(fn ($domain) => trim($domain))
            ->filter()
            ->flatMap(fn ($domain) => $this->normalizeStatefulDomain($domain))
            ->map(fn ($domain) => Str::lower($domain))
            ->unique()
            ->values()
            ->all();

        if ($this->domainIsRecognized($requestHost, $statefulDomains)
            || $this->domainIsRecognized($requestHostWithPort, $statefulDomains)
            || ($originHost && $this->domainIsRecognized($originHost, $statefulDomains))
            || ($refererHost && $this->domainIsRecognized($refererHost, $statefulDomains))) {
            return;
        }

        Log::warning('Sanctum stateful domain mismatch detected.', [
            'request_host' => $requestHost,
            'request_host_with_port' => $requestHostWithPort,
            'origin_host' => $originHost,
            'referer_host' => $refererHost,
            'configured_stateful_domains' => $statefulDomains,
            'request_path' => $request->path(),
        ]);
    }

    protected function normalizeStatefulDomain(string $domain): array
    {
        if (Str::contains($domain, '://')) {
            $host = parse_url($domain, PHP_URL_HOST);
            $port = parse_url($domain, PHP_URL_PORT);
        } else {
            $parts = explode(':', $domain, 2);
            $host = $parts[0];
            $port = $parts[1] ?? null;
        }

        if (!$host) {
            return [$domain];
        }

        return $port ? [$host, "$host:$port"] : [$host];
    }

    protected function extractHostFromUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $parsedHost = parse_url($url, PHP_URL_HOST);
        $parsedPort = parse_url($url, PHP_URL_PORT);

        if (!$parsedHost) {
            return null;
        }

        return $parsedPort ? "$parsedHost:$parsedPort" : $parsedHost;
    }

    protected function domainIsRecognized(string $domain, array $statefulDomains): bool
    {
        return in_array($domain, $statefulDomains, true);
    }
}
