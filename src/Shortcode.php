<?php

namespace DPG\WordPress\EventApi;

use DPG\WordPress\EventApi\Api\Activities;
use DPG\WordPress\EventApi\Api\Exhibitors;
use DPG\WordPress\EventApi\Api\Shops;
use Timber\Timber;

class Shortcode {

	public function __construct() {
		add_shortcode( 'dpg-ep-activities', [ $this, 'get_activities_content' ] );
		add_shortcode( 'dpg-ep-shops', [ $this, 'get_shops_content' ] );
		add_shortcode( 'dpg-ep-shops-extended', [ $this, 'get_shops_extended_content' ] );
		add_shortcode( 'dpg-ep-program-category', [ $this, 'get_program_categorized_content' ] );
		add_shortcode( 'dpg-ep-program', [ $this, 'get_program_content' ] );
	}

	/**
	 * Used for generating (shortcode) content with Timber.
	 *
	 * @return string activity list as html/string or empty on failure.
	 */
	public function get_activities_content(): string {

		$context                 = Timber::context();
		$context['activityList'] = Activities::getAll();

		return Timber::compile( 'event-api-frontend/activities.twig', $context ) ?: '';
	}

	/**
	 * Used for generating (shortcode) content with Timber.
	 *
	 * @return string shop list as html/string or empty on failure.
	 */
	public function get_shops_content( $atts = [] ): string {

		$settings = shortcode_atts(
			[
				'link_target' => '_blank',
			], $atts
		);

		$context                  = Timber::context();
		$shops                    = new Shops();
		$context['branches']      = $shops->get_shops_categorised();
		$context['default_image'] = get_option( 'event_api_default_image' ) ?? DPG_EVENTAPI_URL . '/assets/placeholder.png';
		$context['settings']      = $settings;

		wp_enqueue_script( 'dpg-event-shops' );

		return Timber::compile( 'event-api-frontend/shops.twig', $context ) ?: '';
	}

	/**
	 * Used for generating (shortcode) content with Timber.
	 *
	 * @return string shop list as html/string or empty on failure.
	 */
	public function get_shops_extended_content( $atts = [] ) {

		$settings = shortcode_atts(
			[
				'link_target' => '_blank',
				'button_text' => 'Website'
			], $atts
		);

		$context                  = Timber::context();
		$shops                    = new Shops();
		$context['shops']         = $shops->get_shops_at_random();
		$context['categories']    = $shops->get_categories();
		$context['default_image'] = get_option( 'event_api_default_image' ) ?? DPG_EVENTAPI_URL . '/assets/placeholder.png';
		$context['settings']      = $settings;

		wp_enqueue_script( 'dpg-event-shops' );

		return Timber::compile( 'event-api-frontend/shops-extended.twig', $context ) ?: '';
	}

	/**
	 * Used for generating (shortcode) content with Timber.
	 *
	 * @return string categorized program list as html/string or empty on failure.
	 */
	public function get_program_categorized_content( $atts = [] ): string {

		$settings = shortcode_atts(
			[
				'link_target'   => '_blank',
				'button_text'   => 'Lees meer',
				'button_link'   => 'website',
				'hide_location' => 'false',
				'show_price'    => 'false',
				'free_text'     => 'gratis'
			], $atts
		);

		$context                           = Timber::context();
		$context['activityListByTimeslot'] = Activities::getGroupedByTimeslot();
		$context['active_activity']        = key( $context['activityListByTimeslot'] );
		$context['default_image']          = get_option( 'event_api_default_image' ) ?? DPG_EVENTAPI_URL . '/assets/placeholder.png';
		$context['settings']               = $settings;

		wp_enqueue_script( 'dpg-event-category-filter' );

		return Timber::compile( 'event-api-frontend/program_by_category.twig', $context ) ?: '';
	}

	/**
	 * Used for generating (shortcode) content with Timber.
	 *
	 * @return string program list as html/string or empty on failure.
	 */
	public function get_program_content( $atts = [] ): string {

		$settings = shortcode_atts(
			array(
				'link_target'   => '_blank',
				'button_text'   => 'Boek je ticket nu',
				'button_link'   => 'website',
				'hide_location' => 'false',
				'show_price'    => 'false',
				'free_text'     => 'gratis'
			), $atts
		);

		$context                           = Timber::context();
		$context['activityListByTimeslot'] = Activities::getGroupedByTimeslot();
		$context['activityList']           = Activities::getAll( true, true );
		$context['dayList']                = Activities::getDaysList();
		$context['categoryList']           = Activities::getCategoryList();
		$context['active_activity']        = key( $context['activityListByTimeslot'] );
		$context['default_image']          = get_option( 'event_api_default_image' ) ?? DPG_EVENTAPI_URL . '/assets/placeholder.png';
		$context['settings']               = $settings;

		wp_enqueue_script( 'dpg-event-filter' );

		return Timber::compile( 'event-api-frontend/program.twig', $context ) ?: '';
	}
}