<?php
/**
 * DPG Media Magazines -  WordPress EventApi
 *
 * @package   DPG_WP_EventApi
 */

namespace DPG\WordPress\EventApi;

use DPG\WordPress\EventApi\Admin\Settings;

class Admin
{
    public function __construct()
    {
        if (! is_admin()) {
            return;
        }

        if (! is_plugin_active("sm-main/sm-main.php")) {
            add_action('admin_notices', [$this, 'requirements_notices']);
        }

        $settings = new Settings();
        add_action('admin_init', [$settings, 'register_settings']);
        add_action('admin_menu', [$settings, 'add_event_page']);
    }

    /**
     * @return void
     */
    public function requirements_notices(): void
    {
        echo '<div class="notice notice-warning is-dismissible"><p>';
        esc_html_e(
            'DPG Event API plugin is currently not working: plugin sm-main is needed. Please install or activate the plugin.',
            DPG_EVENTAPI_SLUG
        );
        echo '</p></div>';
    }
}
