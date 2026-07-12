<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Pterodactyl\BlueprintFramework\Libraries\ExtensionLibrary\Admin\BlueprintAdminLibrary as BlueprintExtensionLibrary;

return new class extends Migration
{
    public function up(): void
    {
        $blueprint = app(BlueprintExtensionLibrary::class);

        // Seed only keys that don't exist yet, so reinstalling/updating the
        // theme never clobbers an admin's saved configuration.
        $defaults = [
            'logo_url' => '',
            'logo_size' => '28',
            'hide_title' => '0',
            'preset' => 'bubblegum',
            'color_accent' => '#FF4A9F',
            'color_accent_deep' => '#E51A7A',
            'color_bg' => '#141013',
            'color_surface' => '#1B1519',
            'color_ink' => '#FFF5F9',
            'font_display' => '',
            'font_body' => '',
            'font_url' => '',
            'style_mode' => 'brutal',
            'layout' => 'topbar',
            'radius' => '10',
            'shadow' => '4',
            'bg_image' => '',
            'bg_dim' => '80',
            'ann_enabled' => '0',
            'ann_text' => '',
            'ann_type' => 'info',
            'ann_dismiss' => '1',
            'link_discord' => '',
            'link_support' => '',
            'link_status' => '',
            'hotkeys' => '1',
            'seo_desc' => '',
            'theme_color' => '#FF4A9F',
            'favicon' => '',
            'pwa_enabled' => '0',
            'pwa_name' => 'Žuvačka Panel',
            'pwa_short' => 'Žuvačka',
            'custom_css' => '',
            'admin_theme' => '1',
        ];

        $missing = [];
        foreach ($defaults as $key => $value) {
            if ($blueprint->dbGet('{identifier}', $key) === null) {
                $missing[$key] = $value;
            }
        }
        if ($missing !== []) {
            $blueprint->dbSetMany('{identifier}', $missing);
        }
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'like', '{identifier}::%')->delete();
    }
};
