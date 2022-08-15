<?php

namespace DPG\WordPress\EventApi\Api;

use DPG\WordPress\EventApi\Models\Exhibitor;

class Exhibitors {
	/**
	 * @return array
	 */
	public static function getAll(): array {
		$current_edition_id = (int) get_option( 'eventapi_edition_id' );
		$response           = EventApi::get( 'exhibitor', [ 'editionId' => $current_edition_id ] );

		if ( empty( $response ) ) {
			return [
				self::fillItem( [ 'title' => 'No exhibitors available' ] ),
			];
		}

		$events = [];
		foreach ( $response['items'] as $item ) {
			$events[] = self::fillItem( $item );
		}

		return $events;
	}

	/**
	 * @param array $data
	 *
	 * @return Exhibitor
	 */
	public static function fillItem( array $data ): Exhibitor {
		return new Exhibitor(
			$data['id'] ?? 0,
			$data['title'] ?? '',
			$data['url'] ?? '',
			$data['stand'] ?? '',
			$data['branches'] ?? '',
		);
	}

	/**
	 * @param string $search_shop_query
	 *
	 * @return array
	 */
	public function getShops( string $search_query = '', $divide_in_categories = true ): array {
		$data               = [];
		$data['categories'] = [];
		$exhibitors         = self::getAll();

		/**
		 * @var $exhibitor Exhibitor
		 */
		foreach ( $exhibitors as $exhibitor ) {
			if ( ! self::need_to_add_exhibitor( $exhibitor->getStand(), $search_query ) ) {
				continue;
			}

			if ( $divide_in_categories ) {
				unset( $data['categories'] );

				foreach ( $exhibitor->getBranches() as $branch ) {

					if ( ! isset( $data[ $branch ] ) ) {
						$data[ $branch ] = [ 'shops' => [] ];
					}

					$data[ $branch ]['shops'][] = $exhibitor;

					if ( empty( $data[ $branch ]['shops'] ) ) {
						unset( $data[ $branch ] );
					}
				}
			} else {
				foreach ( $exhibitor->getBranches() as $branch ) {
					if ( ! in_array( $branch, $data['categories'] ) ) {
						$data['categories'][] = $branch;
					}
				}
				$data['exhibitors'][] = $exhibitor;
			}
		}

		if ( $divide_in_categories ) {
			ksort( $data );
		} else {
			sort( $data['categories'] );
			shuffle( $data['exhibitors'] );
		}

		return $data;
	}

	/**
	 * @param array $stand
	 * @param string $search_shop_query
	 *
	 * @return bool
	 */
	private static function need_to_add_exhibitor( array $stand, string $search_shop_query = '' ): bool {
		if ( empty( $search_shop_query ) ) {
			return true;
		}

		if ( str_contains( strtolower( $stand['name'] ), strtolower( $search_shop_query ) ) ) {
			return true;
		}

		return false;
	}


}
