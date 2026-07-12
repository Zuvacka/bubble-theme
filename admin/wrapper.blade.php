{{-- ============================================================
     Bubble Theme — admin wrapper ({identifier} v{version})
     Included in the admin panel layout. When the "Admin theme"
     option is on, it loads the brand fonts and flips the body
     class that scopes admin/admin.css (the Žuvačka admin skin).
     ============================================================ --}}
@php
    $bubbleAdminLib = app(\Pterodactyl\BlueprintFramework\Libraries\ExtensionLibrary\Admin\BlueprintAdminLibrary::class);
    $bubbleAdminThemeOn = (string) $bubbleAdminLib->dbGet('{identifier}', 'admin_theme', '1') === '1';
@endphp
@if ($bubbleAdminThemeOn)
    <style>
        @font-face {
            font-family: 'Funnel Display';
            font-style: normal;
            font-weight: 300 800;
            font-display: swap;
            src: url('{webroot/public}/fonts/funnel-display-latin-wght.woff2') format('woff2-variations');
        }
        @font-face {
            font-family: 'Satoshi';
            font-style: normal;
            font-weight: 300 900;
            font-display: swap;
            src: url('{webroot/public}/fonts/satoshi-variable-wght.woff2') format('woff2-variations');
        }
    </style>
    <script>document.body.classList.add('bubbletheme-admin');</script>
@endif
