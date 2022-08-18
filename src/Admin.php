<?php
/**
 * DPG Media Magazines -  WordPress EventApi
 *
 * @package   DPG_WP_EventApi
 */

namespace DPG\WordPress\EventApi;

use DPG\WordPress\EventApi\Admin\Menu;
use DPG\WordPress\EventApi\Admin\Settings;

class Admin {
	public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		$settings = new Settings();
		$menu     = new Menu();
		add_action( 'admin_init', [ $settings, 'register_settings' ] );
		add_action( 'admin_init', [ $settings, 'register_settings_cache' ] );
		add_action( 'admin_menu', [ $menu, 'add_event_page' ] );
        add_action( 'admin_init', [ $this, 'check_requirements' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'enqueueScripts' ] );
	}

    /**
     * @return void
     */
    public function check_requirements():void {
        if ( ! is_plugin_active( "sm-main/sm-main.php" ) ) {
            add_action( 'admin_notices', [ $this, 'requirement_plugin_notice' ] );
        }

        if ( ! defined( 'EVENTAPI_BASEURI' ) ) {
            add_action( 'admin_notices', [ $this, 'requirement_setting_notice' ] );
        }
    }

	/**
	 * @return void
	 */
	public function requirement_plugin_notice(): void {
		echo '<div class="notice notice-warning is-dismissible"><p>';
		esc_html_e(
			'DPG Event API plugin is currently not working: plugin sm-main is needed. Please install or activate the plugin.',
			DPG_EVENTAPI_SLUG
		);
		echo '</p></div>';
	}

	/**
	 * @return void
	 */
	public function requirement_setting_notice(): void {
		echo '<div class="notice notice-warning is-dismissible"><p>';
		esc_html_e(
			'DPG Event API plugin is currently not working: EVENTAPI_BASEURI must be defined.',
			DPG_EVENTAPI_SLUG
		);
		echo '</p></div>';
	}

	/**
	 * @return void
	 */
	public function enqueueScripts(): void {
		wp_enqueue_media();
		wp_enqueue_script(
			'dpg_eventapi_admin',
			DPG_EVENTAPI_URL . 'assets/admin.js',
			[ 'jquery' ],
			DPG_EVENTAPI_VERSION
		);

		wp_localize_script(
			'dpg_eventapi_admin',
			'dpg_eventapi_admin',
			[
				'ajax_url' => self_admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'ajaxnonce' ),
			]
		);
	}
}
