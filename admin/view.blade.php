{{-- ============================================================
     Bubble Editor — the Bubble Theme admin page ({identifier} v{version})
     Rendered by admin/controller.php with $settings (see DEFAULTS).
     Self-styled (scoped to #bubble-editor) so it looks right whether
     or not the Žuvačka admin theme is enabled.
     ============================================================ --}}

<style>
    #bubble-editor {
        --be-bg: #141013;
        --be-surface: #1b1519;
        --be-raised: #241c21;
        --be-line: #3b2d36;
        --be-ink: #fff5f9;
        --be-ink-dim: #c9b6c2;
        --be-pink: #ff4a9f;
        --be-pink-deep: #e51a7a;
        --be-pink-strong: #d91470;
        --be-lime: #bdfe00;
        --be-black: #0a080a;
        font-family: 'Funnel Display', 'Satoshi', system-ui, sans-serif;
        background: var(--be-bg);
        border: 2px solid var(--be-black);
        border-radius: 12px;
        box-shadow: 6px 6px 0 0 var(--be-black);
        color: var(--be-ink);
        overflow: hidden;
        margin-bottom: 20px;
    }
    #bubble-editor * { box-sizing: border-box; }
    #bubble-editor .be-head {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 18px 22px;
        background: linear-gradient(135deg, #2a1220 0%, #1b1519 60%);
        border-bottom: 2px solid var(--be-line);
    }
    #bubble-editor .be-head img { height: 44px; width: auto; }
    #bubble-editor .be-head h3 {
        margin: 0;
        font-size: 22px;
        font-weight: 700;
        letter-spacing: -0.01em;
        color: var(--be-ink);
    }
    #bubble-editor .be-head p { margin: 2px 0 0; color: var(--be-ink-dim); font-size: 13px; }
    #bubble-editor .be-head .be-version {
        margin-left: auto;
        background: var(--be-pink);
        color: #231b20;
        font-weight: 700;
        font-size: 12px;
        padding: 4px 10px;
        border: 2px solid var(--be-black);
        border-radius: 999px;
        box-shadow: 3px 3px 0 0 var(--be-black);
    }
    #bubble-editor .be-body { display: flex; min-height: 480px; }
    #bubble-editor .be-tabs {
        flex: 0 0 200px;
        border-right: 2px solid var(--be-line);
        padding: 14px 10px;
        background: #171215;
    }
    #bubble-editor .be-tab {
        display: block;
        width: 100%;
        text-align: left;
        background: transparent;
        border: 2px solid transparent;
        border-radius: 8px;
        color: var(--be-ink-dim);
        font-family: inherit;
        font-weight: 600;
        font-size: 13px;
        padding: 9px 12px;
        margin-bottom: 4px;
        cursor: pointer;
        transition: transform 120ms ease-out, box-shadow 120ms ease-out;
    }
    #bubble-editor .be-tab:hover { color: var(--be-ink); background: var(--be-raised); }
    #bubble-editor .be-tab.active {
        color: #231b20;
        background: var(--be-pink);
        border-color: var(--be-black);
        box-shadow: 3px 3px 0 0 var(--be-black);
    }
    #bubble-editor .be-panels { flex: 1 1 auto; padding: 22px 26px; min-width: 0; }
    #bubble-editor .be-panel { display: none; }
    #bubble-editor .be-panel.active { display: block; }
    #bubble-editor .be-panel h4 {
        margin: 0 0 4px;
        font-size: 17px;
        font-weight: 700;
        color: var(--be-ink);
    }
    #bubble-editor .be-panel > p.be-hint { margin: 0 0 18px; color: var(--be-ink-dim); font-size: 13px; }
    #bubble-editor .be-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 16px; }
    #bubble-editor .be-field label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.09em;
        text-transform: uppercase;
        color: var(--be-ink-dim);
        margin-bottom: 6px;
    }
    #bubble-editor .be-field .be-sub { font-size: 12px; color: #8e7b88; margin-top: 5px; }
    #bubble-editor input[type='text'],
    #bubble-editor input[type='url'],
    #bubble-editor input[type='number'],
    #bubble-editor select,
    #bubble-editor textarea {
        width: 100%;
        background: var(--be-raised);
        border: 2px solid var(--be-line);
        border-radius: 7px;
        color: var(--be-ink);
        font-family: 'Satoshi', system-ui, sans-serif;
        font-size: 13px;
        padding: 8px 11px;
        outline: none;
        transition: border-color 120ms ease-out, box-shadow 120ms ease-out;
    }
    #bubble-editor input:focus, #bubble-editor select:focus, #bubble-editor textarea:focus {
        border-color: var(--be-pink);
        box-shadow: 3px 3px 0 0 var(--be-pink-deep);
    }
    #bubble-editor textarea { font-family: ui-monospace, Menlo, Consolas, monospace; min-height: 180px; resize: vertical; }
    #bubble-editor input[type='color'] {
        width: 46px;
        height: 36px;
        padding: 2px;
        background: var(--be-raised);
        border: 2px solid var(--be-line);
        border-radius: 7px;
        cursor: pointer;
    }
    #bubble-editor .be-colorrow { display: flex; align-items: center; gap: 10px; }
    #bubble-editor .be-colorrow code { color: var(--be-ink-dim); font-size: 12px; }
    #bubble-editor .be-toggle { display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none; }
    #bubble-editor .be-toggle input { position: absolute; opacity: 0; }
    #bubble-editor .be-toggle .be-pill {
        flex: 0 0 auto;
        width: 44px;
        height: 24px;
        border: 2px solid var(--be-black);
        border-radius: 999px;
        background: var(--be-raised);
        position: relative;
        transition: background 140ms ease-out;
    }
    #bubble-editor .be-toggle .be-pill::after {
        content: '';
        position: absolute;
        top: 1px;
        left: 2px;
        width: 16px;
        height: 16px;
        border-radius: 999px;
        background: var(--be-ink);
        border: 1px solid var(--be-black);
        transition: transform 140ms ease-out;
    }
    #bubble-editor .be-toggle input:checked + .be-pill { background: var(--be-pink); }
    #bubble-editor .be-toggle input:checked + .be-pill::after { transform: translateX(19px); }
    #bubble-editor .be-toggle .be-toggle-text { font-size: 13px; font-weight: 600; color: var(--be-ink); }
    #bubble-editor .be-presets { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px; margin-bottom: 20px; }
    #bubble-editor .be-preset {
        border: 2px solid var(--be-line);
        border-radius: 9px;
        background: var(--be-raised);
        padding: 10px;
        cursor: pointer;
        text-align: left;
        font-family: inherit;
        color: var(--be-ink);
        transition: transform 120ms ease-out, box-shadow 120ms ease-out, border-color 120ms ease-out;
    }
    #bubble-editor .be-preset:hover { transform: translate(-1px, -1px); box-shadow: 3px 3px 0 0 var(--be-black); }
    #bubble-editor .be-preset.active { border-color: var(--be-pink); box-shadow: 3px 3px 0 0 var(--be-pink-deep); }
    #bubble-editor .be-preset .be-swatches { display: flex; gap: 4px; margin-bottom: 7px; }
    #bubble-editor .be-preset .be-swatches i { width: 18px; height: 18px; border-radius: 5px; border: 1px solid var(--be-black); }
    #bubble-editor .be-preset strong { display: block; font-size: 13px; }
    #bubble-editor .be-preset span { font-size: 11px; color: var(--be-ink-dim); }
    #bubble-editor .be-save {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 26px;
        border-top: 2px solid var(--be-line);
        background: #171215;
    }
    #bubble-editor .be-save button {
        background: var(--be-pink);
        color: #231b20;
        font-family: inherit;
        font-weight: 700;
        font-size: 14px;
        border: 2px solid var(--be-black);
        border-radius: 8px;
        padding: 10px 22px;
        cursor: pointer;
        box-shadow: 4px 4px 0 0 var(--be-black);
        transition: transform 120ms ease-out, box-shadow 120ms ease-out;
    }
    #bubble-editor .be-save button:hover { transform: translate(-1px, -1px); box-shadow: 6px 6px 0 0 var(--be-black); }
    #bubble-editor .be-save button:active { transform: translate(4px, 4px); box-shadow: 0 0 0 0 var(--be-black); }
    #bubble-editor .be-save p { margin: 0; color: var(--be-ink-dim); font-size: 12px; }
    #bubble-editor .be-callout {
        background: #2a1220;
        border: 2px solid var(--be-pink-deep);
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 13px;
        color: var(--be-ink);
        margin-bottom: 16px;
    }
    #bubble-editor table.be-features { width: 100%; border-collapse: collapse; font-size: 13px; }
    #bubble-editor table.be-features th {
        text-align: left;
        font-size: 11px;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: var(--be-ink-dim);
        border-bottom: 2px solid var(--be-line);
        padding: 8px 10px;
    }
    #bubble-editor table.be-features td { padding: 8px 10px; border-bottom: 1px solid var(--be-line); color: var(--be-ink); }
    #bubble-editor .be-badge { display: inline-block; font-size: 11px; font-weight: 700; padding: 2px 9px; border-radius: 999px; border: 1px solid var(--be-black); }
    #bubble-editor .be-badge.on { background: var(--be-lime); color: #231b20; }
    #bubble-editor .be-badge.stock { background: var(--be-raised); color: var(--be-ink-dim); border-color: var(--be-line); }
    #bubble-editor .be-badge.off { background: var(--be-raised); color: #8e7b88; border-color: var(--be-line); }
    @media (max-width: 900px) {
        #bubble-editor .be-body { flex-direction: column; }
        #bubble-editor .be-tabs { flex: none; display: flex; flex-wrap: wrap; gap: 4px; border-right: 0; border-bottom: 2px solid var(--be-line); }
        #bubble-editor .be-tab { width: auto; }
    }
