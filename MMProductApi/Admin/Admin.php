<?php
/**
 * Mediamachine -  WordPress Stock API
 *
 * @package   MM_Product_API
 */

namespace MMProductApi\Admin;

//use MMAPI\Admin\Settings;

class Admin
{
    public function __construct()
    {
        if (!is_admin()) {
            return;
        }

//		$settings = new Settings();
        $menu = new Menu();
//		add_action( 'admin_init', [ $settings, 'register_settings' ] );
//		add_action( 'admin_init', [ $settings, 'register_settings_cache' ] );
        add_action('admin_init', [$this, 'check_requirements']);

        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    /**
     * Check dependency before activating this plugin
     *
     * @return bool
     */
    public static function checkDependency()
    {
        if (!is_plugin_active('woocommerce/woocommerce.php')) {
            return false;
        }

        if (!defined('MM_API_KEY')) {
            return false;
        }

        return true;
    }

    /**
     * @return void
     */
    public function check_requirements(): void
    {
        if ('Mediamachine' != wp_get_theme()) {
            add_action('admin_notices', [$this, 'requirement_theme_notice']);
        }

        if (!is_plugin_active('woocommerce/woocommerce.php')) {
            add_action('admin_notices', [$this, 'requirement_plugin_notice']);
        }

        if (!defined('MM_API_KEY')||!defined('MM_API_URL')) {
            add_action('admin_notices', [$this, 'requirement_setting_notice']);
        }
    }

    /**
     * @return void
     */
    public function requirement_theme_notice(): void
    {
        echo '<div class="notice notice-warning is-dismissible"><p>';
        esc_html_e(
            'Mediamachine theme is required. Please install or activate the theme.',
            MM_API_DOMAIN
        );
        echo '</p></div>';
    }

    /**
     * @return void
     */
    public function requirement_plugin_notice(): void
    {
        echo '<div class="notice notice-warning is-dismissible"><p>';
        esc_html_e(
            'This plugin is currently not working: plugin woocommerce is needed. Please install or activate the plugin.',
            MM_API_DOMAIN
        );
        echo '</p></div>';
    }

    /**
     * @return void
     */
    public function requirement_setting_notice(): void
    {
        echo '<div class="notice notice-warning is-dismissible"><p>';
        esc_html_e(
            'This plugin is currently not working: MM_API_KEY and MM_API_URL must be defined.',
            MM_API_DOMAIN
        );
        echo '</p></div>';
    }

    /**
     * @return void
     */
    public function enqueueScripts(): void
    {
        wp_enqueue_style(
            'mm_api_admin',
            MM_API_DIR . 'assets/admin.css',
            [],
            MM_API_VERSION
        );

        wp_enqueue_script(
            'mm_api_admin',
            MM_API_DIR . 'assets/admin.min.js',
            ['jquery'],
            MM_API_VERSION
        );

        wp_localize_script(
            'mm_api_admin',
            'mm_api_admin',
            [
                'ajax_url' => self_admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce( 'ajaxnonce'),
            ]
        );
    }
}
