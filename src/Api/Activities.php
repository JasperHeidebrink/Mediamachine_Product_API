<?php

namespace DPG\WordPress\EventApi\Api;

use DPG\WordPress\EventApi\Models\Activity;

class Activities {
	/**
	 * @return array
	 */
	public static function getAll( bool $hideArchive = true ): array {
		$current_edition_id = (int) get_option( 'eventapi_edition_id' );
		$response           = EventApi::get( 'activities', [ 'editionId' => $current_edition_id ] );

		if ( empty( $response ) ) {
			return [
				self::fillItem( [ 'title' => 'No activity available' ] ),
			];
		}

		$events = [];
		foreach ( $response as $item ) {
			if ( $hideArchive && ! empty( $item['archived'] ) ) {
				continue;
			}
			$events[ urlencode( trim( $item['title'] ) ) ] = self::fillItem( $item );
		}

		ksort( $events );

		return $events;
	}

	/**
	 * @return array
	 */
	public static function getGroupedByTimeslot(): array {
		$current_edition_id = (int) get_option( 'eventapi_edition_id' );
		$activities         = EventApi::get(
			'activities',
			[ 'editionId' => $current_edition_id ],
			[ 'expand' => true ]
		);

		if ( empty( $activities ) ) {
			return [
				self::fillItem( [ 'title' => 'No activity available' ] ),
			];
		}

		$activities = self::splitActivitiesFromTimeslots( $activities );

		return self::activitiesSort( $activities );
	}

	/**
	 * @return array
	 */
	public static function getDaysList(): array {
		$current_edition_id = (int) get_option( 'eventapi_edition_id' );
		$activities         = EventApi::get(
			'activities',
			[ 'editionId' => $current_edition_id ],
			[ 'expand' => true ]
		);

		if ( empty( $activities ) ) {
			return [
				self::fillItem( [ 'title' => 'No activity available' ] ),
			];
		}
		$dayActivities = [];
		foreach ( $activities as $activity ) {
			$dayActivities[ $activity['dateActive'] ] = $activity;
		}

		ksort( $dayActivities );

		return $dayActivities;
	}

	/**
	 * @return array
	 */
	public static function getCategoryList(): array {
		$current_edition_id = (int) get_option( 'eventapi_edition_id' );
		$activities         = EventApi::get(
			'activities',
			[ 'editionId' => $current_edition_id ],
			[ 'expand' => true ]
		);

		if ( empty( $activities ) ) {
			return [
				self::fillItem( [ 'title' => 'No activity available' ] ),
			];
		}
		$dayActivities = [];
		foreach ( $activities as $activity ) {
			$dayActivities[ $activity['type'] ] = $activity;
		}

		ksort( $dayActivities );

		return $dayActivities;
	}

	/**
	 * @param array $data
	 *
	 * @return Activity
	 */
	public static function fillItem( array $data ): Activity {
		$timebox = $data['timeboxes'][0] ?? [ 'location' => '', 'sublocation' => '' ];

		return new Activity(
			$data['id'] ?? 0,
			wp_strip_all_tags( $data['title'] ?? '' ),
			wp_strip_all_tags( $data['type'] ?? '' ),
			intval( $data['active'] ?? 1 ),
			$data['dateActive'] ?? '',
			self::escape_image($data['media'][0]['url'] ?? '' ),
			wp_strip_all_tags( $timebox['location'] ?? '' ),
			wp_strip_all_tags( $timebox['sublocation'] ?? '' ),
			$data['website'] ?? '',
			$data['readmore'] ?? '',
			$data['timeboxes'] ?? [],
		);
	}

	/**
	 * Special function to allow "'" in urls see DICO-472.
	 *
	 * @param string $image_url
	 *
	 * @return string
	 */
	private static function escape_image( string $image_url ): string {
		if ( ! $image_url ) {
			return '';
		}

		$image_url = str_replace( "'", "%27", $image_url);
		return wp_strip_all_tags( $image_url );
	}

	/**
	 * @param array $activities
	 *
	 * @return array
	 */
	private static function splitActivitiesFromTimeslots( array $activities ) {
		$dayActivities = [];
		foreach ( $activities as $activity ) {
			if (
				strtotime( $activity['dateActive'] ) > time() ||
				! $activity['active'] ||
				! empty( $activity['archived'] )
			) {
				continue;
			}

			$timeboxes = $activity['timeboxes'];

			// sort timeboxes on starttime
			usort( $timeboxes, function ( $a, $b ) {
				return ( $a['timestampStart'] < $b['timestampStart'] ) ? - 1 : 1;
			} );

			$activity_type = strtolower( trim( wp_strip_all_tags( $activity['type'] ) ) );

			foreach ( $timeboxes as $timebox ) {
				$readmore = wp_strip_all_tags( $activity['readmore'] );

				$dayActivities[ $activity_type ][ $timebox['date'] ][] = [
					'title'          => wp_strip_all_tags( $activity['title'] ),
					'type'           => $activity_type,
					'date'           => $timebox['date'] ?? 0,
					'start'          => $timebox['start'] ?? 0,
					'end'            => $timebox['end'] ?? 0,
					'description'    => wp_strip_all_tags( $activity['description'] ),
					'readmore'       => $readmore,
					'image'          => wp_strip_all_tags( $activity['media'][0]['url'] ?? '' ),
					'location'       => wp_strip_all_tags( $timebox['location'] ?? '' ),
					'sublocation'    => wp_strip_all_tags( $timebox['sublocation'] ?? '' ),
					'timestampStart' => $timebox['timestampStart'] ?? 0,
					'timestampEnd'   => $timebox['timestampEnd'] ?? 0,
					'activityId'     => $activity['id'],
					'externalLink'   => ( ! str_contains( $readmore, get_home_url() ) ),
				];
			}
		}

		return $dayActivities;
	}

	/**
	 * @param array $activities
	 *
	 * @return array
	 */
	private static function activitiesSort( array $activities ): array {
		// sort on category
		ksort( $activities );

		// sort within a category on days
		foreach ( $activities as &$days_actitivies ) {
			uksort( $days_actitivies, function ( $a, $b ) {
				return strcmp( $a, $b );
			} );
		}

		// sort within a day on time slot then on title
		foreach ( $activities as &$days_actitivies ) {
			foreach ( $days_actitivies as &$category_day_actitivies ) {
				usort( $category_day_actitivies, function ( $a, $b ) {
					if ( $a['timestampStart'] === $b['timestampStart'] ) {
						return strcmp( $a['title'], $b['title'] );
					}

					return ( $a['timestampStart'] > $b['timestampStart'] ) ? 1 : - 1;
				} );
			}
		}

		return $activities;
	}
}
