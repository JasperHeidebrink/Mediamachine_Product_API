<?php
/**
 * Usage:
 * [dpg-ep-activities]
 */

namespace DPG\WordPress\EventApi\Shortcodes;

use DPG\WordPress\EventApi\Api\Activities;
use Timber;

class Program {
	/**
	 * Render template with twig
	 *
	 * @return void
	 */
	public function show_html(): void {
		$context                    = Timber::context();
		$context['activityList']    = Activities::getSorted();
		$context['active_activity'] = key( $context['activityList'] );
		$context['default_image']   = DPG_EVENTAPI_URL . '/assets/placeholder.png';

		Timber::render( 'frontend/program.twig', $context );
	}
}
