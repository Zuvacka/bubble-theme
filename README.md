# Bubble Theme (Žuvačka)

A production-safe **Blueprint extension** that reskins the entire Pterodactyl 1.x client area in a
**Revolut-like dark foundation × neobrutalism** fusion, branded to the **Žuvačka** brandbook: deep
plum-black canvas, generous whitespace and restrained bubble-gum gradients, with thick 2px frames,
hard offset shadows and buttons that physically "press".

- **Pure CSS.** No JavaScript added, no React components, routes, props or handlers touched. Every panel
  feature works identically to stock.
- **Token-driven.** Every color, font, radius, border width, shadow offset and motion timing lives in one
  `:root` block — retint the whole theme from one place.
- **Performance-neutral.** Two variable woff2 fonts (~60 KB total, preloaded, `font-display: swap`) are
  the only added assets; the auth-card logo is an inlined data URI (zero requests). Transitions are
  150–250 ms ease-out on `transform`/`opacity`/cheap zero-blur shadows only, and
  `prefers-reduced-motion` disables all motion.

---

## Brandbook — extracted summary (Žuvačka)

Source of truth: `brandbook_1.pdf`, `zuvacka_logo.svg`, `zuvacka_logobig.png` (supplied by the client).

- **Brand**: Žuvačka — a young, playful, "bubble-gum" Minecraft hosting brand. Voice: simple, fun,
  accessible, fast, cheeky-but-kind ("Drzosť, ale milá"). Archetype: The Creator.
- **Core palette (exact values from the logo SVG)**: primary pink `#FF4A9F`, deep pink `#E51A7A`,
  highlight pink `#FFC3DF`, brand black `#231B20`. Brandbook secondaries (from the PDF): off-white
  `#FFF5F9`, lime `#BDFE00`, mint `#B2F2E9`, purple `#9B59B6`.
- **Typography**: Funnel Display (primary/display), Satoshi (secondary/body). Both self-hosted as
  variable woff2 with `font-display: swap` (17.7 KB + 42.6 KB).
- **Logo rules**: pixel-art bubble mark; never below **71 px** digital (20 mm print); clear space **⅓×**
  of the logo dimension. The theme renders it at 84 px with ~28 px clear space on the auth card, and
  ships the exact provided SVG (`public/logo.svg`, also used as the extension icon).

### Token mapping (all in `dashboard/theme.css` §1)

| Token | Value | Role |
| --- | --- | --- |
| `--bubble-bg` | `#141013` | page canvas — plum-black derived from brand `#231B20` |
| `--bubble-bg-deep` | `#0D0A0C` | top nav, console well |
| `--bubble-surface` | `#1B1519` | cards, rows |
| `--bubble-raised` | `#241C21` | inputs, card headers |
| `--bubble-overlay` | `#2D232A` | hover states |
| `--bubble-line` | `#3B2D36` | quiet borders |
| `--bubble-ink` | `#FFF5F9` | brand off-white text — 16.9:1 on surface (AAA) |
| `--bubble-ink-dim` | `#C9B6C2` | secondary text — 9.4:1 on surface (AAA) |
| `--bubble-accent` | `#FF4A9F` | logo pink — 6.1:1 as text on canvas (AA) |
| `--bubble-accent-strong` | `#D91470` | white-text fills — 4.9:1 (AA) ⚠ see deviation below |
| `--bubble-accent-deep` | `#E51A7A` | logo deep pink — shadows/pressed (non-text) |
| `--bubble-pink-light` / `--bubble-purple` | `#FFC3DF` / `#9B59B6` | gradient endpoints, decorative |
| `--bubble-green` / `--bubble-mint` | `#BDFE00` / `#B2F2E9` | brand lime/mint accents |
| `--bubble-frame` | `#FFF5F9` | neobrutal 2px frames |
| Fonts | Funnel Display (display) / Satoshi (body) | per brandbook, self-hosted |

**Documented AA deviation**: brand deep pink `#E51A7A` under white text measures **4.41:1** (fails AA by
a hair), so surfaces that carry white text use `#D91470` (same hue family, darkened; 4.9:1). Selection
highlights use brand-black text on brand pink (5.4:1) instead of white (3.1:1 — would fail).

---

## Verified technical approach

Everything below was verified this session against the bundled Blueprint documentation
(`references/configs/confyml.md`, `dashboardwrapper.md`, `placeholders.md`, CLI docs) and the actual
`pterodactyl/panel@1.0-develop` source:

- The theme is a standard Blueprint extension. `conf.yml` uses only documented binds:
  `admin.view` (required), `dashboard.css` (stylesheet compiled into the React bundle),
  `dashboard.wrapper` (blade appended outside the bundle — used solely for the self-hosted font +
  preload, via the documented `{webroot/public}` placeholder), `data.public` (font/logo files, symlinked
  to `public/extensions/bubbletheme`).
