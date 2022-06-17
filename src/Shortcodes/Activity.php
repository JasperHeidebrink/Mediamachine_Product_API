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
	 * Used for generating (shortcode) content with Timber.
	 *
	 * @return string activity list as html/string or empty on failure.
	 */
	public function get_html(): string {
		$context                 = Timber::context();
		$context['activityList'] = Activities::getAll();

		return Timber::compile( 'frontend/activities.twig', $context ) ?: '';
	}
}
