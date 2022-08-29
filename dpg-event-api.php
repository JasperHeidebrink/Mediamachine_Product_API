<?php
/**
 * DPG Media Magazines -  WordPress EventApi
 *
 * PHP version 8.0
 *
 * @category  WordPress-plugin
 * @package   DPG_WP_EventApi
 * @author    DPG Media Magazines <wordpress_beheer.nl@dpgmediamagazines.nl>
 * @copyright 2022 DPG Media Magazines
 * @license   https://www.dpgmediamagazines.nl Closed
 * @version   GIT:1.2.4
 * @link      https://www.dpgmediamagazines.nl
 * @since     1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:       DPG Event API integration
 * Plugin URI:        https://www.dpgmediamagazines.nl
 * Description:       DPG plugin that implement the Event API to retrieve exhibitors and events
 * Version:           1.2.4
 * Author:            DPG Media Magazines
 * Author URI:        https://www.dpgmediamagazines.nl
 * License:           closed
 * License URI:       https://www.dpgmediamagazines.nl
 * Text Domain:       dpg-wp-eventapi
 * Domain Path:       /languages
 */

namespace DPG\WordPress\EventApi;

// If this file is called directly, abort.
use DPG\WordPress\EventApi\Admin\FlashMessages;

if (! defined('WPINC')) {
    die;
}

define('DPG_EVENTAPI_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('DPG_EVENTAPI_URL', trailingslashit(plugin_dir_url(__FILE__)));
define('DPG_EVENTAPI_SLUG', 'dpg-wp-event-api');
define('DPG_EVENTAPI_VERSION', '1.2.4');

new Frontend();
new Shortcode();
new Admin();
new FlashMessages();
new Timber();
