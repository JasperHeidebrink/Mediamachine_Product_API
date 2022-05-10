<?php

namespace DPG\WordPress\EventApi\Admin;

use DPG\WordPress\EventApi\Api\Editions;
use DPG\WordPress\EventApi\Api\Events;

class Settings {
	/**
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			'eventapi_settings_admin',
			'eventapi_event_id'
		);
		register_setting(
			'eventapi_settings_admin',
			'eventapi_edition_id'
		);

		add_settings_section(
			'eventapi_settings',
			__( 'Event settings', DPG_EVENTAPI_SLUG ),
			[ $this, 'settings_info' ],
			'eventapi_settings_admin'
		);

		add_settings_field(
			'eventapi_event_id',
			__( 'Select your event', DPG_EVENTAPI_SLUG ),
			[ $this, 'dropdown_events' ],
			'eventapi_settings_admin',
			'eventapi_settings'
		);
		add_settings_field(
			'eventapi_edition_id',
			__( 'Select your edition', DPG_EVENTAPI_SLUG ),
			[ $this, 'dropdown_editions' ],
			'eventapi_settings_admin',
			'eventapi_settings'
		);
	}

	/**
	 * @return void
	 */
	public function register_settings_cache() {
		register_setting(
			'eventapi_clear_cache_admin',
			'eventapi_clear_cache',
			[ $this, 'clear_cache' ]
		);

		add_settings_section(
			'eventapi_clear_cache',
			__( 'Clear event data cache', DPG_EVENTAPI_SLUG ),
			[ $this, 'clear_cache_info' ],
			'eventapi_clear_cache_admin'
		);
	}

	/**
	 * @return void
	 */
	public function clear_cache():void {
		global $wpdb;
		$query = $wpdb->prepare(
			"DELETE FROM $wpdb->options WHERE option_name LIKE %s",
			'%' . DPG_EVENTAPI_SLUG . '%'
		);
		$wpdb->query( $query );

		$flashMessages = new FlashMessages();
		$flashMessages->set_flash_messages(
			__(
				'Event cache is cleared',
				DPG_EVENTAPI_SLUG
			),
			'updated'
		);
	}

	/**
	 * Print the Section header
	 */
	public function settings_info(): void {
		echo '<hr>';
		_e( 'Enter your settings below:', DPG_EVENTAPI_SLUG );
	}

	/**
	 * Print the Section header
	 */
	public function clear_cache_info(): void {
		echo '<hr>';
		_e( 'Clear the complete event cache. <em>( backend and frontend )</em>', DPG_EVENTAPI_SLUG );
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function dropdown_events(): void {
		$current_event_id = (int) get_option( 'eventapi_event_id' );
		$events           = Events::getAll();

		echo '<select id="eventapi_event_id" name="eventapi_event_id">';
		echo '<option value="">' . __( 'Please select a event', DPG_EVENTAPI_SLUG ) . '</option>';
		foreach ( $events as $event ) {
			$selected = ( $current_event_id === $event->getId() ) ? 'selected="selected"' : '';
			echo '<option value="' . $event->getId() . '" ' . $selected . '>' . $event->getTitle() . '</option>';
		}
		echo '</select>';
	}

	/**
	 * Get the settings option array and print one of its values
	 */
	public function dropdown_editions(): void {
		$current_event_id = (int) get_option( 'eventapi_event_id' );
		$current_edition_id = (int) get_option( 'eventapi_edition_id' );
		$editions = Editions::getAll( $current_event_id );

		if ( empty( $current_event_id ) ) {
			_e( 'First you have to select a event', DPG_EVENTAPI_SLUG );

			return;
		}

		echo '<select id="eventapi_edition_id" name="eventapi_edition_id">';
		echo '<option value="">' . __( 'Please select a edition', DPG_EVENTAPI_SLUG ) . '</option>';
		foreach ( $editions as $edition ) {
			$selected = ( $current_edition_id === $edition->getId() ) ? 'selected="selected"' : '';
			echo '<option value="' . $edition->getId() . '" ' . $selected . '>' . $edition->getTitle() . '</option>';
		}
		echo '</select>';
	}
}
