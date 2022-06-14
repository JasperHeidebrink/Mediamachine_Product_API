<?php

namespace DPG\WordPress\EventApi;

class Frontend {

	public function __construct() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueueScripts' ] );
	}

	/**
	 * @return void
	 */
	public function enqueueScripts(): void {

		wp_enqueue_script(
			'dpg_eventapi',
			DPG_EVENTAPI_URL . 'assets/default.js',
			[ 'jquery' ],
			DPG_EVENTAPI_VERSION
		);

		wp_localize_script(
			'dpg_eventapi',
			'dpg_eventapi',
			[
				'ajax_url' => self_admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'ajaxnonce' ),
			]
		);
	}
}
