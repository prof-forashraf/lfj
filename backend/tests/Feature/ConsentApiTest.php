<?php

use App\Models\CookieConsent;
use App\Models\VisitorPreference;

it('stores and retrieves cookie consent by visitor id', function () {
    $visitorId = 'visitor_test_' . uniqid();

    $payload = [
        'visitor_id' => $visitorId,
        'essential' => true,
        'analytics' => true,
        'marketing' => false,
        'preferences' => true,
        'meta' => ['source' => 'test'],
    ];

    $this->postJson('/api/cookie-consents', $payload)
        ->assertCreated()
        ->assertJsonFragment([
            'visitor_id' => $visitorId,
            'analytics' => true,
            'marketing' => false,
            'preferences' => true,
        ]);

    $this->assertDatabaseHas('cookie_consents', [
        'visitor_id' => $visitorId,
        'analytics' => true,
        'marketing' => false,
        'preferences' => true,
    ]);

    $this->getJson("/api/cookie-consents/{$visitorId}")
        ->assertOk()
        ->assertJsonFragment(['visitor_id' => $visitorId]);
});

it('updates existing cookie consent without authentication', function () {
    $consent = CookieConsent::create([
        'visitor_id' => 'visitor_test_update_' . uniqid(),
        'essential' => true,
        'analytics' => true,
        'marketing' => true,
        'preferences' => true,
    ]);

    $this->putJson("/api/cookie-consents/{$consent->id}", [
        'analytics' => false,
        'marketing' => false,
    ])
        ->assertOk()
        ->assertJsonFragment([
            'id' => $consent->id,
            'analytics' => false,
            'marketing' => false,
        ]);

    $this->assertDatabaseHas('cookie_consents', [
        'id' => $consent->id,
        'analytics' => false,
        'marketing' => false,
    ]);
});

it('stores and retrieves visitor preferences by visitor id', function () {
    $visitorId = 'visitor_prefs_' . uniqid();

    $payload = [
        'visitor_id' => $visitorId,
        'theme' => 'dark',
        'language' => 'es',
        'layout' => 'compact',
        'favorite_categories' => ['rings', 'necklaces'],
        'favorite_tags' => ['gold', 'minimal'],
    ];

    $this->postJson('/api/visitor-preferences', $payload)
        ->assertCreated()
        ->assertJsonFragment([
            'visitor_id' => $visitorId,
            'theme' => 'dark',
            'language' => 'es',
        ]);

    $this->assertDatabaseHas('visitor_preferences', [
        'visitor_id' => $visitorId,
        'theme' => 'dark',
        'language' => 'es',
    ]);

    $this->getJson("/api/visitor-preferences/{$visitorId}")
        ->assertOk()
        ->assertJsonFragment(['visitor_id' => $visitorId]);
});

it('updates existing visitor preferences without authentication', function () {
    $preference = VisitorPreference::create([
        'visitor_id' => 'visitor_prefs_update_' . uniqid(),
        'theme' => 'light',
        'language' => 'en',
        'layout' => 'standard',
    ]);

    $this->putJson("/api/visitor-preferences/{$preference->id}", [
        'theme' => 'dark',
        'layout' => 'comfortable',
    ])
        ->assertOk()
        ->assertJsonFragment([
            'id' => $preference->id,
            'theme' => 'dark',
            'layout' => 'comfortable',
        ]);

    $this->assertDatabaseHas('visitor_preferences', [
        'id' => $preference->id,
        'theme' => 'dark',
        'layout' => 'comfortable',
    ]);
});
