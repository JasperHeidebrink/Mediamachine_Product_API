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
	 * Render template with twig
	 */
	public function show_category_html() {
		$context                           = Timber::context();
		$context['activityListByTimeslot'] = Activities::getGroupedByTimeslot();
		$context['active_activity']        = key( $context['activityListByTimeslot'] );
		$context['default_image']          = DPG_EVENTAPI_URL . '/assets/placeholder.png';

		return Timber::compile( 'frontend/program_by_category.twig', $context );
	}

	/**
	 * Render template with twig
	 */
	public function show_html() {
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
