# Bubble Theme

A production-safe **Blueprint extension** that reskins the entire Pterodactyl 1.x client area in a
**Revolut-like dark foundation × neobrutalism** fusion: deep near-black canvas, generous whitespace and
restrained gradients, with thick 2px frames, hard offset shadows and buttons that physically "press".

- **Pure CSS.** No JavaScript added, no React components, routes, props or handlers touched. Every panel
  feature works identically to stock.
- **Token-driven.** Every color, font, radius, border width, shadow offset and motion timing lives in one
  `:root` block — retint the whole theme from one place.
- **Performance-neutral.** One 22 KB variable woff2 (preloaded, `font-display: swap`) is the only added
  asset. Transitions are 150–250 ms ease-out on `transform`/`opacity`/cheap zero-blur shadows only, and
  `prefers-reduced-motion` disables all motion.

---

## ⚠️ Brandbook status

**No brandbook file arrived with the build request** (the working environment was searched — no upload was
present). Per the brief's fallback, the theme ships with the **Bubble default palette** below, chosen so
that every text/background pair passes **WCAG AA** and so the accent harmonizes with the panel's baked-in
semantic colors. When the real brandbook is available, update the token block in
`dashboard/theme.css` (§1 "DESIGN TOKENS") and the `@font-face` in `dashboard/wrapper.blade.php` — nothing
else needs to change.

### Shipped default tokens

| Token | Value | Role |
| --- | --- | --- |
| `--bubble-bg` | `#0A0C12` | page canvas |
| `--bubble-bg-deep` | `#06070B` | top nav, console well |
| `--bubble-surface` | `#10131C` | cards, rows |
| `--bubble-raised` | `#171B28` | inputs, card headers |
| `--bubble-overlay` | `#1D2233` | hover states |
| `--bubble-line` | `#262D42` | quiet borders |
| `--bubble-ink` | `#F2F4FB` | primary text — 16.9:1 on surface (AAA) |
| `--bubble-ink-dim` | `#A9AFC6` | secondary text — 8.5:1 on surface (AAA) |
| `--bubble-accent` | `#3B82F6` | primary accent — 5.3:1 as text on canvas (AA) |
| `--bubble-accent-strong` | `#2563EB` | filled accent, white text 5.2:1 (AA) |
| `--bubble-cyan` / `--bubble-violet` | `#38D4FF` / `#8B5CF6` | gradient endpoints, decorative only |
| `--bubble-frame` | `#E9EBF5` | neobrutal 2px frames |
| Fonts | Space Grotesk (display) / IBM Plex Sans (body) | body font is the panel's own — zero added bytes |

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
| `dashboard/wrapper.blade.php` | Font preload + `@font-face` (Space Grotesk variable, woff2, `swap`) |
| `admin/view.blade.php` | Admin page: retint + rollback instructions, version-mismatch notice |
| `public/fonts/space-grotesk-latin-wght.woff2` | Self-hosted display font, latin subset, 22 KB |
| `public/logo.svg` | Optimized Bubble mark (spare asset for branding use) |
| `icon.svg` | Extension icon in the admin panel |
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
   restyles button *geometry and press physics* everywhere but preserves those semantic colors — and the
   Bubble accent was chosen in the same blue family so they read as one system. Re-pointing them at a
   non-blue brand color would also require panel source edits.
3. The stock Pterodactyl mascot on the auth card is hidden via CSS (`display: none` on that block only);
   the form is untouched.

## Upgrade notes

The theme never patches panel source files, so panel updates only require re-running
`blueprint -install bubbletheme` afterwards (Blueprint re-applies and rebuilds). If a future panel
version renames internal components, the affected selector group degrades gracefully to stock styling
for that element — nothing can break functionally, because there is no functional surface in this
extension.
