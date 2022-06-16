<?php
/**
 * Usage:
 * [dpg-ep-activities]
 */

namespace DPG\WordPress\EventApi\Shortcodes;

use DPG\WordPress\EventApi\Api\Activities;
use Timber\Timber;

class Program {

	/**
	 * Used for generating (shortcode) content with Timber.
	 *
	 * @return bool|string categorized program list as html/string or false on failure.
	 */
	public function get_category_html(): ?string {
		$context                           = Timber::context();
		$context['activityListByTimeslot'] = Activities::getGroupedByTimeslot();
		$context['active_activity']        = key( $context['activityListByTimeslot'] );
		$context['default_image']          = DPG_EVENTAPI_URL . '/assets/placeholder.png';

		return Timber::compile( 'frontend/program_by_category.twig', $context );
	}

	/**
	 * Used for generating (shortcode) content with Timber.
	 *
	 * @return bool|string program list as html/string or false on failure.
	 */
	public function get_html(): ?string {
		$context                           = Timber::context();
		$context['activityListByTimeslot'] = Activities::getGroupedByTimeslot();
		$context['activityList']           = Activities::getAll(true, true);
		$context['dayList']                = Activities::getDaysList();
		$context['categoryList']           = Activities::getCategoryList();
		$context['active_activity']        = key( $context['activityListByTimeslot'] );
		$context['default_image']          = DPG_EVENTAPI_URL . '/assets/placeholder.png';

		return Timber::compile( 'frontend/program.twig', $context );
	}
}