</style>

@if ("{is_target}" != "true")
    <div class="callout callout-warning">
        This build of Bubble Theme targets Blueprint <code>beta-2026-05</code>, but this panel runs
        Blueprint <code>{target}</code>. Everything is expected to keep working — this is only a
        version-mismatch notice.
    </div>
@endif

<div id="bubble-editor">
    <div class="be-head">
        <img src="{webroot/public}/logo.svg" alt="Žuvačka">
        <div>
            <h3>Bubble Editor</h3>
            <p>The official Žuvačka look for your panel — no rebuild needed, changes go live on refresh.</p>
        </div>
        <span class="be-version">v{version}</span>
    </div>

    <form id="bubble-editor-form" action="" method="POST">
        {{ csrf_field() }}
        <div class="be-body">
            <nav class="be-tabs" aria-label="Bubble Editor sections">
                <button type="button" class="be-tab active" data-panel="branding">🫧 Branding</button>
                <button type="button" class="be-tab" data-panel="colors">🎨 Colors</button>
                <button type="button" class="be-tab" data-panel="type">🔤 Typography</button>
                <button type="button" class="be-tab" data-panel="layout">🧱 Layout &amp; Style</button>
                <button type="button" class="be-tab" data-panel="announce">📣 Announcements</button>
                <button type="button" class="be-tab" data-panel="links">💬 Support Links</button>
                <button type="button" class="be-tab" data-panel="seopwa">🌐 SEO &amp; PWA</button>
                <button type="button" class="be-tab" data-panel="power">⚡ Power Tools</button>
                <button type="button" class="be-tab" data-panel="features">📦 Feature Index</button>
            </nav>

            <div class="be-panels">
                {{-- BRANDING --}}
                <section class="be-panel active" data-panel="branding">
                    <h4>Branding</h4>
                    <p class="be-hint">Your logo, everywhere — navbar and auth card. Leave the URL empty to use the Žuvačka pixel bubble.</p>
                    <div class="be-grid">
                        <div class="be-field">
                            <label for="be-logo-url">Panel logo URL</label>
                            <input type="text" id="be-logo-url" name="logo_url" value="{{ $settings['logo_url'] }}" placeholder="{webroot/public}/logo.svg">
                            <p class="be-sub">Absolute URL or a path on this panel. SVG or PNG recommended.</p>
                        </div>
                        <div class="be-field">
                            <label for="be-logo-size">Logo size (px)</label>
                            <input type="number" id="be-logo-size" name="logo_size" min="16" max="71" value="{{ $settings['logo_size'] }}">
                            <p class="be-sub">Navbar height of the logo. Brandbook minimum for the Žuvačka mark: keep it chunky.</p>
                        </div>
                        <div class="be-field">
                            <label>Remove title</label>
                            <label class="be-toggle">
                                <input type="hidden" name="hide_title" value="0">
                                <input type="checkbox" name="hide_title" value="1" @if ($settings['hide_title'] === '1') checked @endif>
                                <span class="be-pill"></span>
                                <span class="be-toggle-text">Show the logo without the panel name</span>
                            </label>
                        </div>
                    </div>
                </section>

                {{-- COLORS --}}
                <section class="be-panel" data-panel="colors">
                    <h4>Color changer</h4>
                    <p class="be-hint">Pick a preset or dial in your own. One accent retints buttons, links, focus rings, nav states — the whole personality.</p>
                    <div class="be-presets" id="be-presets">
                        <input type="hidden" name="preset" id="be-preset-input" value="{{ $settings['preset'] }}">
                    </div>
                    <div class="be-grid">
                        <div class="be-field">
                            <label for="be-c-accent">Accent</label>
                            <div class="be-colorrow"><input type="color" id="be-c-accent" name="color_accent" value="{{ $settings['color_accent'] }}"><code data-echo="be-c-accent">{{ $settings['color_accent'] }}</code></div>
                        </div>
                        <div class="be-field">
                            <label for="be-c-deep">Deep accent (shadows / pressed)</label>
                            <div class="be-colorrow"><input type="color" id="be-c-deep" name="color_accent_deep" value="{{ $settings['color_accent_deep'] }}"><code data-echo="be-c-deep">{{ $settings['color_accent_deep'] }}</code></div>
                        </div>
                        <div class="be-field">
                            <label for="be-c-bg">Background</label>
                            <div class="be-colorrow"><input type="color" id="be-c-bg" name="color_bg" value="{{ $settings['color_bg'] }}"><code data-echo="be-c-bg">{{ $settings['color_bg'] }}</code></div>
                        </div>
                        <div class="be-field">
                            <label for="be-c-surface">Cards &amp; surfaces</label>
                            <div class="be-colorrow"><input type="color" id="be-c-surface" name="color_surface" value="{{ $settings['color_surface'] }}"><code data-echo="be-c-surface">{{ $settings['color_surface'] }}</code></div>
                        </div>
                        <div class="be-field">
                            <label for="be-c-ink">Text</label>
                            <div class="be-colorrow"><input type="color" id="be-c-ink" name="color_ink" value="{{ $settings['color_ink'] }}"><code data-echo="be-c-ink">{{ $settings['color_ink'] }}</code></div>
                        </div>
                    </div>
                </section>

                {{-- TYPOGRAPHY --}}
                <section class="be-panel" data-panel="type">
                    <h4>Custom font family</h4>
                    <p class="be-hint">Ships with the brand pair — Funnel Display (headings) and Satoshi (body), self-hosted. Override either, or load an extra font stylesheet.</p>
                    <div class="be-grid">
                        <div class="be-field">
                            <label for="be-font-display">Display font family</label>
                            <input type="text" id="be-font-display" name="font_display" value="{{ $settings['font_display'] }}" placeholder="Funnel Display (default)">
                        </div>
                        <div class="be-field">
                            <label for="be-font-body">Body font family</label>
                            <input type="text" id="be-font-body" name="font_body" value="{{ $settings['font_body'] }}" placeholder="Satoshi (default)">
                        </div>
                        <div class="be-field">
                            <label for="be-font-url">Font stylesheet URL</label>
                            <input type="url" id="be-font-url" name="font_url" value="{{ $settings['font_url'] }}" placeholder="https://fonts.googleapis.com/css2?family=…">
                            <p class="be-sub">Optional — loaded on the client area so custom families resolve.</p>
                        </div>
                    </div>
                </section>

                {{-- LAYOUT & STYLE --}}
                <section class="be-panel" data-panel="layout">
                    <h4>Layout &amp; style</h4>
                    <p class="be-hint">Neobrutal by default — thick frames, hard shadows, buttons that press. Flip to GlassUI for the frosted look, or dock the navigation as a sidebar.</p>
                    <div class="be-grid">
                        <div class="be-field">
                            <label for="be-style-mode">Style mode</label>
                            <select id="be-style-mode" name="style_mode">
                                <option value="brutal" @if ($settings['style_mode'] === 'brutal') selected @endif>Neobrutalist (Žuvačka default)</option>
                                <option value="glass" @if ($settings['style_mode'] === 'glass') selected @endif>GlassUI (frosted, soft shadows)</option>
                            </select>
                        </div>
                        <div class="be-field">
                            <label for="be-layout">Layout</label>
                            <select id="be-layout" name="layout">
                                <option value="topbar" @if ($settings['layout'] === 'topbar') selected @endif>Top bar (stock position)</option>
                                <option value="sidebar" @if ($settings['layout'] === 'sidebar') selected @endif>Sidebar (desktop, experimental)</option>
                            </select>
                            <p class="be-sub">Sidebar applies at ≥1024px wide; phones keep the top bar.</p>
                        </div>
                        <div class="be-field">
                            <label for="be-radius">Corner radius (px)</label>
                            <input type="number" id="be-radius" name="radius" min="0" max="24" value="{{ $settings['radius'] }}">
                            <p class="be-sub">0 = razor-sharp brutalism, 24 = full bubble.</p>
                        </div>
                        <div class="be-field">
                            <label for="be-shadow">Hard shadow offset (px)</label>
                            <input type="number" id="be-shadow" name="shadow" min="0" max="10" value="{{ $settings['shadow'] }}">
                        </div>
                        <div class="be-field">
                            <label for="be-bg-image">Background image URL</label>
                            <input type="text" id="be-bg-image" name="bg_image" value="{{ $settings['bg_image'] }}" placeholder="https://…/background.jpg">
                        </div>
                        <div class="be-field">
                            <label for="be-bg-dim">Background dim (%)</label>
                            <input type="number" id="be-bg-dim" name="bg_dim" min="0" max="95" value="{{ $settings['bg_dim'] }}">
                            <p class="be-sub">Dark overlay on top of the image so text stays readable.</p>
                        </div>
                    </div>
                </section>

                {{-- ANNOUNCEMENTS --}}
                <section class="be-panel" data-panel="announce">
                    <h4>Announcements</h4>
                    <p class="be-hint">A banner above the whole client area. Users can dismiss it (per browser) if you allow that; editing the text re-shows it for everyone.</p>
                    <div class="be-grid">
                        <div class="be-field">
                            <label>Enabled</label>
                            <label class="be-toggle">
                                <input type="hidden" name="ann_enabled" value="0">
                                <input type="checkbox" name="ann_enabled" value="1" @if ($settings['ann_enabled'] === '1') checked @endif>
                                <span class="be-pill"></span>
                                <span class="be-toggle-text">Show the announcement banner</span>
                            </label>
                        </div>
                        <div class="be-field">
                            <label for="be-ann-type">Type</label>
                            <select id="be-ann-type" name="ann_type">
                                <option value="info" @if ($settings['ann_type'] === 'info') selected @endif>Info (pink)</option>
                                <option value="success" @if ($settings['ann_type'] === 'success') selected @endif>Success (lime)</option>
                                <option value="warning" @if ($settings['ann_type'] === 'warning') selected @endif>Warning (yellow)</option>
                                <option value="danger" @if ($settings['ann_type'] === 'danger') selected @endif>Important (red)</option>
                            </select>
                        </div>
                        <div class="be-field">
                            <label>Dismissible</label>
                            <label class="be-toggle">
                                <input type="hidden" name="ann_dismiss" value="0">
                                <input type="checkbox" name="ann_dismiss" value="1" @if ($settings['ann_dismiss'] === '1') checked @endif>
                                <span class="be-pill"></span>
                                <span class="be-toggle-text">Users may close the banner</span>
                            </label>
                        </div>
                    </div>
                    <div class="be-field" style="margin-top: 16px;">
                        <label for="be-ann-text">Announcement text</label>
                        <input type="text" id="be-ann-text" name="ann_text" value="{{ $settings['ann_text'] }}" maxlength="500" placeholder="Maintenance on Friday 22:00 CET — servers keep running, panel takes a nap.">
                    </div>
                </section>

                {{-- SUPPORT LINKS --}}
                <section class="be-panel" data-panel="links">
                    <h4>Support links</h4>
                    <p class="be-hint">Icons added to the navigation. Leave a field empty to hide that icon.</p>
                    <div class="be-grid">
                        <div class="be-field">
                            <label for="be-link-discord">Discord invite</label>
                            <input type="url" id="be-link-discord" name="link_discord" value="{{ $settings['link_discord'] }}" placeholder="https://discord.gg/zuvacka">
                        </div>
                        <div class="be-field">
                            <label for="be-link-support">Support center</label>
                            <input type="url" id="be-link-support" name="link_support" value="{{ $settings['link_support'] }}" placeholder="https://zuvacka.sk/support">
                        </div>
                        <div class="be-field">
                            <label for="be-link-status">Status page</label>
                            <input type="url" id="be-link-status" name="link_status" value="{{ $settings['link_status'] }}" placeholder="https://status.zuvacka.sk">
                        </div>
                    </div>
                    <div class="be-field" style="margin-top: 16px;">
                        <label>Hotkeys</label>
                        <label class="be-toggle">
                            <input type="hidden" name="hotkeys" value="0">
                            <input type="checkbox" name="hotkeys" value="1" @if ($settings['hotkeys'] === '1') checked @endif>
                            <span class="be-pill"></span>
                            <span class="be-toggle-text">Keyboard shortcuts (press <strong>?</strong> in the client area for the cheat-sheet)</span>
                        </label>
                    </div>
                </section>

                {{-- SEO & PWA --}}
                <section class="be-panel" data-panel="seopwa">
                    <h4>SEO &amp; PWA</h4>
                    <p class="be-hint">Meta tags, favicon and an installable app manifest generated from these settings.</p>
                    <div class="be-grid">
                        <div class="be-field">
                            <label for="be-seo-desc">Meta description</label>
                            <input type="text" id="be-seo-desc" name="seo_desc" value="{{ $settings['seo_desc'] }}" maxlength="300" placeholder="Žuvačka — Minecraft hosting bez stresu.">
                        </div>
                        <div class="be-field">
                            <label for="be-theme-color">Browser theme color</label>
                            <div class="be-colorrow"><input type="color" id="be-theme-color" name="theme_color" value="{{ $settings['theme_color'] }}"><code data-echo="be-theme-color">{{ $settings['theme_color'] }}</code></div>
                        </div>
                        <div class="be-field">
                            <label for="be-favicon">Favicon URL</label>
                            <input type="text" id="be-favicon" name="favicon" value="{{ $settings['favicon'] }}" placeholder="{webroot/public}/logo.svg">
                        </div>
                        <div class="be-field">
                            <label>PWA support</label>
                            <label class="be-toggle">
                                <input type="hidden" name="pwa_enabled" value="0">
                                <input type="checkbox" name="pwa_enabled" value="1" @if ($settings['pwa_enabled'] === '1') checked @endif>
                                <span class="be-pill"></span>
                                <span class="be-toggle-text">Serve a web app manifest (installable panel)</span>
                            </label>
                        </div>
                        <div class="be-field">
                            <label for="be-pwa-name">PWA app name</label>
                            <input type="text" id="be-pwa-name" name="pwa_name" value="{{ $settings['pwa_name'] }}" maxlength="60">
                        </div>
                        <div class="be-field">
                            <label for="be-pwa-short">PWA short name</label>
                            <input type="text" id="be-pwa-short" name="pwa_short" value="{{ $settings['pwa_short'] }}" maxlength="20">
                        </div>
                    </div>
                </section>

                {{-- POWER TOOLS --}}
                <section class="be-panel" data-panel="power">
                    <h4>Power tools</h4>
                    <p class="be-hint">For when the 30 knobs above aren't enough.</p>
                    <div class="be-field">
                        <label>Admin theme</label>
                        <label class="be-toggle">
                            <input type="hidden" name="admin_theme" value="0">
                            <input type="checkbox" name="admin_theme" value="1" @if ($settings['admin_theme'] === '1') checked @endif>
                            <span class="be-pill"></span>
                            <span class="be-toggle-text">Skin this admin area in Žuvačka too</span>
                        </label>
                    </div>
                    <div class="be-field" style="margin-top: 16px;">
                        <label for="be-custom-css">Custom CSS (client area)</label>
                        <textarea id="be-custom-css" name="custom_css" spellcheck="false" placeholder="/* Injected after the theme — your rules win. */">{{ $settings['custom_css'] }}</textarea>
                        <p class="be-sub">Injected after every other stylesheet. With great power…</p>
                    </div>
                </section>

                {{-- FEATURE INDEX --}}
                <section class="be-panel" data-panel="features">
                    <h4>Feature index</h4>
                    <p class="be-hint">What this theme ships, what the stock panel already does, and what a pure theme honestly can't do.</p>
                    <table class="be-features">
                        <tr><th>Feature</th><th>Status</th></tr>
                        <tr><td>Neobrutalist Žuvačka design (full client area)</td><td><span class="be-badge on">Included</span></td></tr>
                        <tr><td>GlassUI styling mode</td><td><span class="be-badge on">Included</span></td></tr>
                        <tr><td>Multi-layout (top bar / sidebar)</td><td><span class="be-badge on">Included</span></td></tr>
                        <tr><td>Color changer + presets</td><td><span class="be-badge on">Included</span></td></tr>
                        <tr><td>Custom font family</td><td><span class="be-badge on">Included</span></td></tr>
                        <tr><td>Panel logo, size &amp; title removal</td><td><span class="be-badge on">Included</span></td></tr>
                        <tr><td>Background image + dim</td><td><span class="be-badge on">Included</span></td></tr>
                        <tr><td>Announcements (global banner)</td><td><span class="be-badge on">Included</span></td></tr>
                        <tr><td>Support links (Discord / support / status)</td><td><span class="be-badge on">Included</span></td></tr>
                        <tr><td>Hotkeys + cheat-sheet</td><td><span class="be-badge on">Included</span></td></tr>
                        <tr><td>SEO &amp; meta editor, favicon, theme color</td><td><span class="be-badge on">Included</span></td></tr>
                        <tr><td>PWA support (manifest)</td><td><span class="be-badge on">Included</span></td></tr>
                        <tr><td>Custom CSS editor</td><td><span class="be-badge on">Included</span></td></tr>
                        <tr><td>Admin theme</td><td><span class="be-badge on">Included</span></td></tr>
                        <tr><td>Console command history (arrow keys)</td><td><span class="be-badge stock">Stock panel</span></td></tr>
                        <tr><td>Console ANSI recoloring / filtering</td><td><span class="be-badge off">Not possible in a theme — xterm.js canvas</span></td></tr>
                        <tr><td>WHMCS / Paymenter billing integration</td><td><span class="be-badge off">Separate extension territory</span></td></tr>
                        <tr><td>Email builder / mail editor</td><td><span class="be-badge off">Separate extension territory</span></td></tr>
                        <tr><td>Trashbin, folder upload, share logs, page builder</td><td><span class="be-badge off">Separate extension territory</span></td></tr>
                    </table>
                </section>
            </div>
        </div>

        <div class="be-save">
            <button type="submit" name="_method" value="PATCH">Save changes</button>
            <p>Saves instantly — refresh the client area to see it. No <code>blueprint -build</code> needed for any Bubble Editor option.</p>
        </div>
    </form>