- Selectors target only stable surfaces: element selectors, literal Tailwind utility classes present in
  the DOM, styled-components displayName classes (Pterodactyl's `babel.config.js` loads
  `babel-plugin-styled-components` with defaults, so display names survive production builds), and stable
  ids (`#app`, `#logo`, `#terminal`) / vendor classes (`.xterm*`, HeadlessUI `headlessui-dialog-panel-*`
  id prefix, verified on the panel's `@headlessui/react@^1.6.4`).

## Install / build / deploy

On the panel host (Blueprint installed, Node ≥ 22 required by Blueprint for frontend rebuilds):

```bash
# --- production install from a packaged file ---
# 1. copy bubbletheme.blueprint into the Pterodactyl root (usually /var/www/pterodactyl)
blueprint -install bubbletheme

# --- development flow (from this repo) ---
# 1. copy this repo's contents into <pterodactyl-root>/.blueprint/dev/
# 2. apply to the running panel (triggers the panel asset rebuild):
blueprint -build
# 3. package for distribution (produces bubbletheme.blueprint in the panel root):
blueprint -export
```

Blueprint itself performs the frontend rebuild when installing/removing — no manual `yarn build` is
needed or supported for extensions.

## Rollback

```bash
blueprint -remove bubbletheme
```

Blueprint removes the injected CSS/wrapper/public files and rebuilds panel assets back to stock.
No manual cleanup, no data to migrate — the theme stores nothing.

## Every file in this extension

| File | Purpose |
| --- | --- |
| `conf.yml` | Extension manifest (documented keys only) |
| `dashboard/theme.css` | **The theme.** §1 design tokens, then base/nav/cards/auth/forms/buttons/console/dialogs/alerts/tables/links/responsive sections |
| `dashboard/wrapper.blade.php` | Font preloads + `@font-face` (Funnel Display + Satoshi, variable woff2, `swap`) |
| `admin/view.blade.php` | Admin page: retint + rollback instructions, version-mismatch notice |
| `public/fonts/funnel-display-latin-wght.woff2` | Brand display font, latin subset, 17.7 KB |
| `public/fonts/satoshi-variable-wght.woff2` | Brand body font, variable, 42.6 KB |
| `public/logo.svg` | Official Žuvačka logo (exact client-provided SVG) |
| `icon.svg` | Extension icon in the admin panel (same logo) |
| `README.md` | This document |

Files Blueprint touches on the panel when installing (all restored on `-remove`): it copies the admin
view/route, appends the wrapper blade to the dashboard layout, adds `theme.css` to the React bundle
imports, symlinks `public/` → `public/extensions/bubbletheme`, and rebuilds `public/assets`.

## Coverage

Auth (login / 2FA / password reset), dashboard & server list, server console page (frame, command bar,
power buttons, stat cards), file manager, databases, schedules, users/subusers, backups,
network/allocations, startup, settings/SFTP, account overview / API keys / SSH keys / activity, and the
shared chrome: navigation bar, sub-navigation, modals + HeadlessUI dialogs, flash alerts, forms, tables,
pagination, progress bar, spinners, scrollbars, route-transition fade.

Most of these screens are composed from the shared primitives this theme restyles (`GreyRowBox`,
`TitledGreyBox`, `ContentBox`, both button generations, `Input`/`Select`/`Label`, `Modal`/`Dialog`,
`MessageBox`, `SubNavigation`, pagination/spinner/progress) plus the utility repaint — which is what
keeps every view consistent without per-view forks.

## Honest limitations (deliberate, for update-safety)

1. **Console ANSI colors & terminal font** are defined inside Pterodactyl's JS bundle
   (`Console.tsx` xterm theme; xterm renders to canvas, out of CSS reach). The stock palette is already
   high-contrast and readable; the theme styles the console frame, command bar and scrollbars around it.
   Changing ANSI colors would require patching panel source — rejected as update-hostile.
2. **A few button backgrounds** are compiled into hashed CSS-module classes (blue = primary,
   red = danger, gray = text). Variants are indistinguishable to CSS in production builds, so the theme
   restyles button *geometry and press physics* everywhere but preserves those semantic fill colors —
   repainting them all Žuvačka-pink would also turn destructive "Delete" buttons pink, which is worse
   than a handful of primary buttons staying panel-blue. Everywhere the primary color IS reachable
   (literal `bg-blue-*` / `bg-primary-*` utilities, links, focus rings, nav states, selection, progress,
   spinners), it is brand pink. Fully unifying the last baked-in blues would require panel source edits
   — rejected as update-hostile.
3. The stock Pterodactyl mascot on the auth card is hidden via CSS (`display: none` on that block only);
   the form is untouched.

## Upgrade notes

The theme never patches panel source files, so panel updates only require re-running
`blueprint -install bubbletheme` afterwards (Blueprint re-applies and rebuilds). If a future panel
version renames internal components, the affected selector group degrades gracefully to stock styling
for that element — nothing can break functionally, because there is no functional surface in this
extension.
