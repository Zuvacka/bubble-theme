<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Bubble Theme <small>v{version}</small></h3>
            </div>
            <div class="box-body">
                <p>
                    <strong>Bubble Theme</strong> restyles the entire Pterodactyl <em>client area</em> —
                    auth pages, server list, console, file manager, databases, schedules, users, backups,
                    network, startup, settings and account pages — in a dark, Revolut-inspired look with a
                    neobrutalist component language.
                </p>
                <p>
                    The theme is <strong>pure CSS</strong>. It adds no JavaScript, changes no React components,
                    routes, props or handlers, and adds no database tables. Every panel feature keeps working
                    exactly as stock.
                </p>

                <h4>Retinting (design tokens)</h4>
                <p>
                    All colors, fonts, radii, border widths, shadow offsets and motion timings are defined
                    once as CSS custom properties at the top of
                    <code>dashboard/theme.css</code> (the <code>:root</code> block labelled
                    <strong>"1. DESIGN TOKENS"</strong>). Edit that block, rebuild with
                    <code>blueprint -build</code> (dev) or reinstall the extension, and the whole theme retints.
                </p>

                <h4>Rollback</h4>
                <p>
                    Run <code>blueprint -remove bubbletheme</code>. Blueprint removes the injected CSS/wrapper
                    and rebuilds the panel assets back to stock. No manual cleanup is needed.
                </p>

                <h4>Known limitations (by design, for update-safety)</h4>
                <ul>
                    <li>Console ANSI colors and the terminal font are set inside Pterodactyl's JavaScript bundle
                        (xterm.js renders to canvas). The theme styles the console frame, command bar and
                        scrollbars; the stock ANSI palette (which is already high-contrast) is kept.</li>
                    <li>A few button background colors are compiled into the panel bundle's hashed CSS modules
                        and keep Pterodactyl's semantic colors (blue = primary, red = danger). The theme's accent
                        was chosen to harmonize with them.</li>
                </ul>

                @if("{is_target}" != "true")
                <div class="callout callout-warning">
                    This build of Bubble Theme targets Blueprint <code>beta-2026-05</code>, but this panel runs
                    Blueprint <code>{target}</code>. Everything is expected to keep working — this is only a
                    version-mismatch notice.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
