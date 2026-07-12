# Bubble Theme 2.0 (Žuvačka)

The official **Žuvačka** Blueprint extension for Pterodactyl 1.x — a **neobrutalist, bubble-gum**
reskin of the entire client area (thick frames, hard offset shadows, buttons that physically press)
driven by a full **Bubble Editor** admin page: every option below is saved to the database and goes
live on the next page refresh, **no rebuild needed**.

Brand source of truth: `brandbook_1.pdf` — pinks `#FF4A9F` / `#E51A7A` / `#FFC3DF`, brand black
`#231B20`, off-white `#FFF5F9`, lime `#BDFE00`, mint `#B2F2E9`; Funnel Display (primary) + Satoshi
(secondary), both self-hosted; the pixel-bubble logo never rendered below 71 px with ⅓× clear space.

---

## Features (Bubble Editor)

| Feature | How it works |
| --- | --- |
| **Neobrutalist design** | Default style: 2px frames, hard offset shadows, press physics, pink icon tiles — full client-area coverage (auth, dashboard, console, files, databases, schedules, users, backups, network, startup, settings, account) |
| **GlassUI styling** | One click flips every surface to frosted translucency + blur + soft shadows (`color-mix`-based, tracks custom colors) |
| **Multi-layout** | Top bar (stock) or a fixed left **sidebar** (desktop ≥1024px, experimental; phones keep the top bar) |
| **Color changer** | Accent, deep accent, background, surface and text pickers; derived shades (hover states, borders, gradients) are computed automatically |
| **Color presets** | Bubblegum (default), Lime Soda, Mint Freeze, Grape Crush, Midnight |
| **Panel logo** | Custom logo URL, logo size (16–71px), "remove title" toggle; also swaps the auth-card mark |
| **Custom font family** | Override display/body families + optional extra font stylesheet URL; brand pair ships self-hosted (woff2, `font-display: swap`) |
| **Background image** | Any image URL + adjustable dark dim overlay |
| **Announcements** | Global banner above the client area — info/success/warning/danger, optional per-browser dismissal; text edits re-show it for everyone |
| **Support links** | Discord / support center / status page icons injected into the navigation |
| **Hotkeys** | `g`-prefixed navigation shortcuts + `?` cheat-sheet overlay (`g h` dashboard, `g c` console, `g f` files, …) |
| **SEO & meta** | Meta description, browser theme color, custom favicon |
| **PWA support** | Web app manifest generated from settings at `/extensions/bubbletheme/manifest.webmanifest` (installable panel) |
| **Custom CSS** | Injected after every other stylesheet — your rules win |
| **Admin theme** | Toggleable Žuvačka skin for the AdminLTE admin area |

### Honest boundaries

- **Console command history** (arrow keys) is already a stock panel feature.
- **Console ANSI recoloring / log filtering** is impossible from a theme — xterm.js renders the
  terminal to a `<canvas>` inside the JS bundle. The theme styles the frame, command bar and
  scrollbars around it.
- **Billing (WHMCS/Paymenter), email builder, trashbin, folder upload, share logs, page builder,
  registration, Turnstile** are functional extensions (React components + backend), not theme
  territory — they would each be their own Blueprint extension. Nothing here pretends otherwise;
  the Feature Index tab in the Bubble Editor shows the same honest matrix.
- A few button fills are compiled into hashed CSS modules and keep Pterodactyl's semantic colors
  (blue = primary, red = danger) so destructive actions stay visually distinct.

---

## Architecture (all documented Blueprint binds)

```
bubble-theme/
├── conf.yml                     # manifest — documented keys only
├── admin/
│   ├── view.blade.php           # Bubble Editor UI (tabs, presets, toggles)
│   ├── controller.php           # index/update via dbGetMany/dbSet + AdminFormRequest validation
│   ├── admin.css                # Žuvačka admin skin, scoped under body.bubbletheme-admin
│   └── wrapper.blade.php        # flips the admin-skin body class when the toggle is on
├── dashboard/
│   ├── theme.css                # the theme: tokens → base → components → glass → sidebar → injected UI
│   └── wrapper.blade.php        # reads settings (BlueprintClientLibrary) → token overrides, fonts,
│                                #   banner, logo, support links, hotkeys, SEO/PWA, custom CSS
├── routers/web.php              # GET /manifest.webmanifest (public branding data only)
├── migrations/…default_settings # seeds missing defaults; down() removes bubbletheme::* settings
└── public/                      # fonts (woff2) + logo.svg, symlinked to /extensions/bubbletheme
```

- Settings live in Blueprint's settings store (`dbGet`/`dbSet`) — no custom tables.
- The dashboard wrapper emits a `:root` override block, so the bundled `theme.css` never needs a
  rebuild when settings change.
- Wrapper-injected UI (banner, cheat-sheet) lives at `<body>` level, outside the React root; the
  nav logo/support links are re-applied by a MutationObserver because React can remount the bar.
- All stored values are validated on save (hex/url/enum/numeric ranges) **and** re-sanitized in the
  wrapper before being emitted into CSS/JS (`json_encode` with `JSON_HEX_TAG`, hex/URL whitelists).
- The `web` router route is unauthenticated by design and serves only public branding JSON.

## Install / build / rollback

```bash
# production: copy bubbletheme.blueprint to the panel root, then
blueprint -install bubbletheme

# development: copy this repo into <pterodactyl-root>/.blueprint/dev/ then
blueprint -build     # apply to the running panel
blueprint -export    # package bubbletheme.blueprint

# rollback
blueprint -remove bubbletheme   # removes CSS/wrappers/routes and rebuilds stock
                                # assets; the migration's down() clears all
                                # bubbletheme::* settings rows when rolled back
```

Requires Blueprint (target `beta-2026-05`) and Node ≥ 22 on the panel host for the frontend rebuild
that Blueprint performs during install/remove. Bubble Editor changes themselves never need a rebuild.

## Accessibility & performance

- Brand off-white on surface: 16.9:1 (AAA); secondary text 9.4:1 (AAA); accent-as-text 6.1:1 (AA).
  White-text pink fills use `#D91470` (4.9:1) because brand `#E51A7A` misses AA by a hair —
  documented deviation. Selection uses brand-black on pink (5.4:1).
- Two variable woff2 fonts (~60 KB, preloaded, `swap`) are the only added assets; the auth logo is
  an inline data URI. Motion is 120–240 ms transform/opacity only and fully disabled under
  `prefers-reduced-motion`. Focus rings are never removed.

## Update safety

No panel source files are touched. Selectors target element types, literal utility classes,
styled-components display names and stable ids — if a future panel rename hits one group, that
group degrades to stock styling; nothing functional can break. After a panel update, re-run
`blueprint -install bubbletheme`.
