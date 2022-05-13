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
	public function show_category_html(): void {
		$context                           = Timber::context();
		$context['activityListByTimeslot'] = Activities::getGroupedByTimeslot();
		$context['active_activity']        = key( $context['activityListByTimeslot'] );
		$context['default_image']          = DPG_EVENTAPI_URL . '/assets/placeholder.png';

		Timber::render( 'frontend/program_by_category.twig', $context );
	}

	/**
	 * Render template with twig
	 *
	 * @return void
	 */
	public function show_html(): void {
		$context                           = Timber::context();
		$context['activityListByTimeslot'] = Activities::getGroupedByTimeslot();
		$context['activityList']           = Activities::getAll(true, true);
		$context['dayList']                = Activities::getDaysList();
		$context['categoryList']           = Activities::getCategoryList();
		$context['active_activity']        = key( $context['activityListByTimeslot'] );
		$context['default_image']          = DPG_EVENTAPI_URL . '/assets/placeholder.png';

		Timber::render( 'frontend/program.twig', $context );
	}
}
