<?php
/**
 * DPG Media Magazines -  WordPress EventApi
 *
 * @package   DPG_WP_EventApi
 */

namespace DPG\WordPress\EventApi\Admin;

use Timber;

class Menu {
	protected string $parent_plugin_name = 'sm-main';

	public function __construct() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'admin_menu', [ $this, 'add_event_page' ] );
	}

	/**
	 * @return $this
	 */
	public function add_event_page(): self {
		add_submenu_page(
			'sm-main',
			'DPG EventApi Setup',
			'DPG EventApi',
			'manage_options',
			'DPG Event API',
			[
				$this,
				'display_plugin_setup_page',
			]
		);

		return $this;
	}

	/**
	 * Just simple handler of showing the admin template.
	 *
	 * @return void
	 */
	public function display_plugin_setup_page(): void {
		$context          = Timber::context();
		$context['title'] = 'DPG Event API';

		Timber::render( 'admin/options.twig', $context );
	}
}
