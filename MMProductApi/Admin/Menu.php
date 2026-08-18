<?php
/**
 * DPG Media Magazines -  WordPress EventApi
 *
 * @package   DPG_WP_EventApi
 */

namespace MMProductApi\Admin;

use MMProductApi\Cron;


class Menu
{

    public function __construct()
    {
        if (!is_admin()) {
            return;
        }

        add_action('admin_menu', [$this, 'add_settings_page'], 20);
        add_action('admin_init', [$this, 'registerSettings']);
        add_action('admin_post_mm_api_cron_settings', [$this, 'handle_cron_settings']);
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

    private function set_admin_notice(string $notice): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $_SESSION['mm_api_admin_notice'] = $notice;
    }

    public static function get_admin_notice(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['mm_api_admin_notice'])) {
            return '';
        }

        $notice = (string)$_SESSION['mm_api_admin_notice'];
        unset($_SESSION['mm_api_admin_notice']);

        return $notice;
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
        require_once(MM_API_PATH . 'views/admin/settings.php');
    }

    /**
     * Handle cron settings form submits.
     *
     * @return void
     */
    public function handle_cron_settings(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(esc_html__('You are not allowed to do this action.', MM_API_DOMAIN));
        }

        check_admin_referer('mm_api_cron_settings', 'mm_api_cron_settings_nonce');

        $minutes = Cron::DEFAULT_INTERVAL_MINUTES;
        if (isset($_POST['mm_api_interval_minutes'])) {
            $minutes = absint(wp_unslash($_POST['mm_api_interval_minutes']));
        }

        Cron::set_interval_minutes($minutes);
        Cron::reset_schedule();

        $this->set_admin_notice('cron_saved');
        wp_safe_redirect(
            add_query_arg(['page' => D_THEME . 'API'], admin_url('admin.php'))
        );
        exit;
    }

}
