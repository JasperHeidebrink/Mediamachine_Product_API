<?php
/**
 * Usage:
 * [dpg-ep-activities]
 */

namespace DPG\WordPress\EventApi\Shortcodes;

use DPG\WordPress\EventApi\Api\Activities;
use Timber\Timber;

class Activity {

	/**
	 * Render template with twig
	 */
	public function show_html() {
		$context                 = Timber::context();
		$context['activityList'] = Activities::getAll();

		return Timber::compile( 'frontend/program.twig', $context );
	}
}
