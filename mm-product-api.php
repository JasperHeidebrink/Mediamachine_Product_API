<?php
/**
 * Mediamachine -  WordPress Stock API
 *
 * @package           MM_Product_API
 * Plugin Name:       Mediamachine Product Stock API Plugin
 * Plugin URI:        https://www.dpgmediamagazines.nl
 * Description:       This plugin will make it possible to connect a website to the filemaker database of Mediamachine
 * Version:           1.0.0
 * Author:            Dragonet
 * Author URI:        https://www.dragonet.nl
 * License:           GNU General Public License v2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.html
 * @copyright         2025 Mediamachine
 * Text Domain:       mm-product-api
 * Domain Path:       /languages
 */

namespace MMProductApi;

// If this file is called directly, abort.
use MMProductApi\Admin\Admin;

if (!defined('WPINC')) {
    die;
}

/**
 * Define the defaults
 */
//define('MM_API_VERSION', '1.0.0');
define('MM_API_VERSION', time());
define('MM_API_DOMAIN','mm-product-api');
define('MM_API_PATH', trailingslashit(plugin_dir_path(__FILE__)));
define('MM_API_DIR', trailingslashit(plugin_dir_url(__FILE__)));

/**
 * AutoLoad
 */
spl_autoload_register(
    static function ($class)
{
    if (class_exists($class)) {
        return;
    }
    $filepath = str_replace('\\', '/', $class).'.php';
    if (file_exists(__DIR__.'/'.$filepath)) {
        require_once(__DIR__.'/'.$filepath);
    }
});

if ( ! Admin::checkDependency() ) {
    return;
}

new Plugin();



//new Admin();
//if (is_admin()) {
//    new WooCommerce\Admin();
//    new Admin\EnqueueScripts();
//    new Admin\Activator();
//    new Admin\Cleanup();
//    new Admin\Menu();
//    new Admin\ResellerUser();
//    new Admin\Products();
//}


// If this file is called directly, abort.
//use DPG\WordPress\EventApi\Admin\FlashMessages;
//
//if (! defined('WPINC')) {
//    die;
//}
//
//define('DPG_EVENTAPI_PATH', trailingslashit(plugin_dir_path(__FILE__)));
//define('DPG_EVENTAPI_URL', trailingslashit(plugin_dir_url(__FILE__)));
//define('DPG_EVENTAPI_SLUG', 'dpg-wp-event-api');
//define('DPG_EVENTAPI_VERSION', '1.2.13');
//
//new Frontend();
//new Shortcode();
//new Admin();
//new FlashMessages();
//new Timber();
