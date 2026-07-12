<?php

/**
 * Bubble Theme — admin controller.
 *
 * Loads and stores every Bubble Editor setting through Blueprint's
 * BlueprintExtensionLibrary database methods (dbGetMany/dbSet), following the
 * official "Your second admin page" guide. All input is validated by the
 * form request at the bottom of this file.
 */

namespace Pterodactyl\Http\Controllers\Admin\Extensions\{identifier};

use Illuminate\View\View;
use Illuminate\View\Factory as ViewFactory;
use Illuminate\Http\RedirectResponse;
use Pterodactyl\Http\Controllers\Controller;
use Pterodactyl\Http\Requests\Admin\AdminFormRequest;
use Pterodactyl\BlueprintFramework\Libraries\ExtensionLibrary\Admin\BlueprintAdminLibrary as BlueprintExtensionLibrary;

class {identifier}ExtensionController extends Controller
{
    /**
     * Every Bubble Editor setting and its default value. The dashboard
     * wrapper ships the same defaults so the theme renders correctly even
     * before the admin form is saved for the first time.
     */
    public const DEFAULTS = [
        // Branding
        'logo_url'        => '',
        'logo_size'       => '28',
        'hide_title'      => '0',
        // Colors
        'preset'          => 'bubblegum',
        'color_accent'    => '#FF4A9F',
        'color_accent_deep' => '#E51A7A',
        'color_bg'        => '#141013',
        'color_surface'   => '#1B1519',
        'color_ink'       => '#FFF5F9',
        // Typography
        'font_display'    => '',
        'font_body'       => '',
        'font_url'        => '',
        // Layout & style
        'style_mode'      => 'brutal',
        'layout'          => 'topbar',
        'radius'          => '10',
        'shadow'          => '4',
        'bg_image'        => '',
        'bg_dim'          => '80',
        // Announcements
        'ann_enabled'     => '0',
        'ann_text'        => '',
        'ann_type'        => 'info',
        'ann_dismiss'     => '1',
        // Support links
        'link_discord'    => '',
        'link_support'    => '',
        'link_status'     => '',
        // Features
        'hotkeys'         => '1',
        // SEO / PWA
        'seo_desc'        => '',
        'theme_color'     => '#FF4A9F',
        'favicon'         => '',
        'pwa_enabled'     => '0',
        'pwa_name'        => 'Žuvačka Panel',
        'pwa_short'       => 'Žuvačka',
        // Power tools
        'custom_css'      => '',
        'admin_theme'     => '1',
    ];

    public function __construct(
        private ViewFactory $view,
        private BlueprintExtensionLibrary $blueprint,
    ) {}

    public function index(): View
    {
        $stored = $this->blueprint->dbGetMany('{identifier}', array_keys(self::DEFAULTS));
        $settings = [];
        foreach (self::DEFAULTS as $key => $default) {
            $settings[$key] = ($stored[$key] ?? null) !== null && $stored[$key] !== ''
                ? $stored[$key]
                : $default;
        }
        // Text-ish fields are allowed to be genuinely empty.
        foreach (['logo_url', 'font_display', 'font_body', 'font_url', 'bg_image', 'ann_text',
                  'link_discord', 'link_support', 'link_status', 'seo_desc', 'favicon', 'custom_css'] as $key) {
            $settings[$key] = (string) ($stored[$key] ?? '');
        }

        return $this->view->make(
            'admin.extensions.{identifier}.index', [
                'root' => '/admin/extensions/{identifier}',
                'blueprint' => $this->blueprint,
                'settings' => $settings,
            ]
        );
    }

    public function update({identifier}SettingsFormRequest $request): RedirectResponse
    {
        foreach ($request->normalize() as $key => $value) {
            $this->blueprint->dbSet('{identifier}', $key, $value ?? '');
        }

        $this->blueprint->alert('success', 'Bubble Theme settings saved. Refresh the client area (Ctrl+Shift+R) to see them live — no rebuild needed.');

        return redirect()->route('admin.extensions.{identifier}.index');
    }
}

