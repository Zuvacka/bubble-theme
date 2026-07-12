{{-- ============================================================
     Bubble Theme — dashboard wrapper ({identifier} v{version})
     Rendered OUTSIDE the React bundle, at the end of the page.
     Reads the Bubble Editor settings (BlueprintExtensionLibrary,
     client variation) and emits:
       1. Brand fonts (self-hosted woff2) + optional custom font CSS
       2. :root design-token overrides (colors, geometry, logo size)
       3. Body classes: glass mode / sidebar layout / bg image
       4. Announcement banner (dismissible, outside the React root)
       5. Support links + custom logo injected into the navigation
       6. Hotkeys + "?" cheat-sheet
       7. SEO meta, favicon, browser theme color, PWA manifest
       8. Admin-supplied custom CSS
     All visual selectors live in dashboard/theme.css.
     ============================================================ --}}
@php
    $bubbleLib = app(\Pterodactyl\BlueprintFramework\Libraries\ExtensionLibrary\Client\BlueprintClientLibrary::class);

    $bubbleDefaults = [
        'logo_url' => '', 'logo_size' => '28', 'hide_title' => '0',
        'preset' => 'bubblegum',
        'color_accent' => '#FF4A9F', 'color_accent_deep' => '#E51A7A',
        'color_bg' => '#141013', 'color_surface' => '#1B1519', 'color_ink' => '#FFF5F9',
        'font_display' => '', 'font_body' => '', 'font_url' => '',
        'style_mode' => 'brutal', 'layout' => 'sidebar',
        'radius' => '10', 'shadow' => '4',
        'bg_image' => '', 'bg_dim' => '80',
        'ann_enabled' => '0', 'ann_text' => '', 'ann_type' => 'info', 'ann_dismiss' => '1',
        'link_discord' => '', 'link_support' => '', 'link_status' => '',
        'hotkeys' => '1',
        'seo_desc' => '', 'theme_color' => '#FF4A9F', 'favicon' => '',
        'pwa_enabled' => '0',
        'custom_css' => '',
        'hide_branding' => '1',
    ];
    $bubbleStored = $bubbleLib->dbGetMany('{identifier}', array_keys($bubbleDefaults));
    $bt = [];
    foreach ($bubbleDefaults as $bubbleKey => $bubbleDefault) {
        $bubbleValue = $bubbleStored[$bubbleKey] ?? null;
        $bt[$bubbleKey] = is_scalar($bubbleValue) ? (string) $bubbleValue : $bubbleDefault;
    }

    // Defense-in-depth sanitizers (the admin form already validates input).
    $bubbleHex = function (string $value, string $fallback): string {
        // Uppercased so comparisons against the brand defaults are stable
        // (color inputs submit lowercase hex).
        return preg_match('/^#[0-9A-Fa-f]{6}$/', $value) ? strtoupper($value) : $fallback;
    };
    $bubbleCssUrl = function (string $value): string {
        return preg_replace('/[^A-Za-z0-9:\/\.\-_~?&=%#+@,]/', '', $value);
    };
    $bubbleInt = function (string $value, int $min, int $max, int $fallback): int {
        return is_numeric($value) ? max($min, min($max, (int) $value)) : $fallback;
    };
    $bubbleFont = function (string $value): string {
        return trim(preg_replace('/[^A-Za-z0-9 ,\'\-]/', '', $value));
    };

    $btAccent = $bubbleHex($bt['color_accent'], '#FF4A9F');
    $btAccentDeep = $bubbleHex($bt['color_accent_deep'], '#E51A7A');
    $btBg = $bubbleHex($bt['color_bg'], '#141013');
    $btSurface = $bubbleHex($bt['color_surface'], '#1B1519');
    $btInk = $bubbleHex($bt['color_ink'], '#FFF5F9');
    $btThemeColor = $bubbleHex($bt['theme_color'], '#FF4A9F');
    $btRadius = $bubbleInt($bt['radius'], 0, 24, 10);
    $btShadow = $bubbleInt($bt['shadow'], 0, 10, 4);
    $btLogoSize = $bubbleInt($bt['logo_size'], 16, 71, 28);
    $btBgDim = $bubbleInt($bt['bg_dim'], 0, 95, 80);
    $btLogoUrl = $bubbleCssUrl($bt['logo_url']);
    $btBgImage = $bubbleCssUrl($bt['bg_image']);
    $btFavicon = $bubbleCssUrl($bt['favicon']);
    $btFontUrl = $bubbleCssUrl($bt['font_url']);
    $btFontDisplay = $bubbleFont($bt['font_display']);
    $btFontBody = $bubbleFont($bt['font_body']);
    $btDefaultLogo = '{webroot/public}/logo.svg';

    // Payload consumed by the wrapper script below. JSON_HEX_TAG keeps any
    // "</script>" inside stored values inert.
    $btJs = json_encode([
        'logoUrl' => $btLogoUrl !== '' ? $btLogoUrl : $btDefaultLogo,
        'hideTitle' => $bt['hide_title'] === '1',
        'glass' => $bt['style_mode'] === 'glass',
        'sidebar' => $bt['layout'] === 'sidebar',
        'bgImage' => $btBgImage !== '',
        'annEnabled' => $bt['ann_enabled'] === '1' && trim($bt['ann_text']) !== '',
        'annDismiss' => $bt['ann_dismiss'] === '1',
        'annKey' => 'bubbletheme.ann.' . md5($bt['ann_text'] . $bt['ann_type']),
        'discord' => $bt['link_discord'],
        'support' => $bt['link_support'],
        'status' => $bt['link_status'],
        'hotkeys' => $bt['hotkeys'] === '1',
        'seoDesc' => trim($bt['seo_desc']),
        'themeColor' => $btThemeColor,
        'favicon' => $btFavicon,
        'pwa' => $bt['pwa_enabled'] === '1',
        'manifest' => '/extensions/{identifier}/manifest.webmanifest',
        'noBrand' => $bt['hide_branding'] === '1',
    ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
@endphp

{{-- 1. Fonts --}}
<link rel="preload" href="{webroot/public}/fonts/funnel-display-latin-wght.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="{webroot/public}/fonts/satoshi-variable-wght.woff2" as="font" type="font/woff2" crossorigin>
@if ($btFontUrl !== '')
    <link rel="stylesheet" href="{{ $btFontUrl }}">
@endif
<style>
    @font-face {
        font-family: 'Funnel Display';
        font-style: normal;
        font-weight: 300 800;
        font-display: swap;
        src: url('{webroot/public}/fonts/funnel-display-latin-wght.woff2') format('woff2-variations');
        unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
    @font-face {
        font-family: 'Satoshi';
        font-style: normal;
        font-weight: 300 900;
        font-display: swap;
        src: url('{webroot/public}/fonts/satoshi-variable-wght.woff2') format('woff2-variations');
    }
</style>

{{-- 2. Design-token overrides from the Bubble Editor --}}
<style>
    :root {
        --bubble-accent: {{ $btAccent }};
        --bubble-accent-deep: {{ $btAccentDeep }};
        --bubble-bg: {{ $btBg }};
        --bubble-surface: {{ $btSurface }};
        --bubble-ink: {{ $btInk }};
        --bubble-radius: {{ $btRadius }}px;
        --bubble-radius-sm: {{ max(2, (int) round($btRadius * 0.6)) }}px;
        --bubble-hs-x: {{ $btShadow }}px;
        --bubble-hs-y: {{ $btShadow }}px;
        --bubble-logo-size: {{ $btLogoSize }}px;
        {{-- Derived shades (color-mix) so one picker retints the whole
             scale; only emitted when a color differs from the brand
             default, keeping the hand-tuned Žuvačka palette exact. --}}
        @if ($btAccent !== '#FF4A9F')
        --bubble-accent-strong: color-mix(in srgb, {{ $btAccent }} 82%, #000000);
        --bubble-accent-soft: color-mix(in srgb, {{ $btAccent }} 14%, transparent);
        --bubble-pink-light: color-mix(in srgb, {{ $btAccent }} 45%, #ffffff);
        --bubble-gradient: linear-gradient(135deg, color-mix(in srgb, {{ $btAccent }} 45%, #ffffff) 0%, {{ $btAccent }} 55%, {{ $btAccentDeep }} 100%);
        @endif
        @if ($btBg !== '#141013')
        --bubble-bg-deep: color-mix(in srgb, {{ $btBg }} 72%, #000000);
        @endif
        @if ($btSurface !== '#1B1519')
        --bubble-raised: color-mix(in srgb, {{ $btSurface }} 88%, #ffffff);
        --bubble-overlay: color-mix(in srgb, {{ $btSurface }} 80%, #ffffff);
        --bubble-line: color-mix(in srgb, {{ $btSurface }} 74%, #ffffff);
        @endif
        @if ($btInk !== '#FFF5F9')
        --bubble-ink-dim: color-mix(in srgb, {{ $btInk }} 72%, {{ $btBg }});
        --bubble-ink-faint: color-mix(in srgb, {{ $btInk }} 48%, {{ $btBg }});
        --bubble-frame: {{ $btInk }};
        @endif
        @if ($btFontDisplay !== '')
        --bubble-font-display: '{{ $btFontDisplay }}', 'Funnel Display', 'Satoshi', system-ui, sans-serif;
        @endif
        @if ($btFontBody !== '')
        --bubble-font-body: '{{ $btFontBody }}', 'Satoshi', system-ui, sans-serif;
        @endif
        @if ($btLogoUrl !== '')
        --bubble-auth-logo: url('{{ $btLogoUrl }}');
        @endif
    }
    @if ($btBgImage !== '')
    body.bubbletheme-bgimg {
        background-image:
            linear-gradient(rgba(10, 8, 10, {{ $btBgDim / 100 }}), rgba(10, 8, 10, {{ $btBgDim / 100 }})),
            url('{{ $btBgImage }}') !important;
        background-size: cover !important;
        background-position: center !important;
        background-attachment: fixed !important;
    }
    @endif
</style>

{{-- 3. Announcement banner (moved to the top of <body> by the script) --}}
@if ($bt['ann_enabled'] === '1' && trim($bt['ann_text']) !== '')
    <div id="bubbletheme-announcement" class="bubbletheme-announcement" data-type="{{ in_array($bt['ann_type'], ['info', 'success', 'warning', 'danger'], true) ? $bt['ann_type'] : 'info' }}" style="display: none;" role="status">
        <span class="bubbletheme-announcement-badge">{{ ['info' => 'News', 'success' => 'Yay', 'warning' => 'Heads up', 'danger' => 'Important'][$bt['ann_type']] ?? 'News' }}</span>
        <p>{{ $bt['ann_text'] }}</p>
        @if ($bt['ann_dismiss'] === '1')
            <button type="button" id="bubbletheme-announcement-close" aria-label="Dismiss announcement">&times;</button>
        @endif
    </div>
@endif

{{-- 4. Hotkey cheat-sheet ("?" to toggle) --}}
@if ($bt['hotkeys'] === '1')
    <div id="bubbletheme-hotkeys" class="bubbletheme-hotkeys" role="dialog" aria-label="Keyboard shortcuts">
        <div class="bubbletheme-hotkeys-card">
            <h3>Keyboard shortcuts</h3>
            <ul>
                <li><span>Show / hide this cheat-sheet</span><kbd>?</kbd></li>
                <li><span>Dashboard</span><kbd>g&nbsp;h</kbd></li>
                <li><span>Account settings</span><kbd>g&nbsp;a</kbd></li>
                <li><span>API credentials</span><kbd>g&nbsp;k</kbd></li>
                <li><span>Account activity</span><kbd>g&nbsp;v</kbd></li>
                <li><span>Server · Console</span><kbd>g&nbsp;c</kbd></li>
                <li><span>Server · Files</span><kbd>g&nbsp;f</kbd></li>
                <li><span>Server · Databases</span><kbd>g&nbsp;d</kbd></li>
                <li><span>Server · Schedules</span><kbd>g&nbsp;t</kbd></li>
                <li><span>Server · Users</span><kbd>g&nbsp;u</kbd></li>
                <li><span>Server · Backups</span><kbd>g&nbsp;b</kbd></li>
                <li><span>Server · Network</span><kbd>g&nbsp;n</kbd></li>
                <li><span>Server · Startup</span><kbd>g&nbsp;e</kbd></li>
                <li><span>Server · Settings</span><kbd>g&nbsp;x</kbd></li>
                <li><span>Server · Activity</span><kbd>g&nbsp;l</kbd></li>
                <li><span>Close</span><kbd>Esc</kbd></li>
            </ul>
        </div>
    </div>
@endif

{{-- 5. Wrapper script — body classes, logo, nav links, meta, PWA, hotkeys --}}
<script>
(function () {
    'use strict';
    var BT = {!! $btJs !!};

    /* Body classes drive the glass / sidebar / bg-image CSS layers */
    if (BT.glass) { document.body.classList.add('bubbletheme-glass'); }
    if (BT.sidebar) { document.body.classList.add('bubbletheme-sidebar'); }
    if (BT.bgImage) { document.body.classList.add('bubbletheme-bgimg'); }
    if (BT.noBrand) { document.body.classList.add('bubbletheme-nobrand'); }

    /* Announcement banner: move above the React root so layout flows */
    var ann = document.getElementById('bubbletheme-announcement');
    if (ann) {
        var dismissed = false;
        try { dismissed = BT.annDismiss && window.localStorage.getItem(BT.annKey) === '1'; } catch (e) { /* storage blocked */ }
        if (dismissed) {
            ann.remove();
        } else {
            document.body.insertBefore(ann, document.body.firstChild);
            ann.style.display = 'flex';
            var close = document.getElementById('bubbletheme-announcement-close');
            if (close) {
                close.addEventListener('click', function () {
                    ann.remove();
                    try { window.localStorage.setItem(BT.annKey, '1'); } catch (e) { /* storage blocked */ }
                });
            }
        }
    }

    /* Head extras: SEO description, browser theme color, favicon, PWA */
    function headAdd(tag, attrs) {
        var el = document.createElement(tag);
        for (var k in attrs) { el.setAttribute(k, attrs[k]); }
        document.head.appendChild(el);
        return el;
    }
    if (BT.seoDesc && !document.querySelector('meta[name="description"]')) {
        headAdd('meta', { 'name': 'description', 'content': BT.seoDesc });
    }
    var themeMeta = document.querySelector('meta[name="theme-color"]');
    if (themeMeta) { themeMeta.setAttribute('content', BT.themeColor); }
    else { headAdd('meta', { 'name': 'theme-color', 'content': BT.themeColor }); }
    if (BT.favicon) {
        var icons = document.querySelectorAll('link[rel*="icon"]');
        for (var i = 0; i < icons.length; i++) { icons[i].parentNode.removeChild(icons[i]); }
        headAdd('link', { 'rel': 'icon', 'href': BT.favicon });
    }
    if (BT.pwa && !document.querySelector('link[rel="manifest"]')) {
        headAdd('link', { 'rel': 'manifest', 'href': BT.manifest });
    }

    /* Navigation: custom logo + support links. Idempotent + re-applied via
       a MutationObserver because React can remount the navigation bar. */
    var NAV_ICONS = {
        discord: '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M20.32 4.37a19.8 19.8 0 0 0-4.89-1.52.07.07 0 0 0-.08.04c-.21.38-.44.87-.6 1.25a18.3 18.3 0 0 0-5.5 0 12.6 12.6 0 0 0-.61-1.25.07.07 0 0 0-.08-.04c-1.71.3-3.35.81-4.89 1.52a.06.06 0 0 0-.03.03C.53 9.05-.32 13.58.1 18.06c0 .02.01.04.03.05a19.9 19.9 0 0 0 6 3.03.08.08 0 0 0 .08-.03c.46-.63.87-1.3 1.22-2a.08.08 0 0 0-.04-.11 13.1 13.1 0 0 1-1.87-.9.08.08 0 0 1-.01-.12c.13-.1.25-.19.37-.29a.07.07 0 0 1 .08-.01c3.93 1.8 8.18 1.8 12.06 0a.07.07 0 0 1 .08.01c.12.1.24.2.37.29a.08.08 0 0 1-.01.13c-.6.35-1.22.64-1.87.89a.08.08 0 0 0-.04.11c.36.7.77 1.37 1.22 2a.08.08 0 0 0 .08.03 19.8 19.8 0 0 0 6.02-3.03.08.08 0 0 0 .03-.05c.5-5.18-.84-9.68-3.55-13.66a.06.06 0 0 0-.03-.03zM8.02 15.33c-1.18 0-2.16-1.08-2.16-2.42 0-1.33.96-2.42 2.16-2.42 1.21 0 2.18 1.1 2.16 2.42 0 1.34-.96 2.42-2.16 2.42zm7.97 0c-1.18 0-2.15-1.08-2.15-2.42 0-1.33.95-2.42 2.15-2.42 1.21 0 2.18 1.1 2.16 2.42 0 1.34-.95 2.42-2.16 2.42z"/></svg>',
        support: '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm0 4a6 6 0 0 1 3.1.87l-2.2 2.2a3 3 0 0 0-1.8 0l-2.2-2.2A6 6 0 0 1 12 6zM6.87 8.9l2.2 2.2a3 3 0 0 0 0 1.8l-2.2 2.2a6 6 0 0 1 0-6.2zm5.13 9.1a6 6 0 0 1-3.1-.87l2.2-2.2a3 3 0 0 0 1.8 0l2.2 2.2a6 6 0 0 1-3.1.87zm5.13-2.9-2.2-2.2a3 3 0 0 0 0-1.8l2.2-2.2a6 6 0 0 1 0 6.2z"/></svg>',
        status: '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M3 13h3.2l2.1-5.6a1 1 0 0 1 1.88.04l2.92 8.75 1.98-3.71A1 1 0 0 1 16 12h5a1 1 0 1 1 0 2h-4.4l-2.72 5.1a1 1 0 0 1-1.83-.15L9.2 10.6l-1.26 3.75A1 1 0 0 1 7 15H3a1 1 0 1 1 0-2z"/></svg>'
    };

    function navLabelFor(el) {
        if (el.classList.contains('bubbletheme-navlink')) { return el.title || ''; }
        if (el.classList.contains('navigation-link')) { return 'Search'; }
        if (el.tagName === 'BUTTON') { return 'Sign out'; }
        if (el.tagName !== 'A') { return ''; }
        var href = el.getAttribute('href') || '';
        if (href === '/') { return 'Servers'; }
        if (href.indexOf('/admin') === 0) { return 'Admin area'; }
        if (href.indexOf('/account') === 0) { return 'Account'; }
        return '';
    }

    function applyNavExtras() {
        /* Custom logo (+ optional title removal) */
        var logoLink = document.querySelector('#logo a');
        if (logoLink && !logoLink.querySelector('img.bubbletheme-logo')) {
            if (BT.hideTitle) { logoLink.textContent = ''; }
            var img = document.createElement('img');
            img.className = 'bubbletheme-logo';
            img.src = BT.logoUrl;
            img.alt = 'Panel logo';
            logoLink.insertBefore(img, logoLink.firstChild);
        }

        var nav = document.querySelector('[class*="RightNavigation"]');
        if (!nav) { return; }

        /* Support links, placed before the last nav item (sign-out) */
        if (!nav.querySelector('a.bubbletheme-navlink')) {
            [['discord', BT.discord, 'Discord'], ['support', BT.support, 'Support'], ['status', BT.status, 'Status page']].forEach(function (entry) {
                if (!entry[1]) { return; }
                var a = document.createElement('a');
                a.className = 'bubbletheme-navlink';
                a.href = entry[1];
                a.target = '_blank';
                a.rel = 'noopener noreferrer';
                a.title = entry[2];
                a.setAttribute('aria-label', entry[2]);
                a.innerHTML = NAV_ICONS[entry[0]];
                nav.insertBefore(a, nav.lastElementChild);
            });
        }

        /* Sidebar-mode extras: item labels, section headers, account chip.
           Elements are display:none outside sidebar mode (theme.css §16). */
        if (BT.sidebar) {
            if (!nav.querySelector('.bubbletheme-navlabel')) {
                Array.prototype.forEach.call(nav.children, function (el) {
                    var text = navLabelFor(el);
                    if (!text) { return; }
                    var label = document.createElement('span');
                    label.className = 'bubbletheme-navlabel';
                    label.textContent = text;
                    el.appendChild(label);
                });
            }
            if (!nav.querySelector('.bubbletheme-navsection')) {
                var mainHead = document.createElement('span');
                mainHead.className = 'bubbletheme-navsection';
                mainHead.textContent = 'Main menu';
                nav.insertBefore(mainHead, nav.firstElementChild);
                var firstSupport = nav.querySelector('a.bubbletheme-navlink');
                if (firstSupport) {
                    var supportHead = document.createElement('span');
                    supportHead.className = 'bubbletheme-navsection';
                    supportHead.textContent = 'Support';
                    nav.insertBefore(supportHead, firstSupport);
                }
            }
            var container = nav.parentElement;
            var user = window.PterodactylUser;
            if (container && user && user.username && !container.querySelector('.bubbletheme-userchip')) {
                var chip = document.createElement('div');
                chip.className = 'bubbletheme-userchip';
                var dot = document.createElement('span');
                dot.className = 'bubbletheme-userchip-dot';
                dot.textContent = String(user.username).charAt(0);
                var info = document.createElement('div');
                var userName = document.createElement('strong');
                userName.textContent = user.username;
                var userMail = document.createElement('small');
                userMail.textContent = user.email || '';
                info.appendChild(userName);
                info.appendChild(userMail);
                chip.appendChild(dot);
                chip.appendChild(info);
                container.insertBefore(chip, nav);
            }
        }
    }
    applyNavExtras();
    var navPending = false;
    new MutationObserver(function () {
        if (navPending) { return; }
        navPending = true;
        window.requestAnimationFrame(function () {
            navPending = false;
            applyNavExtras();
        });
    }).observe(document.body, { childList: true, subtree: true });

    /* Hotkeys — "g" then a key. Navigation is a full page load (the SPA
       router isn't reachable from outside the bundle), which is fine. */
    if (BT.hotkeys) {
        var overlay = document.getElementById('bubbletheme-hotkeys');
        var lastG = 0;
        var serverMatch = function () {
            var m = window.location.pathname.match(/^\/server\/([^\/]+)/);
            return m ? m[1] : null;
        };
        var go = function (path) { window.location.assign(path); };
        document.addEventListener('keydown', function (e) {
            var t = e.target;
            if (t && (t.tagName === 'INPUT' || t.tagName === 'TEXTAREA' || t.tagName === 'SELECT' || t.isContentEditable)) { return; }
            if (e.ctrlKey || e.metaKey || e.altKey) { return; }
            if (e.key === '?') {
                if (overlay) { overlay.classList.toggle('active'); }
                e.preventDefault();
                return;
            }
            if (e.key === 'Escape' && overlay && overlay.classList.contains('active')) {
                overlay.classList.remove('active');
                return;
            }
            if (e.key === 'g') { lastG = Date.now(); return; }
            if (Date.now() - lastG > 1200) { return; }
            lastG = 0;
            var sid = serverMatch();
            var globalMap = { h: '/', a: '/account', k: '/account/api', v: '/account/activity' };
            var serverMap = { c: '', f: '/files', d: '/databases', t: '/schedules', u: '/users', b: '/backups', n: '/network', e: '/startup', x: '/settings', l: '/activity' };
            if (Object.prototype.hasOwnProperty.call(globalMap, e.key)) {
                go(globalMap[e.key]);
            } else if (sid && Object.prototype.hasOwnProperty.call(serverMap, e.key)) {
                go('/server/' + sid + serverMap[e.key]);
            }
        });
        if (overlay) {
            overlay.addEventListener('click', function (e) {
                if (e.target === overlay) { overlay.classList.remove('active'); }
            });
        }
    }
})();
</script>

{{-- 6. Admin-supplied custom CSS (Bubble Editor › Custom CSS) --}}
@if (trim($bt['custom_css']) !== '')
    <style>
        {!! str_ireplace('</style', '<\/style', $bt['custom_css']) !!}
    </style>
@endif
