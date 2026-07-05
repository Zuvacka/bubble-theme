{{-- ============================================================
     Bubble Theme — dashboard wrapper ({identifier} v{version})
     Loaded OUTSIDE the React bundle. Only used for:
       1. Self-hosted display font (woff2, font-display: swap)
       2. Font preload hint
     All actual theming lives in dashboard/theme.css (bundled).
     ============================================================ --}}
<link rel="preload" href="{webroot/public}/fonts/space-grotesk-latin-wght.woff2" as="font" type="font/woff2" crossorigin>
<style>
    @font-face {
        font-family: 'Space Grotesk';
        font-style: normal;
        font-weight: 300 700;
        font-display: swap;
        src: url('{webroot/public}/fonts/space-grotesk-latin-wght.woff2') format('woff2-variations');
        unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02BB-02BC, U+02C6, U+02DA, U+02DC, U+0304, U+0308, U+0329, U+2000-206F, U+20AC, U+2122, U+2191, U+2193, U+2212, U+2215, U+FEFF, U+FFFD;
    }
</style>
