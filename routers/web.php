<?php

use Illuminate\Support\Facades\Route;
use Pterodactyl\BlueprintFramework\Libraries\ExtensionLibrary\Client\BlueprintClientLibrary;

/*
 * PWA web app manifest, generated from the Bubble Editor settings.
 * Served at /extensions/{identifier}/manifest.webmanifest (Blueprint adds
 * the prefix). Contains only public branding data — safe on the
 * unauthenticated web router.
 */
Route::get('/manifest.webmanifest', function () {
    $blueprint = app(BlueprintClientLibrary::class);

    $name = (string) $blueprint->dbGet('{identifier}', 'pwa_name', 'Žuvačka Panel');
    $short = (string) $blueprint->dbGet('{identifier}', 'pwa_short', 'Žuvačka');
    $themeColor = (string) $blueprint->dbGet('{identifier}', 'theme_color', '#FF4A9F');
    $bg = (string) $blueprint->dbGet('{identifier}', 'color_bg', '#141013');
    $icon = (string) $blueprint->dbGet('{identifier}', 'favicon', '');
    if ($icon === '') {
        $icon = '{webroot/public}/logo.svg';
    }

    return response()->json([
        'name' => $name !== '' ? $name : 'Žuvačka Panel',
        'short_name' => $short !== '' ? $short : 'Žuvačka',
        'start_url' => '/',
        'scope' => '/',
        'display' => 'standalone',
        'background_color' => $bg !== '' ? $bg : '#141013',
        'theme_color' => $themeColor !== '' ? $themeColor : '#FF4A9F',
        'icons' => [
            [
                'src' => $icon,
                'sizes' => 'any',
                'type' => str_ends_with($icon, '.svg') ? 'image/svg+xml' : 'image/png',
                'purpose' => 'any',
            ],
        ],
    ], 200, ['Content-Type' => 'application/manifest+json']);
});