</div>

<script>
    (function () {
        'use strict';
        var root = document.getElementById('bubble-editor');
        if (!root) { return; }

        /* Tabs */
        var tabs = root.querySelectorAll('.be-tab');
        var panels = root.querySelectorAll('.be-panel');
        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                tabs.forEach(function (t) { t.classList.remove('active'); });
                panels.forEach(function (p) { p.classList.remove('active'); });
                tab.classList.add('active');
                root.querySelector('.be-panel[data-panel="' + tab.dataset.panel + '"]').classList.add('active');
            });
        });

        /* Hex echo next to color pickers */
        root.querySelectorAll('code[data-echo]').forEach(function (echo) {
            var input = document.getElementById(echo.dataset.echo);
            if (input) { input.addEventListener('input', function () { echo.textContent = input.value; }); }
        });

        /* Color presets */
        var PRESETS = {
            bubblegum: { label: 'Bubblegum', sub: 'Žuvačka default', accent: '#ff4a9f', deep: '#e51a7a', bg: '#141013', surface: '#1b1519', ink: '#fff5f9' },
            limesoda: { label: 'Lime Soda', sub: 'Zesty & loud', accent: '#bdfe00', deep: '#7fb800', bg: '#101408', surface: '#161b0e', ink: '#f8ffe9' },
            mintfreeze: { label: 'Mint Freeze', sub: 'Cool & calm', accent: '#4fd8c4', deep: '#12a38c', bg: '#0b1413', surface: '#121b19', ink: '#effffb' },
            grape: { label: 'Grape Crush', sub: 'Moody purple', accent: '#a971dc', deep: '#7a3fb8', bg: '#120e17', surface: '#191320', ink: '#f8f2ff' },
            midnight: { label: 'Midnight', sub: 'Blue-black focus', accent: '#7c8cf8', deep: '#4a5ae8', bg: '#0b0d14', surface: '#12141d', ink: '#f2f4ff' }
        };
        var presetWrap = document.getElementById('be-presets');
        var presetInput = document.getElementById('be-preset-input');
        var fields = {
            accent: document.getElementById('be-c-accent'),
            deep: document.getElementById('be-c-deep'),
            bg: document.getElementById('be-c-bg'),
            surface: document.getElementById('be-c-surface'),
            ink: document.getElementById('be-c-ink')
        };
        Object.keys(PRESETS).forEach(function (key) {
            var p = PRESETS[key];
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'be-preset' + (presetInput.value === key ? ' active' : '');
            btn.dataset.preset = key;
            btn.innerHTML = '<span class="be-swatches">'
                + '<i style="background:' + p.accent + '"></i>'
                + '<i style="background:' + p.deep + '"></i>'
                + '<i style="background:' + p.surface + '"></i>'
                + '<i style="background:' + p.ink + '"></i>'
                + '</span><strong>' + p.label + '</strong><span>' + p.sub + '</span>';
            btn.addEventListener('click', function () {
                presetInput.value = key;
                fields.accent.value = p.accent;
                fields.deep.value = p.deep;
                fields.bg.value = p.bg;
                fields.surface.value = p.surface;
                fields.ink.value = p.ink;
                root.querySelectorAll('.be-preset').forEach(function (el) { el.classList.remove('active'); });
                btn.classList.add('active');
                root.querySelectorAll('code[data-echo]').forEach(function (echo) {
                    var input = document.getElementById(echo.dataset.echo);
                    if (input) { echo.textContent = input.value; }
                });
            });
            presetWrap.appendChild(btn);
        });

        /* Manual color edits switch the preset to "custom" */
        Object.keys(fields).forEach(function (key) {
            fields[key].addEventListener('input', function () {
                presetInput.value = 'custom';
                root.querySelectorAll('.be-preset').forEach(function (el) { el.classList.remove('active'); });
            });
        });
    })();
</script>
