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

		wp_register_script(
			'dpg-event-filter',
			DPG_EVENTAPI_URL . 'assets/filter.js',
			[ 'jquery' ],
			DPG_EVENTAPI_VERSION
		);

		wp_register_script(
			'dpg-event-category-filter',
			DPG_EVENTAPI_URL . 'assets/category-filter.js',
			[ 'jquery' ],
			DPG_EVENTAPI_VERSION
		);

	}
}
