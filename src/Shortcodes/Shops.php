<?php
/**
 * Usage:
 * [dpg-ep-activities]
 */

namespace DPG\WordPress\EventApi\Shortcodes;

use DPG\WordPress\EventApi\Api\Exhibitors;
use DPG\WordPress\EventApi\Models\Exhibitor;
use Timber\Timber;

class Shops {

	/**
	 * Used for generating (shortcode) content with Timber.
	 *
	 * @return string shop list as html/string or empty on failure.
	 */
	public function get_html(): string {
		$search_shop_query = '';
		if ( ! empty( $_GET['shop'] ) ) {
			$search_shop_query = filter_var( $_GET['shop'], FILTER_SANITIZE_STRING );
		}

		$context                      = Timber::context();
		$context['search_shop_query'] = $search_shop_query;
		$context['shops']             = $this->getShops( $search_shop_query );

		return Timber::compile( 'event-api-frontend/shops.twig', $context ) ?: '';
	}

	/**
	 * @param string $search_shop_query
	 *
	 * @return array
	 */
	private function getShops( string $search_shop_query ): array {
		$data       = [];
		$exhibitors = Exhibitors::getAll();

		/**
		 * @var $exhibitor Exhibitor
		 */
		foreach ( $exhibitors as $exhibitor ) {
			foreach ( $exhibitor->getBranches() as $branch ) {

				if ( ! isset( $data[ $branch ] ) ) {
					$data[ $branch ] = [ 'shops' => [] ];
				}

				$stand = $exhibitor->getStand();

				if ( $this->need_to_add_exhibitor( $stand, $search_shop_query ) ) {
					$data[ $branch ]['shops'][] = $exhibitor;
				}

				if ( empty( $data[ $branch ]['shops'] ) ) {
					unset( $data[ $branch ] );
				}
			}
		}

		ksort( $data );

		return $data;
	}

	/**
	 * @param array  $stand
	 * @param string $search_shop_query
	 *
	 * @return bool
	 */
	private function need_to_add_exhibitor( array $stand, string $search_shop_query = '' ): bool {
		if ( empty( $search_shop_query ) ) {
			return true;
		}

		if ( str_contains( strtolower( $stand['name'] ), strtolower( $search_shop_query ) ) ) {
			return true;
		}

		return false;
	}
}
