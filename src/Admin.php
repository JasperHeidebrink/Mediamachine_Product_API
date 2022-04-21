<?php
/**
 * DPG Media Magazines -  WordPress EventApi
 *
 * @package   DPG_WP_EventApi
 */

namespace DPG\WordPress\EventApi;

use DPG\WordPress\Temptation\Notices;

class Admin
{
    protected $parent_plugin_name = 'sm-main';

    public function __construct()
    {
        if (! is_admin()) {
            return;
        }

        if (! is_plugin_active("{$this->parent_plugin_name}/{$this->parent_plugin_name}.php")) {
            add_action('admin_notices', [$this, 'requirements_notices']);
        }

        add_action('admin_menu', [$this, 'add_event_page']);
        add_action('admin_init', [$this, 'register_settings']);
    }

    /**
     * @return void
     */
    public function requirements_notices(): void
    {
        echo '<div class="notice notice-warning is-dismissible"><p>';
        esc_html_e(
            'DPG Event API plugin is currently not working: plugin sm-main is needed. Please install or activate the plugin.',
            DPG_EVENTAPI_SLUG
        );
        echo '</p></div>';
    }

    /**
     * @return $this
     */
    public function add_event_page(): self
    {
        add_submenu_page(
            'sm-main',
            'DPG EventApi Setup',
            'DPG EventApi',
            'manage_options',
            $this->plugin_name,
            [
                $this,
                'display_plugin_setup_page',
            ]
        );

        return $this;
    }

    /**
     * Just simple handler of showing the admin template.
     *
     * @return void
     */
    public static function display_plugin_setup_page(): void
    {
        include_once DPG_EVENTAPI_PATH.'views/admin-options.php';
    }

    /**
     * @return void
     */
    public function register_settings()
    {
        register_setting(
            'eventapi_options_group',                       /
            'eventapi_options_group',                       /
            [$this, 'eventapi_options_validate']
        );

        add_settings_section(
            'eventapi_settings',
            __('Event settings', DPG_EVENTAPI_SLUG),
            [$this, 'print_section_info'],
            'eventapi_settings_admin'
        );

        add_settings_field(
            'event_id',
            __('Select your event', DPG_EVENTAPI_SLUG),
            [$this, 'dropdown_events'],
            'eventapi_settings_admin',
            'eventapi_settings'
        );

        add_settings_field(
            'edition_id',
            __('Select your edition', DPG_EVENTAPI_SLUG),
            [$this, 'dropdown_editions'],
            'eventapi_settings_admin',
            'eventapi_settings'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function event_options_validate($input)
    {
        if (isset($input['event_id'])) {
            $input['event_id'] = absint($input['event_id']);
        }

        if (isset($input['edition_id'])) {
            $input['edition_id'] = absint($input['edition_id']);
        }

        return $input;
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function dropdown_events()
    {
        $events  = [
            'id'    => 0,
            'title' => 'example event',
        ];
        $options = get_option('eventapi_options_group');

        echo '<select id="event_id" name="eventapi_options_group[event_id]">';
        echo '<option value="">'.__('Please select a event', DPG_EVENTAPI_SLUG).'</option>';

        foreach ($events as $event) {
            $selected = ($options['event_id'] === $event['id']) ? 'selected="selected"' : '';
            echo '<option value="'.$event['id'].'" '.$selected.'>'.$event['title'].'</option>';
        }
        echo '</select>';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function dropdown_editions()
    {
        $options = get_option('eventapi_options_group');

        if (empty($options['event_id']) {
            return __('First you have to select a event', DPG_EVENTAPI_SLUG);
        }

        $editions = [
            'id'    => 0,
            'title' => 'example event',
        ];
        echo '<select id="edition_id" name="eventapi_options_group[edition_id]">';
        echo '<option value="">'.__('Please select a edition', DPG_EVENTAPI_SLUG).'</option>';
        foreach ($editions as $key => $edition) {
            $selected = ($options['edition_id'] === $edition['id']) ? 'selected="selected"' : '';
            echo '<option value="'.$edition['id'].'" '.$selected.'>'.$edition['title'].'</option>';
        }
        echo '</select>';
    }
}
