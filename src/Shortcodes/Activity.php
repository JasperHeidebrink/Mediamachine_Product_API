<?php
/**
 * Usage:
 * [dpg-ep-activities]
 */

namespace DPG\WordPress\EventApi\Shortcodes;

use DPG\WordPress\EventApi\Api\Activities;
use Timber;

class Activity {
	/**
	 * Render template with twig
	 *
	 * @return void
	 */
	public function show_html(): void {
		$context                 = Timber::context();
		$context['activityList'] = Activities::getAll();

		Timber::render( 'frontend/activities.twig', $context );
	}
}
