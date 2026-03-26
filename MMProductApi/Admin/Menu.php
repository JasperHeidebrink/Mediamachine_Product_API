<?php
/**
 * DPG Media Magazines -  WordPress EventApi
 *
 * @package   DPG_WP_EventApi
 */

namespace MMProductApi\Admin;


class Menu
{

    public function __construct()
    {
        if (!is_admin()) {
            return;
        }

        add_action('admin_menu', [$this, 'add_settings_page'], 20);
        add_action('admin_init', [$this, 'registerSettings']);
    }

    /**
     * @return $this
     */
    public function add_settings_page(): self
    {
        add_menu_page(
            'MediaMachine API Settings',
            'MM API Settings',
            'manage_options',
            D_THEME . 'API',
            [
                $this,
                'display_plugin_setup_page',
            ],
            'dashicons-rest-api',
            81
        );

        return $this;
    }

    /**
     * The settings for this site than can be updated by the admin
     */
    public function registerSettings(): void
    {
        register_setting('MM_Product_API_settings', 'MM_Product_API_title');
    }

    /**
     * Just simple handler of showing the admin template.
     *
     * @return void
     */
    public function display_plugin_setup_page(): void
    {
        echo '<div id="wpbody-content">';
        echo '<div class="wrap">';
        echo '	<h2>' . __('MediaMachine API Settings', MM_API_DOMAIN) . '</h2>';
        require_once(MM_API_PATH.'views/admin/settings.php');
        echo '</div>';
    }
}