class {identifier}SettingsFormRequest extends AdminFormRequest
{
    public function rules(): array
    {
        $hexColor = ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'];
        $toggle = ['nullable', 'string', 'in:0,1'];

        return [
            'logo_url'          => ['nullable', 'string', 'max:500'],
            'logo_size'         => ['nullable', 'numeric', 'min:16', 'max:71'],
            'hide_title'        => $toggle,
            'preset'            => ['nullable', 'string', 'in:bubblegum,limesoda,mintfreeze,grape,midnight,custom'],
            'color_accent'      => $hexColor,
            'color_accent_deep' => $hexColor,
            'color_bg'          => $hexColor,
            'color_surface'     => $hexColor,
            'color_ink'         => $hexColor,
            'font_display'      => ['nullable', 'string', 'max:200'],
            'font_body'         => ['nullable', 'string', 'max:200'],
            'font_url'          => ['nullable', 'url', 'max:500'],
            'style_mode'        => ['nullable', 'string', 'in:brutal,glass'],
            'layout'            => ['nullable', 'string', 'in:topbar,sidebar'],
            'radius'            => ['nullable', 'numeric', 'min:0', 'max:24'],
            'shadow'            => ['nullable', 'numeric', 'min:0', 'max:10'],
            'bg_image'          => ['nullable', 'string', 'max:500'],
            'bg_dim'            => ['nullable', 'numeric', 'min:0', 'max:95'],
            'ann_enabled'       => $toggle,
            'ann_text'          => ['nullable', 'string', 'max:500'],
            'ann_type'          => ['nullable', 'string', 'in:info,success,warning,danger'],
            'ann_dismiss'       => $toggle,
            'link_discord'      => ['nullable', 'url', 'max:500'],
            'link_support'      => ['nullable', 'url', 'max:500'],
            'link_status'       => ['nullable', 'url', 'max:500'],
            'hotkeys'           => $toggle,
            'seo_desc'          => ['nullable', 'string', 'max:300'],
            'theme_color'       => $hexColor,
            'favicon'           => ['nullable', 'string', 'max:500'],
            'pwa_enabled'       => $toggle,
            'pwa_name'          => ['nullable', 'string', 'max:60'],
            'pwa_short'         => ['nullable', 'string', 'max:20'],
            'custom_css'        => ['nullable', 'string', 'max:20000'],
            'admin_theme'       => $toggle,
        ];
    }

    public function attributes(): array
    {
        return [
            'logo_url' => 'Panel Logo URL',
            'logo_size' => 'Logo Size',
            'hide_title' => 'Remove Title',
            'preset' => 'Color Preset',
            'color_accent' => 'Accent Color',
            'color_accent_deep' => 'Deep Accent Color',
            'color_bg' => 'Background Color',
            'color_surface' => 'Surface Color',
            'color_ink' => 'Text Color',
            'font_display' => 'Display Font Family',
            'font_body' => 'Body Font Family',
            'font_url' => 'Font Stylesheet URL',
            'style_mode' => 'Style Mode',
            'layout' => 'Layout',
            'radius' => 'Corner Radius',
            'shadow' => 'Shadow Offset',
            'bg_image' => 'Background Image URL',
            'bg_dim' => 'Background Dim',
            'ann_enabled' => 'Announcement Enabled',
            'ann_text' => 'Announcement Text',
            'ann_type' => 'Announcement Type',
            'ann_dismiss' => 'Announcement Dismissible',
            'link_discord' => 'Discord Link',
            'link_support' => 'Support Link',
            'link_status' => 'Status Page Link',
            'hotkeys' => 'Hotkeys',
            'seo_desc' => 'Meta Description',
            'theme_color' => 'Browser Theme Color',
            'favicon' => 'Favicon URL',
            'pwa_enabled' => 'PWA Enabled',
            'pwa_name' => 'PWA App Name',
            'pwa_short' => 'PWA Short Name',
            'custom_css' => 'Custom CSS',
            'admin_theme' => 'Admin Theme',
        ];
    }
}
