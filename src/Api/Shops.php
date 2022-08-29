<?php

namespace DPG\WordPress\EventApi\Api;

use DPG\WordPress\EventApi\Models\Exhibitor;

class Shops {

	var $shops = [];

	var $branches = [];

	public function __construct() {
		$current_edition_id = (int) get_option( 'eventapi_edition_id' );

		if ( $this->check_needed_params( $current_edition_id ) ) {
			$response = EventApi::get( 'exhibitor', [ 'editionId' => $current_edition_id ] );

			foreach ( $response['items'] as $item ) {
				$this->shops[] = $this->fillItem( $item );

				if ( $item['branches'] ) {
					foreach ( $item['branches'] as $branch ) {
						if ( ! in_array( $branch, $this->branches ) ) {
							$this->branches[] = $branch;
						}
					}
				}
			}
		}
	}

	public function get_shops() {
		return $this->shops;
	}

	public function get_shops_at_random() {
		shuffle( $this->shops );

		return $this->shops;
	}

	public function get_shops_categorised() {
		$data = [];

		foreach ( $this->shops as $shop ) {
			foreach ( $shop->getBranches() as $branch ) {

				if ( ! isset( $data[ $branch ] ) ) {
					$data[ $branch ] = [];
				}

				$data[ $branch ][] = $shop;
			}
		}

		ksort( $data );

		return $data;
	}

	public function get_categories() {
		sort( $this->branches );

		return $this->branches;
	}

	private function check_needed_params( $current_edition_id ) {

		if ( ! $current_edition_id ) {
			return false;
		}

		if ( ! defined( 'EVENTAPI_BASEURI' ) || empty( EVENTAPI_BASEURI ) ) {
			return false;
		}

		if ( ! defined( 'EVENTAPI_CLIENT_ID' ) || empty( EVENTAPI_CLIENT_ID ) ) {
			return false;
		}

		if ( ! defined( 'EVENTAPI_CLIENT_SECRET' ) || empty( EVENTAPI_CLIENT_SECRET ) ) {
			return false;
		}

		return true;
	}


	/**
	 * @param  array  $data
	 *
	 * @return Exhibitor
	 */
	private function fillItem( array $data ): Exhibitor {
		return new Exhibitor(
			$data['id'] ?? 0,
			$data['title'] ?? '',
			$data['url'] ?? '',
			$data['stand'] ?? '',
			$data['branches'] ?? '',
			$data['stand']['publication_image'] ?? ''
		);
	}
}
