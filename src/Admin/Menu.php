<?php
/**
 * DPG Media Magazines -  WordPress EventApi
 *
 * @package   DPG_WP_EventApi
 */

namespace DPG\WordPress\EventApi\Admin;

use DPG\WordPress\EventApi\Api\Activities;

class Menu
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
            'DPG Event API',
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
    public function display_plugin_setup_page(): void
    {
        $context          = \Timber::context();
        $context['title'] = 'DPG Event API';

        \Timber::render('admin/options.twig', $context);
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function dropdown_events(): void
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
    public function dropdown_editions(): void
    {
        $options = get_option('eventapi_options_group');

        if (empty($options['event_id'])) {
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
