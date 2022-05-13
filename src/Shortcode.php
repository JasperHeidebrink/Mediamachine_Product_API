<?php
/**
 * Usage:
 * [dpg-ep-activities]
 * [dpg-ep-shops]
 * [dpg-ep-program]
 */

namespace DPG\WordPress\EventApi;

use DPG\WordPress\EventApi\Shortcodes\Activity;
use DPG\WordPress\EventApi\Shortcodes\Program;
use DPG\WordPress\EventApi\Shortcodes\Shops;

class Shortcode {
	public function __construct() {
		add_shortcode( 'dpg-ep-activities', [ new Activity, 'show_html' ] );
		add_shortcode( 'dpg-ep-shops', [ new Shops, 'show_html' ] );
		add_shortcode( 'dpg-ep-program-category', [ new Program, 'show_category_html' ] );
		add_shortcode( 'dpg-ep-program', [ new Program, 'show_html' ] );
	}
}
